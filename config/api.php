<?php
    if(empty(session_id()))session_start();
	include 'config-file.php';


    header('Content-Type: application/json');
	switch ($_POST["action"]) {


        // Login Account
        case 'login-account':
            $username = mysqli_real_escape_string($con, $_POST['username'] ?: '');
            $password = $_POST['password'] ?: '';
            $sql = "
                SELECT * 
                FROM account
                WHERE account_username='$username'
            ";
            $result = mysqli_query($con,$sql);

            if($result) {
                $account = mysqli_fetch_assoc($result);
                if($account) {
                    if($account['account_password'] == $password) {
                        $_SESSION['account'] = $account;
                        $redirect = '';
                        switch ($account['account_type']) {
                            case '1': $redirect = 'admin-dashboard.php'; break;
                        }
                        echo json_encode(['redirect' => $redirect]);
                    }
                    else {
                        echo json_encode(['error' => ['WRONG_PASSWORD', 'Wrong Password.']]);
                    }
                }
                else {
                    echo json_encode(['error' => ['WRONG_USERNAME', 'Username does not exist.']]);
                }
            }
            else echo json_encode(['error' => ['DB_ERROR', mysqli_error($con)]]);
            break;


        case 'register-account':
            $studentid = $_POST['studentid'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $firstname = $_POST['firstname'];
            $middlename = $_POST['middlename'];
            $lastname = $_POST['lastname'];
            $typeofaccount = $_POST['typeofaccount'];

            $sql = "INSERT INTO accounts(idnumber, username, password, firstname, middlename, lastname, typeofaccount) VALUES ('$studentid', '$username', '$password', '$firstname', '$middlename', '$lastname', '$typeofaccount')";
            $result = mysqli_query($con,$sql);

            if($result) echo json_encode(['message' => 'Successfully created your account <b>'.$_POST['username'].'</b>']);
            else echo json_encode(['error' => ['DB_ERROR', mysqli_error($con)]]);
            break;

        case 'edit-account':
            $password = $_POST['password'];
            $firstname = $_POST['firstname'];
            $middlename = $_POST['middlename'];
            $lastname = $_POST['lastname'];
            $username = $_POST['username'];

            $sql = "UPDATE accounts
                    SET firstname='$firstname', 
                        middlename='$middlename', 
                        lastname='$lastname', 
                        password='$password' 
                    WHERE username='$username'";
            $result = mysqli_query($con,$sql);

            if($result) echo json_encode(['message' => 'Successfully updated your account details <b>'.$_POST['username'].'</b>']);
            else echo json_encode(['error' => ['DB_ERROR', mysqli_error($con)]]);
            break;

        case 'delete-account':
            $username = $_POST['username'];

            $sql = "DELETE FROM accounts
                    WHERE username='$username'";
            $result = mysqli_query($con,$sql);

            if($result) echo json_encode(['message' => 'Successfully deleted the account <b>'.$_POST['username'].'</b>']);
            else echo json_encode(['error' => ['DB_ERROR', mysqli_error($con)]]);
            break;

        case 'create-chapter':
            $chaptername = $_POST['chaptername'];
            $chapterdescription = $_POST['chapterdescription'];

            $sql = "INSERT INTO chapter(chapter_name, chapter_description) VALUES ('$chaptername', '$chapterdescription')";
            $result = mysqli_query($con,$sql);

            if($result) echo json_encode(['message' => 'Successfully added chapter <b>'.$_POST['username'].'</b>']);
            else echo json_encode(['error' => ['DB_ERROR', mysqli_error($con)]]);
            break;

        case 'create-lesson':
            $chapterid = $_POST['lessonchaptername'];
            $lessonname = $_POST['lessonname'];
            $lessondescription = $_POST['lessondescription'];

            $sql = "INSERT INTO lesson(chapter_id, lesson_name, lesson_description) VALUES ('$chapterid', '$lessonname', '$lessondescription')";
            $result = mysqli_query($con,$sql);

            if($result) echo json_encode(['message' => 'Successfully added lesson <b>'.$_POST['username'].'</b>']);
            else echo json_encode(['error' => ['DB_ERROR', mysqli_error($con)]]);
            break;

        case 'check-quiz':
            // $chapterid = $_POST['lessonchaptername'];
            // $lessonname = $_POST['lessonname'];
            // $lessondescription = $_POST['lessondescription'];

            // $sql = "INSERT INTO lesson(chapter_id, lesson_name, lesson_description) VALUES ('$chapterid', '$lessonname', '$lessondescription')";
            $result = mysqli_query($con,$sql);

            if($result) echo json_encode(['message' => 'Successfully checked quiz <b>'.$_POST['username'].'</b>']);
            else echo json_encode(['error' => ['DB_ERROR', mysqli_error($con)]]);
            break;
        
        // Action Not Found

        default:
            echo json_encode(['error' => ['ACTION_NOT_FOUND', 'Action not found.']]);
            break;
    }