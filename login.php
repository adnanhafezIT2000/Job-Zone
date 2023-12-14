<?php
    ob_start();
    session_start();
    $pageTitle = 'Login';

        if( isset($_SESSION['username']) ){   
            header('Location:homePage.php');

        }else if( isset($_SESSION['companyName']) ){
            header('Location:homePage.php');
        }

    include 'initialize.php';

    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){ // Check If User Coming From HTTP POST Request

            $username    = $_POST['username'];
            $password    = $_POST['password'];
            $accountType = $_POST['select'];

            if($accountType == 'job-seeker'){ // Job Seeker Account

                    // Check If The User Exist In DataBase.
                    $quary = $connect->prepare("
                        SELECT job_seeker_id , user_name , password
                        FROM job_seeker
                        WHERE user_name = ? AND password = ?
                        LIMIT 1 
                    ");
                    $quary->execute( array($username , $password) );
                    $row    = $quary->fetch();   
                    $count  = $quary->rowCount();

                    if($count > 0){

                        $_SESSION['username'] = $username;
                        $_SESSION['jobSeekerID']  = $row['job_seeker_id'];
                        header('Location:homePage.php');
                        exit();
                    };

            } else if($accountType == 'employer'){ // Employer Account

                    // Check If The Company Exist In DataBase.
                    $quary = $connect->prepare("
                        SELECT employer_id , account_name , password
                        FROM employer
                        WHERE account_name = ? AND password = ?
                        LIMIT 1 
                    ");
                    $quary->execute(array($username , $password));
                    $row    = $quary->fetch();   
                    $count  = $quary->rowCount();

                    if($count > 0){

                        $_SESSION['companyName'] = $username;
                        $_SESSION['companyID']   = $row['employer_id'];
                        header('Location:homePage.php');
                        exit();
                    };
            }

        }
?>

<div class="login-page">
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
        <div class="create-account">
            <p> Need an account ? </p>
            <a href="sign-in.php"> sign-up </a>
        </div>
        <section class="container">
            <h1> log in to job zone </h1>
            <p> Hire top talent or get hired for your dream job </p>
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                <input 
                    type="text" 
                    name="username" 
                    placeholder="Username" 
                    required = "required"
                    autocomplete="OFF"
                >
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Password" 
                    required = "required"
                    autocomplete="new-password"
                >
                <i class="fa fa-eye-slash"></i>
                <select name="select" required = "required">
                    <option value=""> Please Select Account Type ... </option>
                    <option value="job-seeker">  Job Seeker </option>
                    <option value="employer">  Employer </option>
                </select>
                <button type="submit">log in</button>
            </form>
        </section>
    </div>
</div>


<?php
    include $tmp . 'footer.php';
    ob_end_flush();
?>