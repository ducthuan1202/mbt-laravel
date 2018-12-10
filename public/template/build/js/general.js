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
        text = setting.text ? setting.text : title;

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
                if(cbSuccess) cbSuccess();
            } else {
                if(cbCancel) cbCancel();
            }
        });
}