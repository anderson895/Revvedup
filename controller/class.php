
<?php


include ('config.php');

date_default_timezone_set('Asia/Manila');

class global_class extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }


public function fetch_analytics($scope = "weekly") {
    $conn = $this->conn;
    $analytics = [];

    if ($scope === "weekly") {
        // Get all weeks with transactions
        $sql = "SELECT transaction_date, transaction_item
                FROM transaction
                WHERE transaction_status = 1
                ORDER BY transaction_date ASC";
    } elseif ($scope === "monthly") {
        // Get all months with transactions
        $sql = "SELECT transaction_date, transaction_item
                FROM transaction
                WHERE transaction_status = 1
                ORDER BY transaction_date ASC";
    } else {
        $sql = "SELECT transaction_date, transaction_item
                FROM transaction
                WHERE transaction_status = 1
                ORDER BY transaction_date ASC";
    }

    $result = mysqli_query($conn, $sql);
    if (!$result) return [];

    while ($row = mysqli_fetch_assoc($result)) {
        $date = strtotime($row['transaction_date']);

        if ($scope === "weekly") {
            $year = date("o", $date); // ISO-8601 year
            $week = date("W", $date);
            $label = "Week {$week}, {$year}";
        } elseif ($scope === "monthly") {
            $label = date("M Y", $date);
        } else {
            $label = date("Y-m-d", $date);
        }

        $items = json_decode($row['transaction_item'], true);
        if (!is_array($items)) continue;

        foreach ($items as $it) {
            $subtotal = (float)$it['subtotal'];
            $capital = (float)$it['capital'];
            $qty = (int)$it['qty'];
            $capitalTotal = $capital * $qty;
            $revenue = $subtotal - $capitalTotal;

            if (!isset($analytics[$label])) {
                $analytics[$label] = [
                    "label" => $label,
                    "total_sales" => 0,
                    "capital_total" => 0,
                    "revenue" => 0
                ];
            }

            $analytics[$label]['total_sales'] += $subtotal;
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

        // Collect emp_ids from services
        $services = json_decode($row['transaction_service'], true) ?? [];
        $empIds = [];
        foreach ($services as $s) {
            if (!empty($s['emp_id'])) {
                $empIds[] = (int)$s['emp_id'];
            }
        }

        // Fetch employees
        $employees = [];
        if (!empty($empIds)) {
            $ids = implode(',', array_unique($empIds));
            $empQuery = $this->conn->prepare("SELECT emp_id, emp_fname, emp_lname FROM employee WHERE emp_id IN ($ids)");
            $empQuery->execute();
            $empRes = $empQuery->get_result();
            while ($emp = $empRes->fetch_assoc()) {
                $employees[$emp['emp_id']] = $emp['emp_fname'].' '.$emp['emp_lname'];
            }
        }

        // Merge employee names into services
        foreach ($services as &$s) {
            $id = (int)$s['emp_id'];
            $s['employee_name'] = $employees[$id] ?? "Unknown Employee #$id";
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



public function AddServiceCart($itemName,$price,$employee,$user_id) {
    $query = "INSERT INTO `service_cart` (`service_name`, `service_price`, `service_employee_id`,`service_user_id`) 
              VALUES (?, ?, ?,?)";

    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        return false; 
    }
    $stmt->bind_param("sdii", $itemName, $price, $employee,$user_id);

    $result = $stmt->execute();

    if (!$result) {
        $stmt->close();
        return false;
    }

    $inserted_id = $this->conn->insert_id;
    $stmt->close();

    return $inserted_id;
}









public function updateServiceCart($serviceName, $price, $employee, $user_id, $service_id)
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

    $stmt->bind_param("sdiii", $serviceName, $price, $employee, $user_id, $service_id);

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









public function fetch_all_product() {
     $query = $this->conn->prepare("SELECT * FROM product
        where prod_status='1'
        ORDER BY prod_id DESC");

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
                if(!empty($s['emp_id'])) $empIds[] = (int)$s['emp_id'];
            }
            $allData[] = $row;
        }

        // Fetch employee names
        $employees = [];
        if(!empty($empIds)) {
            $ids = implode(',', array_unique($empIds));
            $empQuery = $this->conn->prepare("SELECT emp_id, emp_fname, emp_lname FROM employee WHERE emp_id IN ($ids)");
            $empQuery->execute();
            $empRes = $empQuery->get_result();
            while($emp = $empRes->fetch_assoc()) {
                $employees[$emp['emp_id']] = $emp['emp_fname'].' '.$emp['emp_lname'];
            }
        }

        // Merge employee names
        foreach($allData as &$row) {
            $services = json_decode($row['transaction_service'], true) ?? [];
            foreach($services as &$s) {
                $id = (int)$s['emp_id'];
                $s['employee_name'] = $employees[$id] ?? "Unknown Employee #$id";
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
    $uniqueBannerFileName = null
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
    $query = "UPDATE product SET prod_name = ?,prod_capital=?, prod_price = ?, prod_qty = ?";
    $types = "sddi"; // s = string, d = double, i = integer
    $params = [$itemName,$capital, $price, $stockQty];

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





    
    public function fetch_all_employee() {
        $query = $this->conn->prepare("SELECT * FROM employee
            where emp_status='1'
            ORDER BY emp_id DESC");

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
                LEFT JOIN employee
                ON employee.emp_id  = service_cart.service_employee_id 
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





    public function AddProduct($itemName, $capital,$price, $stockQty, $itemImageFileName) {
        $query = "INSERT INTO `product` 
                (`prod_name`,`prod_capital`, `prod_price`, `prod_qty`, `prod_img`) 
                VALUES (?,?,?,?,?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("sddis", $itemName,$capital,$price, $stockQty, $itemImageFileName);

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
                SELECT sc.*, e.emp_fname, e.emp_lname 
                FROM service_cart sc
                LEFT JOIN employee e ON e.emp_id = sc.service_employee_id
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




    
public function CheckOutOrder($services, $items, $discount, $vat, $grandTotal, $payment, $change, &$errorMsg = null) {
    $services_json = json_encode($services);
    $items_json = json_encode($items);

    $this->conn->begin_transaction();

    try {
        // 1️⃣ Insert transaction
        $sql = "INSERT INTO `transaction` 
                (transaction_service, transaction_item, transaction_discount, transaction_vat, transaction_total, transaction_payment, transaction_change, transaction_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed: " . $this->conn->error);

        $stmt->bind_param("ssddddd", $services_json, $items_json, $discount, $vat, $grandTotal, $payment, $change);
        if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);

      // ✅ Get last inserted transaction_id
        $transactionId = $this->conn->insert_id;
        $stmt->close();

        // ✅ Insert sa weeks kung may service lang
        if (is_array($services) && count($services) > 0) {
            $this->InsertWeekForTransaction($transactionId);
        }


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

        // 4️⃣ Delete from item_cart
        foreach ($items as $i) {
            if (isset($i['item_id'])) {
                $del = $this->conn->prepare("DELETE FROM item_cart WHERE item_id = ?");
                $del->bind_param("i", $i['item_id']);
                if (!$del->execute()) {
                    $del->close();
                    throw new Exception("Failed to delete item_cart item ID {$i['item_id']}");
                }
                $del->close();
            }
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




public function InsertWeekForTransaction($transactionId) {
    try {
        // insert into weeks table using transaction_date
        $sqlWeek = "INSERT INTO weeks (week_number, week_transaction_id) 
                    SELECT WEEKOFYEAR(transaction_date), transaction_id 
                    FROM transaction 
                    WHERE transaction_id = ?";
        $stmtWeek = $this->conn->prepare($sqlWeek);
        if (!$stmtWeek) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $stmtWeek->bind_param("i", $transactionId);
        if (!$stmtWeek->execute()) {
            throw new Exception("Execute failed: " . $stmtWeek->error);
        }
        $stmtWeek->close();
        return true;

    } catch (Exception $e) {
        throw new Exception("InsertWeekForTransaction error: " . $e->getMessage());
    }
}



    public function fetch_all_employee_record() {
    $query = $this->conn->prepare("
        SELECT transaction_id, transaction_date, transaction_service 
        FROM transaction 
        WHERE transaction_status = 1
    ");
    $query->execute();
    $result = $query->get_result();

    $employees = [];

    while ($row = $result->fetch_assoc()) {
        $date = new DateTime($row['transaction_date']);
        $dayOfWeek = $date->format('N'); // 1=Mon ... 7=Sun
        $month = $date->format('F');
        $year = $date->format('Y');

        $services = json_decode($row['transaction_service'], true);

        if (!empty($services)) {
            foreach ($services as $svc) {
                $empId = isset($svc['emp_id']) ? intval($svc['emp_id']) : 0;
                $price = isset($svc['price']) ? floatval($svc['price']) : 0;

                // ✅ Fetch employee name from DB instead of JSON
                $empName = "Unknown";
                if ($empId > 0) {
                    $stmtEmp = $this->conn->prepare("SELECT CONCAT(emp_fname, ' ', emp_lname) AS fullname 
                                                     FROM employee 
                                                     WHERE emp_id = ?");
                    $stmtEmp->bind_param("i", $empId);
                    $stmtEmp->execute();
                    $stmtEmp->bind_result($fullname);
                    if ($stmtEmp->fetch()) {
                        $empName = $fullname;
                    }
                    $stmtEmp->close();
                }

                // ✅ Initialize employee record if not exists
                if (!isset($employees[$empId])) {
                    $employees[$empId] = [
                        "id" => $empId,
                        "name" => $empName,
                        "days" => array_fill(1, 7, 0), // Mon–Sun
                        "commission" => 0,
                        "deductions" => 0,
                        "months" => []
                    ];
                }

                // ✅ Add commission & day
                $employees[$empId]["days"][$dayOfWeek] += $price;
                $employees[$empId]["commission"] += $price;

                // ✅ Group by month
                if (!isset($employees[$empId]["months"][$month])) {
                    $employees[$empId]["months"][$month] = 0;
                }
                $employees[$empId]["months"][$month] += $price;
            }
        }
    }

    return array_values($employees); // return as indexed array
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






}