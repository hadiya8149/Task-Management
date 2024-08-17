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
function documentValidation($documentName, $documentSize, $documentTemp, $documentType){
    if(empty($documentName)){
        return "Please select a file";
    }
    $file_info = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $file_info->file($documentTemp);
    echo $mime_type;
    if($mime_type!='application/vnd.openxmlformats-officedocument.wordprocessingml.document'){
        header("location: fileUpload.php?erro=only docx file type is allowed");
        exit;
    }
    $upload_max_size= 10*1024*1024;
    if($documentSize>$upload_max_size){
        return "Document must not be larager than 10 mb";
    }

    $destination_path = getcwd().DIRECTORY_SEPARATOR.'var/www/uploads/';
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

    $filename = documentValidation($_FILES['document']['name'],$_FILES['document']['size'],$_FILES['document']['tmp_name'], $_FILES['document']['type']);
    createTask($title, $description, $status, $tag, $filename,$connection);
}
else{

}



?>