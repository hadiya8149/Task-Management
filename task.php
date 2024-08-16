<?php 
include 'model/dbconnection.php';

$dbInstance = new DbConnection;
define ("CONNECTION",  $dbInstance->connectDatabase());
function getAllTasks(){
    $getAllTasksQuery = "SELECT * FROM task;";
    $result = CONNECTION->query($getAllTasksQuery);
    $allTasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $allTasks;
    //PROBLEM 
    //how to render it in html page 
    // solution: return a json object and make a javascript function to add content to table.

}
function createTask($title, $description, $status, $tag){ // post request for create task
    $createTaskQuery = "INSERT INTO task (`title`, `description`, `status`, `tag`) VALUES ('$title', '$description',' $status', '$tag');";
    try{
        $result = CONNECTION->query($createTaskQuery);
        
        header("location: index.php?success=Created Task successfully");
        exit;
    }
    catch(mysqli_sql_exception $exception){
        return $exception;
    };
}

function getTaskById($taskId){
    $getTaskByIdQuery  = "SELECT * FROM task where id=$taskId";
    $taskDetail = CONNECTION->query($getTaskByIdQuery)->fetch_assoc();
    return $taskDetail;
}

if ($_SERVER['REQUEST_METHOD']=='POST'){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $tag = $_POST['tag'];
    createTask($title, $description, $status, $tag);
}
else{

}