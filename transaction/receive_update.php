<?php session_start();
$connect = new PDO("mysql:host=localhost;dbname=dbcons", "root", "1234");
include('../connectdb.php');

if (isset($_POST['hidden_id'])) {

	$id=$_POST['hidden_id'];

	if($id != ''){

		for($i = 0 ; $i < count($_POST['item_no']) ; $i++){

			$data_arr = array(':item_id' => $_POST['hidden_id'], ':item_no' => $_POST['item_no'][$i]);
		
			$sql = "UPDATE detailorderpro SET status_receive='1' WHERE order_id = :item_id AND order_no = :item_no";
		
			$statement = $connect->prepare($sql);
		
			if ($statement->execute($data_arr)){

				echo "update success :";
			}else{
				echo "sql update list error :";
			}
		}

		//เช็คสถานะรายการรับแต่ละรายการ ต้อง = 1 ทั้งหมดถึงจะ update สถานะการรับ

		$query = mysqli_query($conn,"SELECT order_id,SUM(status_receive) AS S_STATUS,COUNT(status_receive) AS C_STATUS FROM detailorderpro WHERE detailorderpro.order_id = '$id'");

		$row=mysqli_fetch_assoc($query);

		if($row['S_STATUS'] == $row['C_STATUS']){

			$data_id=array(':item_id' => $_POST['hidden_id']);

			$sql = "UPDATE orderproduct SET status_receive='1' WHERE order_id = :item_id";
	
			$statement = $connect->prepare($sql);

			if($statement->execute($data_id)){

				echo "update all status success";

			}else{

				echo "sql update error";
				
			}
		}
	}
	// print_r($data);
	// print_r($data_id);
	exit();
}
?>