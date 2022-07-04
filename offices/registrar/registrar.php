<?php
    require_once('../database.php');
    require_once('nav.php');
    require_once('../functions.php');
    
    session_start();
    $myInfo = [];
    auth();

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])){
        logout();
    }
    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Registrar Account</title>
        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/admin.css" rel="stylesheet" />
        <link href="../assets/css/bootstrap.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="../assets/img/bsu.png" />
    </head>
    <body>
        <div class="wrapper">
            <?php sidebar(0);?>
            <div id="content">
                <?php 
                    head();
                    @$content = $_GET['content'];
                    if($content == "dashboard"){
                        dashboard();
                    }
                    else if($content == "profile"){
                        profile();
                    }
                    else if($content == "students"){
                        students();
                    }
                    else if($content == "alumni"){
                        alumni();
                    }
                    else if($content == "requests"){
                        requests();
                    }
                    else if($content == "clearanceIncharge"){
                        clearanceIncharge();
                    }
                    else if($content == "personnels"){
                        personnels();
                    }
                    else if($content == "logs"){
                        logs();
                    }
                ?>
                
                
                
                <footer class="footer py-4">
                    <div class="container">
                        <div class="row align-items-center">
                            <div style="align-items: center;">Copyright &copy; Bulacan State University - Sarmiento Campus 2022</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        
        <script src="../assets/js/jquery-3.3.1.js"></script>
        <script src="../assets/js/jquery-scripts.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    </body>
</html>
