<?php 
include_once 'model/dbconnection.php';
include 'task.php';
$dbInstance = new DbConnection;
define('CONNECTION', $dbInstance->connectDatabase());
if($_SERVER['REQUEST_METHOD']=='GET'){
    $taskId = $_GET['id'];
    $taskData = getTaskById($taskId, CONNECTION);
    $title = $taskData['title'];
    $description = $taskData['description'];
    
}
?>
<html>
<head>
<title>Edit  a task</title>
</head>
<body>
<form enctype="multipart/form-data" action="updateTask.php" id ='updateTask' method='post'>
<input style="width: 200px;" name='title' type="text" value="<?php echo $title;?>">
<input name='description'   value="<?php echo $description;?>"></input>
        <select name='status'>
            <option <?php  echo $taskData['status']=='TODO'?'selected':'' ?> value='TODO'>TODO</option>
            <option <?php  echo $taskData['status']=='IN PROGRESS'?'selected':'' ?>  value='IN PROGRESS'>IN PROGRESS</option>
            <option <?php  echo $taskData['status']=='DONE'?'selected':'' ?> value ='DONE'>DONE</option>
        </select>
        <select name='tag'>
            <option  <?php  echo $taskData['tag']=='bug'?'selected':'' ?> value="bug">type: bug</option>
            <option  <?php  echo $taskData['tag']=='documentation'?'selected':'' ?>  value="documentation">type: documentation</option>
            <option  <?php  echo $taskData['tag']=='question'?'selected':'' ?> value="question">type: question</option>
            <option   <?php  echo $taskData['tag']=='feature request'?'selected':'' ?> value="feature request">type: feature request</option>
            <option   <?php  echo $taskData['tag']=='high'?'selected':'' ?> value="high">priority: high</option>
            <option   <?php  echo $taskData['tag']=='critical'?'selected':'' ?> value="critical">priority: critical</option>
            <option   <?php  echo $taskData['tag']=='medium'?'selected':'' ?>value="medium">priority: medium</option>
            <option   <?php  echo $taskData['tag']=='low'?'selected':'' ?>value="low">priority: low</option>
            <option   <?php  echo $taskData['tag']=='enhancement'?'selected':'' ?>value="enhancement">type: enhancement</option>
        </select>

        <input type="hidden" name="id" value='<?php echo $taskId?>'>
        <span>Allowed type is docx and txt</span>
        <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
        <input name="update_document" type="file" />
<input type='submit'>

</form>
</body>
</html>
