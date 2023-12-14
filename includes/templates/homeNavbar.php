<?php
    session_start();                
?>
<nav>
    <div class="container">
        <a href="homePage.php">
            <img src="layout/images/images/logo.png">
        </a>
        <?php
            if( isset($_SESSION['username']) ){ 
                
                $quary = $connect->prepare("
                    SELECT *
                    FROM job_seeker 
                    WHERE job_seeker_id = ?
                    LIMIT 1 
                ");
                $quary->execute( array($_SESSION['jobSeekerID']) );
                $rows = $quary->fetchAll();
                
                ?>

                <div class='nav-job-seeker'>
                    <div class='content'>
                        <a 
                            href="jobSeekerProfile.php" 
                            title="<?php 
                                        foreach($rows as $row){

                                            echo $row['full_name'];
                                        }
                                   ?>"
                        >
                            <div class="profile-image">
                                <?php

                                    foreach($rows as $row){

                                        if( $row['image'] == '' ){
                                
                                            $name =  explode(" " , $row['full_name']);
                                
                                            $lastName = array_pop($name);
                                
                                            $firstName = substr($name['0'] , '0' , '1');
                                            $lastName  = substr($lastName , '0' , '1');
                                
                                            echo '<span>'. $firstName . $lastName .'</span>';
                                    
                                        } else{

                                            echo '<img src="uploads/Job Seekers/' . $row['image'] . '">';
                                        }
                                    }
                                ?>
                            </div>
                        </a>
                        <ul>
                            <li> <a href="jobSeekerProfile.php"> profile </a> </li>
                            <li> <a href="findWork.php"> find work </a> </li>
                            <li> <a href="jobSeekerRequests.php"> my requests </a> </li>
                            <li> <a href="logout.php"> log out </a> </li>
                        </ul>
                    </div>
                </div>
               

            <?php } elseif( isset($_SESSION['companyName']) ){

                        $quary = $connect->prepare("
                                    SELECT *
                                    FROM employer 
                                    WHERE employer_id = ?
                                    LIMIT 1 
                            ");
                        $quary->execute( array($_SESSION['companyID']) );
                        $rows = $quary->fetchAll();

                    ?>

                    <div class='nav-job-seeker'>
                        <div class='content'>
                            <a 
                                href="employerProfile.php" 
                                title="<?php 
                                            foreach($rows as $row){

                                                echo $row['employer_name'];
                                            }
                                    ?>"
                            >
                                <div class="profile-image">
                                    <?php
                                        foreach($rows as $row){

                                            if( $row['logo'] == '' ){
                                    
                                                echo '<span> <i class="fas fa-building"></i> </span>';
                                        
                                            } else{

                                                echo '<img src="uploads/Employers/' . $row['logo'] . '">';
                                            }
                                        }
                                    ?>
                                </div>
                            </a>
                            <?php
                                $quary= $connect->prepare("SELECT COUNT(request_id) as countRequest
                                                            FROM applicant_request 
                                                            WHERE 
                                                                employer_id = ?
                                                            AND 
                                                                order_status = 1
                                                            LIMIT 1
                                ");
                                $quary->execute( array($_SESSION['companyID']) );
                                $row = $quary->fetch();

                            ?>
                            <ul class="links-employer">
                                <li> <a href="employerProfile.php"> profile </a> </li>
                                <li> <a href="post a job.php"> post a job </a> </li>
                                <li> <a href="employerManageMyAds.php"> manage my ads </a> </li>
                                <li> 
                                    <a 
                                        href="manageApplicantRequest.php" 
                                        class="link-manage-applicant-requests"> 
                                            Manage Applicant Requests
                                            
                                            <?php
                                                if( $row['countRequest'] == 0 ){


                                                } else{ ?>

                                                    <div class="count-notifications">
                                                        <p>
                                                            <?php echo $row['countRequest'];?>
                                                        </p>
                                                    </div>
                                                <?php }
                                            ?>
                                    </a> 
                                </li>
                                <li> <a href="logout.php"> log out </a> </li>
                            </ul>
                        </div>
                    </div>

            <?php
            } else{

                echo "
                        <div class='links-login-signup'>
                            <a href='login.php'>
                                log in
                            </a>
                            <a href='sign-in.php'>
                                sign up
                            </a>
                        </div>
                ";
            }
        ?>
    </div>
</nav>
