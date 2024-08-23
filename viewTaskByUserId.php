<?php 
include_once 'model/dbconnection.php';
$dbInstance = new DbConnection();
define("CONNECTION2", $dbInstance->connectDatabase());
function getAssignedTaskByUserId($username, $connection=CONNECTION2){
    $getUserId = $connection->prepare("select user_id from user_profile where username = ?;");
    $getUserId->bind_param('s', $username);
    $getUserId->execute();
    $result = $getUserId->get_result();
    if($result){
        $userId =$result->fetch_assoc()['user_id'];
        $sql = $connection->prepare("select task.title, task.description, task.status, task.tag from task_assignment join task on task_assignment.task_id=task.id where task_assignment.assignee_id=?");
        $sql->bind_param('i', $userId);
        $sql->execute();
        $tasks = $sql->get_result();
        try{
        $allTaskAssignedToUser  = mysqli_fetch_all($tasks, MYSQLI_ASSOC);
        return $allTaskAssignedToUser;
        }
        catch(mysqli_sql_exception $exception){
            print_r($exception);
        }
    }
}
if ($_SERVER['REQUEST_METHOD']=='POST'){
    $username = $_POST['username'];
    $allTaskAssignedToUser=getAssignedTaskByUserId($username);
       
    print_r(json_encode($allTaskAssignedToUser, JSON_FORCE_OBJECT));
}