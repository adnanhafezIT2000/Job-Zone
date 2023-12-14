$(function () {

    $('.check_username_jobSeeker').keyup(function (e) {

        var usernamejobSeeker = $('.check_username_jobSeeker').val();
        
        $.ajax({

            type: "POST" , 
            url: "jobSeekerUsernameCheckLive.php" , 
            data: {
                "check_submit_button": 1,
                "username_jobseeker_id": usernamejobSeeker ,
            } , 
            success: function (response) {
                $('.error_username').text(response);
            }

        });
    });

    $('.check_email_jobSeeker').keyup(function (e) {

        var emailjobSeeker = $('.check_email_jobSeeker').val();
        
        $.ajax({

            type: "POST" , 
            url: "jobSeekerEmailCheckLive.php" , 
            data: {
                "check_submit_button": 1,
                "email_jobseeker_id": emailjobSeeker ,
            } , 
            success: function (response) {
                $('.error_email').text(response);
            }

        });
    });

    $('.check_username_employer').keyup(function (e) {

        var usernameEmployer = $('.check_username_employer').val();
        
        $.ajax({

            type: "POST" , 
            url: "employerUsernameCheckLive.php" , 
            data: {
                "check_submit_button": 1,
                "username_employer_id": usernameEmployer ,
            } , 
            success: function (response) {
                $('.error_username_employer').text(response);
            }

        });
    });

    $('.check_email_employer').keyup(function (e) {

        var emailEmployer = $('.check_email_employer').val();
        
        $.ajax({

            type: "POST" , 
            url: "employerEmailCheckLive.php" , 
            data: {
                "check_submit_button": 1,
                "email_employer_id": emailEmployer ,
            } , 
            success: function (response) {
                $('.error_email_employer').text(response);
            }

        });
    });
    // (Start) Hide Placeholder On Form Focus
        $('[placeholder]').focus(function () {

            $(this).attr('data-text' , $(this).attr('placeholder'));

            $(this).attr('placeholder' , '');

        }).blur(function () {

                $(this).attr('placeholder' , $(this).attr('data-text'));
        });
    // (End) Hide Placeholder On Form Focus
   
    
    $('.confirm').click(function(){

        return confirm('Are You Sure ?');
    });

    $("#category").change(function(){

        cid = $(this).val();
        $.post("foucsing-on.php" , {id:cid} , function(data){

            $("#foucsing-on").html(data);
        });
    });

});




let icon3Point = document.querySelectorAll(".manageMyAds .container .row .Advirtisment .icon-3-point");
let controlDivs = document.querySelectorAll(".manageMyAds .container .row .Advirtisment .control");

controlDivs.forEach((controlDiv , index) => {

        console.log(controlDiv);
        console.log(index);

        icon3Point[index].onclick = function(){

            controlDiv.classList.toggle("deactive-control-div");

            closeControlDivs(index);
        }
});

function closeControlDivs(index1){

        controlDivs.forEach((item2 , index2) => {

            if( index1 != index2 ){

                item2.classList.add("deactive-control-div");
            }
        })
};

/*---------------------------------------
    Button Scroll To Top            
-----------------------------------------*/
let buttonToTopScroll = document.getElementById("btn_scroll");

window.onscroll = function(){

    if(window.pageYOffset >= 700){

            if(buttonToTopScroll){
                buttonToTopScroll.style.display = "block";
            }
    }
    else{
            if(buttonToTopScroll){
                buttonToTopScroll.style.display = "none";
            }
    }
}

if(buttonToTopScroll){

    buttonToTopScroll.onclick = function(){
        window.scrollTo(0 , 0);
    }
}

/* Input Radio Sign Up */

let labelJobSeeker = document.querySelector(".sign-up-page .form-section .container form .job-seeker-radio label");
let divJobSeeker   = document.querySelector(".sign-up-page .form-section .container form .job-seeker-radio");
let textJobSeeker  = document.querySelector(".sign-up-page .form-section .container form .job-seeker-radio #textJobSeeker");
let radioJobSeeker = document.querySelector(".sign-up-page .form-section .container form .job-seeker-radio #job-seeker");

let labelEmployer  = document.querySelector(".sign-up-page .form-section .container form .employer-radio label");
let divEmployer    = document.querySelector(".sign-up-page .form-section .container form .employer-radio");
let textEmployer   = document.querySelector(".sign-up-page .form-section .container form .employer-radio #textEmployer");
let radioEmployer  = document.querySelector(".sign-up-page .form-section .container form .employer-radio #employer");

if(labelJobSeeker){

    labelJobSeeker.onclick = function(){

        divJobSeeker.style.cssText = `box-shadow: 0 0 8px 0 var(--main-blue-color); 
                                      border-color:var(--main-blue-color);`;
        divEmployer.style.cssText  = `box-shadow: none;`;
    }
}
if(textJobSeeker){

    textJobSeeker.onclick = function(){

        divJobSeeker.style.cssText = `box-shadow: 0 0 8px 0 var(--main-blue-color); 
                                      border-color:var(--main-blue-color);`;
        divEmployer.style.cssText  = `box-shadow: none;`;
    }
}
if(radioJobSeeker){

    radioJobSeeker.onclick = function(){

        divJobSeeker.style.cssText = `box-shadow: 0 0 8px 0 var(--main-blue-color); 
                                      border-color:var(--main-blue-color);`;
        divEmployer.style.cssText  = `box-shadow: none;`;
    }
}

if(labelEmployer){

    labelEmployer.onclick = function(){

        divEmployer.style.cssText = `box-shadow: 0 0 8px 0 var(--main-blue-color);
                                     border-color:var(--main-blue-color);`;
        divJobSeeker.style.cssText  = `box-shadow: none;`;
    }
}
if(textEmployer){

    textEmployer.onclick = function(){

        divEmployer.style.cssText = `box-shadow: 0 0 8px 0 var(--main-blue-color);
                                     border-color:var(--main-blue-color);`;
        divJobSeeker.style.cssText  = `box-shadow: none;`;
    }
}
if(radioEmployer){

    radioEmployer.onclick = function(){

        divEmployer.style.cssText = `box-shadow: 0 0 8px 0 var(--main-blue-color);
                                     border-color:var(--main-blue-color);`;
        divJobSeeker.style.cssText  = `box-shadow: none;`;
    }
}


/*------------------------------------------
    Validation Form Sign-In Job Seeker           
--------------------------------------------*/

let formJobSeeker = document.querySelector(".sign-up-job-seeker .container .row .form .container form");


let jobSeeker_username = document.getElementById("job-seeker-username");
let jobSeeker_usernameError = document.querySelector("#job-seeker-username + p");
function jobSeeker_checkUsernameLive(){

    if( jobSeeker_username.value.length < 3 ){
            jobSeeker_username.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            jobSeeker_username.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            jobSeeker_usernameError.style.cssText = `display:none`;
    }

}

let jobSeeker_password = document.getElementById("job-seeker-password");
let jobSeeker_passwordError = document.querySelector("#job-seeker-password ~ p");
function jobSeeker_checkPasswordLive(){

    if( jobSeeker_password.value.length < 8 ){
            jobSeeker_password.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            jobSeeker_password.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            jobSeeker_passwordError.style.cssText = `display:none`;
    }
}

let jobSeeker_fullName = document.getElementById("job-seeker-fullName");
let jobSeeker_fullNameError = document.querySelector("#job-seeker-fullName + p");
function jobSeeker_checkFullNameLive(){

    if( jobSeeker_fullName.value === '' || jobSeeker_fullName.value == null ){
            jobSeeker_fullName.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            jobSeeker_fullName.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            jobSeeker_fullNameError.style.cssText = `display:none`;
    }
}

let jobSeeker_email = document.getElementById("job-seeker-email");
let jobSeeker_emailError = document.querySelector("#job-seeker-email + p");
let jobSeeker_regExp = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
function jobSeeker_checkEmailLive(){

    if(jobSeeker_email.value.match(jobSeeker_regExp)){

        jobSeeker_email.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
        jobSeeker_emailError.style.cssText = `display:none`;

    } else{

        jobSeeker_email.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    }
}

let jobSeeker_date = document.getElementById("job-seeker-date");
let jobSeeker_dateError = document.querySelector("#job-seeker-date + p");
function jobSeeker_checkDateLive(){

    if( jobSeeker_date.value === '' || jobSeeker_date.value == null ){
            jobSeeker_date.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            jobSeeker_date.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            jobSeeker_dateError.style.cssText = `display:none`;
    }
}

let jobSeeker_phone = document.getElementById("job-seeker-phone");
let jobSeeker_phoneError = document.querySelector("#job-seeker-phone + p");
let jobSeeker_regExp_phone = /^[\d,\s,\+,\-]{8,20}/;
function jobSeeker_checkPhoneLive(){

    if(jobSeeker_phone.value.match(jobSeeker_regExp_phone)){

        jobSeeker_phone.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
        jobSeeker_phoneError.style.cssText = `display:none`;

    } else{

        jobSeeker_phone.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    }
}

let jobSeeker_address = document.getElementById("job-seeker-address");
let jobSeeker_addressError = document.querySelector("#job-seeker-address + p");
function jobSeeker_checkAddressLive(){

    if( jobSeeker_address.value === '' || jobSeeker_address.value == null ){
            jobSeeker_address.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            jobSeeker_address.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            jobSeeker_addressError.style.cssText = `display:none`;
    }
}

let jobSeeker_category   = document.getElementById("category");
let jobSeeker_foucsingOn = document.getElementById("foucsing-on")
function jobSeeker_checkCategoryLive(){

    if( jobSeeker_category.value === '' || jobSeeker_category.value == null ){
            jobSeeker_category.style.cssText   = `box-shadow: 0 0 6px 0 red;`;
            jobSeeker_foucsingOn.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            jobSeeker_category.style.cssText   = `box-shadow: 0 0 8px 0 #31eb31;`;
            jobSeeker_foucsingOn.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
    }
}


if( formJobSeeker ){


        formJobSeeker.addEventListener('submit' ,  (event) => {

        let jobSeeker_notErrors = true;

        /* Username Verification */
           if(jobSeeker_username.value === '' || jobSeeker_username.value == null){

                    jobSeeker_username.style.cssText = `border-color: #f2dede; 
                                            box-shadow: 0 0 6px 0 red;`
                    jobSeeker_usernameError.innerText = "Username is required";
                    event.preventDefault();
                    jobSeeker_notErrors = false;
            } 
            if(jobSeeker_username.value !== '' && jobSeeker_username.value.length < 3){

                    jobSeeker_username.style.cssText = `border-color: #f2dede; 
                                            box-shadow: 0 0 6px 0 red;`
                    jobSeeker_usernameError.innerText = "Minimum 3 characters";
                    event.preventDefault();
                    jobSeeker_notErrors = false;
            }
    
        /* Password Verification */
           if(jobSeeker_password.value === '' || jobSeeker_password.value == null){

                    jobSeeker_password.style.cssText = `border-color: #f2dede; 
                                            box-shadow: 0 0 6px 0 red;`;
                    jobSeeker_passwordError.innerText = "Password is required";
                    event.preventDefault();
                    jobSeeker_notErrors = false;
            } 
            if(jobSeeker_password.value !== '' && jobSeeker_password.value.length < 8){

                    jobSeeker_password.style.cssText = `border-color: #f2dede; 
                                            box-shadow: 0 0 6px 0 red;`;
                    jobSeeker_passwordError.innerText = "Minimum 8 characters";
                    event.preventDefault();
                    jobSeeker_notErrors = false;
            }

        /* Email Verification */
            if(jobSeeker_email.value === '' || jobSeeker_email.value == null){

                    jobSeeker_email.style.cssText = `border-color: #f2dede; 
                                        box-shadow: 0 0 6px 0 red;`
                    jobSeeker_emailError.innerText = "Email is required";
                    event.preventDefault();
                    jobSeeker_notErrors = false;
            } 
            if(jobSeeker_email.value.match(jobSeeker_regExp)){
        
            } else{
        
                    jobSeeker_email.style.cssText = `border-color: #f2dede; 
                                        box-shadow: 0 0 6px 0 red;`
                    jobSeeker_emailError.innerText = "Email is required";
                    event.preventDefault();
                    jobSeeker_notErrors = false;
            }
    
        /* FullName Verification */
            if(jobSeeker_fullName.value === '' || jobSeeker_fullName.value == null){

                    jobSeeker_fullName.style.cssText = `border-color: #f2dede; 
                                            box-shadow: 0 0 6px 0 red;`
                    jobSeeker_fullNameError.innerText = "Full name is required";
                    event.preventDefault();
                    jobSeeker_notErrors = false;
            } 

        /* Date Verification */
            if(jobSeeker_date.value === '' || jobSeeker_date.value == null){

                    jobSeeker_date.style.cssText = `border-color: #f2dede; 
                                            box-shadow: 0 0 6px 0 red;`
                    jobSeeker_dateError.innerText = "Date of birth is required";
                    event.preventDefault();
                    jobSeeker_notErrors = false;
            } 

        /* Phone Verification */
           if(jobSeeker_phone.value === '' || jobSeeker_phone.value == null){

                    jobSeeker_phone.style.cssText = `border-color: #f2dede; 
                                            box-shadow: 0 0 6px 0 red;`
                    jobSeeker_phoneError.innerText = "Phone is required";
                    event.preventDefault();
                    jobSeeker_notErrors = false;
            } 
            if(jobSeeker_phone.value.match(jobSeeker_regExp_phone)){
        
            } else{
        
                    jobSeeker_phone.style.cssText = `border-color: #f2dede; 
                                        box-shadow: 0 0 6px 0 red;`
                    jobSeeker_phoneError.innerText = "Phone number is required";
                    event.preventDefault();
                    jobSeeker_notErrors = false;
            }
    
        /* Address Verification */
           if(jobSeeker_address.value === '' || jobSeeker_address.value == null){

                    jobSeeker_address.style.cssText = `border-color: #f2dede; 
                                            box-shadow: 0 0 6px 0 red;`
                    jobSeeker_addressError.innerText = "Address is required";
                    event.preventDefault();
                    jobSeeker_notErrors = false;
            } 

        /* Category Verification */
            if(jobSeeker_category.value === '' || jobSeeker_category.value == null){

                    jobSeeker_category.style.cssText = `box-shadow: 0 0 6px 0 red;`
                    event.preventDefault();
                    jobSeeker_notErrors = false;
            }

        /* Check Not Errors */
        if(jobSeeker_notErrors == true){

            console.log("INSERT DATA BASE SUCCESSFULLY !!!!");
        }
    });
}

/*------------------------------------------
    Validation Form Sign-In Employer          
--------------------------------------------*/

let formEmployer = document.querySelector(".sign-up-employer .container .row .form .container form");

let Employer_username = document.getElementById("employer-username");
let Employer_usernameError = document.querySelector("#employer-username + p");
function Employer_checkUsernameLive(){

    if( Employer_username.value.length < 3 ){
            Employer_username.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            Employer_username.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            Employer_usernameError.style.cssText = `display:none`;
    }
}

let Employer_password = document.getElementById("employer-password");
let Employer_passwordError = document.querySelector("#employer-password ~ p");
function Employer_checkPasswordLive(){

    if( Employer_password.value.length < 8 ){
            Employer_password.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            Employer_password.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            Employer_passwordError.style.cssText = `display:none`;
    }
}

let Employer_email = document.getElementById("employer-email");
let Employer_emailError = document.querySelector("#employer-email + p");
let Employer_regExp = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
function Employer_checkEmailLive(){

    if(Employer_email.value.match(Employer_regExp)){

        Employer_email.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
        Employer_emailError.style.cssText = `display:none`;

    } else{

        Employer_email.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    }
}

let Employer_name = document.getElementById("employer-name");
let Employer_nameError = document.querySelector("#employer-name + p");
function Employer_checkNameLive(){

    if( Employer_name.value === '' || Employer_name.value == null ){
            Employer_name.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            Employer_name.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            Employer_nameError.style.cssText = `display:none`;
    }
}

let Employer_phone = document.getElementById("employer-phone");
let Employer_phoneError = document.querySelector("#employer-phone + p");
let Employer_regExp_phone = /^[\d,\s,\+,\-]{8,20}/;
function Employer_checkPhoneLive(){

    if(Employer_phone.value.match(Employer_regExp_phone)){

        Employer_phone.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
        Employer_phoneError.style.cssText = `display:none`;

    } else{

        Employer_phone.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    }
}

let Employer_address = document.getElementById("employer-address");
let Employer_addressError = document.querySelector("#employer-address + p");
function Employer_checkAddressLive(){

    if( Employer_address.value === '' || Employer_address.value == null ){
            Employer_address.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            Employer_address.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            Employer_addressError.style.cssText = `display:none`;
    }
}

let Employer_description        = document.getElementById("employer-description");
let Employer_descriptionError   = document.querySelector("#employer-description + p");
let Employer_descriptionCounter = document.querySelector("#employer-description ~ span");
function LetterCount(string){

    let array = string.split("");
    let count;
    for( count = 0 ; count < array.length ; count++ ){

            array[count];
    }
    return `${count} / 50`;
}

function Employer_checkDescriptionLive(){

    Employer_descriptionCounter.textContent = LetterCount(Employer_description.value);

    if( Employer_description.value === '' || Employer_description.value == null ){
            Employer_description.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            Employer_descriptionCounter.style.cssText = `color:red`;

    } else if(Employer_description.value.length < 50 || LetterCount(Employer_description.value) < 50){

            Employer_description.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            Employer_descriptionCounter.style.cssText = `color:red`;
                                 
    } else{

            Employer_description.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            Employer_descriptionCounter.style.cssText = `color:#31eb31;`;
            Employer_descriptionError.style.cssText = `display:none`;
    }

}

if( formEmployer ){

    formEmployer.addEventListener('submit' ,  (event) => {

    let Employer_notErrors = true;

    /* Username Verification */
        if(Employer_username.value === '' || Employer_username.value == null){

                Employer_username.style.cssText = `border-color: #f2dede; 
                                        box-shadow: 0 0 6px 0 red;`
                Employer_usernameError.innerText = "Username is required";
                event.preventDefault();
                Employer_notErrors = false;
        } 
        if(Employer_username.value !== '' && Employer_username.value.length < 3){

                Employer_username.style.cssText = `border-color: #f2dede; 
                                        box-shadow: 0 0 6px 0 red;`
                Employer_usernameError.innerText = "Minimum 3 characters";
                event.preventDefault();
                Employer_notErrors = false;
        }

    /* Password Verification */
        if(Employer_password.value === '' || Employer_password.value == null){

                Employer_password.style.cssText = `border-color: #f2dede; 
                                        box-shadow: 0 0 6px 0 red;`;
                Employer_passwordError.innerText = "Password is required";
                event.preventDefault();
                Employer_notErrors = false;
        } 
        if(Employer_password.value !== '' && Employer_password.value.length < 8){

                Employer_password.style.cssText = `border-color: #f2dede; 
                                        box-shadow: 0 0 6px 0 red;`;
                Employer_passwordError.innerText = "Minimum 8 characters";
                event.preventDefault();
                Employer_notErrors = false;
        }

    /* Email Verification */
        if(Employer_email.value === '' || Employer_email.value == null){

                Employer_email.style.cssText = `border-color: #f2dede; 
                                        box-shadow: 0 0 6px 0 red;`
                Employer_emailError.innerText = "Email is required";
                event.preventDefault();
                Employer_notErrors = false;
        } 
        if(Employer_email.value.match(Employer_regExp)){
    
        } else{
    
                Employer_email.style.cssText = `border-color: #f2dede; 
                                    box-shadow: 0 0 6px 0 red;`
                Employer_emailError.innerText = "Email is required";
                event.preventDefault();
                Employer_notErrors = false;
        }

    /* FullName Verification */
        if(Employer_name.value === '' || Employer_name.value == null){

                Employer_name.style.cssText = `border-color: #f2dede; 
                                            box-shadow: 0 0 6px 0 red;`
                Employer_nameError.innerText = "Full name is required";
                event.preventDefault();
                Employer_notErrors = false;
        } 

    /* Phone Verification */
        if(Employer_phone.value === '' || Employer_phone.value == null){

                Employer_phone.style.cssText = `border-color: #f2dede; 
                                                box-shadow: 0 0 6px 0 red;`
                Employer_phoneError.innerText = "Phone is required";
                event.preventDefault();
                Employer_notErrors = false;
        } 
        if(Employer_phone.value.match(Employer_regExp_phone)){

        } else{

                Employer_phone.style.cssText = `border-color: #f2dede; 
                                                box-shadow: 0 0 6px 0 red;`
                Employer_phoneError.innerText = "Phone number is required";
                event.preventDefault();
                Employer_notErrors = false;
        }

        /* Address Verification */
            if(Employer_address.value === '' || Employer_address.value == null){

                Employer_address.style.cssText = `border-color: #f2dede; 
                                            box-shadow: 0 0 6px 0 red;`
                Employer_addressError.innerText = "Address is required";
                event.preventDefault();
                Employer_notErrors = false;
            } 
        /* Description Verification */
            if(Employer_description.value === '' || Employer_description.value == null){

                Employer_description.style.cssText = `border-color: #f2dede; 
                                            box-shadow: 0 0 6px 0 red;`
                Employer_descriptionError.innerText = "Description is required";
                event.preventDefault();
                Employer_notErrors = false;

            } else if(Employer_description.value.length < 50 || LetterCount(Employer_description.value) < 50){

                Employer_description.style.cssText = `box-shadow: 0 0 6px 0 red;`;
                Employer_descriptionCounter.style.cssText = `color:red`;    
                Employer_descriptionError.innerText = "Minimum 50 characters";

            }
    });
}

/*---------------------------------------------
    Validation Form Profile General Job Seeker          
-----------------------------------------------*/

let formProfileGeneralJobSeeker = document.querySelector(".edit-profile-information .row .right-section .container form");

let profileGeneralJobSeeker_fullName = document.getElementById("profileGenderalJobSeeker_fullName");
let profileGeneralJobSeeker_fullNameError = document.querySelector("#profileGenderalJobSeeker_fullName + p");
function profileGeneralJobSeeker_checkFullNameLive(){

    if( profileGeneralJobSeeker_fullName.value === '' || profileGeneralJobSeeker_fullName.value == null ){
            profileGeneralJobSeeker_fullName.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            profileGeneralJobSeeker_fullName.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            profileGeneralJobSeeker_fullNameError.style.cssText = `display:none`;
    }
}

let profileGenderalJobSeeker_email = document.getElementById("profileGenderalJobSeeker_email");
let profileGenderalJobSeeker_emailError = document.querySelector("#profileGenderalJobSeeker_email + p");
let profileGenderalJobSeeker_email_regExp = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
function profileGeneralJobSeeker_checkEmailLive(){

    if(profileGenderalJobSeeker_email.value.match(profileGenderalJobSeeker_email_regExp)){

        profileGenderalJobSeeker_email.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
        profileGenderalJobSeeker_emailError.style.cssText = `display:none`;

    } else{

        profileGenderalJobSeeker_email.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    }
}

let profileGenderalJobSeeker_phone = document.getElementById("profileGenderalJobSeeker_phone");
let profileGenderalJobSeeker_phoneError = document.querySelector("#profileGenderalJobSeeker_phone + p");
let profileGenderalJobSeeker_phone_regExp = /^[\d,\s,\+,\-]{8,20}/;
function profileGeneralJobSeeker_checkPhoneLive(){

    if(profileGenderalJobSeeker_phone.value.match(profileGenderalJobSeeker_phone_regExp)){

        profileGenderalJobSeeker_phone.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
        profileGenderalJobSeeker_phoneError.style.cssText = `display:none`;

    } else{

        profileGenderalJobSeeker_phone.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    }
}

let profileGenderalJobSeeker_address = document.getElementById("profileGenderalJobSeeker_address");
let profileGenderalJobSeeker_addressError = document.querySelector("#profileGenderalJobSeeker_address + p");
function profileGeneralJobSeeker_checkAddressLive(){

    if( profileGenderalJobSeeker_address.value === '' || profileGenderalJobSeeker_address.value == null ){
            profileGenderalJobSeeker_address.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            profileGenderalJobSeeker_address.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            profileGenderalJobSeeker_addressError.style.cssText = `display:none`;
    }
}


let profileGenderalJobSeeker_date = document.getElementById("profileGenderalJobSeeker_date");
let profileGenderalJobSeeker_dateError = document.querySelector("#profileGenderalJobSeeker_date + p");
function profileGeneralJobSeeker_checkDateLive(){

    if( profileGenderalJobSeeker_date.value === '' || profileGenderalJobSeeker_date.value == null ){
            profileGenderalJobSeeker_date.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            profileGenderalJobSeeker_date.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            profileGenderalJobSeeker_dateError.style.cssText = `display:none`;
    }
}

let profileGenderalJobSeeker_category  = document.getElementById("category");
let profileGenderalJobSeeker_foucsingOn = document.getElementById("foucsing-on")
function profileGeneralJobSeeker_checkCategoryLive(){

    if( profileGenderalJobSeeker_category.value === '' || profileGenderalJobSeeker_category.value == null ){
            profileGenderalJobSeeker_category.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            profileGenderalJobSeeker_foucsingOn.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            profileGenderalJobSeeker_category.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            profileGenderalJobSeeker_foucsingOn.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
    }
}

if( formProfileGeneralJobSeeker ){

    formProfileGeneralJobSeeker.addEventListener('submit' ,  (event) => {

        let formProfileGeneralJobSeeker_notErrors = true;

         /* FullName Verification */
         if(profileGeneralJobSeeker_fullName.value === '' || profileGeneralJobSeeker_fullName.value == null){

            profileGeneralJobSeeker_fullName.style.cssText = `border-color: #f2dede; 
                                                              box-shadow: 0 0 6px 0 red;`
            profileGeneralJobSeeker_fullNameError.innerText = "Full name is required";
            event.preventDefault();
            formProfileGeneralJobSeeker_notErrors = false;
        } 

        /* Email Verification */
            if(profileGenderalJobSeeker_email.value === '' || profileGenderalJobSeeker_email.value == null){

                    profileGenderalJobSeeker_email.style.cssText = `border-color: #f2dede; 
                                                                    box-shadow: 0 0 6px 0 red;`
                    profileGenderalJobSeeker_emailError.innerText = "Email is required";
                    event.preventDefault();
                    formProfileGeneralJobSeeker_notErrors = false;
            } 
            if(profileGenderalJobSeeker_email.value.match(profileGenderalJobSeeker_email_regExp)){
        
            } else{
        
                    profileGenderalJobSeeker_email.style.cssText = `border-color: #f2dede; 
                                                                    box-shadow: 0 0 6px 0 red;`
                    profileGenderalJobSeeker_emailError.innerText = "Email is required";
                    event.preventDefault();
                    formProfileGeneralJobSeeker_notErrors = false;
            }

            /* Phone Verification */
            if(profileGenderalJobSeeker_phone.value === '' || profileGenderalJobSeeker_phone.value == null){

                profileGenderalJobSeeker_phone.style.cssText = `border-color: #f2dede; 
                                                                box-shadow: 0 0 6px 0 red;`
                profileGenderalJobSeeker_phoneError.innerText = "Phone is required";
                event.preventDefault();
                formProfileGeneralJobSeeker_notErrors = false;
        } 
        if(profileGenderalJobSeeker_phone.value.match(profileGenderalJobSeeker_phone_regExp)){

        } else{

                profileGenderalJobSeeker_phone.style.cssText = `border-color: #f2dede; 
                                                                box-shadow: 0 0 6px 0 red;`
                profileGenderalJobSeeker_phoneError.innerText = "Phone number is required";
                event.preventDefault();
                formProfileGeneralJobSeeker_notErrors = false;
        }

        /* Address Verification */
        if(profileGenderalJobSeeker_address.value === '' || profileGenderalJobSeeker_address.value == null){

            profileGenderalJobSeeker_address.style.cssText = `border-color: #f2dede; 
                                    box-shadow: 0 0 6px 0 red;`
            profileGenderalJobSeeker_addressError.innerText = "Address is required";
            event.preventDefault();
            formProfileGeneralJobSeeker_notErrors = false;
        } 

        /* Date Verification */
            if(profileGenderalJobSeeker_date.value === '' || profileGenderalJobSeeker_date.value == null){

                    profileGenderalJobSeeker_date.style.cssText = `border-color: #f2dede; 
                                                                   box-shadow: 0 0 6px 0 red;`
                    profileGenderalJobSeeker_dateError.innerText = "Date of birth is required";
                    event.preventDefault();
                    formProfileGeneralJobSeeker_notErrors = false;
            } 

        /* Category Verification */
            if(profileGenderalJobSeeker_category.value === '' || profileGenderalJobSeeker_category.value == null){

                    profileGenderalJobSeeker_category.style.cssText = `box-shadow: 0 0 6px 0 red;`
                    event.preventDefault();
                    formProfileGeneralJobSeeker_notErrors = false;
            }

        /* Check Not Errors */
        if(formProfileGeneralJobSeeker_notErrors == true){

            console.log("INSERT DATA BASE SUCCESSFULLY !!!!");
        }
    });
}
///////////////////////////////////////////////////////////////////////////////
let formProfileAccountUsernameJobSeeker = document.getElementById("formProfileAccountUsernameJobSeeker");

let formProfileAccountJobSeeker_username      = document.getElementById("profileAccountJobSeeker_username");
let formProfileAccountJobSeeker_usernameError = document.querySelector("#profileAccountJobSeeker_username + p");
function formProfileAccountJobSeeker_checkUsernameLive(){

    if( formProfileAccountJobSeeker_username.value.length < 3 ){
            formProfileAccountJobSeeker_username.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            formProfileAccountJobSeeker_username.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            formProfileAccountJobSeeker_usernameError.style.cssText = `display:none`;
    }
}

if( formProfileAccountUsernameJobSeeker ){

    formProfileAccountUsernameJobSeeker.addEventListener('submit',  (event) => {

        /* Username Verification */
        if(formProfileAccountJobSeeker_username.value === '' || formProfileAccountJobSeeker_username.value == null){

                formProfileAccountJobSeeker_username.style.cssText = `border-color: #f2dede; 
                                                                    box-shadow: 0 0 6px 0 red;`
                formProfileAccountJobSeeker_usernameError.innerText = "Username is required";
                event.preventDefault();
        } 
        if(formProfileAccountJobSeeker_username.value !== '' && formProfileAccountJobSeeker_username.value.length < 3){

                formProfileAccountJobSeeker_username.style.cssText = `border-color: #f2dede; 
                                            box-shadow: 0 0 6px 0 red;`
                formProfileAccountJobSeeker_usernameError.innerText = "Minimum 3 characters";
                event.preventDefault();
        }
    });
}
//////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////
let formProfileAccountPasswordJobSeeker = document.getElementById("formProfileAccountPasswordJobSeeker");

let formProfilePasswordJobSeeker_current      = document.getElementById("profileAccountJobSeeker_currentPassword");
let formProfilePasswordJobSeeker_currentError = document.querySelector("#profileAccountJobSeeker_currentPassword ~ p");

let formProfilePasswordJobSeeker_new      = document.getElementById("profileAccountJobSeeker_newPassword");
let formProfilePasswordJobSeeker_newError = document.querySelector("#profileAccountJobSeeker_newPassword ~ p");

let formProfilePasswordJobSeeker_confirm      = document.getElementById("profileAccountJobSeeker_confirmNewPassword");
let formProfilePasswordJobSeeker_confirmError = document.querySelector("#profileAccountJobSeeker_confirmNewPassword ~ p");

function formProfilePasswordJobSeeker_checkLiveCurrent(){

    formProfilePasswordJobSeeker_current.style.cssText = `box-shadow:none`;
    formProfilePasswordJobSeeker_currentError.style.cssText = `visibility: hidden;`;
}

function formProfilePasswordJobSeeker_checkLiveNew(){

    if( formProfilePasswordJobSeeker_new.value.length < 8 ){
            formProfilePasswordJobSeeker_new.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            formProfilePasswordJobSeeker_newError.innerText = `Minimum 8 chars`;
            formProfilePasswordJobSeeker_newError.style.cssText = `visibility: visible;`;
    } else{
            formProfilePasswordJobSeeker_new.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            formProfilePasswordJobSeeker_newError.style.cssText = `visibility: hidden;`;
    }

    if(formProfilePasswordJobSeeker_confirm.value === formProfilePasswordJobSeeker_new.value && formProfilePasswordJobSeeker_confirm.value != "" && formProfilePasswordJobSeeker_new.value != ""){

            formProfilePasswordJobSeeker_confirm.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            formProfilePasswordJobSeeker_confirmError.style.cssText = `visibility: hidden;`;
    }
    if( formProfilePasswordJobSeeker_confirm.value != formProfilePasswordJobSeeker_new.value ){

        formProfilePasswordJobSeeker_confirm.style.cssText = `box-shadow: 0 0 6px 0 red;`;
        formProfilePasswordJobSeeker_confirmError.innerText = `Password doesn't match`;
        formProfilePasswordJobSeeker_confirmError.style.cssText = `visibility: visible;`;

    }
}

function formProfilePasswordJobSeeker_checkLiveConfirm(){

    if( formProfilePasswordJobSeeker_confirm.value != formProfilePasswordJobSeeker_new.value ){

        formProfilePasswordJobSeeker_confirm.style.cssText = `box-shadow: 0 0 6px 0 red;`;
        formProfilePasswordJobSeeker_confirmError.innerText = `Password doesn't match`;
        formProfilePasswordJobSeeker_confirmError.style.cssText = `visibility: visible;`;

    } else{
        formProfilePasswordJobSeeker_confirm.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
        formProfilePasswordJobSeeker_confirmError.style.cssText = `visibility: hidden;`;
    }
}

if( formProfileAccountPasswordJobSeeker ){

    formProfileAccountPasswordJobSeeker.addEventListener('submit',  (event) => {

        if(formProfilePasswordJobSeeker_current.value === '' || formProfilePasswordJobSeeker_current.value == null){

            formProfilePasswordJobSeeker_current.style.cssText = `border-color: #f2dede; 
                                                                  box-shadow: 0 0 6px 0 red;`
            formProfilePasswordJobSeeker_currentError.innerText = "Current password is required";
            formProfilePasswordJobSeeker_currentError.style.cssText = `visibility: visible;`
            event.preventDefault();
        } 
        if(formProfilePasswordJobSeeker_new.value === '' || formProfilePasswordJobSeeker_new.value == null){

            formProfilePasswordJobSeeker_new.style.cssText = `border-color: #f2dede; 
                                                                  box-shadow: 0 0 6px 0 red;`
            formProfilePasswordJobSeeker_newError.innerText = "New password is required";
            event.preventDefault();
        } 
        if(formProfilePasswordJobSeeker_confirm.value === '' || formProfilePasswordJobSeeker_confirm.value == null){

            formProfilePasswordJobSeeker_confirm.style.cssText = `border-color: #f2dede; 
                                                                  box-shadow: 0 0 6px 0 red;`
            formProfilePasswordJobSeeker_confirmError.innerText = "Confirm password is required";
            event.preventDefault();
        } 
        if(formProfilePasswordJobSeeker_confirm.value !== formProfilePasswordJobSeeker_new.value){

            formProfilePasswordJobSeeker_confirm.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            formProfilePasswordJobSeeker_confirmError.innerText = `Password doesn't match`;
            formProfilePasswordJobSeeker_confirmError.style.cssText = `visibility: visible;`;
            event.preventDefault();
        } 
        if(formProfilePasswordJobSeeker_new.value.length < 8 && formProfilePasswordJobSeeker_new.value !== ''){

            formProfilePasswordJobSeeker_new.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            formProfilePasswordJobSeeker_newError.innerText = `Minimum 8 chars`;
            formProfilePasswordJobSeeker_newError.style.cssText = `visibility: visible;`;
            event.preventDefault();
        }
    });

}


//////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////
if( document.getElementById("inputTypeFileJobSeekerProfile") ){

    document.getElementById("inputTypeFileJobSeekerProfile").onchange = function(){

        document.getElementById("formUpdatePhotoJobSeekerProfile").submit();
    }
}

if( document.getElementById("inputTypeFileAdminProfile") ){

    document.getElementById("inputTypeFileAdminProfile").onchange = function(){

        document.getElementById("formUpdatePhotoAdminProfile").submit();
    }
}
//////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////
let passwordInputs = document.querySelectorAll("input[type='password']");
let iconPasswords  = document.querySelectorAll("input[type='password'] + i");

function changeBackgroundColorIconPassword(icon){

    icon.style.cssText = `background-color: rgba(180,180,180,0.3);`;
}
function changeBackgroundColorIconPasswordToNone(icon){

    icon.style.cssText = `background-color:none`;
}

if( iconPasswords ){

    for (let index = 0; index < iconPasswords.length; index++) {

            iconPasswords[index].onclick = function(){

                let eventIconPassword1 = setTimeout(changeBackgroundColorIconPassword , 15 , iconPasswords[index]);
    
                let eventIconPassword2 = setTimeout(changeBackgroundColorIconPasswordToNone , 300 , iconPasswords[index]);

                if( iconPasswords[index].classList.contains("fa-eye-slash") ){
    
                    iconPasswords[index].classList.remove("fa-eye-slash");
                    iconPasswords[index].classList.add("fa-eye");
        
                    passwordInputs[index].removeAttribute("type");
                    passwordInputs[index].setAttribute("type" , "text");
        
                }else{
    
                    iconPasswords[index].classList.remove("fa-eye");
                    iconPasswords[index].classList.add("fa-eye-slash");
        
                    passwordInputs[index].removeAttribute("type");
                    passwordInputs[index].setAttribute("type" , "password");
        
                }
            }
    }
}
//////////////////////////////////////////////////////////////////////////////////

/*---------------------------------------------
    Validation Form Profile General Employer          
-----------------------------------------------*/

let formProfileGeneralEmployer = document.querySelector(".edit-profile-information .row .right-section-employer-form .container form");

let profileGeneralEmployer_name      = document.getElementById("profileGeneralEmployer_name");
let profileGeneralEmployer_nameError = document.querySelector("#profileGeneralEmployer_name + p");
function profileGeneralEmployer_checkNameLive(){

    if( profileGeneralEmployer_name.value === '' || profileGeneralEmployer_name.value == null ){
            profileGeneralEmployer_name.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            profileGeneralEmployer_name.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            profileGeneralEmployer_nameError.style.cssText = `display:none`;
    }
}

let profileGeneralEmployer_email = document.getElementById("profileGeneralEmployer_email");
let profileGeneralEmployer_emailError = document.querySelector("#profileGeneralEmployer_email + p");
let profileGeneralEmployer_email_regExp = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
function profileGeneralEmployer_checkEmailLive(){

    if(profileGeneralEmployer_email.value.match(profileGeneralEmployer_email_regExp)){

        profileGeneralEmployer_email.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
        profileGeneralEmployer_emailError.style.cssText = `display:none`;

    } else{

        profileGeneralEmployer_email.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    }
}

let profileGeneralEmployer_phone = document.getElementById("profileGeneralEmployer_phone");
let profileGeneralEmployer_phoneError = document.querySelector("#profileGeneralEmployer_phone + p");
let profileGeneralEmployer_phone_regExp = /^[\d,\s,\+,\-]{8,20}/;
function profileGeneralEmployer_checkPhoneLive(){

    if(profileGeneralEmployer_phone.value.match(profileGeneralEmployer_phone_regExp)){

        profileGeneralEmployer_phone.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
        profileGeneralEmployer_phoneError.style.cssText = `display:none`;

    } else{

        profileGeneralEmployer_phone.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    }
}

let profileGeneralEmployer_address = document.getElementById("profileGeneralEmployer_address");
let profileGeneralEmployer_addressError = document.querySelector("#profileGeneralEmployer_address + p");
function profileGeneralEmployer_checkAddressLive(){

    if( profileGeneralEmployer_address.value === '' || profileGeneralEmployer_address.value == null ){
            profileGeneralEmployer_address.style.cssText = `box-shadow: 0 0 6px 0 red;`;
    } else{
            profileGeneralEmployer_address.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            profileGeneralEmployer_addressError.style.cssText = `display:none`;
    }
}

let profileGeneralEmployer_editDescription        = document.getElementById("profileGeneralEmployer_editDescription");
let profileGeneralEmployer_editDescriptionError   = document.querySelector("#profileGeneralEmployer_editDescription + p");
let profileGeneralEmployer_editDescriptionCounter = document.querySelector("#profileGeneralEmployer_editDescription ~ span");

function profileGeneralEmployer_checkDescriptionLive(){

    profileGeneralEmployer_editDescriptionCounter.textContent = LetterCount(profileGeneralEmployer_editDescription.value);

    if( profileGeneralEmployer_editDescription.value === '' || profileGeneralEmployer_editDescription.value == null ){
            profileGeneralEmployer_editDescription.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            profileGeneralEmployer_editDescriptionCounter.style.cssText = `color:red`;

    } else if(profileGeneralEmployer_editDescription.value.length < 50 || LetterCount(profileGeneralEmployer_editDescription.value) < 50){

            profileGeneralEmployer_editDescription.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            profileGeneralEmployer_editDescriptionCounter.style.cssText = `color:red`;
                                 
    } else{

            profileGeneralEmployer_editDescription.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            profileGeneralEmployer_editDescriptionCounter.style.cssText = `color:#31eb31;`;
            profileGeneralEmployer_editDescriptionError.style.cssText = `display:none`;
    }

}

if( formProfileGeneralEmployer ){

    formProfileGeneralEmployer.addEventListener('submit' ,  (event) => {

         /* Name Verification */
         if(profileGeneralEmployer_name.value === '' || profileGeneralEmployer_name.value == null){

            profileGeneralEmployer_name.style.cssText = `border-color: #f2dede; 
                                                         box-shadow: 0 0 6px 0 red;`
            profileGeneralEmployer_nameError.innerText = "Name is required";
            profileGeneralEmployer_nameError.style.cssText = `display:block`;
            event.preventDefault();
        } 

        /* Email Verification */
        if(profileGeneralEmployer_email.value === '' || profileGeneralEmployer_email.value == null){

                profileGeneralEmployer_email.style.cssText = `border-color: #f2dede; 
                                                              box-shadow: 0 0 6px 0 red;`
                profileGeneralEmployer_emailError.innerText = "Email is required";
                profileGeneralEmployer_emailError.style.cssText = `display:block`;
                event.preventDefault();
            } 
        if(profileGeneralEmployer_email.value.match(profileGeneralEmployer_email_regExp)){

        } else{
    
                profileGeneralEmployer_email.style.cssText = `border-color: #f2dede; 
                                                                box-shadow: 0 0 6px 0 red;`
                profileGeneralEmployer_emailError.innerText = "Email is required";
                profileGeneralEmployer_emailError.style.cssText = `display:block`;
                event.preventDefault();
        }

        /* Phone Verification */
        if(profileGeneralEmployer_phone.value === '' || profileGeneralEmployer_phone.value == null){

                profileGeneralEmployer_phone.style.cssText = `border-color: #f2dede; 
                                                              box-shadow: 0 0 6px 0 red;`
                profileGeneralEmployer_phoneError.innerText = "Phone number is required";
                profileGeneralEmployer_phoneError.style.cssText = `display:block`;
                event.preventDefault();
        } 
        if(profileGeneralEmployer_phone.value.match(profileGeneralEmployer_phone_regExp)){

        } else{

                profileGeneralEmployer_phone.style.cssText = `border-color: #f2dede; 
                                                              box-shadow: 0 0 6px 0 red;`
                profileGeneralEmployer_phoneError.innerText = "Phone number is required";
                profileGeneralEmployer_phoneError.style.cssText = `display:block`;
                event.preventDefault();
        }

        /* Address Verification */
        if(profileGeneralEmployer_address.value === '' || profileGeneralEmployer_address.value == null){

                profileGeneralEmployer_address.style.cssText = `border-color: #f2dede; 
                                                                box-shadow: 0 0 6px 0 red;`
                profileGeneralEmployer_addressError.innerText = "Address is required";
                profileGeneralEmployer_addressError.style.cssText = `display:block`;
                event.preventDefault();
        } 

        /* Description Verification */
        if(profileGeneralEmployer_editDescription.value === '' || profileGeneralEmployer_editDescription.value == null){

            profileGeneralEmployer_editDescription.style.cssText = `border-color: #f2dede; 
                                        box-shadow: 0 0 6px 0 red;`
            profileGeneralEmployer_editDescriptionError.innerText = "Description is required";
            profileGeneralEmployer_editDescriptionError.style.cssText = `display:block`;
            event.preventDefault();

        } else if(profileGeneralEmployer_editDescription.value.length < 50 || LetterCount(profileGeneralEmployer_editDescription.value) < 50){

            profileGeneralEmployer_editDescription.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            profileGeneralEmployer_editDescriptionError.style.cssText = `color:red`;    
            profileGeneralEmployer_editDescriptionError.innerText = "Minimum 50 characters";
            profileGeneralEmployer_editDescriptionError.style.cssText = `display:block`;

        }
    });
}
///////////////////////////////////////////////////////////////////////////////


/*---------------------------------------
    Post A job            
-----------------------------------------*/

/*
    Job Title Calculate Characters + Live Validation
*/

let jobTitleInput_postJob   = document.getElementById("jobTitle_postJob"); /* input job title */
let jobTitleError_postJob   = document.querySelector("#jobTitle_postJob + .input-error p"); /* error job title */
let jobTitleCounter_postJob = document.querySelector("#jobTitle_postJob ~ span"); /* counter job title */

function LetterCountPostJob(string , max , min){

    let array = string.split("");
    let count;
    for( count = 0 ; count < array.length ; count++ ){

            array[count];
    }
    return `${count} / ${max}(min ${min})`;
}

function postJob_checkJobTitleLive(){

    jobTitleCounter_postJob.textContent = LetterCountPostJob(jobTitleInput_postJob.value , '100' , '3');

    if( jobTitleInput_postJob.value === '' || jobTitleInput_postJob.value == null ){
            jobTitleInput_postJob.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            jobTitleCounter_postJob.style.cssText = `color:red`;

    } else if(jobTitleInput_postJob.value.length < 3 || LetterCountPostJob(jobTitleInput_postJob.value , '100' , '3') < 3){

            jobTitleInput_postJob.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            jobTitleCounter_postJob.style.cssText = `color:red`;
                                 
    } else if(jobTitleInput_postJob.value.length > 100 || LetterCountPostJob(jobTitleInput_postJob.value , '100' , '3') > 100){

            jobTitleInput_postJob.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            jobTitleCounter_postJob.style.cssText = `color:red`;
    } else{

            jobTitleInput_postJob.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            jobTitleCounter_postJob.style.cssText = `color:#31eb31;`;
            jobTitleError_postJob.style.cssText = `display:none`;
    }

}

/*
    Description Calculate Characters + Live Validation
*/

let description_postJob        = document.getElementById("description-post-job"); /* input description */
let descriptionError_postJob   = document.querySelector("#description-post-job + .input-error p"); /* error description */
let descriptionCounter_postJob = document.querySelector("#description-post-job ~ span"); /* counter description */

function postJob_checkDescriptionLive(){

    descriptionCounter_postJob.textContent = LetterCountPostJob(description_postJob.value , '20.000' , '250');

    if( description_postJob.value === '' || description_postJob.value == null ){

            description_postJob.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            descriptionCounter_postJob.style.cssText = `color:red`;

    }else if(description_postJob.value.length < 250 || LetterCountPostJob(description_postJob.value , '20.000' , '250') < 250){

            description_postJob.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            descriptionCounter_postJob.style.cssText = `color:red`;

    }else if(description_postJob.value.length > 20000 || LetterCountPostJob(description_postJob.value , '20.000' , '250') > 20000){

            description_postJob.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            descriptionCounter_postJob.style.cssText = `color:red`;

    }else{

            description_postJob.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            descriptionCounter_postJob.style.cssText = `color:#31eb31;`;
            descriptionError_postJob.style.cssText = `display:none`;
    }
}








/*
    Category + Subcategory Live Validation
*/
let category_postJob = document.getElementById("category");
let subcategory_postJob = document.getElementById("foucsing-on");
let categoryError_postJob = document.querySelector("#category + p");
let subcategoryError_postJob = document.querySelector("#foucsing-on + p");

function postJob_checkCategoryLive(){

    if(category_postJob.value === '' || category_postJob.value == null){

            category_postJob.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            subcategory_postJob.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            categoryError_postJob.style.cssText = `display:block`;
            subcategoryError_postJob.style.cssText = `display:block`;

    } else{

            category_postJob.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            subcategory_postJob.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
            categoryError_postJob.style.cssText = `display:none`;
            subcategoryError_postJob.style.cssText = `display:none`;
    }
}

/* 
    Salary Live Validation 
*/
let inputSalaryFrom  = document.querySelector(".inputSalaryFrom");  /* input salary from */
let inputSalaryTo    = document.querySelector(".inputSalaryTo");    /* input salary to */
let inputSalaryfixed = document.querySelector(".inputSalaryFixed"); /* input salary fixed */

function restrictAlphabetes(e){

    var x = e.which || e.keycode;

    if( x >= 48 && x <= 57 ){

        return true;

    }else{
        return false;

    }
}

/*
    Work-Shudele Live Validation
*/

let workSheduleStart = document.getElementById("work-shedule-start");
let workSheduleEnd = document.getElementById("work-shedule-end");

function postJob_checkWorkSheduleStartLive(){

    if(workSheduleStart.value === '' || workSheduleStart.value == null){

            workSheduleStart.style.cssText = `box-shadow: 0 0 6px 0 red;`;

    } else{

            workSheduleStart.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
    }
}

function postJob_checkWorkSheduleEndLive(){

    if(workSheduleEnd.value === '' || workSheduleEnd.value == null){

            workSheduleEnd.style.cssText = `box-shadow: 0 0 6px 0 red;`;

    } else{

            workSheduleEnd.style.cssText = `box-shadow: 0 0 8px 0 #31eb31;`;
    }
}

let workSheduleHoursNumber      = document.querySelector(".post-a-job-page .container .row .work-shedule .selects .work-shedule-hours input");
let workSheduleHoursNumberError = document.querySelector(".post-a-job-page .container .row .work-shedule .selects .work-shedule-hours p");

function postJob_checkWorkSheduleHoursLive(){

    if(workSheduleHoursNumber.value === '' || workSheduleHoursNumber.value == null){

            workSheduleHoursNumber.style.cssText  = `box-shadow: 0 0 6px 0 red;`;
            workSheduleHoursNumberError.innerText = 'work houres is required';

    } else{

            workSheduleHoursNumber.style.cssText      = `box-shadow: 0 0 8px 0 #31eb31;`;
            workSheduleHoursNumberError.style.cssText = 'display:none';
    }
}

/*
    form post job validation
*/
let formPostJob = document.getElementById("formPostJob"); /* form post job */

if(formPostJob){

    formPostJob.addEventListener('submit' ,  (event) => {

        /* job title validation */
        if( jobTitleInput_postJob.value === '' || jobTitleInput_postJob.value == null ){
            
                jobTitleInput_postJob.style.cssText = `box-shadow: 0 0 6px 0 red;`;
                jobTitleCounter_postJob.style.cssText = `color:red`;
                jobTitleError_postJob.style.cssText = `display:block`;
                jobTitleError_postJob.innerText = 'job title is required';
                event.preventDefault();
    
        } else if( jobTitleInput_postJob.value.length < 3 ){
    
                jobTitleInput_postJob.style.cssText = `box-shadow: 0 0 6px 0 red;`;
                jobTitleCounter_postJob.style.cssText = `color:red`;
                jobTitleError_postJob.style.cssText = `display:block`;
                jobTitleError_postJob.innerText = 'please enter at least 3 characters';
                event.preventDefault();
    
        }else if(jobTitleInput_postJob.value.length > 100){
    
                jobTitleInput_postJob.style.cssText = `box-shadow: 0 0 6px 0 red;`;
                jobTitleCounter_postJob.style.cssText = `color:red`;
                jobTitleError_postJob.style.cssText = `display:block`;
                jobTitleError_postJob.innerText = 'please enter at max 100 characters';
                event.preventDefault();
        } 
    
        /* description validation */
        if( description_postJob.value === '' || description_postJob.value == null ){
    
                description_postJob.style.cssText = `box-shadow: 0 0 6px 0 red;`;
                descriptionCounter_postJob.style.cssText = `color:red`;
                descriptionError_postJob.style.cssText = `display:block`;
                descriptionError_postJob.innerText = 'desscription is required';
                event.preventDefault();
    
        }else if(description_postJob.value.length < 250){
    
                description_postJob.style.cssText = `box-shadow: 0 0 6px 0 red;`;
                descriptionCounter_postJob.style.cssText = `color:red`;
                descriptionError_postJob.style.cssText = `display:block`;
                descriptionError_postJob.innerText = 'please enter at least 250 characters';
                event.preventDefault();
    
        }else if(description_postJob.value.length > 20000){
    
                description_postJob.style.cssText = `box-shadow: 0 0 6px 0 red;`;
                descriptionCounter_postJob.style.cssText = `color:red`;
                descriptionError_postJob.style.cssText = `display:block`;
                descriptionError_postJob.innerText = 'please enter at max 20,000 characters';
                event.preventDefault();
        }
    
        /* category + subcategory validation */
    
        if(category_postJob.value === '' || category_postJob.value == null){
    
            category_postJob.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            subcategory_postJob.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            categoryError_postJob.innerText = 'category is required';
            subcategoryError_postJob.innerText = 'subcategory is required';
            event.preventDefault();
    
        }
    
        /* work shudele  validation*/
        if(workSheduleStart.value === '' || workSheduleStart.value == null){
    
            workSheduleStart.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            event.preventDefault();
        }
        if(workSheduleEnd.value === '' || workSheduleEnd.value == null){
    
            workSheduleEnd.style.cssText = `box-shadow: 0 0 6px 0 red;`;
            event.preventDefault();
        }
        if(workSheduleHoursNumber.value === '' || workSheduleHoursNumber.value == null){
    
            workSheduleHoursNumber.style.cssText  = `box-shadow: 0 0 6px 0 red;`;
            workSheduleHoursNumberError.style.cssText = `display:block;`;
            workSheduleHoursNumberError.innerText = 'work houres is required';
            event.preventDefault();
    
        }
    
    
    });
}

function changeTextJobType_postJob(inputRadio , label , paragraphField , text){

    if(inputRadio){
     
        inputRadio.onclick = function(){

            paragraphField.innerText = text;
        }
        if(label){
            label.onclick = function(){
        
                paragraphField.innerText = text;
            }
        }
    }
}

let jobTypeParagraph = document.getElementById("jobTypeParagraph");

let fullTimeRadioJobType = document.getElementById("full-time");
let fullTimeLabelJobType = document.querySelector("#full-time + label");
if( fullTimeRadioJobType ){

    if( fullTimeRadioJobType.hasAttribute("checked") && fullTimeRadioJobType != '' && fullTimeRadioJobType != null ){

            jobTypeParagraph.innerText = 'Full time employment in a company.';
    }
}

let partTimeRadioJobType = document.getElementById("part-time");
let partTimeLabelJobType = document.querySelector("#part-time + label");
if( partTimeRadioJobType ){

    if( partTimeRadioJobType.hasAttribute("checked") && partTimeRadioJobType != '' && partTimeRadioJobType != null ){

            jobTypeParagraph.innerText = 'Ongoing employment with fewer hours.';
    }
}

let contractRadioJobType = document.getElementById("contract");
let contractLabelJobType = document.querySelector("#contract + label");
if( contractRadioJobType ){

    if( contractRadioJobType.hasAttribute("checked") && contractRadioJobType != '' && contractRadioJobType != null ){

            jobTypeParagraph.innerText = 'Position for an independent contract worker.';
    }
}

let intershipRadioJobType = document.getElementById("intership");
let intershipLabelJobType = document.querySelector("#intership + label");
if( intershipRadioJobType ){

    if( intershipRadioJobType.hasAttribute("checked") && intershipRadioJobType != '' && intershipRadioJobType != null ){

            jobTypeParagraph.innerText = 'Paid or unpaid position for a student or trainee.';
    }
}

let freelanceRadioJobType = document.getElementById("freelance");
let freelanceLabelJobType = document.querySelector("#freelance + label");
if( freelanceRadioJobType ){

    if( freelanceRadioJobType.hasAttribute("checked") && freelanceRadioJobType != '' && freelanceRadioJobType != null ){

            jobTypeParagraph.innerText = 'A well-defined limited project for a freelancer.';
    }  
}


changeTextJobType_postJob(fullTimeRadioJobType  , fullTimeLabelJobType  , jobTypeParagraph  , 'Full time employment in a company.');

changeTextJobType_postJob(partTimeRadioJobType  , partTimeLabelJobType  , jobTypeParagraph  , 'Ongoing employment with fewer hours.');

changeTextJobType_postJob(contractRadioJobType  , contractLabelJobType  , jobTypeParagraph  , 'Position for an independent contract worker.');

changeTextJobType_postJob(intershipRadioJobType , intershipLabelJobType , jobTypeParagraph  , 'Paid or unpaid position for a student or trainee.');

changeTextJobType_postJob(freelanceRadioJobType , freelanceLabelJobType , jobTypeParagraph  , 'A well-defined limited project for a freelancer.');



let locationParagraph = document.getElementById("locationParagraph");

let oneLocationRadioJobType = document.getElementById("one-location");
let oneLocationLabelJobType = document.querySelector("#one-location + label");
if( oneLocationRadioJobType ){

    if( oneLocationRadioJobType.hasAttribute("checked") && oneLocationRadioJobType != '' && oneLocationRadioJobType != null ){

            locationParagraph.innerText = 'All work requires on-site attendance.';
    }  
}

let multibleLocationsRadioJobType = document.getElementById("multible-locations");
let multibleLocationsLabelJobType = document.querySelector("#multible-locations + label");
if( multibleLocationsRadioJobType ){

    if( multibleLocationsRadioJobType.hasAttribute("checked") && multibleLocationsRadioJobType != '' && multibleLocationsRadioJobType != null ){

            locationParagraph.innerText = 'There is more than one work site.';
    }  
}

let remoteRadioJobType = document.getElementById("remote");
let remoteLabelJobType = document.querySelector("#remote + label");
if( remoteRadioJobType ){

    if( remoteRadioJobType.hasAttribute("checked") && remoteRadioJobType != '' && remoteRadioJobType != null ){

            locationParagraph.innerText = '100% remote without on-site attendance.';
    }  
}


changeTextJobType_postJob(oneLocationRadioJobType   , oneLocationLabelJobType  , locationParagraph  , 'All work requires on-site attendance.');

changeTextJobType_postJob(multibleLocationsRadioJobType  , multibleLocationsLabelJobType  , locationParagraph  , 'There is more than one work site.');

changeTextJobType_postJob(remoteRadioJobType  , remoteLabelJobType  , locationParagraph  , '100% remote without on-site attendance.');


/*
    gender
*/
let genderParagraph = document.getElementById("genderParagraph");

let maleRadioGender   = document.getElementById("post-job-gender-male");
let malelabelGender   = document.querySelector("#post-job-gender-male + label");
if( maleRadioGender ){

    if( maleRadioGender.hasAttribute("checked") && maleRadioGender != '' && maleRadioGender != null ){

            genderParagraph.innerText = 'Only males can applay for the job.';
    }  
}

let femaleRadioGender = document.getElementById("post-job-gender-female");
let femalelabelGender = document.querySelector("#post-job-gender-female + label");
if( femaleRadioGender ){

    if( femaleRadioGender.hasAttribute("checked") && femaleRadioGender != '' && femaleRadioGender != null ){

            genderParagraph.innerText = 'Only females can applay for the job.';
    }  
}

let anyoneRadioGender = document.getElementById("post-job-gender-anyone");
let anyoneLabelGender = document.querySelector("#post-job-gender-anyone + label");
if( anyoneRadioGender ){

    if( anyoneRadioGender.hasAttribute("checked") && anyoneRadioGender != '' && anyoneRadioGender != null ){

            genderParagraph.innerText = 'Anyone can applay for the job.';
    }  
}


changeTextJobType_postJob(maleRadioGender   , malelabelGender  , genderParagraph  , 'Only males can applay for the job.');

changeTextJobType_postJob(femaleRadioGender  , femalelabelGender  , genderParagraph  , 'Only females can applay for the job.');

changeTextJobType_postJob(anyoneRadioGender  , anyoneLabelGender  , genderParagraph  , 'Anyone can applay for the job.');

/*
    salary post job
*/

let salaryRadioPostJobRange = document.getElementById("salary-radio-post-job-range"); /* radio range */
let salaryRadioPostJobfixed = document.getElementById("salary-radio-post-job-fixed"); /* radio fixed */

let salaryLabelPostJobRange = document.querySelector("#salary-radio-post-job-range + label"); /* label range */
let salaryLabelPostJobfixed = document.querySelector("#salary-radio-post-job-fixed + label"); /* label fixed */

if( salaryRadioPostJobfixed ){

    if( salaryRadioPostJobfixed.hasAttribute("checked") && salaryRadioPostJobfixed != '' && salaryRadioPostJobfixed != null ){

        inputSalaryFrom.style.cssText  = `display:none`;
        inputSalaryTo.style.cssText    = `display:none`;
    
        inputSalaryFrom.removeAttribute("required");
        inputSalaryTo.removeAttribute("required");

        inputSalaryFrom.value = '';
        inputSalaryTo.value   = '';
    
        inputSalaryfixed.style.cssText = `display:block`;
    
        inputSalaryfixed.setAttribute("required" , "required");
    }
}

if( salaryRadioPostJobRange ){

    if( salaryRadioPostJobRange.hasAttribute("checked") ){

        inputSalaryFrom.style.cssText  = `display:block`;
        inputSalaryTo.style.cssText    = `display:block`;

        inputSalaryFrom.setAttribute("required" , "required");
        inputSalaryTo.setAttribute("required" , "required");

        inputSalaryfixed.style.cssText = `display:none`;

        inputSalaryfixed.removeAttribute("required");

        inputSalaryfixed.value = '';
    }
}

if(salaryRadioPostJobfixed){

    salaryRadioPostJobfixed.onclick = function(){

        inputSalaryFrom.style.cssText  = `display:none`;
        inputSalaryTo.style.cssText    = `display:none`;
    
        inputSalaryFrom.removeAttribute("required");
        inputSalaryTo.removeAttribute("required");
    
        inputSalaryFrom.value = '';
        inputSalaryTo.value   = '';
    
        inputSalaryfixed.style.cssText = `display:block`;
    
        inputSalaryfixed.setAttribute("required" , "required");
    }
}

if(salaryLabelPostJobfixed){

    salaryLabelPostJobfixed.onclick = function(){

        inputSalaryFrom.style.cssText  = `display:none`;
        inputSalaryTo.style.cssText    = `display:none`;
    
        inputSalaryFrom.removeAttribute("required");
        inputSalaryTo.removeAttribute("required");
    
        inputSalaryFrom.value = '';
        inputSalaryTo.value   = '';
    
        inputSalaryfixed.style.cssText = `display:block`;
    
        inputSalaryfixed.setAttribute("required" , "required");
    }
}

if(salaryRadioPostJobRange){

    salaryRadioPostJobRange.onclick = function(){

        inputSalaryFrom.style.cssText  = `display:block`;
        inputSalaryTo.style.cssText    = `display:block`;
    
        inputSalaryFrom.setAttribute("required" , "required");
        inputSalaryTo.setAttribute("required" , "required");
    
        inputSalaryfixed.style.cssText = `display:none`;
    
        inputSalaryfixed.removeAttribute("required");
    
        inputSalaryfixed.value = '';
    }
}

if(salaryLabelPostJobRange){

    salaryLabelPostJobRange.onclick = function(){

        inputSalaryFrom.style.cssText  = `display:block`;
        inputSalaryTo.style.cssText    = `display:block`;
    
        inputSalaryFrom.setAttribute("required" , "required");
        inputSalaryTo.setAttribute("required" , "required");
    
        inputSalaryfixed.style.cssText = `display:none`;
    
        inputSalaryfixed.removeAttribute("required");
    
        inputSalaryfixed.value = '';
    }
}


/* Employer Manage Advertisment */

let humergerIcons = document.querySelectorAll(".employer-manage-menu-dropdown-icon");

let menuDropdown  = document.querySelectorAll(".employer-manage-menu-dropdown");

menuDropdown.forEach((menu , index) => {

    humergerIcons[index].onclick = function(){

        menu.classList.toggle("visibleMenu");

        closeControlDivs(index);
    }
});

function closeControlDivs(index1){

    menuDropdown.forEach((item2 , index2) => {

        if( index1 != index2 ){

            item2.classList.remove("visibleMenu");
        }
    })
};

/*
    find work page form search ( What , Whare )
*/

let divWhatSearch       = document.getElementById("form-group-search-what");

let divWhereSearch      = document.getElementById("form-group-search-where");

let inputSearchWhat     = document.querySelector(".find-work-section-top .container form #form-group-search-what input");

let inputSearchWhere    = document.querySelector(".find-work-section-top .container form #form-group-search-where input");

let findWorkSearchForm  = document.querySelector(".find-work-section-top .container form");

let findWorkSearchError = document.querySelector(".find-work-section-top .container form .error") 

if( divWhatSearch && inputSearchWhat ){

        inputSearchWhat.addEventListener("focus" , function(){

            divWhatSearch.style.cssText = `

                border-color : var(--main-blue-color);
                box-shadow : 0 0 4px 0px var(--main-blue-color);
            `;
        });

        inputSearchWhat.addEventListener("blur" , function(){

            divWhatSearch.style.cssText = `

                border-color : var(--text-color-09);
                box-shadow : none;
            `;
        });
}

if( divWhereSearch && inputSearchWhere ){

    inputSearchWhere.addEventListener("focus" , function(){

        divWhereSearch.style.cssText = `

            border-color : var(--main-blue-color);
            box-shadow : 0 0 4px 0px var(--main-blue-color);
        `;
    });

    inputSearchWhere.addEventListener("blur" , function(){

        divWhereSearch.style.cssText = `

            border-color : var(--text-color-09);
            box-shadow : none;
        `;
    });
}

if(findWorkSearchForm){

        findWorkSearchForm.addEventListener('submit' , (event) =>{

            if( inputSearchWhat.value === '' && inputSearchWhere.value === '' ){
            
                findWorkSearchError.style.cssText = `display:block`;
                event.preventDefault();
    
            }   
        });
}


/* show advirtisment apply button */

let formApplyRequest    = document.querySelector(".show-advirtisment .container .row .right-section .applayButton-and-gender form");

let formApplyRequestBtn = document.querySelector(".show-advirtisment .container .row .right-section .applayButton-and-gender form button");

if( formApplyRequest ){

        formApplyRequest.addEventListener('submit' , (event) =>{

            if( formApplyRequestBtn ){
                
                if( formApplyRequestBtn.classList.contains("btn-close") ){

                        event.preventDefault();
                }

            }   
        });
}


/* apply page applicant request */

let uploadArea = document.querySelector(".applicant-request-page .container .cv .upload-area");

let inputFile  = document.querySelector(".applicant-request-page .container .cv .upload-area input");

let divUploadFileName = document.querySelector(".applicant-request-page .container .cv .upload-file-name");

let uploadFileName = document.querySelector(".applicant-request-page .container .cv .upload-file-name p");

let submitButton = document.querySelector(".applicant-request-page .container .cv button");

let formUploadFile = document.getElementById("form-upload-file");

if( uploadArea ){

        uploadArea.onclick = function(){

            if( inputFile ){

                    inputFile.click();
            }
        }
}

if( inputFile ){

        inputFile.onchange = ({target}) =>{

                let file = target.files[0];

                if( file ){

                        let fileName = file.name;

                        if( uploadFileName ){

                                if( divUploadFileName ){

                                        divUploadFileName.style.cssText = `display:block`;
                                }

                                uploadFileName.innerText = fileName;
                        }
                }
        }
}


let descriptionTextarea = document.querySelector(".applicant-request-page .container .description .description-box textarea");
let descriptionTextareaCounter = document.querySelector(".applicant-request-page .container .description .description-box span");

function LetterCountTextarea(string){

    let array = string.split("");
    let count;
    for( count = 0 ; count < array.length ; count++ ){

            array[count];
    }
    return `${count} / 2500 characters min(250)`;
}

function checkDescriptionTextareaLive(){

    descriptionTextareaCounter.innerText = LetterCountTextarea(descriptionTextarea.value);

    if( descriptionTextarea.value === '' || descriptionTextarea.value == null ){
            descriptionTextarea.style.cssText = `border-color: red`;
            descriptionTextareaCounter.style.cssText = `color: red`;

    } else if(descriptionTextarea.value.length < 250 || LetterCountTextarea(descriptionTextarea.value) < 250){

            descriptionTextarea.style.cssText = `border-color: red`;
            descriptionTextareaCounter.style.cssText = `color: red`;
                                 
    } else{

            descriptionTextarea.style.cssText = `border-color: #31eb31;`;
            descriptionTextareaCounter.style.cssText = `color:#31eb31;`;
    }

}

if(formUploadFile){

        formUploadFile.addEventListener('submit' ,  (event) => {

            if( descriptionTextarea.value === '' || descriptionTextarea.value == null ){

                    descriptionTextarea.style.cssText = `border-color: red`;

                    event.preventDefault();
    
            } else if(descriptionTextarea.value.length < 250 || LetterCountTextarea(descriptionTextarea.value) < 250){
    
                    descriptionTextarea.style.cssText = `border-color: red`;

                    event.preventDefault();
                                     
            }
        });

}



/* manage aplicant requests */

let formFilterRequests  = document.getElementById("filter-requests");

let selectAdsFilterRequests = document.querySelector(".applicant_request_page .container .filter-request #filter-requests select");

function check_select_ads_filter_requests(){

    if( selectAdsFilterRequests.value === 'All-Requests' ){

           document.location.href = 'manageApplicantRequest.php';

    }else{
    
            formFilterRequests.submit();
    }
}

let numberCircleAdmin = document.querySelectorAll(".numberCircleAdminPercent");
let circle = document.querySelectorAll(".circle");

       
for (let index = 0; index < numberCircleAdmin.length; index++) {
    
        if(numberCircleAdmin[index].innerText == 100){

            circle[index].style.cssText = ` 

                stroke-dashoffset: 0;

            `;

        } else{

            let precentage = '0.' + numberCircleAdmin[index].innerText;

            let circleNumber = 472 - 472 * precentage;
    
            circle[index].style.cssText = ` 
    
                stroke-dashoffset: ${circleNumber};
    
            `;
        }
}




let adminBox = document.querySelectorAll(".admin-dashboard .container .content .content-body .container .box");

let adminBoxNumber = document.querySelectorAll(".admin-dashboard .container .content .content-body .container .box span");

let adminBoxIcon = document.querySelectorAll(".admin-dashboard .container .content .content-body .container .box i");

let adminBoxText = document.querySelectorAll(".admin-dashboard .container .content .content-body .container .box p");



for (let index = 0; index < adminBox.length; index++) {

        adminBox[index].addEventListener('mouseenter' ,  () => {

            adminBox[index].style.cssText = ` background-color: var(--main-blue-color); `;
            adminBoxIcon[index].style.cssText = ` color: var(--white-color); `;
            adminBoxNumber[index].style.cssText = ` color: var(--white-color); `;
            adminBoxText[index].style.cssText = ` color: var(--white-color); `;
        });

        adminBox[index].addEventListener('mouseleave' ,  () => {

            adminBox[index].style.cssText = ` background-color: var(--white-color); `;
            adminBoxIcon[index].style.cssText = ` color: var(--main-blue-color); `;
            adminBoxNumber[index].style.cssText = ` color: var(--text-color-09); `;
            adminBoxText[index].style.cssText = ` color: var(--text-color-06); `;
        });
}

