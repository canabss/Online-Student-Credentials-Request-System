<?php
    require_once('../database.php');
    require_once('../validations.php');
    session_start();
    
    if(!isset($_SESSION['request_id'])){
        header("Location: https://oscrs-bulsusc.com/#request");
        exit();
    }
    else{
        $request = [];
        $student = [];
        $requestId = $studentId = $email = $firstname = $lastname = $middlename = $course = $year = $section = $documents = $status = "";

        if(isset($_SESSION['request_id'])){
            $requestId = $_SESSION['request_id'];
            $request[] = getRequest($requestId);

            foreach($request as $key){
                foreach($key as $req){
                    $requestId = $req['request_id'];
                    $studentId = $req['student_id'];
                    $email = $req['email'];
                    $documents = $req['requested_documents'];
                    $status = $req['request_status'];
                    $requestor = $req['requestor'];
                    $purpose = $req['purpose'];
                }
            }

            $student[] = getStudent($studentId);

            foreach($student as $key){
                foreach($key as $stud){
                    $firstname = $stud['first_name'];
                    $lastname = $stud['last_name'];
                    $middlename = $stud['middle_name'];
                    if($stud["middle_name"] == "N/A"){
                        $middlename = "";
                    }
                    else{
                        $middlename = $stud["middle_name"];
                    }
                    $course = $stud['course'];
                    $year = $stud['year_level'];
                    $section = $stud['section'];
                }
            }
            
        }
    }
    
   
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Request Updates</title>
        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css"/>
        <link href="https://oscrs-bulsusc.com/assets/css/bootstrap.css" rel="stylesheet" />
        <link href="https://oscrs-bulsusc.com/assets/css/homepage.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="https://oscrs-bulsusc.com/assets/img/logo.png" />
    </head>
    <body>
        <div id="page-top">
            <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav" style="background: maroon;">
                <div class="container-fluid">
                    <a class="navbar-brand" href="https://oscrs-bulsusc.com/"><img src="../assets/img/BSU-w.png" style= "height:40px; width: 250px" alt="OSCRS-Logo" /></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                        Menu
                        <i class="fas fa-bars ms-1"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarResponsive">
                        <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                            <li class="nav-item"><a class="nav-link" href="https://oscrs-bulsusc.com">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="https://oscrs-bulsusc.com/#contact">Contact Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="FAQs">FAQ's</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        
        <section class="page-section bg-light" id="request"><br>
            <div class="container">
                <div class="card mx-auto col-lg-7" style="margin-left: 10px; margin-right:10px;">
                    <a href="https://oscrs-bulsusc.com/#request" class="link text-uppercase" style="padding-bottom: 10px; float: left;"><i class="fas fa-chevron-left mr"></i> Back</a>
                    <div class="text-center">
                        <h2 class="section-heading text-uppercase">Request: <?php echo $requestId;?></h2>
                        <h3 class="section-subheading text-muted">Here is your Request Status</h3>
                    </div>
                    <div class="col-lg-11">
                        <table class="table mx-auto">
                            <tr>
                                <th>Student No.</th>
                                <td><?php echo $studentId;?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?php echo $email;?></td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td><?php echo $firstname." ".$middlename." ".$lastname;?></td>
                            </tr>
                            <tr>
                                <th>Course</th>
                                <td><?php echo $course;?></td>
                            </tr>
                            <?php if($requestor == "Student"):?>
                                <tr>
                                    <th>Year</th>
                                    <td><?php echo $year;?></td>
                                </tr>
                                <tr>
                                    <th>Section</th>
                                    <td><?php echo $section;?></td>
                                </tr>
                            <?php endif;?>
                            <tr>
                                <th>Requested Documents</th>
                                <td><?php echo $documents;?></td>
                            </tr>
                            <tr>
                                <th>Purpose</th>
                                <td><?php echo $purpose;?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <?php if($status == "To Confirm"):?>
                                        <mark style="background-color: orange; color: white;">
                                            <?php echo $status;?>
                                        </mark>
                                        <?php echo ', This request will not be process unless you confirm. We have sent to your email a request confirmation, please confirm first. Thank you.';?>
                                    <?php endif;?>
                                    <?php if($status == "For Clearance"):?>
                                        <mark style="background-color: red; color: white;">
                                            <?php echo $status;?>
                                        </mark>
                                    <?php endif;?>
                                    <?php if($status == "Pending"):?>
                                        <mark style="background-color: yellow; color: black;">
                                            <?php echo $status;?>
                                        </mark>
                                    <?php endif;?>
                                    <?php if($status == "To Process"):?>
                                        <mark style="background-color: green; color: white;">
                                            <?php echo $status;?>
                                        </mark>
                                    <?php endif;?>
                                </td>
                            </tr>
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
        <script src="https://oscrs-bulsusc.com/assetsjs/scripts.js"></script>
        <script src="https://oscrs-bulsusc.com/assetsjs/jquery-3.3.1.js"></script>
        <script src="https://oscrs-bulsusc.com/assets/vendor/package/dist/sweetalert2.all.min.js"></script>
        <script src="https://oscrs-bulsusc.com/assetsjs/bootstrap.bundle.min.js"></script>
    </body>
</html>