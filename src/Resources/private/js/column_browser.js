jQuery.fn.sycmsColumnBrowser = function () {
    var operations = [];

    $('.sortable').sortable({
        'stop': function (event) {
            console.log(event);
        }
    });
    $('.columnbrowser').disableSelection();
    $('.moving').hide();

    $('#browser_move_button').on('click', function (el) {
        $('.browsing').hide();
        $('.moving').show();
    });

    $('#moving_cancel_button').on('click', function (el) {
        $('.moving').hide();
        $('.browsing').show();
        $('.sortable').sortable('cancel');
    });
};
