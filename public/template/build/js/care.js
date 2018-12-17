var MBT_Care = function () {

    var deleteItem = function (id) {
        deleteAjax({
            url: "/cares/" + id
        });
    };

    var getCustomerByCity = function(){
        var cityId = $("#city_id").val();
        var userId = $("#user_id").val();
        var customerId = $("#customer_id").val();

        cityId = parseInt(cityId);
        userId = parseInt(userId);
        customerId = parseInt(customerId);

        console.log({
            userId: userId,
            cityId: cityId,
            customerId: customerId
        });
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
            }
        });
    };

    var getCustomerByCityIndex = function(){
        var cityId = $("#sCity").val();
        var userId = $("#sUser").val();
        var customerId = $("#sCustomer").val();

        cityId = parseInt(cityId);
        userId = parseInt(userId);
        customerId = parseInt(customerId);

        sendAjax({
            url: "/customers/by-city",
            method: "GET",
            data: {
                cityId: cityId,
                userId: userId,
                customerId: customerId,
            },
            beforeSend: function(){
                $('#sCustomer').html('<option>đang tải dữ liệu...</option>');
            },
            fnSuccess: function (response) {
                if (response.success) {
                    $('#sCustomer').html(response.message);
                } else {
                    alertError({title: response.message});
                }
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
        getCustomerByCity: function () {
            getCustomerByCity();
        },
        getCustomerByCityIndex: function () {
            getCustomerByCityIndex();
        }
    };

}();
