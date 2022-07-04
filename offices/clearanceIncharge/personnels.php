<?php
    require_once('../database.php');
    require_once('../validations.php');
    require_once('../functions.php');
    require_once('nav.php');
    session_start();
    $myInfo = [];
    $error = [];
    $gender = "";
    
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

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])){
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
        <title>Credential Request System</title>
        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/admin.css" rel="stylesheet" />
        <link href="../assets/css/bootstrap.css" rel="stylesheet" />
        <link href="../assets/css/jquery.dataTables.css" rel="stylesheet" />
        <link href="../assets/css/responsive.dataTables.min.css" rel="stylesheet" />
        <link href="../assets/css/rowReorder.dataTables.min.css" rel="stylesheet" />
         <link rel="icon" type="image/x-icon" href="https://oscrs-bulsusc.com/assets/img/logo.png" />
    </head>
    <body>
        <div class="wrapper">
            <?php sidebar(6);?>
           
            <div id="content">
                <?php head();?><br>
                <section class="content" style="margin-top: 50px;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-body ">
                                    <div class="col-lg-12 justify-content-between" style="display:flex;">
                                        <h1>Personnels</h1>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="modal-footer justify-content-between" style="background-color:white; border:none; float: right;">
                                            <div class="search">
                                                <input class="input-search" id="search" type="search" name="search" placeholder="Search">
                                            </div>
                                        </div>
                                    </div>
                                    <table id="table" class="table table-bordered nowrap" style="width: 100%;">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Action</th>
                                                <th>Employee No.</th>
                                                <th>Complete Name</th>
                                                <th>Gender</th>
                                                <th>Email</th>
                                                <th>Birthday</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $personnels[] = getEmployees($_SESSION['role'], 'Confirmed', ""); $count = 1;?>
                                            <?php foreach($personnels as $key) :?>
                                                <?php foreach($key as $personnel) :?>
                                                    <?php if($personnel['middle_name']=="N/A") $personnel['middle_name']="";?>
                                                    <tr>
                                                        <td width="18"><?php echo $count;?></td>
                                                        <td width="18">
                                                            <form method="POST">
                                                                <input type="hidden" name="id" value="<?php echo $personnel["employee_no"];?>">
                                                                <button type = 'submit' class='btn-edit btn-xs' name = 'delete' id = 'delete'>
                                                                    <i class='fas fa-trash-alt'></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                        <td><?php echo $personnel["employee_no"];?></td>
                                                        <td><?php echo $personnel["first_name"]." ".$personnel["middle_name"]." ".$personnel["last_name"];?></td>
                                                        <td><?php echo $personnel["gender"];?></td>
                                                        <td><?php echo $personnel["email"];?></td>
                                                        <td><?php echo $personnel["birthday"];?></td>
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
        <script type='text/javascript' src='../assets/sweetalert/dist/sweetalert2.all.min.js'></script>
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

        <?php if(isset($_SESSION['deleted'])) :?>
            <?php if($_SESSION['deleted'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Information and Account is Successfully Deleted', 
                        icon:'success', 
                        confirmButtonColor: 'maroon',
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('personnels?id=<?php echo $_SESSION['user_id']?>');
                        }
                    });
                </script>
                <?php unset($_SESSION['deleted']);?>
            <?php endif;?>
        <?php endif; ?>

    </body>
</html>
