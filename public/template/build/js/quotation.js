var MBT_PriceQuotation = function () {

    var deleteItem = function (id) {
        deleteAjax({
            url: "/price-quotations/" + id
        });
    };

    return {
        delete: function (id) {
            alertConfirm({
                cbSuccess: function () {
                    deleteItem(id);
                }
            });
        }
    };

}();
