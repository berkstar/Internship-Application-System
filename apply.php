<?php

include("config.php");
session_start();
if(!(isset($_SESSION['login']) && $_SESSION['login'] != '')){  
    header("Location: index.php");
}  


$studentId = $_SESSION['sid'];

$sql = "SELECT cid, cname, quota FROM company
WHERE quota > 0 and cid <> all (SELECT cid FROM apply
					WHERE sid = $studentId)"; 

$result = mysqli_query($conn, $sql);  

if(isset($_POST["apply"])){
    
    $companyId = $_POST["compID"];

    $sql = "SELECT company.cid, cname, quota  FROM  company, apply  WHERE apply.sid = $studentId and apply.cid = company.cid"; 

    $count = mysqli_num_rows(mysqli_query($conn, $sql));
      
        if($count >= 3){
          echo "
          <script>
              alert('You already have 3 application.')
          </script>";
  
        }
        else{
 
            $sql = "INSERT INTO apply
            VALUES ($studentId,\"$companyId\")";
            mysqli_query($conn, $sql); 
      
            if(mysqli_affected_rows($conn) > 0){
      
            echo "<script>
                alert('You have applied to $companyId.');
             </script>";
      
             $sql = "UPDATE company SET quota = quota - 1 WHERE cid = '$companyId'";
             mysqli_query($conn,$sql);
             header("refresh:1;url=apply.php"); 
          
            }else{
      
            echo "<script>
                alert('You could not apply.');
                </script>";
            header("refresh:1;url=apply.php"); 
          
            }

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
<a href="welcome.php" class="waves-effect red waves-light right btn"><i class="material-icons left">first_page</i>Go Back</a>
    <h4 class ="center green-text darken-3"> Available Internships</h4>
    <form class="white center-align grey lighten-4" action="apply.php" method="POST">
    <div class="row">
    
    <div class="input-field col s5">
    <input name="compID" id="CompanyID" type="text" class="validate">
               <label for="name">Company ID</label>

    </div>
    <div class="input-field col s2">
               <button class="btn waves-effect waves-light pink" type="submit" name="apply">apply
               </button>
           </div>
    </div>

      </form>

    <table class="black-text">
        <thead>
          <tr>
              <th>Company ID</th>
              <th>Company Name</th>
              <th>Quota</th>
              
          </tr>
        </thead>
        <tbody>
        <?php
        while ($row = mysqli_fetch_array($result)) {
                   echo "<tr>";
                   echo "<td>".$row['cid']."</td>";
                   echo "<td>".$row['cname']."</td>";
                   echo "<td>".$row['quota']."</td>";
                   echo "</tr>";
               }
        ?>
          
        </tbody>
        </table>
        </br>

</section>
</body>

</html>