<?php
    require_once('../database.php');
    require_once('nav.php');
    require_once('../functions.php');
    require_once('../sendEmails.php');
    session_start();
    $myInfo = [];
    auth();

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])){
        logout();
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])){
        $db = database();
        $studentNo = $db->real_escape_string($_POST['studentNo']);
        $senderName = $db->real_escape_string($_POST['sender_name']);
        $comment = $db->real_escape_string($_POST['comment']);
        $name = $db->real_escape_string($_POST['name']);
        $email = $db->real_escape_string($_POST['email']);
        if(reply($studentNo, $name, $email, $senderName, $comment)){
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
        <title>Messages</title>
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
            <?php sidebar(2);?>
           
            <div id="content">
                <?php head();?><br>
                <section class="content" style="margin-top: 50px;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-body " >
                                    <h1>Messages</h1><br>
                                    <table id="table" class="table table-bordered" style="width: 100%;">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Action</th>
                                                <th>Student No.</th>
                                                <th>Complete Name</th>
                                                <th>Email</th>
                                                <th>Message</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $messages = []; $messages = getMessages(); $count = 1;?>
                                            <?php foreach($messages as $message) :?>
                                                <tr>
                                                    <td><?php echo $count;?></td>
                                                    <td>
                                                        <button type = 'button' class='btn btn-info btn-xs comment' name = 'reply' style="width: 130px;" >
                                                            <i class='fas fa-reply'></i> Reply
                                                        </button>
                                                        <div class="modal fade" id="send-comment">
                                                            <div class="container">
                                                                <div class="modal-dialog modal-md">
                                                                    <div class="modal-content " style="margin-top: 130px;">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">Send Comments To <br><?php echo $fname." ".$mname." ".$lname;?></h4>
                                                                                <button class="close-upload-panel" name="closs-upload-panel" id="close-upload-panel">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                        </div>
                                                                        <form method="POST" >
                                                                            <div class="row" style="padding: 50px">
                                                                                <input type="hidden" name="studentNo" value="<?php echo $message["sender_id"];?>">
                                                                                <input type="hidden" name="name" value="<?php echo $message["sender_name"];?>">
                                                                                <input type="hidden" name="email" value="<?php echo $message["sender_email"];?>">
                                                                                <input type="hidden" name="sender_name" value="<?php echo $_SESSION['firstname']." ".$_SESSION['lastname'];?>">
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
                                                    </td>
                                                    <td><?php echo $message["sender_id"];?></td>
                                                    <td><?php echo $message["sender_name"];?></td>
                                                    <td><?php echo $message["sender_email"];?></td>
                                                    <td><?php echo $message["sender_message"];?></td>
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
            $('.comment').click(function () {
                $('#send-comment').modal('show');
            });
            $('.close-upload-panel').click(function () {
                $('#send-comment').modal('hide');
            });
        </script>
        <?php if(isset($_SESSION['success'])) :?>
            <?php if($_SESSION['success'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Reply Sent.',
                        icon:'success', 
                        confirmButtonColor: 'maroon',
                        allowOutsideClick: false, 
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('messages?id=<?php echo $_SESSION['user_id']?>');
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
                        title: 'Failed to Send Reply',
                        icon:'error', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            $(document).ready(function () {
                                $('#send-comment').modal('show');
                            });
                        }
                    });
                </script>
                <?php unset($_SESSION['success']);?>
            <?php endif;?>
        <?php endif;?>
    </body>
</html>
