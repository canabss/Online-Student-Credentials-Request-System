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
    else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])){
        $db = database();
        $requestNo = $db->real_escape_string($_POST['request-id']);
        $sql = $db->query("UPDATE requests SET request_status = 'Deleted' WHERE request_id = '$requestNo'");
        if($sql){
            $_SESSION['delete'];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Requests Archieve</title>
        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/admin.css" rel="stylesheet" />
        <link href="../assets/css/bootstrap.css" rel="stylesheet" />
        <link href="../assets/css/jquery.dataTables.css" rel="stylesheet" />
        <link href="../assets/css/responsive.dataTables.min.css" rel="stylesheet"/>
        <link href="../assets/css/rowReorder.dataTables.min.css" rel="stylesheet"/>
        <link rel="icon" type="image/x-icon" href="../assets/img/logo.png" />
        
    </head>
    <body>
        <div class="wrapper">
            <?php sidebar(6);?>
           
            <div id="content">
                <?php head();?><br><br>
               
                <section class="content" style="margin-top: 15px;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-body ">
                                    <div class="col-lg-12 justify-content-between" style="display:flex;">
                                        <h1>Requests Archive</h1>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="modal-footer justify-content-between" style="background-color:white; border:none;">
                                            <div class="filter" style="color:black;">
                                                <select class="filter-course" id="filters1" name="filters">
                                                    <option value="">Sort By.</option>
                                                    <option value="Certificate of Grades">Certificate of Grades</option>
                                                    <option value="Certificate of Registration">Certificate of Registration</option>
                                                    <option value="Certificate of Good moral">Certificate of Good moral</option>
                                                    <option value="Honourable Dismissal">Honourable Dismissal</option>
                                                    <option value="Certificate of Non-issuance of ID">Certificate of Non-issuance of ID</option>
                                                </select>
                                            </div>
                                            <div class="search">
                                                <input class="input-search" id="search1" type="search" name="search" placeholder="Search">
                                            </div>
                                        </div>
                                    </div>
                                    <table id="table1" class="table table-bordered table-striped" style="width: 100%;">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Action</th>
                                                <th>Request No.</th>
                                                <th>Student No.</th>
                                                <th>Complete Name</th>
                                                <th>Requested Document</th>
                                                <th>Academic Year</th>
                                                <th>Semester</th>
                                                <th>Date</th>
                                                <th>Purpose</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $requests1[] = getRequestArchieve(); $count = 1;?>
                                            <?php foreach($requests1 as $key) :?>
                                                <?php foreach($key as $request) :?>
                                                    <?php 
                                                        $db = database();
                                                        $id = $request["student_id"]; 
                                                        $sql = $db->query("SELECT first_name, middle_name, last_name FROM students WHERE student_no = '$id'");
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
                                                        <td>
                                                            <form method="POST">
                                                                <input type="hidden" name="request-id" value="<?php echo $request["request_id"];?>">
                                                                <button type = 'submit' class='btn btn-info btn-xs' name = 'delete' style="width: 130px;" >
                                                                <i class='fas fa-trash-alt'></i> Delete
                                                            </button>
                                                            </form>
                                                        </td>
                                                        <td><?php echo $request["request_id"];?></td>
                                                        <td><?php echo $request["student_id"];?></td>
                                                        <td><?php echo $fname." ".$mname." ".$lname;?></td>
                                                        <td><?php echo $request["requested_documents"];?></td>
                                                        <td><?php echo $request["school_year"];?></td>
                                                        <td><?php echo $request["semester"];?></td>
                                                        <td><?php echo $request["request_date"];?></td>
                                                        <td><?php echo $request["purpose"];?></td>
                                                        <td><?php echo $request["request_status"];?></td>
                                                        
                                                    </tr>
                                                    <?php $count++;?>	
                                                <?php endforeach;?>
                                            <?php endforeach;?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <br/><br/>
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
        
        <script>
            
            $(document).ready(function () {
                var table= $("#table1").DataTable({
                    columnDefs:[
                        {orderable: false, targets: "_all"}
                    ],
                    responsive: true,
                    order:[[0, 'asc']]
                });
                $("#search2").on("keyup", function(){
                    table.search(this.value).draw();
                });
                $("#filters2").on("change", function(){
                    table.search(this.value).draw();
                });
            });
        </script>
        <?php if(isset($_SESSION['delete'])) :?>
            <?php if($_SESSION['delete'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Request Deleted Successfully', 
                        icon:'success', 
                        confirmButtonColor: 'maroon',
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('requestArchieve?id=<?php echo $_SESSION['user_id']?>');
                        }
                    });
                </script>
                <?php unset($_SESSION['delete']);?>
            <?php endif;?>
        <?php endif; ?>

        <?php if(isset($_SESSION['delete'])) :?>
            <?php if($_SESSION['delete'] == false) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Failed to delete request',
                        html: 'SQL Error', 
                        icon:'error', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('requestArchieve?id=<?php echo $_SESSION['user_id']?>');
                        }
                    });
                </script>
                <?php unset($_SESSION['delete']);?>
            <?php endif;?>
        <?php endif;?>
    </body>
</html>
