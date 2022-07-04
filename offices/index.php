<?php
    require_once('database.php');
    require_once('validations.php');
    require_once('functions.php');
    require_once('sendEmails.php');
    session_start();
    $error;
    $key = "iokhwe241kljrf0w98u21$!#?%Krqiop;13j541$@hirohqwr!@$2werqjpo0ue9";

    auth1();

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])){
        $db = database();
        $username = $_POST['email'];
        $password = $_POST['password'];
        $userData = [];
        //echo "truw";
        $sql = $db->query("SELECT * FROM accounts WHERE username = '$username'");
        if($sql){
            if($sql->num_rows >0){
                $userData[] = $sql->fetch_array(MYSQLI_ASSOC);
                //echo decrypt($user['password'], $key);
                foreach($userData as $user){
                    $id = $user['account_id'];
                    $sql = $db->query("SELECT status FROM personnels WHERE account_id = '$id'");
                    if($sql){
                        $status = $sql->fetch_array(MYSQLI_ASSOC); 
                        if($status['status'] == "Confirmed"){
                            if($password == decrypt($user['password'], $key)){
                                $sql = $db->query("SELECT link FROM roles WHERE role_id = '".$user['role']."'");
                                if($sql){
                                    $date = new DateTime("now", new DateTimeZone('Asia/Manila'));
                                    $_SESSION['user_id'] = $user['account_id'];
                                    $_SESSION['role'] = $user['role'];
                                    $_SESSION['date']  = $date->format("Y-m-d");
                                    $_SESSION['login']  = $date->format("h:ia");
                                    $link = $sql->fetch_array(MYSQLI_ASSOC); 
                                    header("Location: ".$link['link']."?id=".$user['account_id']);
                                }
                            }
                            else{
                                $error = "Incorrect username or password";
                                $_SESSION['username'] = $username;
                                $_SESSION['logged'] = false;
                            }
                        }
                        else if($status['status'] == "To Confirm"){
                            $error = "Your email is not yet validated. Check your email for confirmation";
                            $_SESSION['username'] = $username;
                            $_SESSION['logged'] = false;
                        }
                        else{
                            $error = "Your access to your account is not yet approve.";
                            $_SESSION['username'] = $username;
                            $_SESSION['logged'] = false;
                        }
                    }
                }
            }
            else {
                $error = "Incorrect username or password";
                $_SESSION['username'] = $username;
                $_SESSION['logged'] = false;
            }
        }
    }
    else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot'])){
        $db = database();
        $email = $_POST['email'];
        $info = [];
        $valid = false;
        $sql = $db->query("SELECT * FROM personnels");
        
        while($data = $sql->fetch_assoc()){
            $info[] = $data;
        }
        
        foreach($info as $data){
            if($email == $data['email']){
                $valid = true;
                forgotPassword($email, $data['first_name'], $data['last_name'], $data['account_id']);
                break;
            }
        }
        if($valid){
            $_SESSION['success'] = true;
        }
        else if(!$valid){
            $_SESSION['success'] = false;
            $error = "No email found";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Login</title>
        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css"/>
        <link href="assets/css/admin.css" rel="stylesheet" />
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/logo.png" />
    </head>
    <body>
        <div class="wrapper">
            <div id="content" class="content">
                <nav class="navbar navbar-expand-lg navbar-dark bg-maroon fixed-top">
                    <div class="container-fluid">
                        <div>
                            <img src="assets/img/BSU-w.png" style= "height:40px; width: 250px; margin-left: 15px;" alt="OSCRS-Logo"/>
                        </div>
                    </div>
                </nav>
                <div class="login-page">
                    <h1 class="title text-center">ONLINE STUDENT CREDENTIAL REQUEST SYSTEM</h1><br><br>
                    <div class="login-box">
                        <div class="header text-center">
                            <h2>LOGIN</h2>
                        </div><br/>           
                        <form class="form-signin" action="" method="POST">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Username" name="email" value="<?php echo !isset($_SESSION['username']) ? '' : $_SESSION['username'] ?>" required>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Password" name="password" required>
                            </div>
                            <p>
                                <div class="link text-center clicks" style="background: white; float: left; cursor: pointer;">forgot password?</div><br>
                            </p>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn-login text-uppercase" name="login">login</button>
                                </div>
                            </div>
                        </form><br>
                        <p> Don't have account yet?
                            <a href="https://offices.oscrs-bulsusc.com/create" class="link text-center" style="background: white; border: none;">Create account.</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer bg-maroon py-4">
            <div class="container ">
                <div class="row align-items-center">
                    <div style="align-items: center;">Copyright &copy; Bulacan State University - Sarmiento Campus 2022</div>
                </div>
            </div>
        </footer>
        <script src="assets/js/jquery-3.3.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.clicks').click(function () {
                    $('#forgot').modal('show');
                });     
            });
            $(document).ready(function () {
                $('#forgot').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            });
            
            $(document).ready(function () {
                $('.close-upload-panel').click(function () {
                    $('#forgot').modal('hide');
                });     
            });  
        </script>
        <?php if(isset($_SESSION['logged'])) :?>
            <?php if($_SESSION['logged'] == false) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Failed to log-in.',
                        html: '<?php echo "* ".$error;?>', 
                        icon:'error', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            <?php unset($_SESSION['logged']);?>
                        }
                    });
                </script>
            <?php endif;?>
        <?php endif;?>
        
        <?php if(isset($_SESSION['success'])) :?>
            <?php if($_SESSION['success'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Successfully Verify email', 
                        html: 'Please check your email for the link for reset of password',
                        icon:'info', 
                        confirmButtonColor: 'maroon',
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('https://offices.oscrs-bulsusc.com/');
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
                        title: 'Failed to Verify email',
                        html: '<?php echo "* ".$error;?>', 
                        icon:'error', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            $(document).ready(function () {
                                $('#forgot').modal('show');
                            });   
                        }
                    });
                </script>
                <?php unset($_SESSION['success']);?>
            <?php endif;?>
        <?php endif;?>
        
        <?php if(isset($_SESSION['send'])) :?>
            <?php if($_SESSION['send'] == false) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Failed to add student',
                        html: '<?php foreach($error as $er){ echo "* ".$er."<br/>"; }?>', 
                        icon:'error', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            $(document).ready(function () {
                                $('#forgot').modal('show');
                            });   
                        }
                    });
                </script>
                <?php unset($_SESSION['add-accepted']);?>
            <?php endif;?>
        <?php endif;?>
        <div class="modal fade" id="forgot">
            <div class="container">
                <div class="modal-dialog modal-md">
                    <div class="modal-content " style="margin-top: 250px;">
                        <div class="modal-header">
                            <h4 class="modal-title">Forgot Password</h4>
                            <button class="close-upload-panel" name="close-upload-panel" id="close-upload-panel">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" >
                            <div class="row" style="padding: 50px">
                                <div class="col-12">
                                    <input class="form-control" type="email" name="email" id="email" placeholder="Email" autocomplete="off" required><br>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="submit" class="btn btn-info mx-auto" name="forgot" >SUBMIT</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>