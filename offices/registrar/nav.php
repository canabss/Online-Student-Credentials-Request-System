
<?php function sidebar($isActive){?>

    <nav id="sidebar">
        <div class="sidebar-header" style="margin-top: 50px;">
            <?php if(isset($_SESSION['picture'])){?>
                <img src="data:image/image/jpg:charset=utf8;base64,<?php echo base64_encode($_SESSION['picture']);?>" class="rounded mx-auto d-block" id="profile-pic"></img>
            <?php } else{?>
                <img src="../assets/img/default.jpeg" class="rounded mx-auto d-block" id="profile-pic"></img>
            <?php }?>
            <div style="margin-top:15px;" class="text-center"><h4><?php echo $_SESSION['firstname']." ".$_SESSION['lastname']?></h4></div>    
        </div>
        <ul class="list-unstyled " style="overflow: auto; "><br/>
            <li class="<?php echo $isActive != 0 ? '': 'active'?>"><a href="dashboard?id=<?php echo $_SESSION['user_id']?>"><i class="nav-icon fas fa-clipboard-list"></i> Dashboard</a></li>
            <li class="<?php echo $isActive != 1 ? '': 'active'?>"><a href="profile?id=<?php echo $_SESSION['user_id']?>"><i class="nav-icon fas fa-user"></i> Profile</a></li>
            <li class="<?php echo $isActive != 2 ? '': 'active'?>"><a href="messages?id=<?php echo $_SESSION['user_id']?>"><i class="nav-icon fas fa-envelope"></i> Messages</a></li>
            <li class="<?php echo $isActive != 3 ? '': 'active'?>"><a href="students?id=<?php echo $_SESSION['user_id']?>"><i class="nav-icon fas fa-users"></i> Students</a></li>
            <li>
                <!--<a href="pendingRequests?id=<?php //echo $_SESSION['user_id']?>"><i class="nav-icon fas fa-list-alt"></i> Requests</a>-->
                <button class="dropdown-btn <?php echo $isActive != 4 ? '': 'dropdown-active'?><?php echo $isActive != 5 ? '': 'dropdown-active'?><?php echo $isActive != 6 ? '': 'dropdown-active'?>"><i class="nav-icon fas fa-list-alt"></i> Requests List 
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-container" style = " display: <?php echo $isActive != 4 ? '': 'block'?><?php echo $isActive != 5 ? '': 'block'?><?php echo $isActive != 6 ? '': 'block'?>;">
                    <a style = "color: <?php echo $isActive != 4 ? '': 'orange'?>" href="pendingRequests?id=<?php echo $_SESSION['user_id']?>">Pending Requests</a>
                    <a style = "color: <?php echo $isActive != 5 ? '': 'orange'?>" href="processingRequests?id=<?php echo $_SESSION['user_id']?>">Processing Requests</a>
                    <a style = "color: <?php echo $isActive != 6 ? '': 'orange'?>" href="requestArchive?id=<?php echo $_SESSION['user_id']?>">Request Archive</a>
                </div>
            </li>
            <li class="<?php echo $isActive != 7 ? '': 'active'?>"><a href="personnels?id=<?php echo $_SESSION['user_id']?>"><i class="nav-icon fas fa-user-friends"></i> Registrar Personnels</a></li>
            <li class="<?php echo $isActive != 8 ? '': 'active'?>"><a href="clearanceSignatories?id=<?php echo $_SESSION['user_id']?>"><i class="nav-icon fas fa-user-friends"></i> Clearance Signatories</a></li>
            <li class="<?php echo $isActive != 9 ? '': 'active'?>"><a href="logs?id=<?php echo $_SESSION['user_id']?>"><i class="nav-icon fas fa-check-circle"></i> User Logs</a></li>
        </ul>
    </nav>
<?php }?>

<?php function head(){?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-maroon fixed-top">
        <div class="container-fluid">
            <div>
                <button type="button" id="sidebarCollapse" class="btn btn-info" style="background-color: maroon;">
                    <i id="arrow" class="fas fa-bars"></i>
                </button>
                <a href="dashboard?id=<?php echo $_SESSION['user_id']?>"><img src="../assets/img/BSU-w.png" style= "height:55px; margin-left: 15px;" alt="OSCRS-Logo" /></a>
            </div>
            <div style="display: flex;">
                <form method="POST">
                    <button type="submit" style="background: maroon; border: none; color: white;" name="logout">Log out</button>
                </form>
            </div>
        </div>
    </nav>
<?php }?>
