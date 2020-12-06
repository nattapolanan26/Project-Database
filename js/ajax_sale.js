$(document).ready(function() {
    myArray = [];
    var count = 0;
    $("#st_modal").click(function() {
        $("#lb_salelist").hide();
        $("#sale_table").hide();
    });

    function show_data() {
        $("#show_saleslip").DataTable({
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
                    targets: [0, 1, 2, 4], // your case first column
                    className: "text-center",
                },
                {
                    targets: 3,
                    className: "text-left",
                    width: "15%",
                },
            ],

            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "../server/show_sale.php",
                type: "POST",
            },
        });
    }

    show_data(); //เรียกฟังชั่นหน้าแสดง

    // ------------------------------------- ราคารวม -------------------------------------
    function sum_price() {
        var sum = 0;
        var vat = 0;
        var subvat = 0;
        $(".sum").each(function() {
            var sum_list = parseFloat($(this).val());
            sum = sum + sum_list;
            subvat = sum * (7 / 107);
            total = sum - subvat;
            vat = total * (7 / 100); //vat
        });
        $(".in-amount-sum").val(total.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
        $(".cal-amount-sum").val(total); //เก็บจำนวนรวมราคา
        $(".in-vat").val(vat.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
        $(".cal-vat").val(vat); //เก็บจำนวนรวมราคา
    }

    // ------------------------------------- ส่วนลด -------------------------------------
    function discount_item() {
        var totalDiscount = 0;

        $(".discount").each(function() {
            var discount = parseFloat($(this).val());
            if (isNaN(discount)) {
                totalDiscount = totalDiscount + 0;
            } else {
                totalDiscount = totalDiscount + discount;
            }
        });

        $(".in-discount").val(
            totalDiscount.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")
        );
        $(".cal-discount").val(totalDiscount); //เก็บจำนวนส่วนลด
    }

    // ------------------------------------- ราคารวมทั้งสิ้น -------------------------------------
    function total_price() {
        var sum = parseFloat($(".cal-amount-sum").val());
        var discount = parseFloat($(".cal-discount").val());
        var vat = parseFloat($(".cal-vat").val());
        total = (sum + vat) - discount;
        console.log(sum, discount, total);

        $(".in-showtotal").val(
            total.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")
        );
        $(".in-total").val(total); //เก็บจำนวนรวม
    }

    // ------------------------------------- เครียข้อมูล -------------------------------------
    function clear_value() {
        $("#product").val("").trigger("change");
        $("#promotion").val("").trigger("change");
        $("#product_pmt").val("").trigger("change");
        $("#number").val("");

        $("#error_product").text("");
        $("#error_number").text("");
        $("#error_promotion").text("");
        $("#error_product_pmt").text("");

        $("#product").css("border-color", "");
        $("#number").css("border-color", "");
        $("#promotion").css("border-color", "");
        $("#product_pmt").css("border-color", "");

        $("#add_data").text("เพิ่มรายการ");
        // $("#modalAdditem").modal("show");

        $(".product").hide();
        $(".product_pmt").hide();
        $(".promotion").hide();
        $(".lb_pmt").hide();
        $(".lb_pd_pmt").hide();
        $(".lb_pd").hide();
        $(".number").hide();
        $(".lb_number").hide();
        $("#alert_pd").hide();
        $('input[type="radio"]').prop("checked", false);
    }
    // ------------------------------------- โปรโมชั่น & รายการสินค้า -------------------------------------

    $("#promotion").selectpicker();
    $("#product_pmt").selectpicker();
    $("#product").selectpicker();

    load_product("product");

    function load_product(type_pd, product = "") {
        $.ajax({
            type: "POST",
            url: "../js/action.php",
            data: {
                type_pd: type_pd,
                product: product,
            },
            dataType: "JSON",
            success: function(response) {
                var html = "";
                var count = 0;

                for (count; count < response.length; count++) {
                    html +=
                        '<option value="' +
                        response[count].id +
                        '">' +
                        response[count].name +
                        "</option>";
                }
                if (type_pd == "product") {
                    $("#product").html(html);
                    $("#product").selectpicker("refresh");
                }
            },
        });
    }

    load_data("pmt_data");

    function load_data(type_pmt, promotion_id = "") {
        $.ajax({
            type: "POST",
            url: "../js/action.php",
            data: {
                type_pmt: type_pmt,
                promotion_id: promotion_id,
            },
            dataType: "JSON",
            success: function(response) {
                var html = "";
                var count = 0;

                for (count; count < response.length; count++) {
                    html +=
                        '<option value="' +
                        response[count].id +
                        '">' +
                        response[count].name +
                        "</option>";
                }
                if (type_pmt == "pmt_data") {
                    $("#promotion").html(html);
                    $("#promotion").selectpicker("refresh");
                } else {
                    $("#product_pmt").html(html);
                    $("#product_pmt").selectpicker("refresh");
                }
            },
        });
    }

    $(document).on("change", "#promotion", function() {
        $("#product_pmt").val("").trigger("change");
        var promotion_id = $("#promotion").val();
        load_data("product_pmt_data", promotion_id);
    });



    // ------------------------------------ กำหนดค่าเริ่มต้นเมื่อมีการคลิกเพิ่ม  ------------------------------------
    $("#add-list").click(function() {
        clear_value();
    });

    // ------------------------------------ ฟังชั่นเพิ่มรายการ  ------------------------------------
    $("#add_data").click(function() {
        //ดักค่าว่างโปรโมชั่น
        if (
            $("#promotion").val() == "" &&
            $("input[id='have_pmt']:checked").val()
        ) {
            error_promotion = "*กรุณาเลือกโปรโมชั่น";
            $("#error_promotion").text(error_promotion);
            $("#promotion").css("border-color", "#cc0000");
            promotion = "";
        } else {
            error_promotion = "";
            $("#error_promotion").text(error_promotion);
            $("#promotion").css("border-color", "");
            promotion = $("#promotion").val(); //เอาค่าใส่ตัวแปร
        }

        //ดักค่าว่างสินค้าร่วมรายการ
        if (
            $("#product_pmt").val() == "" &&
            $("input[id='have_pmt']:checked").val()
        ) {
            error_product_pmt = "*กรุณาเลือกสินค้าร่วมรายการ";
            $("#error_product_pmt").text(error_product_pmt);
            $("#product_pmt").css("border-color", "#cc0000");
            product_pmt = "";
        } else {
            error_product_pmt = "";
            $("#error_product_pmt").text(error_product_pmt);
            $("#product_pmt").css("border-color", "");
            product_pmt = $("#product_pmt").val(); //เอาค่าใส่ตัวแปร
        }

        //ดักค่าว่างสินค้า
        if (
            $("#product").val() == "" &&
            $("input[id='nohave_pmt']:checked").val()
        ) {
            error_product = "*กรุณาเลือกรายการสินค้า";
            $("#error_product").text(error_product);
            $("#product").css("border-color", "#cc0000");
            product = "";
        } else {
            error_product = "";
            $("#error_product").text(error_product);
            $("#product").css("border-color", "");
            product = $("#product").val(); //เอาค่าใส่ตัวแปร
        }

        //เช็คค่าว่างจำนวน
        if ($("#number").val() == "") {
            error_number = "*กรุณากรอกจำนวนขาย";
            $("#error_number").text(error_number);
            $("#number").css("border-color", "#cc0000");
            number = "";
        } else {
            error_number = "";
            $("#error_number").text(error_number);
            $("#number").css("border-color", "");
            number = $("#number").val(); //เอาค่าใส่ตัวแปร
        }

        if (error_number != "" || error_product_pmt != "" || error_promotion != "") {
            return false;
        } else if (error_number == "" && error_product_pmt == "" && error_promotion == "" && product_pmt != "") {
            //เช็ครายการสินค้าซ้ำ
            item = {};
            item.product_pmt = product_pmt;
            for (i = 0; i < myArray.length; i++) {
                if (myArray[i].product_pmt == product_pmt || myArray[i].product == product_pmt) {
                    Swal.fire({
                        position: "mid-mid",
                        icon: "error",
                        title: "สินค้ามีอยู่ในรายการแล้ว !",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    return false;
                }
            }
            myArray.push(item);
            console.log(myArray)

            $.ajax({
                type: "POST",
                url: "../product/fetch.php",
                data: {
                    sale_product_pmt: product_pmt,
                },
                dataType: "JSON",
                success: function(response) {
                    var stock = parseInt(response.product_stock);
                    //เช็คกดปุ่มเพิ่มรายการ
                    if ($("#add_data").text() == "เพิ่มรายการ") {
                        if (number <= stock) {
                            count = count + 1;

                            var price = parseFloat(response.product_saleprice);
                            var sum = price * number; //รวมราคา
                            var disc = parseFloat(response.promotion_discount);
                            var discount = (sum * disc) / 100;
                            var total = sum - discount;
                            console.log(total);

                            output = '<tr id="row_' + count + '">';
                            output += '<td align="center">' + count + '<input type="hidden" name="item_no[]" id="no' + count + '" class="no" value="' + count + '"></td>';
                            output += "<td>";
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
                            output += '<input type="hidden" name="item_product[]" id="product' + count + '" class="product" value="' + response.product_id + '"></td>';
                            output += '<td align="right">' + number + '<input type="hidden" name="item_number[]" id="number' + count + '" class="number" value="' + number + '"></td>';
                            output += '<td align="right">' + price.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '<input type="hidden" name="item_lot[]" id="lot' + count + '" class="lot" value="' + response.lot_order + '"></td>';
                            output += '<td align="right">' + sum.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '<input class="sum" type="hidden" name="item_sum[]" id="item_sum' + count + '" value="' + sum + '"></td>';
                            output += '<input type="hidden" name="lot_balance[]" id="lot_balance' + count + '" value="' + response.lot_balance + '">';
                            output += '<td align="right">' + discount.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '<input class="discount" type="hidden" name="item_discount[]" id="item_discount' + count + '" value="' + discount + '"></td>';
                            output += '<td align="center"><button type="button" id="' + count + '" name="remove_details" class="btn btn-danger btn_xs remove_details"><i class="fa fa-trash"></i></button></td>';
                            output += "</tr>";

                            $("#sale_table").append(output);
                            $("#lb_salelist").show();
                            $("#sale_table").show();
                            sum_price();
                            discount_item();
                            total_price();
                            console.log(response.product_id, number, response.product_stock);
                        } else if (number > stock) {
                            Swal.fire({
                                position: "mid-mid",
                                icon: "error",
                                title: "จำนวนสต็อกไม่พอ!",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            console.log(number, response.product_stock);
                        }
                    } else if (number > response.product_stock) {
                        Swal.fire({
                            position: "mid-mid",
                            icon: "error",
                            title: "จำนวนสต็อกไม่พอ!",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        console.log(number, response.product_stock);
                    }

                    //Clear data
                    clear_value();
                },
            });
        }

        if (error_number != "" || error_product != "") {
            return false;
        } else if (error_number == "" && error_product == "" && product != "") {
            //เช็ครายการสินค้าซ้ำ
            item = {};
            item.product = product;
            for (i = 0; i < myArray.length; i++) {
                if (myArray[i].product == product || myArray[i].product_pmt == product) {
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
                type: "POST",
                url: "../product/fetch.php",
                data: {
                    sale_product_notpmt: product,
                },
                dataType: "JSON",
                success: function(response) {
                    var stock = parseInt(response.product_stock);

                    if ($("#add_data").text() == "เพิ่มรายการ") { //เช็คกดปุ่มเพิ่มรายการ
                        if (number <= stock) {
                            if (number < 0) {
                                Swal.fire({
                                    position: "mid-mid",
                                    icon: "error",
                                    title: "ใส่จำนวนที่มากกว่า 0",
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                            } else {
                                count = count + 1;

                                var price = parseFloat(response.product_saleprice);
                                var sum = price * number; //รวมราคา
                                var disc = parseFloat(response.promotion_discount);
                                var dec = (disc / 100).toFixed(2);
                                var mult = sum * dec;
                                var discount = sum - mult; // ส่วนลด

                                console.log(sum);
                                // console.log(sum);
                                // console.log(discount);

                                output = '<tr id="row_' + count + '">';
                                output += '<td align="center">' + count + '<input type="hidden" name="item_no[]" id="no' + count + '" class="no" value="' + count + '"></td>';
                                output += "<td>";
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
                                output += '<input type="hidden" name="item_product[]" id="product' + count + '" class="product" value="' + response.product_id + '"></td>';
                                output += '<td align="right">' + number + '<input type="hidden" name="item_number[]" id="number' + count + '" class="number" value="' + number + '"></td>';
                                output += '<td align="right">' + price.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '<input type="hidden" name="item_lot[]" id="lot' + count + '" class="lot" value="' + response.lot_order + '"></td>';
                                output += '<td align="right">' + sum.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '<input class="sum" type="hidden" name="item_sum[]" id="item_sum' + count + '" value="' + sum + '"></td>';
                                if (isNaN(discount)) {
                                    output += '<td align="right">' + (0).toFixed(2) + "</td>";
                                } else {
                                    output += '<td align="right">' + discount.toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + "</td>";
                                }
                                output += '<input type="hidden" name="lot_balance[]" id="lot_balance' + count + '" value="' + response.lot_balance + '">';
                                output += '<input class="discount" type="hidden" name="item_discount[]" id="item_discount' + count + '" value="0">';
                                output += '<input class="total" type="hidden" name="item_total[]" id="item_total' + count + '" value="' + sum + '">';
                                output += '<td align="center"><button type="button" id="' + count + '" name="remove_details" class="btn btn-danger btn_xs remove_details"><i class="fa fa-trash"></i></button></td>';
                                output += "</tr>";

                                $("#sale_table").append(output);
                                $("#lb_salelist").show();
                                $("#sale_table").show();
                                sum_price();
                                discount_item();
                                total_price();
                                console.log(
                                    response.product_id,
                                    number,
                                    response.product_stock
                                );
                            }
                        } else if (number > stock) {
                            Swal.fire({
                                position: "mid-mid",
                                icon: "error",
                                title: "จำนวนสต็อกไม่พอ!",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            console.log(number, response.product_stock);
                        }
                    } else if (number > response.product_stock) {
                        Swal.fire({
                            position: "mid-mid",
                            icon: "error",
                            title: "จำนวนสต็อกไม่พอ!",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        console.log(number, response.product_stock);
                    }

                    // $("#modalAdditem").modal("hide");
                    //Clear data
                    clear_value();
                },
            });
        }
    });


    // ------------------------------------ ฟังชั่นลบรายการ ------------------------------------
    $(document).on("click", ".remove_details", function() {

        var row_id = $(this).attr("id");

        if (confirm("ต้องการลบรายการนี้ใช่หรือไม่ ?")) {
            count--;
            $("#row_" + row_id + "").remove();
            sum_price();
            discount_item();
            total_price();
        } else {
            return false;
        }
    });

    // ------------------------------------ ฟังชั่นเลือกมีโปรโมชั่น ------------------------------------
    $(".have_pmt").click(function() {
        $(".promotion").show();
        $(".lb_pmt").show();
        $(".lb_pd_pmt").show();
        $(".product_pmt").show();
        $(".number").show();
        $(".lb_number").show();
        $("#alert_pd").show();
        $(".product").hide();
        $(".lb_pd").hide();
        $("span#error_product").empty();
        $("span#error_number").empty();
        $("input").css("border-color", "");
        $("#number").val("");
        $("#product").val("").trigger("change");
    });

    // ------------------------------------ ฟังชั่นเลือกไม่มีโปรโมชั่น ------------------------------------
    $(".nohave_pmt").click(function() {
        $(".promotion").hide();
        $(".lb_pmt").hide();
        $(".product").show();
        $(".lb_pd").show();
        $(".lb_pd_pmt").hide();
        $(".product_pmt").hide();
        $("#alert_pd").hide();
        $(".number").show();
        $(".lb_number").show();
        $("span#error_product_pmt").empty();
        $("span#error_promotion").empty();
        $("span#error_number").empty();
        $("input").css("border-color", "");
        $("#number").val("");
        $("#promotion").val("").trigger("change");
        $("#product_pmt").val("").trigger("change");
    });

    $(document).on("submit", function(e) {
        e.preventDefault();

        var sale_form = $("#sale_form").serialize();

        //ดักค่าว่างวันที่
        if ($("#date").val() == "") {
            error_date = "*กรุณาเลือกวันที่ขาย";
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

        if (error_date == "" && error_customer == "") {
            Swal.fire({
                title: "ยืนยันบันทึกข้อมูล?",
                text: "โปรตรวจสอบการขายสินค้าก่อนยืนยัน!",
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
                        url: "../sale_product/sale_sql.php",
                        data: sale_form,
                        success: function(data) {
                            if (data === "อัพเดทข้อมูลการขายสำเร็จ") {
                                $("#message").fadeIn().html('<div class="alert alert-success"><i class="fa fa-check-circle" aria-hidden="true"></i> ' + data + "</div>");
                                setTimeout(function() {
                                    $("#message").fadeOut("Slow");
                                }, 2000);
                                setTimeout(function() {
                                    $("#modalAdd").modal("hide");
                                }, 2000);

                                //Clear data
                                $("#add_form")[0].reset();
                                $("#sale_form")[0].reset();
                                $("#list_table").empty();
                                $("#date").val("");
                                $("#customer").val("");
                                $(".in-amount-sum").val("");
                                $(".in-discount").val("");
                                $(".in-showtotal").val("");
                                $("#lb_salelist").hide();
                                $("#sale_table").hide();
                                myArray = [];
                                count = 0;
                                show_data();
                            } else {
                                $("#message").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> ' + data + "</div>");
                                setTimeout(function() {
                                    $("#message").fadeOut("Slow");
                                }, 2000);
                            }
                            console.log(data);
                        },
                    });
                }
            });
        } else {
            $("#message").fadeIn().html('<div class="alert alert-danger"><i class="fa fa-times" aria-hidden="true"></i> กรุณาตรวจสอบการทำรายการ</div>');
            setTimeout(function() {
                $("#message").fadeOut("Slow");
            }, 2000);
        }
    });

    // ------------------------------------ แสดงรายการขาย ------------------------------------
    $(document).on("click", "#show_data", function() {
        var id = $(this).data("id");
        $("#sale_id").text(id);
        $.ajax({
            type: "POST",
            url: "../product/fetch.php",
            data: {
                show_sale: id,
            },
            dataType: "JSON",
            success: function(response) {
                console.log(response);
                var html = "";

                for (var i = 0; i < response.length; i++) {
                    var total = response[i].s_total;
                    var sum_total = response[i].total_sum;
                    var disc = response[i].discount;
                    var result_total = response[i].total_list;
                    var disc_list = response[i].dis_list;
                    var vat = response[i].vat;
                    html += "<tr>";
                    html += '<td align="center">' + response[i].product_id + "</td>";
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
                    html += "</td>";
                    html += '<td align="left">' + response[i].s_amount + " " + response[i].unit_name + "</td>";
                    html += '<td align="right">' + total.replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + "</td>";
                    html += '<td align="right">' + disc_list.replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + "</td>";
                    html += "</tr>";

                    $(".total").val(sum_total.replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                    $(".disc").val(disc.replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                    $(".vat").val(vat.replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                    $(".result-total").val(result_total.replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                }
                $("#list_show").html(html);
            },
        });
    });
});