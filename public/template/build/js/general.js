var __FORMAT_DATE__ = 'DD/MM/YYYY';

var formatMoney = function(number){
    number = number.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    return number.substr(0, number.length - 3);
};

function alertSuccess(setting) {

    var title = 'Thành công.',
        text = 'Chúc mừng.';
    if (setting) {
        title = setting.title ? setting.title : title;
        text = setting.text ? setting.text : text;
    }
    swal({
        title: title,
        text: text,
        icon: "success",
        button: false,
    });
}

function alertError(setting) {

    var title = 'Lỗi.',
        text = 'Đã xảy ra lỗi trong quá trình thưc thi.';
    if (setting) {
        title = setting.title ? setting.title : title;
        text = setting.text ? setting.text : text;
    }
    swal({
        title: title,
        text: text,
        icon: "error",
        button: false,
    });
}

function alertConfirm(setting) {
    var title = 'Cảnh báo !',
        text = 'Bạn có chắc muốn thực hiện hành động này?',
        cbSuccess = null,
        cbCancel = null;

    if (setting) {
        title = setting.title ? setting.title : title;
        text = setting.text ? setting.text : text;

        cbSuccess = setting.cbSuccess ? setting.cbSuccess : cbSuccess;
        cbCancel = setting.cbCancel ? setting.cbCancel : cbCancel;
    }
    swal({
        title: title,
        text: text,
        icon: "warning",
        dangerMode: true,
        buttons: ["Hủy bỏ", "Đồng ý"]
    })
        .then((willDelete) => {
            if (willDelete) {
                if (cbSuccess) cbSuccess();
            } else {
                if (cbCancel) cbCancel();
            }
        });
}

function sendAjax(options) {
    var setting = {
        url: null,
        timeout: 10e3,
        method: 'DELETE',
        dataType: 'JSON',
        data: {},
        fnSuccess: null,
        fnError: null
    };
    Object.assign(setting, options);
    if (!setting.url) {
        return;
    }

    $.ajax({
        url: setting.url,
        method: setting.method,
        dataType: setting.dataType,
        data: setting.data,
        timeout: setting.timeout,
        beforeSend: function () {
            if (typeof setting.beforeSend === 'function') {
                setting.beforeSend();
            }
        },
        success: function (response) {
            if (typeof setting.fnSuccess === 'function') {
                setting.fnSuccess(response);
            }
        },
        error: function (response) {
            console.log(response);
            if (typeof setting.fnError === 'function') {
                setting.fnError(response);
            } else {
                alertError({title: 'Quá trình truyền tải dữ liệu thất bại.'});
            }
        }
    });
}

function deleteAjax(options) {

    options.fnSuccess = function (response) {
        if (response.success) {
            alertSuccess({title: response.message});
            window.location.reload(true);
        } else {
            alertError({title: response.message});
        }
    };

    sendAjax(options);
}

// date range picker
function _generateDateRange() {
    return {
        'Hôm nay': [moment(), moment()],
        'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '7 ngày trước': [moment().subtract(6, 'days'), moment()],
        '30 ngày trước': [moment().subtract(29, 'days'), moment()],
        'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        'Tháng này': [moment().startOf('month'), moment().endOf('month')],
        '7 ngày tới': [moment(), moment().add(7, 'days').startOf('month')],
        'Tháng sau': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
    };
}

function _generateLocale() {
    return {
        format: __FORMAT_DATE__,
        applyLabel: 'Áp dụng',
        cancelLabel: 'Bỏ chọn',
        fromLabel: 'Từ',
        toLabel: 'Tới',
        customRangeLabel: 'Tùy chọn',
        daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
        monthNames: ['Tháng 01', 'Tháng 02', 'Tháng 03', 'Tháng 04', 'Tháng 05', 'Tháng 06', 'Tháng 07', 'Tháng 08', 'Tháng 09', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
        firstDay: 1
    };
}

function initDateRangePickerSingle() {

    if (typeof ($.fn.daterangepicker) === 'undefined') {
        return;
    }

    $('.drp-single').daterangepicker({
        autoUpdateInput: false,
        singleDatePicker: true,
        singleClasses: "picker_4",
        locale: _generateLocale(),
    });

    $('.drp-single').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format(__FORMAT_DATE__));
    });

}

function initDateRangePickerMulti() {
    if (typeof ($.fn.daterangepicker) === 'undefined') {
        return;
    }
    $('.drp-multi').daterangepicker({
        // startDate: moment().subtract(29, 'days'),
        // endDate: moment(),
        minDate: '01/01/2016',
        maxDate: moment().add(1, 'year'),
        dateLimit: {
            days: 60
        },
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: false,
        ranges: _generateDateRange(),
        opens: 'left',
        buttonClasses: ['btn btn-xs btn-default'],
        applyClass: 'btn-dark',
        cancelClass: 'pull-right',
        autoUpdateInput: false,
        locale: _generateLocale()
    });


    $('.drp-multi').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format(__FORMAT_DATE__) + ' - ' + picker.endDate.format(__FORMAT_DATE__));
    });
}

function initDateRangePickerReport() {

    if( typeof ($.fn.daterangepicker) === 'undefined'){ return; }
    var dateReport = $('#dateReport');
    var spanDateReport = $('#dateReport span');

    var cb = function(start, end, label) {
        spanDateReport.html(start.format(__FORMAT_DATE__) + ' - ' + end.format(__FORMAT_DATE__));
    };

    var optionSet1 = {
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        minDate: '01/01/2016',
        maxDate: '12/31/2020',
        dateLimit: {
            days: 60
        },
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 2,
        timePicker12Hour: true,
        ranges: _generateDateRange(),
        opens: 'left',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        format: __FORMAT_DATE__,
        locale: _generateLocale()
    };

    spanDateReport.html(moment().subtract(29, 'days').format(__FORMAT_DATE__) + ' - ' + moment().format(__FORMAT_DATE__));
    dateReport.daterangepicker(optionSet1, cb);
    $('#options1').click(function() {
        dateReport.data('daterangepicker').setOptions(optionSet1, cb);
    });
    $('#options2').click(function() {
        dateReport.data('daterangepicker').setOptions(optionSet2, cb);
    });
    $('#destroy').click(function() {
        dateReport.data('daterangepicker').remove();
    });

}

function initSelect2() {
    $(".chosen-select").select2({
        width: "100%",
        language: {
            noResults: function () {
                return "Không có kết quả phù hợp!";
            }
        }
    });
}

function getCitiesByUser(callback){
    var cityId = $("#city_id").val();
    var userId = $("#user_id").val();

    cityId = parseInt(cityId);
    userId = parseInt(userId);

    sendAjax({
        url: "/customers/cities-by-user",
        method: "GET",
        data: {
            userId: userId,
            cityId: cityId,
        },
        beforeSend: function(){
            $('#city_id').html('<option>đang tải dữ liệu...</option>');
        },
        fnSuccess: function (response) {
            if (response.success) {
                $('#city_id').html(response.message);
            } else {
                alertError({title: response.message});
            }
            if(typeof callback === 'function') callback();
        }
    });
}

function getCustomerByCityAndUser(callback){
    var cityId = $("#city_id").val();
    var userId = $("#user_id").val();
    var customerId = $("#customer_id").val();

    cityId = parseInt(cityId);
    userId = parseInt(userId);
    customerId = parseInt(customerId);

    sendAjax({
        url: "/customers/by-city",
        method: "GET",
        data: {
            userId: userId,
            cityId: cityId,
            customerId: customerId
        },
        beforeSend: function(){
            $('#customer_id').html('<option>đang tải dữ liệu...</option>');
        },
        fnSuccess: function (response) {
            if (response.success) {
                $('#customer_id').html(response.message);
            } else {
                alertError({title: response.message});
            }
            if(typeof callback === 'function') callback();
        }
    });
}

function getCitiesAndCustomersByUser(){
    console.log('aaaaaaaaa');
    getCitiesByUser(function () {
        getCustomerByCityAndUser();
    });
}

// start init
function initialize() {
    initDateRangePickerSingle();
    initDateRangePickerMulti();
    // initDateRangePickerReport();
    initSelect2();
}

initialize();