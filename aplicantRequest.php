<?php
    ob_start();
    $pageTitle = 'applicant request';
    $homePage = '';
    include 'initialize.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'aplicantRequest';

    if( $do == 'aplicantRequest'){

        if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

            if( isset( $_POST['btn-apply'] ) ){
    
                $employerID     = $_POST['employerID'];
                $advertismentID = $_POST['advirtismentID'];
                $jobSeekerID    = $_POST['jobSeekerID'];

                $_SESSION['apply-employerID'] = $employerID;
                $_SESSION['apply-advirtismentID'] = $advertismentID;
                $_SESSION['apply-jobSeekerID'] = $jobSeekerID;
    
                /* Get Job Advertisment gender  */
                $quaryGetAdvertisment = $connect->prepare("SELECT gender 
                                                           FROM job_advertisment 
                                                           WHERE advertisment_id = ?
                                                           LIMIT 1
                                                        ");
                $quaryGetAdvertisment->execute( array($advertismentID) );
                $getGenderAdvertisment = $quaryGetAdvertisment->fetch();
    
                /* Get job Seeker gender */
                $quaryGetUser = $connect->prepare("SELECT gender
                                                   FROM job_seeker 
                                                   WHERE job_seeker_id = ?
                                                   LIMIT 1
                                                ");
                $quaryGetUser->execute( array($jobSeekerID) );
                $getGenderJobSeeker = $quaryGetUser->fetch();
    
                /* if the job seeker submitted the request in advance */
                $quaryCheck = $connect->prepare("SELECT *
                                                 FROM applicant_request 
                                                 WHERE 
                                                    employer_id = ? 
                                                    AND 
                                                    advertisment_id = ?
                                                    AND
                                                    job_seeker_id = ?    
                                                 LIMIT 1
                                            ");
    
                $quaryCheck->execute( array($employerID , $advertismentID , $jobSeekerID) );
                $rowCount = $quaryCheck->rowCount();
    
                $formErrors = array();
    
                if( $getGenderAdvertisment['gender'] != $getGenderJobSeeker['gender'] 
                    && 
                    $getGenderAdvertisment['gender'] != 'anyone'
                ){
    
                        $formErrors[] = '
                                        You cannot submit an applicantion because the gender
                                        requested in the advertisment does not match your gender
                                    '; 
                }
    
                if( $rowCount > 0 ){
    
                        $formErrors[] = '
                                The applicantion was rejected because you previously
                                submitted this advertisment
                        '; 
                }
    
                if( !empty( $formErrors ) ){
    
                    foreach($formErrors as $error){
    
                        redirectPage('danger' , $error);
    
                        redirectPage('info' , 'You will be taken to the find work page. Please apply for a matching jobs' , 'findWork.php' , '4');
                    }
    
                } elseif( empty( $formErrors ) ){
    
                    header('location:aplicantRequest.php?do=formApplicant');
                }
    
            }
        }


    } elseif($do == 'formApplicant'){ 
        
        ?>

                <form action="aplicantRequest.php?do=sendApplication" enctype="multipart/form-data" id="form-upload-file" method="POST">
                    <div class="applicant-request-page">
                        <div class="container">
                            <div class="description">
                                <h1> apply for job </h1>
                                <p> Improve your chances of getting hired: </p>
                                <ul>
                                    <li>
                                        Make sure that your profile is complete: with your profile photo, bio, your skills, languages, experience, and education.
                                    </li>
                                    <li>
                                        Add attachments with your resume or work samples to support your application.
                                    </li>
                                </ul>
                                <div class="description-box">
                                    <textarea 
                                        name="description"  
                                        placeholder="Describe to the employer why you're a good match for this job. Only the employer will see your applicant"
                                        oninput="checkDescriptionTextareaLive()"
                                        ></textarea>
                                    <span></span>
                                </div>
                            </div>
                            <div class="cv">
                                <h1> add attachments </h1>
                                <div class="upload-area">
                                    <input type="file" name="CV" required>
                                    <i class="fa fa-upload"></i>
                                    <p> Documents or images that might be helpful to support your application </p>
                                    <section class="contructions">
                                        <h5>
                                            Maximum 10MB
                                        </h5>
                                        <h5>
                                            Allowed extension (jpg , jpeg , png , pdf , pptx , docx)
                                        </h5>
                                    </section>
                                </div>

                                <div class="upload-file-name"> 
                                    <i class="fa fa-file"></i>
                                    <p> </p>
                                </div>

                                <button name="send-application" type="submit"> send application </button>
                            </div>
                        </div>
                    </div>
                </form>
                        
   <?php } elseif( $do == 'sendApplication' ){

            
                if( isset($_POST['send-application']) ){

                        //Fetch Job Seeker Full Name 
                        $quaryJobSeekerFullName = $connect->prepare("SELECT full_name 
                                                             FROM job_seeker 
                                                             WHERE job_seeker_id = ? 
                                                             LIMIT 1
                                                        ");
                        $quaryJobSeekerFullName->execute( array($_SESSION['apply-jobSeekerID']) );
                        $jobSeekerFullName = $quaryJobSeekerFullName->fetch();

                        $description = filter_var($_POST['description'] , FILTER_SANITIZE_STRING);
                        
                        $CV_Name     = $_FILES['CV']['name'];
                        $CV_Position = $_FILES['CV']['tmp_name'];
                        $CV_Type     = $_FILES['CV']['type'];
                        $CV_Size     = $_FILES['CV']['size'];

                        // Set Allowed Files Extensions
                        $allowedExtensions = array('jpg' , 'jpeg' , 'png' , 'pdf' , 'pptx' , 'docx');

                        // Get File Extension
                        $fileExtension = strtolower(end(explode('.' , $CV_Name))); // $fileExtension (string)

                        // Get File Name
                        $fileName = explode('.' , $CV_Name);
                        $fileName = $jobSeekerFullName['full_name'] . ' ' . $fileName[0];

                        // Set CV New Name
                        $CV_New_Name = $fileName . $_SESSION['apply-jobSeekerID'] . $_SESSION['apply-advirtismentID'] . '.' . $fileExtension;

                        // Validation To File
                        $formErrors = array();

                        if( $CV_Size > '10000000'){ // 10MB

                                $formErrors[] = 'Maximum size (10MB)';
                        }   

                        if( ! in_array($fileExtension , $allowedExtensions) ){

                            $formErrors[] = 'Allowed extension (jpg , jpeg , png , pdf , pptx , docx)';
                        }

                        if( !empty($formErrors) ){

                            foreach( $formErrors as $error ){

                                redirectPage('danger' , $error);
                            }

                            redirectPage('info' , 'redirect previous page wihtin 4 seconds ' , 'aplicantRequest.php?do=formApplicant' , '4');


                        } elseif( empty($formErrors) ){

                            $quary = $connect->prepare("INSERT INTO

                                applicant_request(employer_id , advertisment_id , job_seeker_id , 
                                                  date_of_applicant , CV , description)

                                VALUES(:z_employerID , :z_advertismentID , :z_jobSeekerID , 
                                       now() , :z_CV , :z_description )
                            ");

                            $quary->execute(array(
                                                        
                                'z_employerID'     => $_SESSION['apply-employerID'] ,
                                'z_advertismentID' => $_SESSION['apply-advirtismentID'] ,
                                'z_jobSeekerID'    => $_SESSION['apply-jobSeekerID'] ,
                                'z_CV'             => $CV_New_Name ,
                                'z_description'    => $description
                            ));

                            move_uploaded_file($CV_Position , "uploads/CV/" . $CV_New_Name);

                            redirectPage('success' , 'The request has been submitted successfully');

                            redirectPage('info' , 'redirect find work wihtin 4 seconds ' , 'findWork.php' , '4');
                        }
                }
        }
?>


<?php
    include $tmp . 'Footer.php';
    ob_end_flush();
?>