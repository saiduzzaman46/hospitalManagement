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

    $query = "INSERT INTO `patientsregister` (`pid`,`fname`, `email`, `dob`, `gender`, `address`) 
              VALUES ('$pid','$fname', '$email', '$dob', '$gender', '$address')";
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
