<?php
    require_once('database.php');
    require_once('validations.php');
    require_once('functions.php');
    $error = [];
    $key = "iokhwe241kljrf0w98u21$!#?%Krqiop;13j541$@hirohqwr!@$2werqjpo0ue9";
    $isSelected = "";
    $gender = "";
    $department = '';
    auth1();

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){ 
        $db = database();
        $employeeNo = $db->real_escape_string($_POST['employeeNo']);
        $lastName = $db->real_escape_string(ucFirst($_POST['lastName']));
        $firstName = $db->real_escape_string(ucFirst($_POST['firstName']));
        $middleName = $db->real_escape_string(ucFirst($_POST['middleName']));
        $birthday = $db->real_escape_string($_POST['birthday']);
        $email = $db->real_escape_string($_POST['email']);
        $gender = $db->real_escape_string($_POST['gender']);
        $role = $db->real_escape_string($_POST['role']);
        $department = $db->real_escape_string($_POST['department']);
        $username = $db->real_escape_string($_POST['username']);
        $password = $db->real_escape_string($_POST['password']);
        $confirm = $db->real_escape_string($_POST['confirm-password']);


        $error = employeeNoValidation($employeeNo, $error);
        $error = lastnameValidation($lastName, $error);
        $error = firstnameValidation($firstName, $error);
        if(empty($middleName)){
            $middleName = middlenameIsEmpty($middleName);
        }
        else{
            $error = middlenameValidation($middleName, $error);
        }
        birthdaySessionSet($birthday);
        $error = employeeEmailValidation($email, $error);
        $email = emailIsEmpty($email);
        genderSessionSet($gender);
        
        $email = emailIsEmpty($email);
        $error = usernameValidation($username, $error);
        $error = passwordValidation($password, $confirm, $error);

        if(empty($error)){
            $password = encrypt($password, $key);

            if(createAccount($role, $username, $password)){
                $accountId = [];
                $sql = $db->query("SELECT account_id FROM accounts WHERE username = '$username'");
                if($sql){
                    $accountId[] = $sql->fetch_array(MYSQLI_ASSOC);
                    foreach($accountId as $id){
                        if(addEmployee($employeeNo, $lastName, $firstName, $middleName, $birthday, $email, $gender, $id['account_id'], $role, $department)){
                            $_SESSION['created'] = true;
                        }
                        else{
                            $error[] = "SQL Error";
                            $_SESSION['created'] = false;
                        }
                    }
                }
            }
            else{
                $error[] = "SQL Error";
                $_SESSION['created'] = false;
            }
        }
        else{
            $isSelected = $role;
            $_SESSION['created'] = false;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Create Account - Credential Request System</title>
        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css"/>
        <link href="assets/css/admin.css" rel="stylesheet" />
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/logo.png" />
    </head>
    <body>
        <div id="page-top">
            <nav class="navbar navbar-expand-lg navbar-dark bg-maroon fixed-top" id="mainNav">
                <div class="container-fluid">
                    <div>
                        <img src="assets/img/BSU-w.png" style= "height:40px; width: 250px; margin-left: 15px;" alt="OSCRS-Logo" />
                    </div>
                </div>
            </nav>
        </div>
        <section class="page-section bg-light background" id="request">
        </br></br>
            <div class="container">
                <div class="card align-items-stretch mb-5" style="margin-left: 10px; margin-right:10px;">
                    <a href="https://offices.oscrs-bulsusc.com" class="link text-uppercase" style="padding-bottom: 10px"><i class="fas fa-chevron-left mr"></i> Back</a>
                    <div class="header text-center">
                        
                        <h2>Create Account</h2>
                    </div></br> 
                    <form id="requestForm" method="POST" action="">
                        <div class="row">
                            <div class="col-lg-4">
                                <label class = "form-label" for="employeeNo">Employee No.: *</label>
                                <input class = "form-control" placeholder="Employee No." type="text" name="employeeNo" id="studentNo" maxlength="11" value="<?php echo !isset($_SESSION['employeeNo']) ? '' : $_SESSION['employeeNo'] ?>" required/></br>
                            </div>
                            <div class="col-lg-4">
                                <label  class = "form-label" for="role">Account Role: *</label>
                                <select name="role" id="role" class = "form-select" required>
                                    <option value="">Select</option> 
                                    <option value="1" <?php if("$isSelected" == "1") :?>selected = "selected"<?php endif;?>>Registrar</option>
                                    <option value="2" <?php if("$isSelected" == "2") :?>selected = "selected"<?php endif;?>>Campus Dean</option>
                                    <option value="3" <?php if("$isSelected" == "3") :?>selected = "selected"<?php endif;?>>Campus Secretary</option>
                                    <option value="4" <?php if("$isSelected" == "4") :?>selected = "selected"<?php endif;?>>Cashier</option>
                                    <option value="5" <?php if("$isSelected" == "5") :?>selected = "selected"<?php endif;?>>Department Head</option>
                                    <option value="6" <?php if("$isSelected" == "6") :?>selected = "selected"<?php endif;?>>Instructor</option>
                                    <option value="7" <?php if("$isSelected" == "7") :?>selected = "selected"<?php endif;?>>Guidance</option>
                                    <option value="8" <?php if("$isSelected" == "8") :?>selected = "selected"<?php endif;?>>Student Affairs</option>
                                    <option value="9" <?php if("$isSelected" == "9") :?>selected = "selected"<?php endif;?>>Campus Nurse</option>
                                    <option value="10" <?php if("$isSelected" == "10") :?>selected = "selected"<?php endif;?>>Campus Librarian</option>
                                </select><br>
                            </div>
                            <div class="col-lg-4" id="dept">
                                <label for="department">Department:</label>
                                <select name="department" id="department" class = "form-select">
                                    <option value="">Select</option>
                                    <option value="Industrial and Information Technology Department" <?php if("$department" == "Industrial and Information Technology Department") :?>selected = "selected"<?php endif;?>>Industrial and Information Technology Department</option>
                                    <option value="Business Administration Department" <?php if("$department" == "Business Administration Department") :?>selected = "selected"<?php endif;?>>Business Administration Department</option>
                                    <option value="General Academic and Teacher Education Department" <?php if("$department" == "General Academic and Teacher Education Department") :?>selected = "selected"<?php endif;?>>General Academic and Teacher Education Department</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label class = "form-label" for="lastName">Last Name: *</label>
                                <input class = "form-control" type="text" placeholder="Ex. Dela Cruz" pattern = "[a-zA-Z ]+" name="lastName" id="lastName" value="<?php echo !isset($_SESSION['lastName']) ? '' : $_SESSION['lastName'] ?>" required/></br>
                            </div>
                            <div class="col-lg-4">
                                <label class = "form-label" for="firstName">First Name: *</label>
                                <input class = "form-control" type="text" placeholder="Ex. Juan" pattern = "[a-zA-Z ]+" name="firstName" id="firstName" value="<?php echo !isset($_SESSION['firstName']) ? '' : $_SESSION['firstName'] ?>" required/></br>
                            </div>
                            <div class="col-lg-4">
                                <label class = "form-label" for="middleName">Middle Name:(Optional)</label>
                                <input class = "form-control" type="text" placeholder="Ex. Ponce" pattern = "[a-zA-Z ]+" name="middleName" id="middleName" value="<?php echo !isset($_SESSION['middleName']) ? '' : ($_SESSION['middleName'] == 'N/A' ? '' :$_SESSION['middleName'])?>"/></br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label class = "form-label" for="email">Email Address.: *</label>
                                <input class = "form-control" type="email" placeholder="Ex. juanponcedelacruz@gmail.com" name="email" id="email"  value="<?php echo !isset($_SESSION['email']) ? '' : $_SESSION['email'] ?>"/></br>
                            </div>
                            <div class="col-lg-4">
                                <label class = "form-label" for="birthday">Birthday: *</label>
                                <input class = "form-control text-uppercase" type="date" max= "<?php $date = new DateTime("now", new DateTimeZone('Asia/Manila')); echo $date->format("Y-m-d");?>" name="birthday"  id="birthday" value="<?php echo !isset($_SESSION['birthday']) ? '' : $_SESSION['birthday'] ?>" required/></br>
                            </div>
                            <div class="col-lg-4">
                                <label class = "form-label" for="gender">Gender: *</label><br/>
                                <select name="gender" id="gender" class = "form-select" required>
                                    <option value="">Select</option>
                                    <option value="Male" <?php if("$gender" == "Male") :?>selected = "selected"<?php endif;?>>Male</option>
                                    <option value="Female" <?php if("$gender" == "Female") :?>selected = "selected"<?php endif;?>>Female</option>
                                    <option value="Custom" <?php if("$gender" == "Custom") :?>selected = "selected"<?php endif;?>>Custom</option>
                                </select><br/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label  class = "form-label" for="username">Username: *</label>
                                <input type="text" class="form-control" placeholder="Username" patterm="/^[a-z\d]+$/i" name="username" value="<?php echo !isset($_SESSION['username']) ? '' : $_SESSION['username']?>" required></br>
                            </div>
                            <div class="col-lg-4">
                                <label  class = "form-label" for="password">Password: *</label>
                                <input type="password" class="form-control" placeholder="Password (8 Charanters Minimum)" name="password" minlength="8" autocomplete="off" required></br>
                            </div>
                            <div class="col-lg-4">
                                <label  class = "form-label" for="confirm-password">Confirm Password: *</label>
                                <input type="password" class="form-control" placeholder="Confirm Password" name="confirm-password" minlength="8" autocomplete="off" required><br>
                            </div>
                        </div>
                        <div>
                            
                            <button class="btn btn-info text-uppercase" name="submit" href="#request" style="float: right;">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
            <footer class="footer bg-maroon py-4">
                <div class="container">
                    <div class="row align-items-center">
                        <div style="align-items: center;">Copyright &copy; Bulacan State University - Sarmiento Campus 2021</div>
                    </div>
                </div>
            </footer>
                
        <script src="assets/js/jquery-3.3.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
        <script>
            $(document).ready(function(){
                $("#role").on("change", function(){
                    if($(this).val() == '5' || $(this).val() == '6'){
                        $('#dept').addClass('active');
                    }
                    else{
                        $('#dept').removeClass('active');
                        $('select[name="department"] option[value=""]').attr('selected', 'selected');
                    }
                });
            });
        </script>
        <?php if(isset($_SESSION['created'])) :?>
            <?php if($_SESSION['created'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Account Created Successfully',
                        html: 'We have sent to your email an email verification. Please check your email, thank you!',
                        icon:'info', 
                        confirmButtonColor: 'maroon',
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('https://offices.oscrs-bulsusc.com/');
                        }
                    });
                </script>
                
            <?php endif;?>
        <?php endif; ?>

        <?php if(isset($_SESSION['created'])) :?>
            <?php if($_SESSION['created'] == false) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Failed to create account.',
                        html: '<?php foreach($error as $er){ echo "* ".$er."<br/>"; }?>', 
                        icon:'error', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            var temp = '<?php echo $department ?>';
                            if(temp != ""){
                                $('#dept').addClass('active');
                            }
                            else{
                                $('#dept').removeClass('active');
                                
                            }
                        }
                    });;
                </script>
                <?php unset($_SESSION['created']);?>
            <?php endif;?>
        <?php endif;?>
    </body>
</head>