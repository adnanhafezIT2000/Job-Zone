<?php
    ob_start();
    session_start();
    $pageTitle = 'Admin Login';

    if( isset( $_SESSION['adminID'] ) ){

        header('Location:dashboard.php');
        exit();
    }

    include '../initialize.php';

    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

        $username    = $_POST['username'];
        $password    = $_POST['password'];

        $quary = $connect->prepare("
            SELECT *
            FROM admin
            WHERE user_name = ? AND password = ?
            LIMIT 1 
        ");
        $quary->execute( array($username , $password) );
        $row    = $quary->fetch();   
        $count  = $quary->rowCount();

        if($count > 0){

            $_SESSION['adminID']  = $row['admin_id'];
            header('Location:dashboard.php');
            exit();
        };
    }
?>
    <div class="admin-login-page">
        <div class="login-box">
            <h1> admin login </h1>
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                <input 
                    type="text" 
                    name="username" 
                    placeholder="Username"
                    required="required"
                >
                <input 
                    type="password" 
                    name="password"
                    placeholder="Password"
                    required="required"
                >
                <i class="fa fa-eye-slash"></i>
                <button type="submit">login</button>
            </form>
        </div>
    </div>
<?php
    include $tmp . 'footer.php';
    ob_end_flush();
?>