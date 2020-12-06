$(document).ready(function() {
    function show_data() {
        $('#show_quo').DataTable({
            "oLanguage": {
                "sLengthMenu": "แสดง _MENU_ แถว",
                "sZeroRecords": "ไม่เจอข้อมูลที่ค้นหา",
                "sInfo": "แสดง _START_ - _END_ ทั้งหมด _TOTAL_ แถว",
                "sInfoEmpty": "แสดง 0 - 0 ของ 0 แถว",
                "sInfoFiltered": "(จากแถวทั้งหมด _MAX_ แถว)",
                "sSearch": "ค้นหา :",
                "aaSorting": [
                    [0, 'desc']
                ],
                "oPaginate": {
                    "sFirst": "หน้าแรก",
                    "sPrevious": "ก่อนหน้า",
                    "sNext": "ถัดไป",
                    "sLast": "หน้าสุดท้าย"
                }
            },
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "../server/show_order.php",
                type: "POST"
            }
        });
    }
    show_data();

    // ---------------------------------------- แสดงรายการใบเสนอซื้อ ----------------------------------------
    $(document).on("click", ".show_data", function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#show_id').text(id);
        console.log(id);

        if (id != '') {
            $.ajax({
                url: "../product/fetch.php",
                method: "POST",
                data: {
                    order_list: id
                },
                dataType: "JSON",
                success: function(response) {
                    console.log(response);
                    var html = "";
                    for (var i = 0; i < response.length; i++) {
                        html += '<tr class="active-row">';
                        html += '<td align="center">' + response[i].cpn_id + '</td>';
                        html += '<td align="center">' + response[i].cpn_name + '</td>';
                        html += '<td align="center"><a href="../mpdf/order_mpdf.php?order_id=' + response[i].order_id + '&cpn_id=' + response[i].cpn_id + '" class="btn btn-primary btn-xs"><i class="fa fa-print" aria-hidden="true"></i></a></td>';
                        html += '</tr>';;
                    }
                    $("#list_show").html(html);
                    show_data(); //เรียกฟังชั่นหน้าแสดง
                }
            });
        }
    });
});