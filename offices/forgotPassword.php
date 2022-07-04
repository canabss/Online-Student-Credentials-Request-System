<?php
    require_once('database.php');
    require_once('validations.php');
    require_once('functions.php');
    $error = [];
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){
        $db = database();
        $email = $db->real_escape_string($_POST['email']);
        $password = $db->real_escape_string($_POST['password']);
        $confirm = $db->real_escape_string($_POST['confirm-password']);
        $id = $_GET['account_id'];
        $key = "iokhwe241kljrf0w98u21$!#?%Krqiop;13j541$@hirohqwr!@$2werqjpo0ue9";
        $error = passwordValidation($password, $confirm, $error);
        
        if(empty($error)){
            $pass = encrypt($password, $key);
            $sql = $db->query("UPDATE accounts SET password = '$pass' WHERE account_id = '$id'");
            if($sql){
                $_SESSION['updated'] = true;
            }
            else{
                $error = "SQL Error";
                $_SESSION['updated'] = false;
            }
        }
        else{
             $_SESSION['updated'] = false;
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
                            <img src="assets/img/BSU-w.png" style= "height:40px; width: 220px; margin-left: 15px;" alt="OSCRS-Logo" />
                        </div>
                    </div>
                </nav>
                <div class="content">
                    <div class="forgot-page">
                        <div class="col-lg-12">
                            <div class="forgot-box mx-auto">
                                <div class="header text-center">
                                    <h2>Forgot Password</h2>
                                </div><br/>           
                                <form action="" method="post">
                                    <div class="col-lg-12">
                                        <label  class = "form-label" for="password"><?php echo $_GET['email'];?></label>
                                        <input type="hidden" class="form-control" name="email" value="<?php echo $_GET['email'];?>"></br>
                                    </div>
                                    <div class="col-lg-12">
                                        <label  class = "form-label" for="password">Password: </label>
                                        <input type="password" class="form-control" placeholder="Password (8 Charanters Minimum)" name="password" minlength="8" autocomplete="off" required></br>
                                    </div>
                                    <div class="col-lg-12">
                                        <label  class = "form-label" for="confirm-password">Confirm Password: </label>
                                        <input type="password" class="form-control" placeholder="Confirm Password" name="confirm-password" minlength="8" autocomplete="off" required><br>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn-submit text-uppercase" name="submit">Submit</button>
                                    </div>
                                </form><br>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer bg-maroon py-4">
                    <div class="container">
                        <div class="row align-items-center">
                            <div style="align-items: center;">Copyright &copy; Bulacan State University - Sarmiento Campus 2022</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
        <?php if(isset($_SESSION['updated'])) :?>
            <?php if($_SESSION['updated'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Successfully Password Reset',
                        html: 'You may login now, thank you!',
                        icon:'info', 
                        confirmButtonColor: 'maroon',
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            <?php unset($_SESSION['updated']);?>
                            window.location.replace('https://offices.oscrs-bulsusc.com/');
                        }
                    });
                </script>
                <?php unset($_SESSION['updated']);?>
            <?php endif;?>
        <?php endif; ?>

        <?php if(isset($_SESSION['updated'])) :?>
            <?php if($_SESSION['updated'] == false) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Failed to Reset Password.',
                        html: '<?php foreach($error as $er){ echo "* ".$er."<br/>"; }?>', 
                        icon:'error', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        window.location.replace('https://offices.oscrs-bulsusc.com/forgotPassword?email='<?php echo $_GET['email'];?>'account_id='<?php echo $_GET['account_id'];?>);
                    });
                </script>
                <?php unset($_SESSION['updated']);?>
            <?php endif;?>
        <?php endif;?>
    </body>
</head>