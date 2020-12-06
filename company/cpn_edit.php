<?php if (!isset($_SESSION)) {
    session_start();
} ?>
<!DOCTYPE html>
<html>

<head>
    <?php include('../h.php'); ?>
    <script type="text/javascript">
        function addTellist() {
            var selectBox = document.getElementById("tel_list");
            for (var i = 0; i < selectBox.options.length; i++)
                selectBox.options[i].selected = true;
            document.cpnedit.submit();
        }

        $(function() {
            $('#AddTel').click(function() {
                var success = 0;
                var addBox = document.getElementById("tel");
                //alert(addBox.value);
                selectBox = document.getElementById("tel_list");

                for (var j = 0; j < selectBox.options.length; j++)
                    selectBox.options[j].selected = true;

                if (j == '0') // ยังไม่มีเบอร์
                {
                    addTellist();
                } else // มีเบอร์แล้ว
                {

                    for (var i = 0; i < selectBox.options.length; i++) {
                        //alert(selectBox.options[i].value + '/' + addBox.value);

                        if (selectBox.options[i].value != addBox.value) {
                            success = 1;
                        } else {
                            Swal.fire('ไม่สามารถเพิ่มเบอร์โทรได้', 'กรุณาตรวจสอบเนื่องจากเบอร์ซ้ำกัน !', 'error');
                            success = 0;
                            break;
                        }
                    }
                    //alert(success);

                    if (success == 1) {
                        addTellist();
                    }
                }
            });
        });

        function removeTellist() {
            selectBox = document.getElementById("tel_list");
            for (var i = 0; i < selectBox.options.length; i++)
                if (selectBox.options[i].selected)
                    selectBox.remove(i);
        }

        function submitForm() {
            var selectBox = document.getElementById("tel_list");
            for (var i = 0; i < selectBox.options.length; i++)
                selectBox.options[i].selected = true;
            document.getElementById("cpnedit").action = "cpn_update.php";
        }
    </script>

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
                        $submit = $_GET['submit'];

                        if ($submit == 'Edit') {
                            $sql = "SELECT * FROM company WHERE cpn_id = '" . $id . "'";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_array($result);

                            $province = $row['province_id'];
                            $amphur = $row['amphur_id'];
                            $district = $row['district_id'];
                            $zipcode = $row['zipcode_id'];
                        ?>
                            <form action="#" name="cpnedit" id="cpnedit" method="post" enctype="multipart/form-data">

                                <h4>แก้ไขบริษัทคู่ค้า</h4>
                                <?php

                                if ($id != '') { // ถ้า id ไม่เท่ากับ ค่าว่าง
                                    $sqlcompany = "SELECT * FROM company WHERE cpn_id='" . $id . "'";
                                    $query = mysqli_query($conn, $sqlcompany); //คิวรี่ คำสั่งsql เก็บใน ตัวแปร result
                                    $rowcpn = mysqli_fetch_array($query); //เฟรดเก็บไว้ใน $row
                                    $cpn_name = $rowcpn['cpn_name'];
                                    extract($rowcpn);
                                } ?>
                                <table class="table">

                                    <tr hidden>
                                        <td align="right">รหัสบริษัทคู่ค้า : </td>
                                        <td><input class="form-control" type="text" name="id" value="<?= $cpn_id; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td align="right">ชื่อบริษัทคู่ค้า :</td>
                                        <td><input class="form-control" style="width:300px" type="text" name="name" value="<?php if (isset($_POST['name'])) {
                                                                                                                                echo $_POST['name'];
                                                                                                                            } else {
                                                                                                                                echo $cpn_name;
                                                                                                                            }  ?>"></td>
                                    </tr>
                                    <tr>
                                        <td align="right">เบอร์ :</td>
                                        <td>
                                            <div class="form-inline">
                                                <input class="form-control mb-2" style="width:210px" type="text" id="tel" name="tel" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="10">&ensp;
                                                <input class='btn btn-info btn-xs mb-2' style="width:80px" type="button" id="AddTel" name="add" value="เพิ่ม">
                                            </div>
                                            <div class="form-inline">
                                                <select id="tel_list" name="tel_list[]" style="width:210px" class="form-control" multiple>
                                                    <?php
                                                    $sqltel = "SELECT * FROM tel_company WHERE cpn_id='" . $id . "'";
                                                    $resulttel = mysqli_query($conn, $sqltel);
                                                    if (!isset($_POST['tel_list']) && !isset($_POST['tel']))                    //เช็คPOST เมื่อเข้าเว็ปมาหน้าแรก
                                                        while ($row = mysqli_fetch_array($resulttel)) {
                                                            print "<option>" . $row['cpn_tel'] . "</option>";
                                                        }

                                                    if (isset($_POST['tel_list']))                                              //เช็ค tel_list ว่ามีค่าที่เก็บไว้หรือป่าว เมื่อทำการแก้ไข
                                                        foreach ($_POST['tel_list'] as $rowtel)                                    //ใช้foreach วนค่า tel_list แล้วเก็บใส่ตัวแปร rowtel
                                                        {
                                                            print "<option>" . $rowtel . "</option>";                                  //แสดง ค่าที่เก็บใน rowtel
                                                        }

                                                    if (isset($_POST['tel'])) {
                                                        if ($_POST['tel'] != '') {  //เช็คค่า tel ว่ามีหรือไม่  ถ้าเป็นค่าว่างจะไม่แสดงในoptionได้ หากเป็นค่าไม่ว่างมีการกรอกจะสามารถแสดงออกได้
                                                            print "<option>" . $_POST['tel'] . "</option>";
                                                        } else {
                                                            echo "<script>"; //คำสั่งสคิป
                                                            echo "alert('กรุณากรอกเบอร์โทรศัพท์!');"; //แสดงหน้าต่างเตือน
                                                            echo "</script>";
                                                        }
                                                    }
                                                    ?>
                                                </select>&ensp;
                                                <input class='btn btn-info btn-xs mb-2' style="width:80px" type="button" name="remove" value="ลบ" onclick="removeTellist();">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">อีเมลล์ :</td>
                                        <td><input class="form-control" style="width:300px" type="text" name="email" value="<?php if (isset($_POST['email'])) {
                                                                                                                                echo $_POST['email'];
                                                                                                                            } else {
                                                                                                                                echo $cpn_email;
                                                                                                                            } ?>"></td>
                                    </tr>
                                    <tr>
                                        <td align="right">เลขที่อยู่ :</td>
                                        <td><input class="form-control" style="width:300px" type="text" name="address" value="<?php if (isset($_POST['address'])) {
                                                                                                                                    echo $_POST['address'];
                                                                                                                                } else {
                                                                                                                                    echo $cpn_address;
                                                                                                                                } ?>"></td>
                                    </tr>
                                    <tr>
                                        <td align="right">จังหวัด : </td>
                                        <td colspan="3">
                                            <div>
                                                <select class="form-control custom-select" style="width:300px" name="province" id="province" data-fv-notempty='true' data-fv-notempty-message='Please Enter...' onchange="data_show(this.value,'amphur','');document.getElementById('district').innerHTML = '<option value=>กรุณาเลือกอำเภอ . . .</option>';">
                                                    <option value=''>กรุณาเลือกจังหวัด . . .</option>
                                                    <?php
                                                    $sql = "SELECT * FROM tbl_provinces ORDER BY province_id ASC";
                                                    $result = mysqli_query($conn, $sql);
                                                    while ($row = mysqli_fetch_array($result)) {
                                                    ?>
                                                        <option value="<?php echo $row['province_id']; ?>" <?php if ($row['province_id'] == $province) echo 'selected'; ?>><?php echo $row['province_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="right">อำเภอ : </td>
                                        <td>
                                            <div>
                                                <select class="form-control custom-select" style="width:300px" name="amphur" id="amphur" onchange="data_show(this.value,'district','');" data-fv-notempty='true'>
                                                    <option value=''>เลือกอำเภอ . . .</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="right">ตำบล : </td>
                                        <td>
                                            <div>
                                                <select class="form-control custom-select" style="width:300px" name="district" id="district" onchange="data_show(this.value,'zipcode','');" data-fv-notempty='true'>
                                                    <option value=''>เลือกตำบล . . .</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">รหัสไปษณีย์ : </td>
                                        <td colspan="3">
                                            <div>
                                                <select class="form-control custom-select" style="width:300px" name="zipcode" id="zipcode" data-fv-notempty='true'>
                                                    <option value=''>เลือกรหัสไปษณีย์ . . .</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td colspan="2">
                                            <input class="w3-button w3-black w3-round-xlarge" style="width:220px;" type="submit" value="ย้อนกลับ" onclick="document.cpnedit.action='cpn_show.php'">
                                            <input class="w3-button w3-red w3-round-xlarge" type="submit" name="update" value="บันทึก" style="width:220px" onclick="submitForm();">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script type="text/javascript">
        function data_show(select_id, result, point_id) {
            var xhttp = new XMLHttpRequest();
            //alert("data2.php?result="+result+"&select_id="+select_id);

            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById(result).innerHTML = this.responseText;
                }
            };

            xhttp.open("GET", "../js/action.php?result=" + result + "&select_id=" + select_id + "&point_id=" + point_id, true);
            xhttp.send();
        }

        if ('<?= $submit ?>' == 'Edit') {
            window.onLoad = data_show('<?= $province ?>', 'amphur', '<?= $amphur ?>');
            window.onLoad = data_show('<?= $amphur ?>', 'district', '<?= $district ?>');
            window.onLoad = data_show('<?= $district ?>', 'zipcode', '<?= $zipcode ?>');
        }
    </script>
<?php } ?>
</body>

</html>