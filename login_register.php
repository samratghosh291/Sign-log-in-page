<?php 

require('connection.php');
session_start();

#for login
if(isset($_POST['login'])){
    $email_username = mysqli_real_escape_string($con, $_POST['email_username']);
    $query="SELECT * FROM `registered_user` WHERE `email`='$email_username' OR `username`='$email_username'";
    $result=mysqli_query($con,$query);

    if($result){

        if(mysqli_num_rows($result)==1){

            $result_fetch=mysqli_fetch_assoc($result);
            if(password_verify($_POST['password'],$result_fetch['password'])){
                #if password matched
                
                $_SESSION['logged_in']=true;
                $_SESSION['username']=$result_fetch['username'];
                header("location: index.php");

            }
            else{

                #if incorrect password
                echo "
            <script>alert('Incorrect Password');
            window.location.href='index.php';
            </script>
            ";
            }

        }
        else{

            echo "
            <script>alert('Email or Username not Registered');
            window.location.href='index.php';
            </script>
            ";

        }

    }
    else{

        error_log("Error running query: " . mysqli_error($con));
        echo "
        <script>alert('Cannot Run Query');
        window.location.href='index.php';
        </script>
        ";

    }
}

#for registration
if(isset($_POST['register'])){
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $fullname = mysqli_real_escape_string($con, $_POST['fullname']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $user_exists_query="SELECT * FROM `registered_user` WHERE `username`='$username' OR `email`='$email'";
    $result=mysqli_query($con,$user_exists_query);

    if($result){
        #it will be executed if username or email already registered
        if(mysqli_num_rows($result)>0){
            #if any user has already taken username or email
            $result_fetch=mysqli_fetch_assoc($result);
            if($result_fetch['username']==$username){
                #error for username already registered
                echo "
                <script>alert('$username - Username already taken');
                window.location.href='index.php';
                </script>
                ";
            }
            else{
                # error for user already registered 
                echo "
                <script>alert('$email - E-Mail already registered');
                window.location.href='index.php';
                </script>
                ";
            }
        }
        #it will be executed if no one has taken username and email
        else{

            $password=password_hash($password,PASSWORD_BCRYPT);
            $query="INSERT INTO `registered_user`(`full_name`, `username`, `email`, `password`) VALUES ('$fullname','$username','$email','$password')";
            if(mysqli_query($con,$query)){
                #if data inserted successfully
                echo "
                <script>alert('Registration Successful');
                window.location.href='index.php';
                </script>
                ";
            }
            else{
                error_log("Error running query: " . mysqli_error($con));
                #if query data cannot be inserted
                echo "
                <script>alert('Cannot Run Query');
                window.location.href='index.php';
                </script>
                ";
            }
        }
    }
    else{
        error_log("Error running query: " . mysqli_error($con));
        echo "
        <script>alert('Cannot Run Query');
        window.location.href='index.php';
        </script>
        ";
    }
}
?>
