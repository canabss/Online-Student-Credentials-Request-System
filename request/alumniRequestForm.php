<?php
    require_once('../database.php');
    require_once('../validations.php');
    require_once('../functions.php');
    session_start();
    $error = [];
    $year = "";
    $course = "";
    $purpose = "";
    $hasClearance = "";
    $needClearance = false;
    $alreadyRequested = false;
    $err = "";
    $db = database();
    clearValues();
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){ 
        $studentNo = $db->real_escape_string($_POST['studentNo']);
        $lastName = $db->real_escape_string(ucFirst($_POST['lastName']));
        $firstName = $db->real_escape_string(ucFirst($_POST['firstName']));
        $middleName = $db->real_escape_string(ucFirst($_POST['middleName']));
        $birthday = $db->real_escape_string($_POST['birthday']);
        $email = $db->real_escape_string($_POST['email']);
        $contactNo = $db->real_escape_string($_POST['contact']);
        $course = $db->real_escape_string(ucFirst($_POST['course']));
        //$year = $db->real_escape_string($_POST['year']);
        $documents = $_POST['docs'];
        if($_POST['purpose'] == "Others"){
            if(empty($_POST['specify-purpose'])){
                $error[] = 'Specify Purpose is required.';
            }
            else{
                 $purpose = $db->real_escape_string($_POST['specify-purpose']);
            }
        }
        else{
            $purpose = $db->real_escape_string($_POST['purpose']);
        }
        $hasClearance = $db->real_escape_string($_POST['has-clearance']);
        $error = isAlumni($studentNo, $birthday, $course, $error);
        $error = lastnameValidation($lastName, $error);
        $error = firstnameValidation($firstName, $error);
        
        //$clearance = basename($_FILES['my-file']['name']);
        if($_POST['has-clearance'] == 'YES'){
            if(empty($_FILES['my-file']['name'])){
                $error[] = "Please Select an image file for your clearance";
            }
            else{
                $image = $_FILES['my-file']['tmp_name'];
                $clearanceContent = addslashes(file_get_contents($image));
            }
        }
        else{
            $clearanceContent = "";
        }
        
        if(empty($middleName)){
            $middleName = middlenameIsEmpty($middleName);
        }
        else{
            $error = middlenameValidation($middleName, $error);
        }
        birthdaySessionSet($birthday);
        $error = emailValidation($email, $error);
        $email = emailIsEmpty($email);
        if(empty($contactNo)){
            $contactNo = contactIsEmpty($contactNo);
        }
        else{
            $error = contactValidation($contactNo, $error);
        }
        courseSessionSet($course);
        yearSessionSet($year);
        $error = documentValidation($documents, $error);
        purposeSessionSet($purpose);


        if(empty($error)){
            $arr = [];
            $alreadyRequestedList = [];
            
            foreach($documents as $document){
                $getPreviousRequest = $db->query("SELECT * FROM requests WHERE student_id = '$studentNo' AND requested_documents LIKE '%$document%'");
                $arr = $getPreviousRequest->fetch_array(MYSQLI_ASSOC);
                if($getPreviousRequest){
                    if($getPreviousRequest->num_rows > 0){
                        $alreadyRequestedList[] = $document;
                        $alreadyRequested = true;
                    }
                }
            }
            
            if($alreadyRequested){
                for($i = 0; $i < count($alreadyRequestedList); $i++){
                    if($i != count($alreadyRequestedList)-1){
        				$err .= $alreadyRequestedList[$i]." and ";
        			}
        			else{
        				$err .= $alreadyRequestedList[$i];
        		    }
                }
                $error[] = "You have already requested the following: ".$err;
                $_SESSION['submitted-request'] = false;
            }
            else{
                if(submitRequestAlumni($studentNo, $email, $lastName, $firstName, $middleName, $documents, "2020-2021", "1st Semester", $purpose,"Alumni", $needClearance, $clearanceContent)){
                    $_SESSION['submitted-request'] = true;
                }
                else{
                    $error[] = "SQL Error";
                    $_SESSION['submitted-request'] = false;
                }
            }
        }
        else{
            $_SESSION['submitted-request'] = false;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Credential Request System</title>
        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css"/>
        <link href="https://oscrs-bulsusc.com/assets/css/bootstrap.css" rel="stylesheet" />
        <link href="https://oscrs-bulsusc.com/assets/css/homepage.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="https://oscrs-bulsusc.com/assets/img/logo.png" />
    </head>
    <body>
        <div id="page-top">
            <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav" style="background: maroon;">
                <div class="container-fluid">
                    <a class="navbar-brand" href="https://oscrs-bulsusc.com/"><img src="https://oscrs-bulsusc.com/assets/img/BSU-w.png" style= "height:40px; width: 250px" alt="OSCRS-Logo" /></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                        Menu
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarResponsive">
                        <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                            <li class="nav-item"><a class="nav-link" href="https://oscrs-bulsusc.com/">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="https://oscrs-bulsusc.com/#contact">Contact Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="FAQs">FAQ's</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        
        <section class="page-section bg-light background" id="request" style="height: 100%;">
        </br>
            <div class="container">
                <div class="card align-items-stretch mb-5" style="margin-left: 10px; margin-right:10px;">
                    <a href="https://oscrs-bulsusc.com/#request" class="link text-uppercase" style="padding-bottom: 10px"><i class="fas fa-chevron-left mr"></i> Back</a>
                    <h4>Requestor Information:</h4></br>
                    <form id="requestForm" method="post" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-4">
                                <label class = "form-label" for="lastName">Last Name:*</label>
                                <input class = "form-control" type="text" placeholder="Ex. Dela Cruz" pattern = "[a-zA-Z ]+" name="lastName" id="lastName" value="<?php echo !isset($_SESSION['lastName']) ? '' : $_SESSION['lastName'] ?>" required/></br>
                            </div>
                            <div class="col-lg-4">
                                <label class = "form-label" for="firstName">First Name:*</label>
                                <input class = "form-control" type="text" placeholder="Ex. Juan" pattern = "[a-zA-Z ]+" name="firstName" id="firstName" value="<?php echo !isset($_SESSION['firstName']) ? '' : $_SESSION['firstName'] ?>" required/></br>
                            </div>
                            <div class="col-lg-4">
                                <label class = "form-label" for="middleName">Middle Name:</label>
                                <input class = "form-control" type="text" placeholder="Ex. Ponce" pattern = "[a-zA-Z ]+" name="middleName" id="middleName" value="<?php echo !isset($_SESSION['middleName']) ? '' : $_SESSION['middleName'] ?>"/></br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label class = "form-label" for="studentno">Student No.:(Optional)</label>
                                <input class = "form-control" placeholder="Ex. 2021-123456" type="tel" pattern="[0-9]{4}[-]{1}[0-9]{6}" name="studentNo" id="studentNo" maxlength="11" value="<?php echo !isset($_SESSION['studentno']) ? '' : $_SESSION['studentno'] ?>"/></br>
                            </div>
                            <div class="col-lg-4">
                                <label class = "form-label" for="birthday">Birthday:*&nbsp;</label><br>
                                <input class = "form-control text-uppercase" type="date" name="birthday" id="birthday" max= "<?php $date = new DateTime("now", new DateTimeZone('Asia/Manila')); echo $date->format("Y-m-d");?>" value="<?php echo !isset($_SESSION['birthday']) ? '' : $_SESSION['birthday'] ?>" required/></br>
                                <!--<div class="field-dayWrapper">
                                    <input class="field-dayBox" type="text" pattern="[0-9]*" name="day" value="" id="day" min="0" max="31" placeholder="DD" required>
                                </div>
                                <div class="field-monthWrapper">
                                    <input class="field-monthBox" type="text" pattern="[0-9]*" name="month" value="" id="month" min="0" max="12" placeholder="MM" required>
                                </div>
                                <div class="field-yearWrapper">
                                    <input class="field-yearBox" type="text" pattern="[0-9]*" name="year" value="" id="year" min="0" max="2050" placeholder="YYYY" required>
                                </div>-->
                            </div><br>
                            <div class="col-lg-4">
                                <label class = "form-label" for="email">Email Address.:*</label>
                                <input class = "form-control" type="email" placeholder="juanponcedelacruz@gmail.com" name="email" id="email"  value="<?php echo !isset($_SESSION['email']) ? '' : $_SESSION['email'] ?>" required/></br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <label class = "form-label" for="address">Complete Address:(Optional)</label>
                                <input class = "form-control" placeholder="Complete Address" type="text" name="address"/></br>
                            </div>  
                            <div class="col-lg-4">
                                <label class = "form-label" for="contact">Contact No.:(Optional)</label>
                                <input class = "form-control" placeholder="Ex. 09991234567" type="tel" name="contact" id="contact" pattern = "[0-9]{4}[0-9]{3}[0-9]{4}" maxlength="11" value="<?php echo !isset($_SESSION['contact']) ? '' : $_SESSION['contact'] ?>"/></br>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-lg-4">
                                <label class = "form-label" for="course">Course:*</label><br/>
                                <select name="course" id="course" class = "form-select" required>
                                    <option value="">Select</option>
                                    
                                    <optgroup label="Department of Industrial and Information Technology">
                                        <option value="Bachelor of Science in Information Technology" <?php if("$course" == "Bachelor of Science in Information Technology") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Information Technology</option>
                                        <option value="Bachelor in Industrial Technology Major in Computer Technology" <?php if("$course" == "Bachelor in Industrial Technology Major in Computer Technology") :?>selected = "selected"<?php endif;?>>Bachelor in Industrial Technology Major in Computer Technology</option>
                                        <option value="Bachelor in Industrial Technology Major in Electronics" <?php if("$course" == "Bachelor in Industrial Technology Major in Electronics") :?>selected = "selected"<?php endif;?>>Bachelor in Industrial Technology Major in Electronics</option>
                                        <option value="Bachelor in Industrial Technology Major in Drafting" <?php if("$course" == "Bachelor in Industrial Technology Major in Drafting") :?>selected = "selected"<?php endif;?>>Bachelor in Industrial Technology Major in Drafting</option>
                                        <option value="Bachelor in Industrial Technology Major in Foods" <?php if("$course" == "Bachelor in Industrial Technology Major in Foods") :?>selected = "selected"<?php endif;?>>Bachelor in Industrial Technology Major in Foods</option>
                                    </optgroup><br><br>
                                    
                                    <optgroup label="Department of Education">
                                        <option value="Bachelor in Elementary Education General Education">Bachelor in Elementary Education General Education</option>
                                        <option value="Bachelor of Secondary Education Major in English" <?php if("$course" == "Bachelor of Secondary Education Major in English") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in English</option>
                                        <option value="Bachelor of Secondary Education Major in Mathematics" <?php if("$course" == "Bachelor of Secondary Education Major in Mathematics") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in Mathematics</option>
                                        <option value="Bachelor of Secondary Education Major in Science" <?php if("$course" == "Bachelor of Secondary Education Major in Science") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in Science</option>
                                        <option value="Bachelor of Secondary Education Major in Physical Science" <?php if("$course" == "Bachelor of Secondary Education Major in Physical Science") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in Physical Science</option>
                                        <option value="Bachelor of Secondary Education Major in Filipino" <?php if("$course" == "Bachelor of Secondary Education Major in Filipino") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in Filipino</option>
                                        <option value="Bachelor of Secondary Education Major in Social Studies" <?php if("$course" == "Bachelor of Secondary Education Major in Social Studies") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in Social Studies</option>
                                    </optgroup><br><br>
                                    
                                    <optgroup label="Department of Business Administration">
                                        <option value="Bachelor of Science in Business Administration Major in General Business Administration" <?php if("$course" == "Bachelor of Science in Business Administration Major in General Business Administration") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Business Administration Major in General Business Administration</option>
                                        <option value="Bachelor of Science in Business Administration Major in Financial Management" <?php if("$course" == "Bachelor of Science in Business Administration Major in Financial Management") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Business Administration Major in Financial Management </option>
                                        <option value="Bachelor of Science in Business Administration Major in Marketing Management" <?php if("$course" == "Bachelor of Science in Business Administration Major in Marketing Management") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Business Administration Major in Marketing Management</option>
                                        <option value="Bachelor of Science in Business Administration Major in Business Economics" <?php if("$course" == "Bachelor of Science in Business Administration Major in Business Economics") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Business Administration Major in Business Economics</option>
                                        <option value="Bachelor of Science in Entrepeneurship" <?php if("$course" == "Bachelor of Science in Entrepeneurship") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Entrepeneurship</option>
                                    </optgroup><br><br>
                                    
                                    <optgroup label="Department of Hospital Management">
                                        <option value="Bachelor of Science in Hospitality Management" <?php if("$course" == "Bachelor of Science in Hospitality Management") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Hospitality Management</option>
                                        <option value="Bachelor of Science in Hotel Restaurant Management" <?php if("$course" == "Bachelor of Science in Hotel Restaurant Management") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Hotel Restaurant Management</option>
                                    </optgroup><br>
                                </select><br>
                                
                            <!--<div class="col-lg-4">
                                <label class = "form-label" for="year">Year Graduated:*</label>
                                <input class = "form-control" placeholder="Ex. 2020-2021" type="text" name="year" id="year" value="<?php echo !isset($_SESSION['year']) ? '' : $_SESSION['year'] ?>" required/></br>
                            </div>-->
                        </div>
                        <h4>Document(s) Information:*</h4><br>
                        <div class="col-lg-7 mx-auto">
                            <input type="checkbox" name="docs[]" value="Transcript of Records" <?php echo !isset($_SESSION['document0']) ? '' : 'checked' ?>> Transcript of Records (TOR)<br>
                            <input type="checkbox" name="docs[]" value="Certificate of Graduation" <?php echo !isset($_SESSION['document1']) ? '' : 'checked' ?>> Certificate of Graduation<br>
                            <input type="checkbox" name="docs[]" value="Diploma" <?php echo !isset($_SESSION['document2']) ? '' : 'checked' ?>> Diploma<br><br>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label class = "form-label" for="has-clearance">Do you have Clearance?*</label><br/>
                                <select name="has-clearance" id="has-clearance" class = "form-select" required>
                                    <option value="">Select</option>
                                    <option value="YES" <?php if("$hasClearance" == "YES") :?>selected = "selected"<?php endif;?>>YES</option>
                                    <option value="NO" <?php if("$hasClearance" == "NO") :?>selected = "selected"<?php endif;?>>NO</option>
                                </select><br>
                            </div> 
                        </div>
                        <div class="row" id="yes">
                            <label class = "form-label">Please upload your clearance: (.jpeg, .jpg, .png, .gif)</label><br>
                            <p class="file-return"></p>
                            <div class="input-file-container">  
                                <input class="input-file" id="my-file"  name="my-file" type="file" accept="image/png, image/gif, image/jpeg, image/jpg">
                                <label tabindex="0" for="my-file" class="input-file-trigger">Select a file...</label></br>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <label class = "form-label" for="purpose">Purpose of Request:*</label><br/>
                                <select name="purpose" id="purpose" class = "form-select" required>
                                    <option value="">Select</option>
                                    <option value="Employment Requirements"  <?php if("$purpose" == "Employment Requirements") :?>selected = "selected"<?php endif;?>>Employment Requirements</option>
                                    <option value="For Board Examination"  <?php if("$purpose" == "For Board Examination") :?>selected = "selected"<?php endif;?>>For Board Examination</option>
                                    <option value="For Further Studies"  <?php if("$purpose" == "For Further Studies") :?>selected = "selected"<?php endif;?>>For Further Studies</option>
                                    <option value="For General Purposes"  <?php if("$purpose" == "For General Purposes") :?>selected = "selected"<?php endif;?>>For General Purposes</option>
                                    <option value="For Personal Copy"  <?php if("$purpose" == "For Personal Copy") :?>selected = "selected"<?php endif;?>>For Personal Copy</option>
                                    <option value="Others"  <?php if("$purpose" == "Others") :?>selected = "selected"<?php endif;?>>Others</option>
                                </select></br>
                            </div> 
                        </div><br>
                        <div class="row">
                            <div class="col-lg-8" id="specify">
                                <label class = "form-label" for="specify-purpose">Please Specify:*</label>
                                <input class = "form-control" type="text" placeholder="Purpose" name="specify-purpose" id="specify-purpose"  value="<?php echo empty($purpose) ? '' : $purpose?>"/></br>
                            </div> 
                        </div><br>
                        <div>
                           
                            <button class="btn btn-info text-uppercase" name="submit" style="float: right;" id="submit">Submit</button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </section>
        <footer class="footer py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div style="align-items: center;">Copyright &copy; Bulacan State University - Sarmiento Campus 2022</div>
                </div>
            </div>
        </footer>
        <script src="https://oscrs-bulsusc.com/assets/js/jquery-3.3.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
        <script src="https://oscrs-bulsusc.com/assets/js/bootstrap.bundle.min.js"></script>
        <script>
            document.querySelector("html").classList.add('js');

            var fileInput  = document.querySelector( ".input-file" ),  
                button     = document.querySelector( ".input-file-trigger" ),
                the_return = document.querySelector(".file-return");
                  
            button.addEventListener( "keydown", function( event ) {  
                if ( event.keyCode == 13 || event.keyCode == 32 ) {  
                    fileInput.focus();  
                }  
            });
            button.addEventListener( "click", function( event ) {
               fileInput.focus();
               return false;
            });  
            fileInput.addEventListener( "change", function( event ) {  
                the_return.innerHTML = this.value;  
            });  
           
           $(document).ready(function(){
                $("#has-clearance").on("change", function(){
                    if(($("#has-clearance").val()) == "YES"){
                        $('#yes').addClass('active');
                    }
                    else{
                        $('#yes').removeClass('active');
                    }
                }); 
           });
           
           $(document).ready(function(){
                $("#purpose").on("change", function(){
                    if(($("#purpose").val()) == "Others"){
                        $('#specify').addClass('active');
                    }
                    else{
                        $('#specify').removeClass('active');
                    }
                }); 
           });
           
           <?php if("$hasClearance" == "YES") :?>
                $('#yes').addClass('active');
           <?php endif;?>
           
            <?php if("$purpose" == "Others") :?>
                $('#specify').addClass('active');
            <?php endif;?>
            
          
        </script>
        <?php if(isset($_SESSION['submitted-request'])) :?>
            <?php if($_SESSION['submitted-request'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Request Submitted', 
                        text: 'Please check your email to confirm your request or else it will be cancel. Thank you!',
                        icon:'info', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('https://oscrs-bulsusc.com/');
                        }
                    });
                </script>
                <?php clearSessions();?>
            <?php endif;?>
        <?php endif; ?>
        
        
        <?php if(isset($_SESSION['submitted-request'])) :?>
            <?php if($_SESSION['submitted-request'] == false) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Failed to submit request',
                        html: '<?php foreach($error as $er){ echo "* ".$er."<br>"; }?>', 
                        icon:'error', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    });
                </script>
                <?php 
                    unset($_SESSION['submitted-request']);
                ?>
            <?php endif;?>
        <?php endif;?>
        
        
    </body>
</html>