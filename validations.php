<?php
    

    function studentNoSessionSet($studentNo){
        if(!empty($studentNo)){
            $_SESSION['studentno'] = $studentNo;
        }
    }

    //Lastname input validation
    function lastnameValidation($lastName, $error){
        if(!preg_match("/^[a-zA-Z-' ]*$/", $lastName)) {
            $error[] = 'Special characters are not allowed in Lastname.';
            $_SESSION['lastName'] = $lastName;
        }else{
            $_SESSION['lastName'] = $lastName;
        }
        return $error;
    }

    //Firstname input Validation
    function firstnameValidation($firstName, $error){
        if(!preg_match("/^[a-zA-Z-' ]*$/", $firstName)) {
            $error[] = 'Special characters are not allowed in Firstname.';
            $_SESSION['firstName'] = $firstName;
        }else{
            $_SESSION['firstName'] = $firstName;
        }
        return $error;
    }

    //Middlename set N/A if empty
    function middlenameIsEmpty($middleName){
        if(empty($middleName)){
            $middleName = "N/A";
        }
        return $middleName;
    }

    //Middlename input validation
    function middlenameValidation($middleName, $error){
        if(!preg_match("/^[a-zA-Z-' ]*$/", $middleName)){
            $error[] = 'Special characters are not allowed in Middlename.';
            $_SESSION['middleName'] = $middleName;
        }
        else{
            $_SESSION['middleName'] = $middleName;
        }
        return $error;
    }

    //Birthday session set
    function birthdaySessionSet($birthday){
        if(!empty($birthday)){
            $_SESSION['birthday'] = $birthday;
        }
    }

    //Email set N/A if empty
    function emailIsEmpty($email){
        if(empty($email)){
            $email = "N/A";
        }
        return $email;
    }

    //Email input validation
    function emailValidation($email, $error){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error[] = 'Incorrect Email format. Please follow the given format.';
            if(isset($_SESSION['email'])) {
                unset($_SESSION['email']);
            }
        }
        else{
            $_SESSION['email'] = $email;
        }
        return $error;
    }

    function employeeEmailValidation($email, $error){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error[] = 'Incorrect Email format. Please follow the given format.';
            if(isset($_SESSION['email'])) {
                unset($_SESSION['email']);
            }
        }
        else{
            $isExist = isEmailTaken($email);
            if($isExist){
                $error[] = 'The Email Address is already used. Please enter another.';
            }
            $_SESSION['email'] = $email;
        }
        return $error;
    }

    //Contact set N/A if input is empty
    function contactIsEmpty($contactNo){
        if(empty($contactNo)){
            $contactNo = "N/A";
        }
        return $contactNo;
    }

    //Contact input validation
    function contactValidation($contactNo, $error){
        if(!preg_match("/^[0-9+ ]+$/D", $contactNo)){
            $error[] = 'Invalid Contact No. format. Please follow the given format.';
            $_SESSION['contact'] = $contactNo;
        }
        else{
            $_SESSION['contact'] = $contactNo;
        }
        return $error;
    }

    //Course session set
    function courseSessionSet($course){
        if(!empty($course)) {
            $_SESSION['course'] = $course;
        }
    }

    

    //Year session set
    function yearSessionSet($year){
        if(!empty($year)) {
            $_SESSION['year'] = $year;
        }
    }

    //Section session set
    function sectionSessionSet($section){
        if(!empty($section)) {
            $_SESSION['section'] = $section;
        }
    }

    

    function passwordValidation($password, $confirm, $error){
        if($password == $confirm){
            $_SESSION['password'] = $password; 
        }
        else{
            $error[] = 'Please make sure that you enter the right confirm password.';
        }
        return $error;
    }

    

    function documentValidation($documents, $error){
        $aray = ['Transcript of Records', 'Certificate of Graduation', 'Diploma'];
        if(count($documents) == 0){
            $error[] = "Please Select any Document(s)";
        }
        else{
             foreach($documents as $document){
                 for($i = 0; $i < count($aray); $i++){
                    if($document == $aray[$i]){
                        $_SESSION['document'.$i] = $document;
                    }
                }
            }
        }
       return $error;
    }
    
    function documentValidation1($documents, $error){
        $aray1 = ['Certificate of Grades', 'Certificate of Registration', 'Certificate of Good moral', 'Honourable Dismissal', 'Certificate of Non-issuance of ID'];
        if(count($documents) == 0){
            $error[] = "Please Select any Document(s)";
        }
        else{
             foreach($documents as $document){
                for($i = 0; $i < count($aray1); $i++){
                    if($document == $aray1[$i]){
                        $_SESSION['document'.$i] = $document;
                    }
                }
                $count++;
            }
        }
       return $error;
    }
    
    function purposeSessionSet($purpose){
        if(!empty($purpose)) {
            $_SESSION['purpose'] = $purpose;
        }
    }

    function academicYearSessionSet($academicYear){
        if(!empty($academicYear)) {
            $_SESSION['academicYear'] = $academicYear;
        }
    }

    //Section session set
    function semesterSessionSet($semester){
        if(!empty($semester)) {
            $_SESSION['semester'] = $semester;
        }
    }

    function genderSessionSet($gender){
        if(!empty($gender)) {
            $_SESSION['gender'] = $gender;
        }
    }

    
    
    function studentNoValidation($studentNo, $error){
        if(!preg_match("/^[0-9-]+$/D", $studentNo)) {
            $error[] = 'Special characters, letters and spaces are not allowed in Student No. field, Please follow the given format.';
            $_SESSION['studentno'] = $studentNo;
        }
        else{
            $isExist = isStudent($studentNo);
            if($isExist){
                $error[] = 'The Student No, is already taken. Student No. must be unique.';
            }
            $_SESSION['studentno'] = $studentNo;   
        }
        return $error;
    }
    function isCurrentStudent($studentNo, $birthday, $course, $error){
        if(!preg_match("/^[0-9-]+$/D", $studentNo)) {
            $error[] = 'Special characters, letters and spaces are not allowed in Student No. field, Please follow the given format.';
            $_SESSION['studentno'] = $studentNo;
        }
        else{
            $isStudent = isStudent1($studentNo, $birthday, $course);
            if(!$isStudent){
                $error[] = 'Student not found. Please check your inputted data and follow the format.';
            }
            $_SESSION['studentno'] = $studentNo;   
        }
        return $error;
    }
    
    function isAlumni($studentNo, $birthday, $course, $error){
        if(!preg_match("/^[0-9-]+$/D", $studentNo)) {
            $error[] = 'Special characters, letters and spaces are not allowed in Student No. field, Please follow the given format.';
            $_SESSION['studentno'] = $studentNo;
        }
        else{
            $isStudent = isStudent($studentNo, $birthday, $course);
            if(!$isStudent){
                $error[] = 'Alumni not found. Please check your inputted data and follow the format.';
            }
            $_SESSION['studentno'] = $studentNo;   
        }
        return $error;
    }
    
    function employeeNoValidation($employeeNo, $error){
        if(!preg_match("/^[0-9-]+$/D", $employeeNo)) {
            $error[] = 'Special characters, letters and spaces are not allowed in Student No. field, Please follow the given format.';
            $_SESSION['employeeNo'] = $employeeNo;
        }
        else{
            $isExist = isEmployee($employeeNo);
            if($isExist){
                $error[] = 'The Employee No, is already taken. Employee No. must be unique.';
            }
            $_SESSION['employeeNo'] = $employeeNo;   
        }
        return $error;
    }


?>