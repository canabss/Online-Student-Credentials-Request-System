<?php
    require_once("database.php");
    $db = database();
    $isExpire = false;
    $clearanceId = $db->real_escape_string($_GET['clearance_id']);
    $requestId = $db->real_escape_string($_GET['request_id']);
    $status = [];
    $status1 = [];
    $sql = $db->query("SELECT * FROM requests WHERE request_id = '$requestId'");

    if($sql){
        $status = $sql->fetch_array(MYSQLI_ASSOC);
        
        $sql1 = $db->query("SELECT * FROM clearance WHERE clearance_id = '$clearanceId'");
        $status1 = $sql1->fetch_array(MYSQLI_ASSOC);
        
        
        if($status['request_status'] == "To Confirm"){
            if($status['requested_documents'] == "Certificate of Good moral" || $status['requested_documents'] == "Honourable Dismissal" || $status['requested_documents'] == "Certificate of Non-issuance of ID" ||$status['requested_documents'] == "Certificate of Good moral and Honourable Dismissal" || $status['requested_documents'] == "Certificate of Good moral and Certificate of Non-issuance of ID" || $status['requested_documents'] == "Honourable Dismissal and Certificate of Non-issuance of ID" || $status['requested_documents'] == "Certificate of Good moral and Honourable Dismissal and Certificate of Non-issuance of ID"){
                $sql1 = $db->query("UPDATE requests SET request_status = 'Pending' WHERE request_id  = '$requestId' ");
            }
            else{
                if($status1['clearance_status'] == "Complete"){
                    $sql1 = $db->query("UPDATE requests SET request_status = 'Pending' WHERE request_id  = '$requestId' ");
                }
                else{
                    $sql = $db->query("UPDATE clearance SET clearance_status = 'Pending' WHERE clearance_id  = '$clearanceId'");
                    $sql1 = $db->query("UPDATE requests SET request_status = 'For Clearance' WHERE request_id  = '$requestId' ");
                }
            }
        }
        else{
            $isExpire = true;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Request Confirmed - Credential Request System</title>
        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css"/>
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <link href="assets/css/homepage.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/logo.png" />
    </head>
    <body>
        <div>
            <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav" style="background: maroon;">
                <div class="container-fluid">
                    <a class="navbar-brand" href="https://oscrs-bulsusc.com/"><img src="assets/img/BSU-w.png" style= "height:40px; width: 250px" alt="OSCRS-Logo" /></a>
                    
                </div>
            </nav>
        </div>
        
        <header class="masthead">
            <div class="container">
                <?php if(!$isExpire):?>
                    <br><br>
                    <div class="masthead-heading text-uppercase text-muted">Your request has been confirmed.</div>
                    <div class="masthead-subheading text-muted">See updates on your email or you can track your request, just click the button.</div>
                    <a class="btn btn-info btn-xl text-uppercase" href="request/trackRequest.php">Track Request</a>
                    <br><br><br>
                <?php endif;?>
                <?php if($isExpire):?>
                    <br><br><br><br>
                    <div class="masthead-heading text-uppercase text-muted">Your Email is already Verified.</div>
                    <div class="masthead-subheading text-muted">See updates on your email or you can track your request, just click the button.</div>
                    <a class="btn btn-info btn-xl text-uppercase" href="request/trackRequest.php">Track Request</a>
                    <br><br><br>
                <?php endif;?>
            </div>
        </header>

        <footer class="footer py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div style="align-items: center;">Copyright &copy; Bulacan State University - Sarmiento Campus 2022</div>
                </div>
            </div>
        </footer>
        <script src="assets/js/jquery-3.3.1.js"></script>
    </body>
</html>