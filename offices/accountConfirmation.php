<?php
    require_once("database.php");
    $db = database();
    $isExpire = false;
    $status = [];
    $employeeNo = $db->real_escape_string($_GET['employee_no']);
    $role = $db->real_escape_string($_GET['role']);
    $status1 = "";
    $sql = $db->query("SELECT * FROM personnels WHERE employee_no = '$employeeNo'");

    if($sql){
        if($sql -> num_rows > 0){
            $status = $sql->fetch_array(MYSQLI_ASSOC);
            if($status['status'] == "To Confirm"){
                if($role == '5'){
                    $dept = $status['department'];
                    $sql1 = $db->query("SELECT * FROM personnels WHERE role = '$role' AND department = '$dept'");
                    if($sql1){
                        if($sql1 -> num_rows == 1){
                            $status1 = "Confirmed";
            				$sql = $db->query("UPDATE personnels SET status = '$status1' WHERE employee_no = '$employeeNo'");
                        }
                        else{
                            $status1 = "Pending";
            				$sql = $db->query("UPDATE personnels SET status = '$status1' WHERE employee_no = '$employeeNo'");
                        }
                    }
                }
                else if ($role == '6'){
                    $status1 = "Pending";
        			$sql = $db->query("UPDATE personnels SET status = '$status1' WHERE employee_no = '$employeeNo'");
                }
    			else{
    			    $sql2 = $db->query("SELECT * FROM personnels WHERE role = '$role'");
    			    if($sql2){
                        if($sql2 -> num_rows == 1){
                            $status1 = "Confirmed";
            				$sql = $db->query("UPDATE personnels SET status = '$status1' WHERE employee_no = '$employeeNo'");
                        }
                        else{
                            $status1 = "Pending";
            				$sql = $db->query("UPDATE personnels SET status = '$status1' WHERE employee_no = '$employeeNo'");
                        }
    			    }
    			}
            }
            else{
                $isExpire = true;
            }
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
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <link href="assets/css/homepage.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/logo.png" />
    </head>
    <body>
        <div>
            <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav" style="background: maroon;">
                <div class="container-fluid">
                    <a class="navbar-brand" href="https://oscrs-bulsusc.com/"><img src="assets/img/BSU-w.png" style= "height:40px; width: 250px" alt="OSCRS-Logo" /></a>
                    
                </div>
            </nav>
        </div>
        
        <header class="masthead">
            <div class="container">
                <?php if(!$isExpire):?>
                    <?php if($status1 == 'Confirmed'):?>
                        <br><br><br>
                        <div class="masthead-heading text-uppercase text-muted">Your email has been confirmed.</div>
                        <div class="masthead-subheading text-muted">You can access your account now. Click the button below.</div>
                        <a class="btn btn-info btn-xl text-uppercase" href="https://offices.oscrs-bulsusc.com/">Login Now</a>
                        <br><br><br>
                    <?php endif;?>
                    <?php if($status1 == 'Pending'):?>
                        <br><br><br>
                        <div class="masthead-heading text-uppercase text-muted">Your email has been confirmed.</div>
                        <div class="masthead-subheading text-muted">Please wait for your respected office to approve your account.</div>
                        <br><br><br>
                    <?php endif;?>
                <?php endif;?>
                <?php if($isExpire):?>
                    <br><br><br><br>
                    <div class="masthead-heading text-uppercase text-muted">Your Email is already Verified.</div>
                    <br><br><br>
                <?php endif;?>
            </div>
        </header>

        <footer class="footer py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div style="align-items: center;">Copyright &copy; Bulacan State University - Sarmiento Campus 2022</div>
                </div>
            </div>
        </footer>
        <script src="assets/js/jquery-3.3.1.js"></script>
    </body>
</html>