<?php
    $quaryCategory = $connect->prepare("SELECT category_name 
                                        FROM category
                                    ");
    $quaryCategory->execute();
    $categories = $quaryCategory->fetchAll();
?>
<footer>
    <div class="container">
        <img src="layout/images/images/logo-footer.png" alt="">
        <p class="text">
            <span> job zone <span>It is a job site that allows companies and employers to find suitable 
            job offers for them and helps job seekers to find suitable jobs for them.
        </p>
        <ul>
            <li> <i class="fab fa-facebook-f"></i> </li>
            <li> <i class="fab fa-google-plus-g"></i></li>
            <li> <i class="fab fa-twitter"> </i> </li>
            <li> <i class="fab fa-pinterest-p"></i> </li>
        </ul>
        <div class="email">
            <i class="fa fa-envelope"></i>
            <p> info@jobzone.com </p>
        </div>
        <p class="copy-write">
            © <?php echo date('Y')?> Job Zone. All rights reserved
        </p>
        <div class="categories">
            <div class="row">
                <?php
                    foreach($categories as $category){

                        echo '<div class="col-sm-4">';
                            echo $category['category_name'];
                        echo '</div>';
                    }
                ?>
            </div>
        </div>
    </div>
</footer>