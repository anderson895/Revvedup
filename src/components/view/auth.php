<?php
include ('../controller/config.php');

date_default_timezone_set('Asia/Manila');

class auth_class extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }

    // ✅ Fetch user account
    public function check_account($id) {
        $id = intval($id);
        $query = "SELECT * FROM `user` WHERE user_id = $id AND `status` = 1";

        $result = $this->conn->query($query);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc(); 
        }
        return null;
    }

    // ✅ Fetch business details
    public function get_business_details() {
        $query = "SELECT * FROM `business_details` LIMIT 1"; // since only one business record
        $result = $this->conn->query($query);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
}
