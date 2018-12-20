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


    return {
        toSave: function (orderId) {
            toSave(orderId);
        }
    };

}();
