<?php
    ob_start();
    $pageTitle = 'show advirtisment';
    $homePage = '';

    include 'initialize.php';

    $advirtismentID = isset($_GET['advirtismentID']) && is_numeric($_GET['advirtismentID']) ? $_GET['advirtismentID'] : 0;
    
    /* Get Advirtisment And Company Information */
    $quaryGetAdvirtisment = $connect->prepare("SELECT job_advertisment.* , employer.*
                                FROM job_advertisment
                                INNER JOIN employer
                                    ON employer.employer_id = job_advertisment.employer_id
                                WHERE job_advertisment.advertisment_id = ?
                                LIMIT 1
                        ");
    $quaryGetAdvirtisment->execute( array($advirtismentID) );
    $getAdvirtisment = $quaryGetAdvirtisment->fetchAll(); 

?>

    <div class="show-advirtisment">
        <div class="container">
            <div class="row">
                <section class="col-sm-9 left-section">
                       <div class="employer-logo">
                            <?php
                                foreach($getAdvirtisment as $row){
                                    if( $row['logo'] == '' ){
                            
                                        echo '<span class="photoText"> <i class="fas fa-building"></i> </span>';
                                
                                    } else{

                                        echo '<img src="uploads/Employers/' . $row['logo'] . '">';

                                    }
                                }
                            ?>
                       </div>
                        
                       <h4 class="job-title">
                            <?php echo $row['job_title']?>
                       </h4>

                       <h3 class="employer-name">
                            <?php echo $row['employer_name']; ?>
                       </h3>

                       <?php
                           $quaryCategory = $connect->prepare("SELECT category_name 
                                                               FROM category 
                                                               WHERE category_id = ?
                                                            ");
                            $quaryCategory->execute( array($row['category_id']) );
                            $category= $quaryCategory->fetch(); 

                            $quarySubcategory = $connect->prepare("SELECT f_name 
                                                                   FROM focusing_on 
                                                                    WHERE f_id = ?
                                                                ");
                            $quarySubcategory->execute( array($row['f_id']) );
                            $subcategory= $quarySubcategory->fetch(); 
                       ?>

                       <div class="category-subcategory">
                            <div class="category">
                                <?php echo $category['category_name'] ?>
                            </div>
                            <div class="subcategory">
                                <?php echo $subcategory['f_name'] ?>
                            </div>
                       </div>

                       <div class="jobType-and-location-and-datePublication">
                            <div class="job-type">
                                <?php echo $row['job_type']?>
                            </div>
                            <div class="location">
                                <?php echo $row['work_location']?>
                            </div>
                            <div class="date-publication">
                                <?php echo getDatePosted($row['date_of_publication'])?>
                            </div>
                       </div>

                       <div class="salary">
                           <?php
                                if($row['salary_static'] == ''){

                                    echo '<div class="salary-min-max">';
                                        echo '$' . $row['salary_min'] 
                                             . ' - ' . 
                                             ' $' . $row['salary_max'];
                                    echo '</div>';

                                } else{

                                    echo '<div class="salary-static">';
                                        echo '$' . $row['salary_static'];
                                    echo '</div>';
                                }
                           ?>
                           <div class="salary-type">
                                per <?php echo $row['salary_type']; ?>
                           </div>   
                       </div>

                       <div class="work-shedule">
                            <div class="work-shedule-start-end">
                                <?php
                                    echo 'from ' .  $row['work_shedule_start'] .
                                         ' to ' . $row['work_shedule_end'];
                                ?>
                            </div>
                            <div class="work-shedule-hours">
                                <?php
                                    echo $row['work_shedule_hours'] 
                                         . ' hours work a ' .
                                         $row['work_shedule_type'];
                                ?>
                            </div>
                       </div>

                       <div class="description">
                            <p>
                                <?php echo $row['job_description']?>
                            </p>
                       </div>

                </section>

                <section class="col-sm-3 right-section">
                    <div class="applayButton-and-gender">
                        <form  action="aplicantRequest.php" method="post">
                            <input  type="hidden" name="employerID" value="<?php echo $row['employer_id']; ?>">
                            <input  type="hidden" name="advirtismentID" value="<?php echo $row['advertisment_id']; ?>">
                            <input  type="hidden" name="jobSeekerID" value="<?php echo $_SESSION['jobSeekerID']; ?>">
                            <button 
                                type="submit" 
                                name="btn-apply"
                                <?php 
                                    if( !isset( $_SESSION['jobSeekerID'] ) ){

                                            echo 'class="btn-close" ';
                                    }
                                ?>
                            > 
                                <i class="fa fa-rocket"></i>
                                apply now
                            </button>
                        </form>
                        <div class="gender">
                            <p>
                                <?php
                                    if($row['gender'] == 'male'){

                                        echo 'Only males can applay for the job';

                                    } elseif($row['gender'] == 'female'){

                                        echo 'Only females can applay for the job';

                                    } elseif($row['gender'] == 'anyone'){

                                        echo 'Anyone can applay for the job';
                                    }
                                ?>
                            </p>
                        </div>
                    </div>

                    <div class="employer-information">
                        <span>company</span>
                        <ul>
                            <li>
                                <i class="fas fa-building"></i> 
                                <p> <?php echo $row['employer_name']?> </p>
                            </li>
                            <li>
                                <i class="fa fa-map-marker-alt"></i> 
                                <p> <?php echo $row['employer_address']?> </p>
                            </li>
                            <li>
                                <i class="fa fa-briefcase"></i> 
                                <p class="posted-number-jobs">
                                    <?php
                                        $quaryAdvirtismentCount = $connect->prepare("SELECT COUNT(advertisment_id) as advirtismentCount
                                                                                     FROM job_advertisment 
                                                                                     WHERE employer_id = ?; ");
                                        $quaryAdvirtismentCount->execute( array($row['employer_id']) );
                                        $GetAdvirtismentCount = $quaryAdvirtismentCount->fetch();

                                        echo 'Posted ' . $GetAdvirtismentCount['advirtismentCount'] . ' jobs';
                                    ?>
                                </p>
                            </li>
                        </ul>
                    </div>
                    
                </section>
            </div>
        </div>
    </div>
<?php

    include $tmp . 'Footer.php';
    ob_end_flush();
?>