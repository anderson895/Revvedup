<?php


include ('../controller/config.php');

date_default_timezone_set('Asia/Manila');

class auth_class extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }

    public function check_account($id) {
            $id = intval($id);
            $query = "SELECT * FROM `user` WHERE user_id = $id AND `status` = 1";

            $result = $this->conn->query($query);

            if ($result && $result->num_rows > 0) {
                return $result->fetch_assoc(); 
            }

            return null;
    }



    


}