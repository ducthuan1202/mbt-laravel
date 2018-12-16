var MBT_Care = function () {

    var getErrors = function (err) {
        if (!err.errors) {
            return '';
        }

        var errStr = '<ul>';
        for (var key in err.errors) {
            errStr += '<li>' + err.errors[key].join(",") + '</li>';
        }
        errStr += '</ul>';
        return errStr;
    };

    var deleteItem = function (id) {
        deleteAjax({
            url: "/cares/" + id
        });
    };

    var openForm = function (id) {
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
            },
            error: function (response) {
                alertError({title: 'Quá trình truyền tải dữ liệu thất bại.'});
            }
        });
    };

    var saveForm = function () {
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
            error: function (response) {
                var err = response.responseJSON;
                $("#errors").html(getErrors(err)).removeClass('hidden');
            }
        });
    };

    var getHistories = function (id) {
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
            error: function (response) {
                alertError({title: 'Không thể tải lịch sử.'});
            }
        });
    };

    var getCustomerByCity = function(customerId){
        var cityId = $("#city_id").val();
        cityId = parseInt(cityId);
        if(isNaN(cityId)){
            alertError({title: 'Khu vực không hợp lê.'});
            return;
        }
        sendAjax({
            url: "/customers/by-city",
            method: "GET",
            data: {cityId: cityId, customerId: customerId},
            beforeSend: function(){
                $('#customer_id').html('<option>đang tải dữ liệu...</option>');
            },
            fnSuccess: function (response) {
                if (response.success) {
                    $('#customer_id').html(response.message);
                } else {
                    alertError({title: response.message});
                }
            },
            fnFail: function () {
                alertError({title: 'Không thể tải lịch sử.'});
            }
        });
    };

    var getCustomerByCityIndex = function(){
        var cityId = $("#sCity").val();
        cityId = parseInt(cityId);
        sendAjax({
            url: "/customers/by-city",
            method: "GET",
            data: {cityId: cityId},
            beforeSend: function(){
                $('#sCustomer').html('<option>đang tải dữ liệu...</option>');
            },
            fnSuccess: function (response) {
                if (response.success) {
                    $('#sCustomer').html(response.message);
                } else {
                    alertError({title: response.message});
                }
            },
            fnFail: function () {
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
        getCustomerByCity: function (customerId) {
            getCustomerByCity(customerId);
        },
        getCustomerByCityIndex: function () {
            getCustomerByCityIndex();
        }
    };

}();
