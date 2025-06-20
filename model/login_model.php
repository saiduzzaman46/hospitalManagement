<?php
function get_phone($conn, $phone)
{
    $query = "SELECT * FROM `login` WHERE `phone` = '$phone';";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}
function get_password($conn, $phone, $password)
{
    $query = "SELECT * FROM `login` WHERE `phone` = '$phone' AND `password` = '$password';";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function get_user_ref_id($conn, $phone)
{
    $query = "SELECT `user_ref_id` FROM `login` WHERE `phone` = '$phone';";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['user_ref_id'];
    } else {
        return false;
    }
}

function get_role($conn, $phone)
{
    $query = "SELECT `role` FROM `login` WHERE `phone` = '$phone'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['role'];
    } else {
        return false;
    }
}

function get_user_info($conn, $user_ref_id)
{
    $query = "SELECT * FROM `patientsregister` WHERE `id` = '$user_ref_id';";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}
