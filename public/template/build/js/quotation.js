var MBT_PriceQuotation = function () {

    var deleteItem = function (id) {
        deleteAjax({
            url: "/quotations/" + id
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
