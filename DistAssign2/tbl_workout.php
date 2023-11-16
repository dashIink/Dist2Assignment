<?php
include_once("db.php");


class tbl_workout{
    public $conn;


    function __construct($conn){
        $this->conn = $conn;

    }

    function addToTable($user, $workout, $specifics){
        $sql = "INSERT INTO `tbl_workout` (`userid`, `workout`, `specifics`) VALUES ('$user', '$workout', '$specifics');";
        if ($this->conn->query($sql) === TRUE) {
            return "success";
          } else {
            return "Error: " . $sql . "<br>" . $conn->error;
          }
    }
    function getTable($user){
        $sql = "SELECT * FROM `tbl_workout` WHERE `userid` = $user;";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $returnArr = array();
            while($row = $result->fetch_assoc()) {
              $returnArr[0][] = $row["workout"];
              $returnArr[1][] = $row["specifics"];
            }
        } else {
            $returnArr = array();
        }
          return $returnArr;
    }
    function deleteTable($user){
        $sql = "DELETE FROM `tbl_workout` WHERE `userid` = $user;";
        if ($this->conn->query($sql) === TRUE) {
            return "success";
        } else {
            return "Error: " . $sql . "<br>" . $conn->error;
        }
          return $returnArr;
    }
    
}
?>