/**
 * Created by Eddie on 21/09/2017.
 */


function _readNotification() {
    $.ajax({
        url: "/admin/notification/reader_notification",
        type: 'POST',
        async: false,
        success: function () {
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;
}

/**
 * System Locales
 */

$(".language_selector").on("click", $(this).submit(), function (e) {
    $.post('/lang', {language_selector: $(this).attr('id')}, function (data) {
        if (data.tipoMensaje === "CORRECTO") {
            swal("Correcto!", data.mensaje, "success");
            window.setTimeout(redirect, 500);
            function redirect() {
                window.location.reload();
            }
        } else {
            swal("Error!", data.mensaje, "error");
        }
    });
    event.preventDefault();
});




