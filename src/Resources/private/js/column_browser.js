jQuery.fn.sycmsColumnBrowser = function () {
    $('.sortable').sortable({
        'handle': '.grippy'
    });
    $('.columnbrowser').disableSelection();
};
