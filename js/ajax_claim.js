// ------------------------------------ Add Data  ------------------------------------
$(document).ready(function() {
    // ------------------------------------ ฟังชั่นดึงข้อมูลแสดงใบเคลม  ------------------------------------
    show_data();

    var count = 0;
    var myArray = [];

    function show_data() {
        $("#claim_table").DataTable({
            oLanguage: {
                sLengthMenu: "แสดง _MENU_ แถว",
                sZeroRecords: "ไม่เจอข้อมูลที่ค้นหา",
                sInfo: "แสดง _START_ - _END_ ทั้งหมด _TOTAL_ แถว",
                sInfoEmpty: "แสดง 0 - 0 ของ 0 แถว",
                sInfoFiltered: "(จากแถวทั้งหมด _MAX_ แถว)",
                sSearch: "ค้นหา :",
                aaSorting: [
                    [0, "desc"]
                ],
                oPaginate: {
                    sFirst: "หน้าแรก",
                    sPrevious: "ก่อนหน้า",
                    sNext: "ถัดไป",
                    sLast: "หน้าสุดท้าย",
                },
            },
            columnDefs: [{
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8], // your case first column
                className: "text-center",
            }, ],

            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "../server/show_claim.php",
                type: "POST",
            },
        });
    }

    // ------------------------------------ กำหนดค่าเริ่มต้นเมื่อมีการคลิกรับเคลมจากบริษัทคู่ค้า ------------------------------------
    $("#rc_data").click(function() {
        $("#ccp_id").val("").trigger("change");
        $("#date_rc").val("");

        $("#error_date_rc").text("");
        $("#date_rc").css("border-color", "");

        $("#lb_rc_list").hide();
        $("#receive_claim_tb").hide();
        load_claim("claim_id"); //โหลดฟังชั่น
    });

    // ------------------------------------ กำหนดค่าเริ่มต้นเมื่อมีการคลิกคืนสินค้าเคลม  ------------------------------------
    $("#return_data").click(function() {
        $("#claim_slip").val("").trigger("change");
        $("#date_rt").val("");
        $("#date_claim").html("");
        $("#date_cpn").html("");

        $("#error_date_rt").text("");
        $("#date_rt").css("border-color", "");

        $("#lb_rt_date").hide();
        $("#lb_cpn_date").hide();
        $("#lb_rt_list").hide();
        $("#return_claim_tb").hide();
        load_return("claim_id"); //โหลดฟังชั่น
    });
    // ------------------------------------- ใบเสร็จการขาย & รายการสินค้า -------------------------------------

    $("#sale_slip").selectpicker();

    load_data("sale_id");

    function load_data(type_sale, sale_id = "") {
        $.ajax({
            type: "POST",
            url: "../js/action.php",
            data: {
                type_sale: type_sale,
                sale_id: sale_id,
            },
            dataType: "JSON",
            success: function(response) {
                var html = "";

                for (var count = 0; count < response.length; count++) {
                    html += '<option value="' + response[count].id + '">' + response[count].id + " " + response[count].name + '</option>">';
                }
                if (type_sale == "sale_id") {
                    $("#sale_slip").html(html);
                    $("#sale_slip").selectpicker("refresh");
                }
            },
        });
    }

    $(document).on("change", "#sale_slip", function() {
        var sale_id = $("#sale_slip").val();
        $("#lb_date_sale").show();
        $("#lb_emp_sale").show();
        myArray = [];
        $.ajax({
            type: "POST",
            url: "../product/fetch.php",
            data: {
                sale_id: sale_id,
            },
            dataType: "JSON",
            success: function(response) {
                // console.log(response)
                var html = "";

                for (var i = 0; i < response.length; i++) {
                    var date = response[i].s_date;
                    var s_date = new Date(date);
                    var dd = String(s_date.getDate()).padStart(2, '0');
                    var mm = String(s_date.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy = s_date.getFullYear();
                    date_slip = dd + '/' + mm + '/' + yyyy;
                    $('#date_sale').text(date_slip);

                    var name = response[i].emp_name + " " + response[i].emp_lname;
                    $('#emp_sale').text(name);

                    var status = response[i].product_status;
                    var stock = parseInt(response[i].product_stock);
                    var reorder_stock = parseInt(response[i].product_reorder);
                    var amount = parseInt(response[i].s_amount);
                    html += "<tr>";
                    html += '<td align="center">';
                    if (status == 4) {
                        // เช็คาสถานะเคลม ต้องเป็น Color '4'
                        html += '<button type="button" name="add[]" id="add"  data-item_product="' + response[i].product_id + '" data-item_lot="' + response[i].lot_order + '" class="btn btn-danger btn-circle add"><i class="fa fa-plus fa-lg" aria-hidden="true"></i></button>';
                    }
                    html += "</td>";
                    html += '<td align="center">' + response[i].s_no + "</td>";
                    html += "<td>";
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
                    if (response[i].exp_date != null) {
                        html += '<div style="color:red;">' + "วันหมดอายุ " + response[i].exp_date + "</div>";
                    }
                    html += "</td>";
                    html += '<td align="right">' + amount.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + "</td>";
                    if (reorder_stock > stock) {
                        html += '<td align="right" style="color:red;">' + stock.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '</td>';
                    } else {
                        html += '<td align="right" style="color:green;">' + stock.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '</td>';
                    }
                    html += '<td align="center">' + response[i].unit_name + '</td>';
                    html += "</tr>";
                }
                $("#sale_list").html(html);
                $("#claim_list_tb").find("tr:gt(0)").remove(); // table tr remove
                $("#lb_claim").hide();
                $("#claim_list_tb").hide();
                $("#lb_sale").show();
                $("#sale_table").show();
                myArray = [];
                count = 0;
            },
        });
    });

    $(document).on("click", ".add", function() {
        var product = $.trim($(this).data("item_product"));
        var lot = $(this).data("item_lot");

        item = {};
        item.product = product;

        for (i = 0; i < myArray.length; i++) {
            if (myArray[i].product == product) {
                Swal.fire({
                    position: "mid-mid",
                    icon: "error",
                    title: "สินค้ามีอยู่ในรายการแล้ว !",
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
                claim_product: product,
                claim_lot: lot,
            },
            dataType: "JSON",
            success: function(response) {
                var stock = parseInt(response.product_stock);
                var reorder = parseInt(response.product_reorder);
                var balance = parseInt(response.lot_balance);
                console.log(stock, reorder, balance);
                if (stock != 0 && stock >= reorder) { //เช็คสต็อก
                    if (response.exp_date > response.C_DATE) { //เช็คประกัน
                        var html = "";
                        count++;
                        html = "<tr>";
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
                        html += '<input type="hidden" name="lot_order[]" id="lot_order' + count + '" class="lot_order" value="' + response.lot_order + '"></td>';
                        html += '<input type="hidden" name="lot_balance[]" id="lot_balance' + count + '" class="lot_balance" value="' + response.lot_balance + '"></td>';
                        html += '<td><input type="number" name="amount[]" id="amount' + count + '" class="form-control" min="1" max="99" value=""></td>';
                        html += '<td><input type="text" name="price[]" id="price' + count + '" class="form-control"></td>';
                        html += '<td><input type="text" name="cause[]" id="cause' + count + '" class="form-control"></td>';
                        html += "</tr>";

                        $("#claim_list").append(html);
                        $("#lb_claim").show();
                        $("#claim_list_tb").show();
                        console.log(response);

                    } else {
                        Swal.fire({
                            position: "mid-mid",
                            icon: "error",
                            title: "ประกันสินค้าหมดอายุแล้ว !",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        return false;
                    }
                } else {
                    Swal.fire({
                        position: "mid-mid",
                        icon: "error",
                        title: "คลังสต็อกสินค้าหมด !",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    return false;
                }
            },
        });
    });

    $(document).on("submit", function(event) {
        event.preventDefault();

        var form_data = $("#claim_form").serialize();
        console.log(form_data);
        //ดักค่าว่างวันที่
        if ($("#date").val() == "") {
            error_date = "*กรุณาเลือกวันที่เคลม";
            $("#error_date").text(error_date);
            $("#date").css("border-color", "#cc0000");
            date = "";
        } else {
            error_date = "";
            $("#error_date").text(error_date);
            $("#date").css("border-color", "");
            date = $("#date").val();
        }

        //ดักค่าลูกค้า
        if ($("#customer").val() == "") {
            error_customer = "*กรุณารายชื่อลูกค้า";
            $("#error_customer").text(error_customer);
            $("#customer").css("border-color", "#cc0000");
            customer = "";
        } else {
            error_customer = "";
            $("#error_customer").text(error_customer);
            $("#customer").css("border-color", "");
            customer = $("#customer").val();
        }

        if ($("input[id='claim_shop']:checked").val() || $("input[id='claim_company']:checked").val()) {
            error_claim = "";
        } else {
            error_claim = "*กรุณาเลือกการเคลม";
        }
        if (error_date == "" && error_customer == "" && error_claim == "") {
            if ($("input[id='claim_shop']:checked").val()) {
                // alert("shop");
                Swal.fire({
                    title: "ยืนยันการเคลมกับทางร้าน?",
                    text: "โปรดตรวจสอบรายการเคลมสินค้าก่อนยืนยัน!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ยืนยัน",
                    cancelButtonText: "ยกเลิก",
                    buttonsStyling: true,
                }).then((result) => {
                    if (result.value === true) {
                        $.ajax({
                            type: "POST",
                            url: "../claim/insert.php",
                            data: form_data,
                            success: function(data) {
                                if (data === "เพิ่มข้อมูลสำเร็จ") {
                                    $("#message").fadeIn().html('<div class="alert alert-success"><i class="fa fa-check-circle" aria-hidden="true"></i> ' + data + "</div>");
                                    setTimeout(function() { $("#message").fadeOut("Slow"); }, 2000);
                                    setTimeout(function() { $("#modalAdd").modal("hide"); }, 2000);
                                    $("#sale_list").empty();
                                    $("#claim_list").empty();
                                    $("#date").val("");
                                    $("#customer").val("").trigger("change");
                                    $("#lb_claim").hide();
                                    $("#claim_list_tb").hide();
                                    $("#lb_sale").hide();
                                    $("#sale_table").hide();
                                    $("#sale_slip").val("").trigger("change");
                                    $("form").trigger("reset");
                                    count = 0; //เคลียร์ค่าตัวแปร
                                    show_data(); //เรียกฟังชั่นหน้าแสดง
                                    load_data("sale_id");
                                } else if (data === "ข้อมูลผิดพลาด") {
                                    $("#message").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + data + "</div>");
                                    setTimeout(function() { $("#message").fadeOut("Slow"); }, 2000);
                                    setTimeout(function() { $("#modalAdd").modal("hide"); }, 2000);
                                    show_data(); //เรียกฟังชั่นหน้าแสดง
                                } else if (data === "ไม่มีรายการสินค้า") {
                                    $("#message").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + data + "</div>");
                                    setTimeout(function() { $("#message").fadeOut("Slow"); }, 2000);
                                } else {
                                    $("#message").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + data + "</div>");
                                    setTimeout(function() { $("#message").fadeOut("Slow"); }, 2000);
                                }
                                console.log(data);
                            },
                        });
                    }
                });
            } else if ($("input[id='claim_company']:checked").val()) {
                // alert("company");
                Swal.fire({
                    title: "ยืนยันการเคลมกับบริษัทคู่ค้า?",
                    text: "โปรดตรวจสอบรายการเคลมสินค้าก่อนยืนยัน!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ยืนยัน",
                    cancelButtonText: "ยกเลิก",
                    buttonsStyling: true,
                }).then((result) => {
                    if (result.value === true) {
                        $.ajax({
                            type: "POST",
                            url: "../claim/insert.php",
                            data: form_data,
                            success: function(data) {
                                if (data === "เพิ่มข้อมูลสำเร็จ") {
                                    $("#message").fadeIn().html('<div class="alert alert-success"><i class="fa fa-check-circle" aria-hidden="true"></i> ' + data + "</div>");
                                    setTimeout(function() { $("#message").fadeOut("Slow"); }, 2000);
                                    setTimeout(function() { $("#modalAdd").modal("hide"); }, 2000);
                                    $("#sale_list").empty();
                                    $("#claim_list").empty();
                                    $("#date").val("");
                                    $("#customer").val("").trigger("change");
                                    $("#lb_claim").hide();
                                    $("#claim_list_tb").hide();
                                    $("#lb_sale").hide();
                                    $("#sale_table").hide();
                                    $("#sale_slip").val("").trigger("change");
                                    $("form").trigger("reset");
                                    count = 0; //เคลียร์ค่าตัวแปร
                                    show_data(); //เรียกฟังชั่นหน้าแสดง
                                    load_data("sale_id");
                                } else if (data === "ข้อมูลผิดพลาด") {
                                    $("#message").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + data + "</div>");
                                    setTimeout(function() { $("#message").fadeOut("Slow"); }, 2000);
                                    setTimeout(function() { $("#modalAdd").modal("hide"); }, 2000);
                                    show_data(); //เรียกฟังชั่นหน้าแสดง
                                } else if (data === "ไม่มีรายการสินค้า") {
                                    $("#message").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + data + "</div>");
                                    setTimeout(function() { $("#message").fadeOut("Slow"); }, 2000);
                                } else {
                                    $("#message").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + data + "</div>");
                                    setTimeout(function() { $("#message").fadeOut("Slow"); }, 2000);
                                }
                                console.log(data);
                            },
                        });
                    }
                });
            }
        } else {
            $("#message").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> กรุณาตรวจสอบการทำรายการ</div>');
            setTimeout(function() { $("#message").fadeOut("Slow"); }, 2000);
        }
    });

    // ------------------------------------ กำหนดค่าเริ่มต้นเมื่อมีการคลิกเพิ่ม  ------------------------------------
    $("#add_data").click(function() {
        $("#sale_slip").val("").trigger("change");
        $("#date").val("");
        $("#customer").val("").trigger("change");

        $("#error_date").text("");
        $("#error_customer").text("");
        $("#emp_sale").text("");
        $("#date_sale").text("");

        $("#date").css("border-color", "");
        $("#customer").css("border-color", "");

        $("#lb_claim").hide();
        $("#claim_list_tb").hide();
        $("#lb_sale").hide();
        $("#sale_table").hide();
        $("#lb_c_list").hide();
        $("#lb_date_sale").hide();
        $("#lb_emp_sale").hide();


        $('input[type="radio"]').prop("checked", false);
    });


    // ------------------------------------ แสดงรายการเคลม ------------------------------------
    $(document).on("click", "#show_data", function() {
        var c_id = $(this).data("id_claim");
        var s_id = $(this).data("id_sale");

        $("#id_claim").text(c_id);
        $("#id_sale").text(s_id);
        $("#btn_update").val(c_id);

        $.ajax({
            type: "POST",
            url: "../product/fetch.php",
            data: {
                claim_show: c_id,
            },
            dataType: "JSON",
            success: function(response) {
                console.log(response);
                var html = "";
                for (var i = 0; i < response.length; i++) {
                    html += "<tr class='active-row'>";
                    html += '<td align="center">' + response[i].product_id + '<input type="hidden" name="item_pd[]" id="item_pd" value="' + response[i].product_id + '"></td>';
                    html += '<td align="center">' + response[i].cl_no + '<input type="hidden" name="item_no[]" id="item_no" value="' + response[i].cl_no + '"></td>';
                    html += '<td><input type="hidden" name="cl_id" id="cl_id" value="' + response[i].cl_id + '">';
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
                    html += "</td>";
                    html += '<td align="left">' + response[i].cl_amount + " " + response[i].unit_name + '<input type="hidden" name="item_company[]" id="item_company" value="' + response[i].cpn_id + '"></td>';
                    html += '<td align="center">' + response[i].cl_price.replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + "</td>";
                    html += '<td align="center">' + response[i].cl_cause + "</td>";
                    html += "</tr>";
                }
                $("#list_show").html(html);
            },
        });
    });

    // ------------------------------------ ยืนยันการส่งเคลมสินค้ากับบริษัทคู่ค้า ------------------------------------
    $("#btn_update").click(function(e) {
        e.preventDefault();

        // const id = $("#btn_update").val();
        var c_data = $("#claim_data").serialize();
        // console.log(c_data)

        Swal.fire({
            title: "ยืนยันการส่งเคลมกับบริษัทคู่ค้า",
            text: "โปรดตรวจเช็ครายการเคลมก่อนยืนยัน !",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก",
            buttonsStyling: true,
        }).then((result) => {
            if (result.value === true) {
                $.ajax({
                    type: "POST",
                    url: "../claim/insert.php",
                    data: c_data,
                    success: function(response) {
                        console.log(response);
                        if (response === "อัพเดทการส่งเคลมกับบริษัทคู่ค้าสำเร็จ") {
                            $("#alert_message").fadeIn().html('<div class="alert alert-success"><i class="fa fa-check-circle" aria-hidden="true"></i> ' + response + "</div>");
                            setTimeout(function() { $("#alert_message").fadeOut("Slow"); }, 2000);
                            setTimeout(function() { $("#modalShow").modal("hide"); }, 2000);
                            show_data(); //เรียกฟังชั่นหน้าแสดง
                        } else if (response === "ข้อมูลใบเคลมผิดพลาด") {
                            $("#alert_message").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + response + "</div>");
                            setTimeout(function() { $("#alert_message").fadeOut("Slow"); }, 2000);
                        } else if (response === "ข้อมูลรายการใบเคลมผิดพลาด") {
                            $("#alert_message").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + response + "</div>");
                            setTimeout(function() { $("#alert_message").fadeOut("Slow"); }, 2000);
                        } else if (response === "ไม่มีรายการ") {
                            $("#alert_message").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + response + "</div>");
                            setTimeout(function() { $("#alert_message").fadeOut("Slow"); }, 2000);
                        }
                    },
                });
            }
        });
    });

    // ------------------------------------- ใบเคลมสินค้าบริษัทคู่ค้า & รายการสินค้า -------------------------------------

    $("#ccp_id").selectpicker();

    function load_claim(type_claim, claim_id = "") {
        $.ajax({
            type: "POST",
            url: "../js/action.php",
            data: {
                type_claim: type_claim,
                claim_id: claim_id,
            },
            dataType: "JSON",
            success: function(response) {
                var html = "";

                for (var count = 0; count < response.length; count++) {
                    html += '<option value="' + response[count].id + '">' + response[count].id + '</option>">';
                }
                if (type_claim == "claim_id") {
                    $("#ccp_id").html(html);
                    $("#ccp_id").selectpicker("refresh");
                }
            },
        });
    }

    $(document).on("change", ".ccp_id", function() {
        var ccp_id = $(this).val();

        $.ajax({
            type: "POST",
            url: "../product/fetch.php",
            data: {
                ccp_id: ccp_id,
            },
            dataType: "JSON",
            success: function(response) {
                console.log(response);

                var html = "";
                var pd = "";

                for (var i = 0; i < response.length; i++) {
                    console.log(response[i].ccp_id)
                    var total = response[i].cl_amount - response[i].total;

                    if (response[i].product_name != null) {
                        pd = response[i].product_name + " ";
                    }
                    if (response[i].brand_name != null) {
                        pd += response[i].brand_name;
                    }
                    if (response[i].color_name != null) {
                        pd += " สี" + response[i].color_name;
                    }
                    if (response[i].class != null) {
                        pd += " ชั้น " + response[i].class;
                    }
                    if (response[i].tl_size != null) {
                        pd += " ขนาด (" + response[i].tl_size + ")";
                    }
                    if (response[i].pb_size != null) {
                        pd += " ขนาด (" + response[i].pb_size + ")";
                    }
                    if (response[i].ct_size != null) {
                        pd += " ขนาด (" + response[i].ct_size + ")";
                    }
                    if (response[i].pb_thick != null) {
                        pd += " หนา (" + response[i].pb_thick + ")";
                    }
                    if (response[i].cc_volume != null) {
                        pd += " ปริมาณ " + response[i].cc_volume;
                    }
                    if (response[i].cs_volume != null) {
                        pd += " ปริมาณ " + response[i].cs_volume;
                    }
                    if (response[i].cm_volume != null) {
                        pd += " ปริมาณ " + response[i].cm_volume;
                    }
                    html += "<tr>";
                    html += '<td align="center"><input type="checkbox" id="' + response[i].ccp_id + '" data-item_no="' + response[i].ccp_no + '" data-item_product="' + response[i].product_id + '" data-pd_name="' + pd + '" data-item_company="' + response[i].cpn_id + '" data-cpn_name="' + response[i].cpn_name + '" data-item_amount="' + response[i].cl_amount + '" data-rc_amount="' + response[i].total + '" data-status="' + response[i].ccp_status + '"  data-total="' + total + '" data-item_unit="' + response[i].unit_name + '" data-lot_order="' + response[i].lot_order + '" data-cl_id="' + response[i].cl_id + '" data-cr_id="' + response[i].cr_id + '" class="check_box" /></td>';
                    html += '<td align="center">' + response[i].ccp_no + "</td>";
                    html += "<td>" + pd + "</td>";
                    html += '<td>' + total + " " + response[i].unit_name + '</td>';
                    if (response[i].ccp_status == '1') {
                        html += '<td align="center"><div class="mx-auto bg-success" style="font-size:12px;width:120px;border-radius:6px;color:#4440BB;"><a style="color:#FFFFFF;"><i class="fa fa-check" aria-hidden="true"></i>&ensp;รับครบ</a></div></td>';
                    } else {
                        html += '<td></td>';
                    }
                    html += "</tr>";
                    console.log(response[i].ccp_no, response[i].product_id, response[i].total, total);
                }
                $("#lb_c_list").show();
                $("#receive_claim_tb").show();
                $("#list_table").html(html);
            },
        });
    });

    $(document).on("click", ".check_box", function() {
        var html = "";

        if (this.checked) {
            html += '<td align="center"><input type="checkbox" id="' + $(this).attr("id") + '" data-item_no="' + $(this).data("item_no") + '" data-item_product="' + $(this).data("item_product") + '" data-pd_name="' + $(this).data("pd_name") + '" data-cpn_name="' + $(this).data("cpn_name") + '" data-item_company="' + $(this).data("item_company") + '" data-item_amount="' + $(this).data("item_amount" + $(this).data("item_no") + "") + '" data-total="' + $(this).data("total") + '" data-status="' + $(this).data("status") + '" data-rc_amount="' + $(this).data("rc_amount") + '" data-item_unit="' + $(this).data("item_unit") + '" data-lot_order="' + $(this).data("lot_order") + '" data-cl_id="' + $(this).data("cl_id") + '" data-cr_id="' + $(this).data("cr_id") + '" class="check_box" checked /></td>';
            html += '<td align="center">' + $(this).data("item_no") + '<input type="hidden" name="hidden_id" class="form-control hidden_id" value="' + $(this).attr("id") + '" /><input type="hidden" name="item_no[]" class="form-control item_no" value="' + $(this).data("item_no") + '" /><input type="hidden" name="lot_order[]" id="lot_order' + $(this).data("item_no") + '" class="form-control lot_order" value="' + $(this).data("lot_order") + '" /></td>';
            html += '<td>' + $(this).data("pd_name") + '<input type="hidden" name="item_pd_c[]" id="item_pd_c' + $(this).data("item_no") + '" class="form-control item_pd_c" value="' + $(this).data("item_product") + '" /></td>';
            html += '<td>' + $(this).data("total") + " " + $(this).data("item_unit") + '<input type="hidden" name="cl_id" class="form-control cl_id" value="' + $(this).data("cl_id") + '" /><input type="hidden" name="cr_id" class="form-control cr_id" value="' + $(this).data("cr_id") + '" /></td>';
            if ($(this).data("status") == '1') {
                html += '<td align="center"><div class="mx-auto bg-success" style="font-size:12px;width:120px;border-radius:6px;color:#4440BB;"><a style="color:#FFFFFF;"><i class="fa fa-check" aria-hidden="true"></i>&ensp;รับครบ</a></div></td>';
            } else {
                html += '<td align="center"><input type="number" name="item_amount[]" id="item_amount' + $(this).data("item_no") + '" class="form-control item_amount" min="1" max="' + $(this).data("total") + '" data-item_amount="' + $(this).data("item_amount") + '" data-rc_amount="' + $(this).data("rc_amount") + '" data-total="' + $(this).data("total") + '"></td>';
            }
        } else {
            html += '<td align="center"><input type="checkbox" id="' + $(this).attr("id") + '"  data-item_no="' + $(this).data("item_no") + '" data-item_company="' + $(this).data("item_company") + '" data-item_product="' + $(this).data("item_product") + '" data-pd_name="' + $(this).data("pd_name") + '" data-cpn_name="' + $(this).data("cpn_name") + '" data-item_amount="' + $(this).data("item_amount" + $(this).data("item_no") + "") + '" data-total="' + $(this).data("total") + '" data-status="' + $(this).data("status") + '" data-rc_amount="' + $(this).data("rc_amount") + '" data-item_unit="' + $(this).data("item_unit") + '" data-cl_id="' + $(this).data("cl_id") + '" class="check_box" /></td>';
            html += '<td align="center">' + $(this).data("item_no") + '<input type="hidden" name="item_no[]" class="form-control item_no" value="' + $(this).data("item_no") + '"></td>';
            html += "<td>" + $(this).data("pd_name") + "</td>";
            html += "<td>" + $(this).data("total") + " " + $(this).data("item_unit") + "</td>";
            if ($(this).data("status") == '1') {
                html += '<td align="center"><div class="mx-auto bg-success" style="font-size:12px;width:120px;border-radius:6px;color:#4440BB;"><a style="color:#FFFFFF;"><i class="fa fa-check" aria-hidden="true"></i>&ensp;รับครบ</a></div></td>';
            } else {
                html += '<td><input type="hidden" name="item_amount[]" id="item_amount' + $(this).data("item_no") + '" class="form-control item_amount" ></td>';
            }
        }
        $(this).closest("tr").html(html);
    });

    $('#btn_submit').click(function(e) {
        e.preventDefault();

        var form_data_rc = $("#claim_form_rc").serialize();

        //เช็คการเลือกรายการ
        if (!$("input.check_box").is(":checked")) {
            error_check = "กรุณาทำรายการ";
            $("#message_receive").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + error_check + "</div>");
            setTimeout(function() { $("#message_receive").fadeOut("Slow"); }, 2000);
            check = "";
        } else {
            error_check = "";
            $("#error_check").text(error_check);
            $(".check_box").css("border-color", "");
            check = $(".check_box").val();
        }

        if ($("#date_rc").val() == "") {
            error_date_rc = "*กรุณาเลือกวันที่รับเคลม";
            $("#error_date_rc").text(error_date_rc);
            $("#date_rc").css("border-color", "#cc0000");
            date_rc = "";
        } else {
            error_date_rc = "";
            $("#error_date_rc").text(error_date_rc);
            $("#date_rc").css("border-color", "");
            date_rc = $("#date_rc").val();
        }

        //เช็คจำนวนรับรายการ
        $(".item_amount").each(function() {
            var num = 0;
            var amount = parseInt($(this).data("item_amount")); // จำนวนที่ส่งเคลม
            var total_amount = parseInt(num + $(this).data("rc_amount")); // จำนวนที่รับมาแล้ว
            var value = parseInt($(this).val()); // จำนวนต้องการรับ
            var balance = parseInt($(this).data("total")); //ผลลัพธ์เมื่อรับครบ
            var result = total_amount + value; //ผลลัพธ์ที่เกินส่งเคลม

            console.log("จำนวนส่งเคลม" + amount, "จำนวนรับ" + value, "จำนวนในระบบ" + total_amount, "คงเหลือ" + balance)

            if ($(this).val() == "" && !$("input.check_box").is(":checked")) {
                error_amount = "*กรุณาใส่จำนวนรับ";
                $("#message_receive").fadeIn().html('<div class="alert alert-danger">' + error_amount + "</div>");
            } else if ($(this).val() == "0" || $(this).val() < "0") {
                Swal.fire({
                    position: "mid-mid",
                    icon: "error",
                    title: "โปรดใส่จำนวนรับที่มากกว่า 0",
                    showConfirmButton: false,
                    timer: 1500,
                });
            } else if (result > amount) {
                Swal.fire({
                    position: "mid-mid",
                    icon: "error",
                    title: "จำนวนรับมากกว่าจำนวนที่เคลม",
                    showConfirmButton: false,
                    timer: 1500,
                });
            } else if (balance == '0') {
                Swal.fire({
                    position: "mid-mid",
                    icon: "error",
                    title: "รับสินค้าหมดแล้ว",
                    showConfirmButton: false,
                    timer: 1500,
                });
            } else {
                error_amount = "";

            }
        });

        if (error_check == '' && error_date_rc == '' && error_amount == '') {

            Swal.fire({
                title: "ยืนยันการรับสินค้าเคลม?",
                text: "โปรตรวจสอบรายการสินค้าที่จะรับก่อนกดยืนยัน!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ยืนยัน",
                cancelButtonText: "ยกเลิก",
                buttonsStyling: true,
            }).then((result) => {
                if (result.value === true) {

                    $.ajax({
                        type: "POST",
                        url: "../claim/insert.php",
                        data: form_data_rc,
                        success: function(data) {
                            if (data === "อัพเดทรายการเคลมสำเร็จ") {
                                $("#message_receive").fadeIn().html('<div class="alert alert-success"><i class="fa fa-check-circle" aria-hidden="true"></i> ' + data + "</div>");
                                setTimeout(function() { $("#message_receive").fadeOut("Slow"); }, 2000);
                                $("#date_rc").val("");
                                $("#receive_claim_tb").hide();
                                $("#lb_rc_list").hide();
                                $("#ccp_id").val("").trigger("change");
                                $("form").trigger("reset");
                                show_data();
                                load_claim("claim_id"); //โหลดฟังชั่น
                            } else {
                                $("#message_receive").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + data + "</div>");
                                setTimeout(function() { $("#message_receive").fadeOut("Slow"); }, 2000);
                                show_data();
                            }
                            console.log(data);
                        },
                    });
                }
            });
        } else {
            $("#message_receive").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> กรุณาตรวจสอบการทำรายการ</div>');
            setTimeout(function() {
                $("#message_receive").fadeOut("Slow");
            }, 2000);
        }
    });

    // ------------------------------------- ใบเสร็จการขาย & รายการสินค้า -------------------------------------

    $("#claim_slip").selectpicker();

    function load_return(type_claim_slip, claim_id = "") {
        $.ajax({
            type: "POST",
            url: "../js/action.php",
            data: {
                type_claim_slip: type_claim_slip,
                claim_id: claim_id,
            },
            dataType: "JSON",
            success: function(response) {
                var html = "";

                for (var count = 0; count < response.length; count++) {
                    html += '<option value="' + response[count].id + '">' + response[count].id + '</option>">';
                }
                if (type_claim_slip == "claim_id") {
                    $("#claim_slip").html(html);
                    $("#claim_slip").selectpicker("refresh");
                }
            },
        });
    }

    $(document).on("change", "#claim_slip", function() {
        var claim_id = $("#claim_slip").val();
        $("#lb_rt_date").show();
        $("#lb_cpn_date").show();
        $("#lb_rt_list").show();
        $("#return_claim_tb").show();
        myArray = [];

        $.ajax({
            type: "POST",
            url: "../product/fetch.php",
            data: {
                claim_id: claim_id,
            },
            dataType: "JSON",
            success: function(response) {
                // console.log(response)
                var html = "";

                for (var i = 0; i < response.length; i++) {
                    var date = response[i].cl_date;
                    var ccp_date = response[i].ccp_date;
                    var stock = parseInt(response[i].product_stock);
                    var reorder_stock = parseInt(response[i].product_reorder);
                    var cl_amount = parseInt(response[i].cl_amount);
                    var receive = parseInt(response[i].receive);
                    html += '<tr>';
                    html += '<td align="center">' + response[i].cl_no + '</td>';
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
                    if (response[i].exp_date != null) {
                        html += '<div style="color:red;">' + "วันหมดอายุ " + response[i].exp_date + '</div>';
                    }
                    html += '</td>';
                    if (reorder_stock > stock) {
                        html += '<td align="center" style="color:red;">' + stock.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '</td>';
                    } else {
                        html += '<td align="center" style="color:green;">' + stock.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '</td>';
                    }
                    html += '<td align="center">' + cl_amount.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '</td>';
                    if (isNaN(receive)) {
                        html += '<td align="center">' + (0).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '</td>';
                    } else {
                        html += '<td align="center">' + receive.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '</td>';
                    }
                    html += '<td align="center">' + response[i].unit_name + '</td>';
                    if (cl_amount === receive) {
                        html += '<td align="center"><div class="mx-auto bg-success" style="font-size:12px;width:100px;border-radius:6px;color:#4440BB;"><a style="color:#FFFFFF;"><i class="fa fa-check" aria-hidden="true"></i>&ensp;รับครบ</a></div></td>';
                    } else if (isNaN(receive)) {
                        html += '<td align="center"><div style="font-size:12px;width:100px;border-radius:6px;color:#4440BB;"><a style="color:red;">ยังไม่มีการรับ</a></div></td>';
                    } else {
                        html += '<td></td>';
                    }
                    html += '<input type="hidden" name="cl_id" id="cl_id" value="' + response[i].cl_id + '" />';
                    html += '<input type="hidden" name="no_list[]" id="no_list" value="' + response[i].cl_no + '" />';
                    html += '<input type="hidden" name="pd_lot[]" id="pd_lot' + response[i].cl_no + '" value="' + response[i].product_id + '" />';
                    html += '<input type="hidden" name="lot[]" id="lot' + response[i].cl_no + '" value="' + response[i].lot_order + '" />';
                    html += '<input type="hidden" name="lot_balance[]" id="lot_balance' + response[i].cl_no + '" value="' + response[i].lot_balance + '" />';
                    html += '<input type="hidden" name="receive_number[]" class="receive_number" id="receive_number' + response[i].cl_no + '" data-stock="' + stock + '" value="' + receive + '" />';
                    html += '</tr>';
                }
                $("#list_return_tb").html(html);
                var today = new Date(date);
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();

                cl_date = dd + '/' + mm + '/' + yyyy;
                $("#date_claim").html(cl_date);

                var today2 = new Date(ccp_date);
                var dd2 = String(today2.getDate()).padStart(2, '0');
                var mm2 = String(today2.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy2 = today2.getFullYear();

                ccp_date = dd2 + '/' + mm2 + '/' + yyyy2;
                $("#date_cpn").html(ccp_date);
            },
        });
    });

    $("#btn_return").click(function(e) {
        e.preventDefault();


        //เช็คจำนวนรับรายการ
        $(".receive_number").each(function() {
            var stock = parseInt($(this).data("stock")); // จำนวนที่ส่งเคลม
            var receive = parseInt($(this).val()); // จำนวนต้องการรับ
            console.log("สต็อก = ", stock, "จำนวนรับ =", receive)
            if (stock < receive) {
                error_stock = "กรุณาตรวจสอบสต็อกในรายการ";
                Swal.fire({
                    position: "mid-mid",
                    icon: "error",
                    title: "กรุณาตรวจสอบสต็อกในรายการ",
                    showConfirmButton: false,
                    timer: 1500,
                });
                return false;
            } else {
                error_stock = "";
            }
        });

        if (error_stock == "") {
            var data = $("#claim_form_return").serialize();
            console.log(data)
            Swal.fire({
                title: "ยืนยันสถานะการคืนสินค้าเคลม",
                text: "โปรดตรวจสอบรายการเคลมก่อนยืนยัน !",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ยืนยัน",
                cancelButtonText: "ยกเลิก",
                buttonsStyling: true,
            }).then((result) => {
                if (result.value === true) {
                    $.ajax({
                        type: "POST",
                        url: "../claim/insert.php",
                        data: data,
                        success: function(response) {
                            console.log(response);
                            if (response === "คืนสินค้าสำเร็จ") {
                                $("#message_return").fadeIn().html('<div class="alert alert-success"><i class="fa fa-check-circle" aria-hidden="true"></i> ' + response + "</div>");
                                setTimeout(function() { $("#message_return").fadeOut("Slow"); }, 2000);
                                setTimeout(function() { $("#modalReturn").modal("hide"); }, 2000);
                                show_data(); //เรียกฟังชั่นหน้าแสดง
                            } else {
                                $("#message_return").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + response + "</div>");
                                setTimeout(function() { $("#message_return").fadeOut("Slow"); }, 2000);
                            }
                        },
                    });
                }
            });
        }
    });

    $(document).on("click", ".show_data_print", function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#show_id').text(id);
        console.log(id);

        if (id != '') {
            $.ajax({
                url: "../product/fetch.php",
                method: "POST",
                data: {
                    claim_list: id
                },
                dataType: "JSON",
                success: function(response) {
                    console.log(response);
                    var html = "";
                    for (var i = 0; i < response.length; i++) {
                        html += '<tr class="active-row">';
                        html += '<td align="center">' + response[i].cpn_id + '</td>';
                        html += '<td align="center">' + response[i].cpn_name + '</td>';
                        html += '<td align="center"><a href="../mpdf/claim_mpdf.php?cl_id=' + response[i].cl_id + '&cpn_id=' + response[i].cpn_id + '" class="btn btn-primary btn-xs"><i class="fa fa-print" aria-hidden="true"></i></a></td>';
                        html += '</tr>';;
                    }
                    $("#cpn_list").html(html);
                    show_data(); //เรียกฟังชั่นหน้าแสดง
                }
            });
        }
    });
});