var MBT_Care = function () {

    var deleteItem = function (id) {
        alertConfirm({
            cbSuccess: function () {
                deleteAjax({url: "/cares/" + id});
            }
        });
    };

    return {
        delete: function (id) {
            deleteItem(id);
        }
    };

}();