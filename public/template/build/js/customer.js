var MBT_Customer = function () {

    var deleteItem = function (id) {
        deleteAjax({
            url: "/customers/" + id
        });
    };

    var switchBuyStatus = function(){
        var selectStatus = $("select[name='status']");
        var inputTotalSal = $("input[name='average_sale']");

        var status = selectStatus.val().toLowerCase().trim();
        switch (status) {
            case '2':
                inputTotalSal.prop("disabled", true);
                break;
            case '1':
                inputTotalSal.prop("disabled", false);
                break;
            default:
                break;
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
