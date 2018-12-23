var MBT_PriceQuotation = function () {

    var deleteItem = function (id) {
        deleteAjax({
            url: "/quotations/" + id
        });
    };

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

    var getDetail = function(id){

        sendAjax({
            url: "/quotations/detail",
            method: "GET",
            data: {id: id},
            fnSuccess: function (response) {
                if (response.success) {
                    $('#quotationModal').html(response.message);
                    $('#quotationModal').modal('show');
                } else {
                    alertError({title: response.message});
                }
            }
        });
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

    var statusOnchange = function(){
        if($("#labelStatus").length  && $("#status").length){
            $("#labelStatus").html('Lý do ' + $("#status option:selected").text());
        }
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
        },
        getDetail: function(id){
            getDetail(id);
        },
        priceOrAmountOnchange: function(){
            priceOrAmountOnchange();
        },
        statusOnchange: function(){
            statusOnchange();
        }
    };

}();

MBT_PriceQuotation.statusOnchange();