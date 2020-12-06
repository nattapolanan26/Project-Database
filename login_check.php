<?php session_start();
include('connectdb.php');
include('h.php');
if (isset($_POST['loginform'])) {

    $username = $_POST['username']; //รับค่าจาก Text
    $password = $_POST['password'];
    $passwordenc = md5($password);
    // ตรวจสอบจาก employee
    $sqlemp = "SELECT * FROM employee  WHERE employee.emp_username = '$username' AND employee.emp_password='$password'";
    $empquery = mysqli_query($conn, $sqlemp);
    // ตรวจสอบจาก customer
    $sqlcus ="SELECT * FROM customer WHERE customer.cus_username = '$username' AND customer.cus_passw='$password'";
    $cusquery = mysqli_query($conn, $sqlcus);

    if (mysqli_num_rows($empquery) == 1) {
        $rowemp = mysqli_fetch_array($empquery);
        $empid=$rowemp['emp_id'];
        $_SESSION['posid']=$rowemp['pos_id'];

        $sql="SELECT employee.emp_id,position.pos_name,employee.emp_username,employee.emp_name,employee.emp_lname,position.pos_status FROM employee INNER JOIN position ON employee.pos_id = position.pos_id WHERE position.pos_id = '".$rowemp['pos_id']."' AND employee.emp_id = '".$empid."'";
        $result1=mysqli_query($conn, $sql);
        $rowpos = mysqli_fetch_array($result1);
// echo $sql;
        $_SESSION['empid'] = $rowpos['emp_id'];
        $_SESSION['posname'] = $rowpos['pos_name'];
        $_SESSION['userid'] = $rowemp['emp_username']; //user พนักงาน
        $_SESSION['user'] = $rowemp['emp_name'] . " " . $rowemp['emp_lname']; //ชื่อพนักงาน และ นามสกุล
        $_SESSION['status'] = $rowpos['pos_status'];
        $user=$_SESSION['user'];

        echo "<script>"; //คำสั่งสคิป
        // echo "swal.fire('Login Success!','สวัสดีคุณ $user <br>ยินดีต้อนรับเข้าสู่เว็บไซต์','success');";
        echo "window.location.href='index.php'";
        echo "</script>";

    } else if(mysqli_num_rows($cusquery) == 1) {
        $rowcus = mysqli_fetch_array($cusquery);
        $_SESSION['posid']=$rowcus['pos_id'];

        $sql="SELECT * FROM customer INNER JOIN position ON customer.pos_id = position.pos_id WHERE position.pos_id = '".$rowcus['pos_id']."'";
        $result=mysqli_query($conn, $sql);
        $rowpos = mysqli_fetch_array($result);

        $_SESSION['posname'] = $rowpos['pos_name'];
        $_SESSION['userid'] = $rowcus['cus_username']; //user พนักงาน
        $_SESSION['user'] = $rowcus['cus_name'] . " " . $rowcus['cus_lname']; //ชื่อพนักงาน และ นามสกุล
        $_SESSION['status'] = $rowpos['pos_status'];  
        $user=$_SESSION['user'];

        echo "<script>"; //คำสั่งสคิป
        echo "alert('Hello $user!');"; //แสดงหน้าต่างเตือน
        echo "window.location.href='index.php';"; //แสดงหน้าก่อนนี้
        echo "</script>";

    } 
    else {
        echo "<script>";
        echo "alert('Username หรือ Password ไม่ถูกต้อง !');";
        echo "window.location.href='javascript:history.back(1)';";
        echo "</script>";
    }
} else {
    header("Location: login_form.php");
}
?>
</script>