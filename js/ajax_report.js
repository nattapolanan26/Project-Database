$(document).ready(function() {
    $(function() {
        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });
        //value
        $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + "'" + ' AND ' + "'" + picker.endDate.format('YYYY-MM-DD'));
            $('#income_date').val(" เริ่มวันที่ " + picker.startDate.format('DD/MM/YYYY') + " - วันที่ " + picker.endDate.format('DD/MM/YYYY'));
        });
        //clear
        $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

    });

    $('#filter').click(function() {
        var from_date = $('#from_date').val();
        $('#show_date_income').html($('#income_date').val());
        console.log(from_date);
        if (from_date != '') {
            $.ajax({
                url: "filter.php",
                method: "POST",
                data: {
                    from_date: from_date
                },
                success: function(data) {
                    $('#tb_report').html(data);
                    $('#from_date').val('');
                }
            });
        } else {
            alert("Please Select Date");
        }
    });


    $(function() {
        $('input[name="datefilter_sale"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });
        //value
        $('input[name="datefilter_sale"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + "'" + ' AND ' + "'" + picker.endDate.format('YYYY-MM-DD'));
            $('#ip_date').val(" เริ่มวันที่ " + picker.startDate.format('DD/MM/YYYY') + " - วันที่ " + picker.endDate.format('DD/MM/YYYY'));
            // console.log($(this).val());
        });
        //clear
        $('input[name="datefilter_sale"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

    });

    $('#filter_sale').click(function() {
        var from_date_sale = $('#datefilter_sale').val();
        $('#show_date').html($('#ip_date').val());
        console.log(from_date_sale);
        if (from_date_sale != '') {
            $.ajax({
                url: "filter.php",
                method: "POST",
                data: {
                    from_date_sale: from_date_sale
                },
                success: function(data) {
                    $('#tb_report').html(data);
                    $('#datefilter_sale').val('');
                }
            });
        } else {
            alert("Please Select Date");
        }
    });

    $(function() {
        // *** (year only) ***
        $("#datefilter_sale_y").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years"
        });
    });

    // --------------------------------

    $('#filter_sale_y').click(function() {
        var from_date_sale_y = $('#datefilter_sale_y').val();

        let years = parseInt(from_date_sale_y) + 543;

        $('#show_date').html("ผลสรุปยอดขายประจำปี : พ.ศ." + years + " หรือ ค.ศ." + from_date_sale_y);
        console.log(from_date_sale_y, years);
        if (from_date_sale_y != '') {
            $.ajax({
                url: "filter.php",
                method: "POST",
                data: {
                    from_date_sale_y: from_date_sale_y
                },
                success: function(data) {
                    $('#tb_report').html(data);
                    $('#datefilter_sale_y').val('');
                }
            });
        } else {
            alert("Please Select Date");
        }
    });

    $(function() {
        $('input[name="datefilter_claim"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });
        //value
        $('input[name="datefilter_claim"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + "'" + ' AND ' + "'" + picker.endDate.format('YYYY-MM-DD'));
            $('#claim_date').val("รายการเคลมสินค้า เริ่มวันที่ " + picker.startDate.format('DD/MM/YYYY') + " - วันที่ " + picker.endDate.format('DD/MM/YYYY'));
        });
        //clear
        $('input[name="datefilter_claim"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });

    $('#filter_search_claim').click(function() {
        var from_claim_date = $('#datefilter_claim').val();
        $('#show_date').html($('#claim_date').val());

        console.log(from_claim_date);
        if (from_claim_date != '') {
            $.ajax({
                url: "filter.php",
                method: "POST",
                data: {
                    from_claim_date: from_claim_date
                },
                success: function(data) {
                    $('#tb_report').html(data);
                    $('#datefilter_claim').val('');
                }
            });
        } else {
            Swal.fire({
                position: "mid-mid",
                icon: "error",
                title: "โปรดใส่ข้อมูลที่ต้องการค้นหา !",
                showConfirmButton: false,
                timer: 1500,
            });
            return false;
        }
    });
});