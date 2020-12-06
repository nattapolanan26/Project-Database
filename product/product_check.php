<!DOCTYPE html>
<?php
	include('../connectdb.php');
?>
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
</head>
<title>Product Loworder</title>
<body>
<form action="#" name="productcheck" id="productcheck" method="post">
<?php    
    $id=isset($_GET['add_id']) ? $_GET['add_id']:'';

    if($id == 'PRO001'){
        echo "<script>";
        echo "window.location.href='sbs_show.php?p_id=$id';";
        echo "</script>";
    }elseif($id === 'PRO002'){
        echo "<script>";
        echo "window.location.href='mortar_show.php?p_id=$id';";
        echo "</script>";
    }elseif($id === 'PRO003'){
        echo "<script>";
        echo "window.location.href='cgr_color_show.php?p_id=$id';";
        echo "</script>";
    }elseif($id === 'PRO004'){
        echo "<script>";
        echo "window.location.href='ct_show.php?p_id=$id';";
        echo "</script>";
    }elseif($id === 'PRO005'){
        echo "<script>";
        echo "window.location.href='att_show.php?p_id=$id';";
        echo "</script>";
    }elseif($id === 'PRO006'){
        echo "<script>";
        echo "window.location.href='pvc_show.php?p_id=$id';";
        echo "</script>";
    }elseif($id === 'PRO007'){
        echo "<script>";
        echo "window.location.href='chemical_show.php?p_id=$id';";
        echo "</script>";
    }
?>
</body>
</html>