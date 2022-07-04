<?php
    require_once("../database.php");
    session_start();
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){
        $requestId = $_POST['request_id'];
        $db = database();
		$request = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM requests WHERE NOT request_status = 'Archieve' ORDER BY request_id AND requestor DESC");
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
        <link href="https://oscrs-bulsusc.com/assets/css/bootstrap.css" rel="stylesheet" />
        <link href="https://oscrs-bulsusc.com/assets/css/homepage.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="https://oscrs-bulsusc.com/assets/img/logo.png" />
    </head>
    <body>
        <div id="page-top">
            <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav" style="background: maroon;">
                <div class="container-fluid">
                    <a class="navbar-brand" href="https://oscrs-bulsusc.com/"><img src="https://oscrs-bulsusc.com/assets/img/BSU-w.png" style= "height:40px; width: 250px" alt="OSCRS-Logo" /></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                        Menu
                        <i class="fas fa-bars ms-1"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarResponsive">
                        <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                            <li class="nav-item"><a class="nav-link" href="https://oscrs-bulsusc.com/">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="https://oscrs-bulsusc.com/#contact">Contact Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="FAQs">FAQ's</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        
        <section class="page-section bg-light" id="request" style="height: 100vh;"><br/>
            <div class="container">
                <div class="card mx-auto col-lg-6">
                    
                    <h3>Enter your Request No.</h3>
                    <p class="text-muted">Check updates of your request.</p></br>
                    <form id="contactForm" method="POST">
                        <div class="form-group">
                            <input class = "form-control" type="text" name="request_id" placeholder="Request Code" style="width: 350px"/></br></br>
                        </div>
                        <div>
                            <a href="https://oscrs-bulsusc.com/" class="link"><i class="fas fa-chevron-left mr"></i> Back</a>
                            <button class="btn btn-info text-uppercase" name="submit" href="#request" style="float: right;" id="submit">Submit</button>
                        </div>
                    </form>
                   
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
        <script src="https://oscrs-bulsusc.com/assets/js/scripts.js"></script>
        <script src="https://oscrs-bulsusc.com/assets/js/jquery-3.3.1.js"></script>
        <script src="https://oscrs-bulsusc.com/assets/vendor/package/dist/sweetalert2.all.min.js"></script>
        <script src="https://oscrs-bulsusc.com/assets/js/bootstrap.bundle.min.js"></script>
    </body>
</html>