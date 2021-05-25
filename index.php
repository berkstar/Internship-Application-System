<?php


    include("config.php");
    mysqli_query($conn, "SELECT * FROM student");
    $loginerror ="";
    $errors = array("username"=>"", "ID"=>"");

    //User presses enter button
    if(isset($_POST["submit"])){

        if(empty($_POST["username"])){
            $errors["username"] = "Please enter your username.";
           
        }

        if(empty($_POST["ID"])){
            $errors["ID"] = "Please enter your ID.";

        }

        if(array_filter($errors)){
       
            //header("Location: welcome.php");
        }
        else{
    
            $username = $_POST["username"];
            $password = $_POST["ID"];
            $username = mysqli_real_escape_string($conn, $username);  
            $password = mysqli_real_escape_string($conn, $password);  
    
    
            $sql = "SELECT * FROM student WHERE UPPER(sname) = UPPER('$username') and sid = '$password'"; 
    
            $result = mysqli_query($conn, $sql);  
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
            if($row != null){  
                session_start();
                $_SESSION['login'] = "1";
                $_SESSION['sid'] = $password;
                $loginerror = "Login successful";  
                header("Location: welcome.php");
            }  
            else{  
                $loginerror = " Login failed. Invalid username or ID.";  
            }     
    
        }

    }

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <title>Summer Internship Application</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
<style type="text/css">
    .brand-text{
        color: #ccff99 !important;
    }
    form{
        max-width: 800px;
        margin: 10px auto;
        padding: 20px;
    }
    </style>

</head>
<body class="grey lighten-4">
<nav class="blue z-depth-0">
    <div class= "container">
        <a href="#" class="brand-logo brand-text">Summer Internship Application</a>
        <ul id="nav-mobile" class="right hide-on-small-and-down">
    </div>
</nav>



<section class="container grey-text">
    <h4 class ="center pink-text darken-3"> Login</h4>
    <form class="white" action="index.php" method="POST">
        <input type="text" name="username">
        <label for="username">Username:</label>
        <div class="red-text"><?php echo $errors["username"]; ?></div>
        <input type="text" name="ID">
        <label for="ID">ID:</label>
        <div class="red-text"><?php echo $errors["ID"]; ?></div>
        <div class="center">
            <input type="submit" name="submit" value="Login" class="btn brand">
            </div>
    </form>
    <h4 class ="center red-text"><?php echo $loginerror; ?></h4>
</section>

</body>


</html>