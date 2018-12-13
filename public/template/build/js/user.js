var MBT_User = function () {

    var deleteItem = function (id) {
        deleteAjax({
            url: "/users/" + id
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
