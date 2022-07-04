<?php
    require_once('../database.php');
    require_once('nav.php');
    require_once('../sendEmails.php');
    session_start();
    $db = database();
    $error = "";
    $offices = ["none", "Campus Registrar", "Dean's Office", "Campus Secretary", "Campus Cashier", "Department Head", "Professor", "Guidance Office", "Student Affairs", "Campus Clinic", "Campus Library"];
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
    
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){
        $id = $_POST['clearanceId'];
        if($_SESSION['role'] == '2'){
            $sql = $db->query("UPDATE clearance SET dean = 'true', clearance_status = 'Complete' WHERE clearance_id = '$id'");
            $sql1 = $db->query("UPDATE requests SET request_status = 'Pending' WHERE clearance_id = '$id' AND request_status = 'For Clearance'");
            if($sql && $sql1){
                $_SESSION['success'] = true;
            }
            else{
                $error = "SQL Error";
                $_SESSION['success'] = false;
            }
        }
        else if($_SESSION['role'] == '3'){
            $sql = $db->query("UPDATE clearance SET secretary = 'true' WHERE clearance_id = '$id'");
            if($sql){
                $_SESSION['success'] = true;
            }
            else{
                $error = "SQL Error";
                $_SESSION['success'] = false;
            }
        }
        else if($_SESSION['role'] == '4'){
            $sql = $db->query("UPDATE clearance SET cashier = 'true' WHERE clearance_id = '$id'");
            if($sql){
                $_SESSION['success'] = true;
            }
            else{
                $error = "SQL Error";
                $_SESSION['success'] = false;
            }
        }
        else if($_SESSION['role'] == '5'){
            $sql = $db->query("UPDATE clearance SET department_head = 'true' WHERE clearance_id = '$id'");
            if($sql){
                $_SESSION['success'] = true;
            }
            else{
                $error = "SQL Error";
                $_SESSION['success'] = false;
            }
        }
        else if($_SESSION['role'] == '6'){
            $sql = $db->query("UPDATE clearance SET adviser = 'true' WHERE clearance_id = '$id'");
            if($sql){
                $_SESSION['success'] = true;
            }
            else{
                $error = "SQL Error";
                $_SESSION['success'] = false;
            }
            
        }
        else if($_SESSION['role'] == '7'){
            $sql = $db->query("UPDATE clearance SET guidance = 'true' WHERE clearance_id = '$id'");
            if($sql){
                $_SESSION['success'] = true;
            }
            else{
                $error = "SQL Error";
                $_SESSION['success'] = false;
            }
        }
        else if($_SESSION['role'] == '8'){
            $sql = $db->query("UPDATE clearance SET student_affairs = 'true' WHERE clearance_id = '$id'");
            if($sql){
                $_SESSION['success'] = true;
            }
            else{
                $error = "SQL Error";
                $_SESSION['success'] = false;
            }
        }
        else if($_SESSION['role'] == '9'){
            $sql = $db->query("UPDATE clearance SET clinic = 'true' WHERE clearance_id = '$id'");
            if($sql){
                $_SESSION['success'] = true;
            }
            else{
                $error = "SQL Error";
                $_SESSION['success'] = false;
            }
        }
        else if($_SESSION['role'] == '10'){
            $sql = $db->query("UPDATE clearance SET library = 'true' WHERE clearance_id = '$id'");
            if($sql){
                $_SESSION['success'] = true;
            }
            else{
                $error = "SQL Error";
                $_SESSION['success'] = false;
            }
        }
    }
    else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])){
        logout();
    }
    else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])){
        $clearanceId = $db->real_escape_string($_POST['clearanceId']);
        $requestNo = $db->real_escape_string($_POST['requestNo']);
        $lastname = $db->real_escape_string($_POST['lastname']);
        $firstname = $db->real_escape_string($_POST['firstname']);
        $middlename = $db->real_escape_string($_POST['middlename']);
        $email = $db->real_escape_string($_POST['email']);
        $senderName = $db->real_escape_string($_POST['senderName']);
        $comment = $db->real_escape_string($_POST['comment']);
        $num = $_SESSION['role'];
        $office = $offices[$num];
        if(commentClearance($clearanceId, $lastname, $firstname, $middlename, $email, $senderName, $comment, $office)){
            $_SESSION['success'] = true;
        }
        else{
            $_SESSION['success'] = false;
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
        <link href="https://oscrs-bulsusc.com/assets/css/admin.css" rel="stylesheet" />
        <link href="https://oscrs-bulsusc.com/assets/css/bootstrap.css" rel="stylesheet"/>
        <link href="https://oscrs-bulsusc.com/assets/css/jquery.dataTables.css" rel="stylesheet"/>
        <link href="https://oscrs-bulsusc.com/assets/css/responsive.dataTables.min.css" rel="stylesheet" />
        <link href="https://oscrs-bulsusc.com/assets/css/rowReorder.dataTables.min.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="https://oscrs-bulsusc.com/assets/img/logo.png" />
    </head>
    <body>
        <div class="wrapper">
        <?php sidebar(2);?>
            <div id="content">
                <?php head();?>
                <div class="request-tab">
                    <a href="https://offices.oscrs-bulsusc.com/clearanceIncharge/clearances?id=<?php echo $_SESSION['user_id']?>" style="color: orange;">Clearances</a>
                    <a href="https://offices.oscrs-bulsusc.com/clearanceIncharge/declinedClearances?id=<?php echo $_SESSION['user_id']?>">Declined Clearances</a>
                </div><br><br>
                <section class="content" style="margin-top: 50px;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-body ">
                                    <h1>Clearances</h1>
                                    <div class="col-lg-12">
                                        <div class="modal-footer justify-content-between" style="background-color:white; border:none;">
                                            
                                            <div class="search">
                                                <input class="input-search" id="search" type="search" name="search" placeholder="Search">
                                            </div>
                                        </div>
                                    </div>
                                    <table id="table" class="table table-bordered table-striped nowrap" style="width: 100%;">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Action</th>
                                                <th>Clearance No.</th>
                                                <th>Student No.</th>
                                                <th>Complete Name</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                            
                                            $clearances = []; 
                                            if(!($db -> connect_error)){
                                                if($_SESSION['role'] == '2'){
                                                    $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND secretary = 'true' AND dean = 'false' ORDER BY clearance_id");
                                                    while($data = mysqli_fetch_assoc($sql)){
                                                        $clearances[] = $data;
                                                    }
                                                }
                                                else if($_SESSION['role'] == '3'){
                                                    $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND cashier = 'true' AND secretary = 'false' ORDER BY clearance_id");
                                                    while($data = mysqli_fetch_assoc($sql)){
                                                        $clearances[] = $data;
                                                    }
                                                }
                                                else if($_SESSION['role'] == '4'){
                                                    $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND guidance = 'true' AND cashier = 'false' ORDER BY clearance_id");
                                                    while($data = mysqli_fetch_assoc($sql)){
                                                        $clearances[] = $data;
                                                    }
                                                }
                                                else if($_SESSION['role'] == '5'){
                                                    $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND adviser = 'true' AND department_head = 'false' ORDER BY clearance_id");
                                                    while($data = mysqli_fetch_assoc($sql)){
                                                        $clearances[] = $data;
                                                    }
                                                }
                                                else if($_SESSION['role'] == '6'){
                                                    $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND adviser = 'false' ORDER BY clearance_id");
                                                    if($sql){
                                                        while($data = mysqli_fetch_assoc($sql)){
                                                            $clearances[] = $data;
                                                        }
                                                    }
                                                    
                                                }
                                                else if($_SESSION['role'] == '7'){
                                                    $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND student_affairs = 'true' AND guidance = 'false' ORDER BY clearance_id");
                                                    while($data = mysqli_fetch_assoc($sql)){
                                                        $clearances[] = $data;
                                                    }
                                                }
                                                else if($_SESSION['role'] == '8'){
                                                    $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND library = 'true' AND student_affairs = 'false' ORDER BY clearance_id");
                                                    if($sql){
                                                        while($data = mysqli_fetch_assoc($sql)){
                                                            $clearances[] = $data;
                                                        }
                                                    }
                                                }
                                                else if($_SESSION['role'] == '9'){
                                                    $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND department_head = 'true' AND clinic = 'false' ORDER BY clearance_id");
                                                    if($sql){
                                                        while($data = mysqli_fetch_assoc($sql)){
                                                            $clearances[] = $data;
                                                        }
                                                    }
                                                }
                                                else if($_SESSION['role'] == '10'){
                                                    $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND clinic = 'true' AND library = 'false' ORDER BY clearance_id");
                                                    while($data = mysqli_fetch_assoc($sql)){
                                                        $clearances[] = $data;
                                                    }
                                                }
                                            }
                                            $count = 1;
                                        ?>
                                            <?php foreach($clearances as $clearance) :?>
                                                    <?php 
                                                        $db = database();
                                                        $id = $clearance["student_no"]; 
                                                        $sql = $db->query("SELECT first_name, middle_name, last_name FROM students WHERE student_no = '$id'");
                                                        $name[] = $sql->fetch_array(MYSQLI_ASSOC);
                                                        foreach($name as $name1){
                                                            $fname = $name1["first_name"];
                                                            $lname = $name1["last_name"];
                                                            $mname = $name1["middle_name"];
                                                        }
                                                    ?>
                                                        <td><?php echo $count;?></td>
                                                        <td>
                                                            <form method="POST">
                                                                <input type="hidden" name="clearanceId" value="<?php echo $clearance["clearance_id"];?>">
                                                                <button type = 'submit' class='btn btn-info btn-xs' style="margin-bottom: 10px; width: 130px;" name="submit">
                                                                    <i class='fas fa-check'></i> Approve
                                                                </button>
                                                            </form>
                                                            <button type = 'button' class='btn btn-info btn-xs comment' style="width: 130px;" name = 'btn-edit' value="">
                                                                <i class='fas fa-comment-alt'></i> Comment
                                                            </button>
                                                            <div class="modal fade" id="send-comment">
                                                                <div class="container">
                                                                    <div class="modal-dialog modal-md">
                                                                            <div class="modal-content " style="margin-top: 130px;">
                                                                                <div class="modal-header">
                                                                                    <h4 class="modal-title">Send Comments To <br><?php echo $fname." ".$mname." ".$lname;?></h4>
                                                                                    <form method="POST" action="">
                                                                                        <button type="submit" class="close-upload-panel" name="closs-upload-panel" id="close-upload-panel">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </form>
                                                                                </div>
                                                                                <form method="POST" action="" enctype="multipart/form-data">
                                                                                    <div class="row" style="padding: 50px">
                                                                                        <input type="hidden" name="clearanceId" value="<?php echo $clearance["clearance_id"];?>">
                                                                                        <input type="hidden" name="lastname" value="<?php echo $lname;?>">
                                                                                        <input type="hidden" name="firstname" value="<?php echo $fname;?>">
                                                                                        <input type="hidden" name="middlename" value="<?php echo $mname;?>">
                                                                                        <input type="hidden" name="email" value="<?php echo clearance["email"];?>">
                                                                                        <input type="hidden" name="studentNo" value="<?php echo $clearance["student_no"];?>">
                                                                                        <input type="hidden" name="sender_name" value="<?php echo $_SESSION['firstname']." ".$_SESSION['middlename']." ".$_SESSION['lastname'];?>">
                                                                                        <div class="col-12">
                                                                                            <textarea class="form-control" name="comment" id="comment" placeholder="Write Comments Here.." style="height: 200px;" required></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer justify-content-between">
                                                                                        <button type="submit" class="btn btn-info mx-auto" name="send" ><i class='fa fa-check'></i> SEND</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><?php echo $clearance["clearance_id"];?></td>
                                                        <td><?php echo $clearance["student_no"];?></td>
                                                        <td><?php echo $fname." ".$mname." ".$lname;?></td>
                                                        <td><?php echo $clearance["email"];?></td>
                                                        <td><?php echo $clearance["clearance_status"];?></td>
                                                        
                                                    </tr>
                                                    <?php $count++;?>	
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
        
        <script src="https://oscrs-bulsusc.com/assets/js/jquery-3.3.1.js"></script>
        <script src="https://oscrs-bulsusc.com/assets/js/jquery-scripts.js"></script>
        <script src="https://oscrs-bulsusc.com/assets/js/jquery.dataTables.js"></script>
        <script src="https://oscrs-bulsusc.com/assets/js/bootstrap.bundle.min.js"></script>
        <script src="https://oscrs-bulsusc.com/assets/js/dataTables.responsive.min.js"></script>
        <script src="https://oscrs-bulsusc.com/assets/js/dataTables.rowReorder.min.js"></script>
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
                $("#search").on("keyup", function(){
                    table.search(this.value).draw();
                });
            });
            $('.comment').click(function () {
                $('#send-comment').modal('show');
            });
       </script>
       <?php if(isset($_SESSION['success'])) :?>
            <?php if($_SESSION['success'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Successfully Clearance Approved', 
                        icon:'success', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('clearances.php?id=<?php echo $_SESSION['user_id']?>');
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
                        title: 'Failed to Approve Clearance',
                        html: '<?php  echo $error;?>', 
                        icon:'error', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    });
                </script>
                <?php unset($_SESSION['success']);?>
            <?php endif;?>
        <?php endif;?>
    </body>
</html>
