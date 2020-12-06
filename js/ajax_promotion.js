$(document).ready(function() {

    var count = 0;
    var myArray = [];

    $(function() {
        var dtToday = new Date();
        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if (month < 10)
            month = '0' + month.toString();
        if (day < 10)
            day = '0' + day.toString();
        var maxDate = year + '-' + month + '-' + day;
        var date_start = $('#date_start').attr('min', maxDate);
        var date_end = $('#date_end').attr('min', maxDate);
    });

    //ดักวันที่เริ่มต้น
    $('#date_start').change(function() {
        var date_s = $(this).val();
        $('.date_end').attr('min', date_s); //กำหนดวันที่สิ้นสุดให้เป็นวันที่เริ่มต้น
        console.log(date_s)
    });
    //ดักวันที่สิ้นสุด
    $('#date_end').change(function() {
        var date_e = $(this).val();
        $('.date_start').attr('max', date_e); //กำหนดวันที่เริ่มต้นห้ามเกินวันที่สุดสิ้น
        console.log(date_e)
    });

    // ------------------------------------ ฟังชั่นหน้าแสดง ------------------------------------
    function show_data() {
        $('#example').DataTable({
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
                url: "../server/show_pmt.php",
                type: "POST"
            }
        });
    }

    show_data(); //เรียกฟังชั่นหน้าแสดง
    // ------------------------------------ ฟังชั่นเครียค่าเมื่อกดปุ่ม ------------------------------------
    $('#add').click(function(e) {
        e.preventDefault();
        $('#pmt_name').val('');
        $('#date_start').val('');
        $('#date_end').val('');
        $('#discount').val('');
        $('#product').val('');

        $('#error_name').text('');
        $('#error_date_s').text('');
        $('#error_date_e').text('');
        $('#error_discount').text('');
        $('#error_product').text('');

        $('#pmt_name').css('border-color', '');
        $('#date_start').css('border-color', '');
        $('#date_end').css('border-color', '');
        $('#discount').css('border-color', '');
        $('#product').css('border-color', '');

        $('#add_product').text('เพิ่มสินค้าร่วมโปรโมชั่น');
        $('#pmt_table').find('tr:gt(0)').remove(); //เคลียร์ค่าใน table row
        $("#product").val("").trigger("change"); //clear
        $('#btn_submit').text('บันทึกข้อมูล');
        count = 0; //เคลียร์ค่าตัวแปร
    });

    // ------------------------------------ ฟังชั่นเพิ่มรายการสินค้า ------------------------------------
    $('#add_product').click(function(e) {
        e.preventDefault();
        var id_promotion = $('#id_pmt').val();

        var product = $('#product').val();
        item = {};
        item.product = product;

        //เช็ครายการสินค้าซ้ำกันใน array ข้อมูลที่เพิ่มใหม่
        for (i = 0; i < myArray.length; i++) {
            if (myArray[i].product === product) {
                Swal.fire({
                    position: "mid-mid",
                    icon: "error",
                    title: "สินค้ามีอยู่ในรายการแล้ว !",
                    showConfirmButton: false,
                    timer: 1500,
                });
                $("#product").val("").trigger("change"); //clear
                return;
            }
        }
        myArray.push(item);
        console.log(myArray)


        if (product == '') {
            $("#product").val("").trigger("change"); //clear
            Swal.fire({
                position: "mid-mid",
                icon: "error",
                title: "กรุณาเลือกสินค้า !",
                showConfirmButton: false,
                timer: 1500,
            });

        } else {
            $.ajax({
                type: "POST",
                url: "../product/fetch.php",
                data: {
                    product: product
                },
                dataType: "JSON",
                success: function(response) {
                    // console.log(response)

                    //เช็คกดปุ่มเพิ่มรายการ
                    if ($('#add_product').text() == 'เพิ่มสินค้าร่วมโปรโมชั่น') {

                        count = count + 1;

                        output = '<tr id="row_' + count + '">';
                        output += '<td>';
                        if (response.product_name != null) {
                            output += response.product_name + " ";
                        }
                        if (response.brand_name != null) {
                            output += response.brand_name;
                        }
                        if (response.color_name != null) {
                            output += " สี" + response.color_name;
                        }
                        if (response.class != null) {
                            output += " ชั้น " + response.class;
                        }
                        if (response.tl_size != null) {
                            output += " ขนาด (" + response.tl_size + ")";
                        }
                        if (response.pb_size != null) {
                            output += " ขนาด (" + response.pb_size + ")";
                        }
                        if (response.ct_size != null) {
                            output += " ขนาด (" + response.ct_size + ")";
                        }
                        if (response.pb_thick != null) {
                            output += " หนา (" + response.pb_thick + ")";
                        }
                        if (response.cc_volume != null) {
                            output += " ปริมาณ " + response.cc_volume;
                        }
                        if (response.cs_volume != null) {
                            output += " ปริมาณ " + response.cs_volume;
                        }
                        if (response.cm_volume != null) {
                            output += " ปริมาณ " + response.cm_volume;
                        }
                        output += '<input type="hidden" name="hidden_product[]" id="product' + count + '" class="product" value="' + response.product_id + '"></td>';
                        output += '<td align="center"><button type="button" id="' + count + '" name="remove_details" class="btn btn-danger btn_xs remove_details"><i class="fa fa-trash"></i> ลบ</button></td>';
                        output += '</tr>';
                        $('#pmt_table').append(output);
                    }
                    // var check_pd = $("#product option[value=" + product + "]").hide();
                    // console.log(check_pd)
                    $("#product").val("").trigger("change"); //clear
                }
            });
        }

        $.ajax({
            type: "POST",
            url: "../product/fetch.php",
            data: {
                pmt_product: id_promotion
            },
            dataType: "JSON",
            success: function(response) {
                //เช็ครายการสินค้าซ้ำกันในข้อมูลเดิม กับ ข้อมูลที่เพิ่มใหม่
                for (var i = 0; i < response.length; i++) {
                    if (response[i].product_id === product) {
                        Swal.fire({
                            position: "mid-mid",
                            icon: "error",
                            title: "สินค้ามีอยู่ในรายการแล้ว !",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        $("#product").val("").trigger("change"); //clear
                        return false;
                    }
                }


                if (product == '') {
                    $("#product").val("").trigger("change"); //clear
                    Swal.fire({
                        position: "mid-mid",
                        icon: "error",
                        title: "กรุณาเลือกสินค้า !",
                        showConfirmButton: false,
                        timer: 1500,
                    });

                } else {
                    $.ajax({
                        type: "POST",
                        url: "../product/fetch.php",
                        data: {
                            product: product
                        },
                        dataType: "JSON",
                        success: function(response) {
                            // console.log(response)

                            //เช็คกดปุ่มเพิ่มรายการ
                            if ($('#add_product').text() == 'เพิ่มสินค้าร่วมโปรโมชั่น ') {

                                count = count + 1;

                                output = '<tr id="row_' + count + '">';
                                output += '<td>';
                                if (response.product_name != null) {
                                    output += response.product_name + " ";
                                }
                                if (response.brand_name != null) {
                                    output += response.brand_name;
                                }
                                if (response.color_name != null) {
                                    output += " สี" + response.color_name;
                                }
                                if (response.class != null) {
                                    output += " ชั้น " + response.class;
                                }
                                if (response.tl_size != null) {
                                    output += " ขนาด (" + response.tl_size + ")";
                                }
                                if (response.pb_size != null) {
                                    output += " ขนาด (" + response.pb_size + ")";
                                }
                                if (response.ct_size != null) {
                                    output += " ขนาด (" + response.ct_size + ")";
                                }
                                if (response.pb_thick != null) {
                                    output += " หนา (" + response.pb_thick + ")";
                                }
                                if (response.cc_volume != null) {
                                    output += " ปริมาณ " + response.cc_volume;
                                }
                                if (response.cs_volume != null) {
                                    output += " ปริมาณ " + response.cs_volume;
                                }
                                if (response.cm_volume != null) {
                                    output += " ปริมาณ " + response.cm_volume;
                                }
                                output += '<input type="hidden" name="product[]" id="product' + count + '" class="product" value="' + response.product_id + '"></td>';
                                output += '<td align="center"><button type="button" id="' + count + '" name="remove_details" class="btn btn-danger btn_xs remove_details"><i class="fa fa-trash"></i> ลบ</button></td>';
                                output += '</tr>';
                                $('#pmt_table').append(output);
                            }
                            $("#product").val("").trigger("change"); //clear
                        }
                    });
                }
            }
        });
    });

    // ------------------------------------ ลบข้อมูลในรายการสินค้า ------------------------------------
    $(document).on('click', '.remove_details', function() {
        var row_id = $(this).attr("id");
        console.log(row_id)
        if (confirm("ต้องการลบรายการนี้ใช่หรือไม่ ?")) {
            myArray.splice(row_id - 1, 1);
            count--;
            $('#row_' + row_id + '').remove();
            $("#product").val("").trigger("change"); //clear
            console.log(myArray)
        } else {

            return false;

        }
    });

    // ------------------------------------ ฟังชั่นเพิ่มโปรโมชั่น/เพิ่มสินค้าร่วมรายการ ------------------------------------
    $(document).on('submit', function(event) {

        event.preventDefault();
        var error = '';
        var tb_product = $('#myform').serialize();

        if ($('#pmt_name').val() == '') {
            error_name = '*กรุณาใส่ชื่อโปรโมชั่น';
            $('#error_name').text(error_name);
            $('#pmt_name').css('border-color', '#cc0000');
            name = '';
        } else {
            error_name = '';
            $('#error_name').text(error_name);
            $('#pmt_name').css('border-color', '');
            name = $('#pmt_name').val(); //เอาค่าใส่ตัวแปร
        }

        //เช็คค่าว่างวันที่เริ่ม
        if ($('#date_start').val() == '') {
            error_date_s = '*กรุณาเลือกวันที่เริ่มโปรโมชั่น';
            $('#error_date_s').text(error_date_s);
            $('#date_start').css('border-color', '#cc0000');
            date_start = '';
        } else {
            error_date_s = '';
            $('#error_date_s').text(error_date_s);
            $('#date_start').css('border-color', '');
            date_start = $('#date_start').val(); //เอาค่าใส่ตัวแปร
        }

        //เช็คค่าว่างวันที่สิ้นสุด
        if ($('#date_end').val() == '') {
            error_date_e = '*กรุณาเลือกวันที่สิ้นสุดโปรโมชั่น';
            $('#error_date_e').text(error_date_e);
            $('#date_end').css('border-color', '#cc0000');
            date_end = '';
        } else {
            error_date_e = '';
            $('#error_date_e').text(error_date_e);
            $('#date_end').css('border-color', '');
            date_end = $('#date_end').val(); //เอาค่าใส่ตัวแปร
        }

        //เช็คค่าว่างส่วนลด%
        if ($('#discount').val() == '') {
            error_discount = '*กรุณาใส่ส่วนลด%';
            $('#error_discount').text(error_discount);
            $('#discount').css('border-color', '#cc0000');
            discount = '';
        } else {
            error_discount = '';
            $('#error_discount').text(error_discount);
            $('#discount').css('border-color', '');
            discount = $('#discount').val(); //เอาค่าใส่ตัวแปร
        }

        if (error_name != '' || error_date_s != '' || error_date_e != '' || error_discount != '') {

            return false;

        } else {
            Swal.fire({
                title: 'ยืนยันบันทึกข้อมูล?',
                text: "โปรดตรวจสอบการเพิ่มโปรโมชั่น!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                buttonsStyling: true
            }).then((result) => {
                if (result.value === true) {

                    if ($('#btn_submit').text() == 'บันทึกข้อมูล') {
                        $.ajax({
                            type: "POST",
                            url: "../promotion/promotion_insert.php",
                            data: tb_product,
                            success: function(data) {
                                if (data === 'บันทึกโปรโมชั่นสำเร็จ') {
                                    $('#message').fadeIn().html('<div class="alert alert-success"><i class="fa fa-check-circle" aria-hidden="true"></i> ' + data + '</div>');
                                    setTimeout(function() {
                                        $('#message').fadeOut("Slow");
                                    }, 2000);
                                    setTimeout(function() {
                                        $('#modalAdd').modal('hide');
                                    }, 2000);
                                    $('#pmt_table').find('tr:gt(0)').remove(); //เคลียร์ค่าใน table row
                                    count = 0; //เคลียร์ค่าตัวแปร
                                    $('#pmt_name').val('');
                                    $('#date_start').val('');
                                    $('#date_end').val('');
                                    $('#discount').val('');
                                    $("#product").val("").trigger("change"); //clear
                                    show_data();
                                } else {
                                    $('#message').fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + data + '</div>');
                                    setTimeout(function() {
                                        $('#message').fadeOut("Slow");
                                    }, 2000);
                                    show_data();
                                }
                                console.log(data);
                            }
                        });
                    } else if ($('#btn_submit').text() == 'แก้ไขรายการ') {
                        console.log(tb_product)
                        $.ajax({
                            type: "POST",
                            url: "../promotion/promotion_update.php",
                            data: tb_product,
                            success: function(data) {
                                if (data === 'อัพเดทโปรโมชั่นสำเร็จ') {
                                    $('#message').fadeIn().html('<div class="alert alert-success"><i class="fa fa-check-circle" aria-hidden="true"></i> ' + data + '</div>');
                                    setTimeout(function() {
                                        $('#message').fadeOut("Slow");
                                    }, 2000);
                                    setTimeout(function() {
                                        $('#modalAdd').modal('hide');
                                    }, 2000);
                                    count = 0; //เคลียร์ค่าตัวแปร
                                    $("#product").val("").trigger("change"); //clear

                                } else {
                                    $('#message').fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + data + '</div>');
                                    setTimeout(function() {
                                        $('#message').fadeOut("Slow");
                                    }, 2000);
                                }
                                console.log(data)
                            }
                        });
                    }
                }
            }, )
        }
    });

    // ------------------------------------ แก้ไขโปรโมชั่น ------------------------------------
    $(document).on('click', '.edit_pmt', function() {
        // clear
        $('#error_name').text('');
        $('#error_date_s').text('');
        $('#error_date_e').text('');
        $('#error_discount').text('');
        $('#error_product').text('');

        $('#pmt_name').css('border-color', '');
        $('#date_start').css('border-color', '');
        $('#date_end').css('border-color', '');
        $('#discount').css('border-color', '');
        $('#product').css('border-color', '');

        $('#add_product').text('เพิ่มสินค้าร่วมโปรโมชั่น ');
        $('#exampleModalLabel').text('แก้ไขโปรโมชั่น ');
        $('#pmt_table').find('tr:gt(0)').remove(); //เคลียร์ค่าใน table row
        $("#product").val("").trigger("change"); //clear
        count = 0; //เคลียร์ค่าตัวแปร


        var row_id = $(this).data("id");
        var pmt_id = $('#id_pmt').val(row_id);
        console.log(pmt_id)
            //fetch Promotion edit
        $.ajax({
            type: "POST",
            url: "../product/fetch.php",
            data: {
                row_id: row_id
            },
            dataType: "JSON",
            success: function(response) {

                $('#pmt_name').val(response.promotion_name);
                $('#date_start').val(response.date_start);
                $('#date_end').val(response.date_end);
                $('#discount').val(response.promotion_discount);

                $('#btn_submit').text('แก้ไขรายการ');
                $('#add_product').text('เพิ่มสินค้าร่วมโปรโมชั่น ');
                console.log(row_id, response.promotion_name, response.date_start, response.date_end, response.promotion_discount)
            }
        });
        //fetch List product in promotion edit
        $.ajax({
            type: "POST",
            url: "../product/fetch.php",
            data: {
                pmt_product: row_id
            },
            dataType: "JSON",
            success: function(response) {
                var html = "";

                for (var i = 0; i < response.length; i++) {
                    count = count + 1;

                    html += '<tr id="row_' + count + '">';
                    html += '<td><input type="hidden" name="fetch_product[]" id="fetch_product' + count + '" class="fetch_product" value="' + response[i].product_id + '">';
                    if (response[i].product_name != null) {
                        html += response[i].product_name + " ";
                    }
                    if (response[i].brand_name != null) {
                        html += response[i].brand_name;
                    }
                    if (response[i].color_name != null) {
                        html += " สี" + response[i].color_name;
                    }
                    if (response[i].class != null) {
                        html += " ชั้น " + response[i].class;
                    }
                    if (response[i].tl_size != null) {
                        html += " ขนาด (" + response[i].tl_size + ")";
                    }
                    if (response[i].pb_size != null) {
                        html += " ขนาด (" + response[i].pb_size + ")";
                    }
                    if (response[i].ct_size != null) {
                        html += " ขนาด (" + response[i].ct_size + ")";
                    }
                    if (response[i].pb_thick != null) {
                        html += " หนา (" + response[i].pb_thick + ")";
                    }
                    if (response[i].cc_volume != null) {
                        html += " ปริมาณ " + response[i].cc_volume;
                    }
                    if (response[i].cs_volume != null) {
                        html += " ปริมาณ " + response[i].cs_volume;
                    }
                    if (response[i].cm_volume != null) {
                        html += " ปริมาณ " + response[i].cm_volume;
                    }
                    html += '</td><td><button type="button" id="' + count + '" name="remove_details" class="btn btn-danger btn_xs remove_details"><i class="fa fa-trash"></i> ลบ</button></td>';
                    html += '</tr>';
                }
                $('#pmt_table').append(html);

                console.log(response)
            }
        });
    });


    //แสดงข้อมูล
    $(document).on("click", ".show_pmt", function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#pmt_id').text(id);

        $.ajax({
            type: "POST",
            url: "../product/fetch.php",
            data: {
                show_promotion: id
            },
            dataType: "JSON",
            success: function(response) {
                console.log(response);
                var html = "";
                for (var i = 0; i < response.length; i++) {
                    html += '<tr>';
                    html += '<td align="center">' + response[i].product_id + '</td>';
                    html += '<td>';
                    if (response[i].product_name != null) {
                        html += response[i].product_name + " ";
                    }
                    if (response[i].brand_name != null) {
                        html += response[i].brand_name;
                    }
                    if (response[i].color_name != null) {
                        html += " สี" + response[i].color_name;
                    }
                    if (response[i].class != null) {
                        html += " ชั้น " + response[i].class;
                    }
                    if (response[i].tl_size != null) {
                        html += " ขนาด (" + response[i].tl_size + ")";
                    }
                    if (response[i].pb_size != null) {
                        html += " ขนาด (" + response[i].pb_size + ")";
                    }
                    if (response[i].ct_size != null) {
                        html += " ขนาด (" + response[i].ct_size + ")";
                    }
                    if (response[i].pb_thick != null) {
                        html += " หนา (" + response[i].pb_thick + ")";
                    }
                    if (response[i].cc_volume != null) {
                        html += " ปริมาณ " + response[i].cc_volume;
                    }
                    if (response[i].cs_volume != null) {
                        html += " ปริมาณ " + response[i].cs_volume;
                    }
                    if (response[i].cm_volume != null) {
                        html += " ปริมาณ " + response[i].cm_volume;
                    }
                    html += '</td>';
                    html += '</tr>';
                }
                $("#list_show").html(html);
            }
        });
    });


    //แสดงข้อมูล
    $(document).on("click", ".del_pmt", function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        // alert(id)
        console.log(id)
        Swal.fire({
            title: 'ยืนยันการลบข้อมูล?',
            text: "โปรดตรวจสอบโปรโมชั่นและรายการสินค้าก่อนยืนยัน!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
            buttonsStyling: true
        }).then((result) => {
            if (result.value === true) {
                $.ajax({
                    type: "POST",
                    url: "../promotion/promotion_del.php",
                    data: "promotion_id=" + id,
                    success: function(data) {
                        if (data === 'ลบข้อมูลสำเร็จ') {
                            $('#message').fadeIn().html('<div class="alert alert-success"><i class="fa fa-check-circle" aria-hidden="true"></i> ' + data + '</div>');
                            setTimeout(function() {
                                $('#message').fadeOut("Slow");
                            }, 2000);
                            setTimeout(function() {
                                $('#modalAdd').modal('hide');
                            }, 2000);

                            show_data();
                        } else {
                            $('#message').fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + data + '</div>');
                            setTimeout(function() {
                                $('#message').fadeOut("Slow");
                            }, 2000);
                            show_data();
                        }
                        console.log(data);
                    }
                });
            }

        }, )
    });
});