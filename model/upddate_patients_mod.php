<?php

function update_patient($conn, $id, $fname, $email, $dob, $gender, $address)
{
    // Update patient details
    $query = "UPDATE `patientsregister` SET `fname` = '$fname', `email` = '$email', `dob` = '$dob', `gender` = '$gender', `address` = '$address' WHERE `id` = '$id'";

    if ($conn->query($query)) {
        return true;
    } else {
        return false;
    }
}
function update_phone($conn, $id, $phone)
{
    // Update phone number
    $query = "UPDATE `login` SET `phone` = '$phone' WHERE `user_ref_id` = '$id'";

    if ($conn->query($query)) {
        return true;
    } else {
        return false;
    }
}

function update_password($conn, $id, $password)
{
    // Update password
    $query = "UPDATE `login` SET `password` = '$password' WHERE `user_ref_id` = '$id'";

    if ($conn->query($query)) {
        return true;
    } else {
        return false;
    }
}
