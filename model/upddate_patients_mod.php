<?php

function update_patient($conn, $pid, $fname, $email, $dob, $gender, $address)
{
    // Update patient details
    $query = "UPDATE `patientsregister` SET `fname` = '$fname', `email` = '$email', `dob` = '$dob', `gender` = '$gender', `address` = '$address' WHERE `pid` = '$pid'";

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
    $query = "UPDATE `login` SET `password` = '$password' WHERE `user_ref_id` = '$pid'";

    if ($conn->query($query)) {
        return true;
    } else {
        return false;
    }
}
