$(function () {
    setupUi();
});

function setupUi() {
    $(':input').addClass('ui-widget-content');
    $('input[type="submit"], .button, #footer a').button();
    $('th').addClass('ui-widget-header');
    $('.tree').menu();
    $('.link-dialog').click(function () {
        showLinkDialog(this);
        return false;
    });
    $('.datepicker').on('focus', function () {
        $(this).datepicker({
            dateFormat: "yy-mm-dd"
        }).datepicker('show');
    });

    $( ":checkbox" ).button();
}

function showLinkDialog(link) {
    if (window.linkDialogUrls === undefined) {
        window.linkDialogUrls = new Array();
    }
    var url = $(link).prop('href');
    var id = 'link-dialog-' + btoa(url);

    if (!window.linkDialogUrls[id]) {
        window.linkDialogUrls[id] = true;
        dialog = $('<div id="' + id + '"/>');
        dialog.load(url, function () {
            dialog.dialog({modal: true, title: $(link).text()});
            dialog.dialog('open');
        });
    } else {
        dialog.dialog('open');
    }
}