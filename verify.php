<?php

require_once('config.php');
session_start();
if(!isset($_SESSION['st_loggedin'])){
    header('location:login.php');
}

// verificaton
$usar_id = $_SESSION['st_loggedin'][0]['id'];

if(isset($_POST['st_email_send_btn'])){
    $user_email = Student('email',$usar_id);
    $code = rand(9999,999999);

    $subject = "PSMS - Email Verification.";

    $message = "
    <html>
    <head>
    <title>Email Verification.</title>
    </head>
    <body>
    <p><b>This email contains HTML Tags!</b></p>
    <table>
    <tr>
    <th>Code</th>
    <th>".$code."</th>
    </tr>   
    </table>
    <p>Thanks.</p>
    </body>
    </html>
    ";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= 'From: <'.$user_email.'>' . "\r\n";

    $send_mail = mail($user_email,$subject,$message,$headers);
    if($send_mail == true){
        $stm = $pdo->prepare("UPDATE students SET email_code=? WHERE id=?");
        $stm->execute(array($code,$usar_id));

        $_SESSION['email_code_send'] = 1;
        $success = "Code send success.please check your registration Email";
    }
    else{
        $error = "Email Send Failed !";
    }

}
// verify Email Code
if(isset($_POST['st_email_verify_btn'])){
    $st_code = $_POST['st_email_code'];
    $db_code = Student('email_code',$usar_id);

    if(empty($st_code)){
        $error = "Email Code is Required";
    }
    else if($st_code !=$db_code){
        $error = "Email Code does't match";
    }
    else{
        $stm = $pdo->prepare("UPDATE students SET email_code=?,is_email_verified=? WHERE id=?");
        $stm->execute(array(null,1,$usar_id));

        unset($_SESSION['email_code_send']);
        $success = "your Email verify success!";

    }
}

// for mobile varification

if(isset($_POST['st_mobile_send_btn'])){
    $user_mobile = Student('mobile',$usar_id);
    $code = rand(9999,999999);

    // $msg = "Your Code is: ".$code;
    // $message = urlencode($msg);
    $message = urlencode("Your Code is: ".$code);
    $smsresult = file_get_contents("http://66.45.237.70/api.php?username=XXXXXXXXXX&password=XXXXXXX&number=88".$user_mobile."&message=$message");

    $stm = $pdo->prepare("UPDATE students SET mobile_code=? WHERE id=?");
    $stm->execute(array($code,$usar_id));

    $_SESSION['mobile_code_send'] = 1;
    $success = "Code send success.please check your register mobile number";
}
// verify Mobile Code
if(isset($_POST['st_mobile_verify_btn'])){
    $st_code = $_POST['st_mobile_code'];
    $db_code = Student('mobile_code',$usar_id);

    if(empty($st_code)){
        $error = "Mobile Code is Required";
    }
    else if($st_code !=$db_code){
        $error = "Mobile Code does't match";
    }
    else{
        $stm = $pdo->prepare("UPDATE students SET mobile_code=?,is_mobile_verified=? WHERE id=?");
        $stm->execute(array(null,1,$usar_id));

        unset($_SESSION['mobile_code_send']);
        $success = "your Mobile verify success!";

    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>

	<!-- META ============================================= -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<meta name="robots" content="" />
	
	<!-- DESCRIPTION -->
	<meta name="description" content="PSMS : Student Login" />
	
	<!-- OG -->
	<meta property="og:title" content="PSMS : Student varification" />
	<meta property="og:description" content="PSMS : Student varification" />
	<meta property="og:image" content="" />
	<meta name="format-detection" content="telephone=no">
	
	<!-- FAVICONS ICON ============================================= -->
	<link rel="icon" href="assets/images/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.png" />
	
	<!-- PAGE TITLE HERE ============================================= -->
	<title>PSMS : Student varification</title>
	
	<!-- MOBILE SPECIFIC ============================================= -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!--[if lt IE 9]>
	<script src="assets/js/html5shiv.min.js"></script>
	<script src="assets/js/respond.min.js"></script>
	<![endif]-->
	
	<!-- All PLUGINS CSS ============================================= -->
	<link rel="stylesheet" type="text/css" href="assets/css/assets.css">
	
	<!-- TYPOGRAPHY ============================================= -->
	<link rel="stylesheet" type="text/css" href="assets/css/typography.css">
	
	<!-- SHORTCODES ============================================= -->
	<link rel="stylesheet" type="text/css" href="assets/css/shortcodes/shortcodes.css">
	
	<!-- STYLESHEETS ============================================= -->
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link class="skin" rel="stylesheet" type="text/css" href="assets/css/color/color-1.css">
	
</head>
<body id="bg">
<div class="page-wraper">
	<!-- <div id="loading-icon-bx"></div> -->
	<div class="account-form">
		<div class="account-head" style="background-image:url(assets/images/background/bg2.jpg);">
			<a href="index.php"><img src="assets/images/logo-white-2.png" alt=""></a>
		</div>
		<div class="account-form-inner">
			<div class="account-container">
				<div class="heading-bx left">
					<h2 class="title-head">Student <span>varification</span></h2>
					<p><u><b><?php echo Student('name',$_SESSION['st_loggedin'][0]['id']);?></b></u> Please Verify Your account</p>
				</div>	

					<?php if(isset($error)) :?>
						<div class="alert alert-danger">
							<?php echo $error;?>
						</div>
					<?php endif ;?>

                    <?php if(isset($success)) :?>
					<div class="alert alert-success">
						<?php echo $success;?>
					</div>
					<?php endif;?>

                    <!-- varify status -->
                    <?php
                        $email_status = Student('is_email_verified',$_SESSION['st_loggedin'][0]['id']);
                        $mobile_status = Student('is_mobile_verified',$_SESSION['st_loggedin'][0]['id']);

                        if(isset($_SESSION['st_loggedin']) AND $email_status == 1 AND $mobile_status == 1){
                            header('location:deshbord/index.php');
                        }
                    ?>
                    <p>Email:<?php
                    if($email_status == 1){
                        echo '<span class="badge badge-success">verifed</span>';
                    }
                    else{
                        echo '<span class="badge badge-danger">Not verifed</span>';
                    }
                    ?></p>
                    <p>Mobile:<?php
                    if($mobile_status == 1){
                        echo '<span class="badge badge-success">verifed</span>';
                    }
                    else{
                        echo '<span class="badge badge-danger">Not verifed</span>';
                    }
                    ?></p>

                <!-- verify email -->
                <?php if(isset($_SESSION['email_code_send']) == 1) :?>

                <form class="contact-bx" method="POST">   
					<div class="row placeani">
						<div class="col-lg-12 m-b30">
							<button name="st_email_send_btn" type="submit" value="Submit" class="btn button-md">Resend Email Code</button>
						</div>
					</div>
				</form>

                <form class="contact-bx" method="POST"  >   
					<div class="row placeani">
						<div class="col-lg-12">
							<div class="form-group">
								<div class="input-group">
									<label>Type your code</label>
									<input name="st_email_code" type="text" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-lg-12 m-b30">
							<button name="st_email_verify_btn" type="submit" value="Submit" class="btn button-md">Verify Email</button>
						</div>
					</div>
				</form>
                <?php endif;?>

                <?php if($email_status != 1 AND !isset($_SESSION['email_code_send']))  :?>
                <form class="contact-bx" method="POST">   
					<div class="row placeani">
						<div class="col-lg-12 m-b30">
							<button name="st_email_send_btn" type="submit" value="Submit" class="btn button-md">click to verrify email</button>
						</div>
					</div>
				</form>
                <?php endif;?>

                <!--start for mobile varify -->

                <?php if(isset($_SESSION['mobile_code_send']) == 1) :?>

                <form class="contact-bx" method="POST">   
                    <div class="row placeani">
                        <div class="col-lg-12 m-b30">
                            <button name="st_mobile_send_btn" type="submit" value="Submit" class="btn button-md">Resend Mobile Code</button>
                        </div>
                    </div>
                </form>

                <form class="contact-bx" method="POST"  >   
                    <div class="row placeani">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <label>Type your code</label>
                                    <input name="st_mobile_code" type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 m-b30">
                            <button name="st_mobile_verify_btn" type="submit" value="Submit" class="btn button-md">Verify Mobile</button>
                        </div>
                    </div>
                </form>
                <?php endif;?>

                <?php if($mobile_status != 1 AND !isset($_SESSION['mobile_code_send']))  :?>
                <form class="contact-bx" method="POST">   
					<div class="row placeani">
						<div class="col-lg-12 m-b30">
							<button name="st_mobile_send_btn" type="submit" value="Submit" class="btn button-md">click to verrify mobile</button>
						</div>
					</div>
				</form>
                <?php endif;?>
                
                 <!--End for mobile varify -->
                
			</div>
		</div>
	</div>
</div>
<!-- External JavaScripts -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/vendors/bootstrap/js/popper.min.js"></script>
<script src="assets/vendors/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/vendors/bootstrap-select/bootstrap-select.min.js"></script>
<script src="assets/vendors/bootstrap-touchspin/jquery.bootstrap-touchspin.js"></script>
<script src="assets/vendors/magnific-popup/magnific-popup.js"></script>
<script src="assets/vendors/counter/waypoints-min.js"></script>
<script src="assets/vendors/counter/counterup.min.js"></script>
<script src="assets/vendors/imagesloaded/imagesloaded.js"></script>
<script src="assets/vendors/masonry/masonry.js"></script>
<script src="assets/vendors/masonry/filter.js"></script>
<script src="assets/vendors/owl-carousel/owl.carousel.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/contact.js"></script>
</body>

</html>