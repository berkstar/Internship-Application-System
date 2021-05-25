<?php

include("config.php");
session_start();
if(!(isset($_SESSION['login']) && $_SESSION['login'] != '')){  
    header("Location: index.php");
}  


$studentId = $_SESSION['sid'];

$sql = "SELECT company.cid, cname, quota  FROM  company, apply  WHERE apply.sid = $studentId and apply.cid = company.cid"; 

$result = mysqli_query($conn, $sql);  
$count = mysqli_num_rows($result);

if(isset($_POST["newIntern"])){
    
    if($count >= 3){
        echo "
        <script>
            alert('You already have 3 application.')
        </script>";
    }
    else{
        header("Location: apply.php");
    }

}

if(isset($_GET["cid"])){
    
    $companyId = $_GET["cid"];
    $sql = "DELETE FROM apply WHERE sid = $studentId and cid = '$companyId'";
    $deleted = mysqli_query($conn,$sql);
    if(mysqli_affected_rows($conn) > 0){

        echo "<script>
            alert('You have cancelled $companyId apply.');
        </script>";

        $sql = "UPDATE company SET quota = quota + 1 WHERE cid = '$companyId'";
        mysqli_query($conn,$sql);

        header("refresh:1;url=welcome.php"); 
        
    }else{

        echo "<script>
            alert('You could not cancel the apply.');
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
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
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>


<body class="grey lighten-4">
<nav class="blue z-depth-0">
    <div class= "container">
        <a href="#" class="brand-logo brand-text">Summer Internship Application</a>
        <ul id="nav-mobile" class="right hide-on-small-and-down">
        <a class="brand-logo center orange darken-4"><?php echo  "Student ID:".$studentId ?></a>
        <li><a href="logout.php" class="btn brand z-depth">Logout</a></li>
    </div>
</nav>



<section class="container grey-text">
    <h4 class ="center pink-text darken-3"> Applied Internships</h4>


    <table class="black-text">
        <thead>
          <tr>
              <th>Company ID</th>
              <th>Company Name</th>
              <th>Quota</th>
              <th>Cancel</th>
              
          </tr>
        </thead>
        <tbody>
        <?php
        while ($row = mysqli_fetch_array($result)) {
                   echo "<tr>";
                   echo "<td>".$row['cid']."</td>";
                   echo "<td>".$row['cname']."</td>";
                   echo "<td>".$row['quota']."</td>";
                   $curr = $row['cid'];
                   echo "<td><a href=\"welcome.php?cid=$curr\"";
                   echo " 
                   class=\"btn-floating btn-small waves-effect waves-light red\">
                   <i class=\"material-icons\">close</i></button></td>";
                   echo "</tr>";
               }
        ?>

        </tbody>
    </table>

    </br>
    <form class="white center-align grey lighten-4" action="welcome.php" method="POST">
    <button class="btn waves-effect waves-light purple" type="submit" name="newIntern">Apply for new internship
    <i class="material-icons right">assignment_returned</i>
    </button>
    </form>
</section>

</body>

</html>