// ดึงค่าสินค้าที่ชำรุดแล้ว
$(document).ready(function () {
    $('#search').keyup(function () {
        var searchProduct = $(this).val();
        if (searchProduct != '') {
            $.ajax({
                method: 'POST',
                url: 'action.php',
                data: {
                    querypro: searchProduct
                },
                success: function (response) {
                    $('#show-product').html(response);
                }
            });
        }else{
            //เช็ค Text ไม่มีการป้อนข้อมูลใส่
            $('#show-product').html('');
        }
    });
});