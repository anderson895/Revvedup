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

            $updated = $db->updateItemCart($prod_id, $qty, $user_id, $item_id); // create this function
            if($updated){
                echo json_encode(['status'=>200,'message'=>'Item updated successfully']);
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
                        'message' => 'Remove successfully.'
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
                $uniqueBannerFileName 
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
                $capital  = $_POST['capital'];
                $price     = $_POST['price'];
                $stockQty  = $_POST['stockQty'];

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
                            'message' => 'Error uploading itemImage image.'
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
                    $itemImageFileName 
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

               

        }else{
            echo "404";
        }
    }else {
        echo 'No POST REQUEST';
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

   if (isset($_GET['requestType']))
    {
        if ($_GET['requestType'] == 'fetch_all_product') {
            $result = $db->fetch_all_product();
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
            $result = $db->fetch_all_employee();
            echo json_encode([
                'status' => 200,
                'data' => $result
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
                $result = $db->fetch_all_employee_record(); 

                if ($result) {
                    echo json_encode(['status'=>200,'data'=>$result]);
                } else {
                    echo json_encode(['status'=>404,'message'=>'Item not found']);
                }
        }



    }else {
        echo 'No GET REQUEST';
    }
}
?>