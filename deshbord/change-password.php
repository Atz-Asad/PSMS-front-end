<?php require_once('header.php');?>
<?php
if(isset($_POST['change_btn'])){
    $carrrent_password = $_POST['carrrent_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // password same
    $user_id = $_SESSION['st_loggedin'][0]['id'];
    $db_password = Student('password',$user_id);

    if(empty($carrrent_password)){
        $error = "corrent password is Requrired";
    }
    else if(empty($new_password)){
        $error = "New password is Requrired";
    }
    else if(empty($new_password)){
        $error = "confirm New password is Requrired";
    }
    else if($db_password != SHA1($carrrent_password)){
        $error = "Carrent password is wrong";
    }
    else if($new_password != $confirm_new_password){
        $error = "new password and confirm new password does'nt macth";
    }
    else{
        $update = $pdo->prepare("UPDATE students SET password =? WHERE id=?");
        $update->execute(array(SHA1($confirm_new_password),$user_id));

        $success = "password change succefully";
    }

}


?>

<!--Main container start -->
<main class="ttr-wrapper">
    <div class="container-fluid">
        <div class="db-breadcrumb">
            <h4 class="breadcrumb-title">Profile</h4>
            <ul class="db-breadcrumb-list">
                <li><a href="#"><i class="fa fa-home"></i>Home</a></li>
                <li>Change password</li>
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

                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="carrrent_password">Carrrent Password</label>
                                <input type="password" name="carrrent_password" class="form-control" id="carrrent_password">
                            </div>
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" class="form-control" id="new_password">
                            </div>
                            <div class="form-group">
                                <label for="confirm_new_password">Confirm new Password</label>
                                <input type="password" name="confirm_new_password" class="form-control" id="confirm_new_password">
                            </div>
                            <div class="form-group">
                                <input type="submit" name="change_btn" class=" btn btn-success" value="Change password">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card END -->
        
    </div>
</main>

<?php require_once('footer.php');?>