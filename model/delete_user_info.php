<?php

function delete_user_info($conn, $id) {
    $query = "DELETE FROM `patientsregister` WHERE `pid` = '$id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $query2 = "DELETE FROM `login` WHERE `user_ref_id` = '$id'";
        $result2 = mysqli_query($conn, $query2);
        if ($result2) {
            return true; 
        } else {
            return false; 
        }
    } else {
        return false; 
    }
}

function delete_doctor_info($conn, $id) {
    $query = "DELETE FROM `doctorregister` WHERE `did` = '$id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $query2 = "DELETE FROM `login` WHERE `user_ref_id` = '$id' AND `role` = 'doctor'";
        $result2 = mysqli_query($conn, $query2);
        if ($result2) {
            return true; 
        } else {
            return false; 
        }
    } else {
        return false; 
    }
}