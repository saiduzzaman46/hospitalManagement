<?php


function update_patient($conn, $pid, $fname, $email, $dob, $gender, $address)
{
    date_default_timezone_set('Asia/Dhaka');
    $update_date = date('Y-m-d H:i:s');
    $query = "UPDATE `patientsregister` SET `fname` = '$fname', `email` = '$email', `dob` = '$dob', `gender` = '$gender', `address` = '$address',`update_date` = '$update_date' WHERE `pid` = '$pid'";

    if ($conn->query($query)) {
        return true;
    } else {
        return false;
    }
}
function update_phone($conn, $pid, $phone)
{
    // Update phone number
    $query = "UPDATE `login` SET `phone` = '$phone' WHERE `user_ref_id` = '$pid'";

    if ($conn->query($query)) {
        return true;
    } else {
        return false;
    }
}

function update_password($conn, $pid, $password)
{
    // Update password
    // Hash the password
    // $password = password_hash($password, PASSWORD_DEFAULT);

    $query = "UPDATE `login` SET `password` = '$password' WHERE `user_ref_id` = '$pid'";

    if ($conn->query($query)) {
        return true;
    } else {
        return false;
    }
}
