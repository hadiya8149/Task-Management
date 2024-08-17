<?php
include_once 'model/dbconnection.php';
$dbInstance = new DbConnection;
define('CONNECTION', $dbInstance->connectDatabase());
function updateTask($taskId, $title, $description, $status, $tag, $connection=CONNECTION){
    $updateTaskQuery = "UPDATE task set `title`='$title', `description`='$description', `status`='$status', `tag`='$tag' where id = $taskId";
    try{
        $connection->query($updateTaskQuery);
        header("location: index.php?success=task updated successfully");
    } 
    catch(mysqli_sql_exception $exception){
        header("location: task.php?error=please try again later");
    }
}
$taskId = (int) $_POST['id'];
$title = $_POST['title'];
$description  = $_POST['description'];
$status = $_POST['status'];
$tag = $_POST['tag'];
updateTask($taskId, $title, $description, $status, $tag);
