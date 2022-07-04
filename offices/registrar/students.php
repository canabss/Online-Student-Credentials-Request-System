<?php 
    require_once('../database.php');  
    require_once('../validations.php');
    require_once('../functions.php');
    require_once('nav.php');
    $course =''; $year = ''; $error = [];
    session_start();
    $myInfo = [];
    auth();

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])){ 
        $db = database();
        $studentNo = $db->real_escape_string($_POST['studentNo']);
        $lastName = $db->real_escape_string(ucFirst($_POST['lastName']));
        $firstName = $db->real_escape_string(ucFirst($_POST['firstName']));
        $middleName = $db->real_escape_string(ucFirst($_POST['middleName']));
        $birthday = $db->real_escape_string($_POST['birthday']);
        $contactNo = $db->real_escape_string($_POST['contact']);
        $course = $db->real_escape_string(ucFirst($_POST['course']));
        $year = $db->real_escape_string($_POST['year']);
        $section = $db->real_escape_string($_POST['section']);

        $error = studentNoValidation($studentNo, $error);
        $error = lastnameValidation($lastName, $error);
        $error = firstnameValidation($firstName, $error);
        if(empty($middleName)){
            $middleName = middlenameIsEmpty($middleName);
        }
        else{
            $error = middlenameValidation($middleName, $error);
        }
        birthdaySessionSet($birthday);
        if(empty($contactNo)){
            $contactNo = contactIsEmpty($contactNo);
        }
        else{
            $error = contactValidation($contactNo, $error);
        }
        courseSessionSet($course);
        yearSessionSet($year);
        sectionSessionSet($section);
        if(empty($error)){
            if(addStudent($studentNo, $lastName, $firstName, $middleName, $birthday, $contactNo, $course, $year, $section,'-----')){
                $_SESSION['add-accepted'] = true;
            }
            else{
                $error[] = "SQL Error";
                $_SESSION['add-accepted'] = false;
            }
        }
        else{
            $_SESSION['add-accepted'] = false;
        }
    }
    
    else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['close-add'])){ 
        clearSessions();
    }

    else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])){ 
        $db = database();
        $studentNo = $db->real_escape_string($_POST['studentNo1']);
        $lastName = $db->real_escape_string(ucFirst($_POST['lastName1']));
        $firstName = $db->real_escape_string(ucFirst($_POST['firstName1']));
        $middleName = $db->real_escape_string(ucFirst($_POST['middleName1']));
        $birthday = $db->real_escape_string($_POST['birthday1']);
        $contactNo = $db->real_escape_string($_POST['contact1']);
        $course = $db->real_escape_string(ucFirst($_POST['course1']));
        $year = $db->real_escape_string($_POST['year1']);
        $section = $db->real_escape_string($_POST['section1']);

        studentNoSessionSet($studentNo);
        $error = lastnameValidation($lastName, $error);
        $error = firstnameValidation($firstName, $error);
        if(empty($middleName)){
            $middleName = middlenameIsEmpty($middleName);
        }
        else{
            $error = middlenameValidation($middleName, $error);
        }
        birthdaySessionSet($birthday);
        if(empty($contactNo)){
            $contactNo = contactIsEmpty($contactNo);
        }
        else{
            $error = contactValidation($contactNo, $error);
        }
        courseSessionSet($course);
        yearSessionSet($year);
        sectionSessionSet($section);

        if(empty($error)){
            if(updateStudent($lastName, $firstName, $middleName, $birthday, $contactNo, $course, $year, $section, $studentNo)){
                $_SESSION['edit-accepted'] = true;
            }
            else{
                $error[] = "SQL Error";
                $_SESSION['edit-accepted'] = false;
            }
        }
        else{
            $_SESSION['edit-accepted'] = false;
        }
    }

    else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['close-edit'])){ 
        clearSessions();
    }

    else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])){
        logout();
    }
    
    else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['first'])){
        $db = database();
        $list = $_POST['select1'];
        $success = false;
        foreach($list as  $data){
            $sql = $db->query("UPDATE students SET year_level = '2nd' WHERE student_no = '$data'");
            if($sql){
                $success = true;
            }
            else{
                break;
            }
        }
        if(!$success){
            $_SESSION['update-year'] = false;
        }
        else{
            $_SESSION['update-year'] = true;
        }
        
    }
    else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['second'])){
        $db = database();
        $list = $_POST['select2'];
        $success = false;
        foreach($list as  $data){
            $sql = $db->query("UPDATE students SET year_level = '3rd' WHERE student_no = '$data'");
            if($sql){
                $success = true;
            }
            else{
                break;
            }
        }
        if(!$success){
            $_SESSION['update-year'] = false;
        }
        else{
            $_SESSION['update-year'] = true;
        }
        
    }
    else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['third'])){
        $db = database();
        $list = $_POST['select3'];
        $success = false;
        foreach($list as  $data){
            $sql = $db->query("UPDATE students SET year_level = '4th' WHERE student_no = '$data'");
            if($sql){
                $success = true;
            }
            else{
                break;
            }
        }
        if(!$success){
            $_SESSION['update-year'] = false;
        }
        else{
            $_SESSION['update-year'] = true;
        }
        
    }
    else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forth'])){
        $db = database();
        $list = $_POST['select4'];
        $success = false;
        
        $date = new DateTime("now", new DateTimeZone('Asia/Manila'));
        $yearNow = $date->format("Y");
        
        foreach($list as  $data){
            $sql = $db->query("UPDATE students SET year_level = 'Graduate', year_graduated = '$yearNow' WHERE student_no = '$data'");
            if($sql){
                $success = true;
            }
            else{
                break;
            }
        }
        if(!$success){
            $_SESSION['update-year'] = false;
        }
        else{
            $_SESSION['update-year'] = true;
        }
        
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Student List</title>
        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/admin.css" rel="stylesheet" />
        <link href="../assets/css/bootstrap.css" rel="stylesheet" />
        <link href="../assets/css/jquery.dataTables.css" rel="stylesheet" />
        <link href="../assets/css/responsive.dataTables.min.css" rel="stylesheet" />
        <link href="../assets/css/rowReorder.dataTables.min.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="../assets/img/logo.png" />
    </head>
    <body id='students'>
        <div class="wrapper">
            <?php sidebar(3);?>
           
            <div id="content" >
                <?php head();?><br>
                <section class="content" style="margin-top: 50px;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-body ">
                                    <div class="col-lg-12 justify-content-between" style="display:flex;">
                                        <h1>Students</h1>
                                        <button class="btn btn-info btn-xl text-uppercase open-add" name="add-buttton"><i class="fa fa-plus"></i> Add Student</button>
                                    </div>
                                    <br>
                                    <div class="tab">
                                        <button class="tablinks" onclick="openTable(event, '1st-year')" id="defaultOpen">1st Year</button>
                                        <button class="tablinks" onclick="openTable(event, '2nd-year')">2nd Year</button>
                                        <button class="tablinks" onclick="openTable(event, '3rd-year')">3rd Year</button>
                                        <button class="tablinks" onclick="openTable(event, '4th-year')">4th Year</button>
                                        <button class="tablinks" onclick="openTable(event, 'alumni')">Alumni</button>
                                    </div>

                                    <div id="1st-year" class="tabcontent">
                                        <div class="col-lg-12">
                                            <div class="modal-footer justify-content-between" style="background-color:white; border:none;">
                                                <div class="filter" style="color:black;">
                                                    <select class="filter-course" id="filters1" name="filters1">
                                                        <option value="">Courses</option>
                                                        <option value="Bachelor of Science in Information Technology">Bachelor of Science in Information Technology</option>
                                                        <br>
                                                        <optgroup label="Bachelor in Industrial Technology">
                                                            <option value="Bachelor in Industrial Technology Major in Computer Technology">Bachelor in Industrial Technology Major in Computer Technology</option>
                                                            <option value="Bachelor in Industrial Technology Major in Electronics">Bachelor in Industrial Technology Major in Electronics</option>
                                                            <option value="Bachelor in Industrial Technology Major in Drafting">Bachelor in Industrial Technology Major in Drafting</option>
                                                            <option value="Bachelor in Industrial Technology Major in Foods">Bachelor in Industrial Technology Major in Foods</option>
                                                        </optgroup>
                                                        <br>
                                                        <option value="BEED">Bachelor in Elementary Education General Education</option>
                                                        <br>
                                                        <optgroup label="Bachelor of Secondary Education">
                                                            <option value="Bachelor of Secondary Education Major in English">Bachelor of Secondary Education Major in English</option>
                                                            <option value="Bachelor of Secondary Education Major in Mathematics">Bachelor of Secondary Education Major in Mathematics</option>
                                                            <option value="Bachelor of Secondary Education Major in Science">Bachelor of Secondary Education Major in Science</option>
                                                            <option value="Bachelor of Secondary Education Major in Physical Science">Bachelor of Secondary Education Major in Physical Science</option>
                                                            <option value="Bachelor of Secondary Education Major in Filipino">Bachelor of Secondary Education Major in Filipino</option>
                                                            <option value="Bachelor of Secondary Education Major in Social Studies">Bachelor of Secondary Education Major in Social Studies</option>
                                                        </optgroup>
                                                        <br>
                                                        <optgroup label="Bachelor of Science in Business Administration">
                                                            <option value="Bachelor of Science in Business Administration Major in Financial Management">Bachelor of Science in Business Administration Major in Financial Management </option>
                                                            <option value="Bachelor of Science in Business Administration Major in Marketing Management">Bachelor of Science in Business Administration Major in Marketing Management</option>
                                                            <option value="Bachelor of Science in Business Administration Major in Business Economics">Bachelor of Science in Business Administration Major in Business Economics</option>
                                                        </optgroup>
                                                        <option value="Bachelor of Science in Entrepeneurship">Bachelor of Science in Entrepeneurship</option>
                                                        <option value="Bachelor of Science in Hospitality Management">Bachelor of Science in Hospitality Management</option>
                                                        <option value="BSTM">Bachelor of Science in Hospitality Management</option>
                                                    </select>
                                                </div>
                                                <div class="search">
                                                    <input class="input-search" id="search1" type="search" name="search1" placeholder="Search">
                                                </div>
                                            </div>
                                        </div>
                                        <form method="POST">
                                            <table id="table1" class="table table-bordered nowrap" style="width: 100%;">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th >#</th>
                                                        <th ><input type="checkbox" name="select-all1" id="select-all1">&nbsp; Action</th>
                                                        <th >Student No.</th>
                                                        <th>Name</th>
                                                        <th>Course</th>
                                                        <th>Academic Status</th>
                                                        <th>Section</th>
                                                        <th >Birthday</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php 
                                                    $students1[] = getStudents("1st");
                                                        /*if(!isset("submit-filter")){
                                                            $students[] = getStudents();
                                                        }
                                                        else{
                                                            if(empty($_POST["filters"])){
                                                                $students[] = getStudents();
                                                            }
                                                            else{
                                                                $students[] = getFilterStudents($_POST["filters"]);
                                                            }
                                                        }*/
                                                        $count = 1;
                                                    ?>
                                                    <?php foreach($students1 as $key) :?>
                                                        <?php foreach($key as $student) :?>
                                                            <?php if($student['middle_name']=="N/A") $student['middle_name']="";?>
                                                            <tr>
                                                                <td><?php echo $count;?></td>
                                                                <td>
                                                                    <input type="checkbox" name="select1[]" class="select-this1" value="<?php echo $student["student_no"];?>">&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    <button type = 'button' class='btn-edit btn-xs open-edit' name = 'btn-edit' id = 'btn-edit' value = '<?php echo $student['student_no']."/".$student["last_name"]."/".$student["first_name"]."/".$student["middle_name"]."/".$student["birthday"]."/".$student["contact_no"]."/".$student["course"]."/".$student["year_level"]."/".$student["section"];?>'>
                                                                        <i class='fas fa-edit'></i>
                                                                    </button>
                                                                </td>
                                                                <td><?php echo $student["student_no"];?></td>
                                                                <td><?php echo $student["first_name"]." ".$student["middle_name"]." ".$student["last_name"];?></td>
                                                                <td><?php echo $student["course"];?></td>
                                                                <td><?php echo $student["year_level"].' '.'Year';?></td>
                                                                <td><?php echo $student["section"];?></td>
                                                                <td><?php echo $student["birthday"];?></td>
                                                            </tr>
                                                            <?php $count++;?>	
                                                        <?php endforeach;?>
                                                    <?php endforeach;?>
                                                </tbody>
                                                <tfoot >
                                                    <tr>
                                                        <th></th>
                                                        <th>
                                                            <button type="submit" class="btn btn-info" name="first">
                                                                <i class="fa fa-level-up-alt"></i> Year-Level
                                                            </button>
                                                        </th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                </tfoot>
                                            </table>
                                        </form>
                                    </div>

                                    <div id="2nd-year" class="tabcontent">
                                        <div class="col-lg-12">
                                            <div class="modal-footer justify-content-between" style="background-color:white; border:none;">
                                                <div class="filter" style="color:black;">
                                                    <select class="filter-course" id="filters2" name="filters2">
                                                        <option value="">Courses</option>
                                                        <option value="Bachelor of Science in Information Technology">Bachelor of Science in Information Technology</option>
                                                        <br>
                                                        <optgroup label="Bachelor in Industrial Technology">
                                                            <option value="Bachelor in Industrial Technology Major in Computer Technology">Bachelor in Industrial Technology Major in Computer Technology</option>
                                                            <option value="Bachelor in Industrial Technology Major in Electronics">Bachelor in Industrial Technology Major in Electronics</option>
                                                            <option value="Bachelor in Industrial Technology Major in Drafting">Bachelor in Industrial Technology Major in Drafting</option>
                                                            <option value="Bachelor in Industrial Technology Major in Foods">Bachelor in Industrial Technology Major in Foods</option>
                                                        </optgroup>
                                                        <br>
                                                        <option value="BEED">Bachelor in Elementary Education General Education</option>
                                                        <br>
                                                        <optgroup label="Bachelor of Secondary Education">
                                                            <option value="Bachelor of Secondary Education Major in English">Bachelor of Secondary Education Major in English</option>
                                                            <option value="Bachelor of Secondary Education Major in Mathematics">Bachelor of Secondary Education Major in Mathematics</option>
                                                            <option value="Bachelor of Secondary Education Major in Science">Bachelor of Secondary Education Major in Science</option>
                                                            <option value="Bachelor of Secondary Education Major in Physical Science">Bachelor of Secondary Education Major in Physical Science</option>
                                                            <option value="Bachelor of Secondary Education Major in Filipino">Bachelor of Secondary Education Major in Filipino</option>
                                                            <option value="Bachelor of Secondary Education Major in Social Studies">Bachelor of Secondary Education Major in Social Studies</option>
                                                        </optgroup>
                                                        <br>
                                                        <optgroup label="Bachelor of Science in Business Administration">
                                                            <option value="Bachelor of Science in Business Administration Major in Financial Management">Bachelor of Science in Business Administration Major in Financial Management </option>
                                                            <option value="Bachelor of Science in Business Administration Major in Marketing Management">Bachelor of Science in Business Administration Major in Marketing Management</option>
                                                            <option value="Bachelor of Science in Business Administration Major in Business Economics">Bachelor of Science in Business Administration Major in Business Economics</option>
                                                        </optgroup>
                                                        <option value="Bachelor of Science in Entrepeneurship">Bachelor of Science in Entrepeneurship</option>
                                                        <option value="Bachelor of Science in Hospitality Management">Bachelor of Science in Hospitality Management</option>
                                                        <option value="BSTM">Bachelor of Science in Hospitality Management</option>
                                                    </select>
                                                </div>
                                                <div class="search">
                                                    <input class="input-search" id="search2" type="search" name="search2" placeholder="Search">
                                                </div>
                                            </div>
                                        </div>
                                        <form method="POST">
                                            <table id="table2" class="table table-bordered nowrap" style="width: 100%;">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th >#</th>
                                                        <th ><input type="checkbox" name="select-all2" id="select-all2">&nbsp; Action</th>
                                                        <th >Student No.</th>
                                                        <th>Name</th>
                                                        <th>Course</th>
                                                        <th>Academic Status</th>
                                                        <th>Section</th>
                                                        <th >Birthday</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php 
                                                    $students2[] = getStudents("2nd");
                                                        /*if(!isset("submit-filter")){
                                                            $students[] = getStudents();
                                                        }
                                                        else{
                                                            if(empty($_POST["filters"])){
                                                                $students[] = getStudents();
                                                            }
                                                            else{
                                                                $students[] = getFilterStudents($_POST["filters"]);
                                                            }
                                                        }*/
                                                        $count = 1;
                                                    ?>
                                                    <?php foreach($students2 as $key) :?>
                                                        <?php foreach($key as $student) :?>
                                                            <?php if($student['middle_name']=="N/A") $student['middle_name']="";?>
                                                            <tr>
                                                                <td><?php echo $count;?></td>
                                                                <td>
                                                                    <input type="checkbox" name="select2[]" class="select-this2" value="<?php echo $student["student_no"];?>">&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    <button type = 'button' class='btn-edit btn-xs open-edit' name = 'btn-edit' id = 'btn-edit' value = '<?php echo $student['student_no']."/".$student["last_name"]."/".$student["first_name"]."/".$student["middle_name"]."/".$student["birthday"]."/".$student["contact_no"]."/".$student["course"]."/".$student["year_level"]."/".$student["section"];?>'>
                                                                        <i class='fas fa-edit'></i>
                                                                    </button>
                                                                </td>
                                                                <td><?php echo $student["student_no"];?></td>
                                                                <td><?php echo $student["first_name"]." ".$student["middle_name"]." ".$student["last_name"];?></td>
                                                                <td><?php echo $student["course"];?></td>
                                                                <td><?php echo $student["year_level"].' '.'Year';?></td>
                                                                <td><?php echo $student["section"];?></td>
                                                                <td><?php echo $student["birthday"];?></td>
                                                            </tr>
                                                            <?php $count++;?>	
                                                        <?php endforeach;?>
                                                    <?php endforeach;?>
                                                </tbody>
                                                <tfoot >
                                                    <tr>
                                                        <th></th>
                                                        <th>
                                                            <button type="submit" class="btn btn-info" name="second">
                                                                <i class="fa fa-level-up-alt"></i> Year-Level
                                                            </button>
                                                        </th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                </tfoot>
                                            </table>
                                        </form>
                                    </div>

                                    <div id="3rd-year" class="tabcontent">
                                        <div class="col-lg-12">
                                            <div class="modal-footer justify-content-between" style="background-color:white; border:none;">
                                                <div class="filter" style="color:black;">
                                                    <select class="filter-course" id="filters3" name="filters3">
                                                        <option value="">Courses</option>
                                                        <option value="Bachelor of Science in Information Technology">Bachelor of Science in Information Technology</option>
                                                        <br>
                                                        <optgroup label="Bachelor in Industrial Technology">
                                                            <option value="Bachelor in Industrial Technology Major in Computer Technology">Bachelor in Industrial Technology Major in Computer Technology</option>
                                                            <option value="Bachelor in Industrial Technology Major in Electronics">Bachelor in Industrial Technology Major in Electronics</option>
                                                            <option value="Bachelor in Industrial Technology Major in Drafting">Bachelor in Industrial Technology Major in Drafting</option>
                                                            <option value="Bachelor in Industrial Technology Major in Foods">Bachelor in Industrial Technology Major in Foods</option>
                                                        </optgroup>
                                                        <br>
                                                        <option value="BEED">Bachelor in Elementary Education General Education</option>
                                                        <br>
                                                        <optgroup label="Bachelor of Secondary Education">
                                                            <option value="Bachelor of Secondary Education Major in English">Bachelor of Secondary Education Major in English</option>
                                                            <option value="Bachelor of Secondary Education Major in Mathematics">Bachelor of Secondary Education Major in Mathematics</option>
                                                            <option value="Bachelor of Secondary Education Major in Science">Bachelor of Secondary Education Major in Science</option>
                                                            <option value="Bachelor of Secondary Education Major in Physical Science">Bachelor of Secondary Education Major in Physical Science</option>
                                                            <option value="Bachelor of Secondary Education Major in Filipino">Bachelor of Secondary Education Major in Filipino</option>
                                                            <option value="Bachelor of Secondary Education Major in Social Studies">Bachelor of Secondary Education Major in Social Studies</option>
                                                        </optgroup>
                                                        <br>
                                                        <optgroup label="Bachelor of Science in Business Administration">
                                                            <option value="Bachelor of Science in Business Administration Major in Financial Management">Bachelor of Science in Business Administration Major in Financial Management </option>
                                                            <option value="Bachelor of Science in Business Administration Major in Marketing Management">Bachelor of Science in Business Administration Major in Marketing Management</option>
                                                            <option value="Bachelor of Science in Business Administration Major in Business Economics">Bachelor of Science in Business Administration Major in Business Economics</option>
                                                        </optgroup>
                                                        <option value="Bachelor of Science in Entrepeneurship">Bachelor of Science in Entrepeneurship</option>
                                                        <option value="Bachelor of Science in Hospitality Management">Bachelor of Science in Hospitality Management</option>
                                                        <option value="BSTM">Bachelor of Science in Hospitality Management</option>
                                                    </select>
                                                </div>
                                                <div class="search">
                                                    <input class="input-search" id="search3" type="search" name="search3" placeholder="Search">
                                                </div>
                                            </div>
                                        </div>
                                        <form method="POST">
                                            <table id="table3" class="table table-bordered nowrap" style="width: 100%;">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th >#</th>
                                                        <th ><input type="checkbox" name="select-all3" id="select-all3">&nbsp; Action</th>
                                                        <th >Student No.</th>
                                                        <th>Name</th>
                                                        <th>Course</th>
                                                        <th>Academic Status</th>
                                                        <th>Section</th>
                                                        <th >Birthday</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php 
                                                    $students3[] = getStudents("3rd");
                                                        /*if(!isset("submit-filter")){
                                                            $students[] = getStudents();
                                                        }
                                                        else{
                                                            if(empty($_POST["filters"])){
                                                                $students[] = getStudents();
                                                            }
                                                            else{
                                                                $students[] = getFilterStudents($_POST["filters"]);
                                                            }
                                                        }*/
                                                        $count = 1;
                                                    ?>
                                                    <?php foreach($students3 as $key) :?>
                                                        <?php foreach($key as $student) :?>
                                                            <?php if($student['middle_name']=="N/A") $student['middle_name']="";?>
                                                            <tr>
                                                                <td><?php echo $count;?></td>
                                                                <td>
                                                                    <input type="checkbox" name="select3[]" class="select-this3" value="<?php echo $student["student_no"];?>">&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    <button type = 'button' class='btn-edit btn-xs open-edit' name = 'btn-edit' id = 'btn-edit' value = '<?php echo $student['student_no']."/".$student["last_name"]."/".$student["first_name"]."/".$student["middle_name"]."/".$student["birthday"]."/".$student["contact_no"]."/".$student["course"]."/".$student["year_level"]."/".$student["section"];?>'>
                                                                        <i class='fas fa-edit'></i>
                                                                    </button>
                                                                </td>
                                                                <td><?php echo $student["student_no"];?></td>
                                                                <td><?php echo $student["first_name"]." ".$student["middle_name"]." ".$student["last_name"];?></td>
                                                                <td><?php echo $student["course"];?></td>
                                                                <td><?php echo $student["year_level"].' '.'Year';?></td>
                                                                <td><?php echo $student["section"];?></td>
                                                                <td><?php echo $student["birthday"];?></td>
                                                            </tr>
                                                            <?php $count++;?>	
                                                        <?php endforeach;?>
                                                    <?php endforeach;?>
                                                </tbody>
                                                <tfoot >
                                                    <tr>
                                                        <th></th>
                                                        <th>
                                                            <button type="submit" class="btn btn-info" name="third">
                                                                <i class="fa fa-level-up-alt"></i> Year-Level
                                                            </button>
                                                        </th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                </tfoot>
                                            </table>
                                        </form>
                                    </div>

                                    <div id="4th-year" class="tabcontent">
                                        <div class="col-lg-12">
                                            <div class="modal-footer justify-content-between" style="background-color:white; border:none;">
                                                <div class="filter" style="color:black;">
                                                    <select class="filter-course" id="filters4" name="filters4">
                                                        <option value="">Courses</option>
                                                        <option value="Bachelor of Science in Information Technology">Bachelor of Science in Information Technology</option>
                                                        <br>
                                                        <optgroup label="Bachelor in Industrial Technology">
                                                            <option value="Bachelor in Industrial Technology Major in Computer Technology">Bachelor in Industrial Technology Major in Computer Technology</option>
                                                            <option value="Bachelor in Industrial Technology Major in Electronics">Bachelor in Industrial Technology Major in Electronics</option>
                                                            <option value="Bachelor in Industrial Technology Major in Drafting">Bachelor in Industrial Technology Major in Drafting</option>
                                                            <option value="Bachelor in Industrial Technology Major in Foods">Bachelor in Industrial Technology Major in Foods</option>
                                                        </optgroup>
                                                        <br>
                                                        <option value="BEED">Bachelor in Elementary Education General Education</option>
                                                        <br>
                                                        <optgroup label="Bachelor of Secondary Education">
                                                            <option value="Bachelor of Secondary Education Major in English">Bachelor of Secondary Education Major in English</option>
                                                            <option value="Bachelor of Secondary Education Major in Mathematics">Bachelor of Secondary Education Major in Mathematics</option>
                                                            <option value="Bachelor of Secondary Education Major in Science">Bachelor of Secondary Education Major in Science</option>
                                                            <option value="Bachelor of Secondary Education Major in Physical Science">Bachelor of Secondary Education Major in Physical Science</option>
                                                            <option value="Bachelor of Secondary Education Major in Filipino">Bachelor of Secondary Education Major in Filipino</option>
                                                            <option value="Bachelor of Secondary Education Major in Social Studies">Bachelor of Secondary Education Major in Social Studies</option>
                                                        </optgroup>
                                                        <br>
                                                        <optgroup label="Bachelor of Science in Business Administration">
                                                            <option value="Bachelor of Science in Business Administration Major in Financial Management">Bachelor of Science in Business Administration Major in Financial Management </option>
                                                            <option value="Bachelor of Science in Business Administration Major in Marketing Management">Bachelor of Science in Business Administration Major in Marketing Management</option>
                                                            <option value="Bachelor of Science in Business Administration Major in Business Economics">Bachelor of Science in Business Administration Major in Business Economics</option>
                                                        </optgroup>
                                                        <option value="Bachelor of Science in Entrepeneurship">Bachelor of Science in Entrepeneurship</option>
                                                        <option value="Bachelor of Science in Hospitality Management">Bachelor of Science in Hospitality Management</option>
                                                        <option value="BSTM">Bachelor of Science in Hospitality Management</option>
                                                    </select>
                                                </div>
                                                <div class="search">
                                                    <input class="input-search" id="search4" type="search" name="search4" placeholder="Search">
                                                </div>
                                            </div>
                                        </div>
                                        <form method="POST">
                                            <table id="table4" class="table table-bordered nowrap" style="width: 100%;">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th >#</th>
                                                        <th ><input type="checkbox" name="select-all4" id="select-all4">&nbsp; Action</th>
                                                        <th >Student No.</th>
                                                        <th>Name</th>
                                                        <th>Course</th>
                                                        <th>Academic Status</th>
                                                        <th>Section</th>
                                                        <th >Birthday</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php 
                                                    $students4[] = getStudents("4th");
                                                        /*if(!isset("submit-filter")){
                                                            $students[] = getStudents();
                                                        }
                                                        else{
                                                            if(empty($_POST["filters"])){
                                                                $students[] = getStudents();
                                                            }
                                                            else{
                                                                $students[] = getFilterStudents($_POST["filters"]);
                                                            }
                                                        }*/
                                                        $count = 1;
                                                    ?>
                                                    <?php foreach($students4 as $key) :?>
                                                        <?php foreach($key as $student) :?>
                                                            <?php if($student['middle_name']=="N/A") $student['middle_name']="";?>
                                                            <tr>
                                                                <td><?php echo $count;?></td>
                                                                <td>
                                                                    <input type="checkbox" name="select4[]" class="select-this4" value="<?php echo $student["student_no"];?>">&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    <button type = 'button' class='btn-edit btn-xs open-edit' name = 'btn-edit' id = 'btn-edit' value = '<?php echo $student['student_no']."/".$student["last_name"]."/".$student["first_name"]."/".$student["middle_name"]."/".$student["birthday"]."/".$student["contact_no"]."/".$student["course"]."/".$student["year_level"]."/".$student["section"];?>'>
                                                                        <i class='fas fa-edit'></i>
                                                                    </button>
                                                                </td>
                                                                <td><?php echo $student["student_no"];?></td>
                                                                <td><?php echo $student["first_name"]." ".$student["middle_name"]." ".$student["last_name"];?></td>
                                                                <td><?php echo $student["course"];?></td>
                                                                <td><?php echo $student["year_level"].' '.'Year';?></td>
                                                                <td><?php echo $student["section"];?></td>
                                                                <td><?php echo $student["birthday"];?></td>
                                                            </tr>
                                                            <?php $count++;?>	
                                                        <?php endforeach;?>
                                                    <?php endforeach;?>
                                                </tbody>
                                                <tfoot >
                                                    <tr>
                                                        <th></th>
                                                        <th>
                                                            <button type="submit" class="btn btn-info" name="forth">
                                                                <i class="fa fa-level-up-alt"></i> Year-Level
                                                            </button>
                                                        </th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                </tfoot>
                                            </table>
                                        </form>
                                    </div>

                                    <div id="alumni" class="tabcontent">
                                        <div class="col-lg-12">
                                            <div class="modal-footer justify-content-between" style="background-color:white; border:none;">
                                                <div class="filter" style="color:black;">
                                                    <select class="filter-course" id="filters5" name="filters5">
                                                        <option value="">Courses</option>
                                                        <option value="Bachelor of Science in Information Technology">Bachelor of Science in Information Technology</option>
                                                        <br>
                                                        <optgroup label="Bachelor in Industrial Technology">
                                                            <option value="Bachelor in Industrial Technology Major in Computer Technology">Bachelor in Industrial Technology Major in Computer Technology</option>
                                                            <option value="Bachelor in Industrial Technology Major in Electronics">Bachelor in Industrial Technology Major in Electronics</option>
                                                            <option value="Bachelor in Industrial Technology Major in Drafting">Bachelor in Industrial Technology Major in Drafting</option>
                                                            <option value="Bachelor in Industrial Technology Major in Foods">Bachelor in Industrial Technology Major in Foods</option>
                                                        </optgroup>
                                                        <br>
                                                        <option value="BEED">Bachelor in Elementary Education General Education</option>
                                                        <br>
                                                        <optgroup label="Bachelor of Secondary Education">
                                                            <option value="Bachelor of Secondary Education Major in English">Bachelor of Secondary Education Major in English</option>
                                                            <option value="Bachelor of Secondary Education Major in Mathematics">Bachelor of Secondary Education Major in Mathematics</option>
                                                            <option value="Bachelor of Secondary Education Major in Science">Bachelor of Secondary Education Major in Science</option>
                                                            <option value="Bachelor of Secondary Education Major in Physical Science">Bachelor of Secondary Education Major in Physical Science</option>
                                                            <option value="Bachelor of Secondary Education Major in Filipino">Bachelor of Secondary Education Major in Filipino</option>
                                                            <option value="Bachelor of Secondary Education Major in Social Studies">Bachelor of Secondary Education Major in Social Studies</option>
                                                        </optgroup>
                                                        <br>
                                                        <optgroup label="Bachelor of Science in Business Administration">
                                                            <option value="Bachelor of Science in Business Administration Major in Financial Management">Bachelor of Science in Business Administration Major in Financial Management </option>
                                                            <option value="Bachelor of Science in Business Administration Major in Marketing Management">Bachelor of Science in Business Administration Major in Marketing Management</option>
                                                            <option value="Bachelor of Science in Business Administration Major in Business Economics">Bachelor of Science in Business Administration Major in Business Economics</option>
                                                        </optgroup>
                                                        <option value="Bachelor of Science in Entrepeneurship">Bachelor of Science in Entrepeneurship</option>
                                                        <option value="Bachelor of Science in Hospitality Management">Bachelor of Science in Hospitality Management</option>
                                                        <option value="BSTM">Bachelor of Science in Hospitality Management</option>
                                                    </select>
                                                </div>
                                                <div class="search">
                                                    <input class="input-search" id="search5" type="search" name="search5" placeholder="Search">
                                                </div>
                                            </div>
                                        </div>
                                        <table id="table5" class="table table-bordered nowrap" style="width: 100%;">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th >#</th>
                                                    <th >Student No.</th>
                                                    <th>Name</th>
                                                    <th>Course</th>
                                                    <th>Academic Status</th>
                                                    <th >Birthday</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php 
                                                $alumni[] = getStudents("Graduate");
                                                    /*if(!isset("submit-filter")){
                                                        $students[] = getStudents();
                                                    }
                                                    else{
                                                        if(empty($_POST["filters"])){
                                                            $students[] = getStudents();
                                                        }
                                                        else{
                                                            $students[] = getFilterStudents($_POST["filters"]);
                                                        }
                                                    }*/
                                                    $count = 1;
                                                ?>
                                                <?php foreach($alumni as $key) :?>
                                                    <?php foreach($key as $alumnus) :?>
                                                        <?php if($alumnus['middle_name']=="N/A") $alumnus['middle_name']="";?>
                                                        <tr>
                                                            <td><?php echo $count;?></td>
                                                            <td><?php echo $alumnus["student_no"];?></td>
                                                            <td><?php echo $alumnus["first_name"]." ".$alumnus["middle_name"]." ".$alumnus["last_name"];?></td>
                                                            <td><?php echo $alumnus["course"];?></td>
                                                            <td><?php echo $alumnus["year_level"];?></td>
                                                            <td><?php echo $alumnus["birthday"];?></td>
                                                        </tr>
                                                        <?php $count++;?>	
                                                    <?php endforeach;?>
                                                <?php endforeach;?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="footer py-4">
                    <div class="container ">
                        <div class="row align-items-center">
                            <div style="align-items: center;">Copyright &copy; Bulacan State University - Sarmiento Campus 2022</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="../assets/js/jquery-3.3.1.js"></script>
        <script src="../assets/js/jquery-scripts.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/jquery.dataTables.js"></script>
        <script src="../assets/js/dataTables.responsive.min.js"></script>
        <script src="../assets/js/dataTables.rowReorder.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
        <script>
            function openTable(evt, tableName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }

                tablinks = document.getElementsByClassName("tablinks");

                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(tableName).style.display = "block";
                evt.currentTarget.className += " active";
            }

                document.getElementById("defaultOpen").click();

            $(document).ready(function () {
                var table= $("#table1").DataTable({
                    columnDefs:[
                        {orderable: false, targets: "_all"}
                    ],
                    responsive: true,
                    order:[[0, 'asc']]
                });
                $("#search1").on("keyup", function(){
                    table.search(this.value).draw();
                });
                $("#filters1").on("change", function(){
                    table.search(this.value).draw();
                });
            });

            $(document).ready(function () {
                var table= $("#table2").DataTable({
                    columnDefs:[
                        {orderable: false, targets: "_all"}
                    ],
                    responsive: true,
                    order:[[0, 'asc']]
                });
                $("#search2").on("keyup", function(){
                    table.search(this.value).draw();
                });
                $("#filters2").on("change", function(){
                    table.search(this.value).draw();
                });
            });

            $(document).ready(function () {
                var table= $("#table3").DataTable({
                    columnDefs:[
                        {orderable: false, targets: "_all"}
                    ],
                    responsive: true,
                    order:[[0, 'asc']]
                });
                $("#search3").on("keyup", function(){
                    table.search(this.value).draw();
                });
                $("#filters3").on("change", function(){
                    table.search(this.value).draw();
                });
            });

            $(document).ready(function () {
                var table= $("#table4").DataTable({
                    columnDefs:[
                        {orderable: false, targets: "_all"}
                    ],
                    responsive: true,
                    order:[[0, 'asc']]
                });
                $("#search4").on("keyup", function(){
                    table.search(this.value).draw();
                });
                $("#filters4").on("change", function(){
                    table.search(this.value).draw();
                });
            });

            $(document).ready(function () {
                var table= $("#table5").DataTable({
                    columnDefs:[
                        {orderable: false, targets: "_all"}
                    ],
                    responsive: true,
                    order:[[0, 'asc']]
                });
                $("#search5").on("keyup", function(){
                    table.search(this.value).draw();
                });
                $("#filters5").on("change", function(){
                    table.search(this.value).draw();
                });
            });

            $('#select-all1').change(function(){
                if($(this).is(":checked")){
                    $(".select-this1").each(function(){
                        this.checked = true;
                    });
                }
                else{
                    $(".select-this1").each(function(){
                        this.checked = false;
                    });
                }
            });

            $('#select-all2').change(function(){
                if($(this).is(":checked")){
                    $(".select-this2").each(function(){
                        this.checked = true;
                    });
                }
                else{
                    $(".select-this2").each(function(){
                        this.checked = false;
                    });
                }
            });

            $('#select-all3').change(function(){
                if($(this).is(":checked")){
                    $(".select-this3").each(function(){
                        this.checked = true;
                    });
                }
                else{
                    $(".select-this3").each(function(){
                        this.checked = false;
                    });
                }
            });

            $('#select-all4').change(function(){
                if($(this).is(":checked")){
                    $(".select-this4").each(function(){
                        this.checked = true;
                    });
                }
                else{
                    $(".select-this4").each(function(){
                        this.checked = false;
                    });
                }
            });

            $('#select-all5').change(function(){
                if($(this).is(":checked")){
                    $(".select-this5").each(function(){
                        this.checked = true;
                    });
                }
                else{
                    $(".select-this5").each(function(){
                        this.checked = false;
                    });
                }
            });
        </script>
        <div class="modal fade" id="add">
            <div class="container">
                <div class="modal-dialog modal-md">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Student</h4>
                            <form method="POST" action="">
                                <button type="submit" class="close-add" name="close-add" id="close-add">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </form>
                        </div>
                        <form method="POST" id="add-form">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <label class = "form-label" for="studentno">Student No.: *</label>
                                        <input class = "form-control" placeholder="Ex. 2018-123456" type="tel" pattern="[0-9]{4}[-]{1}[0-9]{6}" name="studentNo" id="studentNo" maxlength="11" value="<?php echo !isset($_SESSION['studentno']) ? '' : $_SESSION['studentno'] ?>" required/></br>
                                    </div>
                                    <div class="col-12">
                                        <label class = "form-label" for="lastName">Last Name: *</label>
                                        <input class = "form-control" type="text" placeholder="Ex. Dela Cruz" pattern = "[a-zA-Z ]+" name="lastName" id="lastName" value="<?php echo !isset($_SESSION['lastName']) ? '' : $_SESSION['lastName'] ?>" required/></br>
                                    </div>
                                    <div class="col-12">
                                        <label class = "form-label" for="firstName">First Name: *</label>
                                        <input class = "form-control" type="text" placeholder="Ex. Juan" pattern = "[a-zA-Z ]+" name="firstName" id="firstName" value="<?php echo !isset($_SESSION['firstName']) ? '' : $_SESSION['firstName'] ?>" required/></br>
                                    </div>
                                    <div class="col-12">
                                        <label class = "form-label" for="middleName">Middle Name:(Optional)</label>
                                        <input class = "form-control" type="text" placeholder="Ex. Ponce" pattern = "[a-zA-Z ]+" name="middleName" id="middleName" value="<?php echo !isset($_SESSION['middleName']) ? '' : ($_SESSION['middleName'] == 'N/A' ? '' :$_SESSION['middleName'])?>"/></br>
                                    </div>
                                    <div class="col-12">
                                        <label class = "form-label" for="birthday">Birthday: *</label>
                                        <input class = "form-control text-uppercase" type="date" max= "<?php $date = new DateTime("now", new DateTimeZone('Asia/Manila')); echo $date->format("Y-m-d");?>" name="birthday"  id="birthday" value="<?php echo !isset($_SESSION['birthday']) ? '' : $_SESSION['birthday'] ?>" required/></br>
                                    </div>
                                    <div class="col-12">
                                        <label class = "form-label" for="contact">Contact No.:(Optional)</label>
                                        <input class = "form-control" placeholder="Ex. 09123456789" type="tel" pattern = "[0-9]{4}[0-9]{3}[0-9]{4}" name="contact" id="contact" value="<?php echo !isset($_SESSION['contact']) ? '' : ($_SESSION['contact'] == 'N/A' ? '': $_SESSION['contact']) ?>"/></br>
                                    </div> 
                                    <div class="col-12">
                                        <label class = "form-label" for="course">Course: *</label><br/>
                                        <select name="course" id="course" class = "form-select" required>
                                            <option value="">Select</option>
                                    
                                            <optgroup label="Department of Industrial and Information Technology">
                                                <option value="Bachelor of Science in Information Technology" <?php if("$course" == "Bachelor of Science in Information Technology") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Information Technology</option>
                                                <option value="Bachelor in Industrial Technology Major in Computer Technology" <?php if("$course" == "Bachelor in Industrial Technology Major in Computer Technology") :?>selected = "selected"<?php endif;?>>Bachelor in Industrial Technology Major in Computer Technology</option>
                                                <option value="Bachelor in Industrial Technology Major in Electronics" <?php if("$course" == "Bachelor in Industrial Technology Major in Electronics") :?>selected = "selected"<?php endif;?>>Bachelor in Industrial Technology Major in Electronics</option>
                                                <option value="Bachelor in Industrial Technology Major in Drafting" <?php if("$course" == "Bachelor in Industrial Technology Major in Drafting") :?>selected = "selected"<?php endif;?>>Bachelor in Industrial Technology Major in Drafting</option>
                                                <option value="Bachelor in Industrial Technology Major in Foods" <?php if("$course" == "Bachelor in Industrial Technology Major in Foods") :?>selected = "selected"<?php endif;?>>Bachelor in Industrial Technology Major in Foods</option>
                                            </optgroup><br><br>
                                            
                                            <optgroup label="Department of Education">
                                                <option value="Bachelor in Elementary Education General Education">Bachelor in Elementary Education General Education</option>
                                                <option value="Bachelor of Secondary Education Major in English" <?php if("$course" == "Bachelor of Secondary Education Major in English") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in English</option>
                                                <option value="Bachelor of Secondary Education Major in Mathematics" <?php if("$course" == "Bachelor of Secondary Education Major in Mathematics") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in Mathematics</option>
                                                <option value="Bachelor of Secondary Education Major in Science" <?php if("$course" == "Bachelor of Secondary Education Major in Science") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in Science</option>
                                                <option value="Bachelor of Secondary Education Major in Physical Science" <?php if("$course" == "Bachelor of Secondary Education Major in Physical Science") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in Physical Science</option>
                                                <option value="Bachelor of Secondary Education Major in Filipino" <?php if("$course" == "Bachelor of Secondary Education Major in Filipino") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in Filipino</option>
                                                <option value="Bachelor of Secondary Education Major in Social Studies" <?php if("$course" == "Bachelor of Secondary Education Major in Social Studies") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in Social Studies</option>
                                            </optgroup><br><br>
                                            
                                            <optgroup label="Department of Business Administration">
                                                <option value="Bachelor of Science in Business Administration Major in General Business Administration" <?php if("$course" == "Bachelor of Science in Business Administration Major in General Business Administration") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Business Administration Major in General Business Administration</option>
                                                <option value="Bachelor of Science in Business Administration Major in Financial Management" <?php if("$course" == "Bachelor of Science in Business Administration Major in Financial Management") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Business Administration Major in Financial Management </option>
                                                <option value="Bachelor of Science in Business Administration Major in Marketing Management" <?php if("$course" == "Bachelor of Science in Business Administration Major in Marketing Management") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Business Administration Major in Marketing Management</option>
                                                <option value="Bachelor of Science in Business Administration Major in Business Economics" <?php if("$course" == "Bachelor of Science in Business Administration Major in Business Economics") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Business Administration Major in Business Economics</option>
                                                <option value="Bachelor of Science in Entrepeneurship" <?php if("$course" == "Bachelor of Science in Entrepeneurship") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Entrepeneurship</option>
                                            </optgroup><br><br>
                                            
                                            <optgroup label="Department of Hospital Management">
                                                <option value="Bachelor of Science in Hospitality Management" <?php if("$course" == "Bachelor of Science in Hospitality Management") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Hospitality Management</option>
                                                <option value="Bachelor of Science in Hotel Restaurant Management" <?php if("$course" == "Bachelor of Science in Hotel Restaurant Management") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Hotel Restaurant Management</option>
                                            </optgroup><br>
                                        </select><br>	
                                    </div>
                                    <div class="col-12">
                                        <label class = "form-label" for="year">Year: *</label><br/>
                                        <select name="year" id="year" class = "form-select" required>
                                            <option value="">Select</option>
                                            <option value="1st" <?php if("$year" == "1st") :?>selected = "selected"<?php endif;?>>1st</option>
                                            <option value="2nd" <?php if("$year" == "2nd") :?>selected = "selected"<?php endif;?>>2nd</option>
                                            <option value="3rd" <?php if("$year" == "3rd") :?>selected = "selected"<?php endif;?>>3rd</option>
                                            <option value="4th" <?php if("$year" == "4th") :?>selected = "selected"<?php endif;?>>4th</option>
                                        </select><br/>
                                    </div>
                                    <div class="col-12">
                                            <label class = "form-label" for="section">Section: *</label>
                                            <input class = "form-control" placeholder="Ex. 1A" type="text" name="section" id="section" value="<?php echo !isset($_SESSION['section']) ? '' : $_SESSION['section'] ?>" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="submit" class="btn btn-info mx-auto" name="add" ><i class='fa fa-plus'></i> ADD</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="edit">
            <div class="container">
                <div class="modal-dialog modal-md">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Student Information</h4>
                            <form method="POST" action="">
                                <button type="submit" class="close-edit" name="close-edit" id="close-edit">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </form>
                        </div>
                        <form method="POST" id="edit-form">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <input class = "form-control" placeholder="Ex. 2018-123456" type="hidden" name="studentNo1" id="studentNo1" maxlength="11" value="<?php echo !isset($_SESSION['studentno']) ? '' : $_SESSION['studentno'] ?>" required/>
                                    </div>
                                    <div class="col-12">
                                        <label class = "form-label" for="lastName">Last Name: *</label>
                                        <input class = "form-control" type="text" placeholder="Ex. Dela Cruz" pattern = "[a-zA-Z ]+" name="lastName1" id="lastName1" value="<?php echo !isset($_SESSION['lastName']) ? '' : $_SESSION['lastName'] ?>" required/></br>
                                    </div>
                                    <div class="col-12">
                                        <label class = "form-label" for="firstName">First Name: *</label>
                                        <input class = "form-control" type="text" placeholder="Ex. Juan" pattern = "[a-zA-Z ]+" name="firstName1" id="firstName1" value="<?php echo !isset($_SESSION['firstName']) ? '' : $_SESSION['firstName'] ?>" required/></br>
                                    </div>
                                    <div class="col-12">
                                        <label class = "form-label" for="middleName">Middle Name:(Optional)</label>
                                        <input class = "form-control" type="text" placeholder="Ex. Ponce" pattern = "[a-zA-Z ]+" name="middleName1" id="middleName1" value="<?php echo !isset($_SESSION['middleName']) ? '' : ($_SESSION['middleName'] == 'N/A' ? '' :$_SESSION['middleName'])?>"/></br>
                                    </div>
                                    <div class="col-12">
                                        <label class = "form-label" for="birthday">Birthday: *</label>
                                        <input class = "form-control text-uppercase" type="date" max= "<?php $date = new DateTime("now", new DateTimeZone('Asia/Manila')); echo $date->format("Y-m-d");?>" name="birthday1"  id="birthday1" value="<?php echo !isset($_SESSION['birthday']) ? '' : $_SESSION['birthday'] ?>" required></br>
                                    </div>
                                    <div class="col-12">
                                        <label class = "form-label" for="contact">Contact No.:(Optional)</label>
                                        <input class = "form-control" placeholder="Ex. 09123456789" type="tel" pattern = "[0-9]{4}[0-9]{3}[0-9]{4}" name="contact1" id="contact1" value="<?php echo !isset($_SESSION['contact']) ? '' : ($_SESSION['contact'] == 'N/A' ? '': $_SESSION['contact']) ?>"/></br>
                                    </div> 
                                    <div class="col-12">
                                    <label class = "form-label" for="course">Course: *</label><br/>
                                        <select name="course1" id="course1" class = "form-select" required>
                                            <option value="">Select</option>
                                    
                                            <optgroup label="Department of Industrial and Information Technology">
                                                <option value="Bachelor of Science in Information Technology" <?php if("$course" == "Bachelor of Science in Information Technology") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Information Technology</option>
                                                <option value="Bachelor in Industrial Technology Major in Computer Technology" <?php if("$course" == "Bachelor in Industrial Technology Major in Computer Technology") :?>selected = "selected"<?php endif;?>>Bachelor in Industrial Technology Major in Computer Technology</option>
                                                <option value="Bachelor in Industrial Technology Major in Electronics" <?php if("$course" == "Bachelor in Industrial Technology Major in Electronics") :?>selected = "selected"<?php endif;?>>Bachelor in Industrial Technology Major in Electronics</option>
                                                <option value="Bachelor in Industrial Technology Major in Drafting" <?php if("$course" == "Bachelor in Industrial Technology Major in Drafting") :?>selected = "selected"<?php endif;?>>Bachelor in Industrial Technology Major in Drafting</option>
                                                <option value="Bachelor in Industrial Technology Major in Foods" <?php if("$course" == "Bachelor in Industrial Technology Major in Foods") :?>selected = "selected"<?php endif;?>>Bachelor in Industrial Technology Major in Foods</option>
                                            </optgroup><br><br>
                                            
                                            <optgroup label="Department of Education">
                                                <option value="Bachelor in Elementary Education General Education">Bachelor in Elementary Education General Education</option>
                                                <option value="Bachelor of Secondary Education Major in English" <?php if("$course" == "Bachelor of Secondary Education Major in English") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in English</option>
                                                <option value="Bachelor of Secondary Education Major in Mathematics" <?php if("$course" == "Bachelor of Secondary Education Major in Mathematics") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in Mathematics</option>
                                                <option value="Bachelor of Secondary Education Major in Science" <?php if("$course" == "Bachelor of Secondary Education Major in Science") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in Science</option>
                                                <option value="Bachelor of Secondary Education Major in Physical Science" <?php if("$course" == "Bachelor of Secondary Education Major in Physical Science") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in Physical Science</option>
                                                <option value="Bachelor of Secondary Education Major in Filipino" <?php if("$course" == "Bachelor of Secondary Education Major in Filipino") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in Filipino</option>
                                                <option value="Bachelor of Secondary Education Major in Social Studies" <?php if("$course" == "Bachelor of Secondary Education Major in Social Studies") :?>selected = "selected"<?php endif;?>>Bachelor of Secondary Education Major in Social Studies</option>
                                            </optgroup><br><br>
                                            
                                            <optgroup label="Department of Business Administration">
                                                <option value="Bachelor of Science in Business Administration Major in General Business Administration" <?php if("$course" == "Bachelor of Science in Business Administration Major in General Business Administration") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Business Administration Major in General Business Administration</option>
                                                <option value="Bachelor of Science in Business Administration Major in Financial Management" <?php if("$course" == "Bachelor of Science in Business Administration Major in Financial Management") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Business Administration Major in Financial Management </option>
                                                <option value="Bachelor of Science in Business Administration Major in Marketing Management" <?php if("$course" == "Bachelor of Science in Business Administration Major in Marketing Management") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Business Administration Major in Marketing Management</option>
                                                <option value="Bachelor of Science in Business Administration Major in Business Economics" <?php if("$course" == "Bachelor of Science in Business Administration Major in Business Economics") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Business Administration Major in Business Economics</option>
                                                <option value="Bachelor of Science in Entrepeneurship" <?php if("$course" == "Bachelor of Science in Entrepeneurship") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Entrepeneurship</option>
                                            </optgroup><br><br>
                                            
                                            <optgroup label="Department of Hospital Management">
                                                <option value="Bachelor of Science in Hospitality Management" <?php if("$course" == "Bachelor of Science in Hospitality Management") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Hospitality Management</option>
                                                <option value="Bachelor of Science in Hotel Restaurant Management" <?php if("$course" == "Bachelor of Science in Hotel Restaurant Management") :?>selected = "selected"<?php endif;?>>Bachelor of Science in Hotel Restaurant Management</option>
                                            </optgroup><br>
                                        </select><br/>	
                                    </div>
                                    <div class="col-12">
                                        <label class = "form-label" for="year">Year: *</label><br/>
                                        <select name="year1" id="year1" class = "form-select" >
                                            <option value="">Select</option>
                                            <option value="1st" <?php if("$year" == "1st") :?>selected = "selected"<?php endif;?>>1st</option>
                                            <option value="2nd" <?php if("$year" == "2nd") :?>selected = "selected"<?php endif;?>>2nd</option>
                                            <option value="3rd" <?php if("$year" == "3rd") :?>selected = "selected"<?php endif;?>>3rd</option>
                                            <option value="4th" <?php if("$year" == "4th") :?>selected = "selected"<?php endif;?>>4th</option>
                                        </select><br/>
                                    </div>
                                    <div class="col-12">
                                            <label class = "form-label" for="section1">Section: </label>
                                            <input class = "form-control" placeholder="Ex. 1A" type="text" name="section1" id="section1" value="<?php echo !isset($_SESSION['section']) ? '' : $_SESSION['section'] ?>" /></br>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="submit" class="btn btn-info mx-auto" name="edit" ><i class='fa fa-check'></i> Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php if(isset($_SESSION['add-accepted'])) :?>
            <?php if($_SESSION['add-accepted'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Successfully Added', 
                        icon:'success', 
                        confirmButtonColor: 'maroon',
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('students.php?id=<?php echo $_SESSION['user_id']?>');
                        }
                    });
                </script>
                <?php 
                    clearSessions();
                ?>
            <?php endif;?>
        <?php endif; ?>

        <?php if(isset($_SESSION['add-accepted'])) :?>
            <?php if($_SESSION['add-accepted'] == false) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Failed to add student',
                        html: '<?php foreach($error as $er){ echo "* ".$er."<br/>"; }?>', 
                        icon:'error', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            $(document).ready(function () {
                                $('#add').modal('show');
                            });   
                        }
                    });
                </script>
                <?php unset($_SESSION['add-accepted']);?>
            <?php endif;?>
        <?php endif;?>

        <?php if(isset($_SESSION['edit-accepted'])) :?>
            <?php if($_SESSION['edit-accepted'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Successfully Updated student information', 
                        icon:'success', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('students.php?id=<?php echo $_SESSION['user_id']?>');
                        }
                    });
                </script>
                <?php 
                    clearSessions();
                ?>
            <?php endif;?>
        <?php endif; ?>

        <?php if(isset($_SESSION['edit-accepted'])) :?>
            <?php if($_SESSION['edit-accepted'] == false) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Failed to update student information',
                        html: '<?php foreach($error as $er){ echo "* ".$er."<br/>"; }?>', 
                        icon:'error', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            $(document).ready(function () {
                                $('#edit').modal('show');
                            });   
                        }
                    });
                </script>
                <?php unset($_SESSION['edit-accepted']);?>
            <?php endif;?>
        <?php endif;?>
        
        <?php if(isset($_SESSION['update-year'])) :?>
            <?php if($_SESSION['update-year'] == true) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Successfully update student(s) year level', 
                        icon:'success', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.replace('students?id=<?php echo $_SESSION['user_id']?>');
                        }
                    });
                </script>
                <?php unset($_SESSION['update-year']);?>
            <?php endif;?>
        <?php endif; ?>

        <?php if(isset($_SESSION['update-year'])) :?>
            <?php if($_SESSION['update-year'] == false) :?>
                <script type='text/javascript'>
                    Swal.fire({
                        title: 'Failed to update student(s) year level',
                        icon:'error', 
                        confirmButtonColor: 'maroon', 
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                             window.location.replace('students?id=<?php echo $_SESSION['user_id']?>');
                        }
                    });
                </script>
                <?php unset($_SESSION['update-year']);?>
            <?php endif;?>
        <?php endif;?>
    </body>
</html>
