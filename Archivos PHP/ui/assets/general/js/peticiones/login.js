/**
 * Created by Eduardo Martinez on 31/03/2017.
 */
$('.frmLogin').submit(function (event) {
    $.post($(this).attr('id'), $(this).serialize(), function (data) {
        if (data.tipoMensaje === "CORRECTO") {
            swal("Correcto!", data.mensaje, "success");

            if(data.redir !== ""){
                window.setTimeout(redirect, 500);
                function redirect() {
                    window.location.replace(data.redir);
                }
            }else if(data.reload === true){
                location.reload();
            }
        }else{
            swal("Error!", data.mensaje, "error");
        }
    })
        .fail(function(data){
            swal("Correcto!",data.mensaje, "success");

        });
    event.preventDefault();
});
