<?php
    ob_start();
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);
    $pageTitle = 'Post A job Profile';
    $homePage = '';
    include 'initialize.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'post-a-job';

    if($do == 'post-a-job'){

        $categories = $connect->prepare("SELECT * FROM category");
        $categories->execute();
        $getCategories = $categories->fetchAll();

    ?>

<div class="post-a-job-page">
    <div class="container">
        <form action="post a job.php?do=insert-post-a-job" method="post" id="formPostJob">
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
                        <input 
                            type="text" 
                            name="job-title"
                            placeholder="Job Title" 
                            id="jobTitle_postJob"
                            oninput="postJob_checkJobTitleLive()"
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

                                    echo "<option value='" . $category['category_id'] . "'>"
                                            . $category['category_name'] .
                                         "</option>";
                                }

                            ?>
                        </select>
                        <p> </p>
                    </div>
                    <div class="select-subcategory col-sm-3">
                        <select name="foucsing-on" id="foucsing-on">
                            <option value="">choose category to show</option>
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
                                    name="job-type"
                                    value="full time" 
                                    checked
                                >
                                <label for="full-time">full time</label>
                            </li>
                            <li>
                                <input 
                                    type="radio" 
                                    id="part-time" 
                                    name="job-type"
                                    value="part time"
                                >
                                <label for="part-time">part time</label>
                            </li>
                            <li>
                                <input 
                                    type="radio" 
                                    id="contract" 
                                    name="job-type"
                                    value="contract"
                                >
                                <label for="contract">contract</label>
                            </li>
                            <li>
                                <input 
                                    type="radio" 
                                    id="intership" 
                                    name="job-type"
                                    value="intership"
                                    >
                                <label for="intership">intership</label>
                            </li>
                            <li>
                                <input 
                                    type="radio" 
                                    id="freelance" 
                                    name="job-type"
                                    value="freelance"
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
                                    checked
                                >
                                <label for="one-location">one location</label>
                            </li>
                            <li>
                                <input 
                                    type="radio" 
                                    id="multible-locations" 
                                    name="location"
                                    value="multible locations"
                                >
                                <label for="multible-locations">multible locations</label>
                            </li>
                            <li>
                                <input 
                                    type="radio" 
                                    id="remote" 
                                    name="location"
                                    value="remote"
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
                                    name="salary-range-fixed"
                                    value="range" 
                                    checked
                                >
                                <label for="salary-radio-post-job-range">range</label>
                            </li>
                            <li>
                                <input 
                                    type="radio" 
                                    id="salary-radio-post-job-fixed" 
                                    name="salary-range-fixed"
                                    value="fixed"
                                >
                                <label for="salary-radio-post-job-fixed">fixed</label>
                            </li>
                        </ul>
                        <select name="salary-type">
                            <option value="year">Per Year</option>
                            <option value="month" selected>Per Month</option>
                            <option value="week">Per Week</option>
                            <option value="hour">Per Hour</option>
                        </select>
                        <input 
                            type="text" 
                            name="salary-min"
                            required='required' 
                            oninput="notationSalary()" 
                            onkeypress="return restrictAlphabetes(event)" 
                            maxlength="6" 
                            class="inputSalaryFrom" 
                            placeholder="Form ($)"
                        >
                        <input 
                            type="text"
                            name="salary-max" 
                            required='required' 
                            onkeypress="return restrictAlphabetes(event)" 
                            class="inputSalaryTo" 
                            maxlength="6" 
                            placeholder="To ($)"
                        >
                        <input 
                            type="text"
                            name="salary-static" 
                            onkeypress="return restrictAlphabetes(event)" 
                            class="inputSalaryFixed" 
                            maxlength="6" 
                            placeholder="Salary ($)"
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
                                name="work-shedule-start"
                            >
                                <option value=""> Start of a week </option>
                                <option value="Sunday"> Sunday </option>
                                <option value="Monday"> Monday </option>
                                <option value="Tuesday"> Tuesday </option>
                                <option value="Wedenesday"> Wedenesday </option>
                                <option value="Thursday"> Thursday </option>
                                <option value="Friday"> Friday </option>
                                <option value="Satarday"> Satarday </option>
                            </select>
                        </div>
                        <div class="work-shedule-end col-sm-6">
                            <select 
                                id="work-shedule-end"
                                oninput="postJob_checkWorkSheduleEndLive()" 
                                name="work-shedule-end"
                            >
                                <option value=""> End of a week </option>
                                <option value="Sunday"> Sunday </option>
                                <option value="Monday"> Monday </option>
                                <option value="Tuesday"> Tuesday </option>
                                <option value="Wedenesday"> Wedenesday </option>
                                <option value="Thursday"> Thursday </option>
                                <option value="Friday"> Friday </option>
                                <option value="Satarday"> Satarday </option>
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
                                        checked 
                                    >
                                    <label for="workShudeleTypeDay">day</label>
                                </li>
                                <li> 
                                    <input 
                                        type="radio" 
                                        id="workShudeleTypeWeek" 
                                        name="workShudeleType"
                                        value="week"
                                    >
                                    <label for="workShudeleTypeWeek">week</label>
                                </li>
                            </ul>
                        </div>
                        <div class="work-shedule-hours col-sm-6">
                            <input 
                                type="number" 
                                name="work-shedule-hours"
                                oninput="postJob_checkWorkSheduleHoursLive()" 
                                placeholder="Enter work hours"
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
                                    checked
                                >
                                <label for="post-job-gender-male">male</label>
                            </li>
                            <li>
                                <input 
                                    type="radio" 
                                    id="post-job-gender-female" 
                                    name="gender"
                                    value="female"
                                >
                                <label for="post-job-gender-female">female</label>
                            </li>
                            <li>
                                <input 
                                    type="radio" 
                                    id="post-job-gender-anyone" 
                                    name="gender"
                                    value="anyone"
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
                            name="job-description" 
                            oninput="postJob_checkDescriptionLive()" 
                            id="description-post-job"
                            placeholder="Write description to the job"
                            ></textarea>
                        <div class="input-error">
                            <p> </p>
                        </div>
                        <span> </span>
                    </div>
                </div>

                <button type="submit" name="post-job-button"> post a job </button>

            </div>
        </form>
    </div>
</div>

    <?php } elseif($do == 'insert-post-a-job'){

                if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

                    if( isset( $_POST['post-job-button'] ) ){

                        $employerID       = $_SESSION['companyID'];
                        $jobTitle         = filter_var($_POST['job-title'] , FILTER_SANITIZE_STRING);
                        $category         = $_POST['category'];
                        $foucsing         = $_POST['foucsing-on'];
                        $jobType          = $_POST['job-type'];
                        $location         = $_POST['location'];
                        $salaryRangeFixed = $_POST['salary-range-fixed'];
                        $salaryMin        = $_POST['salary-min'];
                        $salaryMax        = $_POST['salary-max'];
                        $salarystatic     = $_POST['salary-static'];
                        $salaryType       = $_POST['salary-type'];
                        $workSheduleStart = $_POST['work-shedule-start'];
                        $workSheduleEnd   = $_POST['work-shedule-end'];
                        $workShudeleType  = $_POST['workShudeleType'];
                        $workSheduleHours = $_POST['work-shedule-hours'];
                        $gender           = $_POST['gender'];
                        $description      = $_POST['job-description'];

                    
                        if( $salaryRangeFixed == 'range' && $salarystatic == '' ){

                                $quary = $connect->prepare("INSERT INTO job_advertisment(
                                                    employer_id , category_id , f_id , job_title , 
                                                    job_type , salary_min  , salary_max , salary_type , 
                                                    work_shedule_start , work_shedule_end , work_shedule_type ,
                                                    work_shedule_hours , gender , job_description , 
                                                    date_of_publication	, work_location)
                                                    
                                                VALUES(:z_employerID , :z_category , :z_foucsing , :z_jobTitle , 
                                                    :z_jobType , :z_salaryMin , :z_salaryMax , :z_salaryType ,
                                                    :z_workSheduleStart , :z_workSheduleEnd , :z_workSheduleType , 
                                                    :z_workSheduleHours , :z_gender , :z_jobDescription , 
                                                    now() , :z_location)
                                ");

                                $quary->execute(array(
                                        
                                        'z_employerID'        => $employerID ,
                                        'z_category'          => $category ,
                                        'z_foucsing'          => $foucsing ,
                                        'z_jobTitle'          => $jobTitle ,
                                        'z_jobType'           => $jobType ,
                                        'z_salaryMin'         => $salaryMin ,
                                        'z_salaryMax'         => $salaryMax , 
                                        'z_salaryType'        => $salaryType ,
                                        'z_workSheduleStart'  => $workSheduleStart ,
                                        'z_workSheduleEnd'    => $workSheduleEnd , 
                                        'z_workSheduleType'   => $workShudeleType ,
                                        'z_workSheduleHours'  => $workSheduleHours ,
                                        'z_gender'            => $gender , 
                                        'z_jobDescription'    => $description ,
                                        'z_location'          => $location
                                ));

                                echo '<div class="full-page-alerts with-navbar">';
                                    echo '<div class="content-alerts">';
                                        redirectPage('success' , 'advirtisment posted successfully');
                                        redirectPage('info' , 'redirect post job page wihtin 3 seconds' , 'post a job.php' , '3');
                                    echo '</div>';
                                echo '</div>';


                        } else if( $salaryRangeFixed == 'fixed' && $salaryMin == '' && $salaryMax == '' ){

                            $quary = $connect->prepare("INSERT INTO job_advertisment(
                                                        employer_id , category_id , f_id , job_title , 
                                                        job_type , salary_static , salary_type , 
                                                        work_shedule_start , work_shedule_end , work_shedule_type ,
                                                        work_shedule_hours , gender , job_description , 
                                                        date_of_publication	, work_location)
                                
                                            VALUES(:z_employerID , :z_category , :z_foucsing , :z_jobTitle , 
                                                :z_jobType , :z_salaryStatic , :z_salaryType ,
                                                :z_workSheduleStart , :z_workSheduleEnd , :z_workSheduleType , 
                                                :z_workSheduleHours , :z_gender , :z_jobDescription , 
                                                now() , :z_location)
                                ");

                                $quary->execute(array(
                                                            
                                        'z_employerID'        => $employerID ,
                                        'z_category'          => $category ,
                                        'z_foucsing'          => $foucsing ,
                                        'z_jobTitle'          => $jobTitle ,
                                        'z_jobType'           => $jobType ,
                                        'z_salaryStatic'      => $salarystatic , 
                                        'z_salaryType'        => $salaryType ,
                                        'z_workSheduleStart'  => $workSheduleStart ,
                                        'z_workSheduleEnd'    => $workSheduleEnd , 
                                        'z_workSheduleType'   => $workShudeleType ,
                                        'z_workSheduleHours'  => $workSheduleHours ,
                                        'z_gender'            => $gender , 
                                        'z_jobDescription'    => $description ,
                                        'z_location'          => $location
                                ));

                                echo '<div class="full-page-alerts with-navbar">';
                                    echo '<div class="content-alerts">';
                                        redirectPage('success' , 'advirtisment posted successfully');
                                        redirectPage('info' , 'redirect post job page wihtin 3 seconds' , 'post a job.php' , '3');
                                    echo '</div>';
                                echo '</div>';


                        }
                    }
                }
            } 
    
    ?>
<?php
    include $tmp . 'footer.php';
    ob_end_flush();
?>