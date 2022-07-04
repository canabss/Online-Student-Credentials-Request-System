<?php
	global $db;
	require_once("sendEmails.php");

	function database(){
		$host = "localhost";
		$username = "u601593730_oscrs";
		$password = "Capstoneoscrs001";
		$database = "u601593730_oscrs_database";
		$db= new mysqli($host,$username,$password,$database);
		return $db;
	}

	function addStudent($studentNo, $lastName, $firstName, $middleName, $birthday, $contactNo, $course, $year, $section, $yearGraduated){
		$db = database();

		if(!($db -> connect_error)){
			$sql = $db->query("INSERT INTO students VALUES ('$studentNo', '$lastName', '$firstName', '$middleName', '$birthday',  '$contactNo', '$course', '$year','$section', '$yearGraduated')");
		}
		
		return $sql;
	}

	function updateStudent($lastName, $firstName, $middleName, $birthday, $contactNo, $course, $year, $section, $studentNo){
		$db = database();

		if(!($db -> connect_error)){
			 $sql = $db->query("UPDATE students SET  last_name = '$lastName', first_name = '$firstName', middle_name = '$middleName', birthday = '$birthday', contact_no = '$contactNo', course = '$course', year_level = '$year', section = '$section' WHERE student_no='$studentNo'");
		}
		
		return $sql;
	}

	function getStudents($yearLevel){
		$db = database();
		$students = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM students WHERE year_level = '$yearLevel'");
	        while($data = mysqli_fetch_assoc($sql)){
	            $students[] = $data;
	        }
	    }
        return $students;
	}
	
	function getMessages(){
		$db = database();
		$messages = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM messages ORDER BY message_id DESC");
	        while($data = mysqli_fetch_assoc($sql)){
	            $messages[] = $data;
	        }
	    }
        return $messages;
	}

	function getFilterStudents($value){
		$db = database();
		$students = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM students WHERE course = '$value'");
	        while($data = mysqli_fetch_assoc($sql)){
	            $students[] = $data;
	        }
	    }
        return $students;
	}

	function createAccount($role, $username, $password){
		$db = database();
		if(!($db -> connect_error)){
			$sql = $db->query("INSERT INTO accounts(role, username, password) VALUES ('$role', '$username', '$password')");
		}
		return $sql;
	}

	function logs($account_id, $date, $login, $logout, $role){
		$db = database();
		if(!($db -> connect_error)){
			$sql = $db->query("INSERT INTO logs(account_id, date, login, logout, role_id) VALUES ('$account_id', '$date', '$login', '$logout', '$role')");
		}
		
		return $sql;
	}
    
    function isStudents($studentNo){
		$db = database();
		$isExist = false;
		$sql = $db->query("SELECT * FROM students");
		while($data = mysqli_fetch_assoc($sql)){
			if($studentNo == $data['student_no']){
				$isExist = true;
				break;
			}
		}
		return $isExist;
	}
    
	function isStudent($studentNo, $birthday, $course){
		$db = database();
		$isExist = false;
		$sql = $db->query("SELECT * FROM students WHERE year_level = 'Graduate'");
		while($data = mysqli_fetch_assoc($sql)){
			if($studentNo == $data['first_name'] && $studentNo == $data['last_name'] && $birthday == $data['birthday'] && $course == $data['course']){
				$isExist = true;
				break;
			}
		}
		return $isExist;
	}
	
	function isStudent1($studentNo, $birthday, $course){
		$db = database();
		$isExist = false;
		$sql = $db->query("SELECT * FROM students WHERE NOT year_level = 'Graduate'");
		while($data = mysqli_fetch_assoc($sql)){
			if($studentNo == $data['first_name'] && $studentNo == $data['last_name'] && $studentNo == $data['student_no'] && $birthday == $data['birthday'] && $course == $data['course']){
				$isExist = true;
				break;
			}
		}
		return $isExist;
	}

	function isEmailTaken($email){
		$db = database();
		$isExist = false;
		$sql = $db->query("SELECT * FROM personnels");
		while($data = mysqli_fetch_assoc($sql)){
			if($email == $data['email']){
				$isExist = true;
				break;
			}
		}
		return $isExist;
	}

	function isEmployee($employeeNo){
		$db = database();
		$isExist = false;
		$sql = $db->query("SELECT * FROM personnels");
		while($data = mysqli_fetch_assoc($sql)){
			if($employeeNo == $data['employee_no']){
				$isExist = true;
				break;
			}
		}
		return $isExist;
	}

	function usernameValidation($username, $error){
		$db = database();
        $isExist = false;
        $sql = $db->query("SELECT username FROM accounts");
        while($data = mysqli_fetch_assoc($sql)){
            if($username == $data['username']){
                $isExist = true;
                break;
            }
        }
        if($isExist){
            $error[] = 'Username is already taken. Please enter another.';
        }
        $_SESSION['username'] = $username;
        return $error;
    }

	function emailIsTaken($email, $error){
		$db = database();
        $isExist = false;
        $sql = $db->query("SELECT email FROM accounts");
        while($data = mysqli_fetch_assoc($sql)){
            if($email == $data['email']){
                $isExist = true;
                break;
            }
        }
        if($isExist){
            $error[] = 'Email is already taken. Please enter another.';
        }
        $_SESSION['email'] = $email; 
        return $error;
    }



	function submitRequest($studentNo, $email, $lastName, $firstName, $middleName, $documents, $academicYear, $semester, $purpose, $requestor, $needClearance){
		$db = database();
		$add = null;
		$dateTimeZone = new DateTime("now", new DateTimeZone('Asia/Manila'));
		$date = $dateTimeZone->format('Y-m-d');
		$list = "";
		$status = "To Confirm";
		$requestRows = $db->query("SELECT COUNT(*) as 'rows' FROM requests");
		$requestRow = $requestRows->fetch_array(MYSQLI_ASSOC);
		$requestId = $dateTimeZone->format('Y')."-".($requestRow['rows']+1);
		$length = count($documents);
		for($i=0; $i<$length; $i++){
			if($i != $length-1){
				$list .= $documents[$i]." and ";
			}
			else{
				$list .= $documents[$i];
		    }
		}

		if(in_array("Certificate of Grades", $documents) || in_array("Certificate of Registration", $documents)){
			$clearanceResult = $db->query("SELECT * FROM clearance WHERE student_no = '$studentNo'");
			$haveClearance = null;
			$myClearanceId = "";
			$clearances = [];
			if($clearanceResult){
				$clearances[] =$clearanceResult->fetch_array(MYSQLI_ASSOC);
				foreach($clearances as $clearance){
					if($clearance['school_year'] == $academicYear && $clearance['semester'] == $semester){
						$haveClearance = true;
						$myClearanceId = $clearance['clearance_id'];
						break;
					}
				}
				if($haveClearance){
					$add = $db->query("INSERT INTO requests VALUES('$requestId', '$studentNo', '$date', '$requestor', '$email', '$list', '$academicYear', '$semester', '$myClearanceId', '$purpose', '$status')");
					if($add){
					    requestConfirmation($requestId, $email, $lastName, $firstName, $myClearanceId);
					}
				}
				else{
					$clearanceRows = $db->query("SELECT COUNT(*) as 'rows' FROM clearance");
					$clearanceRow = $clearanceRows->fetch_array(MYSQLI_ASSOC);
					$myClearanceId = $dateTimeZone->format('Y')."-".($clearanceRow['rows']+1);
					$sql = $db->query("INSERT INTO clearance VALUES('$myClearanceId', '$studentNo', '$email', '$academicYear', '$semester', 'false', 'false', 'false', 'false', 'false', 'false', 'false', 'false', 'false', '$status')");
					if($sql){
						$add = $db->query("INSERT INTO requests VALUES('$requestId', '$studentNo', '$date', '$requestor', '$email', '$list', '$academicYear', '$semester', '$myClearanceId', '$purpose', '$status')");
						if($add){
    					    requestConfirmation($requestId, $email, $lastName, $firstName, $myClearanceId);
    					}
					}
				}
			}
		}else{
			$add = $db->query("INSERT INTO requests VALUES('$requestId', '$studentNo', '$date', '$requestor', '$email', '$list', '$academicYear', '$semester', 'N/A', '$purpose', '$status')");
			if($add){
			    requestConfirmation($requestId, $email, $lastName, $firstName, $myClearanceId);
			}
		}
		return $add;
	}

	function submitRequestAlumni($studentNo, $email, $lastName, $firstName, $middleName, $documents, $year, $semester, $purpose, $requestor, $needClearance){
		$db = database();
		$add = null;
		$dateTimeZone = new DateTime("now", new DateTimeZone('Asia/Manila'));
		$date = $dateTimeZone->format('Y-m-d');
		$list = "";
		$status = "To Confirm";
		$requestRows = $db->query("SELECT COUNT(*) as 'rows' FROM requests");
		$requestRow = $requestRows->fetch_array(MYSQLI_ASSOC);
		$requestId = $dateTimeZone->format('Y')."-".($requestRow['rows']+1);
		$length = count($documents);
		for($i=0; $i<$length; $i++){
			if($i != $length-1){
				$list .= $documents[$i]." and ";
			}
			else{
				$list .= $documents[$i];
			}
		}

		$clearanceResult = $db->query("SELECT * FROM clearance WHERE student_no = '$studentNo'");
		$haveClearance = null;
		$myClearanceId = "";
		$clearances = [];
        $sem = "";
        $yr = "";
        $stts = "";
		if($clearanceResult){
			$clearances[] =$clearanceResult->fetch_array(MYSQLI_ASSOC);
			foreach($clearances as $clearance){
				if($clearance['school_year'] == $year){
					$haveClearance = true;
					$myClearanceId = $clearance['clearance_id'];
					$sem = $clearance['semester'];
                    $yr = $clearance['school_year'];
                    if($clearance['semester'] == "Complete"){
                        $stts = "Pending";
                    }
                    else{
                        $stts = "For Clearance";
                    }
					break;
				}
			}
			if($haveClearance){
				$add = $db->query("INSERT INTO requests VALUES('$requestId', '$studentNo', '$date', '$requestor', '$email', '$list', '$yr', '$sem', '$myClearanceId', '$purpose', 'To Confirm')");
				if($add){
				    requestConfirmation($requestId, $email, $lastName, $firstName, $myClearanceId);
				}
			}
			else{
				$clearanceRows = $db->query("SELECT COUNT(*) as 'rows' FROM clearance");
				$clearanceRow = $clearanceRows->fetch_array(MYSQLI_ASSOC);
				$myClearanceId = $dateTimeZone->format('Y')."-".($clearanceRow['rows']+1);
				$sql = $db->query("INSERT INTO clearance VALUES('$myClearanceId', '$studentNo', '$email', '$year', '$semester', 'false', 'false', 'false', 'false', 'false', 'false', 'false', 'false', 'false', 'To Confirm')");
				if($sql){
					$add = $db->query("INSERT INTO requests VALUES('$requestId', '$studentNo', '$date', '$requestor', '$email', '$list', '$year', '$semester', '$myClearanceId', '$purpose', 'To Confirm')");
					if($add){
					    requestConfirmation($requestId, $email, $lastName, $firstName, $myClearanceId);
					}
				}
			}
		}
		return $add;
	}

	function getRequest($requestId){
		$db = database();
		$request = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM requests WHERE request_id = '$requestId'");
	        $request[] = $sql->fetch_array(MYSQLI_ASSOC);
	    }
        return $request;
	}

	function getAllRequest($requestor, $status){
		$db = database();
		$request = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM requests WHERE request_status = '$status' AND requestor = '$requestor' ORDER BY request_id");
			if($sql){
				while($data = mysqli_fetch_assoc($sql)){
					$request[] = $data;
				}
			}
			
	    }
        return $request;
	}
	
	function getAllRequest1($status){
		$db = database();
		$request = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM requests WHERE request_status = '$status' ORDER BY request_id");
			if($sql){
				while($data = mysqli_fetch_assoc($sql)){
					$request[] = $data;
				}
			}
			
	    }
        return $request;
	}

	function getAllRequests(){
		$db = database();
		$request = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM requests WHERE NOT request_status = 'Archieve' AND NOT request_status = 'To Confirm' AND NOT request_status = 'For Clearance' AND NOT request_status = 'Deleted' ORDER BY request_id AND requestor DESC");
			if($sql){
				while($data = mysqli_fetch_assoc($sql)){
					$request[] = $data;
				}
			}
			
	    }
        return $request;
	}
	
	function getRequests(){
		$db = database();
		$request = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM requests WHERE NOT request_status = 'For Clearance' ORDER BY request_id AND requestor DESC");
			if($sql){
				while($data = mysqli_fetch_assoc($sql)){
					$request[] = $data;
				}
			}
			
	    }
        return $request;
	}

	function getRequestArchieve(){
		$db = database();
		$request = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM requests WHERE request_status = 'Archieve' ORDER BY request_id AND requestor DESC");
			if($sql){
				while($data = mysqli_fetch_assoc($sql)){
					$request[] = $data;
				}
			}
			
	    }
        return $request;
	}

	function getStudent($studentId){
		$db = database();
		$student = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM students WHERE student_no = '$studentId'");
	        $student[] = $sql->fetch_array(MYSQLI_ASSOC);
	    }
        return $student;
	}

	function addEmployee($employeeNo, $lastName, $firstName, $middleName, $birthday, $email, $gender, $accountId, $role, $department){
		$db = database();
		$status = "To Confirm";
		$personnels = [];
		if(empty($department)) $department='N/A';

		if(!($db -> connect_error)){
			
			$sql = $db->query("INSERT INTO personnels VALUES ('$employeeNo', '$lastName', '$firstName', '$middleName', '$birthday', '$email', '$gender', '$role', '$department', 'N/A', '$accountId', '$status')");
			if($sql){
			    accountConfirmation($employeeNo, $email, $lastname, $firstname, $role);
			}
		}
		
		return $sql;
	}

	function updateEmployee($lastName, $firstName, $middleName, $birthday, $email, $gender, $employeeNo){
		$db = database();

		if(!($db -> connect_error)){
			 $sql = $db->query("UPDATE personnels SET  last_name = '$lastName', first_name = '$firstName', middle_name = '$middleName', birthday = '$birthday', email = '$email', gender = '$gender' WHERE employee_no='$employeeNo'");
		}
		
		return $sql;
	}

	function getEmployeesByOffice($office){
		$db = database();
		$personnels = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM personnels WHERE office = '$office'");
	        while($data = mysqli_fetch_assoc($sql)){
	            $personnels[] = $data;
	        }
	    }
        return $personnels;
	}
    
    function getSignatories($role ,$status){
		$db = database();
		$personnels = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM personnels WHERE status = '$status' AND NOT role = '$role'");
	        while($data = mysqli_fetch_assoc($sql)){
	            $personnels[] = $data;
	        }
	    }
        return $personnels;
	}
	
	function getEmployees($role,$status){
		$db = database();
		$personnels = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM personnels WHERE status = '$status' AND role = '$role'");
	        while($data = mysqli_fetch_assoc($sql)){
	            $personnels[] = $data;
	        }
	    }
        return $personnels;
	}

	function getFacultyMembers($role,$status, $department){
		$db = database();
		$personnels = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM personnels WHERE status = '$status' AND department = '$department'");
	        while($data = mysqli_fetch_assoc($sql)){
	            $personnels[] = $data;
	        }
	    }
        return $personnels;
	}

	function getLogs($roleId){
		$db = database();
		$logs = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM logs WHERE role_id = '$roleId' ORDER BY log_id DESC");
	        while($data = mysqli_fetch_assoc($sql)){
	            $logs[] = $data;
	        }
	    }
        return $logs;
	}

	function getAlumni(){
		$db = database();
		$students = [];
		if(!($db -> connect_error)){
			$sql = $db->query("SELECT * FROM students WHERE year_level = 'Graduate'");
	        while($data = mysqli_fetch_assoc($sql)){
	            $students[] = $data;
	        }
	    }
        return $students;
	}
	
	function updateStatus($requestNo, $status){
		$db = database();

		if(!($db -> connect_error)){
		    if($status == "Pending"){
		        $sql = $db->query("UPDATE requests SET request_status = 'To Process' WHERE request_id = '$requestNo'");
		    }
		    else if($status == "To Process"){
		        $sql = $db->query("UPDATE requests SET request_status = 'Archieve' WHERE request_id = '$requestNo'");
		    }
			
			$sql1 = $db->query("SELECT * FROM requests WHERE request_id = '$requestNo'");
			$myRequest = [];
			$myRequest = $sql1->fetch_array(MYSQLI_ASSOC);
			if($sql1){
			    $text = "Your request has been approved by the registrar, we will process your request as soon as possible . Thank you!";
			    $id = $myRequest['student_id'];
			    $sql2 = $db->query("SELECT * FROM students WHERE student_no = '$id'");
			    $myInfo = [];
			    $myInfo = $sql2->fetch_array(MYSQLI_ASSOC);
			    //echo json_encode($myInfo);
			    emailNotify($requestNo, $myInfo['last_name'], $myInfo['first_name'], $myInfo['middle_name'], $myRequest['email'], $text);
			}
		}
		
		return $sql;
	}
?>  