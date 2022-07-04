<?php
    require_once('assets/phpMailer/Exception.php');
    require_once('assets/phpMailer/PHPMailer.php');
    require_once('assets/phpMailer/SMTP.php');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = TRUE;
    $mail->SMTPSecure = "ssl";
    $mail->Port     = 465;  
    $mail->Username = "no-reply@oscrs-bulsusc.com";
    $mail->Password = "pvakabjcspuplv#1";
    $mail->Host     = "smtp.titan.email";
    $mail->Mailer   = "smtp";
    $mail->setFrom('no-reply@oscrs-bulsusc.com', 'BulSU-SC OSCRS');
    $mail->addAddress($_POST['email'], $_POST['firstname'].' '.$_POST['lastname']);
    
    $mail->isHTML(true);
    $mail->Subject = 'We attached to this message your Requested Documents.';
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
                                    <div>Hello, <b>'.$_POST['firstname'].' '.$_POST['lastname'].'</b><br><br>
                                        Request No.: <b>'.$_POST['requestNo'].'</b><br><br>
                                        Please click the button for confirmation of your request.
                                    </div><br><br>
                                </div><br><br>
                                Regards,
                                <br>BulSU-SC, OSCRS.
                            </div>
                        </div>
                    </body>
                </html>';
    foreach ($_FILES["attachment"]["name"] as $k => $v) {
        $mail->AddAttachment( $_FILES["attachment"]["tmp_name"][$k], $_FILES["attachment"]["name"][$k] );
    }
    
    $mail->IsHTML(true);
    
    if(!$mail->Send()) {
    	$_SESSION['isSent'] = "Failed to sent attachment.";
    } else {
    	$_SESSION['isSent'] = "Attachment Successfully Sent.";
    }	
?>