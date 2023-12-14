<?php
    ob_start();
    error_reporting(E_ALL & ~E_NOTICE);
    $pageTitle = 'Find Work';
    $homePage = '';
    include 'initialize.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'main-search';

    if( $do == 'main-search' ){

        // Fetch all active job Advertisments informations
        $fetchAllJobAdvertisments = $connect->prepare("SELECT * 
                                                       FROM job_advertisment
                                                       WHERE advertisment_status = 1
                                                       ORDER BY date_of_publication DESC 
                                                    ");
        $fetchAllJobAdvertisments->execute();
        $rowAdvertisments = $fetchAllJobAdvertisments->fetchAll();

        // Fetch all categories
        $fetchAllCategories = $connect->prepare("SELECT * 
                                                 FROM category 
                                               ");
        $fetchAllCategories->execute();
        $rowCategories= $fetchAllCategories->fetchAll();

        /* Recommendations  */

        // Fetch Job Seeker Information
        $quaryGetJobSeeker = $connect->prepare("SELECT category_id , gender
                                                FROM job_seeker
                                                WHERE job_seeker_id = ?
                                            ");
        $quaryGetJobSeeker->execute( array($_SESSION['jobSeekerID']) );
        $getJobSeeker = $quaryGetJobSeeker->fetch();

        // Fetch Advirtisments According To The Category and Subcategory And Gender
        $quaryGetAdvirtisments1 = $connect->prepare("SELECT DISTINCT job_advertisment.* 
                                                    FROM job_advertisment
                                    INNER JOIN job_seeker 
                                        ON job_advertisment.category_id = job_seeker.category_id 
                                         
                                    
                                    WHERE job_seeker.category_id  = ? 
                                    AND job_advertisment.gender LIKE ?
                                    AND job_advertisment.advertisment_status = 1
                                    ORDER BY job_advertisment.date_of_publication DESC
                            ");

        $quaryGetAdvirtisments1->execute( array( $getJobSeeker['category_id'] , $getJobSeeker['gender']) );
        $recommendationAdvirtisments1 = $quaryGetAdvirtisments1->fetchAll();
        $rowsRecommendations = $quaryGetAdvirtisments1->rowCount();


        $quaryGetAdvirtisments2 = $connect->prepare("SELECT DISTINCT job_advertisment.* 
                                                    FROM job_advertisment
                                    INNER JOIN job_seeker 
                                        ON job_advertisment.category_id = job_seeker.category_id 
                                         
                                    
                                    WHERE job_seeker.category_id  = ? 
                                    AND job_advertisment.gender LIKE '%anyone%'
                                    AND job_advertisment.advertisment_status = 1
                                    ORDER BY job_advertisment.date_of_publication DESC
                            ");

        $quaryGetAdvirtisments2->execute( array($getJobSeeker['category_id']) );
        $recommendationAdvirtisments2 = $quaryGetAdvirtisments2->fetchAll();
        $rowsRecommendations = $quaryGetAdvirtisments2->rowCount();

        foreach( $recommendationAdvirtisments2 as $row ){

            array_push($recommendationAdvirtisments1 , $row);

        }


?>
            <div class="find-work-section-top">
                <div class="container">
                    <h2> find remote jobs </h2>
                    <p>
                        Create a profile and apply for new remote job opportunities.
                        Find professionals that best match your job requirements.
                    </p>
                    <form action="findWork.php?do=search-job-title-location" method="post">
                        <div class="form-group-search-what" id="form-group-search-what">
                            <span> what </span>
                            <input type="text" name="job-title" placeholder="Job title or keywords">
                            <i class="fa fa-search"></i>
                        </div>
                        <div class="form-group-search-where" id="form-group-search-where">
                            <span> where </span>
                            <input type="text" name="location" placeholder="Company address , city , state or zip code">
                            <i class="fa fa-map-marker-alt"></i>
                        </div>
                        <div class="error">
                            <i class="fa fa-exclamation-circle"></i>
                            <h5> Enter a job title or location to start a search </h5>
                        </div>
                        <button type="submit" name="find-jobs">find jobs</button>
                    </form>
                </div>
            </div>

            <div class="section-advanced-search-find-work">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-3 advanced-search">
                            <div class="col-sm-12 category-search">
                                <h4> categories </h4>
                                <a href="findWork.php" id="activeCategorySearch" class="link-all-category">
                                    all categories
                                    (<?php 
                                        echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE advertisment_status = 1"); 
                                    ?>) 
                                </a>
                                <ul class="main-list-category">
                                    <?php
                                        foreach($rowCategories as $category){

                                            $quary = $connect->prepare(" SELECT COUNT(advertisment_id) as count
                                                                         FROM job_advertisment
                                                                         WHERE 
                                                                              category_id = ?
                                                                              AND
                                                                              advertisment_status = 1
                                                                        LIMIT 1
                                            ");
                                            $quary->execute( array( $category['category_id'] ) );
                                            $count = $quary->fetch();

                                            $categoryName = str_replace("&" , "/" , $category['category_name'] );
                                            
                                            echo '<li class="main-li-list-category">';
                                                echo '<a href="findWork.php?do=searchCategory&catID='
                                                        . $category['category_id']
                                                        . '&catName=' . $categoryName .
                                                     ' "> ';
                                                    echo '<i class="fa fa-plus"></i>';
                                                    echo 
                                                        $category['category_name'] 
                                                        . ' (' . $count["count"] . ')';
                                                echo '</a>';
                                            echo '</li>';

                                        }
                                    ?>
                                    
                                </ul>
                            </div>
                            <form action="findWork.php?do=advancedSearch" method="post">
                                <div class="col-sm-12 search-job-type">
                                    <h4> job type </h4>
                                    <ul>
                                        <li>
                                            <label for="full-time">
                                                <input type="radio" name="job-type" value="full time" id="full-time" required="required">
                                                full time
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'full time' " , "AND advertisment_status = 1");
                                                ?>)
                                            </label>
                                        </li>
                                        <li>
                                            <label for="part-time">
                                                <input type="radio" name="job-type" value="part time" id="part-time" required="required">
                                                part time
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'part time' " , "AND advertisment_status = 1");
                                                ?>)
                                            </a>
                                        </li>
                                        <li>
                                            <label for="contract">
                                                <input type="radio" name="job-type" value="contract" id="contract" required="required">
                                                contract
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'contract' " , "AND advertisment_status = 1");
                                                ?>)
                                            </label>
                                        </li>
                                        <li>
                                            <label for="intership">
                                                <input type="radio" name="job-type" value="intership" id="intership" required="required">
                                                intership
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'intership' " , "AND advertisment_status = 1");
                                                ?>)
                                            </label>
                                        </li>
                                        <li>
                                            <label for="freelance">
                                                <input type="radio" name="job-type" value="freelance" id="freelance" required="required">
                                                freelance
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'freelance' " , "AND advertisment_status = 1");
                                                ?>)
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-sm-12 search-location">
                                    <h4> location </h4>
                                    <ul>
                                        <li>
                                            <label for="one-location">
                                                <input type="radio" name="location" value="one location" id="one-location" required="required">
                                                one location
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'one location' " , "AND advertisment_status = 1");
                                                ?>)
                                            </label>
                                        </li>
                                        <li>
                                            <label for="multible-locations">
                                                <input type="radio" name="location" value="multible locations" id="multible-locations" required="required">
                                                multible locations
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'multible locations' " , "AND advertisment_status = 1");
                                                ?>)
                                            </label>
                                        </li>
                                        <li>
                                            <label for="remote">
                                                <input type="radio" name="location" value="remote" id="remote" required="required">
                                                remote
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'remote' " , "AND advertisment_status = 1");
                                                ?>)
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-sm-12 search-salary">
                                    <h4> salary </h4>
                                    <input type="text" name="salaryFrom" placeholder="From" required="required">
                                    <input type="text" name="salaryTo" placeholder="To" required="required">
                                </div>
                                <div class="col-sm-12 box-button-submit">
                                    <button name="search-all-jobs" type="submit"> clear filter </button>
                                </div>
                            </form>
                        </div>

                        <div class="col-sm-9 job-advertisments">
                           <div class="col-sm-12 counter-job-advertisments">
                                <span>
                                    <?php 
                                        if( isset($_SESSION['jobSeekerID']) ){

                                            echo "Recommendations for " . count( $recommendationAdvirtisments1 ); 

                                        } else{

                                            echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE advertisment_status = 1");  
                                        }
                                    ?>
                                    jobs
                                </span>
                           </div>

                           
                           <?php
                                if( isset($_SESSION['jobSeekerID']) ){

                                    foreach($recommendationAdvirtisments1 as $recomendation){
                                    
                                        // Fetch employer information
                                        $quaryEmployer = $connect->prepare("SELECT * 
                                                                            FROM employer 
                                                                            WHERE employer_id = ?
                                                                        ");
                                        $quaryEmployer->execute( array($recomendation['employer_id']) );
                                        $rowEmployers= $quaryEmployer->fetchAll();
    
    
                                        echo '<a';
                                            echo ' class="col-sm-12 box-job-advertisment-information" ';
                                            echo ' href="showAdvirtisment.php?advirtismentID= ' . $recomendation['advertisment_id'] . ' " ';
                                        echo '>';
                                        
                                            foreach($rowEmployers as $employer){
                                                if( $employer['logo'] == '' ){
                                            
                                                    $name =  explode(" " , $employer['employer_name']);
                                        
                                                    $lastName = array_pop($name);
                                        
                                                    $firstName = substr($name['0'] , '0' , '1');
                                                    $lastName  = substr($lastName , '0' , '1');
                                        
                                                    echo '<span class="photoText">'. $firstName . $lastName .'</span>';
                                            
                                                } else{
    
                                                    echo '<img src="uploads/Employers/' . $employer['logo'] . '">';
    
                                                }
                                                echo '<p class="employer-location">' . $employer['employer_address'] . '</p>';
                                            }
    
                                            echo '<h4 class="job-title">' . $recomendation['job_title'] . '</h4>';
    
                                            echo '<div class="job-type">' . $recomendation['job_type'] . '</div>';
    
                                            echo '<div class="job-location">' . $recomendation['work_location'] . '</div>';
    
                                            $quaryCategory = $connect->prepare("SELECT category_name 
                                                                                    FROM category 
                                                                                    WHERE category_id = ?
                                                                                ");
                                            $quaryCategory->execute( array($recomendation['category_id']) );
                                            $category= $quaryCategory->fetch(); 
    
                                            echo '<div class="category-name">' . $category['category_name'] . '</div> ';
    
                                            echo '<p class="description">' . $recomendation['job_description'] . '</p>';
    
                                            echo ' <span class="date-of-publication">' . getDatePosted($recomendation['date_of_publication'])  . '</span>';
                                        echo '</a>';
                                    }

                                } else{

                                    foreach($rowAdvertisments as $ad){
                                    
                                        // Fetch employer information
                                        $quaryEmployer = $connect->prepare("SELECT * 
                                                                            FROM employer 
                                                                            WHERE employer_id = ?
                                                                        ");
                                        $quaryEmployer->execute( array($ad['employer_id']) );
                                        $rowEmployers= $quaryEmployer->fetchAll();
    
    
                                        echo '<a';
                                            echo ' class="col-sm-12 box-job-advertisment-information" ';
                                            echo ' href="showAdvirtisment.php?advirtismentID= ' . $ad['advertisment_id'] . ' " ';
                                        echo '>';
                                        
                                            foreach($rowEmployers as $employer){
                                                if( $employer['logo'] == '' ){
                                        
                                                    echo '<span class="photoText"> <i class="fas fa-building"></i> </span>';
                                            
                                                } else{
    
                                                    echo '<img src="uploads/Employers/' . $employer['logo'] . '">';
    
                                                }
                                                echo '<p class="employer-location">' . $employer['employer_address'] . '</p>';
                                            }
    
                                            echo '<h4 class="job-title">' . $ad['job_title'] . '</h4>';
    
                                            echo '<div class="job-type">' . $ad['job_type'] . '</div>';
    
                                            echo '<div class="job-location">' . $ad['work_location'] . '</div>';
    
                                            $quaryCategory = $connect->prepare("SELECT category_name 
                                                                                    FROM category 
                                                                                    WHERE category_id = ?
                                                                                ");
                                            $quaryCategory->execute( array($ad['category_id']) );
                                            $category= $quaryCategory->fetch(); 
    
                                            echo '<div class="category-name">' . $category['category_name'] . '</div> ';
    
                                            echo '<p class="description">' . $ad['job_description'] . '</p>';
    
                                            echo ' <span class="date-of-publication">' . getDatePosted($ad['date_of_publication'])  . '</span>';
                                        echo '</a>';
                                    }
                                }

                           ?>
                        </div>
                    </div>
                </div>
            </div>

<?php 
        } elseif( $do == 'searchCategory' ) {

            $catID = isset($_GET['catID']) && is_numeric($_GET['catID']) ? $_GET['catID'] : 0;

            $catName = isset($_GET['catName']) ? $_GET['catName'] : 'undefined';

            $catName = str_replace("/" , "&" , $catName);

            // Fetch all active job Advertisments informations By Category
            $fetchAllJobAdvertismentsByCategory = $connect->prepare("SELECT * 
                                                           FROM job_advertisment
                                                           WHERE advertisment_status = 1 
                                                                 AND
                                                                 category_id = ?
                                                           ORDER BY date_of_publication DESC 
                                                        ");
            $fetchAllJobAdvertismentsByCategory->execute( array($catID) );
            $rowAdvertisments = $fetchAllJobAdvertismentsByCategory->fetchAll();
            $countAdvertisment = $fetchAllJobAdvertismentsByCategory->rowCount();

            // Fetch all categories
            $fetchAllCategories = $connect->prepare("SELECT * 
                                                     FROM category 
                                                ");
            $fetchAllCategories->execute();
            $rowCategories= $fetchAllCategories->fetchAll();
        
            ?>

            <div class="find-work-section-top">
                <div class="container">
                    <h2> find <?php echo $catName; ?> jobs </h2>
                    <p>
                        Create a profile and apply for new remote job opportunities.
                        Find professionals that best match your job requirements.
                    </p>
                    <form action="findWork.php?do=search-job-title-location" method="post">
                        <div class="form-group-search-what" id="form-group-search-what">
                            <span> what </span>
                            <input type="text" name="job-title" placeholder="Job title or keywords">
                            <i class="fa fa-search"></i>
                        </div>
                        <div class="form-group-search-where" id="form-group-search-where">
                            <span> where </span>
                            <input type="text" name="location" placeholder="Company address , city , state or zip code">
                            <i class="fa fa-map-marker-alt"></i>
                        </div>
                        <div class="error">
                            <i class="fa fa-exclamation-circle"></i>
                            <h5> Enter a job title or location to start a search </h5>
                        </div>
                        <button type="submit" name="find-jobs">find jobs</button>
                    </form>
                </div>
            </div>

            <div class="section-advanced-search-find-work">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-3 advanced-search">
                            <div class="col-sm-12 category-search">
                                <h4> categories </h4>
                                <a href="findWork.php" class="link-all-category">
                                    all categories
                                    (<?php 
                                        echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE advertisment_status = 1"); 
                                    ?>) 
                                </a>

                                <ul class="main-list-category">
                                    <?php
                                        foreach($rowCategories as $category){

                                            $quary = $connect->prepare(" SELECT COUNT(advertisment_id) as count
                                                                         FROM job_advertisment
                                                                         WHERE 
                                                                              category_id = ?
                                                                              AND
                                                                              advertisment_status = 1
                                                                        LIMIT 1
                                            ");
                                            $quary->execute( array( $category['category_id'] ) );
                                            $count = $quary->fetch();

                                            $categoryName = str_replace("&" , "/" , $category['category_name'] );
                                            

                                            echo '<li class="main-li-list-category">';
                                                echo '<a ';
                                                    echo ' 
                                                            href="findWork.php?do=searchCategory&catID='
                                                            . $category['category_id']
                                                            . '&catName=' . $categoryName .

                                                         ' " ';
                                                    if( $category['category_name'] == $catName ){

                                                        echo 'id="activeCategorySearch" ';
                                                    }
                                                    echo '>';

                                                    if( $category['category_id'] == $catID ){

                                                        echo '<i id="activeCategorySearch" class="fa fa-minus"></i>';

                                                    } else{
                                                        echo '<i class="fa fa-plus"></i>';
                                                       
                                                    }
                                                    
                                                    echo 
                                                        $category['category_name'] 
                                                        . ' (' . $count["count"] . ')';

                                                echo '</a>';
                                                
                                                if( $category['category_name'] == $catName ){ 

                                                    $quarySubcategories =  $connect->prepare(" SELECT * FROM focusing_on
                                                                                               WHERE category_id = ?
                                                                                        ");

                                                    $quarySubcategories->execute( array( $category['category_id'] ) );
                                                    $subcategories = $quarySubcategories->fetchAll();
                                                    

                                                    echo '<ul class="subcategory-list">';

                                                    foreach( $subcategories as $subcategory ){

                                                        $quary = $connect->prepare(" SELECT COUNT(advertisment_id) as count
                                                                         FROM job_advertisment
                                                                         WHERE 
                                                                              f_id = ?
                                                                              AND
                                                                              advertisment_status = 1
                                                                        LIMIT 1
                                                                    ");
                                                        $quary->execute( array( $subcategory['f_id'] ) );
                                                        $count = $quary->fetch();

                                                        $subcategoryName = str_replace("&" , "/" , $subcategory['f_name'] );
                                                        echo '<li>';

                                                            echo '<div>';
                                                            
                                                                echo '<a ';
                                                                    echo ' 
                                                                            href="findWork.php?do=searchSubcategory&subCatID='
                                                                            . $subcategory['f_id']
                                                                            . '&subCatName=' . $subcategoryName 
                                                                            . '&CatID=' . $catID 
                                                                            . '&CatName=' .  $categoryName .
                                                                        ' " ';

                                                                    echo '>';
                                                                    echo 
                                                                    '( ' . $count["count"] . ' ) ' . $subcategory['f_name'];
                                                                echo '</a>';
                                                            
                                                            echo '</div>';


                                                        echo '</li>';
                                                    }

                                                    echo '</ul>';
                                                }

                                            echo '</li>';

                                        }
                                    ?>
                                </ul>
                            </div>

                            <form action="findWork.php?do=advancedSearch" method="post">
                                <input type="hidden" value="<?php echo $catID; ?>" name="categoryID">
                                <div class="col-sm-12 search-job-type">
                                    <h4> job type </h4>
                                    <ul>
                                        <li>
                                            <label for="full-time">
                                                <input type="radio" value="full time" required="required" name="job-type" id="full-time">
                                                full time
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'full time' " , "AND advertisment_status = 1 AND category_id = $catID");
                                                ?>)
                                            </label>
                                        </li>
                                        <li>
                                            <label for="part-time">
                                                <input type="radio" value="part time" required="required" name="job-type" id="part-time">
                                                part time
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'part time' " , "AND advertisment_status = 1 AND category_id = $catID");
                                                ?>)
                                            </a>
                                        </li>
                                        <li>
                                            <label for="contract">
                                                <input type="radio" value="contract" required="required" name="job-type" id="contract">
                                                contract
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'contract' " , "AND advertisment_status = 1 AND category_id = $catID");
                                                ?>)
                                            </label>
                                        </li>
                                        <li>
                                            <label for="intership">
                                                <input type="radio" value="intership" required="required" name="job-type" id="intership">
                                                intership
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'intership' " , "AND advertisment_status = 1 AND category_id = $catID");
                                                ?>)
                                            </label>
                                        </li>
                                        <li>
                                            <label for="freelance">
                                                <input type="radio" value="freelance" required="required" name="job-type" id="freelance">
                                                freelance
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'freelance' " , "AND advertisment_status = 1 AND category_id = $catID");
                                                ?>)
                                            </label>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-sm-12 search-location">
                                    <h4> location </h4>
                                    <ul>
                                        <li>
                                            <label for="one-location">
                                                <input type="radio" value="one location" required="required" name="location" id="one-location">
                                                one location
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'one location' " , "AND advertisment_status = 1 AND category_id = $catID");
                                                ?>)
                                            </label>
                                        </li>
                                        <li>
                                            <label for="multible-locations">
                                                <input type="radio" value="multible locations" required="required" name="location" id="multible-locations">
                                                multible locations
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'multible locations' " , "AND advertisment_status = 1 AND category_id = $catID");
                                                ?>)
                                            </label>
                                        </li>
                                        <li>
                                            <label for="remote">
                                                <input type="radio" value="remote" required="required" name="location" id="remote">
                                                remote
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'remote' " , "AND advertisment_status = 1 AND category_id = $catID");
                                                ?>)
                                            </label>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-sm-12 search-salary">
                                    <h4> salary </h4>
                                    <input type="text" name="salaryFrom" placeholder="From" required="required">
                                    <input type="text" name="salaryTo"  placeholder="To" required="required">
                                </div>

                                <div class="col-sm-12 box-button-submit">
                                    <button name="search-category-jobs" type="submit"> clear filter </button>
                                </div>
                            </form>
                        </div>


                        <div class="col-sm-9 job-advertisments">
                           <div class="col-sm-12 counter-job-advertisments">
                                <span>
                                    <?php echo $countAdvertisment; ?>
                                    jobs
                                </span>
                           </div>

                           <?php
                                foreach($rowAdvertisments as $ad){
                                    
                                    // Fetch employer information
                                    $quaryEmployer = $connect->prepare("SELECT * 
                                                                        FROM employer 
                                                                        WHERE employer_id = ?
                                                                    ");
                                    $quaryEmployer->execute( array($ad['employer_id']) );
                                    $rowEmployers= $quaryEmployer->fetchAll();

                                    echo '<a';
                                        echo ' class="col-sm-12 box-job-advertisment-information" ';
                                        echo ' href="showAdvirtisment.php?advirtismentID= ' . $ad['advertisment_id'] . ' " ';
                                    echo '>';
                                    
                                        foreach($rowEmployers as $employer){
                                            if( $employer['logo'] == '' ){
                                    
                                                echo '<span class="photoText"> <i class="fas fa-building"></i> </span>';
                                        
                                            } else{

                                                echo '<img src="uploads/Employers/' . $employer['logo'] . '">';

                                            }
                                            echo '<p class="employer-location">' . $employer['employer_address'] . '</p>';
                                        }

                                        echo '<h4 class="job-title">' . $ad['job_title'] . '</h4>';

                                        echo '<div class="job-type">' . $ad['job_type'] . '</div>';

                                        echo '<div class="job-location">' . $ad['work_location'] . '</div>';

                                        $quaryCategory = $connect->prepare("SELECT category_name 
                                                                                FROM category 
                                                                                WHERE category_id = ?
                                                                            ");
                                        $quaryCategory->execute( array($ad['category_id']) );
                                        $category= $quaryCategory->fetch(); 

                                        echo '<div class="category-name">' . $category['category_name'] . '</div> ';

                                        echo '<p class="description">' . $ad['job_description'] . '</p>';

                                        echo ' <span class="date-of-publication">' . getDatePosted($ad['date_of_publication'])  . '</span>';
                                    echo '</a>';
                                }
                           ?>
                        </div>
                    </div>
                </div>
            </div>

    <?php } elseif($do == 'searchSubcategory'){

                $catID = isset($_GET['CatID']) && is_numeric($_GET['CatID']) ? $_GET['CatID'] : 0;

                $catName = isset($_GET['CatName']) ? $_GET['CatName'] : 'undefined';

                $catName = str_replace("/" , "&" , $catName);

                $subcategoryID = isset($_GET['subCatID']) && is_numeric($_GET['subCatID']) ? $_GET['subCatID'] : 0;

                $subcategoryName = isset($_GET['subCatName']) ? $_GET['subCatName'] : 'undefined';

                $subcategoryName = str_replace("/" , "&" , $subcategoryName);

                // Fetch all active job Advertisments informations By Subcategory
                $fetchAllJobAdvertismentsBySubcategory = $connect->prepare("SELECT * 
                                                                            FROM job_advertisment
                                                                            WHERE advertisment_status = 1 
                                                                               AND
                                                                                 category_id = ?
                                                                               AND 
                                                                                 f_id = ?
                                                                            ORDER BY date_of_publication DESC 
                ");
                $fetchAllJobAdvertismentsBySubcategory->execute( array($catID , $subcategoryID) );
                $rowAdvertisments = $fetchAllJobAdvertismentsBySubcategory->fetchAll();
                $countAdvertisment = $fetchAllJobAdvertismentsBySubcategory->rowCount();

                // Fetch all categories
                $fetchAllCategories = $connect->prepare("SELECT * 
                                                         FROM category 
                ");
                $fetchAllCategories->execute();
                $rowCategories= $fetchAllCategories->fetchAll();
               
                ?>

                <div class="find-work-section-top">
                    <div class="container">
                        <h2> find <?php echo $subcategoryName; ?> jobs </h2>
                        <p>
                            Create a profile and apply for new remote job opportunities.
                            Find professionals that best match your job requirements.
                        </p>
                        <form action="findWork.php?do=search-job-title-location" method="post">
                            <div class="form-group-search-what" id="form-group-search-what">
                                <span> what </span>
                                <input type="text" name="job-title" placeholder="Job title or keywords">
                                <i class="fa fa-search"></i>
                            </div>
                            <div class="form-group-search-where" id="form-group-search-where">
                                <span> where </span>
                                <input type="text" name="location" placeholder="Company address , city , state or zip code">
                                <i class="fa fa-map-marker-alt"></i>
                            </div>
                            <div class="error">
                                <i class="fa fa-exclamation-circle"></i>
                                <h5> Enter a job title or location to start a search </h5>
                            </div>
                            <button type="submit" name="find-jobs">find jobs</button>
                        </form>
                    </div>
                </div>

                <div class="section-advanced-search-find-work">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-3 advanced-search">
                            <div class="col-sm-12 category-search">
                                <h4> categories </h4>
                                <a href="findWork.php" class="link-all-category">
                                    all categories
                                    (<?php 
                                        echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE advertisment_status = 1"); 
                                    ?>) 
                                </a>

                                <ul class="main-list-category">
                                    <?php
                                        foreach($rowCategories as $category){

                                            $quary = $connect->prepare(" SELECT COUNT(advertisment_id) as count
                                                                         FROM job_advertisment
                                                                         WHERE 
                                                                              category_id = ?
                                                                              AND
                                                                              advertisment_status = 1
                                                                        LIMIT 1
                                            ");
                                            $quary->execute( array( $category['category_id'] ) );
                                            $count = $quary->fetch();

                                            $categoryName = str_replace("&" , "/" , $category['category_name'] );
                                            

                                            echo '<li class="main-li-list-category">';
                                                echo '<a ';
                                                    echo ' 
                                                            href="findWork.php?do=searchCategory&catID='
                                                            . $category['category_id']
                                                            . '&catName=' . $categoryName .

                                                         ' " ';
                                                    if( $category['category_name'] == $catName ){

                                                        echo 'id="activeCategorySearch" ';
                                                    }
                                                    echo '>';

                                                    if( $category['category_id'] == $catID ){

                                                        echo '<i id="activeCategorySearch" class="fa fa-minus"></i>';

                                                    } else{
                                                        echo '<i class="fa fa-plus"></i>';
                                                       
                                                    }
                                                    
                                                    echo 
                                                        $category['category_name'] 
                                                        . ' (' . $count["count"] . ')';

                                                echo '</a>';
                                                
                                                if( $category['category_name'] == $catName ){ 

                                                    $quarySubcategories =  $connect->prepare(" SELECT * FROM focusing_on
                                                                                               WHERE category_id = ?
                                                                                        ");

                                                    $quarySubcategories->execute( array( $category['category_id'] ) );
                                                    $subcategories = $quarySubcategories->fetchAll();
                                                    

                                                    echo '<ul class="subcategory-list">';

                                                    foreach( $subcategories as $subcategory ){

                                                        $quary = $connect->prepare(" SELECT COUNT(advertisment_id) as count
                                                                         FROM job_advertisment
                                                                         WHERE 
                                                                              f_id = ?
                                                                              AND
                                                                              advertisment_status = 1
                                                                        LIMIT 1
                                                                    ");
                                                        $quary->execute( array( $subcategory['f_id'] ) );
                                                        $count = $quary->fetch();

                                                        $subcategoryNameNew = str_replace("&" , "/" , $subcategory['f_name'] );
                                                        echo '<li>';

                                                            echo '<div>';
                                                            
                                                                echo '<a ';
                                                                    echo ' 
                                                                            href="findWork.php?do=searchSubcategory&subCatID='
                                                                            . $subcategory['f_id']
                                                                            . '&subCatName=' . $subcategoryNameNew 
                                                                            . '&CatID=' . $catID 
                                                                            . '&CatName=' .  $categoryName .
                                                                        ' " ';

                                                                        if( $subcategory['f_name'] == $subcategoryName ){

                                                                            echo 'id="activeCategorySearch" ';
                                                                        }

                                                                    echo '>';
                                                                    echo 
                                                                    '( ' . $count["count"] . ' ) ' . $subcategory['f_name'];
                                                                echo '</a>';
                                                            
                                                            echo '</div>';


                                                        echo '</li>';
                                                    }

                                                    echo '</ul>';
                                                }

                                            echo '</li>';

                                        }
                                    ?>
                                </ul>
                            </div>

                            <form action="findWork.php?do=advancedSearch" method="post">
                                    
                                <input type="hidden" value="<?php echo $catID ?>" name="categoryID">
                                <input type="hidden" value="<?php echo $subcategoryID; ?>" name="subcategoryID">

                                <div class="col-sm-12 search-job-type">
                                    <h4> job type </h4>
                                    <ul>
                                        <li>
                                            <label for="full-time">
                                                <input type="radio" value="full time" required="required" name="job-type" id="full-time">
                                                full time
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'full time' " , "AND advertisment_status = 1 AND category_id = $catID AND f_id = $subcategoryID");
                                                ?>)
                                            </label>
                                        </li>
                                        <li>
                                            <label for="part-time">
                                                <input type="radio" value="part time" required="required" name="job-type" id="part-time">
                                                part time
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'part time' " , "AND advertisment_status = 1 AND category_id = $catID AND f_id = $subcategoryID");
                                                ?>)
                                            </a>
                                        </li>
                                        <li>
                                            <label for="contract">
                                                <input type="radio" value="contract" required="required" name="job-type" id="contract">
                                                contract
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'contract' " , "AND advertisment_status = 1 AND category_id = $catID AND f_id = $subcategoryID");
                                                ?>)
                                            </label>
                                        </li>
                                        <li>
                                            <label for="intership">
                                                <input type="radio" value="intership" required="required" name="job-type" id="intership">
                                                intership
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'intership' " , "AND advertisment_status = 1 AND category_id = $catID AND f_id = $subcategoryID");
                                                ?>)
                                            </label>
                                        </li>
                                        <li>
                                            <label for="freelance">
                                                <input type="radio" value="freelance" required="required" name="job-type" id="freelance">
                                                freelance
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'freelance' " , "AND advertisment_status = 1 AND category_id = $catID AND f_id = $subcategoryID");
                                                ?>)
                                            </label>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-sm-12 search-location">
                                    <h4> location </h4>
                                    <ul>
                                        <li>
                                            <label for="one-location">
                                                <input type="radio" value="one location" required="required" name="location" id="one-location">
                                                one location
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'one location' " , "AND advertisment_status = 1 AND category_id = $catID AND f_id = $subcategoryID");
                                                ?>)
                                            </label>
                                        </li>
                                        <li>
                                            <label for="multible-locations">
                                                <input type="radio" value="multible locations" required="required" name="location" id="multible-locations">
                                                multible locations
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'multible locations' " , "AND advertisment_status = 1 AND category_id = $catID AND f_id = $subcategoryID");
                                                ?>)
                                            </label>
                                        </li>
                                        <li>
                                            <label for="remote">
                                                <input type="radio" value="remote" required="required" name="location" id="remote">
                                                remote
                                                (<?php
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'remote' " , "AND advertisment_status = 1 AND category_id = $catID AND f_id = $subcategoryID");
                                                ?>)
                                            </label>
                                        </li>
                                    </ul>
                                </div>


                                <div class="col-sm-12 search-salary">
                                    <h4> salary </h4>
                                    <input type="text" name="salaryFrom" placeholder="From" required="required">
                                    <input type="text" name="salaryTo" placeholder="To" required="required">
                                </div>

                                <div class="col-sm-12 box-button-submit">
                                    <button name="search-subcategory-jobs" type="submit"> clear filter </button>
                                </div>
                            </form>
                
                        </div>


                        <div class="col-sm-9 job-advertisments">
                           <div class="col-sm-12 counter-job-advertisments">
                                <span>
                                    <?php echo $countAdvertisment; ?>
                                    jobs
                                </span>
                           </div>

                           <?php
                                foreach($rowAdvertisments as $ad){
                                    
                                    // Fetch employer information
                                    $quaryEmployer = $connect->prepare("SELECT * 
                                                                        FROM employer 
                                                                        WHERE employer_id = ?
                                                                    ");
                                    $quaryEmployer->execute( array($ad['employer_id']) );
                                    $rowEmployers= $quaryEmployer->fetchAll();

                                    echo '<a';
                                        echo ' class="col-sm-12 box-job-advertisment-information" ';
                                        echo ' href="showAdvirtisment.php?advirtismentID= ' . $ad['advertisment_id'] . ' " ';
                                    echo '>';
                                    
                                        foreach($rowEmployers as $employer){
                                            if( $employer['logo'] == '' ){
                                    
                                                echo '<span class="photoText"> <i class="fas fa-building"></i> </span>';
                                        
                                            } else{

                                                echo '<img src="uploads/Employers/' . $employer['logo'] . '">';

                                            }
                                            echo '<p class="employer-location">' . $employer['employer_address'] . '</p>';
                                        }

                                        echo '<h4 class="job-title">' . $ad['job_title'] . '</h4>';

                                        echo '<div class="job-type">' . $ad['job_type'] . '</div>';

                                        echo '<div class="job-location">' . $ad['work_location'] . '</div>';

                                        $quaryCategory = $connect->prepare("SELECT category_name 
                                                                                FROM category 
                                                                                WHERE category_id = ?
                                                                            ");
                                        $quaryCategory->execute( array($ad['category_id']) );
                                        $category= $quaryCategory->fetch(); 

                                        echo '<div class="category-name">' . $category['category_name'] . '</div> ';

                                        echo '<p class="description">' . $ad['job_description'] . '</p>';

                                        echo ' <span class="date-of-publication">' . getDatePosted($ad['date_of_publication'])  . '</span>';
                                    echo '</a>';
                                }
                           ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php } elseif( $do == 'advancedSearch' ){

                    // Fetch all categories
                    $fetchAllCategories = $connect->prepare("SELECT * 
                                                             FROM category 
                    ");
                    $fetchAllCategories->execute();
                    $rowCategories= $fetchAllCategories->fetchAll();

                    if($_SERVER['REQUEST_METHOD'] == 'POST'){

                        if( isset($_POST['search-all-jobs']) ){

                           $jobType    = $_POST['job-type'];
                           $location   = $_POST['location'];
                           $salaryFrom = filter_var($_POST['salaryFrom'] , FILTER_SANITIZE_NUMBER_INT);
                           $salaryTo   = filter_var($_POST['salaryTo']   , FILTER_SANITIZE_NUMBER_INT);

                            $quarySearch_MIN_Max = $connect->prepare("SELECT * 
                                                             FROM job_advertisment 
                                                             WHERE 
                                                                salary_static IN(' ')
                                                                AND	
                                                                job_type LIKE :job_type_value 
                                                                AND
                                                                work_location LIKE :work_location_value
                                                                AND
                                                                salary_min BETWEEN $salaryFrom AND $salaryTo
                                                                AND
                                                                salary_max BETWEEN $salaryFrom AND $salaryTo 
                                                             ORDER BY date_of_publication DESC
                            ");

                            $searchJobTypeValue      = "%" . $jobType  . "%";
                            $searchWorkLocationValue = "%" . $location . "%";

                            $quarySearch_MIN_Max->bindParam("job_type_value" , $searchJobTypeValue);
                            $quarySearch_MIN_Max->bindParam("work_location_value" , $searchWorkLocationValue);
                          
                            $quarySearch_MIN_Max->execute();
                            $rowsSearch_MIN_Max = $quarySearch_MIN_Max->fetchAll(); 

                           
                            /*-----------------------------------*/

                            $quarySearch_Static = $connect->prepare("SELECT * 
                                                             FROM job_advertisment 
                                                             WHERE 
                                                                salary_min IN(' ')
                                                                AND	
                                                                job_type LIKE :job_type_value 
                                                                AND
                                                                work_location LIKE :work_location_value
                                                                AND
                                                                salary_static BETWEEN $salaryFrom AND $salaryTo  
                                                             ORDER BY date_of_publication DESC 
                            ");

                            $searchJobTypeValue      = "%" . $jobType  . "%";
                            $searchWorkLocationValue = "%" . $location . "%";

                            $quarySearch_Static->bindParam("job_type_value" , $searchJobTypeValue);
                            $quarySearch_Static->bindParam("work_location_value" , $searchWorkLocationValue);
                          
                            $quarySearch_Static->execute();
                            $rowsSearch_Static = $quarySearch_Static->fetchAll();

                            foreach( $rowsSearch_Static as $row ){

                                    array_push($rowsSearch_MIN_Max , $row);
                            }

                            ?>

                            <div class="find-work-section-top">
                                <div class="container">
                                    <h2> find remote jobs </h2>
                                    <p>
                                        Create a profile and apply for new remote job opportunities.
                                        Find professionals that best match your job requirements.
                                    </p>
                                    <form action="findWork.php?do=search-job-title-location" method="post">
                                        <div class="form-group-search-what" id="form-group-search-what">
                                            <span> what </span>
                                            <input type="text" name="job-title" placeholder="Job title or keywords">
                                            <i class="fa fa-search"></i>
                                        </div>
                                        <div class="form-group-search-where" id="form-group-search-where">
                                            <span> where </span>
                                            <input type="text" name="location" placeholder="Company address , city , state or zip code">
                                            <i class="fa fa-map-marker-alt"></i>
                                        </div>
                                        <div class="error">
                                            <i class="fa fa-exclamation-circle"></i>
                                            <h5> Enter a job title or location to start a search </h5>
                                        </div>
                                        <button type="submit" name="find-jobs">find jobs</button>
                                    </form>
                                </div>
                            </div>

                            <div class="section-advanced-search-find-work">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-3 advanced-search">
                                            <div class="col-sm-12 category-search">
                                                <h4> categories </h4>
                                                <a href="findWork.php"  class="link-all-category">
                                                    all categories
                                                    (<?php 
                                                        echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE advertisment_status = 1"); 
                                                    ?>) 
                                                </a>
                                                <ul class="main-list-category">
                                                    <?php
                                                        foreach($rowCategories as $category){

                                                            $quary = $connect->prepare(" SELECT COUNT(advertisment_id) as count
                                                                                        FROM job_advertisment
                                                                                        WHERE 
                                                                                            category_id = ?
                                                                                            AND
                                                                                            advertisment_status = 1
                                                                                        LIMIT 1
                                                            ");
                                                            $quary->execute( array( $category['category_id'] ) );
                                                            $count = $quary->fetch();

                                                            $categoryName = str_replace("&" , "/" , $category['category_name'] );
                                                            
                                                            echo '<li class="main-li-list-category">';
                                                                echo '<a href="findWork.php?do=searchCategory&catID='
                                                                        . $category['category_id']
                                                                        . '&catName=' . $categoryName .
                                                                    ' "> ';
                                                                    echo '<i class="fa fa-plus"></i>';
                                                                    echo 
                                                                        $category['category_name'] 
                                                                        . ' (' . $count["count"] . ')';
                                                                echo '</a>';
                                                            echo '</li>';

                                                        }
                                                    ?>
                                                    
                                                </ul>
                                            </div>
                                            <form action="findWork.php?do=advancedSearch" method="post">
                                                <div class="col-sm-12 search-job-type">
                                                    <h4> job type </h4>
                                                    <ul>
                                                        <li>
                                                            <label for="full-time">
                                                                <input 
                                                                    type="radio" 
                                                                    name="job-type" 
                                                                    value="full time" 
                                                                    id="full-time" 
                                                                    required="required"
                                                                    <?php 
                                                                        if( $jobType == 'full time' ){

                                                                            echo 'checked';
                                                                        }
                                                                    ?>
                                                                >
                                                                full time
                                                                (<?php
                                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'full time' " , "AND advertisment_status = 1");
                                                                ?>)
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label for="part-time">
                                                                <input 
                                                                    type="radio" 
                                                                    name="job-type" 
                                                                    value="part time" 
                                                                    id="part-time" 
                                                                    required="required"
                                                                    <?php 
                                                                        if( $jobType == 'part time' ){

                                                                            echo 'checked';
                                                                        }
                                                                    ?>
                                                                >
                                                                part time
                                                                (<?php
                                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'part time' " , "AND advertisment_status = 1");
                                                                ?>)
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <label for="contract">
                                                                <input 
                                                                    type="radio" 
                                                                    name="job-type" 
                                                                    value="contract" 
                                                                    id="contract" 
                                                                    required="required"
                                                                    <?php 
                                                                        if( $jobType == 'contract' ){

                                                                            echo 'checked';
                                                                        }
                                                                    ?>
                                                                >
                                                                contract
                                                                (<?php
                                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'contract' " , "AND advertisment_status = 1");
                                                                ?>)
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label for="intership">
                                                                <input 
                                                                    type="radio" 
                                                                    name="job-type" 
                                                                    value="intership" 
                                                                    id="intership" 
                                                                    required="required"
                                                                    <?php 
                                                                        if( $jobType == 'intership' ){

                                                                            echo 'checked';
                                                                        }
                                                                    ?>
                                                                >
                                                                intership
                                                                (<?php
                                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'intership' " , "AND advertisment_status = 1");
                                                                ?>)
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label for="freelance">
                                                                <input 
                                                                    type="radio" 
                                                                    name="job-type" 
                                                                    value="freelance" 
                                                                    id="freelance" 
                                                                    required="required"
                                                                    <?php 
                                                                        if( $jobType == 'freelance' ){

                                                                            echo 'checked';
                                                                        }
                                                                    ?>
                                                                >
                                                                freelance
                                                                (<?php
                                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'freelance' " , "AND advertisment_status = 1");
                                                                ?>)
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-sm-12 search-location">
                                                    <h4> location </h4>
                                                    <ul>
                                                        <li>
                                                            <label for="one-location">
                                                                <input 
                                                                    type="radio" 
                                                                    name="location" 
                                                                    value="one location" 
                                                                    id="one-location" 
                                                                    required="required"
                                                                    <?php 
                                                                        if( $location == 'one location' ){

                                                                            echo 'checked';
                                                                        }
                                                                    ?>
                                                                >
                                                                one location
                                                                (<?php
                                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'one location' " , "AND advertisment_status = 1");
                                                                ?>)
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label for="multible-locations">
                                                                <input 
                                                                    type="radio" 
                                                                    name="location" 
                                                                    value="multible locations" 
                                                                    id="multible-locations" 
                                                                    required="required"
                                                                    <?php 
                                                                        if( $location == 'multible locations' ){

                                                                            echo 'checked';
                                                                        }
                                                                    ?>
                                                                >
                                                                multible locations
                                                                (<?php
                                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'multible locations' " , "AND advertisment_status = 1");
                                                                ?>)
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label for="remote">
                                                                <input 
                                                                    type="radio" 
                                                                    name="location" 
                                                                    value="remote" 
                                                                    id="remote" 
                                                                    required="required"
                                                                    <?php 
                                                                        if( $location == 'remote' ){

                                                                            echo 'checked';
                                                                        }
                                                                    ?>
                                                                >
                                                                remote
                                                                (<?php
                                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'remote' " , "AND advertisment_status = 1");
                                                                ?>)
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-sm-12 search-salary">
                                                    <h4> salary </h4>
                                                    <input 
                                                        type="text" 
                                                        name="salaryFrom" 
                                                        placeholder="From" 
                                                        required="required"
                                                        value="<?php echo $salaryFrom; ?>"
                                                    >
                                                    <input 
                                                        type="text" 
                                                        name="salaryTo" 
                                                        placeholder="To" 
                                                        required="required"
                                                        value="<?php echo $salaryTo; ?>"
                                                    >
                                                </div>
                                                <div class="col-sm-12 box-button-submit">
                                                    <button name="search-all-jobs" type="submit"> clear filter </button>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="col-sm-9 job-advertisments">
                                        <div class="col-sm-12 counter-job-advertisments">
                                                <span>
                                                    <?php echo count($rowsSearch_MIN_Max); ?>
                                                    jobs
                                                </span>
                                        </div>

                                        <?php
                                                foreach($rowsSearch_MIN_Max as $ad){
                                                    
                                                    // Fetch employer information
                                                    $quaryEmployer = $connect->prepare("SELECT * 
                                                                                        FROM employer 
                                                                                        WHERE employer_id = ?
                                                                                    ");
                                                    $quaryEmployer->execute( array($ad['employer_id']) );
                                                    $rowEmployers= $quaryEmployer->fetchAll();

                                                    echo '<a';
                                                        echo ' class="col-sm-12 box-job-advertisment-information" ';
                                                        echo ' href="showAdvirtisment.php?advirtismentID= ' . $ad['advertisment_id'] . ' " ';
                                                    echo '>';
                                                    
                                                        foreach($rowEmployers as $employer){
                                                            if( $employer['logo'] == '' ){
                                                    
                                                                echo '<span class="photoText"> <i class="fas fa-building"></i> </span>';
                                                        
                                                            } else{

                                                                echo '<img src="uploads/Employers/' . $employer['logo'] . '">';

                                                            }
                                                            echo '<p class="employer-location">' . $employer['employer_address'] . '</p>';
                                                        }

                                                        echo '<h4 class="job-title">' . $ad['job_title'] . '</h4>';

                                                        echo '<div class="job-type">' . $ad['job_type'] . '</div>';

                                                        echo '<div class="job-location">' . $ad['work_location'] . '</div>';

                                                        $quaryCategory = $connect->prepare("SELECT category_name 
                                                                                                FROM category 
                                                                                                WHERE category_id = ?
                                                                                            ");
                                                        $quaryCategory->execute( array($ad['category_id']) );
                                                        $category= $quaryCategory->fetch(); 

                                                        echo '<div class="category-name">' . $category['category_name'] . '</div> ';

                                                        echo '<p class="description">' . $ad['job_description'] . '</p>';

                                                        echo ' <span class="date-of-publication">' . getDatePosted($ad['date_of_publication'])  . '</span>';
                                                    echo '</a>';
                                                }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                  <?php } elseif( isset($_POST['search-category-jobs']) ){

                            $catID      = $_POST['categoryID'];
                            $jobType    = $_POST['job-type'];
                            $location   = $_POST['location'];
                            $salaryFrom = filter_var($_POST['salaryFrom'] , FILTER_SANITIZE_NUMBER_INT);
                            $salaryTo   = filter_var($_POST['salaryTo']   , FILTER_SANITIZE_NUMBER_INT);

                            $quarySearch_MIN_Max = $connect->prepare("SELECT * 
                                                            FROM job_advertisment 
                                                            WHERE 
                                                                category_id = :categoryID
                                                                AND
                                                                salary_static IN(' ')
                                                                AND	
                                                                job_type LIKE :job_type_value 
                                                                AND
                                                                work_location LIKE :work_location_value
                                                                AND
                                                                salary_min BETWEEN $salaryFrom AND $salaryTo
                                                                AND
                                                                salary_max BETWEEN $salaryFrom AND $salaryTo 
                                                            ORDER BY date_of_publication DESC
                            ");

                            $searchJobTypeValue      = "%" . $jobType  . "%";
                            $searchWorkLocationValue = "%" . $location . "%";

                            $quarySearch_MIN_Max->bindParam("job_type_value" , $searchJobTypeValue);
                            $quarySearch_MIN_Max->bindParam("work_location_value" , $searchWorkLocationValue);
                            $quarySearch_MIN_Max->bindParam("categoryID" , $catID);

                            $quarySearch_MIN_Max->execute();
                            $rowsSearch_MIN_Max = $quarySearch_MIN_Max->fetchAll(); 

                            /*-----------------------------------*/

                            $quarySearch_Static = $connect->prepare("SELECT * 
                                                            FROM job_advertisment 
                                                            WHERE 
                                                                category_id = :categoryID
                                                                AND
                                                                salary_min IN(' ')
                                                                AND	
                                                                job_type LIKE :job_type_value 
                                                                AND
                                                                work_location LIKE :work_location_value
                                                                AND
                                                                salary_static BETWEEN $salaryFrom AND $salaryTo  
                                                            ORDER BY date_of_publication DESC 
                            ");

                            $searchJobTypeValue      = "%" . $jobType  . "%";
                            $searchWorkLocationValue = "%" . $location . "%";

                            $quarySearch_Static->bindParam("job_type_value" , $searchJobTypeValue);
                            $quarySearch_Static->bindParam("work_location_value" , $searchWorkLocationValue);
                            $quarySearch_Static->bindParam("categoryID" , $catID);

                            $quarySearch_Static->execute();
                            $rowsSearch_Static = $quarySearch_Static->fetchAll();

                            foreach( $rowsSearch_Static as $row ){

                                    array_push($rowsSearch_MIN_Max , $row);
                            }

                            ?>

                            <div class="find-work-section-top">
                                <div class="container">
                                    <h2> find remote jobs </h2>
                                    <p>
                                        Create a profile and apply for new remote job opportunities.
                                        Find professionals that best match your job requirements.
                                    </p>
                                    <form action="findWork.php?do=search-job-title-location" method="post">
                                        <div class="form-group-search-what" id="form-group-search-what">
                                            <span> what </span>
                                            <input type="text" name="job-title" placeholder="Job title or keywords">
                                            <i class="fa fa-search"></i>
                                        </div>
                                        <div class="form-group-search-where" id="form-group-search-where">
                                            <span> where </span>
                                            <input type="text" name="location" placeholder="Company address , city , state or zip code">
                                            <i class="fa fa-map-marker-alt"></i>
                                        </div>
                                        <div class="error">
                                            <i class="fa fa-exclamation-circle"></i>
                                            <h5> Enter a job title or location to start a search </h5>
                                        </div>
                                        <button type="submit" name="find-jobs">find jobs</button>
                                    </form>
                                </div>
                            </div>

                            <div class="section-advanced-search-find-work">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-3 advanced-search">
                                            <div class="col-sm-12 category-search">
                                                <h4> categories </h4>
                                                <a href="findWork.php" class="link-all-category">
                                                    all categories
                                                    (<?php 
                                                        echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE advertisment_status = 1"); 
                                                    ?>) 
                                                </a>
                                                <ul class="main-list-category">
                                                    <?php
                                                        foreach($rowCategories as $category){

                                                            $quary = $connect->prepare(" SELECT COUNT(advertisment_id) as count
                                                                                        FROM job_advertisment
                                                                                        WHERE 
                                                                                            category_id = ?
                                                                                            AND
                                                                                            advertisment_status = 1
                                                                                        LIMIT 1
                                                            ");
                                                            $quary->execute( array( $category['category_id'] ) );
                                                            $count = $quary->fetch();

                                                            $categoryName = str_replace("&" , "/" , $category['category_name'] );
                                                            
                                                            echo '<li class="main-li-list-category">';
                                                                echo '<a href="findWork.php?do=searchCategory&catID='
                                                                        . $category['category_id']
                                                                        . '&catName=' . $categoryName .
                                                                    ' "> ';
                                                                    echo '<i class="fa fa-plus"></i>';
                                                                    echo 
                                                                        $category['category_name'] 
                                                                        . ' (' . $count["count"] . ')';
                                                                echo '</a>';
                                                            echo '</li>';

                                                        }
                                                    ?>
                                                    
                                                </ul>
                                            </div>
                                            <form action="findWork.php?do=advancedSearch" method="post">
                                                <div class="col-sm-12 search-job-type">
                                                    <h4> job type </h4>
                                                    <ul>
                                                        <li>
                                                            <label for="full-time">
                                                                <input 
                                                                    type="radio" 
                                                                    name="job-type" 
                                                                    value="full time" 
                                                                    id="full-time" 
                                                                    required="required"
                                                                    <?php 
                                                                        if( $jobType == 'full time' ){

                                                                            echo 'checked';
                                                                        }
                                                                    ?>
                                                                >
                                                                full time
                                                                (<?php
                                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'full time' " , "AND advertisment_status = 1 AND category_id = $catID");
                                                                ?>)
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label for="part-time">
                                                                <input 
                                                                    type="radio" 
                                                                    name="job-type" 
                                                                    value="part time" 
                                                                    id="part-time" 
                                                                    required="required"
                                                                    <?php 
                                                                        if( $jobType == 'part time' ){

                                                                            echo 'checked';
                                                                        }
                                                                    ?>
                                                                >
                                                                part time
                                                                (<?php
                                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'part time' " , "AND advertisment_status = 1 AND category_id = $catID");
                                                                ?>)
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <label for="contract">
                                                                <input 
                                                                    type="radio" 
                                                                    name="job-type" 
                                                                    value="contract" 
                                                                    id="contract" 
                                                                    required="required"
                                                                    <?php 
                                                                        if( $jobType == 'contract' ){

                                                                            echo 'checked';
                                                                        }
                                                                    ?>
                                                                >
                                                                contract
                                                                (<?php
                                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'contract' " , "AND advertisment_status = 1 AND category_id = $catID");
                                                                ?>)
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label for="intership">
                                                                <input 
                                                                    type="radio" 
                                                                    name="job-type" 
                                                                    value="intership" 
                                                                    id="intership" 
                                                                    required="required"
                                                                    <?php 
                                                                        if( $jobType == 'intership' ){

                                                                            echo 'checked';
                                                                        }
                                                                    ?>
                                                                >
                                                                intership
                                                                (<?php
                                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'intership' " , "AND advertisment_status = 1 AND category_id = $catID");
                                                                ?>)
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label for="freelance">
                                                                <input 
                                                                    type="radio" 
                                                                    name="job-type" 
                                                                    value="freelance" 
                                                                    id="freelance" 
                                                                    required="required"
                                                                    <?php 
                                                                        if( $jobType == 'freelance' ){

                                                                            echo 'checked';
                                                                        }
                                                                    ?>
                                                                >
                                                                freelance
                                                                (<?php
                                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'freelance' " , "AND advertisment_status = 1 AND category_id = $catID");
                                                                ?>)
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-sm-12 search-location">
                                                    <h4> location </h4>
                                                    <ul>
                                                        <li>
                                                            <label for="one-location">
                                                                <input 
                                                                    type="radio" 
                                                                    name="location" 
                                                                    value="one location" 
                                                                    id="one-location" 
                                                                    required="required"
                                                                    <?php 
                                                                        if( $location == 'one location' ){

                                                                            echo 'checked';
                                                                        }
                                                                    ?>
                                                                >
                                                                one location
                                                                (<?php
                                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'one location' " , "AND advertisment_status = 1 AND category_id = $catID");
                                                                ?>)
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label for="multible-locations">
                                                                <input 
                                                                    type="radio" 
                                                                    name="location" 
                                                                    value="multible locations" 
                                                                    id="multible-locations" 
                                                                    required="required"
                                                                    <?php 
                                                                        if( $location == 'multible locations' ){

                                                                            echo 'checked';
                                                                        }
                                                                    ?>
                                                                >
                                                                multible locations
                                                                (<?php
                                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'multible locations' " , "AND advertisment_status = 1 AND category_id = $catID");
                                                                ?>)
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label for="remote">
                                                                <input 
                                                                    type="radio" 
                                                                    name="location" 
                                                                    value="remote" 
                                                                    id="remote" 
                                                                    required="required"
                                                                    <?php 
                                                                        if( $location == 'remote' ){

                                                                            echo 'checked';
                                                                        }
                                                                    ?>
                                                                >
                                                                remote
                                                                (<?php
                                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'remote' " , "AND advertisment_status = 1 AND category_id = $catID");
                                                                ?>)
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-sm-12 search-salary">
                                                    <h4> salary </h4>
                                                    <input 
                                                        type="text" 
                                                        name="salaryFrom" 
                                                        placeholder="From" 
                                                        required="required"
                                                        value="<?php echo $salaryFrom; ?>"
                                                    >
                                                    <input 
                                                        type="text" 
                                                        name="salaryTo" 
                                                        placeholder="To" 
                                                        required="required"
                                                        value="<?php echo $salaryTo; ?>"
                                                    >
                                                </div>
                                                <div class="col-sm-12 box-button-submit">
                                                    <button name="search-all-jobs" type="submit"> clear filter </button>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="col-sm-9 job-advertisments">
                                        <div class="col-sm-12 counter-job-advertisments">
                                                <span>
                                                    <?php echo count($rowsSearch_MIN_Max); ?>
                                                    jobs
                                                </span>
                                        </div>

                                        <?php
                                                foreach($rowsSearch_MIN_Max as $ad){
                                                    
                                                    // Fetch employer information
                                                    $quaryEmployer = $connect->prepare("SELECT * 
                                                                                        FROM employer 
                                                                                        WHERE employer_id = ?
                                                                                    ");
                                                    $quaryEmployer->execute( array($ad['employer_id']) );
                                                    $rowEmployers= $quaryEmployer->fetchAll();

                                                    echo '<a';
                                                        echo ' class="col-sm-12 box-job-advertisment-information" ';
                                                        echo ' href="showAdvirtisment.php?advirtismentID= ' . $ad['advertisment_id'] . ' " ';
                                                    echo '>';
                                                    
                                                        foreach($rowEmployers as $employer){
                                                            if( $employer['logo'] == '' ){
                                                    
                                                                echo '<span class="photoText"> <i class="fas fa-building"></i> </span>';
                                                        
                                                            } else{

                                                                echo '<img src="uploads/Employers/' . $employer['logo'] . '">';

                                                            }
                                                            echo '<p class="employer-location">' . $employer['employer_address'] . '</p>';
                                                        }

                                                        echo '<h4 class="job-title">' . $ad['job_title'] . '</h4>';

                                                        echo '<div class="job-type">' . $ad['job_type'] . '</div>';

                                                        echo '<div class="job-location">' . $ad['work_location'] . '</div>';

                                                        $quaryCategory = $connect->prepare("SELECT category_name 
                                                                                                FROM category 
                                                                                                WHERE category_id = ?
                                                                                            ");
                                                        $quaryCategory->execute( array($ad['category_id']) );
                                                        $category= $quaryCategory->fetch(); 

                                                        echo '<div class="category-name">' . $category['category_name'] . '</div> ';

                                                        echo '<p class="description">' . $ad['job_description'] . '</p>';

                                                        echo ' <span class="date-of-publication">' . getDatePosted($ad['date_of_publication'])  . '</span>';
                                                    echo '</a>';
                                                }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                  <?php } elseif( isset( $_POST['search-subcategory-jobs'] ) ){

                            $catID      = $_POST['categoryID'];
                            $subCatID   = $_POST['subcategoryID'];
                            $jobType    = $_POST['job-type'];
                            $location   = $_POST['location'];
                            $salaryFrom = filter_var($_POST['salaryFrom'] , FILTER_SANITIZE_NUMBER_INT);
                            $salaryTo   = filter_var($_POST['salaryTo']   , FILTER_SANITIZE_NUMBER_INT);

                            $quarySearch_MIN_Max = $connect->prepare("SELECT * 
                                                            FROM job_advertisment 
                                                            WHERE 
                                                                category_id = :categoryID
                                                                AND
                                                                f_id = :subcategoryID
                                                                AND
                                                                salary_static IN(' ')
                                                                AND	
                                                                job_type LIKE :job_type_value 
                                                                AND
                                                                work_location LIKE :work_location_value
                                                                AND
                                                                salary_min BETWEEN $salaryFrom AND $salaryTo
                                                                AND
                                                                salary_max BETWEEN $salaryFrom AND $salaryTo 
                                                            ORDER BY date_of_publication DESC
                            ");

                            $searchJobTypeValue      = "%" . $jobType  . "%";
                            $searchWorkLocationValue = "%" . $location . "%";

                            $quarySearch_MIN_Max->bindParam("job_type_value" , $searchJobTypeValue);
                            $quarySearch_MIN_Max->bindParam("work_location_value" , $searchWorkLocationValue);
                            $quarySearch_MIN_Max->bindParam("categoryID" , $catID);
                            $quarySearch_MIN_Max->bindParam("subcategoryID" , $subCatID);

                            $quarySearch_MIN_Max->execute();
                            $rowsSearch_MIN_Max = $quarySearch_MIN_Max->fetchAll(); 

                            /*-----------------------------------*/

                            $quarySearch_Static = $connect->prepare("SELECT * 
                                                            FROM job_advertisment 
                                                            WHERE 
                                                                category_id = :categoryID
                                                                AND
                                                                f_id = :subcategoryID
                                                                AND
                                                                salary_min IN(' ')
                                                                AND	
                                                                job_type LIKE :job_type_value 
                                                                AND
                                                                work_location LIKE :work_location_value
                                                                AND
                                                                salary_static BETWEEN $salaryFrom AND $salaryTo  
                                                            ORDER BY date_of_publication DESC 
                            ");

                            $searchJobTypeValue      = "%" . $jobType  . "%";
                            $searchWorkLocationValue = "%" . $location . "%";

                            $quarySearch_Static->bindParam("job_type_value" , $searchJobTypeValue);
                            $quarySearch_Static->bindParam("work_location_value" , $searchWorkLocationValue);
                            $quarySearch_Static->bindParam("categoryID" , $catID);
                            $quarySearch_Static->bindParam("subcategoryID" , $subCatID);

                            $quarySearch_Static->execute();
                            $rowsSearch_Static = $quarySearch_Static->fetchAll();

                            foreach( $rowsSearch_Static as $row ){

                                    array_push($rowsSearch_MIN_Max , $row);
                            }

                    ?>

                    <div class="find-work-section-top">
                        <div class="container">
                            <h2> find remote jobs </h2>
                            <p>
                                Create a profile and apply for new remote job opportunities.
                                Find professionals that best match your job requirements.
                            </p>
                            <form action="findWork.php?do=search-job-title-location" method="post">
                                <div class="form-group-search-what" id="form-group-search-what">
                                    <span> what </span>
                                    <input type="text" name="job-title" placeholder="Job title or keywords">
                                    <i class="fa fa-search"></i>
                                </div>
                                <div class="form-group-search-where" id="form-group-search-where">
                                    <span> where </span>
                                    <input type="text" name="location" placeholder="Company address , city , state or zip code">
                                    <i class="fa fa-map-marker-alt"></i>
                                </div>
                                <div class="error">
                                    <i class="fa fa-exclamation-circle"></i>
                                    <h5> Enter a job title or location to start a search </h5>
                                </div>
                                <button type="submit" name="find-jobs">find jobs</button>
                            </form>
                        </div>
                    </div>

                    <div class="section-advanced-search-find-work">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-3 advanced-search">
                                    <div class="col-sm-12 category-search">
                                        <h4> categories </h4>
                                        <a href="findWork.php" class="link-all-category">
                                            all categories
                                            (<?php 
                                                echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE advertisment_status = 1"); 
                                            ?>) 
                                        </a>
                                        <ul class="main-list-category">
                                            <?php
                                                foreach($rowCategories as $category){

                                                    $quary = $connect->prepare(" SELECT COUNT(advertisment_id) as count
                                                                                FROM job_advertisment
                                                                                WHERE 
                                                                                    category_id = ?
                                                                                    AND
                                                                                    advertisment_status = 1
                                                                                LIMIT 1
                                                    ");
                                                    $quary->execute( array( $category['category_id'] ) );
                                                    $count = $quary->fetch();

                                                    $categoryName = str_replace("&" , "/" , $category['category_name'] );
                                                    
                                                    echo '<li class="main-li-list-category">';
                                                        echo '<a href="findWork.php?do=searchCategory&catID='
                                                                . $category['category_id']
                                                                . '&catName=' . $categoryName .
                                                            ' "> ';
                                                            echo '<i class="fa fa-plus"></i>';
                                                            echo 
                                                                $category['category_name'] 
                                                                . ' (' . $count["count"] . ')';
                                                        echo '</a>';
                                                    echo '</li>';

                                                }
                                            ?>
                                            
                                        </ul>
                                    </div>
                                    <form action="findWork.php?do=advancedSearch" method="post">
                                        <div class="col-sm-12 search-job-type">
                                            <h4> job type </h4>
                                            <ul>
                                                <li>
                                                    <label for="full-time">
                                                        <input 
                                                            type="radio" 
                                                            name="job-type" 
                                                            value="full time" 
                                                            id="full-time" 
                                                            required="required"
                                                            <?php 
                                                                if( $jobType == 'full time' ){

                                                                    echo 'checked';
                                                                }
                                                            ?>
                                                        >
                                                        full time
                                                        (<?php
                                                            echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'full time' " , "AND advertisment_status = 1 AND category_id = $catID AND f_id = $subCatID");
                                                        ?>)
                                                    </label>
                                                </li>
                                                <li>
                                                    <label for="part-time">
                                                        <input 
                                                            type="radio" 
                                                            name="job-type" 
                                                            value="part time" 
                                                            id="part-time" 
                                                            required="required"
                                                            <?php 
                                                                if( $jobType == 'part time' ){

                                                                    echo 'checked';
                                                                }
                                                            ?>
                                                        >
                                                        part time
                                                        (<?php
                                                            echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'part time' " , "AND advertisment_status = 1 AND category_id = $catID AND f_id = $subCatID");
                                                        ?>)
                                                    </a>
                                                </li>
                                                <li>
                                                    <label for="contract">
                                                        <input 
                                                            type="radio" 
                                                            name="job-type" 
                                                            value="contract" 
                                                            id="contract" 
                                                            required="required"
                                                            <?php 
                                                                if( $jobType == 'contract' ){

                                                                    echo 'checked';
                                                                }
                                                            ?>
                                                        >
                                                        contract
                                                        (<?php
                                                            echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'contract' " , "AND advertisment_status = 1 AND category_id = $catID AND f_id = $subCatID");
                                                        ?>)
                                                    </label>
                                                </li>
                                                <li>
                                                    <label for="intership">
                                                        <input 
                                                            type="radio" 
                                                            name="job-type" 
                                                            value="intership" 
                                                            id="intership" 
                                                            required="required"
                                                            <?php 
                                                                if( $jobType == 'intership' ){

                                                                    echo 'checked';
                                                                }
                                                            ?>
                                                        >
                                                        intership
                                                        (<?php
                                                            echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'intership' " , "AND advertisment_status = 1 AND category_id = $catID AND f_id = $subCatID");
                                                        ?>)
                                                    </label>
                                                </li>
                                                <li>
                                                    <label for="freelance">
                                                        <input 
                                                            type="radio" 
                                                            name="job-type" 
                                                            value="freelance" 
                                                            id="freelance" 
                                                            required="required"
                                                            <?php 
                                                                if( $jobType == 'freelance' ){

                                                                    echo 'checked';
                                                                }
                                                            ?>
                                                        >
                                                        freelance
                                                        (<?php
                                                            echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'freelance' " , "AND advertisment_status = 1 AND category_id = $catID AND f_id = $subCatID");
                                                        ?>)
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-12 search-location">
                                            <h4> location </h4>
                                            <ul>
                                                <li>
                                                    <label for="one-location">
                                                        <input 
                                                            type="radio" 
                                                            name="location" 
                                                            value="one location" 
                                                            id="one-location" 
                                                            required="required"
                                                            <?php 
                                                                if( $location == 'one location' ){

                                                                    echo 'checked';
                                                                }
                                                            ?>
                                                        >
                                                        one location
                                                        (<?php
                                                            echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'one location' " , "AND advertisment_status = 1 AND category_id = $catID AND f_id = $subCatID");
                                                        ?>)
                                                    </label>
                                                </li>
                                                <li>
                                                    <label for="multible-locations">
                                                        <input 
                                                            type="radio" 
                                                            name="location" 
                                                            value="multible locations" 
                                                            id="multible-locations" 
                                                            required="required"
                                                            <?php 
                                                                if( $location == 'multible locations' ){

                                                                    echo 'checked';
                                                                }
                                                            ?>
                                                        >
                                                        multible locations
                                                        (<?php
                                                            echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'multible locations' " , "AND advertisment_status = 1 AND category_id = $catID AND f_id = $subCatID");
                                                        ?>)
                                                    </label>
                                                </li>
                                                <li>
                                                    <label for="remote">
                                                        <input 
                                                            type="radio" 
                                                            name="location" 
                                                            value="remote" 
                                                            id="remote" 
                                                            required="required"
                                                            <?php 
                                                                if( $location == 'remote' ){

                                                                    echo 'checked';
                                                                }
                                                            ?>
                                                        >
                                                        remote
                                                        (<?php
                                                            echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'remote' " , "AND advertisment_status = 1 AND category_id = $catID AND f_id = $subCatID");
                                                        ?>)
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-12 search-salary">
                                            <h4> salary </h4>
                                            <input 
                                                type="text" 
                                                name="salaryFrom" 
                                                placeholder="From" 
                                                required="required"
                                                value="<?php echo $salaryFrom; ?>"
                                            >
                                            <input 
                                                type="text" 
                                                name="salaryTo" 
                                                placeholder="To" 
                                                required="required"
                                                value="<?php echo $salaryTo; ?>"
                                            >
                                        </div>
                                        <div class="col-sm-12 box-button-submit">
                                            <button name="search-all-jobs" type="submit"> clear filter </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-sm-9 job-advertisments">
                                    <div class="col-sm-12 counter-job-advertisments">
                                            <span>
                                                <?php echo count($rowsSearch_MIN_Max); ?>
                                                jobs
                                            </span>
                                    </div>

                                <?php
                                foreach($rowsSearch_MIN_Max as $ad){
                        
                                // Fetch employer information
                                $quaryEmployer = $connect->prepare("SELECT * 
                                                                    FROM employer 
                                                                    WHERE employer_id = ?
                                                                ");
                                $quaryEmployer->execute( array($ad['employer_id']) );
                                $rowEmployers= $quaryEmployer->fetchAll();

                                echo '<a';
                                    echo ' class="col-sm-12 box-job-advertisment-information" ';
                                    echo ' href="showAdvirtisment.php?advirtismentID= ' . $ad['advertisment_id'] . ' " ';
                                echo '>';
                                
                                    foreach($rowEmployers as $employer){
                                        if( $employer['logo'] == '' ){
                                
                                            echo '<span class="photoText"> <i class="fas fa-building"></i> </span>';
                                    
                                        } else{

                                            echo '<img src="uploads/Employers/' . $employer['logo'] . '">';

                                        }
                                        echo '<p class="employer-location">' . $employer['employer_address'] . '</p>';
                                    }

                                    echo '<h4 class="job-title">' . $ad['job_title'] . '</h4>';

                                    echo '<div class="job-type">' . $ad['job_type'] . '</div>';

                                    echo '<div class="job-location">' . $ad['work_location'] . '</div>';

                                    $quaryCategory = $connect->prepare("SELECT category_name 
                                                                            FROM category 
                                                                            WHERE category_id = ?
                                                                        ");
                                    $quaryCategory->execute( array($ad['category_id']) );
                                    $category= $quaryCategory->fetch(); 

                                    echo '<div class="category-name">' . $category['category_name'] . '</div> ';

                                    echo '<p class="description">' . $ad['job_description'] . '</p>';

                                    echo ' <span class="date-of-publication">' . getDatePosted($ad['date_of_publication'])  . '</span>';
                                echo '</a>';
                            }
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
                                    
                <?php }
            }
        } elseif( $do == 'search-job-title-location' ){

                   $valueSearchWhat  = filter_var($_POST['job-title'] , FILTER_SANITIZE_STRING);

                   $valueSearchWhere = filter_var($_POST['location'] , FILTER_SANITIZE_STRING);

                   if( isset($_POST['find-jobs']) ){

                        if( $valueSearchWhat == "" && $valueSearchWhere != "" ){

                                $quarySearch = $connect->prepare("SELECT job_advertisment.*
                                                FROM job_advertisment 
                                                INNER JOIN employer
                                                    ON employer.employer_id = job_advertisment.employer_id
                                                WHERE employer.employer_address LIKE :searchValue
                                                      AND 
                                                      job_advertisment.advertisment_status = 1

                                                ORDER BY date_of_publication DESC 
                                            ");
                                $searchValue = '%' . $valueSearchWhere . '%';
                                $quarySearch->bindParam("searchValue" , $searchValue);

                                $quarySearch->execute();
                                $rowsSearch = $quarySearch->fetchAll(); 

                                $rowsCount  =  $quarySearch->rowCount();


                        } elseif( $valueSearchWhere == "" && $valueSearchWhat != "" ){

                            
                                $quarySearch = $connect->prepare("SELECT *
                                                                  FROM job_advertisment
                                                                  WHERE
                                                                    job_title LIKE :searchValue
                                                                    AND
                                                                    advertisment_status = 1

                                                                  ORDER BY date_of_publication DESC 
                                                                ");
                                $searchValue = '%' . $valueSearchWhat . '%';
                                $quarySearch->bindParam("searchValue" , $searchValue);

                                $quarySearch->execute();
                                $rowsSearch = $quarySearch->fetchAll();
                                
                                $rowsCount  =  $quarySearch->rowCount();


                        } else{

                            $quarySearch = $connect->prepare("SELECT job_advertisment.*
                                            FROM job_advertisment 
                                            INNER JOIN employer
                                                ON employer.employer_id = job_advertisment.employer_id
                                            WHERE employer.employer_address  LIKE :searchlocationValue
                                                  AND
                                                  job_advertisment.job_title LIKE :searchJobTitleValue
                                                  AND 
                                                  job_advertisment.advertisment_status = 1

                                            ORDER BY date_of_publication DESC 
                                    ");
                            $searchlocationValue = '%' . $valueSearchWhere . '%';
                            $searchJobTitleValue = '%' . $valueSearchWhat . '%';

                            $quarySearch->bindParam("searchlocationValue" , $searchlocationValue);
                            $quarySearch->bindParam("searchJobTitleValue" , $searchJobTitleValue);

                            $quarySearch->execute();
                            $rowsSearch = $quarySearch->fetchAll(); 

                            $rowsCount  =  $quarySearch->rowCount();

                        }

                        // Fetch all categories
                        $fetchAllCategories = $connect->prepare("SELECT * 
                                                                 FROM category 
                                                            ");
                        $fetchAllCategories->execute();
                        $rowCategories= $fetchAllCategories->fetchAll();

                        ?>

                        <div class="find-work-section-top">
                            <div class="container">
                                <h2> find remote jobs </h2>
                                <p>
                                    Create a profile and apply for new remote job opportunities.
                                    Find professionals that best match your job requirements.
                                </p>
                                <form action="findWork.php?do=search-job-title-location" method="post">
                                    <div class="form-group-search-what" id="form-group-search-what">
                                        <span> what </span>
                                        <input type="text" name="job-title" placeholder="Job title or keywords">
                                        <i class="fa fa-search"></i>
                                    </div>
                                    <div class="form-group-search-where" id="form-group-search-where">
                                        <span> where </span>
                                        <input type="text" name="location" placeholder="Company address , city , state or zip code">
                                        <i class="fa fa-map-marker-alt"></i>
                                    </div>
                                    <div class="error">
                                        <i class="fa fa-exclamation-circle"></i>
                                        <h5> Enter a job title or location to start a search </h5>
                                    </div>
                                    <button type="submit" name="find-jobs">find jobs</button>
                                </form>
                            </div>
                        </div>

                        <div class="section-advanced-search-find-work">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-3 advanced-search">
                                        <div class="col-sm-12 category-search">
                                            <h4> categories </h4>
                                            <a href="findWork.php" class="link-all-category">
                                                all categories
                                                (<?php 
                                                    echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE advertisment_status = 1"); 
                                                ?>) 
                                            </a>
                                            <ul class="main-list-category">
                                                <?php
                                                    foreach($rowCategories as $category){

                                                        $quary = $connect->prepare(" SELECT COUNT(advertisment_id) as count
                                                                                    FROM job_advertisment
                                                                                    WHERE 
                                                                                        category_id = ?
                                                                                        AND
                                                                                        advertisment_status = 1
                                                                                    LIMIT 1
                                                        ");
                                                        $quary->execute( array( $category['category_id'] ) );
                                                        $count = $quary->fetch();

                                                        $categoryName = str_replace("&" , "/" , $category['category_name'] );
                                                        
                                                        echo '<li class="main-li-list-category">';
                                                            echo '<a href="findWork.php?do=searchCategory&catID='
                                                                    . $category['category_id']
                                                                    . '&catName=' . $categoryName .
                                                                ' "> ';
                                                                echo '<i class="fa fa-plus"></i>';
                                                                echo 
                                                                    $category['category_name'] 
                                                                    . ' (' . $count["count"] . ')';
                                                            echo '</a>';
                                                        echo '</li>';

                                                    }
                                                ?>
                                                
                                            </ul>
                                        </div>
                                        <form action="findWork.php?do=advancedSearch" method="post">
                                            <div class="col-sm-12 search-job-type">
                                                <h4> job type </h4>
                                                <ul>
                                                    <li>
                                                        <label for="full-time">
                                                            <input type="radio" name="job-type" value="full time" id="full-time" required="required">
                                                            full time
                                                            (<?php
                                                                echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'full time' " , "AND advertisment_status = 1");
                                                            ?>)
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label for="part-time">
                                                            <input type="radio" name="job-type" value="part time" id="part-time" required="required">
                                                            part time
                                                            (<?php
                                                                echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'part time' " , "AND advertisment_status = 1");
                                                            ?>)
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <label for="contract">
                                                            <input type="radio" name="job-type" value="contract" id="contract" required="required">
                                                            contract
                                                            (<?php
                                                                echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'contract' " , "AND advertisment_status = 1");
                                                            ?>)
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label for="intership">
                                                            <input type="radio" name="job-type" value="intership" id="intership" required="required">
                                                            intership
                                                            (<?php
                                                                echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'intership' " , "AND advertisment_status = 1");
                                                            ?>)
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label for="freelance">
                                                            <input type="radio" name="job-type" value="freelance" id="freelance" required="required">
                                                            freelance
                                                            (<?php
                                                                echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE job_type = 'freelance' " , "AND advertisment_status = 1");
                                                            ?>)
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-sm-12 search-location">
                                                <h4> location </h4>
                                                <ul>
                                                    <li>
                                                        <label for="one-location">
                                                            <input type="radio" name="location" value="one location" id="one-location" required="required">
                                                            one location
                                                            (<?php
                                                                echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'one location' " , "AND advertisment_status = 1");
                                                            ?>)
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label for="multible-locations">
                                                            <input type="radio" name="location" value="multible locations" id="multible-locations" required="required">
                                                            multible locations
                                                            (<?php
                                                                echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'multible locations' " , "AND advertisment_status = 1");
                                                            ?>)
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label for="remote">
                                                            <input type="radio" name="location" value="remote" id="remote" required="required">
                                                            remote
                                                            (<?php
                                                                echo getItemCount("advertisment_id" , "job_advertisment" , "WHERE work_location	 = 'remote' " , "AND advertisment_status = 1");
                                                            ?>)
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-sm-12 search-salary">
                                                <h4> salary </h4>
                                                <input type="text" name="salaryFrom" placeholder="From" required="required">
                                                <input type="text" name="salaryTo" placeholder="To" required="required">
                                            </div>
                                            <div class="col-sm-12 box-button-submit">
                                                <button name="search-all-jobs" type="submit"> clear filter </button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-sm-9 job-advertisments">
                                    <div class="col-sm-12 counter-job-advertisments">
                                            <span>
                                                <?php echo $rowsCount  ?>
                                                jobs
                                            </span>
                                    </div>

                                    <?php
                                            foreach($rowsSearch as $ad){
                                                
                                                // Fetch employer information
                                                $quaryEmployer = $connect->prepare("SELECT * 
                                                                                    FROM employer 
                                                                                    WHERE employer_id = ?
                                                                                ");
                                                $quaryEmployer->execute( array($ad['employer_id']) );
                                                $rowEmployers= $quaryEmployer->fetchAll();

                                                echo '<a';
                                                    echo ' class="col-sm-12 box-job-advertisment-information" ';
                                                    echo ' href="showAdvirtisment.php?advirtismentID= ' . $ad['advertisment_id'] . ' " ';
                                                echo '>';
                                                
                                                    foreach($rowEmployers as $employer){
                                                        if( $employer['logo'] == '' ){
                                                
                                                            echo '<span class="photoText"> <i class="fas fa-building"></i> </span>';
                                                    
                                                        } else{

                                                            echo '<img src="uploads/Employers/' . $employer['logo'] . '">';

                                                        }
                                                        echo '<p class="employer-location">' . $employer['employer_address'] . '</p>';
                                                    }

                                                    echo '<h4 class="job-title">' . $ad['job_title'] . '</h4>';

                                                    echo '<div class="job-type">' . $ad['job_type'] . '</div>';

                                                    echo '<div class="job-location">' . $ad['work_location'] . '</div>';

                                                    $quaryCategory = $connect->prepare("SELECT category_name 
                                                                                            FROM category 
                                                                                            WHERE category_id = ?
                                                                                        ");
                                                    $quaryCategory->execute( array($ad['category_id']) );
                                                    $category= $quaryCategory->fetch(); 

                                                    echo '<div class="category-name">' . $category['category_name'] . '</div> ';

                                                    echo '<p class="description">' . $ad['job_description'] . '</p>';

                                                    echo ' <span class="date-of-publication">' . getDatePosted($ad['date_of_publication'])  . '</span>';
                                                echo '</a>';
                                            }
                                    ?>
                                    </div>
                                </div>
                            </div>
                        </div>


                <?php }
            }
        ?>

<?php
    include $tmp . 'footer.php';
    ob_end_flush();
?>