<?php 
session_start();
if(isset($_SESSION['email'])){
    header("location: home.php");
    exit();
}
include 'model/dbconnection.php';
$connection = new DbConnection;
$conn = $connection->connectDatabase();
function validate($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
function formValidation(){
    if(isset($_POST['email']) && isset($_POST['password'])&& isset($_POST['address']) && isset($_POST['cnic'])&&isset($_POST['dateofbirth'])&&isset($_POST['gender'])&&isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['confirm-password'])){
    
        if(empty($email) || empty($password) || empty($confirmPassword)){
            header("location: register.php?error=email or password is empty");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("location: register.php?error=invalid email format");
            exit();
        }
        validatePassword($password, $confirmPassword);

    }
}
function validateDate($date){
    $validDate = date_create_from_format('Y-m-d', $date);
    if(!$validDate){
       header("location: register.php?error=Invalid Date");
       exit();
    }
    else{
        return $date;
    }
}
function validateCnic($cnic){
    $cnic =(string) $cnic; 
    if ((preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $cnic )or (strlen($cnic)!==13))){
        header("location: register.php?error=Invalid CNIC number");
        exit();
    }
    return $cnic;
}
function validatePassword($password, $confirmPassword){
    if($password===$confirmPassword){
        return TRUE;
    }
    else{
        header("location:register.php?error=Passwords do not match");
    }
}
function insertUserIntoDatabase($email, $password, $connection){
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $userExistQuery = "SELECT * FROM user where email='$email'";
    $userExistResult = $connection->query($userExistQuery);
    if($userExistResult->num_rows>0){
        header("location: register.php?error=Account already exists");
    }
    else{
        $insertQuery = "INSERT INTO user (email,password) VALUES('$email' , '$hashedPassword');";
        try{
            if($connection->query($insertQuery)==TRUE){
                $userId = $connection->insert_id;
                echo $userId;
                $insertUserProfileData = "INSERT INTO user_profile (user_id, first_name, last_name, `address`, gender, cnic, date_of_birth) VALUES($userId, '$fname',' $lname',' $address', '$gender',' $cnic','$dateofbirth');";
             
                if($connection->query($insertUserProfileData)==TRUE){
                    echo('<script>alert("signed up successfully")</script>');
                    header('location: login.php');
                    exit;
                }
                else{
                    header("location: register.php?error=$userId");
                    exit;
                }
            }

        }
        catch(mysqli_sql_exception $exception){
            header("location: register.php?error=Please try again later");
            exit();
        }
    }
}
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['email']) && isset($_POST['password'])&& isset($_POST['address']) && isset($_POST['cnic'])&&isset($_POST['dateofbirth'])&&isset($_POST['gender'])&&isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['confirm-password'])){
        $fname = validate($_POST['fname']);
        $lname = validate($_POST['lname']);
        $email = validate($_POST['email']);
        $address = validate($_POST['address']);
        $gender = validate($_POST['gender']);
        $dateofbirth = validateDate($_POST['dateofbirth']);
        $cnic = validateCnic($_POST['cnic']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm-password'];
        validatePassword($password, $confirmPassword);
        insertUserIntoDatabase($email,$password, $conn);
    
    }
    else{
        header("location : register.php?error=All fields are required!");
        exit();
    }
}
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link rel="stylesheet" href="css/style.css">

    <div class="container d-flex align-items-center justify-content-center h-100">
    <form id='signupform' action='signup.php' class="form text-center" method='post'>
<h1 class="text-center"> Create an Account</h1>
<p class="error">
    <?php if(isset($_GET['error'])){
         echo $_GET['error'];   
    }
    ?>
   <div class='form-group text-left'>
    
   <input type='text' class='form-control' name='fname' placeholder="First Name" required>
    <input type='text' class='form-control' name='lname' placeholder="Last Name" required>
    <input type='email' class='form-control' name ='email' placeholder="Enter email" required>
    <input type='address' class='form-control' name ='address' placeholder="Address" requried>
    <input type='number' class='form-control' name ='cnic' placeholder="Enter cnic without dashes" required>
    <input type='date' class='form-control' name='dateofbirth' placeholder='yyyy/mm/dd' required>
   
    <select name="gender" id='gender' class='form-select' required> 
<option value='female' selected >Female</option>
<option  value='male' >Male</option>
<option value='others'>Others</option>
</select>


    <input type='password' class='form-control' name='password' placeholder="Enter Password" required>
    <input type='password' class='form-control' name='confirm-password' placeholder="Re enter password" required>

<div class='text-center '>
<button type='submit' class='btn btn-primary m-auto signup-btn' name='signup'>Signup</button> 
</div>
</div>
</form>
</div>