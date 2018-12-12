var MBT_City = function () {

    var deleteItem = function (id) {
        $.ajax({
            url: "/cities/" + id,
            method: "POST",
            data: {_method: "DELETE"},
            dataType: "json",
            timeout: 15e3,
            success: function (response) {
                if (response.success) {
                    alertSuccess({title: response.message});
                    window.location.reload(true);
                } else {
                    alertError({title: response.message});
                }
            }
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