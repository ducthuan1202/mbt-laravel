var MBT_PriceQuotation = function () {

    var deleteItem = function (id) {
        deleteAjax({
            url: "/quotations/" + id
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
        priceOrAmountOnchange: function(){
            priceOrAmountOnchange();
        },
        statusOnchange: function(){
            statusOnchange();
        }
    };

}();

MBT_PriceQuotation.statusOnchange();