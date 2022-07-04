<?php
    require_once('database.php');
    session_start();
    unset($_SESSION['request_id']);
    unset($_SESSION['document0']);
    unset($_SESSION['document1']);
    unset($_SESSION['document2']);
    unset($_SESSION['document3']);
    unset($_SESSION['document4']);
    $error = [];
    
    
    if(isset($_POST['send'])){
        $studentNo = $_POST['studentNo'];
        $completeName = ucFirst($_POST['name']);
        $email = $_POST['email'];
        $message = ucFirst($_POST['message']);

        //Student No. input validation
        if(!preg_match("/^[0-9-]+$/D", $studentNo)) {
            $error[] = 'Special characters, letters and spaces are not allowed in Student No. field, Please follow the given format.';
            $_SESSION['studentno'] = $studentNo;
        }
        else{
            $_SESSION['studentno'] = $studentNo;
        }

        //Lastname input Validation
        if(!preg_match("/^[a-zA-Z-' ]*$/", $completeName)) {
            $error[] = 'Special characters are not allowed in Complete Name.';
            $_SESSION['name'] = $completeName;
        }else{
            $_SESSION['name'] = $completeName;
        }

        //Email input validation
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error[] = 'Incorrect Email format. Please follow the given format.';
            if(isset($_SESSION['email'])) {
                unset($_SESSION['email']);
            }
        }
        else{
            $_SESSION['email'] = $email;
        }


        if(empty($error)){
            $db = database();
            $sql = $db->query("INSERT INTO messages(sender_id, sender_name, sender_email, sender_message) VALUES('$studentNo', '$completeName', '$email', '$message')");
            if($sql){
                $_SESSION['send'] = true;
            }
            else{
               $_SESSION['send'] = false;
            }
        }
        else{
            $_SESSION['send'] = false;
        }
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['track'])){
        $requestId = $_POST['request_id'];
      
        
        $db = database();
		$request = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM requests WHERE NOT request_status = 'Archieve' AND NOT request_status = 'Deleted' ORDER BY request_id AND requestor DESC");
			if($sql){
				while($data = mysqli_fetch_assoc($sql)){
					$request[] = $data;
				}
			}
			
	    }
	    $isExist = false;
	    foreach($request as $req){
	        if($req['request_id'] == $requestId){
	            $isExist = true;
	            break;
	        }
	    }
	    if($isExist){
	        $_SESSION['request_id'] = $requestId;
	        header("Location: https://oscrs-bulsusc.com/request/requestUpdates");
	        exit(); 
	    }
	    else {
	        $_SESSION['request_id'] = $requestId;
	        header("Location: https://oscrs-bulsusc.com/request/requestNotFound?request_id='$requestId'");
	        exit(); 
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
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <link href="assets/css/homepage.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/logo.png" />
    </head>
    <body>
        <div id="page-top">
            <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav" style="background: maroon;">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#"><img src="assets/img/BSU-w.png" style= "height: 50px;" alt="OSCRS-Logo" /></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                        Menu
                        <i class="fas fa-bars ms-1"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarResponsive">
                        <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                            <li class="nav-item"><a class="nav-link" href="#page-top">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="#contact">Contact Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="FAQs">FAQ's</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        
        <header class="masthead heads">
            <div class="container">
                <div class="masthead-heading text-uppercase">WELCOME TO ONLINE STUDENT CREDENTIAL REQUEST SYSTEM</div><br/>
                <div class="masthead-subheading">Get your official documents and credentials</div>
                <a class="btn btn-primary btn-xl text-uppercase" href="#request">Request now</a>
            </div>
        </header>
        
        <section class="page-section bg-light" id="request">
            <div class="container ">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Make a Request</h2>
                    <h3 class="section-subheading text-muted">Get your credentials here.</h3>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <a href="request/alumniRequestForm?requestor=alumni" class="request-link">
                            <div class="service first-service" id="alumni">
                                <div class="icon"></div>
                                <h4>Alumni Request</h4>
                                <p>Alumni can request documents here.</p>
                            </div>
                        </a>
                    </div>
                     <div class="col-lg-4">
                         <a href="request/studentRequestForm?requestor=student" class="request-link">
                            <div class="service second-service">
                                <div class="icon"></div>
                                <h4>Student Request </h4>
                                <p>Students can request documents here.</p>
                            </div>
                        </a>
                    </div>
                     <div class="col-lg-4" style="cursor: pointer;">
                        <a class="request-link" id="open">
                            <div class="service third-service">
                                <div class="icon"></div>
                                <h4>Tracking Request</h4>
                                <p>You can see updates on request here.</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 mx-auto text-center"><p class="large text-muted"><br>The Bulacan State University - Sarmiento Campus created an online platform for our dear alumni and currently enrolled students to easily access their required documents.<br><br>The University strives to fulfill its mission and vision by offering services and a helping hand to our most deserving students.</p></div>
                </div>
            </div>
            <div class="modal fade" id="track" style="padding-top: 180px;">
                <div class="modal-dialog modal-md">
                    <form method="POST">
                        <div class="modal-content">
                            <div class="modal-header bg-dark">
                                <div>
                                    <h4 class="modal-title">Check updates of your request.</h4>
                                </div>
                                <button type="submit" class="close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="card card-primary">
                                <h6 class="text-muted text-center">Enter your request no.</h6><br/>
                                <div class="row">
                                    <div class="form-group">
                                        <input class = "form-control" name="request_id" type="text" placeholder="Request No." style="width: 350px"/></br>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-dark justify-content-between">
                                <button type="submit" name="track" class="btn btn-info mx-auto"><i class="fa fa-search"></i> Track</button>
                            </div>
                        </div>
                    </form>
                </div>
            <div>
        </section>
        
        <section class="page-section" id="contact">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Contact Us</h2>
                    <h3 class="section-subheading text-muted">Please write your queries here.</h3>
                </div>

                <form method="post" action="" id="contactForm">
                    <div class="row align-items-stretch mb-5">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control" name="studentNo"id="student_no" type="text" placeholder="Student No. (Ex. 2018-500123)" value="<?php echo !isset($_SESSION['studentno']) ? '' : $_SESSION['studentno'] ?>"/>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="name" id="name" type="text" placeholder="Complete Name *" value="<?php echo !isset($_SESSION['name']) ? '' : $_SESSION['name'] ?>" required/>
                            </div>
                            <div class="form-group mb-md-0">
                                <input class="form-control" name="email" id="email" type="email" placeholder="Email *" value="<?php echo !isset($_SESSION['email']) ? '' : $_SESSION['email'] ?>" required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-textarea mb-md-0">
                                <textarea class="form-control" name="message" id="message" placeholder="Message *" required><?php echo !isset($_SESSION['message']) ? htmlspecialchars('') : htmlspecialchars($_SESSION['message']) ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="text-center"><input type="submit" name="send" class="btn btn-primary btn-xl text-uppercase" id="send" value="Send Message"/></div>
                    
                </form>
            </div>
        </section>
        
        <footer class="footer py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div style="align-items: center;">Copyright &copy; Bulacan State University - Sarmiento Campus 2022</div>
                </div>
            </div>
        </footer>
        <script src="assets/js/jquery-3.3.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/jquery-scripts.js"></script>
        <?php if(isset($_SESSION['send'])) :?>
            <?php if($_SESSION['send'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Message Sent Successfully', 
                        icon:'success', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('https://oscrs-bulsusc.com/');
                        }
                    });
                </script>
                <?php 
                    unset($_SESSION['send']);
                    unset($_SESSION['studentno']);
                    unset($_SESSION['name']);
                    unset($_SESSION['email']);
                    unset($_SESSION['message']);
                ?>
            <?php endif;?>
        <?php endif; ?>
        <?php if(isset($_SESSION['send'])) :?>
            <?php if($_SESSION['send'] == false) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Message was unable to be sent.',
                        html: '<?php foreach($error as $er){ echo "* ".$er."<br/>"; }?>', 
                        icon:'error', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.href = "#contact";
                        }
                    });
                </script>
                <?php unset($_SESSION['send']);?>
            <?php endif;?>
        <?php endif;?>
    </body>
</html>
