/**
 * Created by Eddie on 28/09/2017.
 */


$(".formMiroUploadXML").submit(function () {
    $("#loading-box").show();
    $("#valbox_content").html("");
    var formData = new FormData(this);
    $.ajax({
        url: $(this).attr('id'),
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            var xml = data.mensaje.data_xml.data;
            var xml_totales = data.mensaje.data_xml.data.totales;

            if (data.tipoMensaje === "CORRECTO") {


                $("#valbox_content").show().html(html_table_noheader(data.mensaje.data_validate.message));

                // Detalles de factura Emisor
                var data_emisor = html_set_title("Datos emisor", 5);
                $('#inv_detaills_emisor').html(data_emisor + html_foreach_input_group(xml.emisor,"data_emisor"));


                // Detalles de factura receptor
                var data_receptor = html_set_title("Datos receptor", 5);
                $('#inv_detaills_receptor').html(data_receptor + html_foreach_input_group(xml.receptor,"data_receptor"));


                $("#id").html(html_foreach_input_group(""));

                // Detalles de factura comprobante
                var data_cfdi = html_set_title("Datos comprobante", 5);
                $('#inv_detaills_cfdi').html(data_cfdi + html_foreach_input_group(xml.cabecera,"data_cfdi"));



                ///////////////*  Detalles de conceptos  *//////////////////////////////

                var data_concepts = '<table class="table table-striped invoice-summary">'
                    + '<thead>'
                    + '<tr class="bg-dark">'
                    + '<td width="25%">&nbsp;&nbsp;Descripcion</td>'
                    + '<td width="15%">Cantidad</td>'
                    + '<td width="10%">UM</td>'
                    + '<td width="15%">Precio</td>'
                    + '<td width="15%">Importe</td>'
                    + '</tr>'
                    + '</thead>'
                    + '<tbody>';

                var concepts = xml.conceptos;
                for (var i = 0; i < concepts.length ; i++) {
                    data_concepts = data_concepts
                        + "<tr><td >" + concepts[i].descripcion.substring(0,50) + "...</td>"
                        + "<td align='center'>" + concepts[i].cantidad + "</td>"
                        + "<td>" + concepts[i].unidad + "</td>"
                        + "<td>$" + concepts[i].valor_unitario + "</td>"
                        + "<td>$" + concepts[i].importe + "</td></tr>";

                }

                $("#inv_concepts").html(data_concepts + html_input("data_invoice[concepts]","hidden",JSON.stringify(concepts)));
                ////////////////////////////////////////////////////////




                $("#inv_subtotal").html("$" + html_input_group("data_invoice[subtotal]","hidden",xml_totales.subtotal));
                $("#inv_isr").html("$" + html_input_group("data_invoice[isr]","hidden",xml.tl_retenciones));
                $("#inv_iva").html("$" + html_input_group("data_invoice[iva]","hidden",xml_totales.iva));
                $("#inv_total").html("$" + html_input_group("data_invoice[total]","hidden",xml_totales.total));



                $("#loading-box").hide(); // Oculta el loading


                // Manda al siguiente slep de validacion //
                $('#demo-main-wz').find('.next').click();



            } else {
                if (typeof data.mensaje === 'string') {
                    swal("¡Ocurrio un error!", data.mensaje, "error");
                } else {
                    var va_html = "";
                    $.each(data.mensaje, function (key, value) {
                        va_html = va_html + ( key.bold() + "  " + value + "\n");
                    });
                    swal("¡Ocurrio un error!", va_html, "error");

                }
                $("#loading-box").hide();
            }
        }

    });

    return false;
});



$(".CheckOrderSap").submit(function () {
    var formData = new FormData(this);
    $.ajax({
        url: "/admin/sap/query/findorder",
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.tipoMensaje === "CORRECTO") {
            swal(data.mensaje,"success");
            }
        }

    });

    return false;
});

$(".formMiroUploadPDF").submit(function () {
    $("#loading-box").show();
    $("#valbox_content").html("");
    var formData = new FormData(this);
    $.ajax({
        url: $(this).attr('id'),
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.tipoMensaje === "CORRECTO") {

                var va_html = ' <table class="table table-striped"><tbody>';
                $.each(data.mensaje, function (key, value) {
                    var button_show = '<textarea class="form-control"  style="resize:none;border: none; " readonly >' + value + '</textarea>';
                    if (value.length >= 50) {
                        va_html = va_html + ("<tr><td><b>" + key + "</b></td>" + "<td>" + button_show + "</td></tr>");
                    } else {
                        va_html = va_html + ("<tr><td><b>" + key + "</b></td>" + "<td>" + value + "</td></tr>");
                    }
                });
                va_html = va_html + "</tbody></table>";
                $("#valbox_content").show().html(va_html);
                $("#loading-box").hide();

                // Manda al siguiente slep de validacion //
                $('#demo-main-wz').find('.next').click();

            } else {
                if (typeof data.mensaje === 'string') {
                    swal("¡Ocurrio un error!", data.mensaje, "error");
                } else {
                    var va_html = "";
                    $.each(data.mensaje, function (key, value) {
                        va_html = va_html + ( key.bold() + "  " + value + "\n");
                    });
                    swal("¡Ocurrio un error!", va_html, "error");

                }
                $("#loading-box").hide();
            }
        }

    });

    return false;
});
