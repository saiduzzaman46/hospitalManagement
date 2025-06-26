<?php
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

function update_doctor($conn, $did, $fname, $specialty, $email, $phone, $fees, $info, $availability)
{
    
    $query = "UPDATE `doctorregister` 
              SET `fname` = '$fname', `specialty` = '$specialty', `email` = '$email', 
                  `fees` = '$fees', `info` = '$info', `availablity` = '$availability' 
              WHERE `did` = '$did'";

    if ($conn->query($query)) {
        $login_query = "UPDATE `login` 
                        SET `phone` = '$phone' WHERE `user_ref_id` = '$did' AND `role` = 'doctor'";

        if ($conn->query($login_query)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
