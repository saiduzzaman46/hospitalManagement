<?php
require_once '../config/connection.php';
// function get_email_exist($conn, $email)
// {
//     $query = "SELECT * FROM `patientsregister` WHERE `email` = '$email';";
//     $result = mysqli_query($conn, $query);
//     if (mysqli_num_rows($result) > 0) {
//         return true;
//     } else {
//         return false;
//     }
// }
// function get_phone_exist($conn, $phone)
// {
//     $query = "SELECT * FROM `login` WHERE `phone` = '$phone';";
//     $result = mysqli_query($conn, $query);
//     if (mysqli_num_rows($result) > 0) {
//         return true;
//     } else {
//         return false;
//     }
// }

function insert_patient($conn,$pid, $fname, $email, $password, $phone, $dob, $gender, $address)
{
    if (strpos($phone, '+88') === 0) {
        $phone = substr($phone, 3);
    }

    date_default_timezone_set('Asia/Dhaka');
    $register_date = date('Y-m-d H:i:s');

    // Hash the password
    // $password = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepare the SQL query
    $query = "INSERT INTO `patientsregister` (`pid`, `fname`, `email`, `dob`, `gender`, `address`, `register_date`) 
              VALUES ('$pid', '$fname', '$email', '$dob', '$gender', '$address', '$register_date')";
    
    if ($conn->query($query)) {
        $role = 'patient';
        $login_query = "INSERT INTO `login` (`phone`, `password`, `user_ref_id`, `role`) 
                        VALUES ('$phone', '$password', '$pid', '$role')";
        
        if ($conn->query($login_query)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}   

/*
Example data for insert_patient():
$pid = 'P12345';
$fname = 'John Doe';
$email = 'johndoe@example.com';
$password = 'securePassword123';
$phone = '+8801712345678';
$dob = '1990-05-15';
$gender = 'Male';
$address = '123 Main Street, Dhaka';

Usage:
insert_patient($conn, $pid, $fname, $email, $password, $phone, $dob, $gender, $address);
*/
/*
More example data for insert_patient():
$pid = 'P54321';
$fname = 'Jane Smith';
$email = 'janesmith@example.com';
$password = 'anotherSecurePass456';
$phone = '+8801812345678';
$dob = '1985-10-20';
$gender = 'Female';
$address = '456 Second Avenue, Chittagong';

$pid = 'P67890';
$fname = 'Ali Rahman';
$email = 'alirahman@example.com';
$password = 'passAli789';
$phone = '+8801912345678';
$dob = '1992-03-12';
$gender = 'Male';
$address = '789 Third Road, Sylhet';

$pid = 'P13579';
$fname = 'Fatema Begum';
$email = 'fatema.begum@example.com';
$password = 'fatemaPass321';
$phone = '+8801711122233';
$dob = '1995-07-25';
$gender = 'Female';
$address = '101 Fourth Lane, Khulna';
*/