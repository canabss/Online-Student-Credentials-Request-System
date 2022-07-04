<?php
    require_once('../database.php');
    require_once('nav.php');
    require_once('../functions.php');
    require_once('../sendEmails.php');
    
    session_start();
    $myInfo = [];
    auth();
    $db = database();

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])){
        logout();
    }
    else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])){
        $requestNo = $db->real_escape_string($_POST['requestNo']);
        $lastname = $db->real_escape_string($_POST['lastname']);
        $firstname = $db->real_escape_string($_POST['firstname']);
        $middlename = $db->real_escape_string($_POST['middlename']);
        $email = $db->real_escape_string($_POST['email']);
        $senderName = $db->real_escape_string($_POST['senderName']);
        $comment = $db->real_escape_string($_POST['comment']);
        
        if(comment($requestNo, $lastname, $firstname, $middlename, $email, $senderName, $comment)){
            $_SESSION['success'] = true;
        }
        else{
            $_SESSION['success'] = false;
        }
    }
    else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve'])){
        $requestNo = $db->real_escape_string($_POST['requestNo']);
        $status = $db->real_escape_string($_POST['requestStatus']);
        
        if(updateStatus($requestNo, $status)){
            $_SESSION['success1'] = true;
        }
        else{
            $_SESSION['success1'] = false;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Pending Requests</title>
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
            <?php sidebar(4);?>
           
            <div id="content">
                <?php head();?><br><br>
                <section class="content" style="margin-top: 15px;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-body ">
                                    <div class="col-lg-12 justify-content-between" style="display:flex;">
                                        <h1>Pending Requests</h1>
                                        <button class="btn btn-info btn-xl text-uppercase open-to-print" style="margin-top: 5px; float: right; margin-right: 20px;"><i class="fas fa-print"></i> Download / Print<br>Request Logs</button>
                                    </div><br>
                                    <div class="tab">
                                        <button class="tablinks" onclick="openTable(event, 'student-request')" id="defaultOpen">Student Request</button>
                                        <button class="tablinks" onclick="openTable(event, 'alumni-request')">Alumni Request</button>
                                    </div>
                                    <div id="student-request" class="tabcontent">
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
                                            <?php $requests1[] = getAllRequest("Student", "Pending"); $count = 1;?>
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
                                                                <?php if($request["requestor"] == "Student"):?>
                                                                    <form method="POST">
                                                                        <input type="hidden" name="requestNo" value="<?php echo $request["request_id"];?>">
                                                                        <input type="hidden" name="requestStatus" value="<?php echo $request["request_status"];?>">
                                                                        <button type = 'submit' class='btn btn-info btn-xs' style="margin-bottom: 10px; width: 130px;" name="approve">
                                                                            <i class='fas fa-check'></i> &nbsp;&nbsp;&nbsp;&nbsp;Approve
                                                                        </button><br>
                                                                    </form>
                                                                <?php endif;?>
                                                                <button type = 'button' class='btn btn-info btn-xs comment1' name = 'btn-edit' style="width: 130px;" >
                                                                    <i class='fas fa-comment-alt'></i> Comment
                                                                </button>
                                                                <div class="modal fade" id="send-comment1">
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
                                                                                            <input type="hidden" name="requestNo" value="<?php echo $request["request_id"];?>">
                                                                                            <input type="hidden" name="lastname" value="<?php echo $lname;?>">
                                                                                            <input type="hidden" name="firstname" value="<?php echo $fname;?>">
                                                                                            <input type="hidden" name="middlename" value="<?php echo $mname;?>">
                                                                                            <input type="hidden" name="email" value="<?php echo $request["email"];?>">
                                                                                            <input type="hidden" name="senderName" value="<?php echo $_SESSION['firstname']." ".$_SESSION['lastname']?>">
                                                                                            <div class="col-12">
                                                                                                <textarea class="form-control" name="comment" id="comment" placeholder="Write Comments Here.." style="height: 200px;" required><?php echo !isset($_SESSION['message']) ? htmlspecialchars('') : htmlspecialchars($_SESSION['message']) ?></textarea>
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
                                                            <td><?php echo $request["request_id"];?></td>
                                                            <td><?php echo $request["student_id"];?></td>
                                                            <td><?php echo $fname." ".$mname." ".$lname;?></td>
                                                            <td><?php echo $request["requested_documents"];?></td>
                                                            <td><?php echo $request["school_year"];?></td>
                                                            <td><?php echo $request["semester"];?></td>
                                                            <td><?php echo $request["request_date"];?></td>
                                                            <td><?php echo $request["purpose"];?></td>
                                                            <td><?php echo $request["request_status"];?></td>
                                                            <?php
                                                                $db = database();
                                                                $idd = $request["clearance_id"]; 
                                                                $sql = $db->query("SELECT clearance_file FROM clearance WHERE clearance_id = '$idd'");
                                                                $file = $sql->fetch_array(MYSQLI_ASSOC);
                                                            ?>
                                                        </tr>
                                                        <?php $count++;?>	
                                                    <?php endforeach;?>
                                                <?php endforeach;?>
                                                
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="alumni-request" class="tabcontent">
                                        <div class="col-lg-12">
                                            <div class="modal-footer justify-content-between" style="background-color:white; border:none;">
                                                <div class="filter" style="color:black;">
                                                    <select class="filter-course" id="filters2" name="filters">
                                                        <option value="">Sort By.</option>
                                                        <option value="Transcript of Records">Transcript of Records</option>
                                                        <option value="Certificate of Graduation">Certificate of Graduation</option>
                                                        <option value="Diploma">Diploma</option>
                                                    </select>
                                                </div>
                                                <div class="search">
                                                    <input class="input-search" id="search2" type="search" name="search" placeholder="Search">
                                                </div>
                                            </div>
                                        </div>
                                        <table id="table2" class="table table-bordered table-striped" style="width: 100%;">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Action</th>
                                                    <th>Request No.</th>
                                                    <th>Student No.</th>
                                                    <th>Complete Name</th>
                                                    <th>Requested Document</th>
                                                    <th>Date</th>
                                                    <th>Purpose</th>
                                                    <th>Status</th>
                                                    <th>Clearance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php $requests2[] = getAllRequest("Alumni", "Pending"); $count = 1;?>
                                                <?php foreach($requests2 as $key) :?>
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
                                                                
                                                                <?php if($request["requestor"] == "Alumni"):?>
                                                                    <form  method="POST">
                                                                        <input type="hidden" name="requestNo" value="<?php echo $request["request_id"];?>">
                                                                        <input type="hidden" name="requestStatus" value="<?php echo $request["request_status"];?>">
                                                                        <button type = 'submit' class='btn btn-info btn-xs' style="margin-bottom: 10px; width: 130px;" name="approve">
                                                                            <i class='fas fa-check'></i> &nbsp;&nbsp;&nbsp;&nbsp;Approve
                                                                        </button><br>
                                                                    </form>
                                                                <?php endif;?>
                                                                <button type = 'button' class='btn btn-info btn-xs comment2' value="" style="width: 130px;">
                                                                    <i class='fas fa-comment-alt'></i> Comment
                                                                </button>
                                                                <div class="modal fade" id="send-comment2">
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
                                                                                            <input type="hidden" name="requestNo" value="<?php echo $request["request_id"];?>">
                                                                                            <input type="hidden" name="lastname" value="<?php echo $lname;?>">
                                                                                            <input type="hidden" name="firstname" value="<?php echo $fname;?>">
                                                                                            <input type="hidden" name="middlename" value="<?php echo $mname;?>">
                                                                                            <input type="hidden" name="email" value="<?php echo $request["email"];?>">
                                                                                            <input type="hidden" name="senderName" value="<?php echo $_SESSION['firstname']." ".$_SESSION['lastname']?>">
                                                                                            <div class="col-12">
                                                                                                <textarea class="form-control" name="comment" id="comment" placeholder="Write Comments Here.." style="height: 200px;" required><?php echo !isset($_SESSION['message']) ? htmlspecialchars('') : htmlspecialchars($_SESSION['message']) ?></textarea>
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
                                                            <td><?php echo $request["request_id"];?></td>
                                                            <td><?php echo $request["student_id"];?></td>
                                                            <td><?php echo $fname." ".$mname." ".$lname;?></td>
                                                            <td><?php echo $request["requested_documents"];?></td>
                                                            <td><?php echo $request["request_date"];?></td>
                                                            <td><?php echo $request["purpose"];?></td>
                                                            <td><?php echo $request["request_status"];?></td>
                                                            <?php
                                                                $db = database();
                                                                $idd = $request["clearance_id"]; 
                                                                $sql = $db->query("SELECT clearance_file FROM clearance WHERE clearance_id = '$idd'");
                                                                $file = $sql->fetch_array(MYSQLI_ASSOC);
                                                                $tt = $file['clearance_file'];
                                                            ?>
                                                            <td>
                                                                <button class='btn btn-info btn-xs show' value="" style="width: 150px;">View Clearance</button>
                                                                <div class="modal fade" id="show-clearance">
                                                                    <div class="container" >
                                                                        <div class="modal-dialog modal-md">
                                                                                <div class="modal-content"  style="width: 600px;">
                                                                                    <div class="modal-header">
                                                                                        <h4 class="modal-title">Clearance of <br><?php echo $fname." ".$mname." ".$lname;?></h4>
                                                                                        <button type="submit" class="close-upload-panel" name="close-show-clearance" id="close-show-clearance">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <img src="data:image/image/jpg:charset=utf8;base64,<?php echo base64_encode($file['clearance_file']);?>"></img>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php $count++;?>	
                                                    <?php endforeach;?>
                                                <?php endforeach;?>
                                            </tbody>
                                        </table>
                                    </div>
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
            function openTable(evt, tableName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }

                tablinks = document.getElementsByClassName("tablinks");

                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(tableName).style.display = "block";
                evt.currentTarget.className += " active";
            }
            document.getElementById("defaultOpen").click();
            
            $(document).ready(function () {
                var table= $("#table1").DataTable({
                    columnDefs:[
                        {orderable: false, targets: "_all"}
                    ],
                    responsive: true,
                    order:[[0, 'asc']]
                });
                $("#search1").on("keyup", function(){
                    table.search(this.value).draw();
                });
                $("#filters1").on("change", function(){
                    table.search(this.value).draw();
                });
            });

            $(document).ready(function () {
                var table= $("#table2").DataTable({
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
            
            $('.uploadFile').click(function () {
                $('#upload-file').modal('show');
            });
            $('.comment1').click(function () {
                $('#send-comment1').modal('show');
            });
            $('.comment2').click(function () {
                $('#send-comment2').modal('show');
            });
            $(document).ready(function () {
                $('.open-to-print').click(function () {
                    $('#to-print').modal('show');
                });     
            });
            $(document).ready(function () {
                $('#to-print').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            });

            $(document).ready(function () {
                $('.close').click(function () {
                    $('#to-print').modal('hide');
                });     
            });  
            
            $(document).ready(function () {
                $('.show').click(function () {
                    $('#show-clearance').modal('show');
                });     
            });
            
            $(document).ready(function () {
                $('#show-clearance').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            });

            $(document).ready(function () {
                $('#close-show-clearance').click(function () {
                    $('#show-clearance').modal('hide');
                });     
            });  
        </script>
        <div class="modal fade" id="to-print">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Choose to Print: </h4>
                        <button type="submit" class="close" name="close" id="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <a href="printPending?requestor=student" target="_blank" class="request-link">
                                <div class="printing student-print">
                                    <div class="icon"></div>
                                    <h4>Student Requests Logs</h4>
                                </div>
                            </a>
                        </div><br>
                        <div class="col-lg-12">
                            <a href="printPending?requestor=alumni" target="_blank" class="request-link">
                                <div class="printing alumni-print">
                                    <div class="icon"></div>
                                    <h4>Alumni Requests Logs</h4>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if(isset($_SESSION['success'])) :?>
            <?php if($_SESSION['success'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Comment Sent.',
                        icon:'success', 
                        confirmButtonColor: 'maroon',
                        allowOutsideClick: false, 
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('pendingRequests?id=<?php echo $_SESSION['user_id']?>');
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
                        title: 'Failed to send comment',
                        icon:'error', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('pendingRequests?id=<?php echo $_SESSION['user_id']?>');
                        }
                    });
                </script>
                <?php unset($_SESSION['success']);?>
            <?php endif;?>
        <?php endif;?>
        
        <?php if(isset($_SESSION['success1'])) :?>
            <?php if($_SESSION['success1'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Request has been approved.',
                        icon:'success', 
                        confirmButtonColor: 'maroon',
                        allowOutsideClick: false, 
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('pendingRequests?id=<?php echo $_SESSION['user_id']?>');
                        }
                    });
                </script>
                <?php unset($_SESSION['success1']);?>
            <?php endif;?>
        <?php endif; ?>
        <?php if(isset($_SESSION['success1'])) :?>
            <?php if($_SESSION['success1'] == false) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Failed to approve request',
                        icon:'error', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('pendingRequests?id=<?php echo $_SESSION['user_id']?>');
                        }
                    });
                </script>
                <?php unset($_SESSION['success1']);?>
            <?php endif;?>
        <?php endif;?>
    </body>
</html>
