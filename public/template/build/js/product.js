var MBT_Product= function () {

    var deleteItem = function (id) {
        deleteAjax({
            url: "/products/" + id
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
