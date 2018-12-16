var MBT_Order = function () {



    var deleteItem = function (id) {
        deleteAjax({
            url: "/orders/" + id
        });
    };

    var getCustomerByCity = function (customerId) {
        var cityId = $("#city_id").val();
        cityId = parseInt(cityId);
        if (isNaN(cityId)) {
            alertError({title: 'Khu vực không hợp lê.'});
            return;
        }
        sendAjax({
            url: "/customers/by-city",
            method: "GET",
            data: {cityId: cityId, customerId: customerId},
            beforeSend: function () {
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
                alertError({title: 'Không thể tải dữ liệu.'});
            }
        });
    };

    var getCustomerByCityIndex = function () {
        var cityId = $("#sCity").val();
        cityId = parseInt(cityId);
        sendAjax({
            url: "/customers/by-city",
            method: "GET",
            data: {cityId: cityId},
            beforeSend: function () {
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
                alertError({title: 'Không thể tải dữ liệu.'});
            }
        });
    };

    var getDetail = function (id) {
        $("#quotationModel").modal('show');

        sendAjax({
            url: "/orders/detail",
            method: "GET",
            data: {id: id},
            beforeSend: function () {
                $('#quotationModel').show('đang tải dữ liệu');
            },
            fnSuccess: function (response) {
                if (response.success) {
                    $('#quotationModel').html(response.message);
                } else {
                    alertError({title: response.message});
                }
            },
            fnFail: function () {
                alertError({title: 'Không thể tải dữ liệu.'});
            }
        });
    };

    var formatMoney = function(number){
        number = number.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        return number.substr(0, number.length - 3);
    };

    var priceOrAmountOnchange = function () {
        var price = parseInt($("input[name='price']").val());
        var amount = parseInt($("input[name='amount']").val());

        var equal = 0;
        if (!isNaN(price) && !isNaN(amount)) {
            equal = price * amount * 1000;
        }
        $("#total_money").val(formatMoney(equal));
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
        },
        getDetail: function (id) {
            getDetail(id);
        },
        priceOrAmountOnchange: function () {
            priceOrAmountOnchange();
        }
    };

}();
