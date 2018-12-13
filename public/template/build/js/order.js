var MBT_Order = function () {

    var deleteItem = function (id) {
        deleteAjax({
            url: "/orders/" + id
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
