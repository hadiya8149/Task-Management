<?php 
function getAllTasks($connection=CONNECTION){
    $getAllTasksQuery = "SELECT * FROM task;";
    $result = $connection->query($getAllTasksQuery);
    $allTasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $allTasks;

}
function createTask($title, $description, $status, $tag, $connection){ // post request for create task
    $createTaskQuery = "INSERT INTO task (`title`, `description`, `status`, `tag`) VALUES ('$title', '$description',' $status', '$tag');";
    try{
        $result = $connection->query($createTaskQuery);
        
        header("location: index.php?success=Created Task successfully");
        exit;
    }
    catch(mysqli_sql_exception $exception){
        return $exception;
    };
}
function getTaskById($taskId, $connection){
    $getTaskByIdQuery  = "SELECT * FROM task where id=$taskId";
    $taskDetail = $connection->query($getTaskByIdQuery)->fetch_assoc();
    return $taskDetail;
}
if ($_SERVER['REQUEST_METHOD']=='POST'){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $tag = $_POST['tag'];
    createTask($title, $description, $status, $tag, $connection);
}
else{

}