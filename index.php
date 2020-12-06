<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<?php include('h.php'); ?>
</head>
  <body>
  <?php include('navbar.php'); ?>
    <div class="container-fluid">
      <p></p>
      <div class="row">
        <div class="col-md-2">
          <div class="color-login">
          <h6><i class="fa fa-user-circle" aria-hidden="true"></i>&ensp;<a style="font-weight:bold;"><?php echo "ผู้ใช้"; ?></a><a style="color:#c92828;font-weight:bold;"><?php echo " : " . $_SESSION['user']; ?></a></h6>
          <h6><i class="fas fa-check-square"></i>&ensp;<a style="font-weight:bold;"><?php echo "ตำแหน่ง"; ?></a><a style="color:#1d4891;font-weight:bold;"><?php if(isset($_SESSION['posname']) != '') {echo " : " . $_SESSION['posname']; }else{echo  " : ลูกค้า";} ?></a></h6>
          </div>
          <?php include('menu_left.php'); ?>
          <!-- Content Wrapper. Contains page content -->
        </div>
        <div class="col-md-10">
        <div class="jumbotron">
            <h3 class="display-4">หน้าหลัก</h3>
            <p class="lead">
              This project is for testing the ability of students in the Department of Computer Engineering only..</p>
            <hr class="my-4">
            <p>จัดทำโดย : &emsp;นาย ณัฐพล อนันทเดช ภาควิชา วิศวกรรมคอมพิวเตอร์</p>
            <p class="lead">
              <a class="btn btn-primary btn-lg" href="#" role="button">เข้าสู่เว็ปไซต์</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>