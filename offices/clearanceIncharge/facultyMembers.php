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
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])){
        $db = database();
        $id = $_POST['instructors'];
        $section = $_POST['section'];
        
        $sql = $db->query("UPDATE personnels SET advisory = '$section' WHERE employee_no = '$id'");
        if($sql){
             $_SESSION['success'] = true;
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
        <title>Faculty Members</title>
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
            <?php sidebar(5);?>
           
            <div id="content">
                <?php head();?><br>
                <section class="content" style="margin-top: 50px;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-body ">
                                    <div class="col-lg-12 justify-content-between" style="display:flex;">
                                        <h1>Faculty Members</h1>
                                        <button class="btn btn-info btn-xl text-uppercase set-advisory" name="set-advisory"><i class="fa fa-plus"></i> Set Advisory Class</button>
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
                                                <th>Advisory</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $personnels = [];
                                                if($_SESSION['role'] == "5"){
                                                    $personnels[] = getFacultyMembers($_SESSION['role'], 'Confirmed', $_SESSION['department']); 
                                                }
                                                $count = 1;
                                            ?>
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
                                                        <td><?php echo $personnel["advisory"];?></td>
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
            $(document).ready(function () {
                $('.set-advisory').click(function () {
                    $('#set').modal('show');
                });     
            });
            $(document).ready(function () {
                $('#set').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            });
            
            $(document).ready(function () {
                $('.close-add').click(function () {
                    $('#set').modal('hide');
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
                            window.location.replace('facultyMembers?id=<?php echo $_SESSION['user_id']?>');
                        }
                    });
                </script>
                <?php unset($_SESSION['deleted']);?>
            <?php endif;?>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['success'])) :?>
            <?php if($_SESSION['success'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Successfully set advisory class', 
                        icon:'success', 
                        confirmButtonColor: 'maroon',
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('facultyMembers?id=<?php echo $_SESSION['user_id']?>');
                        }
                    });
                </script>
                <?php unset($_SESSION['success']);?>
            <?php endif;?>
        <?php endif; ?>
        <div class="modal fade" id="set">
            <div class="container">
                <div class="modal-dialog modal-md">
                    <div class="modal-content"  style="margin-top: 200px;">
                        <div class="modal-header">
                            <h4 class="modal-title">Set Advisory</h4>
                            <button class="close-add" name="close-add" id="close-add">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" id="set-form">
                            <div class="card-body">
                                   <div class="col-12">
                                        <label class = "form-label" for="instructors">Instructors: *</label><br/>
                                        <select name="instructors" id="instructors" class = "form-select" required>
                                            <option value="">Select</option>
                                            <?php
                                                $db = database();
                                                $arr = [];
                                                $dept = $_SESSION['department'];
                                                $sql = $db->query("SELECT * FROM personnels WHERE department = '$dept'");
                                                $arr[] = $sql->fetch_array(MYSQLI_ASSOC); 
                                                
                                                foreach($arr as $ar){
                                                    echo "<option value=".$ar['employee_no'].">".$ar['first_name']." ".$ar['last_name']."</option>";
                                                }
                                            ?>
                                        </select><br/>
                                    </div>
                                    <div class="col-12">
                                        <label class = "form-label" for="section">Sections: *</label><br/>
                                        <select name="section" id="section" class = "form-select" required>
                                            <option value="">Select</option>
                                            <option value="IT 1A">1A</option>
                                            <option value="IT 1B">1B</option>
                                            <option value="IT 1C">1C</option>
                                            <option value="IT 2A">2A</option>
                                            <option value="IT 2B">2B</option>
                                            <option value="IT 2C">2C</option>
                                            <option value="IT 3A">3A</option>
                                            <option value="IT 3B">3B</option>
                                            <option value="IT 3C">3C</option>
                                            <option value="IT 4A">4A</option>
                                            <option value="IT 4B">4B</option>
                                            <option value="IT 4C">4C</option>
                                        </select><br/>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-info mx-auto" name="add" ><i class='fa fa-plus'></i> SET</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
