<?php
include_once("db.php");


class tbl_userlogin{
    public $conn;


    function __construct($conn){
        $this->conn = $conn;

    }

    function addToTable($user, $password){
        $sql = "INSERT INTO `tbl_userlogin` (`username`, `password`) VALUES ('$user', '".md5($password)."');";
        if ($this->conn->query($sql) === TRUE) {
            return "success";
          } else {
            return "Error: " . $sql . "<br>" . $conn->error;
          }
    }
    function checkIfAuth($user, $password){
        $sql = "SELECT * FROM `tbl_userlogin` WHERE password LIKE '".md5($password)."' AND username LIKE '$user';";
        $result = $this->conn->query($sql);
        $return = array();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $return[] = "success";
            $return[] = $row["id"];
            return $return;
        }
        else{
            $return[] = "failed";
            return $return;
        }     
    }
}
?>