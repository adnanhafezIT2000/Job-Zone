<?php
    include 'connectDB.php';

        if( isset($_POST['check_submit_button']) ){

            $email = $_POST['email_jobseeker_id'];

            $getEmail = $connect->prepare("SELECT * FROM job_seeker WHERE email = ?");
            $getEmail->execute(array( $email));
            $rowCountEmail = $getEmail->rowCount();

            if($rowCountEmail > 0 ){

                    echo 'Sorry , Email already exists.';
            }

        }
?>