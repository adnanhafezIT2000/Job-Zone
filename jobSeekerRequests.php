<?php
    ob_start();
    $pageTitle = 'Requests';
    $homePage = '';
    include 'initialize.php';

    $status = isset($_GET['status']) ? $_GET['status'] : 'all';

    $jobSeekerID = $_SESSION['jobSeekerID'];

    if( $status == 'all' ){

            // Fetch All Requests For Job Seeker
            $quary = $connect->prepare("SELECT * 
                                        FROM applicant_request 
                                        WHERE job_seeker_id = ? 
                                        ORDER BY date_of_applicant DESC
                                    ");
            $quary->execute( array($jobSeekerID) );
            $Requests = $quary->fetchAll();
            $rowsCount = $quary->rowCount();
        ?>

        <div class="applicant_request_page">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3 filter-request">
                        <h3> filter requests </h3>
                        <form action="">
                            <ul>
                                <a 
                                    href="jobSeekerRequests.php?status=all"
                                    class="all allActive">
                                    <li> 
                                        all 
                                        (
                                            <?php echo getItemCount("request_id" , "applicant_request" , "WHERE job_seeker_id = $jobSeekerID"); ?>
                                        ) 
                                    </li>
                                </a>
                                <a href="jobSeekerRequests.php?status=accepted" class="accepted">
                                    <li> 
                                        accepted 
                                        (
                                            <?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 2" , "AND job_seeker_id = $jobSeekerID"); ?>
                                        ) 
                                    </li>
                                </a>
                                <a href="jobSeekerRequests.php?status=pending" class="pending">
                                    <li> 
                                        pending 
                                        (
                                            <?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 1" , "AND job_seeker_id = $jobSeekerID") ?>
                                        ) 
                                    </li>
                                </a>
                                <a href="jobSeekerRequests.php?status=rejected" class="rejected">
                                    <li> 
                                        rejected 
                                        (
                                            <?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 0" , "AND job_seeker_id = $jobSeekerID") ?>
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
                                
                                echo '
                                    <table class="table align-middle mb-0 bg-white">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Employer</th>
                                                <th>Job Title</th>
                                                <th>Status</th>
                                                <th>date of applicant</th>
                                                <th>modified date</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                ';
                                foreach($Requests as $request){

                                    //Fetch Advirtisment
                                    $quaryAdvirtisment = $connect->prepare("SELECT *  
                                                                            FROM job_advertisment 
                                                                            WHERE advertisment_id = ? 
                                                                            LIMIT 1
                                                                        ");
                                    $quaryAdvirtisment->execute( array($request['advertisment_id']) );
                                    $advirtisment = $quaryAdvirtisment->fetch();

                                    //Fetch Employer
                                    $quaryEmployer = $connect->prepare("SELECT *  
                                                                        FROM employer 
                                                                        WHERE employer_id = ? 
                                                                        LIMIT 1
                                                                    ");
                                    $quaryEmployer->execute( array($request['employer_id']) );
                                    $employer = $quaryEmployer->fetch();

                                    //Fetch Category
                                    $quaryCategory = $connect->prepare("SELECT category_name  
                                                                        FROM category 
                                                                        WHERE category_id = ? 
                                                                        LIMIT 1
                                                                    ");
                                    $quaryCategory->execute( array($advirtisment['category_id']) );
                                    $category = $quaryCategory->fetch();
                            ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php
                                                    if( $employer['logo'] == '' ){
                                            
                                                        $name =  explode(" " , $employer['employer_name']);
                                            
                                                        $lastName = array_pop($name);
                                            
                                                        $firstName = substr($name['0'] , '0' , '1');
                                                        $lastName  = substr($lastName , '0' , '1');
                                            
                                                        echo '<span class="photoText">'. $firstName . $lastName .'</span>';
                                                
                                                    } else{
    
                                                        echo '<img src="uploads/Employers/' . $employer['logo'] . '">';
    
                                                    }
                                                ?>
                                                <div class="ms-3 employer-name-email">
                                                    <p class="fw-bold mb-1">
                                                        <?php echo $employer['employer_name'];?>
                                                    </p>
                                                    <p class="text-muted mb-0">
                                                        <?php echo $employer['employer_email'];?>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <p class="fw-normal mb-1">
                                                <?php
                                                    echo $advirtisment['job_title'];
                                                ?>
                                            </p>
                                            <p class="text-muted mb-0">
                                                <?php echo $category['category_name']?>
                                            </p>
                                        </td>

                                        <td>
                                            <?php
                                                if( $request['order_status'] == 2 ){

                                                    echo '<span 
                                                            class="accepted badge badge-warning rounded-pill d-inline"
                                                          >
                                                            accepted
                                                          </span>';

                                                } elseif( $request['order_status'] == 1 ){

                                                    echo '<span 
                                                            class="pending badge badge-warning rounded-pill d-inline"
                                                          >
                                                            pending
                                                          </span>';

                                                } elseif( $request['order_status'] == 0 ){

                                                    echo '<span 
                                                            class="rejected badge badge-warning rounded-pill d-inline"
                                                          >
                                                            rejected
                                                          </span>';
                                                }

                                            ?>
                                        </td>

                                        <td>
                                            <?php echo $request['date_of_applicant']; ?>
                                        </td>

                                        <td>
                                            <?php 
                                                if($request['order_status'] == 1){

                                                    echo "None";

                                                } else{

                                                    echo $request['modified_date']; 
                                                }
                                            ?>
                                        </td>
                                    
                                    </tr>
                                        
                         <?php 
                                } 

                                echo '      </tbody>
                                        </table>
                                ';
                            } 
                        ?>
                    </div>
                </div>
            </div>  
        </div>


<?php } elseif( $status == 'accepted' ){

                // Fetch All Accepted Requests For Job Seeker
                $quary = $connect->prepare("SELECT * 
                                            FROM applicant_request 
                                            WHERE job_seeker_id = ? 
                                            AND
                                                order_status = 2
                                            ORDER BY date_of_applicant DESC
                                        ");
                $quary->execute( array($jobSeekerID) );
                $Requests = $quary->fetchAll();
                $rowsCount = $quary->rowCount();
            ?>

<div class="applicant_request_page">
<div class="container">
    <div class="row">
        <div class="col-sm-3 filter-request">
            <h3> filter requests </h3>
            <form action="">
                <ul>
                    <a 
                        href="jobSeekerRequests.php?status=all"
                        class="all">
                        <li> 
                            all 
                            (
                                <?php  echo getItemCount("request_id" , "applicant_request" , "WHERE job_seeker_id = $jobSeekerID");?>
                            ) 
                        </li>
                    </a>
                    <a href="jobSeekerRequests.php?status=accepted" class="accepted allAccepted">
                        <li> 
                            accepted 
                            (
                                <?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 2" , "AND job_seeker_id = $jobSeekerID"); ?>
                            ) 
                        </li>
                    </a>
                    <a href="jobSeekerRequests.php?status=pending" class="pending">
                        <li> 
                            pending 
                            (
                                <?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 1" , "AND job_seeker_id = $jobSeekerID") ?>
                            ) 
                        </li>
                    </a>
                    <a href="jobSeekerRequests.php?status=rejected" class="rejected">
                        <li> 
                            rejected 
                            (
                                <?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 0" , "AND job_seeker_id = $jobSeekerID") ?>
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
                    
                    echo '
                        <table class="table align-middle mb-0 bg-white">
                            <thead class="bg-light">
                                <tr>
                                    <th>Employer</th>
                                    <th>Job Title</th>
                                    <th>Status</th>
                                    <th>date of applicant</th>
                                    <th>modified date</th>
                                </tr>
                            </thead>

                            <tbody>

                    ';
                    foreach($Requests as $request){

                        //Fetch Advirtisment
                        $quaryAdvirtisment = $connect->prepare("SELECT *  
                                                                FROM job_advertisment 
                                                                WHERE advertisment_id = ? 
                                                                LIMIT 1
                                                            ");
                        $quaryAdvirtisment->execute( array($request['advertisment_id']) );
                        $advirtisment = $quaryAdvirtisment->fetch();

                        //Fetch Employer
                        $quaryEmployer = $connect->prepare("SELECT *  
                                                            FROM employer 
                                                            WHERE employer_id = ? 
                                                            LIMIT 1
                                                        ");
                        $quaryEmployer->execute( array($request['employer_id']) );
                        $employer = $quaryEmployer->fetch();

                        //Fetch Category
                        $quaryCategory = $connect->prepare("SELECT category_name  
                                                            FROM category 
                                                            WHERE category_id = ? 
                                                            LIMIT 1
                                                        ");
                        $quaryCategory->execute( array($advirtisment['category_id']) );
                        $category = $quaryCategory->fetch();
                ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php
                                            if( $employer['logo'] == '' ){
                                    
                                                $name =  explode(" " , $employer['employer_name']);
                                    
                                                $lastName = array_pop($name);
                                    
                                                $firstName = substr($name['0'] , '0' , '1');
                                                $lastName  = substr($lastName , '0' , '1');
                                    
                                                echo '<span class="photoText">'. $firstName . $lastName .'</span>';
                                        
                                            } else{

                                                echo '<img src="uploads/Employers/' . $employer['logo'] . '">';

                                            }
                                    ?>
                                    <div class="ms-3">
                                        <p class="fw-bold mb-1">
                                            <?php echo $employer['employer_name'];?>
                                        </p>
                                        <p class="text-muted mb-0">
                                            <?php echo $employer['employer_email'];?>
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <p class="fw-normal mb-1">
                                    <?php
                                        echo $advirtisment['job_title'];
                                    ?>
                                </p>
                                <p class="text-muted mb-0">
                                    <?php echo $category['category_name']?>
                                </p>
                            </td>

                            <td>
                                <?php
                                    if( $request['order_status'] == 2 ){

                                        echo '<span 
                                                class="accepted badge badge-warning rounded-pill d-inline"
                                              >
                                                accepted
                                              </span>';

                                    } elseif( $request['order_status'] == 1 ){

                                        echo '<span 
                                                class="pending badge badge-warning rounded-pill d-inline"
                                              >
                                                pending
                                              </span>';

                                    } elseif( $request['order_status'] == 0 ){

                                        echo '<span 
                                                class="rejected badge badge-warning rounded-pill d-inline"
                                              >
                                                rejected
                                              </span>';
                                    }

                                ?>
                            </td>

                            <td>
                                <?php echo $request['date_of_applicant']; ?>
                            </td>

                            <td>
                                <?php 
                                    if($request['order_status'] == 1){

                                        echo "None";

                                    } else{

                                        echo $request['modified_date']; 
                                    }
                                ?>
                            </td>
                        
                        </tr>
                            
             <?php 
                    } 

                    echo '      </tbody>
                            </table>
                    ';
                } 
            ?>
        </div>
    </div>
</div>  
</div>


<?php } elseif( $status == 'pending' ){

                // Fetch All Accepted Requests For Job Seeker
                $quary = $connect->prepare("SELECT * 
                                            FROM applicant_request 
                                            WHERE job_seeker_id = ? 
                                            AND
                                                order_status = 1
                                            ORDER BY date_of_applicant DESC
                                        ");
                $quary->execute( array($jobSeekerID) );
                $Requests = $quary->fetchAll();
                $rowsCount = $quary->rowCount();
            ?>

<div class="applicant_request_page">
<div class="container">
<div class="row">
<div class="col-sm-3 filter-request">
<h3> filter requests </h3>
<form action="">
<ul>
    <a 
        href="jobSeekerRequests.php?status=all"
        class="all">
        <li> 
            all 
            (
                <?php echo getItemCount("request_id" , "applicant_request" , "WHERE job_seeker_id = $jobSeekerID");;?>
            ) 
        </li>
    </a>
    <a href="jobSeekerRequests.php?status=accepted" class="accepted">
        <li> 
            accepted 
            (
                <?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 2" , "AND job_seeker_id = $jobSeekerID"); ?>
            ) 
        </li>
    </a>
    <a href="jobSeekerRequests.php?status=pending" class="pending allPending">
        <li> 
            pending 
            (
                <?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 1" , "AND job_seeker_id = $jobSeekerID") ?>
            ) 
        </li>
    </a>
    <a href="jobSeekerRequests.php?status=rejected" class="rejected">
        <li> 
            rejected 
            (
                <?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 0" , "AND job_seeker_id = $jobSeekerID") ?>
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
    
    echo '
        <table class="table align-middle mb-0 bg-white">
            <thead class="bg-light">
                <tr>
                    <th>Employer</th>
                    <th>Job Title</th>
                    <th>Status</th>
                    <th>date of applicant</th>
                    <th>modified date</th>
                </tr>
            </thead>

            <tbody>

    ';
    foreach($Requests as $request){

        //Fetch Advirtisment
        $quaryAdvirtisment = $connect->prepare("SELECT *  
                                                FROM job_advertisment 
                                                WHERE advertisment_id = ? 
                                                LIMIT 1
                                            ");
        $quaryAdvirtisment->execute( array($request['advertisment_id']) );
        $advirtisment = $quaryAdvirtisment->fetch();

        //Fetch Employer
        $quaryEmployer = $connect->prepare("SELECT *  
                                            FROM employer 
                                            WHERE employer_id = ? 
                                            LIMIT 1
                                        ");
        $quaryEmployer->execute( array($request['employer_id']) );
        $employer = $quaryEmployer->fetch();

        //Fetch Category
        $quaryCategory = $connect->prepare("SELECT category_name  
                                            FROM category 
                                            WHERE category_id = ? 
                                            LIMIT 1
                                        ");
        $quaryCategory->execute( array($advirtisment['category_id']) );
        $category = $quaryCategory->fetch();
?>
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <?php
                        if( $employer['logo'] == '' ){
                
                            $name =  explode(" " , $employer['employer_name']);
                
                            $lastName = array_pop($name);
                
                            $firstName = substr($name['0'] , '0' , '1');
                            $lastName  = substr($lastName , '0' , '1');
                
                            echo '<span class="photoText">'. $firstName . $lastName .'</span>';
                    
                        } else{

                            echo '<img src="uploads/Employers/' . $employer['logo'] . '">';

                        }
                    ?>
                    <div class="ms-3">
                        <p class="fw-bold mb-1">
                            <?php echo $employer['employer_name'];?>
                        </p>
                        <p class="text-muted mb-0">
                            <?php echo $employer['employer_email'];?>
                        </p>
                    </div>
                </div>
            </td>

            <td>
                <p class="fw-normal mb-1">
                    <?php
                        echo $advirtisment['job_title'];
                    ?>
                </p>
                <p class="text-muted mb-0">
                    <?php echo $category['category_name']?>
                </p>
            </td>

            <td>
                <?php
                    if( $request['order_status'] == 2 ){

                        echo '<span 
                                class="accepted badge badge-warning rounded-pill d-inline"
                              >
                                accepted
                              </span>';

                    } elseif( $request['order_status'] == 1 ){

                        echo '<span 
                                class="pending badge badge-warning rounded-pill d-inline"
                              >
                                pending
                              </span>';

                    } elseif( $request['order_status'] == 0 ){

                        echo '<span 
                                class="rejected badge badge-warning rounded-pill d-inline"
                              >
                                rejected
                              </span>';
                    }

                ?>
            </td>

            <td>
                <?php echo $request['date_of_applicant']; ?>
            </td>

            <td>
                <?php 
                    if($request['order_status'] == 1){

                        echo "None";

                    } else{

                        echo $request['modified_date']; 
                    }
                ?>
            </td>
        
        </tr>
            
<?php 
    } 

    echo '      </tbody>
            </table>
    ';
} 
?>
</div>
</div>
</div>  
</div>


<?php } elseif( $status == 'rejected' ){

// Fetch All Accepted Requests For Job Seeker
$quary = $connect->prepare("SELECT * 
                            FROM applicant_request 
                            WHERE job_seeker_id = ? 
                            AND
                                order_status = 0
                            ORDER BY date_of_applicant DESC
                        ");
$quary->execute( array($jobSeekerID) );
$Requests = $quary->fetchAll();
$rowsCount = $quary->rowCount();
?>

<div class="applicant_request_page">
<div class="container">
<div class="row">
<div class="col-sm-3 filter-request">
<h3> filter requests </h3>
<form action="">
<ul>
<a 
href="jobSeekerRequests.php?status=all"
class="all">
<li> 
all 
(
<?php echo getItemCount("request_id" , "applicant_request" , "WHERE job_seeker_id = $jobSeekerID");;?>
) 
</li>
</a>
<a href="jobSeekerRequests.php?status=accepted" class="accepted">
<li> 
accepted 
(
<?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 2" , "AND job_seeker_id = $jobSeekerID"); ?>
) 
</li>
</a>
<a href="jobSeekerRequests.php?status=pending" class="pending">
<li> 
pending 
(
<?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 1" , "AND job_seeker_id = $jobSeekerID") ?>
) 
</li>
</a>
<a href="jobSeekerRequests.php?status=rejected" class="rejected allRejected">
<li> 
rejected 
(
<?php echo getItemCount("request_id" , "applicant_request" , "WHERE order_status = 0" , "AND job_seeker_id = $jobSeekerID") ?>
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

echo '
<table class="table align-middle mb-0 bg-white">
<thead class="bg-light">
<tr>
    <th>Employer</th>
    <th>Job Title</th>
    <th>Status</th>
    <th>date of applicant</th>
    <th>modified date</th>
</tr>
</thead>

<tbody>

';
foreach($Requests as $request){

//Fetch Advirtisment
$quaryAdvirtisment = $connect->prepare("SELECT *  
                                FROM job_advertisment 
                                WHERE advertisment_id = ? 
                                LIMIT 1
                            ");
$quaryAdvirtisment->execute( array($request['advertisment_id']) );
$advirtisment = $quaryAdvirtisment->fetch();

//Fetch Employer
$quaryEmployer = $connect->prepare("SELECT *  
                            FROM employer 
                            WHERE employer_id = ? 
                            LIMIT 1
                        ");
$quaryEmployer->execute( array($request['employer_id']) );
$employer = $quaryEmployer->fetch();

//Fetch Category
$quaryCategory = $connect->prepare("SELECT category_name  
                            FROM category 
                            WHERE category_id = ? 
                            LIMIT 1
                        ");
$quaryCategory->execute( array($advirtisment['category_id']) );
$category = $quaryCategory->fetch();
?>
<tr>
<td>
<div class="d-flex align-items-center">
    <?php
        if( $employer['logo'] == '' ){

            $name =  explode(" " , $employer['employer_name']);

            $lastName = array_pop($name);

            $firstName = substr($name['0'] , '0' , '1');
            $lastName  = substr($lastName , '0' , '1');

            echo '<span class="photoText">'. $firstName . $lastName .'</span>';
    
        } else{

            echo '<img src="uploads/Employers/' . $employer['logo'] . '">';

        }
    ?>
    <div class="ms-3">
        <p class="fw-bold mb-1">
            <?php echo $employer['employer_name'];?>
        </p>
        <p class="text-muted mb-0">
            <?php echo $employer['employer_email'];?>
        </p>
    </div>
</div>
</td>

<td>
<p class="fw-normal mb-1">
    <?php
        echo $advirtisment['job_title'];
    ?>
</p>
<p class="text-muted mb-0">
    <?php echo $category['category_name']?>
</p>
</td>

<td>
<?php
    if( $request['order_status'] == 2 ){

        echo '<span 
                class="accepted badge badge-warning rounded-pill d-inline"
              >
                accepted
              </span>';

    } elseif( $request['order_status'] == 1 ){

        echo '<span 
                class="pending badge badge-warning rounded-pill d-inline"
              >
                pending
              </span>';

    } elseif( $request['order_status'] == 0 ){

        echo '<span 
                class="rejected badge badge-warning rounded-pill d-inline"
              >
                rejected
              </span>';
    }

?>
</td>

<td>
<?php echo $request['date_of_applicant']; ?>
</td>

<td>
<?php 
    if($request['order_status'] == 1){

        echo "None";

    } else{

        echo $request['modified_date']; 
    }
?>
</td>

</tr>

<?php 
} 

echo '      </tbody>
</table>
';
} 
?>
</div>
</div>
</div>  
</div>
<?php } ?>

<?php
    include $tmp . 'footer.php';
    ob_end_flush();
?>