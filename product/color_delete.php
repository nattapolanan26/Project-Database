<?php
    include('../connectdb.php');

        $id = isset($_GET['delete_id']) ? $_GET['delete_id']:'';
        $idproduct = isset($_GET['p_id']) ? $_GET['p_id']:'';
        if($id!=''){
        $sqldelete = "DELETE FROM color WHERE color_id ='".$id."'";
        if($conn->query($sqldelete)==TRUE){ 
            echo "<script>"; 
            echo "alert('DELETE SUCCESS!');"; 
            echo "window.location.href='color_show.php?p_id=$idproduct';";
            echo "</script>";
            }
        }
    ?>
