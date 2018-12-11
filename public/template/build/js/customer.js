var MBT_Customer = function () {

    var deleteItem = function (id) {
        $.ajax({
            url: "/customers/" + id,
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

    var switchBuyStatus = function(){
        var selectBuyStatus = $("select[name='buy_status']");
        var inputTotalSal = $("input[name='total_sale']");

        var buyStatus = selectBuyStatus.val().toLowerCase().trim();
        if(buyStatus.length > 0){
            switch (buyStatus) {
                case 'no':
                    inputTotalSal.prop("disabled", true);
                    break;
                case 'yes':
                    inputTotalSal.prop("disabled", false);
                    break;
                default:
                    break;
            }
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
        switchBuyStatus: function () {
            switchBuyStatus();
        }
    };

}();
