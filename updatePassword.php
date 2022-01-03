<?php
    session_start();
    require_once("db.php");

    $error = "";
    $success = "";

    if(!$_SESSION["username"]){
        header("Location: login.php");
    }

    $username = $_SESSION["username"];
    $result = get_info_nhanvien($username);
    if($result['code'] == 0){
        $data = $result['data'];
    }else{
        $error = $result['message'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thay đổi mật khẩu</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <style>
        .nav-item .dropdown{
            margin-right: 80px;
        }
        .nav-item .dropdown-toggle .dropdown-menu{
            max-width: 50px;
        }
        .back {
            font-size: 30px;
            color: red;
        }
        a i{
            font-size: 30px;
            color: red;
        }
	    .card{
            width: 50%;
            height: 100%;
            background:rgb(200,100,0);
            border-radius: 16px
        }
        h3{
            text-align: center;
        }
        .input-group{
            margin-top: 35px;
        }
        .button-submit{
            display: flex;
            justify-content: center;
        }
        body{
            background: rgb(229,229,229);
        }
    </style>
</head>
<body>
    <?php 
        $resultNav = get_info_nhanvien($username);
        if($resultNav['code'] == 0){
            $data = $resultNav['data'];
        }
    ?>
    <nav class="navbar navbar-expand-sm bg-primary justify-content-between">
        <div class="nav-item">
            <h1 class="nav-link">Trang nhân viên</h1>
        </div>
        <div class="nav-item">
            <div class="dropdown">
                <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">
                    <?= $data['name'] ?>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="logout.php">Đăng xuất</a>
                </div>
            </div>
        </div>
    </nav>
    
    <?php 
        if(isset($_POST['update'])){
            $newpwd = $_POST['newpwd'];
            $cpwd = $_POST['cpwd'];
            $oldpwd = $_POST['oldpwd'];
            $resultChangePWD = change_password($oldpwd, $newpwd, $cpwd, $username);
            if($resultChangePWD['code'] == 0){
                $success = $resultChangePWD['message'];
                $newpwd = "";
                $cpwd = "";
                if($data['status'] == 0){
                    $resultChangeStatus = update_status_staff($data['name']);
                    if($resultChangeStatus['code'] != 0){
                        $error = $resultChangeStatus['message'];
                        header("Location: index.php");
                    }
                }

            }else{
                $error = $resultChangePWD['message'];
            }
        }
    ?>
    <a class="back" style="text-decoreation: none; margin: 10px;" href="index.php"><i class="fas fa-arrow-circle-left"></i></a>
    <div class="container">    
        <div class="d-flex justify-content-center">
            <div class="card">
                <div class="card-body">
                    <form novalidate method="post">
                        <h3>THAY ĐỔI MẬT KHẨU</h3>
                        
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="oldpwd" class="form-control" value="<?php if(!empty($oldpwd)) echo $oldpwd; ?>"  placeholder="Nhập mật khẩu cũ" required> 
                        </div>

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="newpwd" class="form-control" value="<?php if(!empty($newpwd)) echo $newpwd; ?>"  placeholder="Nhập mật khẩu mới" required> 
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="cpwd" class="form-control" value="<?php if(!empty($cpwd)) echo $cpwd; ?>" placeholder="Nhập lại mật khẩu mới" required>
                        </div>
                        <p id="errors" style="text-align: center; font-weight: bold; font-size:20px; color: red;">
                            <?php
                                if(!empty($error)){
                                    echo "<div class='alert alert-danger'>$error</div>";
                                }else if(!empty($success)){
                                    echo "<div class='alert alert-success'>$success</div>";
                                }
                            ?>
                        </p>
                        <div class="form-group button-submit">
                            <input class="btn btn-success" type="submit" style="width: 90px; margin-top: 35px; float: right;" name="update" value="Thay đổi">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
