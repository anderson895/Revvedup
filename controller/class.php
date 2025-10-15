
<?php


include ('config.php');

date_default_timezone_set('Asia/Manila');

class global_class extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }


    public function update_business($name, $address, $contact) {
        $sql = "UPDATE business_details 
                SET business_name=?, business_address=?, business_contact_num=? 
                WHERE business_id=1"; 
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $name, $address, $contact);
        return $stmt->execute() ? "success" : "error";
    }

    public function update_basic($user_id, $firstname, $lastname, $username, $email) {
        $sql = "UPDATE user 
                SET firstname=?, lastname=?, username=?, email=? 
                WHERE user_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $firstname, $lastname, $username, $email, $user_id);
        return $stmt->execute() ? "success" : "error";
    }

    public function update_password($user_id, $old_pass, $new_pass) {
        $stmt = $this->conn->prepare("SELECT password FROM user WHERE user_id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($hashed);
        $stmt->fetch();
        $stmt->close();

        if (!password_verify($old_pass, $hashed)) {
            return "Current password is incorrect.";
        }

        $newHash = password_hash($new_pass, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("UPDATE user SET password=? WHERE user_id=?");
        $stmt->bind_param("si", $newHash, $user_id);
        return $stmt->execute() ? "success" : "error";
    }

    public function update_pin($user_id, $pin) {
        // Check kung may existing na gumagamit ng same PIN maliban sa kasalukuyang user
        $stmt = $this->conn->prepare("SELECT user_id FROM user WHERE pin=? AND user_id!=?");
        $stmt->bind_param("si", $pin, $user_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            return "PIN already exists."; // ❌ Duplicate PIN
        }
        $stmt->close();

        // Kung wala pang gumagamit, proceed update
        $stmt = $this->conn->prepare("UPDATE user SET pin=? WHERE user_id=?");
        $stmt->bind_param("ii", $pin, $user_id);
        return $stmt->execute() ? "success" : "error";
    }


 // ✅ Fetch user account
    public function check_account($id) {
        $id = intval($id);
        $query = "SELECT * FROM `user` WHERE user_id= $id AND `status` = 1";

        $result = $this->conn->query($query);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc(); 
        }
        return null;
    }



 public function complete_transaction($transactionId, $refundData, $exchangeData) {
    // Fetch transaction date
    $stmt = $this->conn->prepare("SELECT transaction_date FROM `transaction` WHERE transaction_id = ?");
    $stmt->bind_param("i", $transactionId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return ["status" => false, "message" => "Transaction not found"];
    }

    $transaction = $result->fetch_assoc();
    $transactionDate = strtotime($transaction['transaction_date']);
    $currentDate = strtotime(date("Y-m-d H:i:s"));

    // Check if transaction is within 7 days
    $diffDays = ($currentDate - $transactionDate) / (60 * 60 * 24);
    if ($diffDays > 7) {
        return ["status" => false, "message" => "Refund/Exchange period has expired"];
    }

    // Process refund
    foreach ($refundData as $item) {
        $item['type'] = 'refund'; // add type
        $returnItemJson = $this->conn->real_escape_string(json_encode([$item]));
        $returnQty = intval($item['qty']);
        $transactionIdInt = intval($transactionId);

        $sql = "INSERT INTO returns (return_transaction_item, return_qty, return_transaction_id) 
                VALUES ('$returnItemJson', $returnQty, $transactionIdInt)";
        $this->conn->query($sql); 
    }

    // Process exchange
    foreach ($exchangeData as $item) {
        $item['type'] = 'exchange'; // add type
        $exchangeJson = $this->conn->real_escape_string(json_encode([$item]));
        $itemQty = intval($item['qty']);
        $transactionIdInt = intval($transactionId);

        $sql = "INSERT INTO returns (return_transaction_item, return_qty, return_transaction_id) 
                VALUES ('$exchangeJson', $itemQty, $transactionIdInt)";
        $this->conn->query($sql);
    }

    return ["status" => true, "message" => "Transaction completed successfully"];
}




public function fetch_transaction_record($transactionId) {
    // Fetch transaction
    $sql = "SELECT * FROM `transaction` WHERE transaction_status = 1 AND transaction_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $transactionId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $transaction = $result->fetch_assoc();
        $transaction['transaction_service'] = json_decode($transaction['transaction_service'], true);
        $transactionItems = json_decode($transaction['transaction_item'], true);

        // Fetch refunds & exchanges
        $sql2 = "SELECT * FROM `returns` WHERE return_transaction_id = ?";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->bind_param("i", $transactionId);
        $stmt2->execute();
        $res2 = $stmt2->get_result();

        $returns = [];
        while ($row = $res2->fetch_assoc()) {
            $item = json_decode($row['return_transaction_item'], true);
            $item[0]['qty'] = intval($row['return_qty']);
            $returns[] = $item[0];

            // Subtract returned/exchanged qty from original transaction items
            foreach ($transactionItems as &$tItem) {
                if ($tItem['name'] == $item[0]['name']) {
                    $tItem['qty'] = max(0, intval($tItem['qty']) - $item[0]['qty']);
                }
            }
        }

        $transaction['transaction_item'] = $transactionItems; 
        $transaction['returns'] = $returns; 

        return $transaction;
    } else {
        return null;
    }
}

public function fetch_analytics($scope = "weekly") {
    $conn = $this->conn;
    $analytics = [];

    $sql = "SELECT transaction_id, transaction_date, transaction_item
            FROM transaction
            WHERE transaction_status = 1
            ORDER BY transaction_date ASC";

    $result = mysqli_query($conn, $sql);
    if (!$result) return [];

    while ($row = mysqli_fetch_assoc($result)) {
        $transactionId = intval($row['transaction_id']);
        $date = strtotime($row['transaction_date']);

        // Determine label
        if ($scope === "weekly") {
            $year = date("o", $date);
            $week = date("W", $date);
            $label = "Week {$week}, {$year}";
        } elseif ($scope === "monthly") {
            $label = date("M Y", $date);
        } else {
            $label = date("Y-m-d", $date);
        }

        $items = json_decode($row['transaction_item'], true);
        if (!is_array($items) || empty($items)) continue; // skip empty

        // Fetch returns
        $sqlReturns = "SELECT return_transaction_item, return_qty 
                       FROM `returns` 
                       WHERE return_transaction_id = $transactionId";
        $resReturns = mysqli_query($conn, $sqlReturns);

        $refundMap = [];
        while ($ret = mysqli_fetch_assoc($resReturns)) {
            $retItem = json_decode($ret['return_transaction_item'], true);
            if (isset($retItem[0]['name'], $retItem[0]['type']) && $retItem[0]['type'] === 'refund') {
                $refundMap[$retItem[0]['name']] = intval($ret['return_qty']);
            }
        }

        foreach ($items as $it) {
            $name = $it['name'];
            $originalQty = intval($it['qty']);
            $qty = $originalQty;

            if (isset($refundMap[$name])) {
                $qty = max(0, $qty - $refundMap[$name]);
            }

            $subtotal = (float)$it['subtotal'];
            $capital = (float)$it['capital'];
            $netSubtotal = $subtotal * ($qty / max(1, $originalQty));
            $capitalTotal = $capital * $qty;
            $revenue = $netSubtotal - $capitalTotal;

            // Skip items with zero subtotal
            if ($netSubtotal <= 0) continue;

            if (!isset($analytics[$label])) {
                $analytics[$label] = [
                    "label" => $label,
                    "total_sales" => 0,
                    "capital_total" => 0,
                    "revenue" => 0
                ];
            }

            $analytics[$label]['total_sales'] += $netSubtotal;
            $analytics[$label]['capital_total'] += $capitalTotal;
            $analytics[$label]['revenue'] += $revenue;
        }
    }

    return array_values($analytics);
}






public function fetch_months_with_sales() {
    $conn = $this->conn;
    $sql = "SELECT DISTINCT YEAR(transaction_date) as year, MONTH(transaction_date) as month,
                   DATE_FORMAT(transaction_date, '%M %Y') as label
            FROM transaction
            WHERE transaction_status = 1
            ORDER BY transaction_date ASC";
    $result = mysqli_query($conn, $sql);
    $months = [];

    if($result && mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $months[] = $row;
        }
    }

    return $months;
}







    
public function fetch_transaction_by_id($transactionId) {
    $query = $this->conn->prepare("
        SELECT *
        FROM `transaction`
        LEFT JOIN user
        ON user.user_id = transaction.transaction_by
        WHERE transaction_id = ? AND transaction_status = '1'
        LIMIT 1
    ");
    $query->bind_param("i", $transactionId);

    if ($query->execute()) {
        $result = $query->get_result();

        if ($result->num_rows === 0) {
            return null; // walang transaction
        }

        $row = $result->fetch_assoc();

        // Collect user_ids from services
        $services = json_decode($row['transaction_service'], true) ?? [];
        $empIds = [];
        foreach ($services as $s) {
            if (!empty($s['user_id'])) {
                $empIds[] = (int)$s['user_id'];
            }
        }

        // Fetch employees
        $employees = [];
        if (!empty($empIds)) {
            $ids = implode(',', array_unique($empIds));
            $empQuery = $this->conn->prepare("SELECT user_id, firstname, lastname FROM user WHERE user_id IN ($ids)");
            $empQuery->execute();
            $empRes = $empQuery->get_result();
            while ($emp = $empRes->fetch_assoc()) {
                $employees[$emp['user_id']] = $emp['firstname'].' '.$emp['lastname'];
            }
        }

        // Merge user names into services
        foreach ($services as &$s) {
            $id = (int)$s['user_id'];
            $s['employee_name'] = $employees[$id] ?? "Unknown user #$id";
        }

        // Decode items as array din
        $items = json_decode($row['transaction_item'], true) ?? [];

        // I-return as array na may ready to use services & items
        return [
            "transaction" => $row,
            "services"    => $services,
            "items"       => $items
        ];
    }

    return null;
}




 public function Login_admin($username, $password)
{
    $query = $this->conn->prepare("SELECT * FROM `user` WHERE `username` = ?");
    $query->bind_param("s", $username);

    if ($query->execute()) {
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // 🔍 Check if inactive
                if ($user['status'] == 0) {
                    $query->close();
                    return [
                        'success' => false,
                        'message' => 'Your account is not active.'
                    ];
                }

                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username']; 

                $query->close();
                return [
                    'success' => true,
                    'message' => 'Login successful.',
                    'data' => [
                        'user_id' => $user['user_id'],
                        'position' => $user['position'], 
                    ]
                ];
            } else {
                $query->close();
                return ['success' => false, 'message' => 'Incorrect password.'];
            }
        } else {
            $query->close();
            return ['success' => false, 'message' => 'User not found.'];
        }
    } else {
        $query->close();
        return ['success' => false, 'message' => 'Database error during execution.'];
    }
}




public function Login_employee($pin)
{
    $query = $this->conn->prepare("SELECT * FROM `user` WHERE `pin` = ?");
    $query->bind_param("s", $pin);

    if ($query->execute()) {
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // 🔍 Check if inactive
            if ($user['status'] == 0) {
                $query->close();
                return [
                    'success' => false,
                    'message' => 'Your account is not active.'
                ];
            }

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username']; 

            $query->close();
            return [
                'success' => true,
                'message' => 'Login successful.',
                'data' => [
                    'user_id' => $user['user_id'],
                    'position' => $user['position'], 
                ]
            ];
        } else {
            $query->close();
            return ['success' => false, 'message' => 'User not found.'];
        }
    } else {
        $query->close();
        return ['success' => false, 'message' => 'Database error during execution.'];
    }
}



public function AddServiceCart($itemName,$price,$user,$user_id) {
    $query = "INSERT INTO `service_cart` (`service_name`, `service_price`, `service_employee_id`,`service_user_id`) 
              VALUES (?, ?, ?,?)";

    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        return false; 
    }
    $stmt->bind_param("sdii", $itemName, $price, $user,$user_id);

    $result = $stmt->execute();

    if (!$result) {
        $stmt->close();
        return false;
    }

    $inserted_id = $this->conn->insert_id;
    $stmt->close();

    return $inserted_id;
}









public function updateServiceCart($serviceName, $price, $user, $user_id, $service_id)
{
    $query = "UPDATE `service_cart` 
              SET service_name = ?, 
                  service_price = ?, 
                  service_employee_id = ?, 
                  service_user_id = ?
              WHERE service_id = ?";

    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        return false; // prepare failed
    }

    $stmt->bind_param("sdiii", $serviceName, $price, $user, $user_id, $service_id);

    $result = $stmt->execute();
    $stmt->close();

    return $result; // true if updated, false if failed
}




public function AddToItem($selectedProductId,$modalProdQty,$user_id){
    $query = "INSERT INTO `item_cart` (`item_prod_id`, `item_qty`,`item_user_id`) 
              VALUES (?, ?, ?)";

    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        return false; 
    }
    $stmt->bind_param("iii", $selectedProductId, $modalProdQty,$user_id);

    $result = $stmt->execute();

    if (!$result) {
        $stmt->close();
        return false;
    }

    $inserted_id = $this->conn->insert_id;
    $stmt->close();

    return $inserted_id;
}


// Fetch all users with position 'user', without password or pin
public function fetch_all_users() {
    $sql = "SELECT user.*
            FROM user 
            WHERE position = 'employee'
            ORDER BY user_id DESC";
    $result = $this->conn->query($sql);

    $users = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}


// Add user (firstname, lastname, username, email, pin)
// Add user without username
public function add_user($firstname, $lastname, $email, $pin) {
    $stmt = $this->conn->prepare("INSERT INTO user (firstname, lastname, email, pin) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $firstname, $lastname, $email, $pin);
    return $stmt->execute() ? "User added successfully" : "Error adding user";
}

// Update user without username
public function update_user($user_id, $firstname, $lastname, $email, $pin = null) {
    $stmt = $this->conn->prepare("UPDATE user SET firstname=?, lastname=?, email=?, pin=? WHERE user_id=?");
    $stmt->bind_param("ssssi", $firstname, $lastname, $email, $pin, $user_id);
    return $stmt->execute() ? "User updated successfully" : "Error updating user";
}



// Deactivate user (unchanged)
public function removeUser($user_id) {
    $stmt = $this->conn->prepare("UPDATE user SET status=0 WHERE user_id=?");
    $stmt->bind_param("i", $user_id);
    return $stmt->execute() ? "User deactivated successfully" : "Error deactivating user";
}




public function restore_user($user_id) {
    $stmt = $this->conn->prepare("UPDATE user SET status=1 WHERE user_id=?");
    $stmt->bind_param("i", $user_id);
    return $stmt->execute() ? "User restored successfully" : "Error restoring user";
}




public function fetch_all_product() {
    // Step 1: Fetch all active products
    $query = $this->conn->prepare("SELECT * FROM product WHERE prod_status = 1 ORDER BY prod_id DESC");
    
    if (!$query) {
        error_log("Prepare failed: " . $this->conn->error);
        return [];
    }

    if (!$query->execute()) {
        error_log("Execute failed: " . $query->error);
        return [];
    }

    $result = $query->get_result();
    $products = [];

    while ($row = $result->fetch_assoc()) {
        $row['total_sold_week'] = 0;  
        $row['movement'] = 'Not moving'; 
        $products[$row['prod_id']] = $row;
    }

    // Step 2: Fetch all active transactions in the last 7 days
    $query2 = $this->conn->prepare("SELECT transaction_item FROM transaction 
                                    WHERE transaction_status = 1 
                                    AND transaction_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    if ($query2) {
        $query2->execute();
        $result2 = $query2->get_result();

        while ($row = $result2->fetch_assoc()) {
            $items = json_decode($row['transaction_item'], true);
            if (is_array($items)) {
                foreach ($items as $item) {
                    $prod_id = $item['prod_id'];
                    $qty = (int)$item['qty'];
                    if (isset($products[$prod_id])) {
                        $products[$prod_id]['total_sold_week'] += $qty;
                    }
                }
            }
        }
    }

    // Step 3: Assign movement category
    foreach ($products as &$prod) {
        if ($prod['total_sold_week'] == 0) {
            $prod['movement'] = 'Not moving';
        } elseif ($prod['total_sold_week'] <= 9) {
            $prod['movement'] = 'Slow moving';
        } else {
            $prod['movement'] = 'Fast moving';
        }
    }

    return array_values($products); // Re-index the array
}










public function fetch_archived_product() {
    // Step 1: Fetch all active products
    $query = $this->conn->prepare("SELECT * FROM product WHERE prod_status = 0 ORDER BY prod_id DESC");
    
    if (!$query) {
        error_log("Prepare failed: " . $this->conn->error);
        return [];
    }

    if (!$query->execute()) {
        error_log("Execute failed: " . $query->error);
        return [];
    }

    $result = $query->get_result();
    $products = [];

    while ($row = $result->fetch_assoc()) {
        $row['total_sold_week'] = 0;  
        $row['movement'] = 'Not moving'; 
        $products[$row['prod_id']] = $row;
    }

    // Step 2: Fetch all active transactions in the last 7 days
    $query2 = $this->conn->prepare("SELECT transaction_item FROM transaction 
                                    WHERE transaction_status = 1 
                                    AND transaction_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    if ($query2) {
        $query2->execute();
        $result2 = $query2->get_result();

        while ($row = $result2->fetch_assoc()) {
            $items = json_decode($row['transaction_item'], true);
            if (is_array($items)) {
                foreach ($items as $item) {
                    $prod_id = $item['prod_id'];
                    $qty = (int)$item['qty'];
                    if (isset($products[$prod_id])) {
                        $products[$prod_id]['total_sold_week'] += $qty;
                    }
                }
            }
        }
    }

    // Step 3: Assign movement category
    foreach ($products as &$prod) {
        if ($prod['total_sold_week'] == 0) {
            $prod['movement'] = 'Not moving';
        } elseif ($prod['total_sold_week'] <= 9) {
            $prod['movement'] = 'Slow moving';
        } else {
            $prod['movement'] = 'Fast moving';
        }
    }

    return array_values($products); // Re-index the array
}










public function fetch_all_transaction($limit = 10, $offset = 0, $filter = "") {
    $sql = "
        SELECT *
        FROM `transaction`
        WHERE transaction_status='1'
    ";

    $params = [];
    if(!empty($filter)) {
        $sql .= " AND transaction_id LIKE ?";
        $params[] = "%$filter%";
    }

    $sql .= " ORDER BY transaction_id DESC";
    
    $query = $this->conn->prepare($sql);

    if($query->execute($params)) {
        $result = $query->get_result();
        $allData = [];
        $empIds = [];

        while($row = $result->fetch_assoc()) {
            $services = json_decode($row['transaction_service'], true) ?? [];
            foreach($services as $s) {
                if(!empty($s['user_id'])) $empIds[] = (int)$s['user_id'];
            }
            $allData[] = $row;
        }

        // Fetch user names
        $employees = [];
        if(!empty($empIds)) {
            $ids = implode(',', array_unique($empIds));
            $empQuery = $this->conn->prepare("SELECT user_id, firstname, lastname FROM user WHERE user_id IN ($ids)");
            $empQuery->execute();
            $empRes = $empQuery->get_result();
            while($emp = $empRes->fetch_assoc()) {
                $employees[$emp['user_id']] = $emp['firstname'].' '.$emp['lastname'];
            }
        }

        // Merge user names
        foreach($allData as &$row) {
            $services = json_decode($row['transaction_service'], true) ?? [];
            foreach($services as &$s) {
                $id = (int)$s['user_id'];
                $s['employee_name'] = $employees[$id] ?? "Unknown user #$id";
            }
            $row['transaction_service'] = json_encode($services);
        }

        // Apply pagination AFTER merging employees
        $paginatedData = array_slice($allData, $offset, $limit);

        return $paginatedData;
    }

    return [];
}








public function fetch_all_transaction_with_return($limit = 10, $offset = 0, $filter = "") {
    $sql = "
        SELECT *
        FROM `transaction`
        WHERE transaction_status='1'
    ";

    $params = [];
    if(!empty($filter)) {
        $sql .= " AND transaction_id LIKE ?";
        $params[] = "%$filter%";
    }

    $sql .= " ORDER BY transaction_id DESC";
    $query = $this->conn->prepare($sql);

    if($query->execute($params)) {
        $result = $query->get_result();
        $allData = [];
        $empIds = [];

        while($row = $result->fetch_assoc()) {
            // Decode services and collect user_ids
            $services = json_decode($row['transaction_service'], true) ?? [];
            foreach($services as $s) {
                if(!empty($s['user_id'])) $empIds[] = (int)$s['user_id'];
            }

            // Decode transaction items
            $transactionItems = json_decode($row['transaction_item'], true) ?? [];

            // Fetch returns for this transaction
            $returnsQuery = $this->conn->prepare("SELECT * FROM `returns` WHERE return_transaction_id = ?");
            $returnsQuery->bind_param("i", $row['transaction_id']);
            $returnsQuery->execute();
            $returnsRes = $returnsQuery->get_result();
            while ($ret = $returnsRes->fetch_assoc()) {
                $returnItems = json_decode($ret['return_transaction_item'], true) ?? [];
                foreach ($returnItems as $rItem) {
                    foreach ($transactionItems as &$tItem) {
                        if ($tItem['prod_id'] == $rItem['prod_id']) {
                            $tItem['qty'] = max(0, intval($tItem['qty']) - intval($ret['return_qty']));
                        }
                    }
                }
            }

            // Filter out items with qty 0
            $transactionItems = array_filter($transactionItems, fn($i) => intval($i['qty']) > 0);
            $row['transaction_item'] = json_encode(array_values($transactionItems));

            // Check refundable: within 7 days and items remain
            $transactionDate = new DateTime($row['transaction_date']);
            $today = new DateTime();
            $intervalDays = $today->diff($transactionDate)->days;

            if (empty($transactionItems)) {
                $row['refundable'] = false;
                $row['message'] = "No refundable items.";
            } elseif ($intervalDays > 7) {
                $row['refundable'] = false;
                $row['message'] = "Transaction is beyond refundable period.";
            } else {
                $row['refundable'] = true;
                $row['message'] = "Items are refundable.";
            }

            $allData[] = $row;
        }

        // Fetch employee names
        $employees = [];
        if(!empty($empIds)) {
            $ids = implode(',', array_unique($empIds));
            $empQuery = $this->conn->prepare("SELECT user_id, firstname, lastname FROM user WHERE user_id IN ($ids)");
            $empQuery->execute();
            $empRes = $empQuery->get_result();
            while($emp = $empRes->fetch_assoc()) {
                $employees[$emp['user_id']] = $emp['firstname'].' '.$emp['lastname'];
            }
        }

        // Merge employee names
        foreach($allData as &$row) {
            $services = json_decode($row['transaction_service'], true) ?? [];
            foreach($services as &$s) {
                $id = (int)$s['user_id'];
                $s['employee_name'] = $employees[$id] ?? "Unknown user #$id";
            }
            $row['transaction_service'] = json_encode($services);
        }

        // Apply pagination AFTER merging employees
        $paginatedData = array_slice($allData, $offset, $limit);

        return $paginatedData;
    }

    return [];
}







public function count_transactions($filter = "") {
    $sql = "SELECT COUNT(*) as total FROM `transaction` WHERE transaction_status='1'";
    $params = [];
    if(!empty($filter)) {
        $sql .= " AND transaction_id LIKE ?";
        $params[] = "%$filter%";
    }
    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);
    $res = $stmt->get_result()->fetch_assoc();
    return $res['total'] ?? 0;
}






























public function UpdateProduct(
    $productId,
    $itemName,
    $capital,
    $price,
    $stockQty,
    $category,
    $uniqueBannerFileName = null,
    $description
){
    // Delete old image if new one is provided
    if (!empty($uniqueBannerFileName)) {
        $stmt = $this->conn->prepare("SELECT prod_img FROM product WHERE prod_id = ?");
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        $stmt->bind_result($oldImg);
        $stmt->fetch();
        $stmt->close();

        if (!empty($oldImg)) {
            $oldPath = "../../static/upload/" . $oldImg;
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
    }

    // Build query
    $query = "UPDATE product 
              SET prod_name = ?, prod_capital = ?, prod_price = ?, prod_qty = ?, prod_category = ?,prod_description=?";
    $types = "sddiss"; // s = string, d = double, i = integer
    $params = [$itemName, $capital, $price, $stockQty, $category,$description];

    if (!empty($uniqueBannerFileName)) {
        $query .= ", prod_img = ?";
        $types .= "s";
        $params[] = $uniqueBannerFileName;
    }

    $query .= " WHERE prod_id = ?";
    $types .= "s";
    $params[] = $productId;

    // Prepare and execute
    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        return ['status' => false, 'message' => 'Prepare failed: ' . $this->conn->error];
    }

    $stmt->bind_param($types, ...$params);

    if (!$stmt->execute()) {
        $stmt->close();
        return ['status' => false, 'message' => 'Execution failed: ' . $stmt->error];
    }

    $stmt->close();

    return ['status' => true, 'message' => 'Product updated successfully.'];
}








public function removeProduct($prod_id) {
       
        $deleteQuery = "UPDATE product SET prod_status = 0 WHERE prod_id  = ?";
        $stmt = $this->conn->prepare($deleteQuery);
        if (!$stmt) {
            return 'Prepare failed (update): ' . $this->conn->error;
        }

        $stmt->bind_param("i", $prod_id);
        $result = $stmt->execute();
        $stmt->close();

        return $result ? 'success' : 'Error updating menu';
    }









    public function restoreProduct($prod_id) {
       
        $deleteQuery = "UPDATE product SET prod_status = 1 WHERE prod_id  = ?";
        $stmt = $this->conn->prepare($deleteQuery);
        if (!$stmt) {
            return 'Prepare failed (update): ' . $this->conn->error;
        }

        $stmt->bind_param("i", $prod_id);
        $result = $stmt->execute();
        $stmt->close();

        return $result ? 'success' : 'Error updating menu';
    }





    public function deleteCart($id,$table,$collumn) {
       
        $deleteQuery = "DELETE FROM `$table` WHERE $collumn  = ?";
        $stmt = $this->conn->prepare($deleteQuery);
        if (!$stmt) {
            return 'Prepare failed (update): ' . $this->conn->error;
        }

        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();

        return $result ? 'success' : 'Error updating menu';
    }





    public function VoidCart($user_id) {
       
        $deleteQuery = "DELETE FROM `item_cart` WHERE item_user_id  = ?";
        $stmt = $this->conn->prepare($deleteQuery);
        if (!$stmt) {
            return 'Prepare failed (update): ' . $this->conn->error;
        }

        $stmt->bind_param("i", $user_id);
        $result = $stmt->execute();
        $stmt->close();

        return $result ? 'success' : 'Error updating menu';
    }





    
    public function fetch_all_employee() {
        $query = $this->conn->prepare("SELECT * FROM user
            where status='1'
            ORDER BY user_id DESC");

            if ($query->execute()) {
                $result = $query->get_result();
                $data = [];

                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                return $data;
            }
            return []; 
    }




    public function fetch_all_service_cart($user_id)
    {   
            $query = $this->conn->prepare("
                SELECT * FROM service_cart
                LEFT JOIN user
                ON user.user_id  = service_cart.service_employee_id 
                WHERE service_user_id = ?
                ORDER BY service_id DESC
            ");

            $query->bind_param("i", $user_id);

            if ($query->execute()) {
                $result = $query->get_result();
                $data = [];

                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                return $data;
            }

            return [];
    }








    public function fetch_all_item_cart($user_id)
    {   
            $query = $this->conn->prepare("
                SELECT * FROM item_cart
                LEFT JOIN product
                ON product.prod_id = item_cart.item_prod_id 
                WHERE item_user_id = ?
                ORDER BY item_id DESC
            ");

            $query->bind_param("i", $user_id);

            if ($query->execute()) {
                $result = $query->get_result();
                $data = [];

                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                return $data;
            }

            return [];
    }




    public function fetch_total_cart($user_id)
    {
        $total = 0;

        // Fetch service cart
        $services = $this->fetch_all_service_cart($user_id);
        foreach ($services as $service) {
            $total += floatval($service['service_price']);
        }

        // Fetch item cart
        $items = $this->fetch_all_item_cart($user_id);
        foreach ($items as $item) {
            $total += floatval($item['prod_price']) * intval($item['item_qty']); 
        }

        return $total;
    }


    public function fetch_all_cart($user_id)
    {
        // Fetch services
        $services = $this->fetch_all_service_cart($user_id);

        // Fetch items
        $items = $this->fetch_all_item_cart($user_id);

        return [
            'services' => $services,
            'items' => $items
        ];
    }





    public function AddProduct($itemName, $capital, $price, $stockQty, $itemImageFileName, $category,$description) {
        $query = "INSERT INTO `product` 
                (`prod_name`, `prod_capital`, `prod_price`, `prod_qty`, `prod_img`, `prod_category`,`prod_description`) 
                VALUES (?,?,?,?,?,?,?)";

        $stmt = $this->conn->prepare($query);

        // s = string, d = double, i = integer
        $stmt->bind_param("sddisss", $itemName, $capital, $price, $stockQty, $itemImageFileName, $category,$description);

        $result = $stmt->execute();

        if (!$result) {
            $stmt->close();
            return false;
        }

        $inserted_id = $this->conn->insert_id;
        $stmt->close();

        return $inserted_id;
    }




    public function getServiceById($service_id)
    {
            $query = $this->conn->prepare("
                SELECT sc.*, e.firstname, e.lastname 
                FROM service_cart sc
                LEFT JOIN user e ON e.user_id = sc.service_employee_id
                WHERE sc.service_id = ?
                LIMIT 1
            ");

            $query->bind_param("i", $service_id);

            if ($query->execute()) {
                $result = $query->get_result();
                if ($row = $result->fetch_assoc()) {
                    return $row; 
                }
            }

            return null; 
    }




    public function getItemById($item_id)
    {
        $query = $this->conn->prepare("
            SELECT ic.*, p.prod_name, p.prod_price, p.prod_img,p.prod_id  
            FROM item_cart ic
            LEFT JOIN product p ON p.prod_id = ic.item_prod_id
            WHERE ic.item_id = ?
            LIMIT 1
        ");

        $query->bind_param("i", $item_id);

        if ($query->execute()) {
            $result = $query->get_result();
            if ($row = $result->fetch_assoc()) {
                return $row; // return single item
            }
        }

        return null; // not found
    }




    
public function CheckOutOrder($services, $items, $discount, $vat, $grandTotal, $payment, $change, &$errorMsg = null,$user_id) {
    $services_json = json_encode($services);
    $items_json = json_encode($items);

    $this->conn->begin_transaction();

    try {
        // 1️⃣ Insert transaction
        $sql = "INSERT INTO `transaction` 
                (transaction_service, transaction_item, transaction_discount, transaction_vat, transaction_total, transaction_payment, transaction_change, transaction_status,transaction_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 1,?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed: " . $this->conn->error);

        $stmt->bind_param("ssdddddi", $services_json, $items_json, $discount, $vat, $grandTotal, $payment, $change,$user_id);
        if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);

      // ✅ Get last inserted transaction_id
        $transactionId = $this->conn->insert_id;
        $stmt->close();

      


        // 2️⃣ Deduct stock
        foreach ($items as $item) {
            if (!isset($item['prod_id'], $item['qty'])) continue;

            $prod_id = (int)$item['prod_id'];
            $qty = (int)$item['qty'];

            // Check stock
            $check = $this->conn->prepare("SELECT prod_qty FROM product WHERE prod_id = ? FOR UPDATE");
            $check->bind_param("i", $prod_id);
            $check->execute();
            $check->bind_result($current_qty);
            if (!$check->fetch()) {
                $check->close();
                throw new Exception("Product ID {$prod_id} not found");
            }
            $check->close();

            if ($current_qty < $qty) {
                throw new Exception("Insufficient stock for product ID {$prod_id}");
            }

            // Update stock
            $update = $this->conn->prepare("UPDATE product SET prod_qty = prod_qty - ? WHERE prod_id = ?");
            $update->bind_param("ii", $qty, $prod_id);
            if (!$update->execute()) {
                $update->close();
                throw new Exception("Failed to update stock for product ID {$prod_id}");
            }
            $update->close();
        }

        // 3️⃣ Delete from service_cart
        foreach ($services as $s) {
            if (isset($s['service_id'])) {
                $del = $this->conn->prepare("DELETE FROM service_cart WHERE service_id = ?");
                $del->bind_param("i", $s['service_id']);
                if (!$del->execute()) {
                    $del->close();
                    throw new Exception("Failed to delete service_cart item ID {$s['service_id']}");
                }
                $del->close();
            }
        }

        // 4️⃣ Delete from item_cart based on user_id and prod_id
            foreach ($items as $i) {
                if (!isset($i['prod_id'])) continue;

                $prod_id = (int)$i['prod_id'];

                $del = $this->conn->prepare(
                    "DELETE FROM item_cart WHERE item_user_id = ? AND item_prod_id = ?"
                );
                $del->bind_param("ii", $user_id, $prod_id);
                if (!$del->execute()) {
                    $del->close();
                    throw new Exception("Failed to delete item_cart for product ID {$prod_id}");
                }
                $del->close();
            }



        // 5️⃣ Commit transaction
        $this->conn->commit();
        return $transactionId; // ✅ return new transaction_id

    } catch (Exception $e) {
        $this->conn->rollback();
        $errorMsg = $e->getMessage();
        return false;
    }
}







    // public function fetch_all_employee_record() {
    //     $query = $this->conn->prepare("
    //         SELECT transaction_id, transaction_date, transaction_service 
    //         FROM transaction 
    //         WHERE transaction_status = 1
    //     ");
    //     $query->execute();
    //     $result = $query->get_result();

    //     $employees = [];

    //     while ($row = $result->fetch_assoc()) {
    //         $date = new DateTime($row['transaction_date']);
    //         $dayOfWeek = $date->format('N'); // 1=Mon ... 7=Sun
    //         $month = $date->format('F');
    //         $year = $date->format('Y');

    //         $services = json_decode($row['transaction_service'], true);

    //         if (!empty($services)) {
    //             foreach ($services as $svc) {
    //                 $empId = isset($svc['user_id']) ? intval($svc['user_id']) : 0;
    //                 $price = isset($svc['price']) ? floatval($svc['price']) : 0;

    //                 // ✅ Fetch user name from DB instead of JSON
    //                 $empName = "Unknown";
    //                 if ($empId > 0) {
    //                     $stmtEmp = $this->conn->prepare("SELECT CONCAT(firstname, ' ', lastname) AS fullname 
    //                                                     FROM user 
    //                                                     WHERE user_id = ?");
    //                     $stmtEmp->bind_param("i", $empId);
    //                     $stmtEmp->execute();
    //                     $stmtEmp->bind_result($fullname);
    //                     if ($stmtEmp->fetch()) {
    //                         $empName = $fullname;
    //                     }
    //                     $stmtEmp->close();
    //                 }

    //                 // ✅ Initialize user record if not exists
    //                 if (!isset($employees[$empId])) {
    //                     $employees[$empId] = [
    //                         "id" => $empId,
    //                         "name" => $empName,
    //                         "days" => array_fill(1, 7, 0), // Mon–Sun
    //                         "commission" => 0,
    //                         "deductions" => 0,
    //                         "months" => []
    //                     ];
    //                 }

    //                 // ✅ Add commission & day
    //                 $employees[$empId]["days"][$dayOfWeek] += $price;
    //                 $employees[$empId]["commission"] += $price;

    //                 // ✅ Group by month
    //                 if (!isset($employees[$empId]["months"][$month])) {
    //                     $employees[$empId]["months"][$month] = 0;
    //                 }
    //                 $employees[$empId]["months"][$month] += $price;
    //             }
    //         }
    //     }

    //     return array_values($employees); // return as indexed array
    // }

  public function fetch_all_employee_record($filterMonth = null, $filterYear = null, $filterWeek = null) {
    $query = "SELECT transaction_id, transaction_date, transaction_service, transaction_item 
              FROM transaction 
              WHERE transaction_status = 1";

    $conds  = [];
    $params = [];
    $types  = "";

    // --- Year filter (only if no week given) ---
    if ($filterYear && !$filterWeek) {
        $conds[] = "YEAR(transaction_date) = ?";
        $params[] = $filterYear;
        $types   .= "i";
    }

    // --- Month filter (only if no week given) ---
    if ($filterMonth && !$filterWeek) {
        $conds[] = "MONTH(transaction_date) = ?";
        $params[] = $filterMonth;
        $types   .= "i";
    }

    // --- Week filter (takes priority, ignores month) ---
    if ($filterWeek && $filterYear) {
        $conds[] = "YEARWEEK(transaction_date, 1) = ?";
        $params[] = intval($filterYear . str_pad($filterWeek, 2, '0', STR_PAD_LEFT));
        $types   .= "i";
    }

    if ($conds) {
        $query .= " AND " . implode(" AND ", $conds);
    }

    $stmt = $this->conn->prepare($query);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $employees = [];
    $empIds    = [];

    while ($row = $result->fetch_assoc()) {
        $date       = new DateTime($row['transaction_date']);
        $dayOfWeek  = (int)$date->format('N'); // 1=Mon ... 7=Sun
        $monthName  = $date->format('F');
        $yearNum    = (int)$date->format('Y');
        $weekOfYear = (int)$date->format('W');

        // --- Services ---
        $services = json_decode($row['transaction_service'], true);
        if (!empty($services)) {
            foreach ($services as $svc) {
                $this->addEmployeeData($employees, $empIds, $svc, $dayOfWeek, $monthName, $yearNum, $weekOfYear);
            }
        }

        // --- Items ---
        // $items = json_decode($row['transaction_item'], true);
        // if (!empty($items)) {
        //     foreach ($items as $item) {
        //         $this->addEmployeeData($employees, $empIds, $item, $dayOfWeek, $monthName, $yearNum, $weekOfYear, true);
        //     }
        // }
    }
    $stmt->close();

    // --- Fetch employee names ---
    $this->populateEmployeeNames($employees, $empIds);

    // --- Fetch deductions ---
    $this->populateDeductions($employees);

    return array_values($employees);
}

private function addEmployeeData(&$employees, &$empIds, $data, $dayOfWeek, $monthName, $yearNum, $weekOfYear, $isItem=false) {
    $empId = isset($data['user_id']) ? (int)$data['user_id'] : 0;
    if ($isItem && $empId == 0) $empId = 1; // fallback

    $amount = $isItem ? ($data['subtotal'] ?? 0) : ($data['price'] ?? 0);

    if ($empId > 0) {
        $empIds[] = $empId;
        if (!isset($employees[$empId])) {
            $employees[$empId] = [
                "user_id"    => $empId,
                "name"       => "Unknown",
                "days"       => array_fill(1, 7, 0),
                "commission" => 0,
                "deductions" => 0,
                "months"     => []
            ];
        }

        $employees[$empId]["days"][$dayOfWeek] += $amount;
        $employees[$empId]["commission"] += $amount;
        $employees[$empId]["months"][$monthName] = ($employees[$empId]["months"][$monthName] ?? 0) + $amount;
        $employees[$empId]["_deduction_filters"] = [
            "year" => $yearNum,
            "week" => $weekOfYear
        ];
    }
}

private function populateEmployeeNames(&$employees, $empIds) {
    $empIds = array_unique(array_filter($empIds));
    if (!empty($empIds)) {
        $in  = str_repeat('?,', count($empIds) - 1) . '?';
        $sql = "SELECT user_id, CONCAT(firstname,' ',lastname) AS fullname 
                FROM user WHERE user_id IN ($in)";
        $stmtEmp = $this->conn->prepare($sql);
        $stmtEmp->bind_param(str_repeat("i", count($empIds)), ...$empIds);
        $stmtEmp->execute();
        $resEmp = $stmtEmp->get_result();
        while ($u = $resEmp->fetch_assoc()) {
            if (isset($employees[$u['user_id']])) {
                $employees[$u['user_id']]["name"] = $u['fullname'];
            }
        }
        $stmtEmp->close();
    }
}



// private function populateDeductions(&$employees) {
//     foreach ($employees as $empId => &$empData) {
//         if ($empId > 0 && isset($empData["_deduction_filters"])) {
//             $dedFilters = $empData["_deduction_filters"];
//             $dedQuery = "SELECT SUM(deduction_amount) as total_deduction
//                          FROM deduction 
//                          WHERE deduction_user_id = ?
//                          AND YEARWEEK(deduction_date, 1) = ?";
//             $stmtDed = $this->conn->prepare($dedQuery);
//             $yearWeek = intval($dedFilters["year"] . str_pad($dedFilters["week"], 2, '0', STR_PAD_LEFT));
//             $stmtDed->bind_param("ii", $empId, $yearWeek);
//             $stmtDed->execute();
//             $stmtDed->bind_result($totalDeduction);
//             if ($stmtDed->fetch()) {
//                 $empData["deductions"] = $totalDeduction ? floatval($totalDeduction) : 0;
//             }
//             $stmtDed->close();
//             unset($empData["_deduction_filters"]);
//         }
//     }
// }

private function populateDeductions(&$employees) {
    foreach ($employees as $empId => &$empData) {
        if ($empId > 0 && isset($empData["_deduction_filters"])) {
            $dedFilters = $empData["_deduction_filters"];
            
            // Build deduction_date string (e.g. "October 2025 Week 42")
            $dateObj = new DateTime();
            $dateObj->setISODate($dedFilters["year"], $dedFilters["week"]);
            $monthName = $dateObj->format('F');
            $yearNum   = $dedFilters["year"];
            $weekNum   = $dedFilters["week"];
            $deductionDateString = "$monthName $yearNum Week $weekNum";
            
            $dedQuery = "SELECT SUM(deduction_amount) AS total_deduction
                         FROM deduction 
                         WHERE deduction_user_id = ?
                         AND deduction_date = ?";
            $stmtDed = $this->conn->prepare($dedQuery);
            $stmtDed->bind_param("is", $empId, $deductionDateString);
            $stmtDed->execute();
            $stmtDed->bind_result($totalDeduction);
            if ($stmtDed->fetch()) {
                $empData["deductions"] = $totalDeduction ? floatval($totalDeduction) : 0;
            }
            $stmtDed->close();
            unset($empData["_deduction_filters"]);
        }
    }
}













public function fetch_employee_record_by_id($empId, $filterMonth = null, $filterYear = null, $filterWeek = null) {
    if (!$empId) return [];

    // Get employee name once
    $empName = "Unknown";
    $stmtEmp = $this->conn->prepare("SELECT CONCAT(firstname, ' ', lastname) AS fullname FROM user WHERE user_id = ?");
    $stmtEmp->bind_param("i", $empId);
    $stmtEmp->execute();
    $stmtEmp->bind_result($fullname);
    if ($stmtEmp->fetch()) $empName = $fullname;
    $stmtEmp->close();

    // Base employee structure
    $employee = [
        "user_id"    => $empId,
        "name"       => $empName,
        "days"       => array_fill(1, 7, 0),
        "commission" => 0,
        "deductions" => 0,
        "months"     => []
    ];

    // Fetch transactions
    $query = $this->conn->prepare("
        SELECT transaction_id, transaction_date, transaction_service 
        FROM transaction 
        WHERE transaction_status = 1
    ");
    $query->execute();
    $result = $query->get_result();

    while ($row = $result->fetch_assoc()) {
        $date = new DateTime($row['transaction_date']);
        $dayOfWeek = intval($date->format('N')); // 1=Mon ... 7=Sun
        $monthNum  = intval($date->format('m'));
        $monthName = $date->format('F');
        $year      = intval($date->format('Y'));
        $weekOfYear = intval($date->format('W')); // better than ceil(day/7)

        // Apply filters
        if ($filterMonth && $filterMonth != $monthNum) continue;
        if ($filterYear && $filterYear != $year) continue;
        if ($filterWeek && $filterWeek != $weekOfYear) continue;

        $services = json_decode($row['transaction_service'], true);
        if (!$services) continue;

        foreach ($services as $svc) {
            if (!isset($svc['user_id']) || intval($svc['user_id']) !== $empId) continue;

            $price = isset($svc['price']) ? floatval($svc['price']) : 0;

            // Add commission & days
            $employee["days"][$dayOfWeek] += $price;
            $employee["commission"] += $price;

            // Group by month
            if (!isset($employee["months"][$monthName])) {
                $employee["months"][$monthName] = 0;
            }
            $employee["months"][$monthName] += $price;
        }
    }

    // Fetch total deductions once
    $dedQuery = "
        SELECT SUM(deduction_amount) 
        FROM deduction 
        WHERE deduction_user_id = ? 
    ";
    $params = [$empId];
    $types = "i";

    if ($filterMonth) {
        $dedQuery .= " AND MONTH(deduction_date) = ?";
        $types .= "i";
        $params[] = $filterMonth;
    }
    if ($filterYear) {
        $dedQuery .= " AND YEAR(deduction_date) = ?";
        $types .= "i";
        $params[] = $filterYear;
    }
    if ($filterWeek) {
        $dedQuery .= " AND WEEK(deduction_date, 1) = ?"; // Mode 1: week starts Monday
        $types .= "i";
        $params[] = $filterWeek;
    }

    $stmtDed = $this->conn->prepare($dedQuery);
    $stmtDed->bind_param($types, ...$params);
    $stmtDed->execute();
    $stmtDed->bind_result($totalDeduction);
    if ($stmtDed->fetch()) {
        $employee["deductions"] = floatval($totalDeduction);
    }
    $stmtDed->close();

    return [$employee];
}










    public function updateItemCart($prod_id, $qty, $user_id, $item_id){
        $query = "UPDATE item_cart 
                SET item_prod_id = ?, item_qty = ?, item_user_id = ? 
                WHERE item_id = ?";
        $stmt = $this->conn->prepare($query);
        if(!$stmt) return false;
        $stmt->bind_param("iiii", $prod_id, $qty, $user_id, $item_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }














    
public function EditDeduction($empId, $deductionDate, $deductionAmount) {
    // 1. Check if deduction exists
    $checkStmt = $this->conn->prepare("SELECT deduction_id FROM deduction WHERE deduction_user_id  = ? AND deduction_date = ?");
    $checkStmt->bind_param("is", $empId, $deductionDate);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // 2a. Update existing deduction
        $updateStmt = $this->conn->prepare("UPDATE deduction SET deduction_amount = ? WHERE deduction_user_id  = ? AND deduction_date = ?");
        $updateStmt->bind_param("dis", $deductionAmount, $empId, $deductionDate);

        if ($updateStmt->execute()) {
            return ['status' => true, 'message' => 'Updated successfully.'];
        } else {
            return ['status' => false, 'message' => 'Update failed: ' . $updateStmt->error];
        }

    } else {
        // 2b. Insert new deduction
        $insertStmt = $this->conn->prepare("INSERT INTO deduction (deduction_user_id, deduction_date, deduction_amount) VALUES (?, ?, ?)");
        $insertStmt->bind_param("isd", $empId, $deductionDate, $deductionAmount);

        if ($insertStmt->execute()) {
            return ['status' => true, 'message' => 'Inserted successfully.'];
        } else {
            return ['status' => false, 'message' => 'Insert failed: ' . $insertStmt->error];
        }
    }
}









 // ✅ Fetch business details
    public function get_business_details() {
        $query = "SELECT * FROM `business_details` LIMIT 1";
        $result = $this->conn->query($query);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }




    public function fetch_appointment() {
            $sql = "SELECT appointments.*
                    FROM appointments 
                    ORDER BY appointment_id DESC";
            $result = $this->conn->query($sql);

            $users = [];
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
            }
            return $users;
    }

    


     public function cancel_appointment($appointment_id) {
            // Prepare statement
            $stmt = $this->conn->prepare("UPDATE appointments SET status = 'canceled' WHERE appointment_id = ?");
            
            if ($stmt) {
                // Bind parameter
                $stmt->bind_param("i", $appointment_id); 
                
                // Execute statement
                if ($stmt->execute()) {
                    $stmt->close();
                    return [
                        'success' => true,
                        'message' => 'Appointment canceled successfully.'
                    ];
                } else {
                    $stmt->close();
                    return [
                        'success' => false,
                        'message' => 'Failed to cancel appointment. Please try again.'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to prepare the statement.'
                ];
            }
        }




         public function approve_appointment($appointment_id) {
            // Prepare statement
            $stmt = $this->conn->prepare("UPDATE appointments SET status = 'approved' WHERE appointment_id = ?");
            
            if ($stmt) {
                // Bind parameter
                $stmt->bind_param("i", $appointment_id); 
                
                // Execute statement
                if ($stmt->execute()) {
                    $stmt->close();
                    return [
                        'success' => true,
                        'message' => 'Appointment canceled successfully.'
                    ];
                } else {
                    $stmt->close();
                    return [
                        'success' => false,
                        'message' => 'Failed to cancel appointment. Please try again.'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to prepare the statement.'
                ];
            }
        }


     








    public function getDashboardAnalytics() {
    $data = [];

    // -----------------------------
    // Total Customers
    // -----------------------------
    $sql = "SELECT COUNT(*) AS total FROM customer";
    $data['CustomerCount'] = $this->conn->query($sql)->fetch_assoc()['total'];

    // -----------------------------
    // Total Employees
    // -----------------------------
    $sql = "SELECT COUNT(*) AS total FROM user WHERE position='employee'";
    $data['EmployeeCount'] = $this->conn->query($sql)->fetch_assoc()['total'];

    // -----------------------------
    // Appointment Counts
    // -----------------------------
    $sql = "SELECT 
                SUM(status='pending') AS PendingAppointmentCount,
                SUM(status='approved') AS ApprovedAppointmentCount,
                SUM(status='canceled') AS CanceledAppointmentCount
            FROM appointments";
    $apptCounts = $this->conn->query($sql)->fetch_assoc();
    $data = array_merge($data, $apptCounts);

    // -----------------------------
    // Total Sales
    // -----------------------------
    $sql = "SELECT SUM(transaction_total) AS total FROM transaction WHERE transaction_status=1";
    $data['TotalSales'] = $this->conn->query($sql)->fetch_assoc()['total'] ?? 0;

    // -----------------------------
    // Sales Last 7 Days
    // -----------------------------
    $sql = "SELECT DATE(transaction_date) AS date, SUM(transaction_total) AS total
            FROM transaction
            WHERE transaction_status=1
            AND transaction_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(transaction_date)
            ORDER BY DATE(transaction_date) ASC";
    $result = $this->conn->query($sql);
    $salesLast7Days = [];
    while($row = $result->fetch_assoc()) {
        $salesLast7Days[] = $row;
    }
    $data['SalesLast7Days'] = $salesLast7Days;

    // -----------------------------
    // Employee Services (Most Used Service)
    // -----------------------------
    $sql = "SELECT transaction_service FROM transaction WHERE transaction_status=1";
    $result = $this->conn->query($sql);
    $employeeServiceCounts = [];

    while($row = $result->fetch_assoc()) {
        $services = json_decode($row['transaction_service'], true);
        if(is_array($services)) {
            foreach($services as $s) {
                $uid = $s['user_id'];
                if(!isset($employeeServiceCounts[$uid])) $employeeServiceCounts[$uid] = 0;
                $employeeServiceCounts[$uid]++;
            }
        }
    }

    // Map user IDs to names
   $employeeServices = [];
    if(!empty($employeeServiceCounts)) {
        $ids = implode(',', array_keys($employeeServiceCounts));
        $sql = "SELECT user_id, CONCAT(firstname,' ',lastname) AS employee_name, position 
                FROM user 
                WHERE user_id IN ($ids)";
        $res = $this->conn->query($sql);
        $names = [];
        while($r = $res->fetch_assoc()) {
            $names[$r['user_id']] = [
                'employee_name' => $r['employee_name'],
                'position'      => $r['position']
            ];
        }

        foreach($employeeServiceCounts as $uid => $count) {
            $employeeServices[] = [
                'employee_name' => $names[$uid]['employee_name'] ?? 'Unknown',
                'position'      => $names[$uid]['position'] ?? 'N/A',
                'service_count' => $count
            ];
        }
    }

    $data['EmployeeServices'] = $employeeServices;







    // -----------------------------
// Top 10 Low Stock Products
// -----------------------------
$lowStockProducts = [];
$sql = "SELECT prod_id, prod_name, prod_qty 
        FROM product 
        WHERE prod_status=1 
        ORDER BY prod_qty ASC 
        LIMIT 10";
$result = $this->conn->query($sql);

while($row = $result->fetch_assoc()) {
    $lowStockProducts[] = [
        'name' => $row['prod_name'],
        'quantity' => intval($row['prod_qty'])
    ];
}

$data['LowStockProducts'] = $lowStockProducts;






   // -----------------------------
// Popular Items (Products Sold) - Top 10
// -----------------------------
$sql = "SELECT transaction_item FROM transaction WHERE transaction_status=1";
$result = $this->conn->query($sql);
$productCounts = [];

while($row = $result->fetch_assoc()) {
    $items = json_decode($row['transaction_item'], true);
    if(is_array($items)) {
        foreach($items as $i) {
            $pid = $i['prod_id'];
            $qty = intval($i['qty']);
            if(!isset($productCounts[$pid])) $productCounts[$pid] = 0;
            $productCounts[$pid] += $qty;
        }
    }
}

// Sort products by total sold descending
arsort($productCounts);

// Take only top 10 products
$productCounts = array_slice($productCounts, 0, 10, true);

// Map product IDs to names
$popularItems = [];
if(!empty($productCounts)) {
    $ids = implode(',', array_keys($productCounts));
    $sql = "SELECT prod_id, prod_name FROM product WHERE prod_id IN ($ids)";
    $res = $this->conn->query($sql);
    $names = [];
    while($r = $res->fetch_assoc()) {
        $names[$r['prod_id']] = $r['prod_name'];
    }
    foreach($productCounts as $pid => $count) {
        $popularItems[] = [
            'name' => $names[$pid] ?? 'Unknown',
            'total_sold' => $count
        ];
    }
}

$data['PopularItems'] = $popularItems;


    // -----------------------------
    // Additional Useful Analytics
    // -----------------------------
    // Active Appointments Today
    $sql = "SELECT COUNT(*) AS total FROM appointments WHERE appointmentDate=CURDATE()";
    $data['AppointmentsToday'] = $this->conn->query($sql)->fetch_assoc()['total'] ?? 0;

    // Total Services Rendered
    $sql = "SELECT SUM(JSON_LENGTH(transaction_service)) AS total_services FROM transaction WHERE transaction_status=1";
    $data['TotalServicesRendered'] = $this->conn->query($sql)->fetch_assoc()['total_services'] ?? 0;

    // Total Products Sold
    $sql = "SELECT SUM(JSON_LENGTH(transaction_item)) AS total_items FROM transaction WHERE transaction_status=1";
    $data['TotalProductsSold'] = $this->conn->query($sql)->fetch_assoc()['total_items'] ?? 0;

    return $data;


    
}






   public function getDataCounting()
        {
            $query = "
                SELECT 
                    (SELECT COUNT(*) FROM `appointments` WHERE status='pending' AND seen='0') AS PendingAppointmentCount,
                    (SELECT COUNT(*) FROM `appointments` WHERE status='approved') AS ApprovedAppointmentCount,
                    (SELECT COUNT(*) FROM `user` WHERE position='employee') AS EmployeeCount,
                    (SELECT COUNT(*) FROM `customer`) AS CustomerCount,
                    (SELECT IFNULL(SUM(transaction_total),0) FROM `transaction` WHERE transaction_status=1) AS TotalSales
            ";

            $result = $this->conn->query($query);

            if ($result) {
                return $result->fetch_assoc();  
            } else {
                return ['error' => 'Failed to retrieve counts'];
            }
        }



        public function mark_seen($ids)
        {
            if (empty($ids)) return false;

            $ids = array_map('intval', $ids);
            $ids_str = implode(',', $ids);

            $sql = "UPDATE appointments SET seen = 1 WHERE appointment_id IN ($ids_str)";
            $result = $this->conn->query($sql);

            return $result ? true : false;
        }




}