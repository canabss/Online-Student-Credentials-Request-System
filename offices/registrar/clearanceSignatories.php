<?php
    require_once('../database.php');
    require_once('../validations.php');
    require_once('../functions.php');
    require_once('nav.php');
    session_start();
    $myInfo = [];
    $error = [];
    $gender = "";
    
    auth();

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
        <title>Clearance Signatories</title>
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
            <?php sidebar(8);?>
           
            <div id="content">
                <?php head();?><br>
                <section class="content" style="margin-top: 50px;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-body ">
                                    <div class="col-lg-12 justify-content-between" style="display:flex;">
                                        <h1>Clearance Signatories</h1>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="modal-footer justify-content-between" style="background-color:white; border:none;">
                                             <div class="filter" style="color:black;">
                                                <select class="filter-course" id="filters1" name="filterby">
                                                    <option value="">Select</option> 
                                                    <option value="1" <?php if("$isSelected" == "1") :?>selected = "selected"<?php endif;?>>Registrar</option>
                                                    <option value="2" <?php if("$isSelected" == "2") :?>selected = "selected"<?php endif;?>>Campus Dean</option>
                                                    <option value="3" <?php if("$isSelected" == "3") :?>selected = "selected"<?php endif;?>>Campus Secretary</option>
                                                    <option value="4" <?php if("$isSelected" == "4") :?>selected = "selected"<?php endif;?>>Cashier</option>
                                                    <option value="5" <?php if("$isSelected" == "5") :?>selected = "selected"<?php endif;?>>Department Head</option>
                                                    <option value="6" <?php if("$isSelected" == "6") :?>selected = "selected"<?php endif;?>>Instructor</option>
                                                    <option value="7" <?php if("$isSelected" == "7") :?>selected = "selected"<?php endif;?>>Guidance</option>
                                                    <option value="8" <?php if("$isSelected" == "8") :?>selected = "selected"<?php endif;?>>Student Affairs</option>
                                                    <option value="9" <?php if("$isSelected" == "9") :?>selected = "selected"<?php endif;?>>Campus Nurse</option>
                                                    <option value="10" <?php if("$isSelected" == "10") :?>selected = "selected"<?php endif;?>>Campus Librarian</option>
                                                </select>
                                            </div>
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
                                                <th>Role</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $personnels[] = getSignatories("1", 'Confirmed'); $count = 1;?>
                                            <?php foreach($personnels as $key) :?>
                                                <?php foreach($key as $personnel) :?>
                                                    <?php if($personnel['middle_name']=="N/A") $personnel['middle_name']="";?>
                                                    <tr>
                                                        <td width="18"><?php echo $count;?></td>
                                                        <td width="18">
                                                            <form method="POST">
                                                                <input type="hidden" name="id" value="<?php echo $personnel["employee_no"];?>">
                                                                <button type = 'submit' class='btn-edit btn-xs' name = 'delete' id = 'delete'>
                                                                    <i class='fas fa-trash-alt'></i> Delete
                                                                </button>
                                                            </form>
                                                        </td>
                                                        <td><?php echo $personnel["employee_no"];?></td>
                                                        <td><?php echo $personnel["first_name"]." ".$personnel["middle_name"]." ".$personnel["last_name"];?></td>
                                                        <td><?php echo $personnel["gender"];?></td>
                                                        <td><?php echo $personnel["email"];?></td>
                                                        <td><?php echo $personnel["birthday"];?></td>
                                                        <td>
                                                            <?php 
                                                                if($personnel["role"]  == "2"){
                                                                    echo "Dean";
                                                                }
                                                                else if($personnel["role"]  == "3"){
                                                                    echo "Campus Secretary";
                                                                }
                                                                else if($personnel["role"]  == "4"){
                                                                    echo "Cashier";
                                                                }
                                                                else if($personnel["role"] == "5"){
                                                                    echo "Department Head";
                                                                }
                                                                else if($personnel["role"] == "6"){
                                                                    echo "Adviser";
                                                                }
                                                                else if($personnel["role"] == "7"){
                                                                    echo "Guidance";
                                                                }
                                                                else if($personnel["role"] == "8"){
                                                                    echo "Student Affairs";
                                                                }
                                                                else if($personnel["role"] == "9"){
                                                                    echo "Nurse";
                                                                }
                                                                else if($personnel["role"] == "10"){
                                                                    echo "Librarian";
                                                                }
                                                            ?>
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
                            window.location.replace('personnels.php?id=<?php echo $_SESSION['user_id']?>');
                        }
                    });
                </script>
                <?php unset($_SESSION['deleted']);?>
            <?php endif;?>
        <?php endif; ?>

    </body>
</html>
