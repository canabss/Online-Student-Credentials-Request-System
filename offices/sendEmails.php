<?php
    require_once('assets/phpMailer/Exception.php');
    require_once('assets/phpMailer/PHPMailer.php');
    require_once('assets/phpMailer/SMTP.php');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
        function requestConfirmation($requestNo, $email, $lastname, $firstname, $clearance_id){
            
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
                $mail->Subject = 'Request Confirmation.';
                $mail->Body  = '<html>
                                    <head>
                                        <style type = "text/css">
                                            *{
                                                font-size: 16px;
                                            }
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
                                            .confirm{
                                                color: white;
                                                background-color: maroon;
                                                text-align: center;
                                                font-family: "Monserrat", sans-serif;
                                                font-size: 16px;
                                                border: 1px solid rgba(0, 0, 0, 0.125);
                                                border-radius: 0.25rem;
                                                font-weight: bold;
                                                text-decoration: none;
                                                padding-left: 165px;
                                                padding-right: 165px;
                                                padding-top: 15px;
                                                padding-bottom: 15px;
                                            }
                                            .confirm:hover{
                                                text-align: center;
                                                opacity: 85%;
                                                font-family: "Monserrat", sans-serif;
                                                font-weight: bold;
                                            }
                                            @media (max-width: 768px) {
                                                *{
                                                    font-size: 18px;
                                                }
                                                div.frame{
                                                    width: 475px;
                                                    margin: auto;
                                                }
                                            }
                                        </style>
                                    </head>
                                    <body>
                                        <div class = "frame">
                                            <div class = "frame-box">
                                                <div class="logo"></div><br><br>
                                                <div>
                                                    <div>Hello, <b>'.$firstname.' '.$lastname.'</b><br><br>
                                                        Clearance No.: <b>'.$clearance_id.'</b><br>
                                                        Request No.: <b>'.$requestNo.'</b><br><br>
                                                        Please click the button for confirmation of your request.
                                                    </div><br><br>
                                                    <a class="confirm" style="color: white;" href="https://oscrs-bulsusc.com/requestConfirmation?request_id='.$requestNo.'&clearance_id='.$clearance_id.'">Confirm</a>
                                                </div><br><br>
                                                Regards,
                                                <br>BulSU-SC, OSCRS.
                                            </div>
                                        </div>
                                    </body>
                                </html>';
               
                if($mail->send()){
                    //echo "message sent.";
                }
            } catch (Exception $exception) {
                echo $exception;
            }
        }
        
        function forgotPassword($email, $firstname, $lastname, $clearanceId){
            
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
                $mail->addAddress($email);
        
                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Reset Password Link.';
                $mail->Body  = '<html>
                                    <head>
                                        <style type = "text/css">
                                            *{
                                                font-size: 16px;
                                            }
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
                                            .confirm{
                                                color: white;
                                                background-color: maroon;
                                                text-align: center;
                                                font-family: "Monserrat", sans-serif;
                                                font-size: 16px;
                                                border: 1px solid rgba(0, 0, 0, 0.125);
                                                border-radius: 0.25rem;
                                                font-weight: bold;
                                                text-decoration: none;
                                                padding-left: 165px;
                                                padding-right: 165px;
                                                padding-top: 15px;
                                                padding-bottom: 15px;
                                            }
                                            .confirm:hover{
                                                text-align: center;
                                                opacity: 85%;
                                                font-family: "Monserrat", sans-serif;
                                                font-weight: bold;
                                            }
                                            @media (max-width: 768px) {
                                                *{
                                                    font-size: 18px;
                                                }
                                                div.frame{
                                                    width: 475px;
                                                    margin: auto;
                                                }
                                            }
                                        </style>
                                    </head>
                                    <body>
                                        <div class = "frame">
                                            <div class = "frame-box">
                                                <div class="logo"></div><br><br>
                                                <div>
                                                    <div>Hello, <b>'.$firstname.' '.$lastname.'!</b><br><br>
                                                        Please click the button below to reset your password!.
                                                    </div><br><br>
                                                    <a class="confirm" style="color: white;" href="https://offices.oscrs-bulsusc.com/forgotPassword?email='.$email.'&account_id='.$clearanceId.'">Click Me</a>
                                                </div><br><br>
                                                Regards,
                                                <br>BulSU-SC, OSCRS.
                                            </div>
                                        </div>
                                    </body>
                                </html>';
               
                if($mail->send()){
                    //echo "message sent.";
                }
            } catch (Exception $exception) {
                echo $exception;
            }
        }

        function accountConfirmation($employeeNo, $email, $lastname, $firstname, $role){
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
                $mail->Subject = 'Email Verification';
                $mail->Body  = '<html>
                                    <head>
                                        <style type = "text/css">
                                            *{
                                                font-size: 16px;
                                            }
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
                                            .confirm{
                                                color: white;
                                                background-color: maroon;
                                                text-align: center;
                                                font-family: "Monserrat", sans-serif;
                                                font-size: 16px;
                                                border: 1px solid rgba(0, 0, 0, 0.125);
                                                border-radius: 0.25rem;
                                                font-weight: bold;
                                                text-decoration: none;
                                                padding-left: 165px;
                                                padding-right: 165px;
                                                padding-top: 15px;
                                                padding-bottom: 15px;
                                            }
                                            .confirm:hover{
                                                text-align: center;
                                                opacity: 85%;
                                                font-family: "Monserrat", sans-serif;
                                                font-weight: bold;
                                            }
                                            @media (max-width: 768px) {
                                                *{
                                                    font-size: 18px;
                                                }
                                                div.frame{
                                                    width: 475px;
                                                    margin: auto;
                                                }
                                            }
                                        </style>
                                    </head>
                                    <body>
                                        <div class = "frame">
                                            <div class = "frame-box">
                                                <div class="logo"></div><br><br>
                                                <div>
                                                    <div>Hello, <b>'.$firstname.'</b><br><br>
                                                        Request No.: <b>'.$employeeNo.'</b><br><br>
                                                        Please click the button below to verify your email.
                                                    </div><br><br>
                                                    <a class="confirm" style="color: white;" href="https://offices.oscrs-bulsusc.com/accountConfirmation?employee_no='.$employeeNo.'&role='.$role.'">Confirm</a>
                                                </div><br><br>
                                                Regards,
                                                <br>BulSU-SC, OSCRS.
                                            </div>
                                        </div>
                                    </body>
                                </html>';
                if($mail->send()){
                    //echo "message sent.";
                }
            } catch (Exception $e) {
                echo $exception;
            }
        }
        
        function accountApproveNotification($employeeNo, $email, $lastname, $firstname){
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
                $mail->Subject = 'Email Verification';
                $mail->Body  = '<html>
                                    <head>
                                        <style type = "text/css">
                                            *{
                                                font-size: 16px;
                                            }
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
                                            .confirm{
                                                color: white;
                                                background-color: maroon;
                                                text-align: center;
                                                font-family: "Monserrat", sans-serif;
                                                font-size: 16px;
                                                border: 1px solid rgba(0, 0, 0, 0.125);
                                                border-radius: 0.25rem;
                                                font-weight: bold;
                                                text-decoration: none;
                                                padding-left: 165px;
                                                padding-right: 165px;
                                                padding-top: 15px;
                                                padding-bottom: 15px;
                                            }
                                            .confirm:hover{
                                                text-align: center;
                                                opacity: 85%;
                                                font-family: "Monserrat", sans-serif;
                                                font-weight: bold;
                                            }
                                            @media (max-width: 768px) {
                                                *{
                                                    font-size: 18px;
                                                }
                                                div.frame{
                                                    width: 475px;
                                                    margin: auto;
                                                }
                                            }
                                        </style>
                                    </head>
                                    <body>
                                        <div class = "frame">
                                            <div class = "frame-box">
                                                <div class="logo"></div><br><br>
                                                <div>
                                                    <div>Hello, <b>'.$firstname.'</b><br><br>
                                                        Your account has been approve by your office. You may login now, just click the button below.
                                                    </div><br><br>
                                                    <a class="confirm" href="https://offices.oscrs-bulsusc.com/">Login Now</a>
                                                </div><br><br>
                                                Regards,
                                                <br>BulSU-SC, OSCRS.
                                            </div>
                                        </div>
                                    </body>
                                </html>';
                if($mail->send()){
                    //echo "message sent.";
                }
            } catch (Exception $e) {
                echo $exception;
            }
        }
        
        function comment($requestNo, $lastname, $firstname, $middlename, $email, $senderName, $comment){
             
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.titan.email';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'no-reply@oscrs-bulsusc.com';
            $mail->Password   = 'pvakabjcspuplv#1';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $isSent = false;
            try {
                //Recipients
                $mail->setFrom('no-reply@oscrs-bulsusc.com', 'BulSU-SC OSCRS');
                $mail->addAddress($email, $firstname.' '.$lastname);
        
                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Request Comment(s).';
                $mail->Body  = '<html>
                                    <head>
                                        <style type = "text/css">
                                            *{
                                                font-size: 16px;
                                            }
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
                                            .confirm{
                                                color: white;
                                                background-color: maroon;
                                                text-align: center;
                                                font-family: "Monserrat", sans-serif;
                                                font-size: 16px;
                                                border: 1px solid rgba(0, 0, 0, 0.125);
                                                border-radius: 0.25rem;
                                                font-weight: bold;
                                                text-decoration: none;
                                                padding-left: 165px;
                                                padding-right: 165px;
                                                padding-top: 15px;
                                                padding-bottom: 15px;
                                            }
                                            .confirm:hover{
                                                text-align: center;
                                                opacity: 85%;
                                                font-family: "Monserrat", sans-serif;
                                                font-weight: bold;
                                            }
                                            @media (max-width: 768px) {
                                                *{
                                                    font-size: 18px;
                                                }
                                                div.frame{
                                                    width: 475px;
                                                    margin: auto;
                                                }
                                            }
                                        </style>
                                    </head>
                                    <body>
                                        <div class = "frame">
                                            <div class = "frame-box">
                                                <div class="logo"></div><br><br>
                                                <div>
                                                    <div>Hello, <b>'.$firstname." ".$middlename." ".$lastname.'</b><br><br>
                                                        Request No.: <b>'.$requestNo.'</b><br><br>'.$comment
                                                    .'</div>
                                                </div><br>
                                                Regards,<br>'.$senderName
                                                .'<br>BulSU-SC, OSCRS.
                                            </div>
                                        </div>
                                    </body>
                                </html>';
               
                if($mail->send()){
                    $isSent = true;
                }
            } catch (Exception $exception) {
                $isSent = false;
                $error = $exception;
            }
            return $isSent;
        }
        
        function commentClearance($clearanceId, $lastname, $firstname, $middlename, $email, $senderName, $comment, $office){
             
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.titan.email';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'no-reply@oscrs-bulsusc.com';
            $mail->Password   = 'pvakabjcspuplv#1';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $isSent = false;
            try {
                //Recipients
                $mail->setFrom('no-reply@oscrs-bulsusc.com', 'BulSU-SC OSCRS');
                $mail->addAddress($email, $firstname.' '.$lastname);
        
                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Request Comment(s).';
                $mail->Body  = '<html>
                                    <head>
                                        <style type = "text/css">
                                            *{
                                                font-size: 16px;
                                            }
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
                                            .confirm{
                                                color: white;
                                                background-color: maroon;
                                                text-align: center;
                                                font-family: "Monserrat", sans-serif;
                                                font-size: 16px;
                                                border: 1px solid rgba(0, 0, 0, 0.125);
                                                border-radius: 0.25rem;
                                                font-weight: bold;
                                                text-decoration: none;
                                                padding-left: 165px;
                                                padding-right: 165px;
                                                padding-top: 15px;
                                                padding-bottom: 15px;
                                            }
                                            .confirm:hover{
                                                text-align: center;
                                                opacity: 85%;
                                                font-family: "Monserrat", sans-serif;
                                                font-weight: bold;
                                            }
                                            @media (max-width: 768px) {
                                                *{
                                                    font-size: 18px;
                                                }
                                                div.frame{
                                                    width: 475px;
                                                    margin: auto;
                                                }
                                            }
                                        </style>
                                    </head>
                                    <body>
                                        <div class = "frame">
                                            <div class = "frame-box">
                                                <div class="logo"></div><br><br>
                                                <div>
                                                    <div>Hello, <b>'.$firstname." ".$lastname.'</b><br><br>
                                                        Clearance No.: <b>'.$clearanceId.'</b><br><br>'.$comment
                                                    .'</div>
                                                </div><br>
                                                Regards,<br>'.$senderName
                                                .', '.$office.'<br>BulSU-SC, OSCRS.
                                            </div>
                                        </div>
                                    </body>
                                </html>';
               
                if($mail->send()){
                    $isSent = true;
                }
            } catch (Exception $exception) {
                $isSent = false;
                $error = $exception;
            }
            return $isSent;
        }
        
        function reply($studentNo, $name, $email, $senderName, $comment){
             
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.titan.email';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'no-reply@oscrs-bulsusc.com';
            $mail->Password   = 'pvakabjcspuplv#1';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $isSent = false;
            try {
                //Recipients
                $mail->setFrom('no-reply@oscrs-bulsusc.com', 'BulSU-SC OSCRS');
                $mail->addAddress($email, $name);
        
                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Request Comment(s).';
                $mail->Body  = '<html>
                                    <head>
                                        <style type = "text/css">
                                            *{
                                                font-size: 16px;
                                            }
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
                                            .confirm{
                                                color: white;
                                                background-color: maroon;
                                                text-align: center;
                                                font-family: "Monserrat", sans-serif;
                                                font-size: 16px;
                                                border: 1px solid rgba(0, 0, 0, 0.125);
                                                border-radius: 0.25rem;
                                                font-weight: bold;
                                                text-decoration: none;
                                                padding-left: 165px;
                                                padding-right: 165px;
                                                padding-top: 15px;
                                                padding-bottom: 15px;
                                            }
                                            .confirm:hover{
                                                text-align: center;
                                                opacity: 85%;
                                                font-family: "Monserrat", sans-serif;
                                                font-weight: bold;
                                            }
                                            @media (max-width: 768px) {
                                                *{
                                                    font-size: 18px;
                                                }
                                                div.frame{
                                                    width: 475px;
                                                    margin: auto;
                                                }
                                            }
                                        </style>
                                    </head>
                                    <body>
                                        <div class = "frame">
                                            <div class = "frame-box">
                                                <div class="logo"></div><br><br>
                                                <div>
                                                    <div>Hello, <b>'.$name.'</b><br><br>'.$comment
                                                    .'</div>
                                                </div><br>
                                                Regards,<br>'.$senderName
                                                .'<br>BulSU-SC, OSCRS.
                                            </div>
                                        </div>
                                    </body>
                                </html>';
               
                if($mail->send()){
                    $isSent = true;
                }
            } catch (Exception $exception) {
                $isSent = false;
                $error = $exception;
            }
            return $isSent;
        }
        
        function emailNotify($requestNo, $lastname, $firstname, $middlename, $email, $text){
             
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.titan.email';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'no-reply@oscrs-bulsusc.com';
            $mail->Password   = 'pvakabjcspuplv#1';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $isSent = false;
            try {
                //Recipients
                $mail->setFrom('no-reply@oscrs-bulsusc.com', 'BulSU-SC OSCRS');
                $mail->addAddress($email, $firstname.' '.$lastname);
        
                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Request Notification.';
                $mail->Body  = '<html>
                                    <head>
                                        <style type = "text/css">
                                            *{
                                                font-size: 16px;
                                            }
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
                                            .confirm{
                                                color: white;
                                                background-color: maroon;
                                                text-align: center;
                                                font-family: "Monserrat", sans-serif;
                                                font-size: 16px;
                                                border: 1px solid rgba(0, 0, 0, 0.125);
                                                border-radius: 0.25rem;
                                                font-weight: bold;
                                                text-decoration: none;
                                                padding-left: 165px;
                                                padding-right: 165px;
                                                padding-top: 15px;
                                                padding-bottom: 15px;
                                            }
                                            .confirm:hover{
                                                text-align: center;
                                                opacity: 85%;
                                                font-family: "Monserrat", sans-serif;
                                                font-weight: bold;
                                            }
                                            @media (max-width: 768px) {
                                                *{
                                                    font-size: 18px;
                                                }
                                                div.frame{
                                                    width: 475px;
                                                    margin: auto;
                                                }
                                            }
                                        </style>
                                    </head>
                                    <body>
                                        <div class = "frame">
                                            <div class = "frame-box">
                                                <div class="logo"></div><br><br>
                                                <div>
                                                    <div>Hello, <b>'.$firstname." ".$middlename." ".$lastname.'</b><br><br>
                                                        Request No.: <b>'.$requestNo.'</b><br><br>'.$text
                                                    .'</div>
                                                </div><br>
                                                Regards,<br>
                                                <br>BulSU-SC, OSCRS.
                                            </div>
                                        </div>
                                    </body>
                                </html>';
               
                if($mail->send()){
                    $isSent = true;
                }
            } catch (Exception $exception) {
                $isSent = false;
                echo $exception;
            }
            return $isSent;
        }
        
        function emailNotify1($clearanceNo, $lastname, $firstname, $middlename, $email, $text){
             
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.titan.email';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'no-reply@oscrs-bulsusc.com';
            $mail->Password   = 'pvakabjcspuplv#1';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $isSent = false;
            try {
                //Recipients
                $mail->setFrom('no-reply@oscrs-bulsusc.com', 'BulSU-SC OSCRS');
                $mail->addAddress($email, $firstname.' '.$lastname);
        
                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Request Notification.';
                $mail->Body  = '<html>
                                    <head>
                                        <style type = "text/css">
                                            *{
                                                font-size: 16px;
                                            }
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
                                            .confirm{
                                                color: white;
                                                background-color: maroon;
                                                text-align: center;
                                                font-family: "Monserrat", sans-serif;
                                                font-size: 16px;
                                                border: 1px solid rgba(0, 0, 0, 0.125);
                                                border-radius: 0.25rem;
                                                font-weight: bold;
                                                text-decoration: none;
                                                padding-left: 165px;
                                                padding-right: 165px;
                                                padding-top: 15px;
                                                padding-bottom: 15px;
                                            }
                                            .confirm:hover{
                                                text-align: center;
                                                opacity: 85%;
                                                font-family: "Monserrat", sans-serif;
                                                font-weight: bold;
                                            }
                                            @media (max-width: 768px) {
                                                *{
                                                    font-size: 18px;
                                                }
                                                div.frame{
                                                    width: 475px;
                                                    margin: auto;
                                                }
                                            }
                                        </style>
                                    </head>
                                    <body>
                                        <div class = "frame">
                                            <div class = "frame-box">
                                                <div class="logo"></div><br><br>
                                                <div>
                                                    <div>Hello, <b>'.$firstname." ".$middlename." ".$lastname.'</b><br><br>
                                                        Clearance No.: <b>'.$clearanceNo.'</b><br><br>'.$text
                                                    .'</div>
                                                </div><br>
                                                Regards,<br>
                                                <br>BulSU-SC, OSCRS.
                                            </div>
                                        </div>
                                    </body>
                                </html>';
               
                if($mail->send()){
                    $isSent = true;
                }
            } catch (Exception $exception) {
                $isSent = false;
                echo $exception;
            }
            return $isSent;
        }
?>