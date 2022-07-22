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

?>
<!--Main container start -->
<main class="ttr-wrapper">
    <div class="container-fluid">
        <div class="db-breadcrumb">
            <h4 class="breadcrumb-title">Profile</h4>
            <ul class="db-breadcrumb-list">
                <li><a href="index.php"><i class="fa fa-home"></i>Home</a></li>
                <li>Profile</li>
            </ul>
        </div>	
        <!-- Card -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <?php if(isset($error)) :?>
                        <div class="alert alert-danger">
                           <?php  echo $error ;?>
                        </div>
                        <?php endif ;?>

                        <?php if(isset($success)) :?>
                        <div class="alert alert-success">
                           <?php  echo $success ;?>
                        </div>
                        <?php endif ;?>
                        <table class="table">
                            <tr>
                                <td>Name:</td>
                                <td><?php echo $name;?></td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td><?php echo $email;
                                    if($email_status == 1){
                                        echo '  <i style="color:green" class="fas fa-check-circle"></i>';
                                    }                                
                                ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Mobile:</td>
                                <td><?php echo $mobile;
                                     if($mobile_status == 1){
                                        echo '  <i style="color:green" class="fas fa-check-circle"></i>';
                                    }                                 
                                ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Father's name:</td>
                                <td><?php echo $father_name;?></td>
                            </tr>
                            <tr>
                                <td>Father's mobile:</td>
                               <td><?php echo $father_mobile;?></td>
                            </tr>
                            <tr>
                                <td>Mother's name:</td>
                               <td><?php echo $mother_name;?></td>
                            </tr>
                            <tr>
                                <td>Gender:</td>
                               <td><?php echo $gender;?></td>
                            </tr>
                            <tr>
                                <td>brithday:</td>
                               <td><?php echo $brithday;?></td>
                            </tr>
                            <tr>
                                <td>Address:</td>
                               <td><?php echo $address;?></td>
                            </tr>
                            <tr>
                                <td>Roll:</td>
                               <td><?php echo $roll;?></td>
                            </tr>
                            <tr>
                                <td>Carrent Class:</td>
                               <td><?php echo $carrent_class;?></td>
                            </tr>
                            <tr>
                                <td>Registration date:</td>
                               <td><?php echo $registration_date;?></td>
                            </tr>
                            <tr>
                                <td>Profile Photo:</td>
                                <td>
                                    <?php if($photo !=null):?>
                                        <img style="width:auto;height:100px;" src="<?php echo $photo ;?>">
                                    <?php else :?>
                                        <img alt="" src="assets/images/testimonials/pic3.jpg" width="32" height="32">
                                    <?php endif;?>
                                </td>
                            </tr>
                            <tr>
                                <td><a href="edit-profile.php" class="btn btn-warning">Edit profile</a></td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card END -->
        
    </div>
</main>

<?php require_once('footer.php');?>