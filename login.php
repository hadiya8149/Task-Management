<?php 
session_start();
if(isset($_SESSION['email'])){
    header("location: home.php");
    exit();
}
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link rel="stylesheet" href="css/style.css">
<div class="container">
<div class="d-flex align-items-center justify-content-center form-container h-100">
<form class="form text-center" id='loginform' action='authentication.php' method='post'>
<h1 class="text-center"> Login </h1>
    <p class="error">
    <?php if(isset($_GET['error'])){
         echo $_GET['error'];   
    }
    ?>
    </p>
<div class="form-group">

    <input type='email' id="login_email" class='form-control' name='email'/>
    <input type='password' id="login_password" class='form-control' name='password'/>
    <div class="text-center">

<button type='submit' class='btn btn-primary m-auto' name='signup'>Login</button> 
</div>
</div>
</form>
</div>
</div>
