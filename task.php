<?php 
include_once 'model/dbconnection.php';
$dbInstance = new DbConnection;
define('CONNECTION4', $dbInstance->connectDatabase());
function getAllTasks($connection=CONNECTION4){
    $getAllTasksQuery = "SELECT * FROM task;";
    $result = $connection->query($getAllTasksQuery);
    $allTasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $allTasks;

}
function createTask($title, $description, $status, $tag,$filename, $connection=CONNECTION4){ // post request for create task
    $filename = mysqli_real_escape_string($connection, $filename);
    $createTaskQuery = "INSERT INTO task (`title`, `description`, `status`, `tag`, `filename`) VALUES ('$title', '$description',' $status', '$tag', '$filename');";
    try{
        $result = $connection->query($createTaskQuery);
        header("location: index.php?success=Created Task successfully");
    }
    catch(mysqli_sql_exception $exception){
        return $exception;
    };
}
function getTaskById($taskId, $connection=CONNECTION4){
    $getTaskByIdQuery  = "SELECT * FROM task where id=$taskId";
    $taskDetail = $connection->query($getTaskByIdQuery)->fetch_assoc();
    return $taskDetail;
}
function documentValidation($documentName, $documentSize, $documentTemp, $documentType){
    if(empty($documentName)){
        header("location: index.php?error=Please select a file");
        exit;
    }
    $file_info = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $file_info->file($documentTemp);
    echo $mime_type!='application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    if($mime_type!='application/vnd.openxmlformats-officedocument.wordprocessingml.document'){
        header("location: index.php?erro=only docx file type is allowed");
        exit;
    }
    $upload_max_size= 10*1024*1024;
    if($documentSize>$upload_max_size){
        return "Document must not be larager than 10 mb";
    }
    $destination_path = getcwd().DIRECTORY_SEPARATOR.'var\www\uploads\\';
    $target_path = $destination_path . basename( $_FILES["document"]["name"]);
    if (move_uploaded_file($_FILES['document']['tmp_name'], $target_path)) {
        return $target_path;
    } else {
        header("location: index.php?error=could not upload file");
        exit;
    }   
}
if (isset($_POST['create-task'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $tag = $_POST['tag'];
    var_dump($_FILES);
    $filename = documentValidation($_FILES['document']['name'],$_FILES['document']['size'],$_FILES['document']['tmp_name'], $_FILES['document']['type']);
    // $filename = str_replace('\\', '', $filename);
    
    createTask($title, $description, $status, $tag, $filename);
}
else{

}



?>