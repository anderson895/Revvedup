<?php
include('../class.php');

$db = new global_class();

session_start();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['requestType'])) {
         if ($_POST['requestType'] == 'Login_admin') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $loginResult = $db->Login_admin($username, $password);

            if ($loginResult['success']) {
                echo json_encode([
                    'status' => 'success',
                    'message' => $loginResult['message'],
                    'position' => $loginResult['data']['position']
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => $loginResult['message']
                ]);
            }
    
        }else if ($_POST['requestType'] == 'Login_employee') {
            $pin = $_POST['pin'];
            $loginResult = $db->Login_employee($pin);

            if ($loginResult['success']) {
                echo json_encode([
                    'status' => 'success',
                    'message' => $loginResult['message'],
                    'position' => $loginResult['data']['position']
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => $loginResult['message']
                ]);
            }
    
        }else if ($_POST['requestType'] == 'mark_seen') {
              if (isset($_POST['appointmentIds'])) {
                    $ids = $_POST['appointmentIds'];
                    $result = $db->mark_seen($ids); // call class method

                    if ($result) {
                        echo json_encode(['status' => 200, 'message' => 'Marked as seen']);
                    } else {
                        echo json_encode(['status' => 500, 'message' => 'Failed to mark as seen']);
                    }
                } else {
                    echo json_encode(['status' => 400, 'message' => 'No appointment IDs provided']);
                }
                exit;


            
    
        }else if ($_POST['requestType'] == 'AddUser') {
                $firstname = trim($_POST['firstname']);
                $lastname  = trim($_POST['lastname']);
                $email     = trim($_POST['email']);
                $pin       = $_POST['pin']; 

                $result = $db->add_user($firstname, $lastname, $email, $pin);

                echo json_encode([
                    "status" => $result === "User added successfully" ? 200 : 500,
                    "message" => $result
                ]);
                exit;

            } else if ($_POST['requestType'] == 'UpdateUser') {

                // echo "<pre>";
                // print_r($_POST);
                // echo "</pre>";
                $userId    = $_POST['userId'];
                $firstname = trim($_POST['firstname']);
                $lastname  = trim($_POST['lastname']);
                $email     = trim($_POST['email']);
                $pin       = $_POST['pin'];

                if (!$userId) {
                    echo json_encode(["status" => 400, "message" => "Missing userId"]);
                    exit;
                }

                $result = $db->update_user($userId, $firstname, $lastname, $email, $pin);

                echo json_encode([
                    "status" => $result === "User updated successfully" ? 200 : 500,
                    "message" => $result
                ]);

                exit;
            } else if ($_POST['requestType'] == 'deactivateUser') {

                $userId = $_POST['userId'] ?? null;

                if (!$userId) {
                    echo json_encode(["status" => 400, "message" => "Missing userId"]);
                    exit;
                }

                $result = $db->removeUser($userId);

                echo json_encode([
                    "status" => $result === "User deactivated successfully" ? 200 : 500,
                    "message" => $result
                ]);
                exit;

        } else if ($_POST['requestType'] == 'restoreUser') {

            $userId = $_POST['userId'] ?? null;

            if (!$userId) {
                echo json_encode(["status" => 400, "message" => "Missing userId"]);
                exit;
            }

            $result = $db->restore_user($userId);

            echo json_encode([
                "status" => $result === "User restored successfully" ? 200 : 500,
                "message" => $result
            ]);
            exit;
        }else if ($_POST['requestType'] == 'approve_appointment') {
            $appointment_id = $_POST['appointment_id'];
            $result = $db->approve_appointment($appointment_id);

            if ($result['success']) {
                echo json_encode([
                    'status' => 'success',
                    'message' => $result['message']  
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => $result['message']   
                ]);
            }

        }else if ($_POST['requestType'] == 'cancel_appointment') {
            $appointment_id = $_POST['appointment_id'];
            $result = $db->cancel_appointment($appointment_id);

            if ($result['success']) {
                echo json_encode([
                    'status' => 'success',
                    'message' => $result['message']   
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => $result['message']   
                ]);
            }

        }else if ($_POST['requestType'] == 'CheckOutOrder') {
                $services = $_POST['services'] ?? [];
                $items = $_POST['items'] ?? [];
                $discount = $_POST['discount'] ?? 0;
                $vat = $_POST['vat'] ?? 0;
                $grandTotal = $_POST['grandTotal'] ?? 0;
                $payment = $_POST['payment'] ?? 0;
                $change = $_POST['change'] ?? 0;

                $errorMsg = null;
                $result = $db->CheckOutOrder($services, $items, $discount, $vat, $grandTotal, $payment, $change, $errorMsg);

                if ($result) {
                    echo json_encode([
                        'status' => 200,
                        'message' => 'Success',
                        'transaction_id' => $result
                    ]);
                } else {
                    echo json_encode([
                        'status' => 500,
                        'message' => $errorMsg
                    ]);
                }
    
        }else if($_POST['requestType'] === 'updateItemCart'){
            $item_id = intval($_POST['item_id']);
            $prod_id = intval($_POST['selectedProductId']);
            $qty = intval($_POST['modalProdQty']);
            $user_id = $_SESSION['user_id'];

            $updated = $db->updateItemCart($prod_id, $qty, $user_id, $item_id); 
            if($updated){
                echo json_encode(['status'=>200,'message'=>'Item updated successfully']);
            } else {
                echo json_encode(['status'=>500,'message'=>'Failed to update item']);
            }
            exit;
        }else if($_POST['requestType'] === 'complete_transaction'){

            $transactionId = $_POST['transactionId'];
            $refund = json_decode($_POST['refund'], true);    
            $exchange = json_decode($_POST['exchange'], true);

            // validate
            if (!$transactionId) {
                echo json_encode(['status'=>400,'message'=>'Invalid transaction ID']);
                exit;
            }

            // Pass data to your database function
            $result = $db->complete_transaction($transactionId, $refund, $exchange);

            // Check result based on status
            if (isset($result['status']) && $result['status'] === true) {
                echo json_encode(['status'=>200,'message'=>$result['message']]);
            } else {
                $msg = isset($result['message']) ? $result['message'] : 'Failed to update item';
                echo json_encode(['status'=>500,'message'=>$msg]);
            }
            exit;


        }else if($_POST['requestType'] === 'EditDeduction'){
        

            $empId =$_POST['empId'];
            $deductionDate =$_POST['deductionDate'];
            $deduction =$_POST['deduction'];
            

            $result = $db->EditDeduction($empId, $deductionDate, $deduction); 

            if($result){
                echo json_encode(['status'=>200,'message'=>'updated successfully']);
            } else {
                echo json_encode(['status'=>500,'message'=>'Failed to update item']);
            }
            exit;

            
        }else if ($_POST['requestType'] == 'removeProduct') {

            $prod_id=$_POST['prod_id'];
            $result = $db->removeProduct($prod_id);
            if ($result) {
                    echo json_encode([
                        'status' => 200,
                        'message' => 'Archived successfully.'
                    ]);
            } else {
                    echo json_encode([
                        'status' => 500,
                        'message' => 'No changes made or error updating data.'
                    ]);
            }
        }else if ($_POST['requestType'] == 'restoreProduct') {

            $prod_id=$_POST['prod_id'];
            $result = $db->restoreProduct($prod_id);
            if ($result) {
                    echo json_encode([
                        'status' => 200,
                        'message' => 'Restore successfully.'
                    ]);
            } else {
                    echo json_encode([
                        'status' => 500,
                        'message' => 'No changes made or error updating data.'
                    ]);
            }
        }else if ($_POST['requestType'] == 'deleteCart') {

            $id=$_POST['id'];
            $table=$_POST['table'];
            $collumn=$_POST['collumn'];
            $result = $db->deleteCart($id,$table,$collumn);
            if ($result) {
                    echo json_encode([
                        'status' => 200,
                        'message' => 'Remove successfully.'
                    ]);
            } else {
                    echo json_encode([
                        'status' => 500,
                        'message' => 'No changes made or error updating data.'
                    ]);
            }


        }else if ($_POST['requestType'] == 'UpdateProduct') {
                    $productId = $_POST['productId'];
                    $itemName = $_POST['itemName'];
                    $capital = $_POST['capital'];
                    $price = $_POST['price'];
                    $stockQty = $_POST['stockQty'];
                    $category = $_POST['category']; 
                    $description = $_POST['description']; 

                    // Handle Banner Image Upload
                    $uniqueBannerFileName = null;
                    if (isset($_FILES['itemImage']) && $_FILES['itemImage']['error'] === UPLOAD_ERR_OK) {
                        $uploadDir = '../../static/upload/';
                        $fileExtension = pathinfo($_FILES['itemImage']['name'], PATHINFO_EXTENSION);
                        $uniqueBannerFileName = uniqid('item_', true) . '.' . $fileExtension;

                        move_uploaded_file($_FILES['itemImage']['tmp_name'], $uploadDir . $uniqueBannerFileName);
                    }

                    // Update
                    $result = $db->UpdateProduct(
                        $productId,
                        $itemName,
                        $capital,
                        $price,
                        $stockQty,
                        $category,           
                        $uniqueBannerFileName,
                        $description
                    );

                    if ($result['status']) {
                        echo json_encode([
                            'status' => 200,
                            'message' => $result['message']
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 500,
                            'message' => $result['message']
                        ]);
                    }


    
        }else if ($_POST['requestType'] == 'AddProduct') {

                            $itemName  = $_POST['itemName'];
                            $capital   = $_POST['capital'];
                            $price     = $_POST['price'];
                            $stockQty  = $_POST['stockQty'];
                            $category  = $_POST['category']; 
                            $description  = $_POST['description']; 

                            // FILES
                            $itemImage = $_FILES['itemImage'];
                            $uploadDir = '../../static/upload/';
                            $itemImageFileName = ''; 

                            if (isset($itemImage) && $itemImage['error'] === UPLOAD_ERR_OK) {
                                $bannerExtension = pathinfo($itemImage['name'], PATHINFO_EXTENSION);
                                $menuImageFileName = uniqid('item_', true) . '.' . $bannerExtension;
                                $bannerPath = $uploadDir . $menuImageFileName;

                                $bannerUploaded = move_uploaded_file($itemImage['tmp_name'], $bannerPath);

                                if ($bannerUploaded) {
                                    $itemImageFileName = $menuImageFileName;
                                } else {
                                    echo json_encode([
                                        'status' => 500,
                                        'message' => 'Error uploading item image.'
                                    ]);
                                    exit;
                                }
                            } elseif ($itemImage['error'] !== UPLOAD_ERR_NO_FILE && $itemImage['error'] !== 0) {
                                echo json_encode([
                                    'status' => 400,
                                    'message' => 'Invalid image upload.'
                                ]);
                                exit;
                            }

                            $result = $db->AddProduct(
                                $itemName,
                                $capital,
                                $price,
                                $stockQty,
                                $itemImageFileName,
                                $category,
                                $description
                            );

                            if ($result) {
                                echo json_encode([
                                    'status' => 200,
                                    'message' => 'Added Successfully.'
                                ]);
                            } else {
                                echo json_encode([
                                    'status' => 500,
                                    'message' => 'Error saving data.'
                                ]);
                            }

               

        }else if ($_POST['requestType'] == 'AddServiceCart') {

                $user_id=$_SESSION['user_id'];
                $serviceName  = $_POST['serviceName'];
                $price     = $_POST['price'];
                $employee  = $_POST['employee'];

              
                $result = $db->AddServiceCart(
                    $serviceName,
                    $price,
                    $employee,
                    $user_id
                );

                if ($result) {
                    echo json_encode([
                        'status' => 200,
                        'message' => 'Added Successfully.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 500,
                        'message' => $result
                    ]);
                }

               

        }else if ($_POST['requestType'] == 'updateServiceCart') {

                $user_id=$_SESSION['user_id'];
                $service_id=$_POST['service_id'];
                $serviceName  = $_POST['serviceName'];
                $price     = $_POST['price'];
                $employee  = $_POST['employee'];

                $result = $db->updateServiceCart(
                    $serviceName,
                    $price,
                    $employee,
                    $user_id,
                    $service_id,
                );

                if ($result) {
                    echo json_encode([
                        'status' => 200,
                        'message' => 'Updated Successfully.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 500,
                        'message' => $result
                    ]);
                }

               

        }else if ($_POST['requestType'] == 'AddToItem') {

                $user_id=$_SESSION['user_id'];
                $selectedProductId  = $_POST['selectedProductId'];
                $modalProdQty     = $_POST['modalProdQty'];

              
                $result = $db->AddToItem($selectedProductId,$modalProdQty,$user_id);

                if ($result) {
                    echo json_encode([
                        'status' => 200,
                        'message' => 'Added Successfully.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 500,
                        'message' => $result
                    ]);
                }

               

        }else if ($_POST['requestType'] == 'update_business') {
                $result = $db->update_business(
                    $_POST['businessName'],
                    $_POST['businessAdd'],
                    $_POST['contactNum']
                );
                echo $result;

            } else if ($_POST['requestType'] == 'update_basic') {
                $result = $db->update_basic(
                    $_SESSION['user_id'],
                    $_POST['firstname'],
                    $_POST['lastname'],
                    $_POST['username'],
                    $_POST['email']
                );
                echo $result;

            } else if ($_POST['requestType'] == 'update_security') {

                $On_Session = $db->check_account($_SESSION['user_id']);
                $password   = trim($_POST['password'] ?? '');
                $confirm    = trim($_POST['confirm_password'] ?? '');
                $old_pass   = trim($_POST['old_password'] ?? '');
                $pin        = trim($_POST['pin'] ?? '');

                if ($On_Session['position'] === 'admin') {
                    // Admin → password update
                    if (empty($password) || empty($confirm) || empty($old_pass)) {
                        echo "Password fields cannot be empty.";
                    } elseif ($password !== $confirm) {
                        echo "Passwords do not match.";
                    } else {
                        $result = $db->update_password($_SESSION['user_id'], $old_pass, $password);
                        echo $result;
                    }
                } elseif ($On_Session['position'] === 'employee') {
                    // Employee → PIN update
                    if (empty($pin)) {
                        echo "PIN required.";
                    } else {
                        $result = $db->update_pin($_SESSION['user_id'], $pin);
                        echo $result;
                    }
                } else {
                    echo "Invalid user role.";
                }



            } else {
                echo "404";
            }

    }else {
        echo 'No POST REQUEST';
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

   if (isset($_GET['requestType']))
    {
        if ($_GET['requestType'] == 'fetch_all_product') {
            if(isset($_SESSION['user_id'])){

                $On_Session = $db->check_account($_SESSION['user_id']);
                $position = $On_Session['position'] ?? "employee";

                $result = $db->fetch_all_product();
                echo json_encode([
                    'status' => 200,
                    'data' => $result,
                    "position" => $position
                ]);
            }else{
                
                $result = $db->fetch_all_product();
                echo json_encode([
                    'status' => 200,
                    'data' => $result
                ]);
            }
            
        }else if ($_GET['requestType'] == 'fetch_archived_product') {
            
                $On_Session = $db->check_account($_SESSION['user_id']);
                $position = $On_Session['position'] ?? "employee";

                $result = $db->fetch_archived_product();
                echo json_encode([
                    'status' => 200,
                    'data' => $result,
                    "position" => $position
                ]);


        }else if ($_GET['requestType'] == 'fetch_all_users') {
            $result = $db->fetch_all_users();
            echo json_encode([
                'status' => 200,
                'data' => $result
            ]);
        }else if ($_GET['requestType'] == 'fetch_all_transaction') {
            $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $filter = isset($_GET['filter']) ? $_GET['filter'] : "";
            $offset = ($page - 1) * $limit;

            $transactions = $db->fetch_all_transaction($limit, $offset, $filter);
            $totalRows = $db->count_transactions($filter);

            echo json_encode([
                'status' => 200,
                'data' => [
                    'transactions' => $transactions,
                    'totalRows' => $totalRows
                ]
            ]);


        }else if ($_GET['requestType'] == 'fetch_all_employee') {

            $On_Session = $db->check_account($_SESSION['user_id']);
            $position = $On_Session['position'] ?? "employee";

            $result = $db->fetch_all_employee();
            echo json_encode([
                'status' => 200,
                'data' => $result,
                'position' => $position,
                'default_user_id' => $_SESSION['user_id']
                
            ]);
        }else if ($_GET['requestType'] == 'fetch_all_service_cart') {
            $user_id=$_SESSION['user_id'];
            $result = $db->fetch_all_service_cart($user_id);
            echo json_encode([
                'status' => 200,
                'data' => $result
            ]);
        }else if ($_GET['requestType'] == 'fetch_all_item_cart') {
            $user_id=$_SESSION['user_id'];
            $result = $db->fetch_all_item_cart($user_id);
            echo json_encode([
                'status' => 200,
                'data' => $result
            ]);
        }else if ($_GET['requestType'] == 'fetch_all_cart') {
            $user_id=$_SESSION['user_id'];
            $result = $db->fetch_all_cart($user_id);
            echo json_encode([
                'status' => 200,
                'data' => $result
            ]);
        }else if ($_GET['requestType'] == 'fetch_total_cart') {
            $user_id=$_SESSION['user_id'];
            $result = $db->fetch_total_cart($user_id);
            echo json_encode([
                'status' => 200,
                'data' => $result
            ]);
        }else if ($_GET['requestType'] == 'getServiceById') {
            $service_id=$_GET['service_id'];
            $result = $db->getServiceById($service_id);
            echo json_encode([
                'status' => 200,
                'data' => $result
            ]);
        }else if($_GET['requestType'] === 'getItemById'){
            $item_id = intval($_GET['item_id']);
            $item = $db->getItemById($item_id); 
            if($item){
                echo json_encode(['status'=>200,'data'=>$item]);
            } else {
                echo json_encode(['status'=>404,'message'=>'Item not found']);
            }
            exit;
            
        }else if ($_GET['requestType'] === 'fetch_analytics') {
              $scope = $_GET['scope'] ?? "weekly"; // default weekly
                $result = $db->fetch_analytics($scope); 

                if ($result) {
                    echo json_encode(['status'=>200,'data'=>$result]);
                } else {
                    echo json_encode(['status'=>404,'message'=>'Item not found']);
                }
        }else if ($_GET['requestType'] === 'fetch_all_employee_record') {

                                
                $today = new DateTime("now", new DateTimeZone("Asia/Manila"));

                // Defaults
                $currentYear  = (int)$today->format("Y");
                $currentMonth = (int)$today->format("m");
                $currentWeek  = (int)$today->format("W");

                // Get from GET request or default
                $year  = isset($_GET['year'])  && $_GET['year']  !== '' ? intval($_GET['year'])  : $currentYear;
                $month = isset($_GET['month']) && $_GET['month'] !== '' ? intval($_GET['month']) : $currentMonth;
                $week  = isset($_GET['week'])  && $_GET['week']  !== '' ? intval($_GET['week'])  : $currentWeek;

                // Range validation
                $month = max(1, min(12, $month));
                $week  = max(1, min(53, $week));

                // Require login
                if (!isset($_SESSION['user_id'])) {
                    echo json_encode([
                        'status' => 401,
                        'message' => 'Unauthorized'
                    ]);
                    exit;
                }

                // Get user role
                $On_Session = $db->check_account($_SESSION['user_id']);
                $position = $On_Session['position'] ?? "employee";

                // Fetch data
                if ($position === "admin") {
                    $result = $db->fetch_all_employee_record($month, $year, $week);
                } else {
                    $result = $db->fetch_employee_record_by_id($_SESSION['user_id'], $month, $year, $week);
                }

                // Return JSON
                if ($result) {
                    echo json_encode([
                        'status' => 200,
                        'data' => $result,
                        'position' => $position,
                        'date' => [
                            'month' => $month,
                            'year'  => $year,
                            'week'  => $week
                        ]
                    ]);
                } else {
                    echo json_encode([
                        'status' => 404,
                        'message' => 'No records found',
                        'date' => [
                            'month' => $month,
                            'year'  => $year,
                            'week'  => $week
                        ]
                    ]);
                }

        }else if ($_GET['requestType'] === 'fetch_transaction_record') {


                $transactionId=$_GET['transactionId'];
               
                $result = $db->fetch_transaction_record($transactionId);

                if ($result) {
                    echo json_encode(['status'=>200,'data'=>$result]);
                } else {
                    echo json_encode(['status'=>404,'message'=>'Item not found']);
                }

        }else if ($_GET['requestType'] == 'fetch_appointment') {

            $result = $db->fetch_appointment();
            echo json_encode([
                'status' => 200,
                'data' => $result
            ]);
        }else if ($_GET['requestType'] == 'getDataCounting') {

            $result = $db->getDataCounting();
            echo json_encode([
                'status' => 200,
                'data' => $result
            ]);
        }else if ($_GET['requestType'] == 'getDashboardAnalytics') {

            $result = $db->getDashboardAnalytics();
            echo json_encode([
                'status' => 200,
                'data' => $result
            ]);
        }



    }else {
        echo 'No GET REQUEST';
    }
}
?>