var MBT_Care = function () {

    var deleteItem = function (id) {
        deleteAjax({
            url: "/cares/" + id
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
