var __FORMAT_DATE__ = 'DD/MM/YYYY';

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
        buttons: true,
        dangerMode: true,
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
        fnSuccess: null,
        fnFail: null
    };
    Object.assign(setting, options);
    if (!setting.url) {
        return;
    }

    $.ajax({
        url: setting.url,
        method: setting.method,
        dataType: setting.dataType,
        timeout: setting.timeout,
        success: function (response) {
            if (typeof setting.fnSuccess === 'function') {
                setting.fnSuccess(response);
            }
        },
        error: function (response) {
            if (typeof setting.fnFail === 'function') {
                setting.fnFail(response);
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

    options.fnFail = function () {
        console.log(response);
        alertError({title: 'Quá trình truyền tải dữ liệu thất bại.'});
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
        'Tháng này': [moment().startOf('month'), moment().endOf('month')],
        'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
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
        singleDatePicker: true,
        singleClasses: "picker_4",
        locale: _generateLocale(),
    });

}

function initDateRangePickerMulti() {
    if (typeof ($.fn.daterangepicker) === 'undefined') {
        return;
    }
    $('.drp-multi').daterangepicker({
        // startDate: moment().subtract(29, 'days'),
        // endDate: moment(),
        // minDate: '01/01/2012',
        maxDate: moment(),
        dateLimit: {
            days: 60
        },
        showDropdowns: false,
        showWeekNumbers: false,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: false,
        ranges: _generateDateRange(),
        opens: 'right',
        buttonClasses: ['btn btn-xs btn-default'],
        applyClass: 'btn-dark',
        cancelClass: 'pull-right',
        separator: ' - ',
        locale: _generateLocale()
    });
}

// start init
initDateRangePickerSingle();
initDateRangePickerMulti();