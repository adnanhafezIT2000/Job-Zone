<?php
    ob_start();
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);
    $pageTitle = 'Admin Dashboard';
    include '../initialize.php';

    $quary = $connect->prepare("SELECT image , full_name
                                FROM admin
                                WHERE admin_id = ?
                                LIMIT 1
                            ");
    $quary->execute( array($_SESSION['adminID']) );
    $row = $quary->fetch();
?>
    <div class="admin-dashboard">
       <div class="container">
            <div class="row">
                <div class="col-sm-3 side-navbar">
                    <div class="container">
                        <img src="layout/images/logo.png" alt="">
                        <ul>
                           <a href="dashboard.php" class="active-link">
                                <i class="fa fa-bars"></i>
                                <li> dashboard </li>
                           </a>
                           <a href="profile.php">
                                <i class="fa fa-user"></i>
                                <li> profile </li>
                           </a>
                           <a href="categories.php">
                                <i class="fas fa-tags"></i>
                                <li> categories </li>
                           </a>
                           <a href="logout.php">
                                <i class="fa fa-sign-out-alt"></i>
                                <li> logout </li>
                           </a>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-9 content">
                    <div class="row">
                        <div class="col-sm-12 content-navbar">
                            <div class="title">
                                <i class="fa fa-bars"></i>
                                <h3> dashboard </h3>
                            </div>
                            <div class="image">
                                <?php 
                                    if( $row['image'] == '' ){
                            
                                        echo '<img src="layout/images/default.jpg" alt="">';
                                
                                    } else{

                                        echo '<img src="uploads/' . $row['image'] . '">';
                                    }
                                ?>
                                <span class="online"></span>
                                <h4>
                                    <?php 
                                        if( $row['full_name'] == '' ){
                                
                                            echo 'admin';
                                    
                                        } else{

                                            echo $row['full_name'];
                                        }
                                    ?>
                                </h4>
                            </div>
                        </div>
                        <div class="col-sm-12 content-body">
                            <div class="container">
                                <div class="col-sm-3 box">
                                    <i class="fa fa-user"></i>
                                    <span>
                                        <?php
                                            echo getItemCount('job_seeker_id' , 'job_seeker');
                                        ?>
                                    </span>
                                    <p> total job seekers </p>
                                </div>
                                <div class="col-sm-3 box">
                                    <i class="fas fa-building"></i>
                                    <span>
                                        <?php
                                            echo getItemCount('employer_id' , 'employer');
                                        ?>
                                    </span>
                                    <p> total employers </p>
                                </div>
                                <div class="col-sm-3 box">
                                    <i class="fas fa-bullhorn"></i>
                                    <span>
                                        <?php
                                           echo getItemCount('advertisment_id' , 'job_advertisment');
                                        ?>
                                    </span>
                                    <p> total post jobs </p>
                                </div>
                                <div class="col-sm-3 box">
                                    <i class="fa fa-rocket"></i>
                                    <span>
                                        <?php
                                           echo getItemCount('request_id' , 'applicant_request');
                                        ?>
                                    </span>
                                    <p> total job requests </p>
                                </div>
                                <div class="col-sm-3 box">
                                    <i class="fas fa-user-check"></i>
                                    <span>
                                        <?php
                                           $quary = $connect->prepare("SELECT DISTINCT job_seeker_id
                                                                       FROM applicant_request
                                                                       WHERE order_status = 2
                                                                    ");
                                            $quary->execute();
                                            $rowCountJobSeeker = $quary->rowCount();
                                            echo $rowCountJobSeeker;
                                        ?>
                                    </span>
                                    <p> beneficiary job seeker </p>
                                </div>
                                <div class="col-sm-3 box">
                                    <i class="far fa-building"></i>
                                    <span>
                                        <?php
                                           $quary = $connect->prepare("SELECT DISTINCT employer_id
                                                                       FROM applicant_request
                                                                       WHERE order_status = 2
                                                                    ");
                                            $quary->execute();
                                            $rowCountEmployer = $quary->rowCount();
                                            echo $rowCountEmployer;
                                        ?>
                                    </span>
                                    <p> beneficiary employer </p>
                                </div>
                                <div class="col-sm-3 box">
                                    <i class="fas fa-user-clock"></i>
                                    <span>
                                        <?php
                                           echo getItemCount('advertisment_id' , 'applicant_request' , 'WHERE order_status = 1');
                                        ?>
                                    </span>
                                    <p> requests pending </p>
                                </div>
                                <div class="col-sm-3 box">
                                    <i class="fas fa-user-times"></i>
                                    <span>
                                        <?php
                                           echo getItemCount('advertisment_id' , 'applicant_request' , 'WHERE order_status = 2');
                                        ?>
                                    </span>
                                    <p> requests rejected </p>
                                </div>

                                <div class="col-sm-12 job-seeker-percent">
                                    <h3 class="text-center"> percentages related to job seekers </h3>
                                    <div class="content-circle">
                                        <div class="skill">
                                            <div class="outer">
                                                <div class="inner">
                                                    <div class="numberCircleAdmin">
                                                        <span class="numberCircleAdminPercent">
                                                            <?php
                                                                $allJobSeeker = getItemCount('job_seeker_id' , 'job_seeker');
                                                                
                                                                $benJobSeeker = $rowCountJobSeeker;

                                                                $percent = ($benJobSeeker / $allJobSeeker) * 100;
                                                                
                                                                $percent = explode('.' , $percent );

                                                                if( $percent[0] < 10 ){

                                                                        echo '0' . $percent[0];

                                                                } else{

                                                                    echo $percent[0];
                                                                }
                                                                
                                                            ?>
                                                        </span>%
                                                    </div>
                                                </div>
                                            </div>
                                            <svg class="svgAdmin" xmlns="http://www.w3.org/2000/svg" version="1.1" width="160px" height="160px">
                                                <circle class="trueCircle circle" cx="80" cy="80" r="70" stroke-linecap="round" />
                                            </svg>
                                        </div>
                                        <div class="skill">
                                            <div class="outer">
                                                <div class="inner">
                                                    <div class="numberCircleAdmin">
                                                        <span class="numberCircleAdminPercent">
                                                            <?php
                                                                
                                                                $allJobSeeker = getItemCount('job_seeker_id' , 'job_seeker');
                                                                
                                                                $Non_benJobSeeker = $allJobSeeker - $benJobSeeker;

                                                                $percent = ($Non_benJobSeeker / $allJobSeeker) * 100;
                                                                
                                                                $percent = explode('.' , $percent );

                                                                if( $percent[0] < 10 ){

                                                                        echo '0' . $percent[0];

                                                                } else{

                                                                    echo $percent[0];
                                                                }
                                                            ?>
                                                        </span>%
                                                    </div>
                                                </div>
                                            </div>
                                            <svg class="svgAdmin" xmlns="http://www.w3.org/2000/svg" version="1.1" width="160px" height="160px">
                                                <circle class="falseCircle circle" cx="80" cy="80" r="70" stroke-linecap="round" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="content-text">
                                        <p> percentage of beneficiaries  </p>
                                        <p> percentage of non-beneficiaries </p>
                                    </div>
                                </div>

                                <div class="col-sm-12 employer-percent">
                                    <h3 class="text-center"> percentages related to employers </h3>
                                    <div class="content-circle">
                                        <div class="skill">
                                            <div class="outer">
                                                <div class="inner">
                                                    <div class="numberCircleAdmin">
                                                        <span class="numberCircleAdminPercent">
                                                            <?php
                                                                $allEmployer = getItemCount('employer_id' , 'employer');
                                                                
                                                                $benEmployer = $rowCountEmployer;

                                                                $percent = ($benEmployer / $allEmployer) * 100;
                                                                
                                                                $percent = explode('.' , $percent );

                                                                if( $percent[0] < 10 ){

                                                                        echo '0' . $percent[0];

                                                                } else{

                                                                    echo $percent[0];
                                                                }
                                                                
                                                            ?>
                                                        </span>%
                                                    </div>
                                                </div>
                                            </div>
                                            <svg class="svgAdmin" xmlns="http://www.w3.org/2000/svg" version="1.1" width="160px" height="160px">
                                                <circle class="trueCircle circle" cx="80" cy="80" r="70" stroke-linecap="round" />
                                            </svg>
                                        </div>
                                        <div class="skill">
                                            <div class="outer">
                                                <div class="inner">
                                                    <div class="numberCircleAdmin">
                                                        <span class="numberCircleAdminPercent">
                                                            <?php
                                                                
                                                                $allEmployer = getItemCount('employer_id' , 'employer');
                                                                
                                                                $Non_benEmployer = $allEmployer - $benEmployer;

                                                                $percent = ($Non_benEmployer / $allEmployer) * 100;
                                                                
                                                                $percent = explode('.' , $percent );

                                                                if( $percent[0] < 10 ){

                                                                        echo '0' . $percent[0];

                                                                } else{

                                                                    echo $percent[0];
                                                                }
                                                            ?>
                                                        </span>%
                                                    </div>
                                                </div>
                                            </div>
                                            <svg class="svgAdmin" xmlns="http://www.w3.org/2000/svg" version="1.1" width="160px" height="160px">
                                                <circle class="falseCircle circle" cx="80" cy="80" r="70" stroke-linecap="round" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="content-text">
                                        <p> percentage of beneficiaries </p>
                                        <p> percentage of non-beneficiaries </p>
                                    </div>
                                </div>

                                <div class="col-sm-12 request-percent">
                                    <h3 class="text-center"> percentages related to requests </h3>
                                    <div class="content-circle">
                                        <div class="skill">
                                            <div class="outer">
                                                <div class="inner">
                                                    <div class="numberCircleAdmin">
                                                        <span class="numberCircleAdminPercent">
                                                            <?php
                                                                $allRequests = getItemCount('request_id' , 'applicant_request');
                                                                
                                                                $activeRequests = getItemCount('request_id' , 'applicant_request' , 'WHERE order_status = 2');

                                                                $percent = ($activeRequests / $allRequests) * 100;
                                                                
                                                                $percent = explode('.' , $percent );

                                                                if( $percent[0] < 10 ){

                                                                        echo '0' . $percent[0];

                                                                } else{

                                                                    echo $percent[0];
                                                                }
                                                            ?>
                                                        </span>%
                                                    </div>
                                                </div>
                                            </div>
                                            <svg class="svgAdmin" xmlns="http://www.w3.org/2000/svg" version="1.1" width="160px" height="160px">
                                                <circle class="trueCircle circle" cx="80" cy="80" r="70" stroke-linecap="round" />
                                            </svg>
                                        </div>
                                        <div class="skill">
                                            <div class="outer">
                                                <div class="inner">
                                                    <div class="numberCircleAdmin">
                                                        <span class="numberCircleAdminPercent">
                                                            <?php
                                                                
                                                                $allRequests = getItemCount('request_id' , 'applicant_request');
                                                                
                                                                $pendingRequests = getItemCount('request_id' , 'applicant_request' , 'WHERE order_status = 1');

                                                                $percent = ($pendingRequests / $allRequests) * 100;
                                                                
                                                                $percent = explode('.' , $percent );

                                                                if( $percent[0] < 10 ){

                                                                        echo '0' . $percent[0];

                                                                } else{

                                                                    echo $percent[0];
                                                                }
                                                            ?>
                                                        </span>%
                                                    </div>
                                                </div>
                                            </div>
                                            <svg class="svgAdmin" xmlns="http://www.w3.org/2000/svg" version="1.1" width="160px" height="160px">
                                                <circle class="pendingCircle circle" cx="80" cy="80" r="70" stroke-linecap="round" />
                                            </svg>
                                        </div>
                                        <div class="skill">
                                            <div class="outer">
                                                <div class="inner">
                                                    <div class="numberCircleAdmin">
                                                        <span class="numberCircleAdminPercent">
                                                            <?php
                                                                
                                                                $allRequests = getItemCount('request_id' , 'applicant_request');
                                                                
                                                                $rejectedRequests = getItemCount('request_id' , 'applicant_request' , 'WHERE order_status = 0');

                                                                $percent = ($rejectedRequests / $allRequests) * 100;
                                                                
                                                                $percent = explode('.' , $percent );

                                                                if( $percent[0] < 10 ){

                                                                        echo '0' . $percent[0];

                                                                } else{

                                                                    echo $percent[0];
                                                                }
                                                            ?>
                                                        </span>%
                                                    </div>
                                                </div>
                                            </div>
                                            <svg class="svgAdmin" xmlns="http://www.w3.org/2000/svg" version="1.1" width="160px" height="160px">
                                                <circle class="falseCircle circle" cx="80" cy="80" r="70" stroke-linecap="round" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="content-text">
                                        <p> percentage of accepted </p>
                                        <p> percentage of pending </p>
                                        <p> percentage of rejected </p>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
       </div>
    </div>


<?php
    include $tmp . 'footer.php';
    ob_end_flush();
?>