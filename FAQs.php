<?php
    require_once('database.php');
    session_start();
    unset($_SESSION['request_id']);
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
                    <a class="navbar-brand" href="#"><img src="assets/img/BSU-w.png" style= "height: 40px;" alt="OSCRS-Logo" /></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                        Menu
                        <i class="fas fa-bars ms-1"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarResponsive">
                        <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                            <li class="nav-item"><a class="nav-link" href="https://oscrs-bulsusc.com">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="https://oscrs-bulsusc.com/#contact">Contact Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="https://oscrs-bulsusc.com/FAQs">FAQ's</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <main>
    
            <section class="page-section bg-light">
                <div class="col-12">
                    <div class="card-body " >
                        <h1 style= "margin-left: 20px;">Frequesntly Ask Questions (FAQ's):</h1>
                    </div>
                </div>
                <div class="faq-one">
                    <h1 class="faq-page">What credentials am I able to request here?</h1>
                    <div class="faq-body">
                        <div style="padding: 30px;">
                            <div class="container">
                                <div class="timeline-body">
                                    <h5 class="text-muted" >Alumni can request the following credentials:</h5>
                                    <ul>
                                        <li class="text-muted" style="margin-left: 40px;">Transcript of Records (TOR)</li>
                                        <li class="text-muted" style="margin-left: 40px;">Certificate of Graduation</li>
                                        <li class="text-muted" style="margin-left: 40px;">Diploma</li>
                                    </ul>
                                     <h5 class=" text-muted" >Students can request the following credentials:</h5>
                                    <ul>
                                        <li class="text-muted" style="margin-left: 40px;">Certificate of Grades</li>
                                        <li class="text-muted" style="margin-left: 40px;">Certificate of Registration</li>
                                        <li class="text-muted" style="margin-left: 40px;">Certificate of Good Moral</li>
                                        <li class="text-muted" style="margin-left: 40px;">Honourable Dismissal</li>
                                        <li class="text-muted" style="margin-left: 40px;">Certificate of Non-issuance of ID</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="hr-line">
                <div class="faq-two">
                    <h1 class="faq-page">How to request?</h1>
                    <div class="faq-body">
                        <div style="padding: 30px;">
                            <div class="container">
                                <div class="text-center">
                                    <h3 class="section-subheading">Step by step procedure on how to request documents in Online Student Credential Request System.</h3>
                                </div>
                                
                                <ul class="timeline">
                                    <li>
                                        <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/one-symbols.gif" alt="..." /></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4>Step 1.</h4>
                                                <h4 class="subheading"></h4>
                                            </div>
                                            <div class="timeline-body"><p class="text-muted">Select Student or Alumni Request from the request menu. There will be a form displayed.</p></div>
                                        </div>
                                    </li>
                                    <li class="timeline-inverted">
                                        <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/two-symbols.gif" alt="..." /></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4>Step 2.</h4>
                                                <h4 class="subheading"></h4>
                                            </div>
                                            <div class="timeline-body"><p class="text-muted">Fill out the form with your personal information and select the documents you require. It is important to note that your personal information must be valid and correct in order for your request to be submitted.</p></div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/three-symbols.gif" alt="..." /></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4>Step 3.</h4>
                                                <h4 class="subheading"></h4>
                                            </div>
                                            <div class="timeline-body"><p class="text-muted">Please submit the form. You will see a notification that your request has been submitted, as well as an email verification sent to your email address.</p></div>
                                        </div>
                                    </li>
                                    <li class="timeline-inverted">
                                        <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/four-symbols.gif" alt="..." /></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4>Step 4.</h4>
                                                <h4 class="subheading"></h4>
                                            </div>
                                            <div class="timeline-body"><p class="text-muted">Check your inbox for an email from us and confirm your email address. The request number, which can be used to track your request, will be displayed.</p></div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/five-symbols.gif" alt="..." /></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4>Step 5.</h4>
                                                <h4 class="subheading"></h4>
                                            </div>
                                            <div class="timeline-body"><p class="text-muted">Keep track of your request. After you confirm the request, you will see a "Track Request" button. You can click it, enter your request number, and see the status of your request. Another option is to go to the home page and select Tracking Request from the request menu.</p></div>
                                        </div>
                                    </li>
                                    <li class="timeline-inverted">
                                        <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/six-symbols.gif" alt="..." /></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4>Step 6.</h4>
                                                <h4 class="subheading"></h4>
                                            </div>
                                            <div class="timeline-body"><p class="text-muted">Check your email inbox for any updates for your requested documents.</p></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="hr-line">
                <div class="faq-three">
                    <h1 class="faq-page">When will I be able to receive the credentials I've requested?</h1>
                    <div class="faq-body">
                        <div style="padding: 30px;">
                            <div class="container">
                                <h2 class="text-muted" >Requested Documents Processing Time:</h2><br>
                                <div class="timeline-body">
                                    <h5 class="text-muted" >Within 3 to 7 working days, the following credentials can be processed:</h5>
                                    <ul>
                                        <li class="text-muted" style="margin-left: 40px;">Certificate of Grades</li>
                                        <li class="text-muted" style="margin-left: 40px;">Certificate of Registration</li>
                                        <li class="text-muted" style="margin-left: 40px;">Certificate of Good Moral</li>
                                        <li class="text-muted" style="margin-left: 40px;">Honourable Dismissal</li>
                                        <li class="text-muted" style="margin-left: 40px;">Certificate of Non-issuance of ID</li>
                                    </ul>
                                    <h5 class=" text-muted">Within 3 to 5 weeks, the following credentials can be processed:</h5>
                                    <ul>
                                        <li class="text-muted" style="margin-left: 40px;">Transcript of Records (TOR)</li>
                                        <li class="text-muted" style="margin-left: 40px;">Certificate of Graduation</li>
                                        <li class="text-muted" style="margin-left: 40px;">Diploma</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="hr-line">
                <div class="faq-four">
                    <h1 class="faq-page">Is it possible to pay for my credential request online?</h1>
                    <div class="faq-body">
                        <div style="padding: 30px;">
                            <div class="container">
                                <div class="timeline-body text-muted">
                                   As of now, there is no online payment transaction for the Online Student Credential Request System. However, if you have a payment to settle for your requested document, you can inquire in the system's contact us section.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
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
        <script>
            var faq = document.getElementsByClassName("faq-page");
            var i;
            
            for (i = 0; i < faq.length; i++) {
                faq[i].addEventListener("click", function () {
                    this.classList.toggle("active");
            
                    var body = this.nextElementSibling;
                    if (body.style.display === "block") {
                        body.style.display = "none";
                    } else {
                        body.style.display = "block";
                    }
                });
            }
        </script>
    </body>
</html>