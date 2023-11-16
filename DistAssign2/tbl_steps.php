<?php
include_once("db.php");


class tbl_steps{
    public $conn;


    function __construct($conn){
        $this->conn = $conn;

    }

    function getSteps($userID){
        $sql = "SELECT * FROM `tbl_steps` WHERE `userId` = $userID;";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $returnArr = array();
            while($row = $result->fetch_assoc()) {
              $returnArr[0][] = $row["steps"];
              $returnArr[1][] = $row["date"];
            }
        } else {
            $returnArr = array();
        }
          return $returnArr;
    }
    function updateSteps($user, $steps){
        $date = date("Y-m-d");
        
        $sql = "SELECT * FROM `tbl_steps` WHERE `userId` = $user AND `date` LIKE '$date';";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $sql = "DELETE FROM `tbl_steps` WHERE `userId` = $user AND `date` LIKE '$date';";
            $result = $this->conn->query($sql);
        }
        $sql = "INSERT INTO `tbl_steps` (`userId`, `steps`, `date`) VALUES ('$user', '$steps', '$date');";
        $result = $this->conn->query($sql);
        if ($result == true) {
            return "success";
        }
        else{
            return "failed";
        }     
    }
}
?>