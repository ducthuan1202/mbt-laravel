var MBT_City = function () {

    var deleteItem = function (id) {
        deleteAjax({
            url: "/cities/" + id
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
