<?php
    ob_start();
    $pageTitle = 'Manage Applicant Requests';
    $homePage = '';
    include 'initialize.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'default';

    //Fetch All Advirtisment
    $quaryAllAdvirtisments = $connect->prepare("SELECT * 
                                                FROM job_advertisment 
                                                WHERE employer_id = ? 
                                                ORDER BY date_of_publication DESC
                                            ");
    $quaryAllAdvirtisments->execute( array($_SESSION['companyID']) );
    $allAdvirtisments = $quaryAllAdvirtisments->fetchAll();

    if($do == 'default'){

            // Fetch All Applicant Requests
            $quary = $connect->prepare("SELECT * 
                                        FROM applicant_request 
                                        WHERE employer_id = ? 
                                        ORDER BY date_of_applicant DESC
                                    ");
            $quary->execute( array($_SESSION['companyID']) );
            $applicantRequests = $quary->fetchAll();
            $rowsCount = $quary->rowCount();

            ?>
            
            <div class="applicant_request_page">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-3 filter-request">
                            <h3> filter requests </h3>
                            <form id="filter-requests" action="manageApplicantRequest.php?do=advertising-requests" method="POST">
                                <select name="advirtismentID" oninput="check_select_ads_filter_requests()">
                                    <option value="All-Requests" selected> All Requests </option>
                                    <?php
                                        foreach($allAdvirtisments as $ad){

                                            echo '<option value="'.$ad['advertisment_id'].'">';

                                                echo $ad['job_title'];

                                            echo '</option>';
                                        }
                                    ?>
                                </select>

                                <ul>
                                    <a class="all allActive">
                                        <li> 
                                            all 
                                            (
                                                <?php echo $rowsCount;?>
                                            ) 
                                        </li>
                                    </a>
                                    <a href="manageApplicantRequest.php?do=allRequests&status=2" class="accepted">
                                        <li> 
                                            accepted 
                                            (
                                                <?php 
                                                    $quary = $connect->prepare("SELECT * 
                                                                                FROM applicant_request 
                                                                                WHERE employer_id = ? 
                                                                                AND order_status = 2
                                                                            ");
                                                    $quary->execute( array($_SESSION['companyID']) );
                                                    $rowsCountAccepted = $quary->rowCount(); 
                                                    
                                                    echo $rowsCountAccepted;
                                                ?>
                                            ) 
                                        </li>
                                    </a>
                                    <a href="manageApplicantRequest.php?do=allRequests&status=1" class="pending">
                                        <li> 
                                            pending 
                                            (
                                                <?php 
                                                    $quary = $connect->prepare("SELECT * 
                                                                                FROM applicant_request 
                                                                                WHERE employer_id = ? 
                                                                                AND order_status = 1
                                                                            ");
                                                    $quary->execute( array($_SESSION['companyID']) );
                                                    $rowsCountPending = $quary->rowCount(); 
                                                    
                                                    echo $rowsCountPending;
                                                ?>
                                            ) 
                                        </li>
                                    </a>
                                    <a href="manageApplicantRequest.php?do=allRequests&status=0" class="rejected">
                                        <li> 
                                            rejected 
                                            (
                                                <?php 
                                                    $quary = $connect->prepare("SELECT * 
                                                                                FROM applicant_request 
                                                                                WHERE employer_id = ? 
                                                                                AND order_status = 0
                                                                            ");
                                                    $quary->execute( array($_SESSION['companyID']) );
                                                    $rowsCountRejected = $quary->rowCount(); 
                                                    
                                                    echo $rowsCountRejected;
                                                ?>
                                            ) 
                                        </li>
                                    </a>
                                </ul>
                            </form>
                        </div>

                        <div class="col-sm-9 requests">

                            <?php
                                if( $rowsCount == 0 ){
                                        
                                    echo '<div class="no-request">';
                                        echo '<h1> no requests </h1>';
                                    echo '</div>';

                                } else{

                                    foreach($applicantRequests as $request){

                                        //Fetch Job Seeker
                                        $quaryJobSeeker = $connect->prepare("SELECT full_name , image  
                                                                    FROM job_seeker 
                                                                    WHERE job_seeker_id = ? 
                                                                    LIMIT 1
                                                            ");
                                        $quaryJobSeeker->execute( array($request['job_seeker_id']) );
                                        $jobSeeker = $quaryJobSeeker->fetch();

                                        //Fetch Advirtisment
                                        $quaryAdvirtisment = $connect->prepare("SELECT job_title  
                                                                    FROM job_advertisment 
                                                                    WHERE advertisment_id = ? 
                                                                    LIMIT 1
                                                            ");
                                        $quaryAdvirtisment->execute( array($request['advertisment_id']) );
                                        $advirtisment = $quaryAdvirtisment->fetch();

                            ?>
                            <div class="col-sm-4 request-box">
                                <div class="section-photo">
                                    <div class="photo">
                                        <?php
                                            if( $jobSeeker['image'] == '' ){
                                
                                                $name =  explode(" " , $jobSeeker['full_name']);
                                    
                                                $lastName = array_pop($name);
                                    
                                                $firstName = substr($name['0'] , '0' , '1');
                                                $lastName  = substr($lastName , '0' , '1');
                                    
                                                echo '<span>'. $firstName . $lastName .'</span>';
                                        
                                            } else{
    
                                                echo '<img src="uploads/Job Seekers/' . $jobSeeker['image'] . '">';
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="section-name">
                                    <h5>
                                        <?php echo $jobSeeker['full_name'] ?>
                                    </h5>
                                </div>
                                <div class="section-information">
                                    <ul class="list-informations">
                                        <li> #<?php echo $request['request_id']?> </li>
                                        <li class="job-title">
                                            <?php echo $advirtisment['job_title']?>
                                        </li>
                                        <li> 
                                            <?php echo $request['date_of_applicant'] ?>
                                        </li>
                                    </ul>
                                    <a 
                                        href="manageApplicantRequest.php?do=view-more&requestID=<?php echo $request['request_id']?>"
                                    >
                                        <button> view more </button>
                                    </a>
                                </div>
                                <?php
                                    if($request['order_status'] == 0){

                                        echo '<div class="order-status rejected"> 
                                                    <h5> rejected </h5>       
                                              </div>
                                        ';
                                                
                                    } elseif($request['order_status'] == 1){

                                        echo '<div class="order-status pending"> 
                                                    <h5> pending </h5>       
                                              </div>
                                        ';

                                    } elseif($request['order_status'] == 2){

                                        echo '<div class="order-status accepted"> 
                                                    <h5> accepted </h5>       
                                              </div>
                                        ';
                                    }
                                ?>
                            </div>

                            <?php } 
                            }?>
                        </div>
                    
                    </div>
                </div>  
            </div>


<?php } elseif($do == 'advertising-requests'){

                $advirtismentID = $_POST['advirtismentID'];
                
                // Fetch All Applicant Requests Where advirtismentID = ?
                $quary = $connect->prepare("SELECT * 
                                            FROM applicant_request 
                                            WHERE 
                                                employer_id = ? 
                                                AND 
                                                advertisment_id = ?
                                            ORDER BY date_of_applicant DESC
                                        ");
                $quary->execute( array($_SESSION['companyID'] , $advirtismentID) );
                $applicantRequests = $quary->fetchAll();
                $rowsCount = $quary->rowCount();

                ?>

                <div class="applicant_request_page">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-3 filter-request">
                                <h3> filter requests </h3>
                                <form id="filter-requests" action="manageApplicantRequest.php?do=advertising-requests" method="POST">
                                    <select name="advirtismentID" oninput="check_select_ads_filter_requests()">
                                        <option value="All-Requests"> All Requests </option>
                                        <?php
                                            foreach($allAdvirtisments as $ad){ ?>

                                                <option 
                                                    value="<?php echo $ad['advertisment_id']?>"
                                                    <?php
                                                        if($ad['advertisment_id'] == $advirtismentID){

                                                                echo 'selected';
                                                        }
                                                    ?>
                                                >
                                                    <?php 
                                                        echo $ad['job_title'];
                                                    ?>
                                                </option>
                                            
                                        <?php } ?>
                                    </select>

                                    <ul>
                                        <a 
                                            href="manageApplicantRequest.php?do=filter-advertising-requests&advirtismentID=<?php echo $advirtismentID?>&status=allStatus"
                                            class="all allActive"
                                        >
                                            <li> 
                                                all 
                                                (
                                                    <?php echo getItemCount("request_id" , "applicant_request" , "WHERE advertisment_id = $advirtismentID")?>
                                                ) 
                                            </li>
                                        </a>
                                        <a 
                                            href="manageApplicantRequest.php?do=filter-advertising-requests&advirtismentID=<?php echo $advirtismentID?>&status=2"
                                            class="accepted"
                                        >
                                            <li> 
                                                accepted 
                                                (
                                                    <?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 2" , "AND advertisment_id = $advirtismentID") ?>
                                                ) 
                                            </li>
                                        </a>
                                        <a 
                                            href="manageApplicantRequest.php?do=filter-advertising-requests&advirtismentID=<?php echo $advirtismentID?>&status=1"
                                            class="pending"
                                        >
                                            <li> 
                                                pending 
                                                (
                                                    <?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 1" , "AND advertisment_id = $advirtismentID") ?>
                                                ) 
                                            </li>
                                        </a>
                                        <a 
                                        href="manageApplicantRequest.php?do=filter-advertising-requests&advirtismentID=<?php echo $advirtismentID?>&status=0"
                                            class="rejected"
                                        >
                                            <li> 
                                                rejected 
                                                (
                                                    <?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 0" , "AND advertisment_id = $advirtismentID") ?>
                                                ) 
                                            </li>
                                        </a>
                                    </ul>
                                </form>
                            </div>

                            <div class="col-sm-9 requests">
            
                                <?php
                                    if( $rowsCount == 0 ){
                                        
                                        echo '<div class="no-request">';
                                            echo '<h1> no requests </h1>';
                                        echo '</div>';

                                    } else{

                                        foreach($applicantRequests as $request){

                                            //Fetch Job Seeker
                                            $quaryJobSeeker = $connect->prepare("SELECT full_name , image  
                                                                        FROM job_seeker 
                                                                        WHERE job_seeker_id = ? 
                                                                        LIMIT 1
                                                                ");
                                            $quaryJobSeeker->execute( array($request['job_seeker_id']) );
                                            $jobSeeker = $quaryJobSeeker->fetch();

                                            //Fetch Advirtisment
                                            $quaryAdvirtisment = $connect->prepare("SELECT job_title  
                                                                        FROM job_advertisment 
                                                                        WHERE advertisment_id = ? 
                                                                        LIMIT 1
                                                                ");
                                            $quaryAdvirtisment->execute( array($request['advertisment_id']) );
                                            $advirtisment = $quaryAdvirtisment->fetch();

                                ?>
                                <div class="col-sm-4 request-box">
                                    <div class="section-photo">
                                        <div class="photo">
                                            <?php
                                                if( $jobSeeker['image'] == '' ){
                                    
                                                    $name =  explode(" " , $jobSeeker['full_name']);
                                        
                                                    $lastName = array_pop($name);
                                        
                                                    $firstName = substr($name['0'] , '0' , '1');
                                                    $lastName  = substr($lastName , '0' , '1');
                                        
                                                    echo '<span>'. $firstName . $lastName .'</span>';
                                            
                                                } else{
        
                                                    echo '<img src="uploads/Job Seekers/' . $jobSeeker['image'] . '">';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="section-name">
                                        <h5>
                                            <?php echo $jobSeeker['full_name'] ?>
                                        </h5>
                                    </div>
                                    <div class="section-information">
                                        <ul class="list-informations">
                                            <li> #<?php echo $request['request_id']?> </li>
                                            <li class="job-title">
                                                <?php echo $advirtisment['job_title']?>
                                            </li>
                                            <li> 
                                                <?php echo $request['date_of_applicant'] ?>
                                            </li>
                                        </ul>
                                        <a 
                                            href="manageApplicantRequest.php?do=view-more&requestID=<?php echo $request['request_id']?>"
                                        >
                                            <button> view more </button>
                                        </a>
                                    </div>
                                    <?php
                                        if($request['order_status'] == 0){

                                            echo '<div class="order-status rejected"> 
                                                        <h5> rejected </h5>       
                                                </div>
                                            ';
                                                    
                                        } elseif($request['order_status'] == 1){

                                            echo '<div class="order-status pending"> 
                                                        <h5> pending </h5>       
                                                </div>
                                            ';

                                        } elseif($request['order_status'] == 2){

                                            echo '<div class="order-status accepted"> 
                                                        <h5> accepted </h5>       
                                                </div>
                                            ';
                                        }
                                    ?>
                                </div>

                                <?php } 
                                
                                    }?>
                            </div>
                        </div>
                    </div>  
                </div>


<?php   } elseif($do == 'allRequests'){

                $status = isset($_GET['status']) && is_numeric($_GET['status']) ? $_GET['status'] : 0;

                // Fetch All Applicant Requests where ($status)
                $quary = $connect->prepare("SELECT * 
                                FROM applicant_request 
                                WHERE 
                                    employer_id = ?
                                AND
                                    order_status = ?
                                ORDER BY date_of_applicant DESC
                ");
                $quary->execute( array($_SESSION['companyID'] , $status) );
                $applicantRequests = $quary->fetchAll();
                $rowsCount = $quary->rowCount();
            ?>

            <div class="applicant_request_page">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-3 filter-request">
                            <h3> filter requests </h3>
                            <form id="filter-requests" action="manageApplicantRequest.php?do=advertising-requests" method="POST">
                                <select name="advirtismentID" oninput="check_select_ads_filter_requests()">
                                    <option value="All-Requests"> All Requests </option>
                                    <?php
                                        foreach($allAdvirtisments as $ad){

                                            echo '<option value="'.$ad['advertisment_id'].'">';

                                                echo $ad['job_title'];

                                            echo '</option>';
                                        }
                                    ?>
                                </select>

                                <ul>
                                    <a href="manageApplicantRequest.php" class="all">
                                        <li> 
                                            all 
                                            (
                                                <?php 
                                                    $quary = $connect->prepare("SELECT * 
                                                                                FROM applicant_request 
                                                                                WHERE employer_id = ? 
                                                                            ");
                                                    $quary->execute( array($_SESSION['companyID']) );
                                                    $rowsCount = $quary->rowCount(); 
                                                    
                                                    echo $rowsCount;
                                                ?>
                                            ) 
                                        </li>
                                    </a>
                                    <a href="manageApplicantRequest.php?do=allRequests&status=2" class="accepted 
                                                
                                            <?php 
                                                if( $status == 2 ){

                                                    echo 'allAccepted';
                                                }
                                            ?>
                                    ">
                                        <li> 
                                            accepted 
                                            (
                                                <?php 
                                                    $quary = $connect->prepare("SELECT * 
                                                                                FROM applicant_request 
                                                                                WHERE employer_id = ? 
                                                                                AND order_status = 2
                                                                            ");
                                                    $quary->execute( array($_SESSION['companyID']) );
                                                    $rowsCountAccepted = $quary->rowCount(); 
                                                    
                                                    echo $rowsCountAccepted;
                                                ?>
                                            ) 
                                        </li>
                                    </a>
                                    <a href="manageApplicantRequest.php?do=allRequests&status=1" class="pending
                                    
                                            <?php 
                                                if( $status == 1 ){

                                                    echo 'allPending';
                                                }
                                            ?>
                                    
                                    ">
                                        <li> 
                                            pending 
                                            (
                                                <?php 
                                                    $quary = $connect->prepare("SELECT * 
                                                                                FROM applicant_request 
                                                                                WHERE employer_id = ? 
                                                                                AND order_status = 1
                                                                            ");
                                                    $quary->execute( array($_SESSION['companyID']) );
                                                    $rowsCountPending = $quary->rowCount(); 
                                                    
                                                    echo $rowsCountPending;
                                                ?>
                                            ) 
                                        </li>
                                    </a>
                                    <a href="manageApplicantRequest.php?do=allRequests&status=0" class="rejected 
                                    
                                            <?php 
                                                if( $status == 0 ){

                                                    echo 'allRejected';
                                                }
                                            ?>
                                    ">
                                        <li> 
                                            rejected 
                                            (
                                                <?php 
                                                    $quary = $connect->prepare("SELECT * 
                                                                                FROM applicant_request 
                                                                                WHERE employer_id = ? 
                                                                                AND order_status = 0
                                                                            ");
                                                    $quary->execute( array($_SESSION['companyID']) );
                                                    $rowsCountRejected = $quary->rowCount(); 
                                                    
                                                    echo $rowsCountRejected;
                                                ?>
                                            ) 
                                        </li>
                                    </a>
                                </ul>
                            </form>
                        </div>

                        <div class="col-sm-9 requests">

                                <?php

                                     if( $rowsCount == 0 ){
                                        
                                        echo '<div class="no-request">';
                                            echo '<h1> no requests </h1>';
                                        echo '</div>';
    
                                    } else{
                                        foreach($applicantRequests as $request){

                                            //Fetch Job Seeker
                                            $quaryJobSeeker = $connect->prepare("SELECT full_name , image  
                                                                        FROM job_seeker 
                                                                        WHERE job_seeker_id = ? 
                                                                        LIMIT 1
                                                                ");
                                            $quaryJobSeeker->execute( array($request['job_seeker_id']) );
                                            $jobSeeker = $quaryJobSeeker->fetch();

                                            //Fetch Advirtisment
                                            $quaryAdvirtisment = $connect->prepare("SELECT job_title  
                                                                        FROM job_advertisment 
                                                                        WHERE advertisment_id = ? 
                                                                        LIMIT 1
                                                                ");
                                            $quaryAdvirtisment->execute( array($request['advertisment_id']) );
                                            $advirtisment = $quaryAdvirtisment->fetch();

                                ?>
                                    <div class="col-sm-4 request-box">
                                        <div class="section-photo">
                                            <div class="photo">
                                                <?php
                                                    if( $jobSeeker['image'] == '' ){
                                        
                                                        $name =  explode(" " , $jobSeeker['full_name']);
                                            
                                                        $lastName = array_pop($name);
                                            
                                                        $firstName = substr($name['0'] , '0' , '1');
                                                        $lastName  = substr($lastName , '0' , '1');
                                            
                                                        echo '<span>'. $firstName . $lastName .'</span>';
                                                
                                                    } else{

                                                        echo '<img src="uploads/Job Seekers/' . $jobSeeker['image'] . '">';
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="section-name">
                                            <h5>
                                                <?php echo $jobSeeker['full_name'] ?>
                                            </h5>
                                        </div>
                                        <div class="section-information">
                                            <ul class="list-informations">
                                                <li> #<?php echo $request['request_id']?> </li>
                                                <li class="job-title">
                                                    <?php echo $advirtisment['job_title']?>
                                                </li>
                                                <li> 
                                                    <?php echo $request['date_of_applicant'] ?>
                                                </li>
                                            </ul>
                                            <a 
                                                href="manageApplicantRequest.php?do=view-more&requestID=<?php echo $request['request_id']?>"
                                            >
                                                <button> view more </button>
                                            </a>
                                        </div>
                                        <?php
                                            if($request['order_status'] == 0){

                                                echo '<div class="order-status rejected"> 
                                                            <h5> rejected </h5>       
                                                    </div>
                                                ';
                                                        
                                            } elseif($request['order_status'] == 1){

                                                echo '<div class="order-status pending"> 
                                                            <h5> pending </h5>       
                                                    </div>
                                                ';

                                            } elseif($request['order_status'] == 2){

                                                echo '<div class="order-status accepted"> 
                                                            <h5> accepted </h5>       
                                                    </div>
                                                ';
                                            }
                                        ?>
                                    </div>

                            <?php } 
                            
                                }?>
                            </div>
                        </div>
                    </div>  
                </div>

<?php } elseif($do == 'filter-advertising-requests'){

            $advirtismentID = isset($_GET['advirtismentID']) && is_numeric($_GET['advirtismentID']) ? $_GET['advirtismentID'] : 0;

            $status = isset($_GET['status']) && is_numeric($_GET['status']) ? $_GET['status'] : 'allStatus';

            if( $status == 'allStatus' ){

                // Fetch All Applicant Requests where ($advirtismentID)
                $quary = $connect->prepare("SELECT * 
                                            FROM applicant_request 
                                            WHERE 
                                                employer_id = ?
                                            AND
                                                advertisment_id = ?
                                            ORDER BY date_of_applicant DESC
                                        ");
                $quary->execute( array($_SESSION['companyID'] , $advirtismentID) );
                $applicantRequests = $quary->fetchAll();
                $rowsCount = $quary->rowCount();

            } else{

                // Fetch All Applicant Requests where ($advirtismentID AND $status)
                $quary = $connect->prepare("SELECT * 
                                            FROM applicant_request 
                                            WHERE 
                                                employer_id = ?
                                            AND
                                                advertisment_id = ?
                                            AND
                                                order_status = ?
                                            ORDER BY date_of_applicant DESC
                                        ");
                $quary->execute( array($_SESSION['companyID'] , $advirtismentID , $status) );
                $applicantRequests = $quary->fetchAll();
                $rowsCount = $quary->rowCount();
            }

            ?>

            <div class="applicant_request_page">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-3 filter-request">
                            <h3> filter requests </h3>
                            <form id="filter-requests" action="manageApplicantRequest.php?do=advertising-requests" method="POST">
                                <select name="advirtismentID" oninput="check_select_ads_filter_requests()">
                                    <option value="All-Requests"> All Requests </option>
                                    <?php
                                        foreach($allAdvirtisments as $ad){ ?>

                                            <option 
                                                value="<?php echo $ad['advertisment_id']?>"
                                                <?php
                                                    if($ad['advertisment_id'] == $advirtismentID){

                                                            echo 'selected';
                                                    }
                                                ?>
                                            >
                                                <?php 
                                                    echo $ad['job_title'];
                                                ?>
                                            </option>
                                        
                                    <?php } ?>
                                </select>

                                <ul>
                                    <a 
                                        href="manageApplicantRequest.php?do=filter-advertising-requests&advirtismentID=<?php echo $advirtismentID?>&status=allStatus"
                                        class="all
                                                <?php
                                                    if($status == 'allStatus'){

                                                        echo 'allActive';
                                                    }
                                                ?>
                                        "
                                    >
                                        <li> 
                                            all 
                                            (
                                                <?php echo getItemCount("request_id" , "applicant_request" , "WHERE advertisment_id = $advirtismentID")?>
                                            ) 
                                        </li>
                                    </a>
                                    <a 
                                        href="manageApplicantRequest.php?do=filter-advertising-requests&advirtismentID=<?php echo $advirtismentID?>&status=2"
                                        class="accepted
                                                <?php
                                                    if($status == 2){

                                                        echo 'allAccepted';
                                                    }
                                                ?>
                                        "
                                    >
                                        <li> 
                                            accepted 
                                            (
                                                <?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 2" , "AND advertisment_id = $advirtismentID") ?>
                                            ) 
                                        </li>
                                    </a>
                                    <a 
                                        href="manageApplicantRequest.php?do=filter-advertising-requests&advirtismentID=<?php echo $advirtismentID?>&status=1"
                                        class="pending
                                                <?php
                                                    if($status == 1){

                                                        echo 'allPending';
                                                    }
                                                ?>
                                        "
                                    >
                                        <li> 
                                            pending 
                                            (
                                                <?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 1" , "AND advertisment_id = $advirtismentID") ?>
                                            ) 
                                        </li>
                                    </a>
                                    <a 
                                    href="manageApplicantRequest.php?do=filter-advertising-requests&advirtismentID=<?php echo $advirtismentID?>&status=0"
                                        class="rejected
                                                <?php
                                                    if($status == 0 && $status != 'allStatus'){

                                                        echo 'allRejected';
                                                    }
                                                ?>
                                        "
                                    >
                                        <li> 
                                            rejected 
                                            (
                                                <?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 0" , "AND advertisment_id = $advirtismentID") ?>
                                            ) 
                                        </li>
                                    </a>
                                </ul>
                            </form>
                        </div>

                        <div class="col-sm-9 requests">

                            <?php

                                if( $rowsCount == 0 ){
                                                                        
                                    echo '<div class="no-request">';
                                        echo '<h1> no requests </h1>';
                                    echo '</div>';

                                } else{

                                    foreach($applicantRequests as $request){

                                        //Fetch Job Seeker
                                        $quaryJobSeeker = $connect->prepare("SELECT full_name , image  
                                                                    FROM job_seeker 
                                                                    WHERE job_seeker_id = ? 
                                                                    LIMIT 1
                                                            ");
                                        $quaryJobSeeker->execute( array($request['job_seeker_id']) );
                                        $jobSeeker = $quaryJobSeeker->fetch();

                                        //Fetch Advirtisment
                                        $quaryAdvirtisment = $connect->prepare("SELECT job_title  
                                                                    FROM job_advertisment 
                                                                    WHERE advertisment_id = ? 
                                                                    LIMIT 1
                                                            ");
                                        $quaryAdvirtisment->execute( array($request['advertisment_id']) );
                                        $advirtisment = $quaryAdvirtisment->fetch();

                            ?>
                            <div class="col-sm-4 request-box">
                                <div class="section-photo">
                                    <div class="photo">
                                        <?php
                                            if( $jobSeeker['image'] == '' ){
                                
                                                $name =  explode(" " , $jobSeeker['full_name']);
                                    
                                                $lastName = array_pop($name);
                                    
                                                $firstName = substr($name['0'] , '0' , '1');
                                                $lastName  = substr($lastName , '0' , '1');
                                    
                                                echo '<span>'. $firstName . $lastName .'</span>';
                                        
                                            } else{
    
                                                echo '<img src="uploads/Job Seekers/' . $jobSeeker['image'] . '">';
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="section-name">
                                    <h5>
                                        <?php echo $jobSeeker['full_name'] ?>
                                    </h5>
                                </div>
                                <div class="section-information">
                                    <ul class="list-informations">
                                        <li> #<?php echo $request['request_id']?> </li>
                                        <li class="job-title">
                                            <?php echo $advirtisment['job_title']?>
                                        </li>
                                        <li> 
                                            <?php echo $request['date_of_applicant'] ?>
                                        </li>
                                    </ul>
                                    <a 
                                        href="manageApplicantRequest.php?do=view-more&requestID=<?php echo $request['request_id']?>"
                                    >
                                        <button> view more </button>
                                    </a>
                                </div>
                                <?php
                                    if($request['order_status'] == 0){

                                        echo '<div class="order-status rejected"> 
                                                    <h5> rejected </h5>       
                                            </div>
                                        ';
                                                
                                    } elseif($request['order_status'] == 1){

                                        echo '<div class="order-status pending"> 
                                                    <h5> pending </h5>       
                                            </div>
                                        ';

                                    } elseif($request['order_status'] == 2){

                                        echo '<div class="order-status accepted"> 
                                                    <h5> accepted </h5>       
                                            </div>
                                        ';
                                    }
                                ?>
                            </div>

                            <?php } 
                            
                                }?>
                        </div>
                    </div>
                </div>  
            </div>

    <?php } elseif( $do == 'view-more' ){

                    $requestID = isset($_GET['requestID']) && is_numeric($_GET['requestID']) ? $_GET['requestID'] : 0;

                    //Fetch Aplicant Request
                    $quaryAplicantRequest = $connect->prepare("SELECT * 
                                                               FROM applicant_request 
                                                               WHERE request_id = ? 
                                                               LIMIT 1
                                                            ");
                    $quaryAplicantRequest->execute( array($requestID) );
                    $request = $quaryAplicantRequest->fetch();

                    //Fetch Advirtisment
                    $quaryAdvirtisment = $connect->prepare("SELECT * 
                                                            FROM job_advertisment 
                                                            WHERE advertisment_id = ? 
                                                            LIMIT 1
                                                        ");
                    $quaryAdvirtisment->execute( array($request['advertisment_id']) );
                    $advertisment = $quaryAdvirtisment->fetch();

                    //Fetch Job Seeker
                    $quaryJobSeeker = $connect->prepare("SELECT * 
                                                         FROM job_seeker 
                                                         WHERE job_seeker_id = ? 
                                                         LIMIT 1
                                                    ");
                    $quaryJobSeeker->execute( array($request['job_seeker_id']) );
                    $jobSeeker = $quaryJobSeeker->fetch();

                    //Fetch Category Advirtisment
                    $quaryCategoryAdvirtisment = $connect->prepare("SELECT category_name 
                                                                    FROM category 
                                                                    WHERE category_id = ? 
                                                                    LIMIT 1
                                                                ");
                    $quaryCategoryAdvirtisment->execute( array($advertisment['category_id']) );
                    $categoryAdvirtisment = $quaryCategoryAdvirtisment->fetch();

                    //Fetch Category Job Seeker
                    $quaryCategoryJobSeeker = $connect->prepare("SELECT category_name 
                                                                 FROM category 
                                                                 WHERE category_id = ? 
                                                                 LIMIT 1
                                                            ");
                    $quaryCategoryJobSeeker->execute( array($jobSeeker['category_id']) );
                    $categoryJobSeeker = $quaryCategoryJobSeeker->fetch();

                    //Fetch Subcategory Advirtisment
                    $quarySubcategoryAdvirtisment = $connect->prepare("SELECT f_name 
                                                                       FROM focusing_on 
                                                                       WHERE f_id = ? 
                                                                       LIMIT 1
                                                                    ");
                    $quarySubcategoryAdvirtisment->execute( array($advertisment['f_id']) );
                    $subcategoryAdvirtisment = $quarySubcategoryAdvirtisment->fetch();

                    //Fetch Subategory Job Seeker
                    $quarySubcategoryJobSeeker = $connect->prepare("SELECT f_name 
                                                                    FROM focusing_on 
                                                                    WHERE f_id = ? 
                                                                    LIMIT 1
                                                                ");
                    $quarySubcategoryJobSeeker->execute( array($jobSeeker['f_id']) );
                    $subcategoryJobSeeker = $quarySubcategoryJobSeeker->fetch();
                ?>    

                <div class="view-more-page">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-6 advirtisment">
                                <h2 class="text-center"> advirtisment information </h2>
                                <p class="job-title">
                                    <?php echo $advertisment['job_title']; ?>
                                </p>
                                <ul>
                                    <li>
                                        <i class="fa fa-certificate"></i>
                                        <span>
                                            <?php
                                                echo $categoryAdvirtisment['category_name'] . 
                                                     ' / ' . 
                                                     $subcategoryAdvirtisment['f_name'];
                                            ?>
                                        </span>
                                    </li>
                                    <li>
                                        <i class="fa fa-credit-card"></i>
                                        <span>
                                            <?php
                                                if( $advertisment['salary_static'] == '' ){

                                                    echo '$'. $advertisment['salary_min']
                                                         . ' - ' .
                                                         '$'. $advertisment['salary_max'];

                                                } else{

                                                    echo '$'. $advertisment['salary_static'];
                                                }

                                                echo ' per '. $advertisment['salary_type'];
                                            ?>
                                        </span>
                                    </li>
                                    <li>
                                        <i class="fas fa-briefcase"></i>
                                        <span>
                                            <?php echo $advertisment['job_type']; ?>
                                        </span>
                                    </li>
                                    <li>
                                        <i class="fa fa-map-marker-alt"></i>
                                        <span>
                                            <?php echo $advertisment['work_location']; ?>
                                        </span>
                                    </li>
                                    <li>
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>
                                            <?php
                                                echo 'from ' . $advertisment['work_shedule_start'] . 
                                                     ' to ' . $advertisment['work_shedule_end'] . 
                                                     ' / ' . $advertisment['work_shedule_hours'] . 
                                                     ' hours work a ' . $advertisment['work_shedule_type'];
                                            ?>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-sm-6 applicant">
                                <h2 class="text-center"> applicant information </h2>
                                <div class="photo">
                                    <?php
                                        if( $jobSeeker['image'] == '' ){
                                
                                            $name =  explode(" " , $jobSeeker['full_name']);
                                
                                            $lastName = array_pop($name);
                                
                                            $firstName = substr($name['0'] , '0' , '1');
                                            $lastName  = substr($lastName , '0' , '1');
                                
                                            echo '<span>'. $firstName . $lastName .'</span>';
                                    
                                        } else{

                                            echo '<img src="uploads/Job Seekers/' . $jobSeeker['image'] . '">';
                                        }
                                    ?>
                                </div>
                                <p class="name">
                                    <?php echo $jobSeeker['full_name']; ?>
                                </p>
                                <ul>
                                    <li>
                                        <i class="fa fa-certificate"></i>
                                        <span>
                                            <?php
                                                echo $categoryJobSeeker['category_name'] . 
                                                     ' / ' . 
                                                     $subcategoryJobSeeker['f_name'];
                                            ?>
                                        </span>
                                    </li>
                                    <li>
                                        <i class="fa fa-envelope"></i>
                                        <span>
                                            <?php echo $jobSeeker['email']; ?>
                                        </span>
                                    </li>
                                    <li>
                                        <i class="fa fa-phone"></i>
                                        <span>
                                            <?php echo $jobSeeker['phone']; ?>
                                        </span>
                                    </li>
                                    <li>
                                        <i class="fa fa-map-marker-alt"></i>
                                        <span>
                                            <?php echo $jobSeeker['address']; ?>
                                        </span>
                                    </li>
                                    <li>
                                        <i class="fa fa-calendar"></i>
                                        <span>
                                            <?php 
                                                echo $jobSeeker['date_of_birth'] 
                                                     . ' / ' .
                                                     $jobSeeker['age'] . ' years'; 
                                            ?>
                                        </span>
                                    </li>
                                </ul>
                                <a 
                                    class="download-cv" 
                                    href="uploads/CV/<?php echo $request['CV']?>"
                                > 
                                    download cv 
                                </a>
                            </div>
                            <div class="col-sm-8 col-sm-offset-2 applicant-description">
                                <h2 class="text-center"> applicant description </h2>
                                <p>
                                    <?php
                                        echo $request['description'];
                                    ?>
                                </p>
                            </div>
                            <div class="col-sm-8 col-sm-offset-2 order-status">

                                <?php
                                    if( $request['order_status'] == 1 ){ ?>

                                            <h2 class="text-center"> 
                                                what is your response to the request ? 
                                            </h2>
                                            <div class="col-sm-6 col-sm-offset-3 accepted-rejected-button">
                                                <a 
                                                    href="manageApplicantRequest.php?do=update-status&requestID=<?php echo $requestID?>&newStatus=accepted" 
                                                    class="accepted"
                                                > 
                                                    accepted 
                                                </a>
                                                <a 
                                                    href="manageApplicantRequest.php?do=update-status&requestID=<?php echo$requestID?>&newStatus=rejected"  
                                                    class="rejected"
                                                > 
                                                    rejected 
                                                </a>
                                            </div>

                                <?php } elseif( $request['order_status'] == 2 ){ ?>

                                            <h2 class="text-center textAccepted"> 
                                                this request has been accepted
                                            </h2>

                              <?php  } elseif( $request['order_status'] == 0 ){ ?>

                                            <h2 class="text-center textRejected"> 
                                                this request has been rejected
                                            </h2>
                                <?php }

                                ?>

                            </div>
                        </div>
                    </div>
                </div>

    <?php } elseif( $do == 'update-status' ){

                $requestID = isset($_GET['requestID']) && is_numeric($_GET['requestID']) ? $_GET['requestID'] : 0;
                
                $newStatus = isset($_GET['newStatus']) ? $_GET['newStatus'] : 'none';

                if( $newStatus == 'accepted' ){

                        $quaryAcceptedRequest = $connect->prepare("UPDATE applicant_request
                                                                SET 
                                                                        order_status = 2
                                                                WHERE
                                                                        request_id = ? 
                                                                ");
                        $quaryAcceptedRequest->execute( array($requestID) );

                        $dateNow = date('Y-m-d');
                        $quaryAcceptedRequest = $connect->prepare("UPDATE applicant_request
                                                                SET 
                                                                        modified_date = ?
                                                                WHERE
                                                                        request_id = ? 
                                                                ");
                        $quaryAcceptedRequest->execute( array($dateNow , $requestID) );


                        redirectPage('success' , 'The application has been accepted successfully');
    
                        redirectPage('info' , 'redirect order page within 3 seconds' , 'manageApplicantRequest.php' , '3');

                } elseif( $newStatus == 'rejected' ){

                        $quaryRejectedRequest = $connect->prepare("UPDATE applicant_request
                                                                   SET 
                                                                        order_status = 0
                                                                   WHERE
                                                                        request_id = ? 
                                                                ");
                        $quaryRejectedRequest->execute( array($requestID) );

                        $dateNow = date('Y-m-d');
                        $quaryAcceptedRequest = $connect->prepare("UPDATE applicant_request
                                                                SET 
                                                                        modified_date = ?
                                                                WHERE
                                                                        request_id = ? 
                                                                ");
                        $quaryAcceptedRequest->execute( array($dateNow , $requestID) );

                        redirectPage('success' , 'The application has been rejected successfully');
    
                        redirectPage('info' , 'redirect order page within 3 seconds' , 'manageApplicantRequest.php' , '3');
                }
            } 
    
    ?>

<?php
    include $tmp . 'footer.php';
    ob_end_flush();
?>