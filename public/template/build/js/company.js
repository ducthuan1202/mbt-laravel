var MBT_Company = function () {

    var deleteItem = function (id) {
        deleteAjax({
            url: "/companies/" + id
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
