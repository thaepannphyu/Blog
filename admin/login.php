<?php
include(__DIR__ . '/../app/User.php');
use app\User;
session_start();
 print_r(isset($_SESSION['user_id']));
 if(isset($_POST["password"]) && isset($_POST["email"])){
    $password=$_POST["password"];
    $email=$_POST["email"];
    $user=new User;
    $user=$user->login($email,$password);
    print_r($user);
 }
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>AdminLTE 3 | Starter</title>

    <!-- Google Font: Source Sans Pro -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css" />
  </head>
  <body class="hold-transition sidebar-mini">
    <div class="wrapper">
    
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper ml-0 ">
        <!-- Horizontal Form -->
        <div class="content">
          <div><?php if(isset($_SESSION["alert"]['msg'])){ echo  $_SESSION["alert"]['msg'];} ?></div>
        <div style="height:600px;" class="container-fluid justify-content-center d-flex">
                    <div class="card w-50 my-auto  mx-auto  card-info">
                    <div class="card-header">
                        <h3 class="card-title">Login Form</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form class="form-horizontal my-auto" action="" method="POST">
                        <div class="card-body">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                            <input type="email" name="email" class="form-control" id="inputEmail3" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                            <input type="password"  name="password"  class="form-control" id="inputPassword3" placeholder="Password">
                            </div>
                        </div>
                        </div>
                         <!-- /.card-body -->
                        <div class="card-footer">
                        <button type="submit" class="btn btn-info">log in</button>
                        <button type="submit" class="btn btn-default float-right">Cancel</button>
                        </div>
                        <!-- /.card-footer -->
                        </div>

                    </form>
                    </div>
                    <!-- /.card -->

        <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
  </body>
</html>
