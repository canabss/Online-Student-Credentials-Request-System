<?php
    require_once('assets/phpMailer/Exception.php');
    require_once('assets/phpMailer/PHPMailer.php');
    require_once('assets/phpMailer/SMTP.php');
    require_once('database.php');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    $personnels = [];
    $students = [];
    $alumni = [];
    $students = getAllRequest("Student", "Pending");;
    $alumni = getAllRequest("Alumni", "Pending");
    
    $students1 = getAllRequest("Student", "To Process");;
    $alumni1 = getAllRequest("Alumni", "To Process");
    $personnels = getEmployees(1, 'Confirmed');
    
    foreach($personnels as $personnel){
        notify($personnel['email'], $personnel['first_name'], $personnel['last_name'], count($students), count($alumni), count($students1), count($alumni1));
    }
    
    $clearanceSignatories = getClearanceSignatories('Confirmed');
    foreach($clearanceSignatories as $signatories){
        $db = database();
        $clearances = []; 
        if($signatories['role'] == '2'){
            $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND secretary = 'true' AND dean = 'false' ORDER BY clearance_id");
            while($data = mysqli_fetch_assoc($sql)){
                $clearances[] = $data;
            }
        }
        else if($signatories['role'] == '3'){
            $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND cashier = 'true' AND secretary = 'false' ORDER BY clearance_id");
            while($data = mysqli_fetch_assoc($sql)){
                $clearances[] = $data;
            }
        }
        else if($signatories['role'] == '4'){
            $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND guidance = 'true' AND cashier = 'false' ORDER BY clearance_id");
            while($data = mysqli_fetch_assoc($sql)){
                $clearances[] = $data;
            }
        }
        else if($signatories['role'] == '5'){
            $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND adviser = 'true' AND department_head = 'false' ORDER BY clearance_id");
            while($data = mysqli_fetch_assoc($sql)){
                $clearances[] = $data;
            }
        }
        else if($signatories['role'] == '6'){
            $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND adviser = 'false' ORDER BY clearance_id");
            if($sql){
                while($data = mysqli_fetch_assoc($sql)){
                    $clearances[] = $data;
                }
            }
            
        }
        else if($signatories['role'] == '7'){
            $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND student_affairs = 'true' AND guidance = 'false' ORDER BY clearance_id");
            while($data = mysqli_fetch_assoc($sql)){
                $clearances[] = $data;
            }
        }
        else if($signatories['role'] == '8'){
            $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND library = 'true' AND student_affairs = 'false' ORDER BY clearance_id");
            if($sql){
                while($data = mysqli_fetch_assoc($sql)){
                    $clearances[] = $data;
                }
            }
        }
        else if($signatories['role'] == '9'){
            $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND department_head = 'true' AND clinic = 'false' ORDER BY clearance_id");
            if($sql){
                while($data = mysqli_fetch_assoc($sql)){
                    $clearances[] = $data;
                }
            }
        }
        else if($signatories['role'] == '10'){
            $sql = $db->query("SELECT * FROM clearance WHERE clearance_status = 'Pending' AND clinic = 'true' AND library = 'false' ORDER BY clearance_id");
            while($data = mysqli_fetch_assoc($sql)){
                $clearances[] = $data;
            }
        }
        
        if(count($clearances) != 0){
            notifySignatories($signatories['email'], $signatories['first_name'], $signatories['last_name'], count($clearances));
        }
    }
    
    function notify($email, $firstname, $lastname, $students, $alumni, $students1, $alumni1){
           
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.titan.email';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'no-reply@oscrs-bulsusc.com';
            $mail->Password   = 'pvakabjcspuplv#1';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            
            
            
            try {
                //Recipients
                $mail->setFrom('no-reply@oscrs-bulsusc.com', 'BulSU-SC OSCRS');
                $mail->addAddress($email, $firstname.' '.$lastname);
        
                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Hello  '.$firstname.', Here are the number of pending and processing requests.';
                $mail->Body  = '<html>
                                    <head>
                                        <style type = "text/css">
                                            div.frame{
                                                width: 500px;
                                                margin: auto;
                                            }
                                            .frame-box{
                                                width: 400px;
                                                background-color: white;
                                                padding: 35px;
                                                border: 1px solid rgba(0, 0, 0, 0.2);
                                                border-radius: 1rem;
                                            }
                                            .logo{
                                                height: 85px;
                                                background-image: url(https://oscrs-bulsusc.com/assets/img/logo1.png);
                                                background-repeat: no-repeat;
                                                background-attachment: scroll;
                                                background-position: center center;
                                                background-size: cover;
                                                
                                            }
                                            .label{
                                                font-size: 20px;
                                                font-family: "Montserrat", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                                            }
                                            .small-box {
                                                border-radius: 0.25rem;
                                                box-shadow: 0 0 1px rgba(0, 0, 0, 0.125), 0 1px 3px rgba(0, 0, 0, 0.2);
                                                display: block;
                                                margin-bottom: 20px;
                                                position: relative;
                                                height: 160px;
                                              }
                                              
                                              .small-box .inner {
                                                padding: 2px;
                                              }
                                              
                                              .small-box .h3 {
                                                font-size: 2.0rem;
                                                font-weight: 700;
                                                margin-top: 0;
                                                padding-top: 0;
                                                color: white;
                                                text-align: center;
                                                font-family: "Montserrat", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                                              }
                                              .small-box p {
                                                font-size: 1.2rem;
                                                color: white;
                                                text-align: center;
                                                font-weight: 800;
                                                text-transform: uppercase;
                                                font-family: "Montserrat", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                                              }
                                              
                                              .bg-green{
                                                    background-image: url(https://oscrs-bulsusc.com/assets/img/slide_01.jpg);
                                                    background-repeat: no-repeat;
                                                    background-attachment: scroll;
                                                    background-position: center center;
                                                    background-size: cover;
                                              }
                                             .bg-red {
                                                    background-image: url(https://oscrs-bulsusc.com/assets/img/slide_03.jpg);
                                                    background-repeat: no-repeat;
                                                    background-attachment: scroll;
                                                    background-position: center center;
                                                    background-size: cover;
                                              }
                                              .center{
                                                  text-align: center;
                                                  color: #A9A9A9;
                                              }
                                            @media (max-width: 768px) {
                                                div.frame{
                                                    width: 475px;
                                                    margin: auto;
                                                }
                                                .small-box .h3 {
                                                    font-size: 4.5rem;
                                                }
                                                .small-box p {
                                                    font-size: 1.5rem;
                                                }
                                            }
                                        </style>
                                    </head>
                                    <body>
                                        <div class = "frame">
                                            <div class = "frame-box">
                                                <div class="logo"></div><br>
                                                <p class="label">Hello  <b>'.$firstname.' '.$lastname.'</b>, Here are the number of pending requests.</p>
                                                <div class="box">
                                                    <div class="small-box bg-green">
                                                        <div class="inner">
                                                            <p >Pending Student Requests</p>
                                                            <div class="h3">'.$students.'</div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="box">
                                                    <div class="small-box bg-green">
                                                        <div class="inner">
                                                            <p >Processing Student Requests</p>
                                                            <div class="h3">'.$students1.'</div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="box">
                                                    <div class="small-box bg-red">
                                                        <div class="inner">
                                                            <p>Pending Alumni Requests</p>
                                                            <div class="h3">'.$alumni.'</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="box">
                                                    <div class="small-box bg-red">
                                                        <div class="inner">
                                                            <p>Processing Alumni Requests</p>
                                                            <div class="h3">'.$alumni1.'</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="center">Copyright &copy; Bulacan State University - Sarmiento Campus 2022</div>
                                            </div>
                                        </div>
                                    </body>
                                </html>';
               
                if($mail->send()){
                    echo "message sent.";
                }
            } catch (Exception $exception) {
                $isSent = false;
                echo $exception;
            }
        }
        
        function notifySignatories($email, $firstname, $lastname, $numberOfClearance){
           
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.titan.email';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'no-reply@oscrs-bulsusc.com';
            $mail->Password   = 'pvakabjcspuplv#1';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            
            
            
            try {
                //Recipients
                $mail->setFrom('no-reply@oscrs-bulsusc.com', 'BulSU-SC OSCRS');
                $mail->addAddress($email, $firstname.' '.$lastname);
        
                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Hello  '.$firstname.', Here are the number of pending clearances.';
                $mail->Body  = '<html>
                                    <head>
                                        <style type = "text/css">
                                            div.frame{
                                                width: 500px;
                                                margin: auto;
                                            }
                                            .frame-box{
                                                width: 400px;
                                                background-color: white;
                                                padding: 35px;
                                                border: 1px solid rgba(0, 0, 0, 0.2);
                                                border-radius: 1rem;
                                            }
                                            .logo{
                                                height: 85px;
                                                background-image: url(https://oscrs-bulsusc.com/assets/img/logo1.png);
                                                background-repeat: no-repeat;
                                                background-attachment: scroll;
                                                background-position: center center;
                                                background-size: cover;
                                                
                                            }
                                            .label{
                                                font-size: 20px;
                                                font-family: "Montserrat", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                                            }
                                            .small-box {
                                                border-radius: 0.25rem;
                                                box-shadow: 0 0 1px rgba(0, 0, 0, 0.125), 0 1px 3px rgba(0, 0, 0, 0.2);
                                                display: block;
                                                margin-bottom: 20px;
                                                position: relative;
                                                height: 160px;
                                              }
                                              
                                              .small-box .inner {
                                                padding: 2px;
                                              }
                                              
                                              .small-box .h3 {
                                                font-size: 2.0rem;
                                                font-weight: 700;
                                                margin-top: 0;
                                                padding-top: 0;
                                                color: white;
                                                text-align: center;
                                                font-family: "Montserrat", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                                              }
                                              .small-box p {
                                                font-size: 1.2rem;
                                                color: white;
                                                text-align: center;
                                                font-weight: 800;
                                                text-transform: uppercase;
                                                font-family: "Montserrat", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                                              }
                                              
                                              .bg-green{
                                                    background-image: url(https://oscrs-bulsusc.com/assets/img/slide_01.jpg);
                                                    background-repeat: no-repeat;
                                                    background-attachment: scroll;
                                                    background-position: center center;
                                                    background-size: cover;
                                              }
                                             .bg-red {
                                                    background-image: url(https://oscrs-bulsusc.com/assets/img/slide_03.jpg);
                                                    background-repeat: no-repeat;
                                                    background-attachment: scroll;
                                                    background-position: center center;
                                                    background-size: cover;
                                              }
                                              .center{
                                                  text-align: center;
                                                  color: #A9A9A9;
                                              }
                                            @media (max-width: 768px) {
                                                div.frame{
                                                    width: 475px;
                                                    margin: auto;
                                                }
                                                .small-box .h3 {
                                                    font-size: 4.5rem;
                                                }
                                                .small-box p {
                                                    font-size: 1.5rem;
                                                }
                                            }
                                        </style>
                                    </head>
                                    <body>
                                        <div class = "frame">
                                            <div class = "frame-box">
                                                <div class="logo"></div><br>
                                                <p class="label">Hello  <b>'.$firstname.' '.$lastname.'</b>, Here are the number of pending clearances.</p>
                                                <div class="box">
                                                    <div class="small-box bg-green">
                                                        <div class="inner">
                                                            <p >Pending Clearances</p>
                                                            <div class="h3">'.$numberOfClearance.'</div>
                                                            
                                                        </div>
                                                    </div>
                                                <div class="center">Copyright &copy; Bulacan State University - Sarmiento Campus 2022</div>
                                            </div>
                                        </div>
                                    </body>
                                </html>';
               
                if($mail->send()){
                    echo "message sent.";
                }
            } catch (Exception $exception) {
                $isSent = false;
                echo $exception;
            }
        }
?>