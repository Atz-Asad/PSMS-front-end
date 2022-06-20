<?php require_once('header.php');?>
<?php

    $user_id = $_SESSION['st_loggedin'][0]['id'];

    $stm=$pdo->prepare("SELECT * FROM students WHERE id=?");
    $stm->execute(array($user_id));
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);

	
    $name = $result[0]['name'];
    $email = $result[0]['email'];
    $email_status = $result[0]['is_email_verified'];
    $mobile = $result[0]['mobile'];
    $mobile_status  = $result[0]['is_mobile_verified'];
    $father_name = $result[0]['father_name'];
    $father_mobile = $result[0]['father_mobile'];
    $mother_name = $result[0]['mother_name'];
    $gender = $result[0]['gender'];
    $brithday = $result[0]['brithday'];
    $address = $result[0]['address'];
    $roll = $result[0]['roll'];
    $carrent_class = $result[0]['carrent_class'];
    $registration_date = $result[0]['registration_date'];
    $photo = $result[0]['photo'];


	// update student profile
	if(isset($_POST['profile_update_btn'])){
		$name = $_POST['name'];
		$father_name = $_POST['father_name'];
		$father_mobile = $_POST['father_mobile'];
		$mother_name = $_POST['mother_name'];
		$gender = $_POST['gender'];
		$brithday = $_POST['brithday'];
		$address = $_POST['address'];
		$photo_name = $_FILES['photo']['name'];

		if(empty($father_name)){
			$error = "Father name is Required";
		}
		else if(empty($father_mobile)){
			$error = "Father mobile is Required";
		}
		else{

			if(!empty($photo_name)){
				$target_dir = "assets/images/students/";
				$target_file = $target_dir . basename($_FILES["photo"]["name"]);
				$extention = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

				if($extention != 'png' AND $extention != 'jpg'){
					$error = "photo must jpg or png";
				}
				else{
					$temp_name = $_FILES["photo"]["tmp_name"];
					$final_path =  $target_dir . "user_id". $user_id.".".$extention;
					move_uploaded_file ($temp_name, $final_path);
				}
			}
			else{
				$final_path =  Student('photo',$user_id);
			}
			$update = $pdo->prepare("UPDATE students SET name=?,father_name=?,father_mobile=?,mother_name=?,gender=?,brithday=?,address=?,photo=? WHERE id=? ");
			$update->execute(array(
				$name,
				$father_name,
				$father_mobile,
				$mother_name,
				$gender,
				$brithday,
				$address,
				$final_path,
				$user_id
			));
			$success = "profile update sucessfully!";

		}
	}

?>
<!--Main container start -->
<main class="ttr-wrapper">
	<div class="container-fluid">
		<div class="db-breadcrumb">
			<h4 class="breadcrumb-title">UPDATE Profile</h4>
			<ul class="db-breadcrumb-list">
				<li><a href="#"><i class="fa fa-home"></i>Home</a></li>
				<li>UPDATE Profile</li>
			</ul>
		</div>	
		<div class="row">
			<!-- Your Profile Views Chart -->
			<div class="col-lg-12 m-b30">
				<div class="widget-box">
					<div class="wc-title">
						<h4>Update Profile</h4>
					</div>
					<div class="widget-inner">
						<form class="edit-profile m-b30" method="POST" action="" enctype="multipart/form-data">
							<div class="">
								<?php if(isset($error)) :?>
									<div class="alert alert-danger"><?php echo $error ;?></div>
								<?php endif ;?>

								<?php if(isset($success)) :?>
									<div class="alert alert-success"><?php echo $success ;?></div>
								<?php endif ;?>

								<div class="form-group row">
									<div class="col-sm-10  ml-auto">
										<h3>Your Details</h3>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Name:</label>
									<div class="col-sm-7">
										<input class="form-control" name="name" type="text" value="<?php echo $name ;?>">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Email:</label>
									<div class="col-sm-7">
										<input class="form-control" type="email" value="<?php echo $email ;?> "readonly>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Mobile Nmber:</label>
									<div class="col-sm-7">
										<input class="form-control" type="text" value="<?php echo $mobile ;?>"readonly>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Father's Name:</label>
									<div class="col-sm-7">
										<input class="form-control"  name="father_name" type="text" value="<?php echo $father_name ;?>">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Father's Mobile:</label>
									<div class="col-sm-7">
										<input class="form-control"  name="father_mobile" type="text" value="<?php echo $father_mobile ;?>">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">mother's Name:</label>
									<div class="col-sm-7">
										<input class="form-control"  name="mother_name" type="text" value="<?php echo $mother_name ;?>">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Gender:</label>
									<div class="col-sm-7">
										<label><input 
										<?php
											if($gender =='Male'){echo "checked";}
										?>											
										type="radio" value="Male" name="gender" id=""> Male</label>
										<br>
										<label><input 
										<?php
											if($gender =='Female'){echo "checked";}
										?>
										type="radio" value="Female" name="gender" id=""> Female</label>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Brithday:</label>
									<div class="col-sm-7">
										<input class="form-control"  name="brithday" type="date" value="<?php echo $brithday ;?>">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Address:</label>
									<div class="col-sm-7">
										<input class="form-control"  name="address" type="text" value="<?php echo $address ;?>">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Photo:</label>
									<div class="col-sm-7">
										<?php if($photo != null) :?>
										<div class="profile_photo">
											<a target="_blank" href="<?php echo $photo ;?>">
												<img style="height:100px;width:auto;" src="<?php echo $photo ;?>"> 
											</a>
										</div>
										<mark><smal>If won't change photo,skip the photo field.<smal></mark>
										<?php endif ;?>
										<input class="form-control" type="file" name="photo">
									</div>
								</div>
							</div>
							<div class="">
								<div class="">
									<div class="row">
										<div class="col-sm-2">												
										</div>
										<div class="col-sm-7">
											<button type="submit" name="profile_update_btn" class="btn">Save changes</button>
											<button type="reset" class="btn-secondry">Cancel</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Your Profile Views Chart END-->
		</div>
	</div>
</main>

<?php require_once('footer.php');?>