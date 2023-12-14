<?php
    ob_start();
    $pageTitle = 'Employer Profile';
    $homePage = '';
    include 'initialize.php';

    // Quary to fetch job seeker information
        $quary = $connect->prepare("
            SELECT *
            FROM employer 
            WHERE employer_id = ?
            LIMIT 1 
        ");
        $quary->execute( array($_SESSION['companyID']) );
        $row = $quary->fetch();

        $do = isset($_GET['do']) ? $_GET['do'] : 'show-profile-information';

         // Show profile information
         if( $do == 'show-profile-information' ){
     
                    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

                        $formErrors = array();

                        $logoName     = $_FILES['logo']['name'];
                        $logoPosition = $_FILES['logo']['tmp_name'];
                        $logoType     = $_FILES['logo']['type'];
                        $logoSize     = $_FILES['logo']['size'];

                        // Set Allowed Files Extensions
                        $allowedExtensions = array('jpg' , 'gif' , 'jpeg' , 'png');

                        // Get File Extension
                        $logoExtension = strtolower(end(explode('.' , $logoName))); // $logoExtension (string)

                        // Get Random Name
                        $logoRandomName = rand(0 , 1000000000) . $row['account_name'] . '.' . $logoExtension;

                        if( $logoSize < '3000' || $logoSize > '1000000'){ // 3KB ==> 1MB

                            $formErrors[] = '<div> file range ( 3KB ==> 1MB ) </div>';
                        }
                    
                        if( ! in_array($logoExtension , $allowedExtensions) ){

                                $formErrors[] = '<div> file not valid </div>';
                        }

                        if( empty($formErrors) ){

                            $quary = $connect->prepare("UPDATE 
                                                            employer 
                                                        SET 
                                                            logo = ?
                                                        WHERE 
                                                            employer_id = ?");
    
                            $quary->execute(array( $logoRandomName , $_SESSION['companyID'] ) );

                            move_uploaded_file($logoPosition , "uploads/Employers/" . $logoRandomName);

                            echo 
                            "
                                <script>
                                    document.location.href = 'employerProfile.php';
                                </script>
                            ";

                        }
                    }
                ?>
        
                <div class="main-profile-page">
                    <div class="section-photo-name">
                        <div class="content">
                            <div class="photo">
                                <form action="<?php $_SERVER['PHP_SELF']?>" id="formUpdatePhotoJobSeekerProfile" method="POST" enctype="multipart/form-data">
                                    <?php 
                                        if( $row['logo'] == '' ){
                                
                                            echo '<span> <i class="fas fa-building"></i> </span>';
                                    
                                        } else{

                                            echo '<img src="uploads/Employers/' . $row['logo'] . '">';
                                        }
                                    ?>
                                    <div class="upload">
                                        <input type="file" accept="jpg , gif , jpeg , png" name="logo" id="inputTypeFileJobSeekerProfile">
                                        <i class="fa fa-camera"></i>
                                    </div>
                                </form>
                            </div>
                            <h3 class="full-name">
                                <?php echo $row['employer_name']; ?>
                            </h3>
                            <a href="?do=edit-profile-information" class="btn-edit-profile">
                                <i class="fas fa-pen edit"></i>
                                edit profile
                            </a>
                        </div>
                    </div>
                    <div class="section-information employer-section-information">
                        <ul>
                            <li>
                                <i class="fa fa-envelope"></i>
                                <?php echo $row['employer_email']?>
                            </li>
                            <li>
                                <i class="fa fa-phone"></i>
                                <?php echo $row['employer_phone']?>
                            </li>
                            <li>
                                <i class="fa fa-map-marker-alt"></i>
                                <?php echo $row['employer_address']?>
                            </li>
                            <li class="employer-description-show-information">
                                <i class="fa fa-paragraph"></i>
                                <span> 
                                    <?php echo $row['description']?>
                                </span>
                            </li>
                            <li>
                                <i class="fa fa-clock"></i>
                                <?php echo 'Joined ' . $row['date_register']?>
                            </li>
                        </ul>
                    </div>
                </div> 


        <?php } elseif( $do == 'edit-profile-information' ){

                ?>

                <div class="edit-profile-information">
                    <div class="row">
                        <div class="col-sm-3 left-section">
                            <div class="container">
                                <h3> settings </h3>
                                <ul>
                                    <li>
                                        <a href="employerProfile.php?do=edit-profile-information" class="active">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28px" height="28px" viewBox="0 0 24 24" fit="" preserveAspectRatio="xMidYMid meet" focusable="false">
                                                <path fill="#2196F3" d="M12 24C5.373 24 0 18.627 0 12S5.373 0 12 0s12 5.373 12 12-5.373 12-12 12zm4.425-7.958A1.489 1.489 0 0 0 15 15h-.75v-2.25a.753.753 0 0 0-.75-.75H9v-1.5h1.5c.412 0 .75-.338.75-.75v-1.5h1.5c.825 0 1.5-.675 1.5-1.5v-.308A6.005 6.005 0 0 1 18 12c0 1.56-.6 2.977-1.575 4.042zm-5.175 1.905A5.99 5.99 0 0 1 6 12c0-.465.06-.908.158-1.343L9.75 14.25V15c0 .825.675 1.5 1.5 1.5v1.447zM12 4.5c-4.14 0-7.5 3.36-7.5 7.5 0 4.14 3.36 7.5 7.5 7.5 4.14 0 7.5-3.36 7.5-7.5 0-4.14-3.36-7.5-7.5-7.5z"></path>
                                            </svg>
                                            <span> general </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="employerProfile.php?do=account-profile-information">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28px" height="28px" viewBox="0 0 24 24" fit="" preserveAspectRatio="xMidYMid meet" focusable="false">
                                                <path fill="#0CA718" d="M12 24C5.373 24 0 18.627 0 12S5.373 0 12 0s12 5.373 12 12-5.373 12-12 12zm0-12.047h4.5c-.34 2.787-2.109 5.27-4.5 6.047v-6.04H7.5V8.103L12 6v5.953zM12 4.5L6 7.227v4.091c0 3.784 2.56 7.323 6 8.182 3.44-.86 6-4.398 6-8.182v-4.09L12 4.5z"></path>
                                            </svg>
                                            <span> account </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-9 right-section right-section-employer-form">
                            <div class="container">
                                <form action="employerProfile.php?do=update-profile-information" method="POST">
                                    <h3> general </h3>
                                    <span> profile information </span>
                                    <div class="form">
                                        <div class="col-sm-6">
                                            <input 
                                                type="text" 
                                                placeholder="company , full name , ....."
                                                name="employerName"
                                                id="profileGeneralEmployer_name"
                                                value="<?php 
                                                    echo $row['employer_name'];
                                                ?>"
                                                oninput="profileGeneralEmployer_checkNameLive()"
                                            >
                                            <p> </p>
                                        </div>
                                        <div class="col-sm-6">
                                            <input 
                                                type="email" 
                                                placeholder="Example@gmail.com"
                                                name="employerEmail"
                                                id="profileGeneralEmployer_email"
                                                value="<?php 
                                                    echo $row['employer_email'];
                                                ?>"
                                                oninput="profileGeneralEmployer_checkEmailLive()"
                                            >
                                            <p> </p>
                                        </div>
                                        <div class="col-sm-6">
                                            <input 
                                                type="tel" 
                                                placeholder="Enter your phone number"
                                                name="employerPhone"
                                                id="profileGeneralEmployer_phone"
                                                value="<?php 
                                                    echo $row['employer_phone'];
                                                ?>"
                                                oninput="profileGeneralEmployer_checkPhoneLive()"
                                            >
                                            <p> </p>
                                        </div>
                                        <div class="col-sm-6">
                                            <input 
                                                type="text" 
                                                placeholder="Enter your address"
                                                name="employerAddress"
                                                id="profileGeneralEmployer_address"
                                                value="<?php 
                                                    echo $row['employer_address'];
                                                ?>"
                                                oninput="profileGeneralEmployer_checkAddressLive()"
                                            >
                                            <p> </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <textarea 
                                                name="employerDescription" 
                                                placeholder="Enter your description" 
                                                id="profileGeneralEmployer_editDescription"
                                                oninput="profileGeneralEmployer_checkDescriptionLive()"
                                            ><?php echo $row['description']; ?></textarea>
                                            <p> </p>
                                            <span> </span>
                                        </div>
                                        <div class="col-sm-offset-3 col-sm-6" id="profileButtonEmployerSubmit">
                                            <button type="submit">save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


        <?php } elseif($do == 'update-profile-information') {


                    if( $_SERVER['REQUEST_METHOD'] == "POST" ){

                        $employerName        = filter_var( $_POST['employerName'] , FILTER_SANITIZE_STRING );
                        $employerEmail       = filter_var( $_POST['employerEmail']  , FILTER_SANITIZE_EMAIL );
                        $employerPhone       = filter_var( $_POST['employerPhone'] , FILTER_SANITIZE_NUMBER_INT );
                        $employerAddress     = filter_var( $_POST['employerAddress']  , FILTER_SANITIZE_STRING );
                        $employerDescription = filter_var( $_POST['employerDescription'] , FILTER_SANITIZE_STRING );

                        $quary = $connect->prepare("UPDATE 
                                                        employer 
                                                    SET 
                                                        employer_name = ? , 
                                                        employer_address = ? ,
                                                        employer_email = ? , 
                                                        employer_phone = ? ,
                                                        description = ?
                                                    WHERE 
                                                        employer_id = ?");

                        $quary->execute(array($employerName , $employerAddress , $employerEmail , $employerPhone , $employerDescription , $_SESSION['companyID']));

                        echo '<div class="full-page-alerts with-navbar">';
                            echo '<div class="content-alerts">';
                                redirectPage('success' , 'your profile information has been updated successfully');
                                redirectPage('info' , 'redirect profile page wihtin 3 seconds' , 'employerProfile.php?do=edit-profile-information' , '3');
                            echo '</div>';
                        echo '</div>';
                    }


            } elseif( $do == 'account-profile-information' ){

                ?>
                <div class="edit-profile-information account-edit-profile-information">
                    <div class="row">
                        <div class="col-sm-3 left-section">
                            <div class="container">
                                <h3> settings </h3>
                                <ul>
                                    <li>
                                        <a href="employerProfile.php?do=edit-profile-information">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28px" height="28px" viewBox="0 0 24 24" fit="" preserveAspectRatio="xMidYMid meet" focusable="false">
                                                <path fill="#2196F3" d="M12 24C5.373 24 0 18.627 0 12S5.373 0 12 0s12 5.373 12 12-5.373 12-12 12zm4.425-7.958A1.489 1.489 0 0 0 15 15h-.75v-2.25a.753.753 0 0 0-.75-.75H9v-1.5h1.5c.412 0 .75-.338.75-.75v-1.5h1.5c.825 0 1.5-.675 1.5-1.5v-.308A6.005 6.005 0 0 1 18 12c0 1.56-.6 2.977-1.575 4.042zm-5.175 1.905A5.99 5.99 0 0 1 6 12c0-.465.06-.908.158-1.343L9.75 14.25V15c0 .825.675 1.5 1.5 1.5v1.447zM12 4.5c-4.14 0-7.5 3.36-7.5 7.5 0 4.14 3.36 7.5 7.5 7.5 4.14 0 7.5-3.36 7.5-7.5 0-4.14-3.36-7.5-7.5-7.5z"></path>
                                            </svg>
                                            <span> general </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="employerProfile.php?do=account-profile-information" class="active">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28px" height="28px" viewBox="0 0 24 24" fit="" preserveAspectRatio="xMidYMid meet" focusable="false">
                                                <path fill="#0CA718" d="M12 24C5.373 24 0 18.627 0 12S5.373 0 12 0s12 5.373 12 12-5.373 12-12 12zm0-12.047h4.5c-.34 2.787-2.109 5.27-4.5 6.047v-6.04H7.5V8.103L12 6v5.953zM12 4.5L6 7.227v4.091c0 3.784 2.56 7.323 6 8.182 3.44-.86 6-4.398 6-8.182v-4.09L12 4.5z"></path>
                                            </svg>
                                            <span> account </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-9 right-section">
                            <div class="container right-section-change-password">
                                <h3> account </h3>
                                <div class="formUsernameChangeJobSeeker">
                                    <span> change username </span>
                                    <form id="formProfileAccountUsernameJobSeeker" action="employerProfile.php?do=update-account-profile-information" method="POST">
                                        <input 
                                            type="text" 
                                            placeholder="Username"
                                            name="emaployerUsername"
                                            id="profileAccountJobSeeker_username"
                                            value="<?php 
                                                echo $row['account_name'];
                                            ?>"
                                            oninput="formProfileAccountJobSeeker_checkUsernameLive()"
                                        >
                                        <p> </p>
                                        <button type="submit" name="changeUsername"> save </button>
                                    </form>
                                </div>
                                <div class="formPasswordChangeJobSeeker">
                                    <span> change password </span>
                                    <form id="formProfileAccountPasswordJobSeeker" action="employerProfile.php?do=update-account-profile-information" method="POST">
                                        <input 
                                                type="password" 
                                                placeholder="Current Password"
                                                name="currentPassword"
                                                id="profileAccountJobSeeker_currentPassword"
                                                oninput="formProfilePasswordJobSeeker_checkLiveCurrent()"
                                            >
                                            <i class="fa fa-eye-slash"></i>
                                            <p>
                                                <?php
                                                    if( isset($formErrors) ){

                                                        if( count($formErrors) > 0 ){

                                                            echo $formErrors['current-password-false'];
                                                        }
                                                    }
                                                ?> 
                                            </p>

                                            <input 
                                                type="password" 
                                                placeholder="New Password"
                                                name="newPassword"
                                                id="profileAccountJobSeeker_newPassword"
                                                oninput="formProfilePasswordJobSeeker_checkLiveNew()"
                                            >
                                            <i class="fa fa-eye-slash"></i>
                                            <p> </p>

                                            <input 
                                                type="password" 
                                                placeholder="Confirm New Password"
                                                name="confirmNewPassword"
                                                id="profileAccountJobSeeker_confirmNewPassword"
                                                oninput="formProfilePasswordJobSeeker_checkLiveConfirm()"
                                            >
                                            <i class="fa fa-eye-slash"></i>
                                            <p> </p>
                                            <button type="submit" name="changePassword"> save </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
       <?php } elseif( $do == 'update-account-profile-information'){

                    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

                        if( isset( $_POST['changeUsername'] ) ){

                            $emaployerUsername = filter_var( $_POST['emaployerUsername'] , FILTER_SANITIZE_STRING );

                            $quary = $connect->prepare("UPDATE 
                                                            employer 
                                                        SET 
                                                            account_name = ?
                                                        WHERE 
                                                            employer_id = ?");

                            $quary->execute(array( $emaployerUsername , $_SESSION['companyID']));

                            echo '<div class="full-page-alerts with-navbar">';
                                echo '<div class="content-alerts">';
                                    redirectPage('success' , 'your username has been updated successfully');
                                    redirectPage('info' , 'redirect account profile page wihtin 3 seconds' , 'employerProfile.php?do=account-profile-information' , '3');
                                echo '</div>';
                            echo '</div>';

                        } elseif( isset( $_POST['changePassword'] ) ){

                                $currentPassword    = $_POST['currentPassword'];
                                $newPassword        = $_POST['newPassword'];
                                $confirmNewPassword = $_POST['confirmNewPassword'];

                                $formErrors = array();

                                if( $row['password'] !== $currentPassword ){

                                    $formErrors['current-password-false'] = "Current Password is not true";
                                    
                                    echo '<div class="full-page-alerts with-navbar">';
                                        echo '<div class="content-alerts">';
                                            redirectPage('danger' , 'current password is not true , please try again');
                                            redirectPage('info' , 'redirect account profile page wihtin 3 seconds' , 'employerProfile.php?do=account-profile-information' , '3');
                                        echo '</div>';
                                    echo '</div>';

                                } else{

                                    $quary = $connect->prepare("UPDATE 
                                                                    employer 
                                                                SET 
                                                                    password = ?
                                                                WHERE 
                                                                    employer_id = ?");

                                    $quary->execute(array( $newPassword , $_SESSION['companyID']));

                                    echo '<div class="full-page-alerts with-navbar">';
                                        echo '<div class="content-alerts">';
                                            redirectPage('success' , 'your password has been updated successfully');
                                            redirectPage('info' , 'redirect account profile page wihtin 3 seconds' , 'employerProfile.php?do=account-profile-information' , '3');
                                        echo '</div>';
                                    echo '</div>';

                                }
                            }
                        }
            }?>

<?php
    include $tmp . 'footer.php';
    ob_end_flush();
?>