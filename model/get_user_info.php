<?php

function get_email($conn, $email)
{
    $query = "SELECT * FROM `patientsregister` WHERE `email` = '$email';";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}
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
function get_email_update($conn, $email, $excludeUserId)
{
    $query = "SELECT * FROM `patientsregister` WHERE `email` = '$email' AND `pid` != '$excludeUserId';";
    $result = mysqli_query($conn, $query);
    return (mysqli_num_rows($result) > 0);
}

function get_phone_update($conn, $phone, $excludeUserId)
{
    $query = "SELECT * FROM `login` WHERE `phone` = '$phone' AND `user_ref_id` != '$excludeUserId';";
    $result = mysqli_query($conn, $query);
    return (mysqli_num_rows($result) > 0);
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
    $query = "SELECT * FROM `patientsregister` WHERE `pid` = '$user_ref_id';";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}
function get_user_phone($conn, $user_ref_id)
{
    $query = "SELECT `phone` FROM `login` WHERE `user_ref_id` = '$user_ref_id';";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['phone'];
    } else {
        return false;
    }
}

function get_password_by_id($conn, $id, $password)
{
    $query = "SELECT * FROM `login` WHERE `user_ref_id` = '$id' AND `password` = '$password'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['password'];
    } else {
        return false;
    }
}

function get_last_pid($conn)
{
    $query = "SELECT `pid` FROM `patientsregister` ORDER BY `id` DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['pid'];
    } else {
        return false;
    }
}

function get_last_did($conn)
{
    $query = "SELECT `did` FROM `doctorregister` ORDER BY `id` DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['did'];
    } else {
        return false;
    }
}

function get_doctor_phone($conn, $phone)
{
    $query = "SELECT `phone` FROM `login` WHERE `phone` = '$phone'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}
function get_doctor_password($conn, $did, $password)
{
    $query = "SELECT * FROM `login` WHERE `user_ref_id` = '$did' AND `password` = '$password'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['password'];
    } else {
        return false;
    }
}

function get_doctor_email($conn, $email)
{
    $query = "SELECT `email` FROM `doctorregister` WHERE `email` = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}


function get_doctor_info($conn, $did)
{
    $query = "SELECT * FROM `doctorregister` WHERE `did` = '$did'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}