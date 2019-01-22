var MBT_PaymentSchedule = function () {

    var printError = function(errors){
      var html = '<ul>';
      for(var key in errors){
          html += '<li>'+errors[key][0]+'</li>';
      }
      html += '</ul>';
      return html;
    };

    var toSave = function (orderId) {
        var data = $("#payment-schedule-form").serialize();

        sendAjax({
            url: "/payment-schedules/" + orderId,
            method: "POST",
            data: data,
            beforeSend: function () {
                $('#btnSave').html('đang lưu dữ liệu...');
            },
            fnSuccess: function (response) {
                if (response.success) {
                    alertSuccess({title: response.message});
                    setTimeout(function () {
                        window.location.reload(true);
                    }, 1e3);
                } else {
                    alertError({title: response.message});
                }
            },
            fnError: function(response){
                var data = response.responseJSON;
                if(data.hasOwnProperty('errors')){
                    $("#display-error").html(printError(data.errors)).removeClass('hidden');
                } else {
                    $("#display-error").html('không thể lưu dữ liệu').removeClass('hidden');
                }
                $('#btnSave').html('Lưu');
            }
        });
    };

    var toSaveDebt = function (debtId) {
        var data = $("#payment-schedule-form").serialize();

        sendAjax({
            url: "/payment-schedules/" + debtId + '/debt',
            method: "POST",
            data: data,
            beforeSend: function () {
                $('#btnSave').html('đang lưu dữ liệu...');
            },
            fnSuccess: function (response) {
                if (response.success) {
                    alertSuccess({title: response.message});
                    setTimeout(function () {
                        window.location.reload(true);
                    }, 1e3);
                } else {
                    alertError({title: response.message});
                }
            },
            fnError: function(response){
                var data = response.responseJSON;
                if(data.hasOwnProperty('errors')){
                    $("#display-error").html(printError(data.errors)).removeClass('hidden');
                } else {
                    $("#display-error").html('không thể lưu dữ liệu').removeClass('hidden');
                }
                $('#btnSave').html('Lưu');
            }
        });
    };

    var openForm = function(id){

        sendAjax({
            url: "/payment-schedules/ajax/" + id,
            method: "GET",
            beforeSend: function () {
            },
            fnSuccess: function (response) {
                if(response.success){
                    $("#paymentSchedule").html(response.message);

                    $("#paymentSchedule").modal("show");
                    initialize();
                } else {
                    alertError({title: "không thể mở giao diện sửa"});
                }
            },
            fnError: function(response){
                var data = response.responseJSON;
                if(data.hasOwnProperty('errors')){
                    $("#display-error").html(printError(data.errors)).removeClass('hidden');
                } else {
                    $("#display-error").html('không thể lưu dữ liệu').removeClass('hidden');
                }
            }
        });
    };

    var saveForm = function(id){
        var data = $("#payment-schedule-form-update").serialize();
        sendAjax({
            url: "/payment-schedules/ajax/" + id,
            method: "PUT",
            data: data,
            beforeSend: function () {
                $("#button-update-modal").html('Đang gửi dữ liệu...')
            },
            fnSuccess: function (response) {
                if(response.success){
                    alertSuccess({title: "cập nhật thành công"});
                    window.location.reload(true);
                } else {
                    alertError({title: "cập nhật thất bại"});
                }
                $("#paymentSchedule").html('');
                $("#paymentSchedule").modal('hide');
            },
            fnError: function(response){
                var data = response.responseJSON;
                if(data.hasOwnProperty('errors')){
                    $("#ajaxError").html(printError(data.errors)).removeClass('hidden');
                } else {
                    $("#ajaxError").html('không thể lưu dữ liệu').removeClass('hidden');
                }
            }
        });
    };

    var deleteForm = function(id){
        sendAjax({
            url: "/payment-schedules/ajax/" + id,
            method: "DELETE",
            beforeSend: function () {
                // do something
            },
            fnSuccess: function (response) {
                if(response.success){
                    alertSuccess({title: "xóa thành công"});
                    window.location.reload(true);
                } else {
                    alertError({title: "xóa thất bại"});
                }
            },
            fnError: function(response){
                alertError({title: "xóa thất bại"});
            }
        });
    };


    return {
        toSave: function (id) {
            toSave(id);
        },
        toSaveDebt: function (id) {
            toSaveDebt(id);
        },
        openForm: function (id) {
            openForm(id);
        },
        saveForm: function (id) {
            saveForm(id);
        },
        deleteForm: function (id) {
            alertConfirm({
                cbSuccess: function () {
                    deleteForm(id);
                }
            });

        }
    };

}();
