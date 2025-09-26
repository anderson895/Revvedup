<?php
include('../class.php');

$db = new global_class();



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
    
        }else if ($_POST['requestType'] == 'AddProduct') {

                $itemName  = $_POST['itemName'];
                $menuCategory  = $_POST['menuCategory'];
                $menuDescription = $_POST['menuDescription'];
                $menuPrice  = $_POST['menuPrice'];
                // FILES
                $menuImage = $_FILES['menuImage'];
                $uploadDir = '../../static/upload/';
                $menuImageFileName = ''; 
                if (isset($menuImage) && $menuImage['error'] === UPLOAD_ERR_OK) {
                    $bannerExtension = pathinfo($menuImage['name'], PATHINFO_EXTENSION);
                    $menuImageFileName = uniqid('menu_', true) . '.' . $bannerExtension;
                    $bannerPath = $uploadDir . $menuImageFileName;

                    $bannerUploaded = move_uploaded_file($menuImage['tmp_name'], $bannerPath);

                    if (!$bannerUploaded) {
                        echo json_encode([
                            'status' => 500,
                            'message' => 'Error uploading menuImage image.'
                        ]);
                        exit;
                    }
                } elseif ($menuImage['error'] !== UPLOAD_ERR_NO_FILE && $menuImage['error'] !== 0) {
                    echo json_encode([
                        'status' => 400,
                        'message' => 'Invalid image upload.'
                    ]);
                    exit;
                }
                $result = $db->AddMenu(
                    $menuName,
                    $menuCategory,
                    $menuDescription,
                    $menuPrice,
                    $menuImageFileName 
                );

                if ($result) {
                    echo json_encode([
                        'status' => 200,
                        'message' => 'Posted Successfully.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 500,
                        'message' => 'Error saving data.'
                    ]);
                }

               

        }else{
            echo "404";
        }
    }else {
        echo 'No GET REQUEST';
    }

}
?>