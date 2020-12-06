<!DOCTYPE html>
<html>

<head>
    <meta http-equiv=Content-Type content="text/html; charset=utf-8">
</head>

<body>
    <?php
    include('../connectdb.php');

    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $zipcode = $_POST['zipcode'];
        $province = $_POST['province'];
        $amphur = $_POST['amphur'];
        $district = $_POST['district'];
        $address = $_POST['address'];


        $sql = "SELECT count(cpn_name) AS cpn_name FROM company WHERE cpn_name = '$name' AND cpn_id != '$id'";
        $query = mysqli_query($conn, $sql) or die("Error description: " . mysqli_error($conn));
        $row  = mysqli_fetch_array($query);
        $cpnname = $row['cpn_name'];
        if ($cpnname == '0') {
            $sql = "UPDATE company SET cpn_name='" . $name . "',
                                cpn_email='" . $email . "',
                                province_id='" . $province . "',
                                amphur_id='" . $amphur . "',
                                district_id='" . $district . "',
                                zipcode_id='" . $zipcode . "',
                                cpn_address='" . $address . "'
                WHERE cpn_id='" . $id . "'";
            // echo $sql;
            if ($result = mysqli_query($conn, $sql) == true) {
                $sqldel = "DELETE FROM tel_company where cpn_id ='" . $id . "'";
                $conn->query($sqldel);

                foreach ($_POST['tel_list'] as $rowtel) {
                    $sqltel = "INSERT INTO tel_company (cpn_id,cpn_tel) VALUES ('$id','$rowtel')";
                    $result = mysqli_query($conn, $sqltel);
                }
                echo "<script>";
                echo "alert('Update Success!');";
                echo "window.location.href='cpn_show.php';";
                echo "</script>";
            } else {
                echo "<script>";
                echo "alert('ERROR!');";
                echo "window.location.href='cpn_show.php';";
                echo "</script>";
            }
        } else {
            echo "<script>";
            echo "alert('มีบริษัทชื่อนี้แล้ว!');";
            echo "window.location.href='cpn_show.php';";
            echo "</script>";
        }
    }
    ?>
</body>

</html>