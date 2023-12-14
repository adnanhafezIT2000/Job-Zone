<?php
    include 'connectDB.php';

        if( isset($_POST['check_submit_button']) ){

            $username = $_POST['username_jobseeker_id'];

            $getUsername = $connect->prepare("SELECT * FROM job_seeker WHERE user_name = ?");
            $getUsername->execute(array( $username));
            $rowCountUsername = $getUsername->rowCount();

            if($rowCountUsername > 0 ){

                    echo 'Sorry , Username already exists.';
            }

        }
?>