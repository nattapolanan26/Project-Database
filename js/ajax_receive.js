$(document).ready(function() {
    function show_data() {
        $("#show_receive").DataTable({
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
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "../server/show_data.php",
                type: "POST",
            },
        });
    }

    show_data(); //เรียกฟังชั่นหน้าแสดง

    // ---------------------------------------------------- เพิ่มข้อมูล ----------------------------------------------------
    $(document).on("click", ".add_data", function() {
        $("#lb_s_list").hide();
        $("#receive_table").hide();
        $("#c1").hide();
        $("#c2").hide();
        $("#lb_check1").hide();
        $("#lb_check2").hide();

        $(document).on("change", ".p_order", function() {
            var po_id = $(this).val();
            // alert(po_id)
            $.ajax({
                type: "POST",
                url: "../product/fetch.php",
                data: {
                    po_id: po_id,
                },
                dataType: "JSON",
                success: function(response) {
                    console.log(response);

                    var pd = "";
                    var html = "";
                    for (var i = 0; i < response.length; i++) {
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
                        html += '<td align="center"><input type="checkbox" id="' +
                            response[i].order_id + '" data-item_no="' +
                            response[i].order_no + '" data-item_product="' +
                            response[i].product_id + '" data-pd_name="' + pd + '" data-item_company="' +
                            response[i].cpn_id + '" data-cpn_name="' +
                            response[i].cpn_name + '" data-item_number="' +
                            response[i].number + '" data-item_rp_number="' +
                            response[i].total + '" data-item_status="' +
                            response[i].product_status + '" data-costprice="' +
                            response[i].costprice + '" class="check_box" /></td>';
                        html += '<td align="center">' + response[i].order_no + "</td>";
                        html += "<td>" + pd + "</td>";
                        html += "<td>" + response[i].cpn_name + "</td>";
                        html += "<td>" + parseInt(response[i].costprice).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + "</td>";
                        html += '<td align="center"><a style="color:blue;">' + response[i].number + "</a></td>";
                        if (response[i].total != null) {
                            html += '<td align="center"><a style="color:green;">' + response[i].total + "</a></td>";
                        } else {
                            html += '<td align="center"><a>' + "0" + "</a></td>";
                        }
                        html += "<td></td>";
                        html += "<td></td>";
                        html += "</tr>";
                    }
                    $("#lb_s_list").show();
                    $("#receive_table").show();
                    $("#c1").show();
                    $("#c2").show();
                    $("#lb_check1").show();
                    $("#lb_check2").show();
                    $("#list_table").html(html);
                },
            });
        });

        $(document).on("click", ".check_box", function() {
            var html = "";

            if (this.checked) {
                var total = $(this).data("item_number") - $(this).data("item_rp_number"); // รวมจำนวนรับ
                var no = $(this).data("item_no"); // ลำดับที่

                html += '<td align="center"><input type="checkbox" id="' + $(this).attr("id") + '" data-item_no="' + $(this).data("item_no") + '" data-item_product="' + $(this).data("item_product") + '" data-pd_name="' + $(this).data("pd_name") + '" data-cpn_name="' + $(this).data("cpn_name") + '" data-item_company="' + $(this).data("item_company") + '" data-item_number="' + $(this).data("item_number") + '" data-item_rp_number="' + $(this).data("item_rp_number") + '" data-costprice="' + $(this).data("costprice") + '" class="check_box" checked /></td>';
                html += '<td align="center">' + $(this).data("item_no") + '<input type="hidden" name="hidden_id" class="form-control hidden_id" value="' + $(this).attr("id") + '" /><input type="hidden" name="item_no[]" class="form-control item_no" value="' + $(this).data("item_no") + '" data-item_number="' + $(this).data("item_number") + '" data-item_rp_number="' + $(this).data("item_rp_number") + '" /></td>';
                html += "<td>" + $(this).data("pd_name") + '<input type="hidden" name="item_product[]" id="item_product' + $(this).data("item_no") + '" class="form-control item_product" value="' + $(this).data("item_product") + '" /></td>';
                html += "<td>" + $(this).data("cpn_name") + '<input type="hidden" name="item_company[]" id="item_company' + $(this).data("item_no") + '" class="form-control item_company" value="' + $(this).data("item_company") + '" /></td>';
                html += '<td>' + parseInt($(this).data("costprice")).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '<input type="hidden" name="costprice[]" id="costprice' + $(this).data("item_no") + '" class="form-control costprice" value="' + $(this).data("costprice") + '" /></td>';
                html += '<td align="center"><a style="color:blue;">' + $(this).data("item_number") + "</a></td>";
                if ($(this).data("item_rp_number")) {
                    html += '<td align="center"><a style="color:green;">' + $(this).data("item_rp_number") + "</a>" + "</td>";
                } else {
                    html += '<td align="center"><a>' + "0" + "</a></td>";
                }
                html += '<td><input type="number" name="txt_number[]" id="txt_number' + $(this).data("item_no") + '" value="" data-item_number="' + $(this).data("item_number") + '" data-item_rp_number="' + $(this).data("item_rp_number") + '" class="form-control txt_number" min="1" max="999"></td>';
                html += '<td><input type="number" name="item_date_exp[]" id="item_date_exp' + $(this).data("item_no") + '" class="form-control item_date_exp" value="" min="1" max="99" placeholder="ใส่ปี เช่น 1,2,3..."></td>';

                $(document).on("change", ".item_radio", function() {
                    if ($(this).val() == "quite") {
                        $("#txt_number" + no + "").val(total); //เอาจำนวนสั่งซื้อใส่ใน value เมื่อกดรับทั้งหมด
                    } else {
                        $("#txt_number" + no + "").val("");
                    }
                });
            } else {
                html = '<td align="center"><input type="checkbox" id="' + $(this).attr("id") + '"  data-item_no="' + $(this).data("item_no") + '" data-item_product="' + $(this).data("item_product") + '" data-pd_name="' + $(this).data("pd_name") + '" data-item_company="' + $(this).data("item_company") + '" data-cpn_name="' + $(this).data("cpn_name") + '" data-item_number="' + $(this).data("item_number") + '" data-item_rp_number="' + $(this).data("item_rp_number") + '" data-costprice="' + $(this).data("costprice") + '" class="check_box" /></td>';
                html += '<td align="center">' + $(this).data("item_no") + '<input type="hidden" name="item_no[]" class="form-control item_no" value="' + $(this).data("item_no") + '" /><input type="hidden" name="hidden_id[]" value="' + $(this).attr("id") + '" /></td>';
                html += "<td>" + $(this).data("pd_name") + "</td>";
                html += "<td>" + $(this).data("cpn_name") + "</td>";
                html += '<td>' + parseInt($(this).data("costprice")).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + "</td>";
                html += '<td align="center"><a style="color:blue;">' + $(this).data("item_number") + "</a></td>";
                if ($(this).data("item_rp_number")) {
                    html += '<td align="center"><a style="color:green;">' + $(this).data("item_rp_number") + "</a>" + "</td>";
                } else {
                    html += '<td align="center"><a>' + "0" + "</a></td>";
                }
                html += "<td></td>";
                html += "<td></td>";
            }
            $(this).closest("tr").html(html);
        });

        // ---------------------------------------------------- บันทึกการรับสินค้า ----------------------------------------------------
        $(document).on("submit", function(event) {
            event.preventDefault();

            var form_data = $("#receive_form").serialize();
            var error_exp = "";
            var error_number = "";
            if (!$("input.check_box").is(":checked")) {
                error_check = "กรุณาทำรายการ";
                $("#message").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + error_check + "</div>");
                setTimeout(function() { $("#message").fadeOut("Slow"); }, 2000);
                check = "";
            } else {
                error_check = "";
                $("#error_check").text(error_check);
                $(".check_box").css("border-color", "");
                check = $(".check_box").val();
            }

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

            function check_number() {
                $(".txt_number").each(function() {
                    var num = 0;
                    var po_number = parseInt($(this).data("item_number")); // จำนวนสั่งซื้อ
                    var rp_number = parseInt(num + $(this).data("item_rp_number")); // จำนวนที่รับมา
                    var value = parseInt($(this).val()); // จำนวนต้องการรับ
                    var sum = rp_number + value;

                    if ($(this).val() == "") {
                        error_number = "*กรุณาใส่จำนวนรับ";
                        $("#message")
                            .fadeIn()
                            .html('<div class="alert alert-danger">' + error_number + "</div>");
                    } else if ($(this).val() == "0" || $(this).val() < "0") {
                        Swal.fire({
                            position: "mid-mid",
                            icon: "error",
                            title: "โปรดใส่จำนวนรับที่มากกว่า 0",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    } else if (sum > po_number) {
                        Swal.fire({
                            position: "mid-mid",
                            icon: "error",
                            title: "จำนวนรับมากกว่าจำนวนสั่งซื้อ",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    } else {
                        error_number = "";
                    }
                });
            }

            function date_exp() {
                $(".item_date_exp").each(function() {
                    var date = new Date();
                    year = date.getFullYear();

                    d_exp = date.getFullYear() + parseInt($(this).val()); //ตัดให้เหลือปี + ค่าที่รับ
                    console.log(year, d_exp);

                    if (d_exp == "") {
                        error_exp = "กรุณาใส่วันหมดอายุ";
                        Swal.fire({
                            position: "mid-mid",
                            icon: "error",
                            title: "กรุณาใส่วันหมดอายุ",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    } else if (d_exp <= year) {
                        error_exp = "ใส่ปีหมดอายุที่มากกว่าวันปัจจุบัน";
                        Swal.fire({
                            position: "mid-mid",
                            icon: "error",
                            title: "ใส่ปีหมดอายุที่มากกว่าวันปัจจุบัน",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    } else if (d_exp >= '2999') {
                        error_exp = "โปรดใส่จำนวนปีให้ถูกต้อง";
                        Swal.fire({
                            position: "mid-mid",
                            icon: "error",
                            title: "โปรดใส่จำนวนปีให้ถูกต้อง",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    } else {
                        error_exp = "";
                    }
                });
            }

            check_number();
            date_exp();

            if (error_date == "" && error_check == "" && error_number == "" && error_exp == "") {
                Swal.fire({
                    title: "ยืนยันบันทึกข้อมูล?",
                    text: "โปรตรวจสอบการรับสินค้าก่อนยืนยัน!",
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
                            url: "../transaction/receive_insert.php",
                            method: "POST",
                            data: form_data,
                            success: function(data) {
                                if (data === "เพิ่มข้อมูลสำเร็จ") {
                                    $("#message")
                                        .fadeIn()
                                        .html(
                                            '<div class="alert alert-success"><i class="fa fa-check-circle" aria-hidden="true"></i> ' +
                                            data +
                                            "</div>"
                                        );
                                    setTimeout(function() {
                                        $("#message").fadeOut("Slow");
                                    }, 2000);
                                    $("form").trigger("reset");
                                    $("#receive_table").find("tr:gt(0)").remove(); // table tr remove
                                    $("#p_order").val(0); // selectbox reset
                                    $("#date").val(""); // selectbox reset
                                    count = 0; //เคลียร์ค่าตัวแปร
                                    show_data(); //เรียกฟังชั่นหน้าแสดง
                                    $("#p_order").val("").trigger("change");
                                } else {
                                    $("#message")
                                        .fadeIn()
                                        .html(
                                            '<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' +
                                            data +
                                            "</div>"
                                        );
                                    setTimeout(function() {
                                        $("#message").fadeOut("Slow");
                                    }, 2000);
                                    setTimeout(function() {
                                        $("#modalAdd").modal("hide");
                                    }, 2000);
                                    $("#p_order").val("").trigger("change");
                                }
                                console.log(data);
                                show_data();
                            },
                        });
                    }
                });
            } else {
                check_exp();
                check_number();
            }
        });
    });

    //แสดงข้อมูล
    $(document).on("click", "#show_data", function() {
        var id = $(this).data("id");
        $("#po_id").text(id);
        $.ajax({
            type: "POST",
            url: "../product/fetch.php",
            data: {
                show_receive: id,
            },
            dataType: "JSON",
            success: function(response) {
                console.log(response);
                var html = "";
                for (var i = 0; i < response.length; i++) {
                    html += "<tr>";
                    html += '<td align="center">' + response[i].product_id + "</td>";
                    html += '<td align="center">' + response[i].rp_no + "</td>";
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
                    ("</td>");
                    html +=
                        '<td align="right">' +
                        response[i].rp_number +
                        " " +
                        response[i].unit_name +
                        "</td>";
                    html += "</tr>";
                }
                $("#list_show").html(html);
            },
        });
    });
});