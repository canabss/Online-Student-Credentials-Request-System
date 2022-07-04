
<?php function sidebar($isActive){?>
    <nav id="sidebar"  style="margin-top: 50px;">
        <div class="sidebar-header">
            <?php if(isset($_SESSION['picture'])){?>
                <img src="data:image/image/jpg:charset=utf8;base64,<?php echo base64_encode($_SESSION['picture']);?>" class="rounded mx-auto d-block" id="profile-pic"></img>
            <?php } else{?>
                <img src="../assets/img/default.jpeg" class="rounded mx-auto d-block" id="profile-pic"></img>
            <?php }?>
            <div style="margin-top:15px;" class="text-center"><h5><?php echo $_SESSION['firstname']." ".$_SESSION['lastname']?></h5></div>    
        </div>
        <ul class="list-unstyled "><br/>
            <li class="<?php echo $isActive != 0 ? '': 'active'?>"><a href="dashboard?id=<?php echo $_SESSION['user_id']?>"><i class="nav-icon fas fa-clipboard-list"></i> Dashboard</a></li>
            <li class="<?php echo $isActive != 1 ? '': 'active'?>"><a href="profile?id=<?php echo $_SESSION['user_id']?>"><i class="nav-icon fas fa-user"></i> Profile</a></li>
            <li class="<?php echo $isActive != 2 ? '': 'active'?>"><a href="clearances?id=<?php echo $_SESSION['user_id']?>"><i class="nav-icon fas fa-list-alt"></i> Clearances</a></li>
            <?php if($_SESSION['role'] == "5"):?>
                <li class="<?php echo $isActive != 5 ? '': 'active'?>"><a href="facultyMembers?id=<?php echo $_SESSION['user_id']?>"><i class="nav-icon fas fa-user-graduate"></i> Faculty Members</a></li>
            <?php endif;?>
            <?php if($_SESSION['role'] != "5" && $_SESSION['role'] != "6"):?>
                <li class="<?php echo $isActive != 6 ? '': 'active'?>"><a href="personnels?id=<?php echo $_SESSION['user_id']?>"><i class="nav-icon fas fa-user-friends"></i> Personnels</a></li>
            <?php endif;?>
             <?php if($_SESSION['role'] != "6"):?>
                <li class="<?php echo $isActive != 4 ? '': 'active'?>"><a href="logs?id=<?php echo $_SESSION['user_id']?>"><i class="nav-icon fas fa-check-circle"></i> User Logs</a></li>
             <?php endif;?>
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
                <a href="dashboard?id=<?php echo $_SESSION['user_id']?>"><img src="../assets/img/BSU-w.png" style= "height:40px; width: 250px; margin-left: 15px;" alt="OSCRS-Logo" /></a>
            </div>
            <div style="display: flex;">
                <form method="POST">
                    <button type="submit" style="background: maroon; border: none; color: white;" name="logout">Log out</button>
                </form>
            </div>
        </div>
    </nav>
<?php }?>
