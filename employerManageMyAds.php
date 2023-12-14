<?php
    ob_start();
    $pageTitle = 'Employer Manage My Ads';
    $homePage = '';
    include 'initialize.php';

    // Fetch employer information
    $quaryEmployer = $connect->prepare("SELECT * 
                                        FROM employer 
                                        WHERE employer_id = ?
                                    ");
    $quaryEmployer->execute( array($_SESSION['companyID']) );
    $rowEmployers= $quaryEmployer->fetchAll();

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if( $do == 'Manage' ){

        // Fetch all job advertisment
        $quary = $connect->prepare("SELECT * 
                                    FROM job_advertisment 
                                    WHERE employer_id = ?
                                    ORDER BY date_of_publication DESC");
        $quary->execute( array($_SESSION['companyID']) );
        $rows = $quary->fetchAll();
        $rowCount = $quary->rowCount();

        // Fetch count all job advertisment
        $quaryCountAllJobAds = $connect->prepare(" SELECT COUNT('advertisment_id') as count
                                                   FROM job_advertisment
                                                   WHERE employer_id = ?
                                                   limit 1
                                                ");
        $quaryCountAllJobAds->execute( array($_SESSION['companyID']) );
        $countAllJobAds = $quaryCountAllJobAds->fetch();

        ?>

        <div class="manage-my-ads">
            <?php
                if( $rowCount == 0 ){

                        echo '<div class="no-jobs-poted">';
                                echo '<div class="content">';
                                    echo '<i class="fa fa-briefcase big-icon"></i>';
                                    echo '<p> you haven\'t posted any jobs yet. </p>';
                                    echo '
                                        <a href="post a job.php">
                                            <i class="fa fa-plus"></i>
                                            post job
                                        </a>
                                    ';
                                echo '</div>';
                        echo '</div>';


                } else{ ?>
                    
                    <div class="container">
                        <div class="row">

                            <div class="col-sm-offset-1 col-sm-10 section-count-sort-ads">
                                <p class="countText"> 
                                    The number of all advirtisments : 
                                    <span class="countNumber">
                                        <?php echo $countAllJobAds["count"]; ?>
                                    </span>
                                </p>
                                <ul class="links-list">
                                    <li> 
                                        <a 
                                            href="employerManageMyAds.php?do=ManageActiveAdvertisment" 
                                            class="active"
                                        >
                                            active
                                        </a>
                                    </li>
                                    <li> 
                                        <a 
                                            href="employerManageMyAds.php?do=ManageDeactiveAdvertisment" 
                                            class="deactive"
                                        >
                                            deactive
                                        </a>
                                    </li>
                                </ul>
                            </div>                  
                                        
                            <?php
                                foreach($rows as $row){

                                    echo '<div class="col-sm-offset-1 col-sm-10 advertisment">';

                                            if( $row['advertisment_status'] == 1 ){
                                                echo '<div class="ribbon-shape-active">
                                                        <span> active </span>
                                                    </div>';
                                            } else {
                                                echo '<div class="ribbon-shape-deactive">
                                                        <span> deactive </span>
                                                    </div>';
                                            }

                                            foreach($rowEmployers as $employer){

                                                if( $employer['logo'] == '' ){
                                            
                                                    echo '<span class="photoText"> <i class="fas fa-building"></i> </span>';
                                        
                                            
                                                } else{

                                                    echo '<img src="uploads/Employers/' . $employer['logo'] . '">';

                                                }
                                                echo '<p class="employer-location">' . $employer['employer_address'] . '</p>';
                                            }

                                            echo '<i class="menu-dropdowm-icon employer-manage-menu-dropdown-icon fa fa-bars"></i>';
                                            
                                            echo '<div class="menu employer-manage-menu-dropdown">';
                                                echo '<ul>';
                                                    echo '<li>';
                                                            echo '<i class="fa fa-eye"></i>';
                                                            echo "<a  
                                                                    href='employerManageMyAds.php?do=Show&adid=" . 
                                                                    $row['advertisment_id'] ."' >";
                                                            echo ' show </a>';
                                                    echo '</li>';
                                                    echo '<li>';
                                                            echo '<i class="fa fa-edit"></i>';
                                                            echo "<a  
                                                                    href='employerManageMyAds.php?do=Edit&adid=" . 
                                                                    $row['advertisment_id'] ."' >";
                                                            echo ' edit </a>';
                                                    echo '</li>';
                                                    echo '<li>';
                                                            echo '<i class="fa fa-trash-alt"></i>';
                                                            echo "<a  
                                                                    href='employerManageMyAds.php?do=Delete&adid=" . 
                                                                    $row['advertisment_id'] ."' >";
                                                            echo ' delete </a>';
                                                    echo '</li>';
            
                                                    if( $row['advertisment_status'] == 1 ){

                                                        echo '<li>';
                                                            echo '<i class="fas fa-ban"></i>';
                                                            echo "<a  
                                                                    href='employerManageMyAds.php?do=updateStatus&adid=" . 
                                                                    $row['advertisment_id'] ."' >";
                                                            echo ' deactive </a>';
                                                        echo '</li>';
            
                                                    }else{

                                                        echo '<li>';
                                                            echo '<i class="fa fa-check"></i>';
                                                            echo "<a  
                                                                    href='employerManageMyAds.php?do=updateStatus&adid=" . 
                                                                    $row['advertisment_id'] ."' >";
                                                            echo ' active </a>';
                                                        echo '</li>';

                                                    }
                                                echo '</ul>';
                                            echo '</div>';

                                            echo '<h4 class="job-title">' . $row['job_title'] . '</h4>';

                                            echo '<div class="job-type">' . $row['job_type'] . '</div>';

                                            echo '<div class="job-location">' . $row['work_location'] . '</div>';

                                            $quaryCategory = $connect->prepare("SELECT category_name 
                                                                                FROM category 
                                                                                WHERE category_id = ?
                                                                            ");
                                            $quaryCategory->execute( array($row['category_id']) );
                                            $category= $quaryCategory->fetch(); 

                                            echo '<div class="category-name">' . $category['category_name'] . '</div> ';

                                            echo '<p class="description">' . $row['job_description'] . '</p>';

                                            echo ' <span class="date-of-publication">' . getDatePosted($row['date_of_publication'])  . '</span>';
                                    echo '</div>';
                                }
                            ?>

                        </div>
                    </div>

            <?php } ?>
        </div>


   <?php } elseif( $do == 'ManageActiveAdvertisment') {


                // Fetch active job advertisment
                $quary = $connect->prepare("SELECT * 
                                            FROM job_advertisment 
                                            WHERE employer_id = ? 
                                                AND
                                                advertisment_status = 1
                                            ORDER BY date_of_publication DESC");
                $quary->execute( array($_SESSION['companyID']) );
                $rows = $quary->fetchAll();
                $rowCount = $quary->rowCount();

                // Fetch count active job advertisment
                $quaryCountAllJobAds = $connect->prepare(" SELECT COUNT('advertisment_id') as count
                                                            FROM job_advertisment
                                                            WHERE employer_id = ? 
                                                                AND
                                                                advertisment_status = 1
                                                            limit 1
                                                        ");
                $quaryCountAllJobAds->execute( array($_SESSION['companyID']) );
                $countAllJobAds = $quaryCountAllJobAds->fetch();

        ?>

        <div class="manage-my-ads">
        <?php
                if( $rowCount == 0 ){

                echo '<div class="no-jobs-poted">';
                    echo '<div class="content">';
                        echo '<i class="fa fa-briefcase big-icon"></i>';
                        echo '<p> you don\'t have advertisments activated. </p>';
                        echo '
                            <a href="post a job.php">
                                <i class="fa fa-plus"></i>
                                post job
                            </a>
                        ';
                    echo '</div>';
                echo '</div>';

            } else{ ?>

            <div class="container">
                <div class="row">

                    <div class="col-sm-offset-1 col-sm-10 section-count-sort-ads">
                        <p class="countText"> 
                            The number of activated advirtisments : 
                            <span class="countNumber">
                                <?php echo $countAllJobAds["count"]; ?>
                            </span>
                        </p>
                        <ul class="links-list">
                            <li> 
                                <a 
                                    href="employerManageMyAds.php" 
                                    class="all"
                                >
                                    all
                                </a>
                            </li>
                            <li> 
                                <a 
                                    href="employerManageMyAds.php?do=ManageDeactiveAdvertisment" 
                                    class="deactive"
                                >
                                    deactive
                                </a>
                            </li>
                        </ul>
                    </div>

                    <?php
                    foreach($rows as $row){

                        echo '<div class="col-sm-offset-1 col-sm-10 advertisment">';

                            if( $row['advertisment_status'] == 1 ){
                                echo '<div class="ribbon-shape-active">
                                        <span> active </span>
                                    </div>';
                            }

                            foreach($rowEmployers as $employer){
                                if( $employer['logo'] == '' ){
                            
                                    echo '<span class="photoText"> <i class="fas fa-building"></i> </span>';
                            
                                } else{

                                    echo '<img src="uploads/Employers/' . $employer['logo'] . '">';

                                }
                                echo '<p class="employer-location">' . $employer['employer_address'] . '</p>';
                            }

                            echo '<i class="menu-dropdowm-icon employer-manage-menu-dropdown-icon fa fa-bars"></i>';
                                            
                            echo '<div class="menu employer-manage-menu-dropdown">';
                                echo '<ul>';
                                    echo '<li>';
                                            echo '<i class="fa fa-eye"></i>';
                                            echo "<a  
                                                    href='employerManageMyAds.php?do=Show&adid=" . 
                                                    $row['advertisment_id'] ."' >";
                                            echo ' show </a>';
                                    echo '</li>';
                                    echo '<li>';
                                            echo '<i class="fa fa-edit"></i>';
                                            echo "<a  
                                                    href='employerManageMyAds.php?do=Edit&adid=" . 
                                                    $row['advertisment_id'] ."' >";
                                            echo ' edit </a>';
                                    echo '</li>';
                                    echo '<li>';
                                            echo '<i class="fa fa-trash-alt"></i>';
                                            echo "<a  
                                                    href='employerManageMyAds.php?do=Delete&adid=" . 
                                                    $row['advertisment_id'] ."' >";
                                            echo ' delete </a>';
                                    echo '</li>';

                                    if( $row['advertisment_status'] == 1 ){

                                        echo '<li>';
                                            echo '<i class="fas fa-ban"></i>';
                                            echo "<a  
                                                    href='employerManageMyAds.php?do=updateStatus&adid=" . 
                                                    $row['advertisment_id'] ."' >";
                                            echo ' deactive </a>';
                                        echo '</li>';

                                    }
                                echo '</ul>';
                            echo '</div>';

                            echo '<h4 class="job-title">' . $row['job_title'] . '</h4>';

                            echo '<div class="job-type">' . $row['job_type'] . '</div>';

                            echo '<div class="job-location">' . $row['work_location'] . '</div>';

                            $quaryCategory = $connect->prepare("SELECT category_name 
                                                                FROM category 
                                                                WHERE category_id = ?
                                                            ");
                            $quaryCategory->execute( array($row['category_id']) );
                            $category= $quaryCategory->fetch(); 

                            echo '<div class="category-name">' . $category['category_name'] . '</div> ';

                            echo '<p class="description">' . $row['job_description'] . '</p>';

                            echo ' <span class="date-of-publication">' . getDatePosted($row['date_of_publication'])  . '</span>';
                        echo '</div>';
                    }
                ?>
            </div>
        </div>

    <?php } ?>
</div>

     <?php  } elseif( $do == 'ManageDeactiveAdvertisment' ){

                    // Fetch deactive job advertisment
                    $quary = $connect->prepare("SELECT * 
                                                FROM job_advertisment 
                                                WHERE employer_id = ? 
                                                    AND
                                                    advertisment_status = 0
                                                ORDER BY date_of_publication DESC");
                    $quary->execute( array($_SESSION['companyID']) );
                    $rows = $quary->fetchAll();
                    $rowCount = $quary->rowCount();

                    // Fetch count deactive job advertisment
                    $quaryCountAllJobAds = $connect->prepare(" SELECT COUNT('advertisment_id') as count
                                            FROM job_advertisment
                                            WHERE employer_id = ? 
                                                AND
                                                advertisment_status = 0
                                            limit 1
                                        ");
                    $quaryCountAllJobAds->execute( array($_SESSION['companyID']) );
                    $countAllJobAds = $quaryCountAllJobAds->fetch();

                ?>

                <div class="manage-my-ads">
                <?php

                if( $rowCount == 0 ){

                    echo '<div class="no-jobs-poted">';
                        echo '<div class="content">';
                            echo '<i class="fa fa-briefcase big-icon"></i>';
                            echo '<p> you don\'t have advertisments deactivated. </p>';
                            echo '
                                  <a href="post a job.php">
                                    <i class="fa fa-plus"></i>
                                    post job
                                 </a>';
                        echo '</div>';
                    echo '</div>';

                } else{ ?>

                    <div class="container">
                        <div class="row">

                            <div class="col-sm-offset-1 col-sm-10 section-count-sort-ads">
                                <p class="countText"> 
                                    The number of deactivated advirtisments : 
                                    <span class="countNumber">
                                        <?php echo $countAllJobAds["count"]; ?>
                                    </span>
                                </p>
                                <ul class="links-list">
                                    <li> 
                                        <a 
                                            href="employerManageMyAds.php" 
                                            class="all"
                                        >
                                            all
                                        </a>
                                    </li>
                                    <li> 
                                        <a 
                                            href="employerManageMyAds.php?do=ManageActiveAdvertisment" 
                                            class="active"
                                        >
                                            active
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        <?php
                            foreach($rows as $row){

                                echo '<div class="col-sm-offset-1 col-sm-10 advertisment">';

                                    if( $row['advertisment_status'] == 0 ){
                                            echo '<div class="ribbon-shape-deactive">
                                                <span> deactive </span>
                                            </div>';
                                    }

                                    foreach($rowEmployers as $employer){

                                        if( $employer['logo'] == '' ){

                                            echo '<span class="photoText"> <i class="fas fa-building"></i> </span>';

                                        } else{

                                            echo '<img src="uploads/Employers/' . $employer['logo'] . '">';

                                        }

                                        echo '<p class="employer-location">' . $employer['employer_address'] . '</p>';
                                    }

                                    echo '<i class="menu-dropdowm-icon employer-manage-menu-dropdown-icon fa fa-bars"></i>';
                                            
                                    echo '<div class="menu employer-manage-menu-dropdown">';
                                        echo '<ul>';
                                            echo '<li>';
                                                    echo '<i class="fa fa-eye"></i>';
                                                    echo "<a  
                                                            href='employerManageMyAds.php?do=Show&adid=" . 
                                                            $row['advertisment_id'] ."' >";
                                                    echo ' show </a>';
                                            echo '</li>';
                                            echo '<li>';
                                                    echo '<i class="fa fa-edit"></i>';
                                                    echo "<a  
                                                            href='employerManageMyAds.php?do=Edit&adid=" . 
                                                            $row['advertisment_id'] ."' >";
                                                    echo ' edit </a>';
                                            echo '</li>';
                                            echo '<li>';
                                                    echo '<i class="fa fa-trash-alt"></i>';
                                                    echo "<a  
                                                            href='employerManageMyAds.php?do=Delete&adid=" . 
                                                            $row['advertisment_id'] ."' >";
                                                    echo ' delete </a>';
                                            echo '</li>';

                                            if( $row['advertisment_status'] == 0 ){

                                                echo '<li>';
                                                    echo '<i class="fa fa-check"></i>';
                                                    echo "<a  
                                                            href='employerManageMyAds.php?do=updateStatus&adid=" . 
                                                            $row['advertisment_id'] ."' >";
                                                    echo ' active </a>';
                                                echo '</li>';

                                            }
                                        echo '</ul>';
                                    echo '</div>';

                                    echo '<h4 class="job-title">' . $row['job_title'] . '</h4>';

                                    echo '<div class="job-type">' . $row['job_type'] . '</div>';

                                    echo '<div class="job-location">' . $row['work_location'] . '</div>';

                                    $quaryCategory = $connect->prepare("SELECT category_name 
                                                                FROM category 
                                                                WHERE category_id = ?
                                                            ");
                                    $quaryCategory->execute( array($row['category_id']) );
                                    $category= $quaryCategory->fetch(); 

                                    echo '<div class="category-name">' . $category['category_name'] . '</div> ';

                                    echo '<p class="description">' . $row['job_description'] . '</p>';

                                    echo ' <span class="date-of-publication">' . getDatePosted($row['date_of_publication'])  . '</span>';
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>

            <?php } ?>
        </div>

            <?php } elseif($do == 'Edit'){

                $adid = isset($_GET['adid']) && is_numeric($_GET['adid']) ? $_GET['adid'] : 0;

                $quary = $connect->prepare("
                        SELECT *
                        FROM job_advertisment 
                        WHERE advertisment_id = ?
                        LIMIT 1 
                    ");
                $quary->execute(array($adid));
                $ad = $quary->fetch(); 
                $count = $quary->rowCount();

                $categories = $connect->prepare("SELECT * FROM category");
                $categories->execute();
                $getCategories = $categories->fetchAll();

                if( $count > 0 ){ ?>
             
                    <div class="post-a-job-page">
                        <div class="container">
                            <form action="employerManageMyAds.php?do=Update" method="post" id="formPostJob">
                                <div class="row">

                                    <div class="header-container col-sm-offset-3 col-sm-6">
                                        <h3 class="text-center"> hire the talent you need </h3>
                                        <p class="text-center">
                                            post your job requirements to reach the right candidates
                                        </p>
                                    </div>

                                    <div class="job-title col-sm-12">
                                        <div class="label col-sm-3">
                                            <p> job title </p>
                                        </div>
                                        <div class="input col-sm-9">
                                            <input type="hidden" name="advirtismentID" value="<?php echo $adid; ?>">
                                            <input 
                                                type="text" 
                                                name="jobTitle"
                                                placeholder="Job Title" 
                                                id="jobTitle_postJob"
                                                oninput="postJob_checkJobTitleLive()"
                                                value="<?php 
                                                            if( 
                                                                isset($ad['job_title']) && 
                                                                !empty($ad['job_title']) 
                                                            ){
                                                                echo $ad['job_title'];
                                                            }
                                                        ?>"
                                            >
                                            <div class="input-error">
                                                <p> </p>
                                            </div>
                                            <span> </span>
                                        </div>
                                    </div>

                                    <div class="category col-sm-12">
                                        <div class="label col-sm-3">
                                            <p> category </p>
                                        </div>
                                        <div class="select-category col-sm-3">
                                            <select name="category" id="category" oninput='postJob_checkCategoryLive()'>
                                                <option value="">select category</option>
                                                <?php
                                                    foreach($getCategories as $category){ 

                                                        echo "<option value='" . $category['category_id'] . "'";

                                                        if( $category['category_id'] == $ad['category_id'] ){
                                                            echo "selected";
                                                        }

                                                        echo ">";

                                                        echo $category['category_name'] . "</option>";
                                                    }

                                                ?>
                                            </select>
                                            <p> </p>
                                        </div>
                                        <div class="select-subcategory col-sm-3">
                                            <select name="foucsingOn" id="foucsing-on">
                                                <?php
                                                    $foucsings = $connect->prepare("SELECT * FROM focusing_on");
                                                    $foucsings->execute();
                                                    $getFoucsings = $foucsings->fetchAll();

                                                    foreach( $getFoucsings as $foucesOn ){

                                                        if( $foucesOn['f_id'] == $ad['f_id'] ){

                                                            echo "<option selected value='" . $ad['f_id'] . "'>"
                                                            . $foucesOn['f_name'] .
                                                            "</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <p> </p>
                                        </div>
                                    </div>
            
                                    <div class="job-type col-sm-12">
                                        <div class="label col-sm-3">
                                            <p> job type </p>
                                        </div>
                                        <div class="radio-group col-sm-9">
                                            <ul>
                                                <li>
                                                    <input 
                                                        type="radio" 
                                                        id="full-time" 
                                                        name="jobType"
                                                        value="full time" 
                                                        <?php
                                                            if( $ad['job_type'] == 'full time' ){

                                                                echo "checked";
                                                            }
                                                        ?>
                                                    >
                                                    <label for="full-time">full time</label>
                                                </li>
                                                <li>
                                                    <input 
                                                        type="radio" 
                                                        id="part-time" 
                                                        name="jobType"
                                                        value="part time"
                                                        <?php
                                                            if( $ad['job_type'] == 'part time' ){

                                                                echo "checked";
                                                            }
                                                        ?>
                                                    >
                                                    <label for="part-time">part time</label>
                                                </li>
                                                <li>
                                                    <input 
                                                        type="radio" 
                                                        id="contract" 
                                                        name="jobType"
                                                        value="contract"
                                                        <?php
                                                            if( $ad['job_type'] == 'contract' ){

                                                                echo "checked";
                                                            }
                                                        ?>
                                                    >
                                                    <label for="contract">contract</label>
                                                </li>
                                                <li>
                                                    <input 
                                                        type="radio" 
                                                        id="intership" 
                                                        name="jobType"
                                                        value="intership"
                                                        <?php
                                                            if( $ad['job_type'] == 'intership' ){

                                                                echo "checked";
                                                            }
                                                        ?>
                                                    >
                                                    <label for="intership">intership</label>
                                                </li>
                                                <li>
                                                    <input 
                                                        type="radio" 
                                                        id="freelance" 
                                                        name="jobType"
                                                        value="freelance"
                                                        <?php
                                                            if( $ad['job_type'] == 'freelance' ){

                                                                echo "checked";
                                                            }
                                                        ?>
                                                    >
                                                    <label for="freelance">freelance</label>
                                                </li>
                                            </ul>
                                            <p id="jobTypeParagraph"> full time employment in a company. </p>
                                        </div>
                                    </div>

                                    <div class="location col-sm-12">
                                        <div class="label col-sm-3">
                                            <p> location </p>
                                        </div>
                                        <div class="radio-group col-sm-9">
                                            <ul>
                                                <li>
                                                    <input 
                                                        type="radio" 
                                                        id="one-location" 
                                                        name="location"
                                                        value="one location" 
                                                        <?php
                                                            if( $ad['work_location'] == 'one location' ){

                                                                echo "checked";
                                                            }
                                                        ?>
                                                    >
                                                    <label for="one-location">one location</label>
                                                </li>
                                                <li>
                                                    <input 
                                                        type="radio" 
                                                        id="multible-locations" 
                                                        name="location"
                                                        value="multible locations"
                                                        <?php
                                                            if( $ad['work_location'] == 'multible locations' ){

                                                                echo "checked";
                                                            }
                                                        ?>
                                                    >
                                                    <label for="multible-locations">multible locations</label>
                                                </li>
                                                <li>
                                                    <input 
                                                        type="radio" 
                                                        id="remote" 
                                                        name="location"
                                                        value="remote"
                                                        <?php
                                                            if( $ad['work_location'] == 'remote' ){

                                                                echo "checked";
                                                            }
                                                        ?>
                                                    >
                                                    <label for="remote">remote</label>
                                                </li>
                                            </ul>
                                            <p id="locationParagraph"> all work requires on-site attendance. </p>
                                        </div>
                                    </div>

                                    <div class="salary col-sm-12">
                                        <div class="label col-sm-3">
                                            <p> salary </p>
                                        </div>
                                        <div class="radio-group col-sm-9">
                                            <ul>
                                                <li>
                                                    <p> more candidates apply to jobs with salary information. </p> 
                                                </li>
                                                <li>
                                                    <input 
                                                        type="radio" 
                                                        id="salary-radio-post-job-range" 
                                                        name="salaryRangeFixed"
                                                        value="range" 
                                                        <?php
                                                            if( 
                                                                isset($ad['salary_static']) 
                                                                && 
                                                                empty($ad['salary_static']) ){
        
                                                                    echo "checked";
                                                                }
                                                        ?>
                                                    >
                                                    <label for="salary-radio-post-job-range">range</label>
                                                </li>
                                                <li>
                                                    <input 
                                                        type="radio" 
                                                        id="salary-radio-post-job-fixed" 
                                                        name="salaryRangeFixed"
                                                        value="fixed"
                                                        <?php
                                                            if( 
                                                                isset($ad['salary_static']) 
                                                                && 
                                                                !empty($ad['salary_static']) ){
        
                                                                    echo "checked";
                                                                }
                                                        ?>
                                                    >
                                                    <label for="salary-radio-post-job-fixed">fixed</label>
                                                </li>
                                            </ul>
                                            <select name="salaryType">
                                                <option
                                                    <?php 
                                                        if( 
                                                            isset($ad['salary_type']) && 
                                                            $ad['salary_type'] == 'year' 
                                                        ){
                                                            echo 'selected';
                                                        }
                                                    ?>
                                                    value="year"
                                                >
                                                    Per Year
                                                </option>
                                                <option
                                                    <?php 
                                                        if( 
                                                            isset($ad['salary_type']) && 
                                                            $ad['salary_type'] == 'month' 
                                                        ){
                                                            echo 'selected';
                                                        }
                                                    ?> 
                                                    value="month"
                                                >
                                                    Per Month
                                                </option>
                                                <option 
                                                    <?php 
                                                        if( 
                                                            isset($ad['salary_type']) && 
                                                            $ad['salary_type'] == 'week' 
                                                        ){
                                                            echo 'selected';
                                                        }
                                                    ?> 
                                                    value="week"
                                                >
                                                    Per Week
                                                </option>
                                                <option 
                                                    <?php 
                                                        if( 
                                                            isset($ad['salary_type']) && 
                                                            $ad['salary_type'] == 'hour' 
                                                        ){
                                                            echo 'selected';
                                                        }
                                                    ?> 
                                                    value="hour"
                                                >
                                                    Per Hour
                                                </option>
                                            </select>
                                            <input 
                                                type="text" 
                                                name="salaryMin"
                                                required='required' 
                                                oninput="notationSalary()" 
                                                onkeypress="return restrictAlphabetes(event)" 
                                                maxlength="6" 
                                                class="inputSalaryFrom" 
                                                placeholder="Form ($)"
                                                value="<?php
                                                    if(isset($ad['salary_static']) 
                                                    && 
                                                    empty($ad['salary_static'])){

                                                        echo $ad['salary_min'];
                                                    }
                                                ?>"
                                            >
                                            <input 
                                                type="text"
                                                name="salaryMax" 
                                                required='required' 
                                                onkeypress="return restrictAlphabetes(event)" 
                                                class="inputSalaryTo" 
                                                maxlength="6" 
                                                placeholder="To ($)"
                                                value="<?php
                                                    if(isset($ad['salary_static']) 
                                                    && 
                                                    empty($ad['salary_static'])){

                                                        echo $ad['salary_max'];
                                                    }
                                                ?>"
                                            >
                                            <input 
                                                type="text"
                                                name="salaryStatic" 
                                                onkeypress="return restrictAlphabetes(event)" 
                                                class="inputSalaryFixed" 
                                                maxlength="6" 
                                                placeholder="Salary ($)"
                                                value="<?php
                                                    if(isset($ad['salary_static']) 
                                                    && 
                                                    !empty($ad['salary_static'])){

                                                        echo $ad['salary_static'];
                                                    }
                                                ?>"
                                            >
                                        </div>
                                    </div>

                                    <div class="work-shedule col-sm-12">
                                        <div class="label col-sm-3">
                                            <p> work-shedule </p>
                                        </div>
                                        <div class="selects col-sm-9">
                                            <div class="work-shedule-start col-sm-6">
                                                <select 
                                                    id="work-shedule-start"
                                                    oninput="postJob_checkWorkSheduleStartLive()" 
                                                    name="workSheduleStart"
                                                >
                                                    <option value=""> Start of a week </option>
                                                    <option 
                                                        <?php 
                                                            if( 
                                                                isset($ad['work_shedule_start']) && 
                                                                $ad['work_shedule_start'] == 'Sunday' 
                                                            ){
                                                                echo 'selected';
                                                            }
                                                        ?> 
                                                        value="Sunday"
                                                    > 
                                                        Sunday 
                                                    </option>
                                                    <option
                                                        <?php 
                                                            if( 
                                                                isset($ad['work_shedule_start']) && 
                                                                $ad['work_shedule_start'] == 'Monday' 
                                                            ){
                                                                echo 'selected';
                                                            }
                                                        ?> 
                                                        value="Monday"
                                                    > 
                                                        Monday 
                                                    </option>
                                                    <option 
                                                        <?php 
                                                            if( 
                                                                isset($ad['work_shedule_start']) && 
                                                                $ad['work_shedule_start'] == 'Tuesday' 
                                                            ){
                                                                echo 'selected';
                                                            }
                                                        ?> 
                                                        value="Tuesday"
                                                    > 
                                                        Tuesday 
                                                    </option>
                                                    <option
                                                        <?php 
                                                            if( 
                                                                isset($ad['work_shedule_start']) && 
                                                                $ad['work_shedule_start'] == 'Wedenesday' 
                                                            ){
                                                                echo 'selected';
                                                            }
                                                        ?> 
                                                        value="Wedenesday"
                                                    > 
                                                        Wedenesday 
                                                    </option>
                                                    <option
                                                        <?php 
                                                            if( 
                                                                isset($ad['work_shedule_start']) && 
                                                                $ad['work_shedule_start'] == 'Thursday' 
                                                            ){
                                                                echo 'selected';
                                                            }
                                                        ?> 
                                                        value="Thursday"
                                                    > 
                                                        Thursday 
                                                    </option>
                                                    <option
                                                        <?php 
                                                            if( 
                                                                isset($ad['work_shedule_start']) && 
                                                                $ad['work_shedule_start'] == 'Friday' 
                                                            ){
                                                                echo 'selected';
                                                            }
                                                        ?> 
                                                        value="Friday"
                                                    > 
                                                        Friday 
                                                    </option>
                                                    <option
                                                        <?php 
                                                            if( 
                                                                isset($ad['work_shedule_start']) && 
                                                                $ad['work_shedule_start'] == 'Satarday' 
                                                            ){
                                                                echo 'selected';
                                                            }
                                                        ?> 
                                                        value="Satarday"
                                                    > 
                                                        Satarday 
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="work-shedule-end col-sm-6">
                                                <select 
                                                    id="work-shedule-end"
                                                    oninput="postJob_checkWorkSheduleEndLive()" 
                                                    name="workSheduleEnd"
                                                >
                                                    <option value=""> End of a week </option>
                                                    <option 
                                                        <?php 
                                                            if( 
                                                                isset($ad['work_shedule_end']) && 
                                                                $ad['work_shedule_end'] == 'Sunday' 
                                                            ){
                                                                echo 'selected';
                                                            }
                                                        ?> 
                                                        value="Sunday"
                                                    > 
                                                        Sunday 
                                                    </option>
                                                    <option
                                                        <?php 
                                                            if( 
                                                                isset($ad['work_shedule_end']) && 
                                                                $ad['work_shedule_end'] == 'Monday' 
                                                            ){
                                                                echo 'selected';
                                                            }
                                                        ?> 
                                                        value="Monday"
                                                    > 
                                                        Monday 
                                                    </option>
                                                    <option 
                                                        <?php 
                                                            if( 
                                                                isset($ad['work_shedule_end']) && 
                                                                $ad['work_shedule_end'] == 'Tuesday' 
                                                            ){
                                                                echo 'selected';
                                                            }
                                                        ?> 
                                                        value="Tuesday"
                                                    > 
                                                        Tuesday 
                                                    </option>
                                                    <option
                                                        <?php 
                                                            if( 
                                                                isset($ad['work_shedule_end']) && 
                                                                $ad['work_shedule_end'] == 'Wedenesday' 
                                                            ){
                                                                echo 'selected';
                                                            }
                                                        ?> 
                                                        value="Wedenesday"
                                                    > 
                                                        Wedenesday 
                                                    </option>
                                                    <option
                                                        <?php 
                                                            if( 
                                                                isset($ad['work_shedule_end']) && 
                                                                $ad['work_shedule_end'] == 'Thursday' 
                                                            ){
                                                                echo 'selected';
                                                            }
                                                        ?> 
                                                        value="Thursday"
                                                    > 
                                                        Thursday 
                                                    </option>
                                                    <option
                                                        <?php 
                                                            if( 
                                                                isset($ad['work_shedule_end']) && 
                                                                $ad['work_shedule_end'] == 'Friday' 
                                                            ){
                                                                echo 'selected';
                                                            }
                                                        ?> 
                                                        value="Friday"
                                                    > 
                                                        Friday 
                                                    </option>
                                                    <option
                                                        <?php 
                                                            if( 
                                                                isset($ad['work_shedule_end']) && 
                                                                $ad['work_shedule_end'] == 'Satarday' 
                                                            ){
                                                                echo 'selected';
                                                            }
                                                        ?> 
                                                        value="Satarday"
                                                    > 
                                                        Satarday 
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="work-shedule-type-hours col-sm-6">
                                                <ul>
                                                    <li> 
                                                        <p> work hours :  </p> 
                                                    </li>
                                                    <li> 
                                                        <input 
                                                            type="radio" 
                                                            id="workShudeleTypeDay" 
                                                            name="workShudeleType"
                                                            value="day"
                                                            <?php
                                                                if( 
                                                                    isset($ad['work_shedule_type']) && 
                                                                    $ad['work_shedule_type'] == 'day' 
                                                                ){
                                                                    echo 'checked';
                                                                }
                                                            ?> 
                                                        >
                                                        <label for="workShudeleTypeDay">day</label>
                                                    </li>
                                                    <li> 
                                                        <input 
                                                            type="radio" 
                                                            id="workShudeleTypeWeek" 
                                                            name="workShudeleType"
                                                            value="week"
                                                            <?php
                                                                if( 
                                                                    isset($ad['work_shedule_type']) && 
                                                                    $ad['work_shedule_type'] == 'week' 
                                                                ){
                                                                    echo 'checked';
                                                                }
                                                            ?> 
                                                        >
                                                        <label for="workShudeleTypeWeek">week</label>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="work-shedule-hours col-sm-6">
                                                <input 
                                                    type="number" 
                                                    name="workSheduleHours"
                                                    oninput="postJob_checkWorkSheduleHoursLive()" 
                                                    placeholder="Enter work hours"
                                                    value="<?php
                                                        if(isset($ad['work_shedule_hours']) 
                                                        && 
                                                        !empty($ad['work_shedule_hours'])){

                                                            echo $ad['work_shedule_hours'];
                                                        }
                                                    ?>"
                                                >
                                                <p> </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="gender col-sm-12">
                                        <div class="label col-sm-3">
                                            <p> gender required </p>
                                        </div>
                                        <div class="radio-group col-sm-9">
                                            <ul>
                                                <li>
                                                    <input 
                                                        type="radio"  
                                                        id="post-job-gender-male" 
                                                        name="gender"
                                                        value="male"
                                                        <?php
                                                            if( $ad['gender'] == 'male' ){

                                                                echo "checked";
                                                            }
                                                        ?>
                                                    >
                                                    <label for="post-job-gender-male">male</label>
                                                </li>
                                                <li>
                                                    <input 
                                                        type="radio" 
                                                        id="post-job-gender-female" 
                                                        name="gender"
                                                        value="female"
                                                        <?php
                                                            if( $ad['gender'] == 'female' ){

                                                                echo "checked";
                                                            }
                                                        ?>
                                                    >
                                                    <label for="post-job-gender-female">female</label>
                                                </li>
                                                <li>
                                                    <input 
                                                        type="radio" 
                                                        id="post-job-gender-anyone" 
                                                        name="gender"
                                                        value="anyone"
                                                        <?php
                                                            if( $ad['gender'] == 'anyone' ){

                                                                echo "checked";
                                                            }
                                                        ?>
                                                    >
                                                    <label for="post-job-gender-anyone">anyone</label>
                                                </li>
                                            </ul>
                                            <p id="genderParagraph"> Only males can applay for the job. </p>
                                        </div>
                                    </div>

                                    <div class="description col-sm-12">
                                        <div class="label col-sm-3">
                                            <p> job description </p>
                                        </div>
                                        <div class="input col-sm-9">
                                            <textarea 
                                                name="jobDescription" 
                                                oninput="postJob_checkDescriptionLive()" 
                                                id="description-post-job"
                                                ><?php
                                                    if( isset($ad['job_description']) &&
                                                        !empty($ad['job_description']) ){

                                                            echo $ad['job_description'];
                                                        }
                                                ?></textarea>
                                            <div class="input-error">
                                                <p> </p>
                                            </div>
                                            <span> </span>
                                        </div>
                                    </div>

                                    <button type="submit" name="advirtisment-update-button"> advirtisment update </button>

                                </div>
                            </form>
                        </div>
                    </div>
            <?php }

            } elseif($do == 'Update'){

                
                if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

                    if( isset($_POST['advirtisment-update-button']) ){

                       $advirtismentID   = $_POST['advirtismentID'];
                       $jobTitle         = filter_var($_POST['jobTitle'] , FILTER_SANITIZE_STRING);
                       $category         = $_POST['category'];
                       $foucsingOn       = $_POST['foucsingOn'];
                       $jobType          = $_POST['jobType'];
                       $location         = $_POST['location'];
                       $salaryType       = $_POST['salaryType'];
                       $workSheduleStart = $_POST['workSheduleStart'];
                       $workSheduleEnd   = $_POST['workSheduleEnd'];
                       $workShudeleType  = $_POST['workShudeleType'];
                       $workSheduleHours = $_POST['workSheduleHours'];
                       $gender           = $_POST['gender'];
                       $jobDescription   = filter_var($_POST['jobDescription'] ,FILTER_SANITIZE_STRING);

                       $salaryMin    = $_POST['salaryMin'];
                       $salaryMax    = $_POST['salaryMax'];
                       $salaryStatic = $_POST['salaryStatic'];
                       
                       $updateAllSalary = $connect->prepare("UPDATE job_advertisment
                                                             SET salary_min    = '' , 
                                                                 salary_max    = '' , 
                                                                 salary_static = '' 
                                                             WHERE advertisment_id = ? ");
                       $updateAllSalary->execute(array($advirtismentID));

                      if( $_POST['salaryRangeFixed'] == 'range' ){

                        $quary = $connect->prepare("UPDATE job_advertisment
                                                    SET category_id = ? , f_id = ? , 
                                                        job_title = ? , job_type = ? , 
                                                        salary_min = ? , salary_max = ? , 
                                                        salary_type = ? , work_shedule_start = ? , 
                                                        work_shedule_end = ? , work_shedule_type = ? , 
                                                        work_shedule_hours = ? , gender = ? ,
                                                        job_description = ? , work_location = ?
                                                    WHERE advertisment_id = ?");
                        $quary->execute( 
                                array($category , $foucsingOn , 
                                      $jobTitle , $jobType ,
                                      $salaryMin , $salaryMax ,
                                      $salaryType , $workSheduleStart ,
                                      $workSheduleEnd , $workShudeleType ,
                                      $workSheduleHours , $gender ,
                                      $jobDescription , $location , 
                                      $advirtismentID)
                        );

                        echo '<div class="full-page-alerts with-navbar">';
                            echo '<div class="content-alerts">';
                                redirectPage('success' , 'your advirtisment information has been updated successfully');
                                redirectPage('info' , 'redirect manage ads page wihtin 3 seconds' , 'employerManageMyAds.php' , '3');
                            echo '</div>';
                        echo '</div>';
                            

                      } elseif( $_POST['salaryRangeFixed'] == 'fixed' ){

                           
                        $quary = $connect->prepare("UPDATE job_advertisment
                                                    SET category_id = ? , f_id = ? , 
                                                        job_title = ? , job_type = ? , 
                                                        salary_static = ? , 
                                                        salary_type = ? , work_shedule_start = ? , 
                                                        work_shedule_end = ? , work_shedule_type = ? , 
                                                        work_shedule_hours = ? , gender = ? ,
                                                        job_description = ? , work_location = ?
                                                    WHERE advertisment_id = ?");
                        $quary->execute( 
                                array($category , $foucsingOn , 
                                      $jobTitle , $jobType ,
                                      $salaryStatic ,
                                      $salaryType , $workSheduleStart ,
                                      $workSheduleEnd , $workShudeleType ,
                                      $workSheduleHours , $gender ,
                                      $jobDescription , $location , 
                                      $advirtismentID)
                        );

                        echo '<div class="full-page-alerts with-navbar">';
                            echo '<div class="content-alerts">';
                                redirectPage('success' , 'your advertisment information has been updated successfully');
                                redirectPage('info' , 'redirect manage ads page wihtin 3 seconds' , 'employerManageMyAds.php' , '3');
                            echo '</div>';
                        echo '</div>';

                      }
                        
                    }

                }

            } elseif($do == 'updateStatus'){

                    $adid = isset($_GET['adid']) && is_numeric($_GET['adid']) ? $_GET['adid'] : 0;

                    $quary = $connect->prepare("SELECT advertisment_status 
                                                FROM job_advertisment
                                                WHERE advertisment_id = ?");
                    $quary->execute(array($adid));
                    $result = $quary->fetch();
        
                    if( $result['advertisment_status'] == 1 ){
        
                            $quary = $connect->prepare("UPDATE job_advertisment
                                                        SET advertisment_status = 0
                                                        WHERE advertisment_id = ?
                                                    ");
                            $quary->execute(array($adid));

        
                    } else{
        
                            $quary = $connect->prepare("UPDATE job_advertisment
                                                        SET advertisment_status = 1
                                                        WHERE advertisment_id = ?
                                                    ");
                            $quary->execute(array($adid));
                    }

                    echo '<div class="full-page-alerts with-navbar">';
                        echo '<div class="content-alerts">';
                            redirectPage('success' , 'your advertisment status has been updated successfully');
                            redirectPage('info' , 'redirect manage ads page wihtin 3 seconds' , 'employerManageMyAds.php' , '3');
                        echo '</div>';
                    echo '</div>';

            } elseif( $do == 'Delete' ){

                    $adid = isset($_GET['adid']) && is_numeric($_GET['adid']) ? $_GET['adid'] : 0;
        
                    $quary = $connect->prepare("DELETE FROM job_advertisment WHERE advertisment_id = ?");
                    $quary->execute(array($adid));
        
                    echo '<div class="full-page-alerts with-navbar">';
                        echo '<div class="content-alerts">';
                            redirectPage('success' , 'your advertisment has been deleted successfully');
                            redirectPage('info' , 'redirect manage ads page wihtin 3 seconds' , 'employerManageMyAds.php' , '3');
                        echo '</div>';
                    echo '</div>';
        
                    header("refresh:3;url=companyManageMyAds.php");
                    exit();

    
            } elseif( $do == 'Show' ){

                    $adid = isset($_GET['adid']) && is_numeric($_GET['adid']) ? $_GET['adid'] : 0;

                    $quary = $connect->prepare("SELECT job_advertisment.* , employer.*
                                                FROM job_advertisment
                                                INNER JOIN employer
                                                    ON employer.employer_id = job_advertisment.employer_id
                                                WHERE job_advertisment.advertisment_id = ?
                                                LIMIT 1
                                            ");
                    $quary->execute(array($adid));
                    $row = $quary->fetch();
                    
                    ?>

                <div class="show-advirtisment">
                    <div class="container">
                        <div class="row">
                            <section class="col-sm-9 left-section">
                                <div class="employer-logo">
                                        <?php
                                           
                                            if( $row['logo'] == '' ){
                                        
                                                echo '<span> <i class="fas fa-building"></i> </span>';
                                        
                                            } else{

                                                echo '<img src="uploads/Employers/' . $row['logo'] . '">';

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
                                    <div class="gender gender-employer-show-ad">
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
    <?php } ?>
   
<?php
    include $tmp . 'footer.php';
    ob_end_flush();

?>