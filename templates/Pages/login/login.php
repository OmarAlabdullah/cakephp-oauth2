<?php
if (isset($_POST["submit"])){
    echo "it works";
    $email = $_POST["login-email"];
    $password = $_POST["login-password"];


}
else{
    header("location: ../index.php");
}

function login( $email, $password)
{
    try {

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
