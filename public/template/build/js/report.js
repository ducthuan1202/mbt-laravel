var MBT_Report = function () {

    var __FORMAT_DATE__ = 'DD/MM/YYYY';

    var getRevenue = function (date) {
        $.ajax({
            timeout: 1e4,
            url: '/report/revenue',
            method: 'GET',
            dataType: 'JSON',
            data: date,
            beforeSend: function () {
                console.log('get revenue');
            },
            success: function (response) {
                //reportRevenueThisWeek
                console.log(response);
            },
            error: function () {
                console.log('get revenue fail');
            }
        });
    };

    var revenueInWeek = function () {
        var startOfWeek = moment().startOf('week').add(1, 'days').format(__FORMAT_DATE__);
        var endOfWeek = moment().endOf('week').add(1, 'days').format(__FORMAT_DATE__);

        var startOfMonth = moment().startOf('month').format(__FORMAT_DATE__);
        var endOfMonth = moment().endOf('month').format(__FORMAT_DATE__);

        getRevenue({
            thisWeek: startOfWeek + ' - ' + endOfWeek,
            thisMonth: startOfMonth + ' - ' + endOfMonth,
        });
    };

    /** return methods public */
    return {
        revenueInWeek: function () {
            revenueInWeek();
        }
    };

}();