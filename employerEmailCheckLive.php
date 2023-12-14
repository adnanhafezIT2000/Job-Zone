<?php
    include 'connectDB.php';

        if( isset($_POST['check_submit_button']) ){

            $email = $_POST['email_employer_id'];

            $getEmail = $connect->prepare("SELECT * FROM employer WHERE employer_email = ?");
            $getEmail->execute(array( $email));
            $rowCountEmail = $getEmail->rowCount();

            if($rowCountEmail > 0 ){

                    echo 'Sorry , Email already exists.';
            }

        }
?>