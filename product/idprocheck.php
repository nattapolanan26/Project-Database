<?php
	if(isset($_POST['back'])){
        $idproduct=$_POST['idproduct'];
            echo "<script>"; //คำสั่งสคิป
            echo "window.location.href='mortar_show.php?p_id=$idproduct';"; 
            echo "</script>";
    }
    if(isset($_POST['sbsback'])){
        $idproduct=$_POST['idproduct'];
            echo "<script>"; //คำสั่งสคิป
            echo "window.location.href='sbs_show.php';"; 
            echo "</script>";
    }
    if(isset($_POST['cgrcolorback'])){
        $idproduct=$_POST['idproduct'];
            echo "<script>"; //คำสั่งสคิป
            echo "window.location.href='cgr_color_show.php';";
            echo "</script>";
    }
    if(isset($_POST['bcback'])){
        $idproduct=$_POST['idproduct'];
            echo "<script>"; //คำสั่งสคิป
            echo "window.location.href='cgr_color_show.php';";
            echo "</script>";
    }
    if(isset($_POST['cinsertback'])){
        $idproduct=$_POST['idproduct'];
            echo "<script>"; //คำสั่งสคิป
            echo "window.location.href='cgr_color_show.php';";
            echo "</script>";
    }
    if(isset($_POST['cshowback'])){
        $idproduct=$_POST['idproduct'];
            echo "<script>"; //คำสั่งสคิป
            echo "window.location.href='cgr_color_show.php';";
            echo "</script>";
    }
    if(isset($_POST['colorback'])){
        $idproduct=$_POST['idproduct'];
            echo "<script>"; //คำสั่งสคิป
            echo "window.location.href='color_show.php';";
            echo "</script>";
    }
    if(isset($_POST['ctback'])){
        $idproduct=$_POST['idproduct'];
            echo "<script>"; //คำสั่งสคิป
            echo "window.location.href='ct_show.php';";
            echo "</script>";
    }
    if(isset($_POST['backatt'])){
        $idproduct=$_POST['idproduct'];
            echo "<script>"; //คำสั่งสคิป
            echo "window.location.href='att_show.php';";
            echo "</script>";
    }
    if(isset($_POST['backsatt'])){
        $idproduct=$_POST['idproduct'];
            echo "<script>"; //คำสั่งสคิป
            echo "window.location.href='att_show.php';";
            echo "</script>";
    }
    if(isset($_POST['pvcback'])){
        $idproduct=$_POST['idproduct'];
            echo "<script>"; //คำสั่งสคิป
            echo "window.location.href='pvc_show.php';";
            echo "</script>";
    }
    if(isset($_POST['backshowcmc'])){
        $idproduct=$_POST['idproduct'];
            echo "<script>"; //คำสั่งสคิป
            echo "window.location.href='chemical_show.php';";
            echo "</script>";
    }
    if(isset($_POST['backshowbcmc'])){
        $idproduct=$_POST['idproduct'];
            echo "<script>"; //คำสั่งสคิป
            echo "window.location.href='bchemical_show.php';";
            echo "</script>";
    }
    if(isset($_POST['addmortar'])){
        $idproduct=$_POST['idproduct'];
            echo "<script>"; //คำสั่งสคิป
            echo "window.location.href='mortar_insert.php';";
            echo "</script>";
    }
    if(isset($_POST['backreceive'])){
        $idproduct=$_POST['id'];
            echo "<script>"; //คำสั่งสคิป
            echo "window.location.href='receive_form.php?ID=$idproduct';";
            echo "</script>";
    }
?>
