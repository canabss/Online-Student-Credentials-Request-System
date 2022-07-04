<?php
    require_once('../database.php');
    require_once('nav.php');
    require_once('../functions.php');
    session_start();
    $myInfo = [];
    $myAccount = [];
    
    auth();
    if(isset($_SESSION['user_id'])){
        $db = database();
        $accountId = $_SESSION['user_id'];
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
    
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve'])){
        $db = database();
        $id = $_POST['id'];
        $info =[];
        $sql = $db->query("UPDATE personnels SET status = 'Confirmed' WHERE employee_no = '$id'");
        if($sql){
            $sql1 = $db->query("SELECT * FROM personnels WHERE employee_no = '$id'");
            if($sql1){
                $info = $sql1->fetch_array(MYSQLI_ASSOC);
                $_SESSION['approved'] = true;
                accountApproveNotification($id, $info['email'], $info['last_name'], $info['first_name']);
            }
        }
    }

    else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])){
        $db = database();
        $id = $_POST['id'];
        $id1 = "";
        $accountId = [];

        $sql = $db->query("SELECT account_id FROM personnels WHERE employee_no ='$id'");
        $accountId[] = $sql->fetch_array(MYSQLI_ASSOC);
        foreach($accountId as $acc){
            $id1 = $acc['account_id'];
        }

        $sql = $db->query("DELETE FROM personnels WHERE employee_no ='$id'");
        $sql1 = $db->query("DELETE FROM accounts WHERE account_id ='$id1'");

        if($sql && $sql1){
            $_SESSION['deleted'] = true;
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
        <title>Dashboard</title>
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
            <?php sidebar(0);?>
            <div id="content">
                <?php head();?>
                <section class="content" style="margin-top: 50px; padding: 20px;">
                    <div class="container-fluid">
                        <div class="col-12">
                            <div class="card-body " >
                                <h1 >Dashboard</h1>
                            </div>
                        </div>
                        <div class="row mx-auto">
                            <div class="col-lg-3">
                                <a class="request-link" href="#">
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3>
                                            <?php
                                                $pending = [];
                                                $processing = [];
                                                $length = 0;
                                                $pending[] = getAllRequest1("Pending");
                                                $processing[] = getAllRequest1("To Process");
                                                foreach($pending as $key){
                                                    foreach($key as $tot){
                                                        $length++;
                                                    }
                                                }
                                                foreach($processing as $key){
                                                    foreach($key as $tot){
                                                        $length++;
                                                    }
                                                }
                                                echo $length; 
                                            ?>
                                        </h3>

                                        <p class="text-uppercase">Total Requests</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-list-alt"></i>
                                    </div>
                                </div></a>
                            </div>
                            <div class="col-lg-3">
                                <a class="request-link" href="https://offices.oscrs-bulsusc.com/registrar/pendingRequests?id='<?php echo $_SESSION["user_id"];?>'">
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>
                                        <?php
                                                $total = [];
                                                $length = 0;
                                                $total[] = getAllRequest1("Pending");
                                                foreach($total as $key){
                                                    foreach($key as $tot){
                                                        $length++;
                                                    }
                                                }
                                                echo $length; 
                                            ?>
                                        </h3>

                                        <p class="text-uppercase">Pending Requests</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-list-alt"></i>
                                    </div>
                                </div></a>
                            </div>
                            <div class="col-lg-3">
                                <a class="request-link" href="https://offices.oscrs-bulsusc.com/registrar/processingRequests?id='<?php echo $_SESSION["user_id"];?>'">
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h3>
                                            <?php
                                                $total = [];
                                                $length = 0;
                                                $total[] = getAllRequest1("To Process");
                                                foreach($total as $key){
                                                    foreach($key as $tot){
                                                        $length++;
                                                    }
                                                }
                                                echo $length; 
                                            ?>
                                        </h3>

                                        <p class="text-uppercase">Processing Requests</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-list-alt"></i>
                                    </div>
                                </div></a>
                            </div>
                            <div class="col-lg-3">
                                <a class="request-link" href="https://offices.oscrs-bulsusc.com/registrar/requestArchive?id='<?php echo $_SESSION["user_id"];?>'">
                                <div class="small-box bg-grey">
                                    <div class="inner">
                                        <h3>
                                            <?php
                                                $total = [];
                                                $length = 0;
                                                $total[] = getRequestArchieve();
                                                foreach($total as $key){
                                                    foreach($key as $tot){
                                                        $length++;
                                                    }
                                                }
                                                echo $length; 
                                            ?>
                                        </h3>

                                        <p class="text-uppercase">Requests Archieve</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-list-alt"></i>
                                    </div>
                                </div></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card-body ">
                                <div class="card-body " >
                                    <h2>Active Requests</h2>
                                </div>
                                <table id="table" class="table table-bordered table-striped" style="width: 100%;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th class="text-center">Student No.</th>
                                            <th class="text-center">Complete Name</th>
                                            <th class="text-center" style="width: 25%;">Requested Document</th>
                                            <th class="text-center">Year</th>
                                            <th class="text-center">Semester</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Purpose</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $requests[] = getAllRequests(); $count = 1;?>
                                        <?php foreach($requests as $key) :?>
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
                                                    <td><?php echo $request["student_id"];?></td>
                                                    <td><?php echo $fname." ".$mname." ".$lname;?></td>
                                                    <td><?php echo $request["requested_documents"];?></td>
                                                    <td><?php echo $request["school_year"];?></td>
                                                    <td><?php echo $request["semester"];?></td>
                                                    <td><?php echo $request["request_date"];?></td>
                                                    <td><?php echo $request["purpose"];?></td>
                                                    <td>
                                                        <?php if($request["request_status"] == "Pending"):?>
                                                            <mark style="background-color: red; color: white;">
                                                                <?php echo $request["request_status"];?>
                                                            </mark>
                                                        <?php endif;?>
                                                        <?php if($request["request_status"] == "To Process"):?>
                                                            <mark style="background-color: yellow;">
                                                                <?php echo $request["request_status"];?>
                                                            </mark>
                                                        <?php endif;?>
                                                    </td>
                                                    
                                                </tr>
                                                <?php $count++;?>	
                                            <?php endforeach;?>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card-body ">
                                <div class="card-body " >
                                    <h2>Accounts for approval</h2>
                                </div>
                                <table id="table1" class="table table-bordered nowrap" style="width: 100%;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Employee No.</th>
                                            <th>Complete Name</th>
                                            <th>Gender</th>
                                            <th>Email</th>
                                            <th>Birthday</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $personnels[] = getEmployees($_SESSION['role'],'Pending', ""); $count = 1;?>
                                        <?php foreach($personnels as $key) :?>
                                            <?php foreach($key as $personnel) :?>
                                                <?php if($personnel['middle_name']=="N/A") $personnel['middle_name']="";?>
                                                <tr>
                                                    <td><?php echo $count;?></td>
                                                    <td><?php echo $personnel["employee_no"];?></td>
                                                    <td><?php echo $personnel["first_name"]." ".$personnel["middle_name"]." ".$personnel["last_name"];?></td>
                                                    <td><?php echo $personnel["gender"];?></td>
                                                    <td><?php echo $personnel["email"];?></td>
                                                    <td><?php echo $personnel["birthday"];?></td>
                                                    <td><mark style="background-color: yellow;"><?php echo $personnel["status"];?></mark></td>
                                                    <td>
                                                        <form method="POST" style="margin-bottom: 10px;">
                                                            <input type="hidden" name="id" value="<?php echo $personnel["employee_no"];?>">
                                                            <button type = 'submit' class='btn btn-info btn-xs' name = 'approve' id = 'approve'>
                                                                <i class='fas fa-check'></i> Approve
                                                            </button>
                                                        </form>
                                                        <form method="POST">
                                                            <input type="hidden" name="id" value="<?php echo $personnel["employee_no"];?>">
                                                            <button type = 'submit' class='btn btn-info btn-xs' name = 'delete' id = 'delete'>
                                                                <i class='fas fa-times'></i>&nbsp;&nbsp;&nbsp; Decline
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php $count++;?>	
                                            <?php endforeach;?>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
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
                var table= $("#table").DataTable({
                    columnDefs:[
                        {orderable: false, targets: "_all"}
                    ],
                    responsive: true,
                    order:[[0, 'asc']]
                });
            });
            $(document).ready(function () {
                var table= $("#table1").DataTable({
                    columnDefs:[
                        {orderable: false, targets: "_all"}
                    ],
                    responsive: true,
                    order:[[0, 'asc']]
                });
            });
        </script>

        <?php if(isset($_SESSION['approved'])) :?>
            <?php if($_SESSION['approved'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Succesfully Approved',
                        icon:'success', 
                        confirmButtonColor: 'maroon',
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('dashboard?id=<?php echo $_SESSION['user_id']?>');
                        }
                    });
                </script>
                <?php unset($_SESSION['approved']);?>
            <?php endif;?>
        <?php endif; ?>

        <?php if(isset($_SESSION['deleted'])) :?>
            <?php if($_SESSION['deleted'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Completely Declined', 
                        icon:'success', 
                        confirmButtonColor: 'maroon',
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('dashboard?id=<?php echo $_SESSION['user_id']?>');
                        }
                    });
                </script>
                <?php unset($_SESSION['deleted']);?>
            <?php endif;?>
        <?php endif; ?>

    </body>
</html>
