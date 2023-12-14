<?php
    ob_start();
    session_start();
    $pageTitle = 'SignIn';
    error_reporting(E_ALL & ~E_NOTICE);
    include 'initialize.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'choose-account-create';

    if($do == 'choose-account-create'){ 

            if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

                    if( $_POST['account-type'] == 'job-seeker' ){

                        header('Location:sign-in.php?do=job-seeker');
                        exit();

                    } elseif( $_POST['account-type'] == 'employer' ){

                        header('Location:sign-in.php?do=employer');
                        exit();
                    }
            }
        ?>

        <div class="sign-up-page">
            <div class="description-section">
                <div class="container">
                    <div class="logo">
                        <a href="homePage.php">
                            <img src="layout/images/images/logo.png"> 
                        </a>   
                    </div>
                    <section class="section-for-employer">
                        <div class="content">
                            <h1> for employers </h1>
                            <ul> 
                                <li>
                                    <i class="fa fa-check"></i>
                                    <span> Post unlimited jobs for free </span>
                                </li>
                                <li>
                                    <i class="fa fa-check"></i>
                                    <span> Find professionals across all skills </span>
                                </li>
                                <li>
                                    <i class="fa fa-check"></i>
                                    <span> Browse services and portfolios </span>
                                </li>
                            </ul>
                        </div>
                    </section>
                    <section class="section-for-job-seeker">
                        <div class="content">
                            <h1> for job seekers </h1>
                            <ul> 
                                <li>
                                    <i class="fa fa-check"></i>
                                    <span> Find jobs from full-time to intership </span>
                                </li>
                                <li>
                                    <i class="fa fa-check"></i>
                                    <span> Expand your professional network </span>
                                </li>
                                <li>
                                    <i class="fa fa-check"></i>
                                    <span> Showcase your creativity and skills </span>
                                </li>
                            </ul>
                        </div>
                    </section>
                </div>
            </div>
            <div class="form-section">
                <div class="link-log-in">
                    <p> Already have an account ? </p>
                    <a href="login.php"> log in </a>
                </div>
                <section class="container">
                    <h1> create free account </h1>
                    <p> Hire top talent or get hired for your dream job </p>
                    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                        <div class="job-seeker-radio">
                            <label for="job-seeker" class="large-label"></label>
                            <input type="radio" name="account-type" value="job-seeker" id="job-seeker">
                            <label for="job-seeker" class="text-label" id="textJobSeeker"> job seeker </label>
                        </div>
                        <div class="employer-radio">
                            <label for="employer" class="large-label"></label>
                            <input type="radio" name="account-type" value="employer" id="employer">
                            <label for="employer" class="text-label" id="textEmployer"> employer </label>
                        </div>
                        <button type="submit">continue</button>
                    </form>
                </section>
            </div>
        </div>


    <?php } elseif($do == 'job-seeker'){ 
        
                $categories = $connect->prepare("SELECT * FROM category");
                $categories->execute();
                $getCategories = $categories->fetchAll();

            ?>
                <div class="sign-up-job-seeker">
                    <div class="container main-container">
                        <div class="row">
                            <div class="col-sm-4 photo">
                                <img src="layout/images/images/sign-up.png">
                            </div>
                            <div class="col-sm-8 form">
                                <div class="container">
                                    <form action="sign-in.php?do=job-seeker-insert" method="post">
                                        <div class="col-sm-6 form-group">
                                            <span>usernsme</span>
                                            <input 
                                                type="text" 
                                                name="username" 
                                                placeholder="At least 3 characters"
                                                id="job-seeker-username"
                                                oninput = "jobSeeker_checkUsernameLive()"
                                                class="check_username_jobSeeker"

                                            >
                                            <p> </p>
                                            <small class="error_username"> 
                                                
                                            </small>
                                            <h5 id="job-seeker-username-availability"></h5>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <span>password</span>
                                            <input 
                                                type="password" 
                                                name="password" 
                                                placeholder="At least 8 characters"
                                                id="job-seeker-password"
                                                oninput="jobSeeker_checkPasswordLive()"
                                            >
                                            <i class="fa fa-eye-slash"></i>
                                            <p>  </p>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <span>email</span>
                                            <input 
                                                type="email" 
                                                name="email" 
                                                placeholder="Example@gmail.com"
                                                id="job-seeker-email"
                                                oninput="jobSeeker_checkEmailLive()"
                                                class="check_email_jobSeeker"
                                            >
                                            <p class="error-email">  </p>
                                            <small class="error_email"> 
                                                
                                            </small>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <span>full name</span>
                                            <input 
                                                type="text" 
                                                name="fullName" 
                                                placeholder="Enter your full name"
                                                id="job-seeker-fullName"
                                                oninput="jobSeeker_checkFullNameLive()"
                                            >
                                            <p>  </p>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <span>date of birth</span>
                                            <input 
                                                type="date" 
                                                name="date" 
                                                id="job-seeker-date"
                                                oninput="jobSeeker_checkDateLive()"
                                            >
                                            <p>  </p>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <span>phone</span>
                                            <input 
                                                type="tel" 
                                                name="phone"
                                                placeholder="Enter your phone number"
                                                id="job-seeker-phone"
                                                oninput="jobSeeker_checkPhoneLive()"
                                            >
                                            <p>  </p>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <span>address</span>
                                            <input 
                                                type="text" 
                                                name="address"
                                                placeholder="Enter your address"
                                                id="job-seeker-address"
                                                oninput="jobSeeker_checkAddressLive()"
                                            >
                                            <p>  </p>
                                        </div>
                                        <div class="col-sm-6 form-group form-radio-group">
                                            <span>gender</span>
                                            <div class="radio-group">
                                                <input type="radio" value="male" name="gender" id="male" checked>
                                                <label for="male">male</label>
                                            </div>
                                            <div class="radio-group">
                                                <input type="radio" value="female" name="gender" id="female">
                                                <label for="female">female</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 paragraph-skill">
                                            <p>
                                                Please choose your skill in order to show
                                                suitable job advertisment for you ...
                                            </p>
                                        </div>
                                        <div class="col-sm-6 form-group category">
                                            <select name="category" id="category" oninput="jobSeeker_checkCategoryLive()">
                                                <option value="">select category</option>
                                               <?php
                                                   foreach($getCategories as $category){

                                                        echo "<option value='" . $category['category_id'] . "'>"
                                                             . $category['category_name'] .
                                                             "</option>";
                                                   }
                                               ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 form-group foucsing-on">
                                            <select name="foucsing-on" id="foucsing-on">
                                                <option value="">choose category to show foucsing</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4 button-submit">
                                            <button type="submit"> sign up </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

     <?php } elseif($do == 'job-seeker-insert'){

                if( $_SERVER['REQUEST_METHOD'] == 'POST' ){


                    $username    = filter_var( $_POST['username'] , FILTER_SANITIZE_STRING );
                    $password    = filter_var( $_POST['password'] , FILTER_SANITIZE_STRING );
                    $email       = filter_var( $_POST['email'] , FILTER_SANITIZE_EMAIL );
                    $fullName    = filter_var( $_POST['fullName'] , FILTER_SANITIZE_STRING );
                    $dateOfBirth = $_POST['date'];
                    $phone       = filter_var( $_POST['phone']   , FILTER_SANITIZE_NUMBER_INT);
                    $address     = filter_var( $_POST['address'] , FILTER_SANITIZE_STRING );
                    $gender      = $_POST['gender'];
                    $categoryID  = $_POST['category'];
                    $foucsingID  = $_POST['foucsing-on'];
                    $age = explode("-" , $dateOfBirth);
                    $age = date("Y") - $age['0'];

                    $getUsername = $connect->prepare("SELECT user_name 
                                                       FROM job_seeker
                                                       WHERE user_name = ?");
                    $getUsername->execute( array($username) );
                    $rowUsername = $getUsername->rowCount();

                    $getEmail = $connect->prepare("SELECT email 
                                                    FROM job_seeker
                                                    WHERE email = ?");
                    $getEmail->execute( array($email) );
                    $rowEmail = $getEmail->rowCount();

                    
                    if($rowUsername > 0){

                            echo '<div class="full-page-alerts">';
                                echo '<div class="content-alerts">';
                                    redirectPage('danger' , 'the account has not been created');
                                    redirectPage('info' , 'redirect to sign up page wihtin 5 seconds' , 'sign-in.php?do=job-seeker' , '5');
                                echo '</div>';
                            echo '</div>';

                    } elseif($rowEmail > 0){

                            echo '<div class="full-page-alerts">';
                                echo '<div class="content-alerts">';
                                    redirectPage('danger' , 'the account has not been created');
                                    redirectPage('info' , 'redirect to sign up page wihtin 5 seconds' , 'sign-in.php?do=job-seeker' , '5');
                                echo '</div>';
                            echo '</div>';
                    } 
                    
                    else{

                        $quary = $connect->prepare("INSERT INTO 
                            job_seeker( category_id , f_id , user_name , password ,
                                        full_name , email , age , gender , 
                                        phone , date_of_birth , address , date_register )

                            VALUES( :z_categoryID , :z_foucsingID , :z_username , :z_password , 
                                    :z_fullName , :z_email , :z_age , :z_gender , 
                                    :z_phone , :z_dateOfBirth , :z_address , now() )
                        ");

                        $quary->execute(array(
                                
                            'z_categoryID'     => $categoryID ,
                            'z_foucsingID'     => $foucsingID ,
                            'z_username'       => $username ,
                            'z_password'       => $password ,
                            'z_fullName'       => $fullName ,
                            'z_email'          => $email ,
                            'z_age'            => $age , 
                            'z_gender'         => $gender ,
                            'z_phone'          => $phone ,
                            'z_dateOfBirth'    => $dateOfBirth , 
                            'z_address'        => $address
                        ));

                        echo '<div class="full-page-alerts">';
                            echo '<div class="content-alerts">';
                                redirectPage('success' , 'your account has been created successfully');
                                redirectPage('info' , 'redirect to login page wihtin 5 seconds' , 'login.php' , '5');
                            echo '</div>';
                        echo '</div>';
                    }
                }

     } elseif($do == 'employer'){ 
          
          ?>

                <div class="sign-up-employer">
                    <div class="container main-container">
                        <div class="row">
                            <div class="col-sm-4 photo">
                                <img src="layout/images/images/sign-up.png">
                            </div>
                            <div class="col-sm-8 form">
                                <div class="container">
                                    <form action="sign-in.php?do=employer-insert" method="post">
                                        <div class="col-sm-6 form-group">
                                            <span>usernsme</span>
                                            <input 
                                                type="text" 
                                                name="username" 
                                                placeholder="At least 3 characters"
                                                id="employer-username"
                                                oninput = "Employer_checkUsernameLive()"
                                                class="check_username_employer"
                                            >
                                            <p> </p>
                                            <small class="error_username_employer">

                                            </small>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <span>password</span>
                                            <input 
                                                type="password" 
                                                name="password" 
                                                placeholder="At least 8 characters"
                                                id="employer-password"
                                                oninput="Employer_checkPasswordLive()"
                                            >
                                            <i class="fa fa-eye-slash"></i>
                                            <p>  </p>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <span>email</span>
                                            <input 
                                                type="email" 
                                                name="email" 
                                                placeholder="Example@gmail.com"
                                                id="employer-email"
                                                oninput="Employer_checkEmailLive()"
                                                class="check_email_employer"
                                            >
                                            <p>  </p>
                                            <small class="error_email_employer">

                                            </small>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <span>employer name</span>
                                            <input 
                                                type="text" 
                                                name="employer-name" 
                                                placeholder="company , full name , ....."
                                                id="employer-name"
                                                oninput="Employer_checkNameLive()"
                                            >
                                            <p>  </p>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <span>phone</span>
                                            <input 
                                                type="tel" 
                                                name="phone"
                                                placeholder="Enter your phone number"
                                                id="employer-phone"
                                                oninput="Employer_checkPhoneLive()"
                                            >
                                            <p>  </p>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <span>address</span>
                                            <input 
                                                type="text" 
                                                name="address"
                                                placeholder="Enter your address"
                                                id="employer-address"
                                                oninput="Employer_checkAddressLive()"
                                            >
                                            <p>  </p>
                                        </div>
                                        <div class="col-sm-12 form-group">
                                            <span>description</span>
                                           <textarea 
                                                name="description" 
                                                id="employer-description" 
                                                placeholder="Enter your description"
                                                oninput="Employer_checkDescriptionLive()"></textarea>
                                            <p> </p>
                                            <span> </span>
                                        </div>

                                        <div class="col-sm-4 button-submit">
                                            <button type="submit"> sign up </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


    <?php } elseif($do == 'employer-insert'){


                if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

                    $username    = filter_var( $_POST['username'] , FILTER_SANITIZE_STRING );
                    $password    = filter_var( $_POST['password'] , FILTER_SANITIZE_STRING );
                    $email       = filter_var( $_POST['email'] , FILTER_SANITIZE_EMAIL );
                    $name        = filter_var( $_POST['employer-name'] , FILTER_SANITIZE_STRING );
                    $phone       = filter_var( $_POST['phone']   , FILTER_SANITIZE_NUMBER_INT);
                    $address     = filter_var( $_POST['address'] , FILTER_SANITIZE_STRING );
                    $description = filter_var( $_POST['description'] , FILTER_SANITIZE_STRING);

                    $getUsername = $connect->prepare("SELECT account_name 
                                                      FROM employer
                                                      WHERE account_name = ?");
                    $getUsername->execute( array($username) );
                    $rowUsername = $getUsername->rowCount();

                    $getEmail = $connect->prepare("SELECT employer_email 
                                                   FROM employer
                                                   WHERE employer_email = ?");
                    $getEmail->execute( array($email) );
                    $rowEmail = $getEmail->rowCount();


                    if($rowUsername > 0){

                        echo '<div class="full-page-alerts">';
                            echo '<div class="content-alerts">';
                                redirectPage('danger' , 'the account has not been created');
                                redirectPage('info' , 'redirect to sign up page wihtin 5 seconds' , 'sign-in.php?do=employer' , '5');
                            echo '</div>';
                        echo '</div>';

                    } elseif($rowEmail > 0){

                        echo '<div class="full-page-alerts">';
                            echo '<div class="content-alerts">';
                                redirectPage('danger' , 'the account has not been created');
                                redirectPage('info' , 'redirect to sign up page wihtin 5 seconds' , 'sign-in.php?do=employer' , '5');
                            echo '</div>';
                        echo '</div>';

                    } else{

                        $quary = $connect->prepare("INSERT INTO 
                            employer( account_name , password , employer_name ,
                                    employer_address , employer_email , employer_phone ,
                                    description , date_register )

                            VALUES( :z_username , :z_password , :z_name ,
                                    :z_address , :z_email , :z_phone , 
                                    :z_description , now() )
                        ");

                        $quary->execute(array(
                                
                            'z_username'       => $username ,
                            'z_password'       => $password ,
                            'z_name'           => $name ,
                            'z_address'        => $address ,
                            'z_email'          => $email ,
                            'z_phone'          => $phone ,
                            'z_description'    => $description
                        ));

                        echo '<div class="full-page-alerts">';
                            echo '<div class="content-alerts">';
                                redirectPage('success' , 'your account has been created successfully');
                                redirectPage('info' , 'redirect to login page wihtin 5 seconds' , 'login.php' , '5');
                            echo '</div>';
                        echo '</div>';

                    }
                }
                    
            } 
    ?>    

<?php
   include $tmp . 'footer.php';
   ob_end_flush();
?>