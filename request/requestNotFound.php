<?php
    $requestId = $_GET['request_id'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Request Not Found</title>
        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css"/>
        <link href="https://oscrs-bulsusc.com/assets/css/bootstrap.css" rel="stylesheet" />
        <link href="https://oscrs-bulsusc.com/assets/css/homepage.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="https://oscrs-bulsusc.com/assets/img/logo.png" />
    </head>
    <body>
        <div>
            <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav" style="background: maroon;">
                <div class="container-fluid">
                    <a class="navbar-brand" href="https://oscrs-bulsusc.com/"><img src="https://oscrs-bulsusc.com/assets/img/BSU-w.png" style= "height:40px; width: 250px" alt="OSCRS-Logo" /></a>
                    
                </div>
            </nav>
        </div>
        
        <header class="masthead">
            <div class="container">
                <br><br><br><br>
                <div class="masthead-heading text-uppercase text-muted">Request No.<?php echo " ".$requestId;?> Not Found</div>
                <div class="masthead-subheading text-muted"> You might not have request yet! You can request here, just click the button below.</div>
                <a class="btn btn-info btn-xl text-uppercase" href="https://oscrs-bulsusc.com/#request">Request Now</a>
                <br><br><br>
            </div>
        </header>

        <footer class="footer py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div style="align-items: center;">Copyright &copy; Bulacan State University - Sarmiento Campus 2022</div>
                </div>
            </div>
        </footer>
    </body>
</html>