var MBT_Debt = function () {

    var getCustomerByCity = function(){
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
                    getOrderByCustomer();
                } else {
                    alertError({title: response.message});
                }
            }
        });
    };

    var getOrderByCustomer = function () {
        var orderId = $("#order_id").val();
        var customerId = $("#customer_id").val();
        console.log(customerId);

        orderId = parseInt(orderId);
        customerId = parseInt(customerId);

        sendAjax({
            url: "/orders/by-customer",
            method: "GET",
            data: {
                orderId: orderId,
                customerId: customerId
            },
            beforeSend: function () {
                $('#order_id').html('<option>đang tải dữ liệu...</option>');
            },
            fnSuccess: function (response) {
                if (response.success) {
                    $('#order_id').html(response.message);
                } else {
                    alertError({title: response.message});
                }
            }
        });
    };

    var checkOrderId = function () {
        var orderId = $("#order_id").val();
        var status = $("#status").val();

        status = parseInt(status);
        if (!orderId && status === 2) {
            alertError({title: "Nợ mới phải đi kèm theo 1 đơn hàng nào đó.", text: 'vui lòng chọn lại'});
            return false;
        }

        if (orderId && parseInt(orderId) > 0 && status === 1) {
            alertError({title: "Nợ cũ không đi kèm với đơn hàng.", text: 'vui lòng chọn lại'});
            return false;
        }

        return true;
    };

    return {
        getOrderByCustomer: function () {
            getOrderByCustomer();
        },
        getCustomerByCity: function () {
            getCustomerByCity();
        },
        onSubmit: function () {
            return checkOrderId();
        }
    };

}();
