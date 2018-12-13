var MBT_Debt = function () {

    var deleteItem = function (id) {
        deleteAjax({
            url: "/debts/" + id
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
