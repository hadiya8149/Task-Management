
<?php 
include_once 'model/dbconnection.php';
$dbInstance = new DbConnection;
define('CONNECTION', $dbInstance->connectDatabase());
function downloadFile($id, $connection=CONNECTION){
    $taskId = (int) $id;
    $sql = "SELECT `filename` from task where id =$taskId";

    $filename = $connection->query($sql)->fetch_assoc()['filename'];

    header('Content-Description: File Transfer');
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Disposition: attachment;filename=Taskfile.docx');
    header('Cache-Control: max-age=0');
    header('Content-Transfer-Encoding: binary');
    
    ob_clean();
    flush();
    
    readfile($filename);
}
if(isset($_GET['taskid'])){
    $id = $_GET['taskid'];
    if(empty($id)){
        header("location: index.php?error=file not found");
        exit;                                                          
    }
    $taskId = (integer) $id;
    downloadFile($id);
}

