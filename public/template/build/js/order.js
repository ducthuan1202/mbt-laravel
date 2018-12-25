var MBT_Order = function () {

    var deleteItem = function (id) {
        deleteAjax({
            url: "/orders/" + id
        });
    };

    var getDetail = function (id) {
        sendAjax({
            url: "/orders/detail",
            method: "GET",
            data: {id: id},
            fnSuccess: function (response) {
                if (response.success) {
                    $('#orderModal').html(response.message);
                    $('#orderModal').modal('show');
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

    return {
        delete: function (id) {
            alertConfirm({
                cbSuccess: function () {
                    deleteItem(id);
                }
            });
        },
        priceOrAmountOnchange: function () {
            priceOrAmountOnchange();
        }
    };

}();
