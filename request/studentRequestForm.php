<?php
    require_once('../database.php');
    require_once('../validations.php');
    require_once('../functions.php');
    session_start();
    $error = [];
    $year = "";
    $course = "";
    $purpose = "";
    $academicYear = "";
    $semester = "";
    $needClearance = false;
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
        $year = $db->real_escape_string($_POST['year']);
        $section = $db->real_escape_string($_POST['section']);
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
        $error = isCurrentStudent($studentNo, $birthday, $course, $error);
        $error = lastnameValidation($lastName, $error);
        $error = firstnameValidation($firstName, $error);
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
        sectionSessionSet($section);
        $error = documentValidation1($documents, $error);
        purposeSessionSet($purpose);

       if(in_array("Certificate of Grades", $documents) || in_array("Certificate of Registration", $documents)){
            $academicYear = $db->real_escape_string($_POST['academicYear']);
            $semester = $db->real_escape_string($_POST['semester']);
            academicYearSessionSet($academicYear);
            semesterSessionSet($semester);
            $needClearance = true;
        }
        else{
            $academicYear = "2020-2021";
            $semester = "1st Semester";
            $needClearance = false;
        }
        
        if(empty($error)){
            $arr = [];
            $alreadyRequestedList = [];
            
            foreach($documents as $document){
                $getPreviousRequest = $db->query("SELECT * FROM requests WHERE student_id = '$studentNo' AND requested_documents LIKE '%$document%' AND NOT request_status = 'Archive'");
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
                $error[] = "You still have pending request of the following: ".$err;
                $_SESSION['submitted-request'] = false;
            }
            else{
                if(submitRequest($studentNo, $email, $lastName, $firstName, $middleName, $documents, $academicYear, $semester, $purpose,"Student", $needClearance)){
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
                            <li class="nav-item"><a class="nav-link" href="https://oscrs-bulsusc.com">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="https://oscrs-bulsusc.com/#contact">Contact Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="https://oscrs-bulsusc.com/FAQs">FAQ's</a></li>
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
                    <form id="requestForm" method="POST" action="">
                        <div class="row">
                            <div class="col-lg-4">
                                <label class = "form-label" for="lastName">Last Name:*</label>
                                <input class = "form-control" type="text" placeholder="Ex. Dela Cruz" pattern = "[a-zA-Z ]+" name="lastName" id="lastName" value="<?php echo !isset($_SESSION['lastName']) ? '' : $_SESSION['lastName'] ?>" required></br>
                            </div>
                            <div class="col-lg-4">
                                <label class = "form-label" for="firstName">First Name:*</label>
                                <input class = "form-control" type="text" placeholder="Ex. Juan" pattern = "[a-zA-Z ]+" name="firstName" id="firstName" value="<?php echo !isset($_SESSION['firstName']) ? '' : $_SESSION['firstName'] ?>" required></br>
                            </div>
                            <div class="col-lg-4">
                                <label class = "form-label" for="middleName">Middle Name:(Optional)</label>
                                <input class = "form-control" type="text" placeholder="Ex. Ponce" pattern = "[a-zA-Z ]+" name="middleName" id="middleName" value="<?php echo !isset($_SESSION['middleName']) ? '' : $_SESSION['middleName'] ?>"/></br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label class = "form-label" for="studentno">Student No.:*</label>
                                <input class = "form-control" placeholder="Ex. 2018-123456" name="studentNo" type="tel" pattern="[0-9]{4}[-]{1}[0-9]{6}" id="studentNo" maxlength="11" value="<?php echo !isset($_SESSION['studentno']) ? '' : $_SESSION['studentno'] ?>" required></br>
                            </div>
                            <div class="col-lg-4">
                                <label class = "form-label" for="birthday">Birthday:*</label>
                                <input class = "form-control text-uppercase" type="date" name="birthday"  id="birthday" max= "<?php $date = new DateTime("now", new DateTimeZone('Asia/Manila')); echo $date->format("Y-m-d");?>" value="<?php echo !isset($_SESSION['birthday']) ? '' : $_SESSION['birthday'] ?>" required></br>
                            </div>
                            <div class="col-lg-4">
                                <label class = "form-label" for="email">Email Address.:*</label>
                                <input class = "form-control" type="email" placeholder="Ex. juanponcedelacruz@gmail.com" name="email" id="email"  value="<?php echo !isset($_SESSION['email']) ? '' : $_SESSION['email'] ?>" required></br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <label class = "form-label" for="address">Complete Address:(Optional)</label>
                                <input class = "form-control" placeholder="Address" type="text" name="address" id="address"/></br>
                            </div>  
                            <div class="col-lg-4">
                                <label class = "form-label" for="contact">Contact No.:(Optional)</label>
                                <input class = "form-control" placeholder="Ex. 09991234567" type="tel" pattern = "[0-9]{4}[0-9]{3}[0-9]{4}" maxlength="11" name="contact" id="contact" value="<?php echo !isset($_SESSION['contact']) ? '' : $_SESSION['contact'] ?>"/></br>
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
                                        <option value="BEED">Bachelor in Elementary Education General Education</option>
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
                                </select>	<br/>	
                            </div>
                            <div class="col-lg-4">
                                <label class = "form-label" for="year">Year: *</label><br/>
                                    <select name="year" id="year" class = "form-select" required>
                                        <option value="">Select</option>
                                        <option value="1st" <?php if("$year" == "1st") :?>selected = "selected"<?php endif;?>>1st</option>
                                        <option value="2nd" <?php if("$year" == "2nd") :?>selected = "selected"<?php endif;?>>2nd</option>
                                        <option value="3rd" <?php if("$year" == "3rd") :?>selected = "selected"<?php endif;?>>3rd</option>
                                        <option value="4th" <?php if("$year" == "4th") :?>selected = "selected"<?php endif;?>>4th</option>
                                    </select>
                            </div>
                            <div class="col-lg-4">
                                    <label class = "form-label" for="section">Section:*</label>
                                    <input class = "form-control" placeholder="Ex. 1A" type="text" name="section" id="section" value="<?php echo !isset($_SESSION['section']) ? '' : $_SESSION['section'] ?>" required/></br>
                            </div>
                        </div>
                        <h4>Document(s) Information:*</h4><br>
                        <div id="checkbox" class="col-lg-7 mx-auto">
                            <input type="checkbox" name="docs[]" id="cog" class="check" value="Certificate of Grades" <?php echo !isset($_SESSION['document0']) ? '' : 'checked' ?>> Certificate of Grades<br/>
                            <input type="checkbox" name="docs[]" id="cor" class="check" value="Certificate of Registration" <?php echo !isset($_SESSION['document1']) ? '' : 'checked' ?>> Certificate of Registration<br/>
                            <input type="checkbox" name="docs[]" class="check" value="Certificate of Good moral" <?php echo !isset($_SESSION['document2']) ? '' : 'checked' ?>> Certificate of Good moral<br/>
                            <input type="checkbox" name="docs[]" class="check" value="Honourable Dismissal" <?php echo !isset($_SESSION['document3']) ? '' : 'checked' ?>> Honourable Dismissal<br/>
                            <input type="checkbox" name="docs[]" class="check" value="Certificate of Non-issuance of ID" <?php echo !isset($_SESSION['document4']) ? '' : 'checked' ?>> Certificate of Non-issuance of ID
                        </div><br/>
                        <div class="row">
                            <div class="col-lg-4" id="sem">
                                <label class = "form-label" for="course">Semeter:(of Requested Documents)*</label><br/>
                                <select name="semester" id="semester" class = "form-select">
                                    <option value="">Select</option>
                                    <option value="1st Semester" <?php if("$semester" == "1st Semester") :?>selected = "selected"<?php endif;?>>1st Semester</option>
                                    <option value="2nd Semester" <?php if("$semester" == "2nd Semester") :?>selected = "selected"<?php endif;?>>2nd Semester</option>
                                    <option value="Summer" <?php if("$semester" == "Summer") :?>selected = "selected"<?php endif;?>>Summer</option>
                                </select>
                            </div>
                            <div class="col-lg-4" id="acad-yr">
                                <label class = "form-label" for="academicYear">Academic Year:(of Requested Documents)*</label><br/>
                                <select name="academicYear" id="academicYear" class = "form-select">
                                    <option value="">Select</option>
                                    <option value="2020-2021" <?php if("$academicYear" == "2020-2021") :?>selected = "selected"<?php endif;?>>2020-2021</option>
                                    <option value="2019-2020" <?php if("$academicYear" == "2019-2020") :?>selected = "selected"<?php endif;?>>2019-2020</option>
                                    <option value="2018-2019" <?php if("$academicYear" == "2018-2019") :?>selected = "selected"<?php endif;?>>2018-2019</option>
                                    <option value="2017-2018" <?php if("$academicYear" == "2017-2018") :?>selected = "selected"<?php endif;?>>2017-2018</option>
                                </select></br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <label class = "form-label" for="purpose">Purpose of Request:*</label><br/>
                                <select name="purpose" id="purpose" class = "form-select" required>
                                    <option value="">Select</option>
                                    <option value="Scholarship Requirement" <?php if("$purpose" == "Scholarship Requirement") :?>selected = "selected"<?php endif;?>>Scholarship Requirement</option>
                                    <option value="Transfering School" <?php if("$purpose" == "Transfering School") :?>selected = "selected"<?php endif;?>>Transfering School</option>
                                    <option value="For Enrollment Purposes" <?php if("$purpose" == "For Enrollment Purposes") :?>selected = "selected"<?php endif;?>>Enrollment Purposes</option>
                                    <option value="Educational Purposes" <?php if("$purpose" == "Educational Purposes") :?>selected = "selected"<?php endif;?>>Educational Purposes</option>
                                    <option value="Personal Copy" <?php if("$purpose" == "Personal Copy") :?>selected = "selected"<?php endif;?>>Personal Copy</option>
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
                            <button class="btn btn-info text-uppercase" name="submit" href="#request" style="float: right;" id="submit" >Submit</button>
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
            $('#cog, #cor').change(function(){
                if($(this).is(":checked")){
                    $('#acad-yr').addClass('active');
                    $('#sem').addClass('active');
                }
                else{
                    if(!(($('#cor').is(":checked") && !('#cog').is(":checked")) || ($('#cog').is(":checked") && !('#cor').is(":checked")))){
                        $('#acad-yr').removeClass('active');
                        $('#sem').removeClass('active');
                    }
                    
                }
            });
            <?php if(isset($_SESSION['document0']) || isset($_SESSION['document1'])) :?>
                $('#acad-yr').addClass('active');
                $('#sem').addClass('active');
           <?php endif;?>
           
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
           <?php if("$purpose" == "Others") :?>
                $('#specify').addClass('active');
            <?php endif;?>
            /*var button = document.getElementById('submit');

            button.addEventListener('click', function(event) { 
                setTimeout(function () {
                    event.target.disabled = true;
                }, 0);
            });*/
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
                        html: '<?php foreach($error as $er){ echo "* ".$er."<br/>"; }?>', 
                        icon:'error', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    });
                </script>
                <?php unset($_SESSION['submitted-request']);?>
            <?php endif;?>
        <?php endif;?>
    </body>
</html>