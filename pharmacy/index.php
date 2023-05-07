<?php
error_reporting(1);
session_start();
include("dbcon.php");
if(isset($_SESSION['user_session'])){
  
  $invoice_number="RS-".invoice_number();
	header("location:home.php?invoice_number=$invoice_number");
}

   if(isset($_POST['submit'])){  
  $username =$_POST['username'];

  $password = $_POST['password'];

  $select_sql = "SELECT * FROM users ";

  $select_query = mysqli_query($con,$select_sql);
   
  if($select_query){

  	while ($row =mysqli_fetch_array($select_query)) {
  		$s_username = $row['user_name'];
  		$s_password = $row['password'];
  	}
  }

 if($s_username == $username && $s_password == $password){
          
         $_SESSION['user_session'] = $s_username;
         $invoice_number="RS-".invoice_number();
 	       header("location:home.php?invoice_number=$invoice_number");


 }else{
 	  	    $error_msg = "<center><font color='red'>Login Error</font></center>";
 }

}                  

  function invoice_number(){  

    $chars = "09302909209300923";

    srand((double)microtime()*1000000);

    $i = 1;

    $pass = '';

    while($i <=7){

      $num  = rand()%10;
      $tmp  = substr($chars, $num,1);
      $pass = $pass.$tmp;
      $i++;
    }
    return $pass;
                        
  }                       
?>

<!DOCTYPE html>
<html>
<!DOCTYPE html>
<html>
<head>

	<title>Nepal Medicine System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.css">
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <style>
      .form-group{width:97%;}
      label {display: block;margin-bottom: 5px;text-align: right;line-height: 29px;}
      body{height:100vh;display:flex;justify-content:center;align-items:center;padding:0px;}
    </style>
    
</head>
<body style="background:#f1f1f1;">

	<div class="content" style="width: 600px;background: #f9f9f9; padding: 40px;">

    <center style="margin-bottom:30px;">
      <h1>Medicine Management System</h1>
    </center>

		<form method="POST">

		<table class="table table-bordered table-responsive " >
			<tr>
			  <td><label for="username">Usename</label></td>
			  <td><input type="text" autocomplete="off" name="username" class="form-group" required></td>
			</tr>
			<tr>
				<td><label for="password">Password</label></td>
				<td><input type="password" name="password" class="form-group" required></td>
			</tr>
     <input type="hidden" aucomplete="off" name="invoice_number" value="<?php echo 'RS-'.invoice_number()?>">

		</table>
    
    <div class="cntr_btn">  
      <input type="submit" name="submit" class="btn btn-primary btn-large" value="Login">
     </div> 
		

    <?php echo $error_msg;?>

	</form>

		
	</div>
 
</body>
</html>