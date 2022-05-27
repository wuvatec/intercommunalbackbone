<?php
ob_start();
session_start();
include '../../vendor/autoload.php';
include '../../resources/functions/func.php';

require_once '../../resources/inc/_auth_user.inc';

// Connect to PHP Activerecord
require_once '../../resources/inc/db.php';

// Not secured using http_referer, i would still go ahead to use it as this app is personal
// Redirect user to support where user was intended to come from, when use tries to access
if (!isset($_SERVER['HTTP_REFERER'])) {
	header('location: transfer.php');
}

if(isset($_POST['sign_up'])) {
	if (!empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['username']) && !empty($_POST['password'])) {
		
    $user = User::find_by_username($_POST['username']);
    if ($user == null){
    	// echo $_FILES['image']['name']; die();
	      $errors= array();
	      $file_name = $_FILES['image']['name'];
	      $file_size =$_FILES['image']['size'];
	      $file_tmp =$_FILES['image']['tmp_name'];
	      $file_type=$_FILES['image']['type'];
	      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
	      
	      $expensions= array("jpeg","jpg","png");
	      
	      if(in_array($file_ext,$expensions)=== false){
	         $error_message[]="extension not allowed, please choose a JPEG or PNG file.";
	      }
	      
	      if($file_size > 2097152){
	         $error_message[]='File size must be excately 2 MB';
	      }
	      
	      if(empty($error_message)==true){
	          // echo 'uploads/'.$file_name; die();
	          move_uploaded_file($file_tmp,"uploads/".$file_name);
	          $path = 'uploads/' . basename($file_name); /*die();*/

	          $new_user = new User();
	          $new_user->username = $_POST['username'];
	          $new_user->password = password_hash($_POST['password'], PASSWORD_BCRYPT);
	          $new_user->first_name = $_POST['first_name'];
	          $new_user->last_name = $_POST['last_name'];
	          $new_user->active = 1;
	          $new_user->user_role = 2;
	          $new_user->date = time();

	          $new_user->save();

	          $errors= array();
		      $file_name = $_FILES['photo']['name'];
		      $file_size =$_FILES['photo']['size'];
		      $file_tmp =$_FILES['photo']['tmp_name'];
		      $file_type=$_FILES['photo']['type'];
		      $file_ext=strtolower(end(explode('.',$_FILES['photo']['name'])));
		      
		      $expensions= array("jpeg","jpg","png");
		      
		      if(in_array($file_ext,$expensions)=== false){
		         $error_message[]="extension not allowed, please choose a JPEG or PNG file.";
		      }
		      
		      if($file_size > 2097152){
		         $error_message[]='File size must be excately 2 MB';
		      }
		      
		      if(empty($error_message)==true){
		          // echo 'uploads/'.$file_name; die();
		          move_uploaded_file($file_tmp,"uploads/".$file_name);
		          $path_id = 'ID/' . basename($file_name);

		          $get_user = User::find_by_username($_POST['username']);

		          $account = new Account();
		          $account->uid = $get_user->id;
		          $account->username = $get_user->username;
		          $account->email_address = $_POST['email_address'];
		          $account->dob = $_POST['dob'];
		          $account->address = $_POST['address'];
		          $account->mobile_number = $_POST['mobile_number'];
		          $account->id_type = $_POST['id_type'];
		          $account->id_number = $_POST['id_number'];
		          $account->registered = 1;
		          $account->img_path = $path;
		          $account->id_img_path = $path_id;
				  $account->date = time();
		          
		          $account->save();

		          	echo "<div class=\"alert alert-success centre\">";
					echo 'Account successfully created. An administrator would contact you with your account details.';
					echo "</div>";

		      }else{
		         echo "<div class=\"alert alert-danger centre\">";
				echo 'File can not be uploaded.';
				echo "</div>";
		      }

	      }else{
	         echo "<div class=\"alert alert-danger centre\">";
			echo 'File can not be uploaded.';
			echo "</div>";
	      }
	    } 
	} else {
		echo "<div class=\"alert alert-danger centre\">";
		echo 'All fields are required';
		echo "</div>";
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		.cssload-piano {
	margin: auto;
	width: 65px;
	height: 16px;
	font-size: 16px;
}
.cssload-piano > div {
	height: 100%;
	width: 100%;
	display: block;
	margin-bottom: 0.6em;
	animation: stretchdelay 1.08s infinite ease-in-out;
		-o-animation: stretchdelay 1.08s infinite ease-in-out;
		-ms-animation: stretchdelay 1.08s infinite ease-in-out;
		-webkit-animation: stretchdelay 1.08s infinite ease-in-out;
		-moz-animation: stretchdelay 1.08s infinite ease-in-out;
}
.cssload-piano .cssload-rect2 {
	animation-delay: -0.9s;
		-o-animation-delay: -0.9s;
		-ms-animation-delay: -0.9s;
		-webkit-animation-delay: -0.9s;
		-moz-animation-delay: -0.9s;
}
.cssload-piano .cssload-rect3 {
	animation-delay: -0.72s;
		-o-animation-delay: -0.72s;
		-ms-animation-delay: -0.72s;
		-webkit-animation-delay: -0.72s;
		-moz-animation-delay: -0.72s;
}



@keyframes stretchdelay {
	0%, 40%, 100% {
		transform: scaleX(0.8);
		background-color: rgb(46,88,101);
		box-shadow: 0 0 0 rgba(10,10,10,0.1);
	}
	20% {
		transform: scaleX(1);
		background-color: rgb(0,177,146);
		box-shadow: 0 8px 10px rgba(10,10,10,0.4);
	}
}

@-o-keyframes stretchdelay {
	0%, 40%, 100% {
		-o-transform: scaleX(0.8);
		background-color: rgb(46,88,101);
		box-shadow: 0 0 0 rgba(10,10,10,0.1);
	}
	20% {
		-o-transform: scaleX(1);
		background-color: rgb(0,177,146);
		box-shadow: 0 8px 10px rgba(10,10,10,0.4);
	}
}

@-ms-keyframes stretchdelay {
	0%, 40%, 100% {
		-ms-transform: scaleX(0.8);
		background-color: rgb(46,88,101);
		box-shadow: 0 0 0 rgba(10,10,10,0.1);
	}
	20% {
		-ms-transform: scaleX(1);
		background-color: rgb(0,177,146);
		box-shadow: 0 8px 10px rgba(10,10,10,0.4);
	}
}

@-webkit-keyframes stretchdelay {
	0%, 40%, 100% {
		-webkit-transform: scaleX(0.8);
		background-color: rgb(46,88,101);
		box-shadow: 0 0 0 rgba(10,10,10,0.1);
	}
	20% {
		-webkit-transform: scaleX(1);
		background-color: rgb(0,177,146);
		box-shadow: 0 8px 10px rgba(10,10,10,0.4);
	}
}

@-moz-keyframes stretchdelay {
	0%, 40%, 100% {
		-moz-transform: scaleX(0.8);
		background-color: rgb(46,88,101);
		box-shadow: 0 0 0 rgba(10,10,10,0.1);
	}
	20% {
		-moz-transform: scaleX(1);
		background-color: rgb(0,177,146);
		box-shadow: 0 8px 10px rgba(10,10,10,0.4);
	}
}
	</style>
	<link rel="stylesheet" type="text/css" href="../../public/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../../public/css/custom.css">
	<link href='https://fonts.googleapis.com/css?family=Raleway:400,800' rel='stylesheet' type='text/css'>
</head>
<body>

</body>
</html>