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
        <title>User Logs</title>
        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/admin.css" rel="stylesheet" />
        <link href="../assets/css/bootstrap.css" rel="stylesheet" />
        <link href="../assets/css/jquery.dataTables.css" rel="stylesheet" />
        <link href="../assets/css/responsive.dataTables.min.css" rel="stylesheet" />
        <link href="../assets/css/rowReorder.dataTables.min.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="../assets/img/logo.png" />
    </head>
    <body>
        <div class="wrapper">
            <?php sidebar(9);?>
           
            <div id="content">
                <?php head();?><br>
                <section class="content" style="margin-top: 50px;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-body " >
                                    <h1>Account Logs</h1><br>
                                    <table id="table" class="table table-bordered nowrap" style="width: 100%;">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Account ID</th>
                                                <th>Complete Name</th>
                                                <th>Date</th>
                                                <th>Log in</th>
                                                <th>Log out</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $name = []; $logs[] = getLogs($_SESSION['role']); $count = 1;?>
                                            <?php foreach($logs as $key) :?>
                                                <?php foreach($key as $log) :?>
                                                    <?php 
                                                        $db = database();
                                                        $id = $log["account_id"]; 
                                                        $sql = $db->query("SELECT first_name, middle_name, last_name FROM personnels WHERE account_id = '$id'");
                                                        $name[] = $sql->fetch_array(MYSQLI_ASSOC);
                                                        foreach($name as $name1){
                                                            $fname = $name1["first_name"];
                                                            $lname = $name1["last_name"];
                                                            if($name1["middle_name"] == "N/A"){
                                                                $mname = "";
                                                            }
                                                            else{
                                                                $mname = $name1["middle_name"];
                                                            }
                                                        }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $count;?></td>
                                                        <td><?php echo $log["account_id"];?></td>
                                                        <td><?php echo $fname." ".$mname." ".$lname;?></td>
                                                        <td><?php echo $log["date"];?></td>
                                                        <td><?php echo $log["login"];?></td>
                                                        <td><?php echo $log["logout"];?></td>
                                                    </tr>
                                                    <?php $count++;?>	
                                                <?php endforeach;?>
                                            <?php endforeach;?>
                                        </tbody>
                                    </table>
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
        <script src="../assets/js/jquery-3.3.1.js"></script>
        <script src="../assets/js/jquery-scripts.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/jquery.dataTables.js"></script>
        <script src="../assets/js/dataTables.responsive.min.js"></script>
        <script src="../assets/js/dataTables.rowReorder.min.js"></script>
        <script>
            $(document).ready(function () {
                var table= $("#table").DataTable({
                    columnDefs:[
                        {orderable: false, targets: "_all"}
                    ],
                    responsive: true,
                    order:[[0, 'asc']]
                });
            });
        </script>
    </body>
</html>
