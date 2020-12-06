<?php session_start();
include('../home.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv=Content-Type content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../styletable.css">
    <link rel="stylesheet" href="../../style.css">
    <script type="text/javascript">
        function submitupdate() {

            document.editform.action = "color_edit.php";
        }

        function backSubmit() {
            document.editform.action = "idprocheck.php"
        }
    </script>
    <title>Product</title>
</head>

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
                        <?php
                        $id = isset($_GET['edit_id']) ? $_GET['edit_id'] : '';
                        $idproduct = isset($_GET['p_id']) ? $_GET['p_id'] : '';

                        if ($id != '') {
                            $sql = "SELECT * FROM color WHERE color_id='" . $id . "'";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_array($result);

                            $idcolor = $row['color_id'];
                            $name = $row['color_name'];
                            $idcategory = $row['cgr_color_id'];
                        ?>
                            <form action="#" name="editform" id="editform" method="post">
                                <table align="center">
                                    <center>
                                        <h3>แก้ไขสินค้า</h3>
                                    </center>
                                    <tr>
                                        <td align="right">รหัสสินค้า : </td>
                                        <td><input type="hidden" name="idproduct" value="<?php echo $idproduct; ?>"><?php echo $idproduct; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="right">รหัสประเภท : </td>
                                        <td><input type="hidden" name="idcategory" value="<?php echo $idcategory; ?>"><?php echo $idcategory; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="right">รหัสสี : </td>
                                        <td><input type="hidden" name="idcolor" value="<?php echo $idcolor; ?>"><?php echo $idcolor; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="right">ชื่อสี : </td>
                                        <td><input type="text" name="name" value="<?php if (isset($_POST['name'])) {
                                                                                        echo $_POST['name'];
                                                                                    } else {
                                                                                        echo $name;
                                                                                    } ?>">
                                        </td>
                                    </tr>
                                <?php } ?>
                                </table>
                                <center>
                                    <input type="submit" name="cshowback" value="<< Back" onclick="backSubmit();" style="background-color:#ff0000;">
                                    <input type="submit" name="update" onclick="submitupdate();" value="อัพเดทข้อมูล">
                                </center>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <?php
    if (isset($_POST['update'])) {
        $idcolor = $_POST['idcolor'];
        $name = $_POST['name'];
        $idproduct = $_POST['idproduct'];
        $idcategory = $_POST['idcategory'];

        $sql = "SELECT * FROM color WHERE color_name = '$name' AND product_id = '$idproduct' AND cgr_color_id = '$idcategory'";
        $query = mysqli_query($conn, $sql);
        $row  = mysqli_fetch_array($query);

        if ($row['color_name'] == $name && $row['product_id'] == $idproduct && $row['cgr_color_id'] == $idcategory) {
            echo "<script>";
            echo "alert('ชื่อสินค้าซ้ำกรุณากรอกใหม่อีกครั้ง !!');";
            echo "window.location.href='javascript:history.back(1)';";
            echo "</script>";
        } elseif ($name == '') {
            echo "<script>";
            echo "alert('กรุณากรอกชื่อสินค้าใหม่อีกครั้ง !!');";
            echo "window.location.href='javascript:history.back(1)';";
            echo "</script>";
        } else {
            $sql = "UPDATE color SET color_id='" . $idcolor . "' , color_name='" . $name . "' WHERE color_id='" . $idcolor . "'";
            if ($conn->query($sql) == true) {
                echo "<script>";
                echo "alert('UPDATE สำเร็จ!');";
                echo "window.location.href='color_show.php?p_id=$idproduct';";
                echo "</script>";
            } else {
                echo "<script>";
                echo "alert('ERROR!');";
                echo "window.location.href='javascript:history.back(1)';";
                echo "</script>";
            }
        }
    }
    ?>
</body>

</html>