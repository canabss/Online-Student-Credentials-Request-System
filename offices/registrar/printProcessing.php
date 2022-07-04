<!DOCTYPE html>
<?php
	require_once('../database.php');
    $date = new DateTime("now", new DateTimeZone('Asia/Manila'));
?>
<html lang="en">
	<head>
		<style>	
        body{
            padding: 1;
        }
		.table {
			width: 100%;
            margin-bottom: 20px;
		}	
		#PrintButton {
            display: none;
        }
		.table-striped tbody > tr:nth-child(odd) > td,
		.table-striped tbody > tr:nth-child(odd) > th {
			background-color: #f9f9f9;
		}
        img{
            height:40px; 
            width: 250px; 
            margin-left: 15px;
        }
		@media print{
			#print {
				display:none;
			}
		}
		@media print {
			#PrintButton {
				display: none;
			}
		}
		
		@page {
			size: auto;   /* auto is the initial value */
			margin: 0.5;  /* this affects the margin in the printer settings */
		}
	</style>
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="../assets/img/logo.png" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css"/>
	</head>
   
    <body>
        <nav>
            <div class="container-fluid">
                <img src="../assets/img/logo1.png"/>
                <div style="float:right;"><b>Date Prepared: </b><?php echo $date->format("Y-m-d")." - ".$date->format("h:ia"); ?></div>
            </div>
        </nav><br>
        <h3>List of Request</h3>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center" width=11%>Student No.</th>
                    <th class="text-center" width=25%>Complete Name</th>
                    <th class="text-center" width=20%>Requested Document</th>
                    <th class="text-center" width=9%>Year</th>
                    <th class="text-center" width=5%>Semester</th>
                    <th class="text-center" width=10%>Date</th>
                    <th class="text-center" width=18%>Purpose</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $requestor = $_GET['requestor'];
            $requests[] = getAllRequest($requestor, "To Process"); 
            $count = 1;
            ?>
                <?php foreach($requests as $key) :?>
                    <?php foreach($key as $request) :?>
                        <?php 
                            $db = database();
                            $id = $request["student_id"]; 
                            $sql = $db->query("SELECT first_name, middle_name, last_name FROM students WHERE student_no = '$id'");
                            $name[] = $sql->fetch_array(MYSQLI_ASSOC);
                            foreach($name as $name1){
                                $fname = $name1["first_name"];
                                $lname = $name1["last_name"];
                                if($name1["middle_name"] == "N/A"){
                                    $mname = "";
                                }
                                else{
                                    $mname = $name1["middle_name"];
                                }
                            }
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $request["student_id"];?></td>
                            <td class="text-center"><?php echo $fname." ".$mname." ".$lname;?></td>
                            <td class="text-center"><?php echo $request["requested_documents"];?></td>
                            <td class="text-center"><?php echo $request["school_year"];?></td>
                            <td class="text-center"><?php echo $request["semester"];?></td>
                            <td class="text-center"><?php echo $request["request_date"];?></td>
                            <td class="text-center"><?php echo $request["purpose"];?></td>
                            
                        </tr>
                        <?php $count++;?>	
                    <?php endforeach;?>
                <?php endforeach;?>
            </tbody>
        </table>
        <button id="PrintButton" onclick="PrintPage()">Print</button>
    </body>
    <script type="text/javascript">
        function PrintPage() {
            window.print();
        }
        document.loaded = function(){
            
        }
        window.addEventListener('DOMContentLoaded', (event) => {
            PrintPage()
            setTimeout(function(){ window.close() },750)
        });
    </script>
</html>