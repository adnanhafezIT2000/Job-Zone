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

    $do = isset($_GET['do']) ? $_GET['do'] : 'categories';

    $quaryCategories = $connect->prepare("SELECT *
                                          FROM category
                                        ");
    $quaryCategories->execute();
    $categories = $quaryCategories->fetchAll();

    if($do == 'categories'){

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
                            <a href="profile.php">
                                    <i class="fa fa-user"></i>
                                    <li> profile </li>
                            </a>
                            <a href="categories.php" class="active-link">
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
                                    <i class="fas fa-tags"></i>
                                    <h3> categories </h3>
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
                                    <div class="col-sm-10 col-sm-offset-1 addCategory">
                                        <h3 class="text-left"> add a new category </h3>
                                        <form action="categories.php?do=addCategory" method="post">
                                            <input 
                                                type="text" 
                                                name="category_name"
                                                placeholder="Enter a new category"
                                                required="required"
                                            >
                                            <button name="add_category" type="submit">add</button>
                                        </form>
                                    </div>
                                    <?php
                                        foreach($categories as $category){ ?>

                                           <div class="box-category col-sm-10 col-sm-offset-1">
                                                <span>
                                                    <?php
                                                        echo countAdvirtismentCategory_subcategory("advertisment_id" , "job_advertisment" , "category_id" , $category['category_id'])
                                                    ?> 
                                                </span>
                                                <p>
                                                    <?php
                                                        echo $category['category_name']
                                                    ?>
                                                </p>
                                                <div class="links">
                                                    <a href="categories.php?do=editCategory&catID=<?php echo $category['category_id']; ?>" class="editLink"> edit </a>
                                                    <a href="categories.php?do=deleteCategory&catID=<?php echo $category['category_id']; ?>" class="deleteLink confirm"> delete </a>
                                                    <a href="categories.php?do=category&catID=<?php echo $category['category_id']; ?>" class="showLink"> show subcategory </a>
                                                </div>
                                           </div>
                                    <?php 
                                        }
                                    ?>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php

            }elseif($do == 'addCategory'){

                if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

                    if( isset( $_POST['add_category'] ) ){
        
                        $categoryName = $_POST['category_name'];
        
                        $quary = $connect->prepare("INSERT INTO 
                                                    category( category_name )
                        
                                                    VALUES( :z_categoryName )
                                                ");
                        
                        $quary->execute(array(
                            'z_categoryName' => $categoryName
                        ));

                        echo '<div class="full-page-alerts">';
                            echo '<div class="content-alerts">';
                                redirectPage('success' , 'category has been added successfully');
                                redirectPage('info' , 'redirect to categories wihtin 3 seconds' , 'categories.php' , '3');
                            echo '</div>';
                        echo '</div>';
                    }

                }

            } elseif($do == 'editCategory'){
                
                    $catID = isset($_GET['catID']) && is_numeric($_GET['catID']) ? $_GET['catID'] : 0;

                    $quaryCategory = $connect->prepare("SELECT category_name
                                                        FROM category
                                                        WHERE
                                                            category_id = ?
                                                    ");
                    $quaryCategory->execute( array( $catID ) );
                    $category = $quaryCategory->fetch();
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
                                        <a href="profile.php">
                                                <i class="fa fa-user"></i>
                                                <li> profile </li>
                                        </a>
                                        <a href="categories.php" class="active-link">
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
                                                <i class="fas fa-tags"></i>
                                                <h3> categories </h3>
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
                                                <div class="col-sm-8 col-sm-offset-2 editCategory">
                                                    <h3 class="text-center"> edit category </h3>
                                                    <form action="categories.php?do=updateCategory" method="post">
                                                        <input 
                                                            type="hidden" 
                                                            name="catID"
                                                            value="<?php echo $catID; ?>"
                                                        >
                                                        <input 
                                                            type="text" 
                                                            name="newCategoryName"
                                                            value="<?php echo $category['category_name']; ?>"
                                                        >
                                                        <button name="updateCategory" type="submit">update</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

           <?php } elseif( $do == 'updateCategory' ){

                        if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

                            if( isset( $_POST['updateCategory'] ) ){
                
                                $catID = $_POST['catID'];
                                $newCategoryName = $_POST['newCategoryName'];
                
                                $quary = $connect->prepare("UPDATE category
                                                            SET category_name = ?
                                                            WHERE category_id = ?
                                                        ");
                                $quary->execute(array( $newCategoryName , $catID ));

                                echo '<div class="full-page-alerts">';
                                    echo '<div class="content-alerts">';
                                        redirectPage('success' , 'category has been updated successfully');
                                        redirectPage('info' , 'redirect to categories wihtin 3 seconds' , 'categories.php' , '3');
                                    echo '</div>';
                                echo '</div>';
                            }
    
                        }

                } elseif( $do == 'deleteCategory' ){

                        $catID = isset($_GET['catID']) && is_numeric($_GET['catID']) ? $_GET['catID'] : 0;
            
                        $quary = $connect->prepare("DELETE FROM category WHERE category_id = ?");
                        $quary->execute(array($catID));

                        echo '<div class="full-page-alerts">';
                            echo '<div class="content-alerts">';
                                redirectPage('success' , 'category has been deleted successfully');
                                redirectPage('info' , 'redirect to categories wihtin 3 seconds' , 'categories.php' , '3');
                            echo '</div>';
                        echo '</div>';

                }elseif( $do == 'category' ){

                    $catID = isset($_GET['catID']) && is_numeric($_GET['catID']) ? $_GET['catID'] : 0;

                    $quaryCategory = $connect->prepare("SELECT category_name
                                                            FROM category
                                                            WHERE
                                                                category_id = ?
                                                        ");
                    $quaryCategory->execute( array( $catID ) );
                    $category = $quaryCategory->fetch();

                    $quarySubcategories = $connect->prepare("SELECT *
                                                            FROM focusing_on
                                                            WHERE
                                                                category_id = ?
                                                        ");
                    $quarySubcategories->execute( array( $catID ) );
                    $subcategories = $quarySubcategories->fetchAll();
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
                                        <a href="profile.php">
                                                <i class="fa fa-user"></i>
                                                <li> profile </li>
                                        </a>
                                        <a href="categories.php" class="active-link">
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
                                                <i class="fas fa-tags"></i>
                                                <h3> categories </h3>
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
                                                <div class="col-sm-10 col-sm-offset-1 addCategory">
                                                    <h3 class="text-left"> add a new subcategory </h3>
                                                    <form id="addSubcategory" action="categories.php?do=addSubcategory" method="post">
                                                        <input 
                                                            type="hidden" 
                                                            name="catID"
                                                            value="<?php echo $catID;?>"
                                                        >
                                                        <input 
                                                            type="text" 
                                                            name="subcategory_name"
                                                            placeholder="Enter a new subcategory"
                                                            required="required"
                                                        >
                                                        <button name="add_subcategory" type="submit">add</button>
                                                    </form>
                                                </div>
                                                <div class="col-sm-10 col-sm-offset-1 listSubcategory">
                                                    <h4 class="text-center"> 
                                                        <?php 
                                                            echo $category['category_name'];
                                                        ?> 
                                                    </h4>
                                                    <ul>
                                                        <?php
                                                            foreach($subcategories as $sub){ ?>

                                                                <li>
                                                                    <i>
                                                                        <?php
                                                                            echo countAdvirtismentCategory_subcategory('advertisment_id' , 'job_advertisment' , 'f_id' , $sub['f_id']);
                                                                        ?>
                                                                    </i>
                                                                    <span>
                                                                        <?php
                                                                            echo $sub['f_name'];
                                                                        ?>
                                                                    </span>
                                                                    <div class="links">
                                                                        <a href="categories.php?do=editsSubcategory&subcatID=<?php echo $sub['f_id']; ?>" class="editLink"> edit </a>
                                                                        <a href="categories.php?do=deleteSubcategory&subcatID=<?php echo $sub['f_id']; ?>" class="deleteLink confirm"> delete </a>
                                                                    </div>
                                                                </li>

                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

        <?php }  elseif($do == 'addSubcategory'){

                    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

                        if( isset( $_POST['add_subcategory'] ) ){

                            $catID = $_POST['catID'];
                            $subcategoryName = $_POST['subcategory_name'];

                            $quary = $connect->prepare("INSERT INTO 
                                                        focusing_on( category_id , f_name )
                            
                                                        VALUES( :z_categoryID , :z_fName )
                                                    ");
                            
                            $quary->execute(array(
                                'z_categoryID' => $catID , 
                                'z_fName'      => $subcategoryName
                            ));

                            echo '<div class="full-page-alerts">';
                                echo '<div class="content-alerts">';
                                    redirectPage('success' , 'subcategory has been added successfully');
                                    redirectPage('info' , 'redirect to subcategories wihtin 3 seconds' , $_SERVER['HTTP_REFERER'] , '3');
                                echo '</div>';
                            echo '</div>';
                        }

                    }

                } elseif($do == 'editsSubcategory'){
                
                    $subcatID = isset($_GET['subcatID']) && is_numeric($_GET['subcatID']) ? $_GET['subcatID'] : 0;

                    $quarySubcategory = $connect->prepare("SELECT f_name
                                                        FROM focusing_on
                                                        WHERE
                                                            f_id = ?
                                                    ");
                    $quarySubcategory->execute( array( $subcatID ) );
                    $subcategory = $quarySubcategory->fetch();
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
                                        <a href="profile.php">
                                                <i class="fa fa-user"></i>
                                                <li> profile </li>
                                        </a>
                                        <a href="categories.php" class="active-link">
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
                                                <i class="fas fa-tags"></i>
                                                <h3> categories </h3>
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
                                                <div class="col-sm-8 col-sm-offset-2 editCategory">
                                                    <h3 class="text-center"> edit subcategory </h3>
                                                    <form action="categories.php?do=updateSubcategory" method="post">
                                                        <input 
                                                            type="hidden" 
                                                            name="subcatID"
                                                            value="<?php echo $subcatID; ?>"
                                                        >
                                                        <input 
                                                            type="text" 
                                                            name="newSubcategoryName"
                                                            value="<?php echo $subcategory['f_name']; ?>"
                                                        >
                                                        <button name="updateSubcategory" type="submit">update</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

           <?php } elseif( $do == 'updateSubcategory' ){

                        if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

                            if( isset( $_POST['updateSubcategory'] ) ){
                
                                $subcatID = $_POST['subcatID'];
                                $newSubcategoryName = $_POST['newSubcategoryName'];
                
                                $quary = $connect->prepare("UPDATE focusing_on
                                                            SET f_name = ?
                                                            WHERE f_id = ?
                                                        ");
                                $quary->execute(array( $newSubcategoryName , $subcatID ));

                                echo '<div class="full-page-alerts">';
                                    echo '<div class="content-alerts">';
                                        redirectPage('success' , 'subcategory has been updated successfully');
                                        redirectPage('info' , 'redirect to categories wihtin 3 seconds' , $_SERVER['HTTP_REFERER'] , '3');
                                    echo '</div>';
                                echo '</div>';
                            }
    
                        }

                } elseif( $do == 'deleteSubcategory' ){

                        $subcatID = isset($_GET['subcatID']) && is_numeric($_GET['subcatID']) ? $_GET['subcatID'] : 0;
            
                        $quary = $connect->prepare("DELETE FROM focusing_on WHERE f_id = ?");
                        $quary->execute(array($subcatID));

                        echo '<div class="full-page-alerts">';
                            echo '<div class="content-alerts">';
                                redirectPage('success' , 'subcategory has been deleted successfully');
                                redirectPage('info' , 'redirect to categories wihtin 3 seconds' , $_SERVER['HTTP_REFERER'] , '3');
                            echo '</div>';
                        echo '</div>';
                }
        ?>
<?php
    include $tmp . 'footer.php';
    ob_end_flush();
?>