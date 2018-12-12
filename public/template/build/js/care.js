var MBT_Care = function () {

    var getErrors = function(err){
        if(!err.errors) return '';
        var errStr = '<ul>';
      for(var key in err.errors){
          errStr += '<li>' + err.errors[key].join(",") + '</li>';
      }
      errStr += '</ul>';
      return errStr;
    };

    var deleteItem = function (id) {
        $.ajax({
            url: "/users/" + id,
            method: "POST",
            data: {_method: "DELETE"},
            dataType: "json",
            timeout: 15e3,
            success: function (response) {
                if (response.success) {
                    alertSuccess({title: response.message});
                    window.location.reload(true);
                } else {
                    alertError({title: response.message});
                }
            }
        });
    };

    var openForm = function(id){
        $.ajax({
            url: "/care-history/create",
            method: "GET",
            data: {id: id},
            dataType: "json",
            timeout: 15e3,
            success: function (response) {
                if (response.success) {
                    $('#careModelForm').html(response.message);
                    $('#careModelForm').modal('show');
                } else {
                    alertError({title: response.message});
                }
            }
        });
    };

    var saveForm = function(){
        var form = $("#care-history-form");
        var data = form.serialize();
        $.ajax({
            url: "/care-history/store",
            method: "POST",
            data: data,
            dataType: "json",
            timeout: 15e3,
            success: function (response) {
                if (response.success) {
                    $('#careModelForm').html('');
                    $('#careModelForm').modal('hide');
                    alertSuccess({title: response.message});
                } else {
                    alertError({title: response.message});
                }
            },
            error: function(response){
                var err = response.responseJSON;
                $("#errors").html(getErrors(err)).removeClass('hidden');
            }
        });
    };


    var getHistories = function(id){
        $.ajax({
            url: "/care-history",
            method: "GET",
            data: {id: id},
            dataType: "json",
            timeout: 15e3,
            success: function (response) {
                if (response.success) {
                    $('#careModelForm').html(response.message);
                    $('#careModelForm').modal('show');
                } else {
                    alertError({title: response.message});
                }
            },
            error: function(response){
                alertError({title: 'Không thể tải lịch sử.'});
            }
        });
    };

    return {
        delete: function (id) {
            alertConfirm({
                cbSuccess: function () {
                    deleteItem(id);
                }
            });
        },
        openForm: function(id){
            openForm(id);
        },
        saveForm: function(){
            saveForm();
        },
        getHistories: function(){
            getHistories();
        }
    };

}();
