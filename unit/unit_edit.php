<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
  <?php include('../h.php'); ?>
</head>

<body>
  <?php include('../connectdb.php'); ?>
  <div class="container-fluid">
    <?php include('../navbar.php'); ?>
    <p></p>
    <div class="row">
      <div class="col-md-2">
        <!-- Left side column. contains the logo and sidebar -->
        <div class="color-login">
          <h6><i class="fas fa-user-circle"></i>&ensp;<a style="font-weight:bold;"><?php echo "ผู้ใช้"; ?></a><a style="color:#c92828;font-weight:bold;"><?php echo " : " . $_SESSION['user']; ?></a></h6>
          <h6><i class="fas fa-check-square"></i></i></i>&ensp;<a style="font-weight:bold;"><?php echo "ตำแหน่ง"; ?></a><a style="color:#1d4891;font-weight:bold;"><?php echo " : " . $_SESSION['posname']; ?></a></h6>
        </div>
        <?php include('../menu_left.php'); ?>
        <!-- Content Wrapper. Contains page content -->
      </div>
      <div class="col-md-10">
        <div class="card">
          <div class="card-body">
            <form action="unit_update.php" name="unitedit" method="post">
              <?php
              $id = isset($_GET['edit_id']) ? $_GET['edit_id'] : '';   //getรับค่าจาก show เป็น url

              if ($id != '') { // ถ้า id ไม่เท่ากับ ค่าว่าง
                $sql = "SELECT * FROM unit WHERE unit_id='" . $id . "'";
                $result = mysqli_query($conn, $sql); //คิวรี่ คำสั่งsql เก็บใน ตัวแปร result
                $row = mysqli_fetch_array($result); //เฟรดเก็บไว้ใน $row
                extract($row);
              }
              ?>
              <table class="table">
                <h4>แก้ไขหน่วย</h4>
                <tr hidden>
                  <td align="right">รหัสหน่วย : </td>
                  <td><input class="form-control mb-3" type="text" name="id" value="<?= $unit_id; ?>"></td>
                </tr>
                <tr>
                  <td align="right">&emsp;&emsp;&emsp;&emsp;&emsp;ชื่อหน่วย :</td>
                  <td><input class="form-control mb-3" style="width:220px" type="text" name="name" value="<?= $unit_name ?>"></td>
                </tr>
                <tr>
                  <td></td>
                  <td><input class="w3-button w3-red w3-round-xlarge" style="width:220px" type="submit" name="updateunit" value="บันทึก"></td>
                </tr>
              </table>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>