var MBT_ProductSkin = function () {

    var deleteItem = function (id) {
        deleteAjax({
            url: "/skins/" + id
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
