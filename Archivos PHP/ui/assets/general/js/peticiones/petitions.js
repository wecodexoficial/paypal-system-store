/**
 * Created by Eddie on 21/09/2017.
 */


/**
 * Form whit Files
 */
$(".formNormal").submit(function(){
    var formData = new FormData(this);
    $.ajax({
        url: $(this).attr('id'),
        type: 'POST',
        data: formData,
        async: false,
        success: function (data) {
            if (data.tipoMensaje === "CORRECTO") {
                swal("Correcto", data.mensaje, "success");
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
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;
});






