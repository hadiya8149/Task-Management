<?php 
include_once 'model/dbconnection.php';
$dbInstance = new DbConnection();
define("CONNECTION2", $dbInstance->connectDatabase());
function getAssignedTaskByUserId($username, $connection=CONNECTION2){
    $getUserId = "select user_id from user_profile where username = '$username';";
    $userId = $connection->query($getUserId)->fetch_assoc()['user_id'];
    // join with 
    $sql = "select * from task_assignment join task on task_assignment.task_id=task.id where task_assignment.assignee_id=$userId;";
    $result = $connection->query($sql);
    $allTaskAssignedToUser  = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $allTaskAssignedToUser;

}

if ($_SERVER['REQUEST_METHOD']=='POST'){
    $username = $_POST['username'];
    $allTaskAssignedToUser=getAssignedTaskByUserId($username);
    
    print_r(json_encode($allTaskAssignedToUser, JSON_FORCE_OBJECT));
}