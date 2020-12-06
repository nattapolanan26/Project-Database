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
            document.cpnform.submit();
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
            document.getElementById('cpnform').action = "cpn_insert.php"
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
                        <h5 class="card-header"><i class="fas fa-user-circle"></i> เพิ่มพนักงาน</h5>
                        <p></p>
                        <form action="#" id="cpnform" name="cpnform" method="post" method="post" enctype="multipart/form-data">
                            <h4>เพิ่มบริษัทคู่ค้า</h4>
                            <table class="table">
                                <tr hidden>
                                    <td align="right">รหัสบริษัทคู่ค้า :</td>
                                    <td>
                                        <input type="text" style="width:100px" name="cpnid" class="form-control" value="<?php $cpnid = "SELECT concat('CPN-',LPAD(ifnull(SUBSTR(max(cpn_id),5,7),'0')+1,3,'0')) as CPN_ID FROM company";
                                                                                                                        $resultid = mysqli_query($conn, $cpnid);
                                                                                                                        $row = mysqli_fetch_array($resultid);
                                                                                                                        echo $cpnid = $row['CPN_ID']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">ชื่อบริษัทคู่ค้า :</td>
                                    <td>
                                        <input type="text" name="name" class="form-control" style="width:300px" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">เบอร์ :</td>
                                    <td colspan="3">
                                        <div class="form-inline">
                                            <input type="text" class="form-control mb-2" style="width:210px" id="tel" name="tel" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="10" />&ensp;
                                            <input class='btn btn-info btn-xs mb-2' style="width:80px" type="button" id="AddTel" name="add" value="เพิ่ม">&ensp;
                                        </div>
                                        <div class="form-inline">
                                            <select id="tel_list" name="tel_list[]" style="width:210px" class="form-control" multiple>
                                                <?php
                                                if (isset($_POST['tel_list'])) {
                                                    foreach ($_POST['tel_list'] as $rowtel) { //วนค่าเบอร์ที่รับจาก tel
                                                        print "<option>" . $rowtel . "</option>"; //แสดงเบอร์ที่มีการแอดเพิ่มทีละค่า
                                                    }
                                                }
                                                if (isset($_POST['tel'])) {
                                                    if ($_POST['tel'] != '') {  //เช็คค่า tel ว่ามีหรือไม่  ถ้าเป็นค่าว่างจะไม่แสดงในoptionได้ หากเป็นค่าไม่ว่างมีการกรอกจะสามารถแสดงออกได
                                                        print "<option>" . $_POST['tel'] . "</option>";
                                                    } else {
                                                        echo "<script>"; //คำสั่งสคิป
                                                        echo "Swal.fire('กรุณากรอกเบอร์โทร !','ตรวจสอบเบอร์โทร !','info' );"; //แสดงหน้าต่างเตือน
                                                        echo "</script>";
                                                    }
                                                }
                                                ?></select>&ensp;
                                            <input class='btn btn-info btn-xs mb-2' style="width:80px" type="button" name="remove" value="ลบ" onclick="removeTellist();">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">อีเมลล์ :</td>
                                    <td>
                                        <input type="text" class="form-control" style="width:300px" name="email" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
                                    </td>
                                </tr>

                                <tr>
                                    <td align="right">เลขที่อยู่ :</td>
                                    <td>
                                        <input type="text" class="form-control" style="width:300px" name="address" value="<?php if (isset($_POST['address'])) echo $_POST['address']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">จังหวัด :</td>
                                    <td>
                                        <select class="form-control custom-select" style="width:300px" name="province" id="province" data-fv-notempty='true' data-fv-notempty-message='Please Enter...' onchange="data_show(this.value,'amphur','');document.getElementById('district').innerHTML = '<option value=>กรุณาเลือกอำเภอ . . .</option>';">
                                            <option value=''>กรุณาเลือกจังหวัด . . .</option>
                                            <?php
                                            $strSQL = "SELECT * FROM tbl_provinces ORDER BY province_id ASC";
                                            $result = mysqli_query($conn, $strSQL);
                                            while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                <option value="<?php echo $row['province_id']; ?>" <?php if (isset($_POST['province'])) if ($_POST['province'] == $row['province_id']) {
                                                                                                        echo "selected";
                                                                                                    } ?>><?php echo $row['province_name']; ?></option>
                                            <?php } ?>
                                        </select>

                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">อำเภอ :</td>
                                    <td>
                                        <select class="form-control custom-select" style="width:300px" name="amphur" id="amphur" onchange="data_show(this.value,'district','');" data-fv-notempty='true'>
                                            <option value=''>เลือกอำเภอ . . .
                                            <option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">ตำบล :</td>
                                    <td>
                                        <select class="form-control  custom-select" style="width:300px" name="district" onchange="data_show(this.value,'zipcode','');" id="district" data-fv-notempty='true'>
                                            <option value=''>เลือกตำบล . . .</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">รหัสไปษณีย์ : </td>
                                    <td colspan="3">
                                        <div>
                                            <select name='zipcode' id='zipcode' class="form-control custom-select" style="width:300px">
                                                <option value=''>เลือกรหัสไปษณีย์ . . .</option>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center"></td>
                                    <td>
                                        <input class="w3-button w3-black w3-round-xlarge" style="width:220px;" type="submit" name="sbsback" value="ย้อนกลับ" onclick="document.cpnform.action='cpn_show.php'">
                                        <input class="w3-button w3-red w3-round-xlarge" style="width:220px;" type="submit" name="addcpn" value="บันทึก" onclick="submitForm();">
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script language="javascript">
        function data_show(select_id, result, point_id) {
            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById(result).innerHTML = this.responseText;
                }
            };

            xhttp.open("GET", "../js/action.php?result=" + result + "&select_id=" + select_id + "&point_id=" + point_id, true);
            xhttp.send();
        }
    </script>

</body>

</html>