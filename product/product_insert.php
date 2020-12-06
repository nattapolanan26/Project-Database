<!DOCTYPE html>
<html>

<head>
	<?php include('../h.php'); ?>
</head>

<body>
	<p style="color:red;">**หมวดหมู่ประเภทสินค้าที่ต้องการเพิ่ม**</p>
		<div class="form-group">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<label class="input-group-text" for="inputGroupSelect01">หมวดหมู่สินค้า</label>
				</div>
				<select onchange="location = this.value;" name="product" id="product" class="custom-select" required>
					<option value="#">โปรดเลือกประเภทสินค้าที่ต้องการเพิ่ม . . .</option>
					<option value="sbs_show.php">สินค้าเบ็ดเตล็ด เช่น หิน,อิฐ,ทราย/อุปกรณ์ทำสวนต่างๆ ฯลฯ</option>
					<option value="cement_show.php">ซีเมนต์</option>
					<option value="tl_show.php">อุปกรณ์ห้องน้ำ</option>
					<option value="pb_show.php">งานประปา</option>
					<option value="cgr_color_show.php">สีทาอาคาร</option>
					<option value="ct_show.php">เครื่องมือช่าง</option>
					<option value="chemical_show.php">น้ำยาเฉพาะทาง</option>
				</select>
			</div>
		</div>
</body>

</html>