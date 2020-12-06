<?php
    include('../connectdb.php');

        $id = isset($_GET['delete_id']) ? $_GET['delete_id']:'';
        $rowtel = isset($_GET['tel_list']) ? $_GET['tel_list']:'';

        if ($id!='') {
            $sql_tel = "DELETE FROM tel_company where cpn_id ='".$id."'";
            $conn->query($sql_tel)==true;

            if ($id!='') {
                $sqlcst = "DELETE FROM company where cpn_id ='".$id."'";
                $conn->query($sqlcst)==true;
                $sqlcostprice = "DELETE FROM costprice where cpn_id ='".$id."'";
                if($conn->query($sqlcostprice)==true){
                    echo "<script>";
                    echo "alert('Delete Success!');";
                    echo "window.location.href='cpn_show.php';";
                    echo "</script>";
                }
            }
        }
?>
