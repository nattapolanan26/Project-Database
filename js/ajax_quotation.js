$(document).ready(function() {

    myArray = [];
    var count = 0;

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
                url: "../server/show_quo.php",
                type: "POST"
            }
        });
    }

    show_data(); //เรียกฟังชั่นหน้าแสดง


    // ------------------------------------ ฟังชั่นดุึงข้อมูล selectpicker ------------------------------------
    load_data("product_data");

    function load_data(type_product, pd_id = "") {
        $.ajax({
            type: "POST",
            url: "../js/action.php",
            data: {
                type_product: type_product,
                pd_id: pd_id,
            },
            dataType: "JSON",
            success: function(response) {
                var html = "";
                var count = 0;

                for (count; count < response.length; count++) {
                    html += '<option value="' + response[count].id + '">' + response[count].name + "</option>";
                }
                if (type_product == "product_data") {
                    $("#product").html(html);
                    $("#product").selectpicker("refresh");
                } else {
                    $("#company").html(html);
                    $("#company").selectpicker("refresh");
                }
            },
        });
    }

    // ------------------------------------- คำนวณราคารวม -------------------------------------
    function calculate() {
        var totalPrice = 0;
        var zero = 0;
        var vat = 0;
        var total = 0;

        $(".sum").each(function() {
            var price = parseFloat($(this).val());
            totalPrice = totalPrice + price;
            vat = parseInt((totalPrice * (7 / 100))); // vat7%
            total = parseInt(totalPrice + vat); // total
        });
        $(".in-sum-price").val(totalPrice.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
        $(".cal-sum-price").val(totalPrice); // value hidden box

        $(".in-vat").val(vat.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
        $(".cal-vat").val(vat); // value hidden box

        $(".in-total").val(total.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
        $(".cal-total").val(total); // value hidden box


        if ($(".in-sum-price").val() == 0.00 || $(".in-sum-price").val() == 0) { //เครีย vat / total
            $(".in-vat").val(zero.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
            $(".cal-vat").val(zero); // value hidden box

            $(".in-total").val(zero.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
            $(".cal-total").val(zero); // value hidden box
            // count = 0;
        }
    }


    // ------------------------------------ เช็คเมื่อมีการเปลียน select บริษัทคู่ค้า ------------------------------------
    $(document).on("change", "#product", function() {
        var pd_id = $("#product").val();

        $.ajax({
            type: "POST",
            url: "../product/fetch.php",
            data: {
                pd_id: pd_id,
            },
            dataType: "JSON",
            success: function(response) {
                console.log(response)
                var html = "";
                for (var i = 0; i < response.length; i++) {
                    var costprice = parseInt(response[i].costprice);
                    // var stock = parseInt(response[i].product_stock);
                    // var reorder = parseInt(response[i].product_reorder);
                    html += "<tr>";
                    html += '<td align="center">';
                    html += '<button type="button" name="add[]" id="add" data-item_company="' + response[i].cpn_id + '" data-item_product="' + response[i].product_id + '"  class="btn btn-danger btn-circle add"><i class="fa fa-plus fa-lg" aria-hidden="true"></i></button>';
                    html += '</td>';
                    html += '<td align="center">' + response[i].cpn_id + "</td>";
                    html += '<td>' + response[i].cpn_name + '</td>'
                    html += '<td align="center">' + costprice.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '</td>'
                    html += "</tr>";
                }
                $("#quo_tb").html(html);
                $('#lb_cpn').show();
                $('#pd_quo_list').show();
            },
        });
    });

    function disableDropdown() {
        $('#company').prop('disabled', true);
        $('#company').selectpicker('refresh');
        // $("#company").attr("disabled", "disabled");
    }

    function enableDropdown() {
        $('.selectpicker').prop('disabled', false);
        $('.selectpicker').selectpicker('refresh');
    }

    // ------------------------------------ ฟังชั่นเครียค่าเมื่อกดปุ่ม ------------------------------------
    $('.add_data').click(function(e) {
        e.preventDefault();
        $('#date').val('');
        $('#number').val('');
        $("#product").val("").trigger("change"); //clear

        $('#error_date').text('');
        $('#error_product').text('');
        $('#error_number').text('');
        $('#date').css('border-color', '');
        $('#product').css('border-color', '');
        $('#number').css('border-color', '');

        $('#add_product').text('เพิ่มรายการ');
        $('#quo_tb').find('tr:gt(0)').remove(); //เคลียร์ค่าใน table row
        $('#quo_list').find('tr:gt(0)').remove(); //เคลียร์ค่าใน table row
        $('#btn_submit').text('บันทึกการเสนอ');
        $('#num_approve').hide();
        $('#lb_cpn').hide();
        $('#pd_quo_list').hide();
        $('#lb_list').hide();
        $('#quo_list_select').hide();

        enableDropdown();
        calculate();
        count = 0; //เคลียร์ค่าตัวแปร
    });

    $(document).on("click", ".add", function() {
        var product = $.trim($(this).data("item_product"));
        var company = $.trim($(this).data("item_company"));
        var number = $('#number').val();
        console.log(product, company)

        if (number == '') {
            $("#number").val(''); //clear
            Swal.fire({
                position: "mid-mid",
                icon: "error",
                title: "กรุณาใส่จำนวนที่เสนอ !",
                showConfirmButton: false,
                timer: 1500,
            });

        } else {
            item = {};
            item.product = product;
            for (i = 0; i < myArray.length; i++) {
                if (myArray[i].product === product) {
                    Swal.fire({
                        position: "mid-mid",
                        icon: "error",
                        title: "ข้อมูลซ้ำ !",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    return;
                }
            }

            myArray.push(item);
            console.log(myArray)

            $.ajax({
                url: "../product/fetch.php",
                method: "POST",
                data: {
                    quo_list_product: product,
                    quo_list_company: company
                },
                dataType: "JSON",
                success: function(response) {

                    var html = "";
                    count++;

                    var costprice = parseInt(response.costprice);
                    var number = parseInt($('#number').val());
                    var sum = costprice * number;

                    if (number > 0 && number <= 999) {
                        html = "<tr id='row_" + count + "'> ";
                        html += '<td align="center">' + count + '<input type="hidden" name="item_no[]" id="item_no' + count + '" value="' + count + '"></td>';
                        html += "<td>";
                        if (response.product_name != null) {
                            html += response.product_name + " ";
                        }
                        if (response.brand_name != null) {
                            html += response.brand_name;
                        }
                        if (response.color_name != null) {
                            html += " สี" + response.color_name;
                        }
                        if (response.class != null) {
                            html += " ชั้น " + response.class;
                        }
                        if (response.tl_size != null) {
                            html += " ขนาด (" + response.tl_size + ")";
                        }
                        if (response.pb_size != null) {
                            html += " ขนาด (" + response.pb_size + ")";
                        }
                        if (response.ct_size != null) {
                            html += " ขนาด (" + response.ct_size + ")";
                        }
                        if (response.pb_thick != null) {
                            html += " หนา (" + response.pb_thick + ")";
                        }
                        if (response.cc_volume != null) {
                            html += " ปริมาณ " + response.cc_volume;
                        }
                        if (response.cs_volume != null) {
                            html += " ปริมาณ " + response.cs_volume;
                        }
                        if (response.cm_volume != null) {
                            html += " ปริมาณ " + response.cm_volume;
                        }
                        html += '<input type="hidden" name="item_product[]" id="item_product' + count + '" class="item_product" value="' + response.product_id + '"></td>';
                        html += '<td>' + response.cpn_name + '<input type="hidden" name="item_company[]" id="item_company' + count + '" class="item_company" value="' + response.cpn_id + '"></td>';
                        html += '<td>' + number.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '<input type="hidden" name="number[]" id="number' + count + '" class="form-control" value="' + number + '"></td>';
                        html += '<td>' + costprice.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '<input type="hidden" name="costprice[]" id="costprice' + count + '" class="form-control" value="' + response.costprice + '"></td>';
                        html += '<td>' + sum.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '<input type="hidden" name="sum[]" id="sum' + count + '" class="form-control sum" value="' + sum + '"></td>';
                        html += '<td align="center"><button type="button" id="' + count + '" name="remove_details" class="btn btn-danger btn_xs remove_details"><i class="fa fa-trash"></i></button></td>';
                        html += "</tr>";
                        $("#quo_list").append(html);
                        $("#number").val('');
                        $("#product").val("").trigger("change"); //clear
                        $('#pd_quo_list').find('tr:gt(0)').remove(); //เคลียร์ค่าใน table row
                        $('#lb_cpn').hide();
                        $('#pd_quo_list').hide();
                        $('#lb_list').show();
                        $('#quo_list_select').show();
                        calculate(); //คำนวน
                        show_data();
                        console.log(response);
                    } else {
                        Swal.fire({
                            position: "mid-mid",
                            icon: "error",
                            title: "จำนวนที่เสนอ 1-999 เท่านั้น !",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        $("#number").val('');
                    }
                },
            });
        }
    });

    // ------------------------------------ ฟังชั่นลบรายการ ------------------------------------
    $(document).on('click', '.remove_details', function() {
        var row_id = $(this).attr("id");
        console.log(row_id)
        if (confirm("ต้องการลบรายการนี้ใช่หรือไม่ ?")) {
            myArray.splice(row_id - 1, 1);
            count--;
            $('#row_' + row_id + '').remove();
            $("#number").val('');
            $("#product").val("").trigger("change"); //clear
            $('#lb_cpn').hide();
            $('#pd_quo_list').hide();
            calculate();
            console.log(myArray)
        } else {

            return false;

        }
    });

    $('#myform').on('submit', function(event) { //บันทึกข้อมูลลงฐานข้อมูลจากค่าที่รับมา
        event.preventDefault();

        var form_data = $(this).serialize(); //นำค่าทั้งหมดเก็บใส่ตัวแปร
        //ดักค่าว่างวันที่
        if ($("#date").val() == "") {
            error_date = "*กรุณาเลือกวันที่ออก";
            $("#error_date").text(error_date);
            $("#date").css("border-color", "#cc0000");
            date = "";
        } else {
            error_date = "";
            $("#error_date").text(error_date);
            $("#date").css("border-color", "");
            date = $("#date").val();
        }

        if (error_date != "") {

            return false;

        } else if (error_date == "") {
            Swal.fire({
                title: "ยืนยันบันทึกข้อมูลใบเสนอซื้อ?",
                text: "โปรดตรวจสอบรายการเสนอซื้อก่อนกดยืนยัน!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ยืนยัน",
                cancelButtonText: "ยกเลิก",
                buttonsStyling: true,
            }).then((result) => {
                if (result.value === true) {
                    if ($('#btn_submit').text() == 'บันทึกการเสนอ') {
                        $.ajax({
                            type: "POST",
                            url: "../transaction/quo_insert.php",
                            data: form_data,
                            success: function(data) {
                                if (data === "เพิ่มข้อมูลสำเร็จ") {
                                    $("#message").fadeIn().html('<div class="alert alert-success"><i class="fa fa-check-circle" aria-hidden="true"></i> ' + data + "</div>");
                                    setTimeout(function() {
                                        $("#message").fadeOut("Slow");
                                    }, 1000);
                                    setTimeout(function() {
                                        $("#add").modal("hide");
                                    }, 1000);

                                    //Clear data
                                    $("#myform")[0].reset();
                                    $("#date").val("");
                                    $("#product").val("").trigger("change");
                                    $(".in-sum-price").val("");
                                    $(".in-vat").val("");
                                    $(".in-total").val("");
                                    $('#product_list').find('tr:gt(0)').remove(); //เคลียร์ค่าใน table row
                                    $('#lb_cpn').hide();
                                    $('#pd_quo_list').hide();
                                    $('#lb_list').hide();
                                    $('#quo_list_select').hide();

                                    $('#error_date').text('');
                                    $('#date').css('border-color', '');
                                    myArray = [];
                                    count = 0;
                                    show_data();
                                } else {
                                    $("#message").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + data + "</div>");
                                    setTimeout(function() {
                                        $("#message").fadeOut("Slow");
                                    }, 1000);
                                }
                                console.log(data);
                            },
                        });
                    } else if ($('#btn_submit').text() == 'อนุมัติใบเสนอ') {
                        $.ajax({
                            type: "POST",
                            url: "../transaction/quo_update.php",
                            data: form_data,
                            success: function(data) {
                                if (data === 'อัพเดทใบเสนอซื้อสำเร็จ') {
                                    $('#message').fadeIn().html('<div class="alert alert-success"><i class="fa fa-check-circle" aria-hidden="true"></i> ' + data + '</div>');
                                    setTimeout(function() {
                                        $('#message').fadeOut("Slow");
                                    }, 1000);
                                    setTimeout(function() {
                                        $('#modalAdd').modal('hide');
                                    }, 1000);
                                    //Clear data
                                    $("#myform")[0].reset();
                                    $("#date").val("");
                                    $("#product").val("").trigger("change");
                                    $(".in-sum-price").val("");
                                    $(".in-vat").val("");
                                    $(".in-total").val("");
                                    $('#product_list').find('tr:gt(0)').remove(); //เคลียร์ค่าใน table row
                                    $('#lb_cpn').hide();
                                    $('#pd_quo_list').hide();
                                    $('#lb_list').hide();
                                    $('#quo_list_select').hide();

                                    $('#error_date').text('');
                                    $('#date').css('border-color', '');
                                    myArray = [];
                                    count = 0;
                                    show_data();
                                } else {
                                    $('#message').fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + data + '</div>');
                                    setTimeout(function() {
                                        $('#message').fadeOut("Slow");
                                    }, 1000);
                                }
                                console.log(data)
                            }
                        });
                    }
                }
            });
        } else {
            $("#message").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> กรุณาตรวจสอบการทำรายการ</div>');
            setTimeout(function() {
                $("#message").fadeOut("Slow");
            }, 2000);
        }
    });

    // ---------------------------------------- แสดงรายการใบเสนอซื้อ ----------------------------------------
    $(document).on("click", ".show_data", function(e) {
        e.preventDefault();
        var q_id = $(this).data('id');
        $('#show_id').text(q_id);

        if (q_id != '') {
            $.ajax({
                url: "../product/fetch.php",
                method: "POST",
                data: {
                    quo_list: q_id
                },
                dataType: "JSON",
                success: function(response) {
                    console.log(response);
                    var html = "";
                    for (var i = 0; i < response.length; i++) {

                        var sum = parseFloat(response[i].sum_price);
                        var vat = parseFloat(response[i].vat);
                        var total = parseFloat(response[i].total_price);

                        html += '<tr class="active-row">';
                        html += '<td align="center">' + response[i].quo_order + '</td>';
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
                        html += '<td align="center">' + parseInt(response[i].number).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '</td>';
                        html += '<td align="center">' + parseFloat(response[i].price).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '</td>'
                        html += '</tr>';;
                    }
                    $("#list_show").html(html);
                    $(".in-sum-price").val(parseFloat(sum).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                    $(".in-vat").val(parseInt(vat).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                    $(".in-total").val(parseInt(total).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                }
            });
        }
    });
    // ---------------------------------------- อนุมัติรายการใบเสนอซื้อ ----------------------------------------
    $(document).on('click', '.approve', function() {
        var quo_id = $(this).data('id');
        $('#app_quo_id').text(quo_id);
        $('#q_id').val(quo_id);

        $.ajax({
            type: "POST",
            url: "../product/fetch.php",
            data: {
                quo_list: quo_id,
            },
            dataType: "JSON",
            success: function(response) {
                console.log(response)
                var html = "";
                if (response != '') {
                    for (var i = 0; i < response.length; i++) {
                        var number = parseInt(response[i].number);
                        var price = parseInt(response[i].price);
                        var costprice = parseInt(response[i].costprice);
                        console.log(costprice, response[i].quo_order);
                        html += "<tr>";
                        html += '<td align="center">' + response[i].quo_order + '<input type="hidden" name="fetch_no[]" id="fetch_no' + count + '" value="' + response[i].quo_order + '"></td>';
                        html += '<td><input type="hidden" name="fetch_product[]" id="fetch_product' + response[i].quo_order + '" value="' + response[i].product_id + '">';
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
                        html += '<td>' + response[i].cpn_name + '</td><input type="hidden" name="fetch_company[]" id="fetch_company' + response[i].quo_order + '" value="' + response[i].cpn_id + '"></td>';
                        html += '<td align="center">' + number.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '</td>';
                        html += '<td align="right">' + price.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '</td>';
                        html += '<td align="right"><input type="number" id="num_approve' + response[i].quo_order + '" data-costprice="' + costprice + '" name="num_approve[]" class="form-control num_approve" min="1" max="99"></td>';
                        html += '<input type="hidden" name="sum_price[]" id="sum_price' + response[i].quo_order + '" class="form-control sum_price">';
                        html += "</tr>";
                    }
                    $("#list_approve").html(html);
                }
            },
        });

        // ---------------------------------------- อนุมัติใบเสนอซื้อ ----------------------------------------
        $(document).on('click', '#btn_approve', function(event) {

            event.preventDefault();
            var q_id = $('#q_id').val();
            var form_data = $('#myformApprove').serialize(); //นำค่าทั้งหมดเก็บใส่ตัวแปร
            console.log(form_data);
            // var s_price = 0;
            // $(".num_approve").each(function() {
            //     var num_approve = parseInt($(this).val());
            //     var costprice = $(this).data('costprice');
            //     var s_price = costprice * num_approve;
            //     console.log(s_price);
            // });
            // $('.sum_price').val(s_price);
            if (q_id != '') {
                Swal.fire({
                    title: 'ต้องการอนุมัติใบเสนอซื้อหรือไม่?',
                    text: "ตรวจสอบรายการก่อนกดยืนยัน!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'อนุมัติ',
                    cancelButtonText: 'ยกเลิก',
                    buttonsStyling: true
                }).then((result) => {
                    if (result.value === true) {
                        $.ajax({
                            url: "../product/fetch.php",
                            method: "POST",
                            dataType: "JSON",
                            data: {
                                quo_head: q_id
                            },
                            success: function(response) {
                                // alert(data.status)
                                if (response.status == '0') {
                                    $.ajax({
                                        url: "../transaction/quo_update.php",
                                        method: "POST",
                                        data: form_data,
                                        success: function(data) {
                                            if (data == "อนุมัติสำเร็จ") {
                                                Swal.fire({
                                                    position: 'mid-mid',
                                                    icon: 'success',
                                                    title: 'อนุมัติสำเร็จ!',
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                })
                                                setTimeout(function() {
                                                    $('#approve-modal').modal('hide');
                                                }, 1000);

                                                show_data();
                                            } else {
                                                $("#messageBox").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + data + "</div>");
                                                setTimeout(function() {
                                                    $("#messageBox").fadeOut("Slow");
                                                }, 1000);
                                            }
                                            console.log(data)
                                        },
                                    });
                                } else if (response.status == '1') {
                                    Swal.fire({
                                        position: "mid-mid",
                                        icon: "warning",
                                        title: "อนุมัติแล้ว",
                                        showConfirmButton: false,
                                        timer: 1500,
                                    });
                                } else if (response.status == '2') {
                                    Swal.fire({
                                        position: "mid-mid",
                                        icon: 'error',
                                        title: 'ยกเลิกการอนุมัติแล้ว',
                                        showConfirmButton: false,
                                        timer: 1500,
                                    })
                                }
                            }
                        });
                    }
                }, )
            } else {
                alert();
            }
        });

        // ---------------------------------------- อนุมัติใบเสนอซื้อ ----------------------------------------

        $(document).on('click', '.unapprove', function() {

            var form_data = $('#edit_form').serialize(); //ดึงค่าจากฟอร์มทั้งหมด

            Swal.fire({
                title: 'ต้องการปฎิเสธการอนุมัติใบเสนอซื้อหรือไม่?',
                text: "โปรตรวจสอบการทำใบเสนอซื้อก่อนยืนยัน!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ไม่อนุมัติ',
                cancelButtonText: 'ยกเลิก',
                buttonsStyling: true
            }).then((result) => {
                if (result.value === true) {
                    $.ajax({
                        url: "fetch.php",
                        method: "POST",
                        dataType: "JSON",
                        data: {
                            quo_head: quo_id
                        },
                        success: function(data) {
                            if (data.status == '0') {
                                $.ajax({
                                    url: "unapprove.php",
                                    method: "POST",
                                    data: form_data,
                                    success: function(data) {
                                        Swal.fire(
                                            'ไม่อนุมัติสำเร็จ!',
                                            'ใบเสนอสั่งซื้อนี้ถูกยกเลิกเนื่องจากไม่อนุมัติรายการ.',
                                            'success'
                                        )
                                        show_data();
                                    },
                                });
                            } else if (data.status == '1') {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'ได้ยืนยันสถานะอนุมัติแล้ว',
                                    text: '',
                                })
                            } else if (data.status == '2') {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'ได้ยืนยันสถานะไม่อนุมัติแล้ว',
                                    text: '',
                                })
                            }
                        }
                    });
                }
            }, )
        });
    });
});