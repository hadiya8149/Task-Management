<?php
include 'model/dbconnection.php';
$dbInstance = new DbConnection;
define ("CONNECTION",  $dbInstance->connectDatabase());
function updateTask($taskId, $title, $description, $status, $tag){
    $updateTaskQuery = "UPDATE task set `title`='$title', `description`='$description', `status`='$status', `tag`='$tag' where id = $taskId";
    try{
        CONNECTION->query($updateTaskQuery);
    } 
    catch(mysqli_sql_exception $exception){
        var_dump($exception);
        // header("location: task.php?error=please try again later");
    }
}
var_dump($_POST);
$taskId = (int) $_POST['id'];
$title = $_POST['title'];
$description  = $_POST['description'];
$status = $_POST['status'];
$tag = $_POST['tag'];
updateTask($taskId, $title, $description, $status, $tag);
