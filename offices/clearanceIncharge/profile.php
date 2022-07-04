<?php
    require_once('../database.php');
    require_once('nav.php');
    require_once('../validations.php');
    require_once('../functions.php');
    session_start();
    $myInfo = [];
    $error = [];
    $roles = array("", "Registrar", "Dean", "Secretary", "Cashier", "Department Head", "Adviser", "Guidance Officer", "Student Affairs", "Nurse", "Librarian");
    $index = $_SESSION['role'];
    $title = "";
    $key = "iokhwe241kljrf0w98u21$!#?%Krqiop;13j541$@hirohqwr!@$2werqjpo0ue9";
    $db = database();
    $accountId = $_SESSION['user_id'];
    if(!isset($_SESSION['user_id'])){
        header("Location: https://oscrs-bulsusc.com/login");
	    exit(); 
    }
    else if($_SESSION['role'] == "1"){
        $db = database();
        $sql = $db->query("SELECT link From roles WHERE role_id = '".$_SESSION['role']."'");
        if($sql){
            $link = $sql->fetch_array(MYSQLI_ASSOC); 
            header("Location: ../".$link['link']."?id=".$_SESSION['user_id']);
        }
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])){
        if(isset($_FILES['input-profile-img']['name'])){
            $picture = basename($_FILES['input-profile-img']['name']);
            //$error = profilePictureValidation($picture, $error);
            $validFileTypes = ['jpeg', 'jpg', 'png'];
            if(empty($picture)){
                $error = "Please Select an image file";
            }
            else{
                $fileType = pathinfo($picture, PATHINFO_EXTENSION);
                if(!in_array($fileType, $validFileTypes)){
                    $error = "Invalid image file type. Please select another.";
                }
            }
            if(empty($error)){
                $image = $_FILES['input-profile-img']['tmp_name'];
                $content = addslashes(file_get_contents($image));
                $sql = $db->query("SELECT profile_picture_file FROM profiles WHERE account_id = '$accountId'");
                if($sql){
                    if($sql->num_rows > 0){ 
                        $sql = $db->query("UPDATE profiles SET profile_picture_file = '$content', uploaded_date = NOW() WHERE account_id = '$accountId'");
                        if($sql){
                            unset($_SESSION['picture']);
                            $_SESSION['picture'] =  $content;
                            $_SESSION['success'] = true;
                            $title = "Profile picture successfully uploaded.";
                        }
                        else{
                            $error[] = "SQL Error.";
                            $open = "upload-panel";
                            $_SESSION['success'] = false;
                            $title = "Unable to upload profile picture.";
                        }
                    }
                    else{
                        $sql = $db->query("INSERT INTO profiles(profile_picture_file, account_id, uploaded_date) VALUES('$content', '$accountId', NOW())");
                        if($sql){
                            unset($_SESSION['picture']);
                            $_SESSION['picture'] =  $content;
                            $_SESSION['success'] = true;
                            $title = "Profile picture successfully uploaded.";
                        }
                        else{
                            $error[] = "SQL Error.";
                            $open = "upload-panel";
                            $_SESSION['success'] = false;
                            $title = "Unable to upload profile picture.";
                        }
                    }
                }
            }
            else{
                $open = "upload-panel";
                $_SESSION['success'] = false;
                $title = "Failed to upload profile picture.";
            }
        }
        else if(isset($_POST['lastName']) && isset($_POST['firstName']) &&isset($_POST['middleName'])){
            $lastName = $db->real_escape_string(ucFirst($_POST['lastName']));
            $firstName = $db->real_escape_string(ucFirst($_POST['firstName']));
            $middleName = $db->real_escape_string(ucFirst($_POST['middleName']));

            $error = lastnameValidation($lastName, $error);
            $error = firstnameValidation($firstName, $error);
            if(empty($middleName)){
                $middleName = middlenameIsEmpty($middleName);
            }
            else{
                $error = middlenameValidation($middleName, $error);
            }

            if(empty($error)){
                $sql = $db->query("UPDATE personnels SET last_name='$lastName', first_name='$firstName', middle_name='$middleName'  WHERE account_id = '$accountId'");
                if($sql){
                    $_SESSION['success'] = true;
                    $title = "Name updated successfully.";
                }
                else{
                    $error[] = "SQL Error.";
                    $open = "edit-name";
                    $_SESSION['success'] = false;
                    $title = "Failed to update Birthday.";
                }
            }
            else{
                $open = "edit-name";
                $_SESSION['success'] = false;
                $title = "Failed to update name.";
            }
        }
        else if(isset($_POST['birthday'])){
            $birthday = $db->real_escape_string($_POST['birthday']);
            birthdaySessionSet($birthday);

            $sql = $db->query("UPDATE personnels SET birthday='$birthday' WHERE account_id = '$accountId'");
            if($sql){
                $_SESSION['success'] = true;
                $title = "Birthday updated successfully.";
            }
            else{
                $error[] = "SQL Error.";
                $open = "edit-birthday";
                $_SESSION['success'] = false;
                $title = "Failed to update Birthday.";
            }
        }
        else if(isset($_POST['gender'])){
            $gender = $db->real_escape_string($_POST['gender']);
            genderSessionSet($gender);
            $sql = $db->query("UPDATE personnels SET gender='$gender' WHERE account_id = '$accountId'");
            if($sql){
                $_SESSION['success'] = true;
                $title = "Birthday updated successfully.";
            }
            else{
                $error[] = "SQL Error.";
                $open = "edit-gender";
                $_SESSION['success'] = false;
                $title = "Failed to update Gender.";
            }
        }
        //else if(isset()){

        //}
        else if(isset($_POST['new-username'])){
            $username = $db->real_escape_string($_POST['new-username']);
            $sql = $db->query("UPDATE accounts SET username='$username' WHERE account_id = '$accountId'");
            if($sql){
                $_SESSION['success'] = true;
                $title = "Username updated successfully.";
            }
            else{
                $error[] = "SQL Error.";
                $open = "edit-username";
                $_SESSION['success'] = false;
                $title = "Failed to update Username.";
            }
        }
        
        else if(isset($_POST['old-password']) && isset($_POST['new-password']) && isset($_POST['confirm-new-password'])){
            $oldPassword = $db->real_escape_string($_POST['old-password']);
            $newPassword = $db->real_escape_string($_POST['new-password']);
            $confirmnewPassword = $db->real_escape_string($_POST['confirm-new-password']);

            $sql = $db->query("SELECT password FROM accounts WHERE account_id = '$accountId'");
            if($sql){
                $userpassword[] = $sql->fetch_array(MYSQLI_ASSOC);
                foreach($userpassword as $userPass){
                    if($oldPassword == decrypt($userPass['password'], $key)){
                        $error = passwordValidation($newPassword, $confirmnewPassword, $error);
                        if(empty($error)){
                            $password = encrypt($newPassword, $key);
                            $sql = $db->query("UPDATE accounts SET password='$password' WHERE account_id = '$accountId'");
                            if($sql){
                                $_SESSION['success'] = true;
                                $title = "Change password successfully.";
                            }
                            else{
                                $error[] = "SQL Error.";
                                $open = "edit-password";;
                                $_SESSION['success'] = false;
                                $title = "Failed to change Password.";
                            }
                        }
                        else{
                            $open = "edit-password";
                            $_SESSION['success'] = false;
                            $title = "Failed to change Password.";
                        }
                    }
                    else{
                        $error[] = "Incorrect old Password.";
                        $open = "edit-password";
                        $_SESSION['success'] = false;
                        $title = "Failed to change Password.";
                    }
                }
            }
        }
        
        $sql = $db->query("SELECT * FROM personnels WHERE account_id = '".$accountId."'");
        if($sql){
            $myInfo[] = $sql->fetch_array(MYSQLI_ASSOC);
            foreach($myInfo as $info){
                $_SESSION['employeeno'] = $info['employee_no'];
                $_SESSION['lastname'] = $info['last_name'];
                $_SESSION['firstname'] = $info['first_name'];
                $_SESSION['middlename'] = $info['middle_name'];
                $_SESSION['email1'] = $info['email'];
                $_SESSION['birthday1'] = $info['birthday'];
                $_SESSION['gender'] = $info['gender'];
            }
        }
        $sql = $db->query("SELECT * FROM accounts WHERE account_id = '".$accountId."'");
        if($sql){
            $myAccount[] = $sql->fetch_array(MYSQLI_ASSOC);
            foreach($myAccount as $account){
                $_SESSION['username'] = $account['username'];
                $_SESSION['password'] = $account['password'];
            }
        }
        $sql = $db->query("SELECT profile_picture_file FROM profiles WHERE account_id = '$accountId'");
        if($sql){
            if($sql->num_rows > 0){
                $picture = $sql->fetch_array(MYSQLI_ASSOC);
                $_SESSION['picture'] =  $picture['profile_picture_file'];
            }
        }
    }

    else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])){
        logout();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Profile</title>
        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/admin.css" rel="stylesheet" />
        <link href="../assets/css/bootstrap.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="../assets/img/logo.png" />
    </head>
    <body>
        <div class="wrapper">
            <?php sidebar(1);?>
           
            <div id="content">
                <?php head();?>
                <section class="content" style="margin-top: 100px;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="container">
                                    <div class="cards align-items-stretch mb-5 mx-auto">
                                            <h1 style="margin-left: 20px">Profile</h1>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <?php if(isset($_SESSION['picture'])){?>
                                                    <img src="data:image/image/jpg:charset=utf8;base64,<?php echo base64_encode($_SESSION['picture']);?>" class="rounded mx-auto d-block" id="profile-pic"></img>
                                                <?php } else{?>
                                                    <img src="../assets/img/default.jpeg" class="rounded mx-auto d-block" id="profile-pic"></img>
                                                <?php }?>
                                                <div class="text-center">
                                                    <button class="change-profile text-uppercase" name="change-profile" id="change-profile">
                                                        <i class='fas fa-plus-circle'></i> upload profile
                                                    </button><br><br>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <table class="table" id="profile-info">
                                                    <tr>
                                                        <th>
                                                            <p>Role</p>
                                                        </th>
                                                        <td class="value">
                                                            <p>
                                                                <?php 
                                                                    if($index == '5' || $index == '6'){
                                                                        echo $roles[$index].' - '.$_SESSION['department'];
                                                                    }
                                                                    else{
                                                                        echo $roles[$index];
                                                                    }
                                                                ?>
                                                            </p>
                                                        </td>
                                                        <td></dt>
                                                    </tr>
                                                    <tr>
                                                        <th><p>Employee No.</p></th>
                                                        <td class="value">
                                                            <p><?php echo $_SESSION['employeeno'];?></p>
                                                        </td>
                                                        <td></dt>
                                                    </tr>
                                                    <tr>
                                                        <th><p>Name</p></th>
                                                        <td class="value">
                                                            <p>
                                                                <?php $_SESSION['middlename'] == "N/A" ? $mname = "" : $mname = $_SESSION['middlename'];?>
                                                                <?php echo $_SESSION['firstname']." ".$mname." ".$_SESSION['lastname']?>
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <button type = 'button' class='btn-xs' name = 'btn-edit-name' id = 'btn-edit-name' value = '<?php echo "";?>'>
                                                                <i class='fas fa-edit'></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><p>Birthday</p></th>
                                                        <td class="value">
                                                            <p>
                                                            <?php echo $_SESSION['birthday1'];?>
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <button type = 'button' class='btn-xs' name = 'btn-edit-birthday' id = 'btn-edit-birthday' value = '<?php echo "";?>'>
                                                                <i class='fas fa-edit'></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><p>Gender</p></th>
                                                        <td class="value">
                                                            <p>
                                                            <?php echo $_SESSION['gender'];?>
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <button type = 'button' class='btn-xs' name = 'btn-edit-gender' id = 'btn-edit-gender' value = '<?php echo "";?>'>
                                                                <i class='fas fa-edit'></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><p>Email</p></th>
                                                        <td class="value">
                                                            <p><?php echo $_SESSION['email1'];?></p>
                                                        </td>
                                                        <td>
                                                            <button type = 'button' class='btn-xs' name = 'btn-edit-email' id = 'btn-edit-email' value = '<?php echo "";?>'>
                                                                <i class='fas fa-edit'></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            <p>Username</p>
                                                        </th>
                                                        <td class="value">
                                                            <p><?php echo $_SESSION['username'];?></p>
                                                        </td>
                                                        <td>
                                                            <button type = 'button' class='btn-xs' name = 'btn-edit-username' id = 'btn-edit-username' value = '<?php echo "";?>'>
                                                                <i class='fas fa-edit'></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            <p>Password</p>
                                                        </th>
                                                        <td class="value">
                                                            <p>********</p>
                                                        </td>
                                                        <td>
                                                            <button type = 'button' class='btn-xs' name = 'btn-edit-password' id = 'btn-edit-password' value = '<?php echo "";?>'>
                                                                <i class='fas fa-edit'></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </table>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
            </div>
        </div>
        <div class="modal fade" id="upload-panel">
            <div class="container">
                <div class="modal-dialog modal-md">
                        <div class="modal-content " style="margin-top: 130px;">
                            <div class="modal-header">
                                <h4 class="modal-title">Upload Profile Picture</h4>
                                <form method="POST" action="">
                                    <button type="submit" class="close-upload-panel" name="closs-upload-panel" id="close-upload-panel">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </form>
                            </div>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class = "preview-profile rounded mx-auto d-block">
                                    <img id="preview-profile" src='../assets/img/default.jpeg'>
                                    <div class="upload-wrapper">
                                        <label for="input-profile-img" class="label-profile-img"><i class='fas fa-upload'></i> CHOOSE A FILE</label>
                                        <input type="file" name="input-profile-img" id="input-profile-img" accept="image/png, image/gif, image/jpeg, image/jpg" onchange="previewProfilePicture(this);">
                                    </div>
                                    
                                </div>
                           
                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-info mx-auto" name="save" ><i class='fa fa-check'></i> SAVE</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="edit-name">
            <div class="container">
                <div class="modal-dialog modal-md">
                        <div class="modal-content " style="margin-top: 130px;">
                            <div class="modal-header">
                                <h4 class="modal-title">Change Name</h4>
                                <form method="POST" action="">
                                    <button type="submit" class="close-upload-panel" name="closs-upload-panel" id="close-upload-panel">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </form>
                            </div>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <label class = "form-label" for="lastName" >Last Name: *</label>
                                            <input class = "form-control" type="text" placeholder="Ex. Dela Cruz" name="lastName" id="lastName" value="<?php echo $_SESSION['lastname'];?>" required/></br>
                                        </div>
                                        <div class="col-12">
                                            <label class = "form-label" for="firstName">First Name: *</label>
                                            <input class = "form-control" type="text" placeholder="Ex. Juan" name="firstName" id="firstName" value="<?php echo $_SESSION['firstname'];?>" required/></br>
                                        </div>
                                        <div class="col-12">
                                            <label class = "form-label" for="middleName">Middle Name:(Optional)</label>
                                            <input class = "form-control" type="text" placeholder="Ex. Ponce" name="middleName" id="middleName" value="<?php echo $_SESSION['middlename'] == 'N/A' ? '' :$_SESSION['middlename'];?>"/></br>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-info mx-auto" name="save"><i class='fa fa-check'></i> SAVE</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="edit-birthday">
            <div class="container">
                <div class="modal-dialog modal-md">
                        <div class="modal-content " style="margin-top: 230px;">
                            <div class="modal-header">
                                <h4 class="modal-title">Change Birthday</h4>
                                <form method="POST" action="">
                                    <button type="submit" class="close-upload-panel" name="close-edit-birthday" id="close-edit-birthday">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </form>
                            </div>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <label class = "form-label" for="birthday1">Birthday: </label>
                                            <input class = "form-control text-uppercase" type="date" name="birthday"  id="birthday" value="<?php echo !isset($_SESSION['birthday1']) ? '' : $_SESSION['birthday1'] ?>" required/></br>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-info mx-auto" name="save"><i class='fa fa-check'></i> SAVE</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="edit-gender">
            <div class="container">
                <div class="modal-dialog modal-md">
                        <div class="modal-content " style="margin-top: 230px;">
                            <div class="modal-header">
                                <h4 class="modal-title">Change Gender</h4>
                                <form method="POST" action="">
                                    <button type="submit" class="close-upload-panel" name="close-edit-gender" id="close-edit-gender">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </form>
                            </div>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12"><br>
                                            <label class = "form-label" for="gender">Gender: </label><br/>
                                            <select name="gender" id="gender" class = "form-select" required>
                                                <option value="">Select</option>
                                                <option value="Male" <?php if($_SESSION['gender'] == "Male") :?>selected = "selected"<?php endif;?>>Male</option>
                                                <option value="Female" <?php if($_SESSION['gender'] == "Female") :?>selected = "selected"<?php endif;?>>Female</option>
                                                <option value="Custom" <?php if($_SESSION['gender'] == "Custom") :?>selected = "selected"<?php endif;?>>Custom</option>
                                            </select><br>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-info mx-auto" name="save"><i class='fa fa-check'></i> SAVE</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="edit-email">
            <div class="container">
                <div class="modal-dialog modal-md">
                        <div class="modal-content " style="margin-top: 130px;">
                            <div class="modal-header">
                                <h4 class="modal-title">Change Email</h4>
                                <form method="POST" action="">
                                    <button type="submit" class="close-upload-panel" name="closs-upload-panel" id="close-upload-panel">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </form>
                            </div>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="tab">
                                            <div class="col-12">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo $_SESSION['email1'];?>" required><br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-info mx-auto" name="save"  id="save"><i class='fa fa-check'></i> SAVE</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="edit-username">
            <div class="container">
                <div class="modal-dialog modal-md">
                        <div class="modal-content " style="margin-top: 180px;">
                            <div class="modal-header">
                                <h4 class="modal-title">Change Username</h4>
                                <form method="POST" action="">
                                    <button type="submit" class="close-upload-panel" name="closs-upload-panel" id="close-upload-panel">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </form>
                            </div>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <p>Username: <b><?php echo $_SESSION['username'];?></b></p>
                                        </div>
                                        <div class="col-12">
                                            <label for="new-username">New Username</label>
                                            <input type="text" class="form-control" placeholder="New Username" name="new-username" value="<?php echo $_SESSION['username'];?>" required><br>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-info mx-auto" name="save" ><i class='fa fa-check'></i> SAVE</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="edit-password">
            <div class="container">
                <div class="modal-dialog modal-md">
                        <div class="modal-content " style="margin-top: 130px;">
                            <div class="modal-header">
                                <h4 class="modal-title">Change Password</h4>
                                <form method="POST" action="">
                                    <button type="submit" class="close-upload-panel" name="closs-upload-panel" id="close-upload-panel">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </form>
                            </div>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="old-password">Old Password</label>
                                            <input type="password" class="form-control" placeholder="Password (8 Charanters Minimum)" name="old-password" minlength="8" autocomplete="off" required><br>
                                        </div>
                                        <div class="col-12">
                                            <label for="new-password">New Password</label>
                                            <input type="password" class="form-control" placeholder="New Password" name="new-password" minlength="8" autocomplete="off" required><br>
                                        </div>
                                        <div class="col-12">
                                            <label for="confirm-new-password">Confirm New Password</label>
                                            <input type="password" class="form-control" placeholder="Confirm New Password" name="confirm-new-password" minlength="8" autocomplete="off" required><br>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-info mx-auto" name="save" ><i class='fa fa-check'></i> SAVE</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../assets/js/jquery-3.3.1.js"></script>
        <script src="../assets/js/jquery-scripts.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script type='text/javascript' src='../assets/sweetalert/dist/sweetalert2.all.min.js'></script>
        
        <?php if(isset($_SESSION['success'])) :?>
            <?php if($_SESSION['success'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: '<?php echo $title?>',
                        icon:'success', 
                        confirmButtonColor: 'maroon',
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('profile.php?id=<?php echo $_SESSION['user_id']?>');
                        }
                    });
                </script>
                <?php unset($_SESSION['success']);?>
            <?php endif;?>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])) :?>
            <?php if($_SESSION['success'] == false) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: '<?php echo $title?>',
                        html: '<?php foreach($error as $er){ echo "* ".$er."<br/>"; }?>',
                        icon:'error', 
                        confirmButtonColor: 'maroon',
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            if("<?php echo $open?>" == "upload-panel"){
                                $(document).ready(function () {
                                    $('#upload-panel').modal('show');
                                });
                            }
                            else if("<?php echo $open?>" == "edit-name"){
                                $(document).ready(function () {
                                    $('#edit-name').modal('show');
                                });
                            }
                            else if("<?php echo $open?>" == "edit-birthday"){
                                $(document).ready(function () {
                                    $('#edit-birthday').modal('show');
                                });
                            }
                            else if("<?php echo $open?>" == "edit-gender"){
                                $(document).ready(function () {
                                    $('#edit-gender').modal('show');
                                });
                            }
                            else if("<?php echo $open?>" == "edit-email"){
                                $(document).ready(function () {
                                    $('#edit-email').modal('show');
                                });
                            }
                            else if("<?php echo $open?>" == "edit-username"){
                                $(document).ready(function () {
                                    $('#edit-username').modal('show');
                                });
                            }
                            else if("<?php echo $open?>" == "edit-password"){
                                $(document).ready(function () {
                                    $('#edit-password').modal('show');
                                });
                            }
                        }
                    });
                </script>
                <?php unset($_SESSION['success']);?>
            <?php endif;?>
        <?php endif; ?>
    </body>
</html>
