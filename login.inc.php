<?php 

if(isset($_POST['submit'])){

    $username = $_POST['user_name'];
    $password = $_POST['pwd'];


    
    require_once('connect_me.php');
    require_once('functions.inc.php');

    $conn = DB::conn();

    
    if(emptyInputLogin($username,$password) !== false){
        header("location: login/emptyinput");
        exit();
    }

    loginUser($conn,$username,$password);




}else{
    
    header("location: login/New");
    exit();

}