<?php
    ob_start();
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);
    $pageTitle = 'Admin Profile';
    include '../initialize.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'profile';

    $quary = $connect->prepare("SELECT *
                                FROM admin
                                WHERE admin_id = ?
                                LIMIT 1
                            ");
    $quary->execute( array($_SESSION['adminID']) );
    $row = $quary->fetch();

        if( $do == 'profile' ){

            if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
                    
                $formErrors = array();
    
                $imageName     = $_FILES['image']['name'];
                $imagePosition = $_FILES['image']['tmp_name'];
                $imageType     = $_FILES['image']['type'];
                $imageSize     = $_FILES['image']['size'];
    
                // Set Allowed Files Extensions
                $allowedExtensions = array('jpg' , 'gif' , 'jpeg' , 'png');
    
                // Get File Extension
                $imageExtension = strtolower(end(explode('.' , $imageName))); // $imageExtension (string)
    
                // Get Random Name
                $imageRandomName = rand(0 , 1000000000) . $row['user_name'] . '.' . $imageExtension ;
    
                if( $imageSize < '3000' || $imageSize > '1000000'){ // 3KB ==> 1MB
    
                        $formErrors[] = '<div> file range ( 3KB ==> 1MB ) </div>';
                }
                
                if( ! in_array($imageExtension , $allowedExtensions) ){
    
                        $formErrors[] = '<div> file not valid </div>';
                }
    
                if( empty($formErrors) ){
    
                        $quary = $connect->prepare("UPDATE 
                                                        admin 
                                                    SET 
                                                        image = ?
                                                    WHERE 
                                                        admin_id = ?");
    
                        $quary->execute(array( $imageRandomName , $_SESSION['adminID'] ) );
    
                        move_uploaded_file($imagePosition , "uploads/" . $imageRandomName);
    
                        echo 
                        "
                            <script>
                                document.location.href = 'profile.php';
                            </script>
                        ";
    
                }
            
            }
?>
    <div class="admin-dashboard">
       <div class="container">
            <div class="row">
                <div class="col-sm-3 side-navbar">
                    <div class="container">
                        <img src="layout/images/logo.png" alt="">
                        <ul>
                           <a href="dashboard.php">
                                <i class="fa fa-bars"></i>
                                <li> dashboard </li>
                           </a>
                           <a href="profile.php" class="active-link">
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
                                <i class="fa fa-user"></i>
                                <h3> profile </h3>
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
                                <div class="col-sm-8 col-sm-offset-2 formProfileBox">
                                    <form action="<?php $_SERVER['PHP_SELF']?>" method="post" id="formUpdatePhotoAdminProfile" enctype="multipart/form-data">
                                        <div class="image">
                                            <?php 
                                                if( $row['image'] == '' ){
                                        
                                                    echo '<img src="layout/images/default.jpg" alt="">';
                                            
                                                } else{

                                                    echo '<img src="uploads/' . $row['image'] . '">';
                                                }
                                            ?>
                                            <div class="upload">
                                                <input type="file" accept="jpg , gif , jpeg , png" name="image" id="inputTypeFileAdminProfile">
                                                <i class="fa fa-camera"></i>
                                            </div>
                                        </div>
                                    </form>

                                    <form action="profile.php?do=update" method="post" class="formAdminUpdate">
                                        <input 
                                            type="text" 
                                            name="username"
                                            value="<?php echo $row['user_name']?>"
                                            placeholder="Enter username"
                                            required="required"
                                        >
                                        <input 
                                            type="password" 
                                            name="password"
                                            value="<?php echo $row['password']?>"
                                            placeholder="Enter password"
                                            required="required"
                                        >
                                        <i class="fa fa-eye-slash"></i>
                                        <input 
                                            type="text" 
                                            name="fullName"
                                            value="<?php echo $row['full_name']?>"
                                            placeholder="Enter full name"
                                            required="required"
                                        >
                                        <button name="update" type="submit"> update </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       </div>
    </div>

    <?php
        }elseif( $do == 'update' ){

            if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

                if( isset($_POST['update']) ){

                        $username = $_POST['username'];
                        $password = $_POST['password'];
                        $fullName = $_POST['fullName'];

                        $quary = $connect->prepare("UPDATE 
                                                    admin 
                                                SET 
                                                    user_name = ? , 
                                                    password  = ? , 
                                                    full_name = ?
                                                WHERE 
                                                    admin_id = ?");

                        $quary->execute(array( $username , $password  , $fullName , $_SESSION['adminID'] ) );

                        echo '<div class="full-page-alerts">';
                            echo '<div class="content-alerts">';
                                redirectPage('success' , 'information has been updated successfully');
                                redirectPage('info' , 'redirect to profile wihtin 3 seconds' , 'profile.php' , '3');
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