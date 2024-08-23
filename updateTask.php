<?php
// to fix file error problems
// rename the file to timestamp and then store it in database
include_once 'model/dbconnection.php';
$dbInstance = new DbConnection;
define('CONNECTION', $dbInstance->connectDatabase());
function updateTask($taskId, $title, $description, $status, $tag, $filename, $connection = CONNECTION){

    try {
        $filename = mysqli_real_escape_string($connection, $filename);

        $updateTaskQuery = $connection->prepare("UPDATE task set `title`=?, `description`=?, `status`=?, `tag`=?, `filename`=? where id = ?;");
        $updateTaskQuery->bind_param('sssssi', $title, $description, $status, $tag, $filename, $taskId);
        $updateTaskQuery->execute();
        header("location: index.php?success=task updated successfully");
    } catch (mysqli_sql_exception $exception) {
        header("location: task.php?error=please try again later");
    }
}

function documentValidation($documentName, $documentSize, $documentTemp, $documentType)
{

    if (empty($documentName)) {
        header("location: editTask.php?error=Please select a file");
        exit;
    }
    $file_info = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $file_info->file($documentTemp);
    if ($mime_type != 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
        header("location: index.php?erro=only docx file type is allowed");
        exit;
    }
    $upload_max_size = 10 * 1024 * 1024;
    if ($documentSize > $upload_max_size) {
        return "Document must not be larager than 10 mb";
    }

    $destination_path = getcwd() . DIRECTORY_SEPARATOR . 'var\www\uploads\\';
    $target_path = $destination_path . basename($_FILES["update_document"]["name"]);
    if (move_uploaded_file($_FILES['update_document']['tmp_name'], $target_path)) {
        return $target_path;
    } else {
        header("location: index.php?error=could not upload file");
        exit;
    }

}

if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['status']) && isset($_POST['tag'])) {
    if (empty($_POST['id']) || empty($_POST['title']) || empty($_POST['description']) || empty($_POST['status']) || empty($_POST['tag'])) {
        header("location: editTask.php?error=All fields are required");
        exit;
    }


    $taskId = (int) $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $tag = $_POST['tag'];

    $filename = documentValidation($_FILES['update_document']['name'], $_FILES['update_document']['size'], $_FILES['update_document']['tmp_name'], $_FILES['update_document']['type']);
    updateTask($taskId, $title, $description, $status, $tag, $filename);

}
