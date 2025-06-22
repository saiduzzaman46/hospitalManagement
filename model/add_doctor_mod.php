<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
function insert_doctor($conn, $did, $fname, $specialty, $email, $phone, $password, $fees, $info, $availability)
{

    $query = "INSERT INTO `doctorregister` (`did`, `fname`, `specialty`, `email`, `fees`, `info`, `availablity`) 
              VALUES ('$did', '$fname', '$specialty', '$email', '$fees', '$info', '$availability')";

    if ($conn->query($query)) {
        $role = 'doctor';
        $login_query = "INSERT INTO `login` (`phone`, `password`, `user_ref_id`, `role`) 
                        VALUES ('$phone', '$password', '$did', '$role')";

        if ($conn->query($login_query)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
