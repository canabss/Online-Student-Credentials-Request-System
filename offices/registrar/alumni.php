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
        <title>Alumni - Credential Request System</title>
        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/admin.css" rel="stylesheet" />
        <link href="../assets/css/bootstrap.css" rel="stylesheet" />
        <link href="../assets/css/jquery.dataTables.css" rel="stylesheet" />
        <link href="../assets/css/responsive.dataTables.min.css" rel="stylesheet" />
        <link href="../assets/css/rowReorder.dataTables.min.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="../assets/img/bsu.png" />
    </head>
    <body>
        <div class="wrapper">
            <?php sidebar(3);?>
            
            <div id="content">
                <?php head();?><br>
                <section class="content" style="margin-top: 50px;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-body ">
                                    <h1>Alumni List</h1><br>
                                    <table id="table" class="table table-bordered nowrap" style="width: 100%;">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Student No.</th>
                                                <th>Complete Name</th>
                                                <th>Course</th>
                                                <th>Birthday</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $alumni[] = getAlumni(); $count = 1;?>
                                            <?php foreach($alumni as $key) :?>
                                                <?php foreach($key as $alumnus) :?>
                                                    <?php if($alumnus['middle_name']=="N/A") $alumnus['middle_name']="";?>
                                                    <tr>
                                                        <td><?php echo $count;?></td>
                                                        <td><?php echo $alumnus["student_no"];?></td>
                                                        <td><?php echo $alumnus["first_name"]." ".$alumnus["middle_name"]." ".$alumnus["last_name"];?></td>
                                                        <td><?php echo $alumnus["course"];?></td>
                                                        <td><?php echo $alumnus["birthday"];?></td>
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
                $("#search").on("keyup", function(){
                    table.search(this.value).draw();
                });
            });
        </script>
    </body>
</html>
