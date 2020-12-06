<?php session_start();
include('../home.php');
?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv=Content-Type content="text/html; charset=utf-8">
  <link rel="stylesheet" href="../../styletable.css">
  <link rel="stylesheet" href="../../style.css">
  <script type="text/javascript">
    function addSubmit() {
      document.colorform.action = "color_insert.php";
    }

    function backSubmit() {
      document.getElementById("colorform").action = "idprocheck.php";
    }
  </script>
</head>
<title>Product</title>

<body>
<?php include('../connectdb.php'); ?>
    <?php include('../navbar.php'); ?>
  <div class="container-fluid">
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
            <form action="#" id="colorform" name="colorform" method="post" align="center">
              <center>
                <h3>เพิ่มสี</h3>
              </center>
              <?php
              $idproduct = isset($_GET['p_id']) ? $_GET['p_id'] : '';
              $idcategory = isset($_GET['c_id']) ? $_GET['c_id'] : '';
              ?>
              <table align="center">
                <tr>
                  <td>รหัสสินค้า : </td>
                  <td>
                    <input type="hidden" name="idproduct" value="<?php echo $idproduct; ?>">
                    <?php echo $idproduct; ?>
                  </td>
                </tr>

                <tr>
                  <td>รหัสประเภท : </td>
                  <td>
                    <input type="hidden" name="category" value="<?php echo $idcategory; ?>">
                    <?php echo $idcategory; ?>
                  </td>
                </tr>

                <td>รหัสสี : </td>
                <td>
                  <?php
                  $idsql = "SELECT concat('COL',LPAD(ifnull(SUBSTR(max(color_id),4,7),'0')+1,4,'0')) as COLOR_ID FROM color";
                  $conn->query($idsql) == true;
                  $resultid = mysqli_query($conn, $idsql);
                  $row = mysqli_fetch_array($resultid);
                  $idcolor = $row['COLOR_ID'];
                  ?>
                  <?php echo $idcolor; ?>
                </td>
                </tr>

                <tr>
                  <td>ชื่อสี : </td>
                  <td><input type="text" name="namecolor" value="<?php if (isset($_POST['namecolor'])) echo $_POST['namecolor']; ?>"></td>
                </tr>

              </table>
              <center>
                <input type="submit" name="cinsertback" value="<< Back" onclick="backSubmit();" style="background-color:#ff0000;">
                <input type="submit" name="submitcolor" value="เพิ่มข้อมูล" onclick="addSubmit();">

              </center>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


  <?php
  if (isset($_POST['submitcolor'])) {
    $namecolor = $_POST['namecolor'];
    $idproduct = $_POST['idproduct'];
    $idcategory = $_POST['category'];

    $idsql = "SELECT concat('COL',LPAD(ifnull(SUBSTR(max(color_id),4,7),'0')+1,4,'0')) as COLOR_ID FROM color";
    $conn->query($idsql) == true;
    $resultid = mysqli_query($conn, $idsql);
    $row = mysqli_fetch_array($resultid);
    $idcolor = $row['COLOR_ID'];

    $sql = "SELECT * FROM color WHERE color_name = '$namecolor' AND product_id = '$idproduct' AND cgr_color_id = '$idcategory'";
    $query = mysqli_query($conn, $sql);
    $row  = mysqli_fetch_array($query);

    if ($row['product_id'] == $idproduct && $row['cgr_color_id'] == $idcategory && $row['color_name'] == $namecolor) {
      echo "<script>";
      echo "alert('สีซ้ำกรุณากรอกใหม่อีกครั้ง !!');";
      echo "window.location.href='javascript:history.back(1)';";
      echo "</script>";
    } else if ($namecolor == '') {
      echo "<script>";
      echo "alert('กรุณากรอกสีใหม่อีกครั้ง !!');";
      echo "window.location.href='javascript:history.back(1)';";
      echo "</script>";
    } else {
      $sql = "INSERT INTO color (color_id,color_name,product_id,cgr_color_id) VALUES ('$idcolor','$namecolor','$idproduct','$idcategory')";
      if ($conn->query($sql) == true) {
        echo "<script>"; //คำสั่งสคิป
        echo "alert('บันทึกสำเร็จ!');"; //แสดงหน้าต่างเตือน
        echo "window.location.href='color_show.php?p_id=$idproduct&c_id=$idcategory';"; //แสดงหน้าก่อนนี้
        echo "</script>";
      } else {
        echo "<script>"; //คำสั่งสคิป
        echo "alert('กรุณากรอกข้อมูลใหม่อีกครั้ง !!');"; //แสดงหน้าต่างเตือน
        echo "window.location.href='javascript:history.back(1)';"; //แสดงหน้าก่อนนี้
        echo "</script>";
      }
    }
  }

  ?>

</body>

</html>