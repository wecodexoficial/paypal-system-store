/**
 *
 * Created by Eduardo Martinez on 28/08/2017.
 * Wecodex Solutions 2017
 */
var path = window.location.pathname;
var srt = path.substring(path.lastIndexOf('/') + 1);
var page = srt.replace('#', '');






function updateTotals(elem) {
    var tr = $(elem).closest('tr'),
        quantity = $('[name="data_producto[quantity][]"]', tr).val(),
        price = $('[name="data_producto[price][]"]', tr).val();
    subtotal = parseInt(quantity) * parseFloat(price);
    $('.calculate-sub', tr).val(subtotal.toFixed(2));


}


function calculateTotal() {
    var grandTotal = 0,
        disc = 0,
        c_ship = parseInt($('.calculate.shipping').val()) || 0;

    $('#invoice_table tbody tr').each(function () {
        var c_sbt = $('.calculate-sub', this).val(),
            quantity = $('[name="data_producto[quantity][]"]', this).val(),
            price = $('[name="data_producto[price][]"]', this).val() || 0,
            subtotal = parseInt(quantity) * parseFloat(price);

        grandTotal += parseFloat(c_sbt);
        disc += subtotal - parseFloat(c_sbt);
    });

    // VAT, DISCOUNT, SHIPPING, TOTAL, SUBTOTAL:
    var subT = parseFloat(grandTotal),
        finalTotal = parseFloat(grandTotal + c_ship),
        vat = parseInt($('.invoice-vat').attr('data-vat-rate'));

    $('.invoice-sub-total').text(subT.toFixed(2));
    $('#invoice_subtotal').val(subT.toFixed(2));
    $('.invoice-discount').text(disc.toFixed(2));
    $('#invoice_discount').val(disc.toFixed(2));

    if ($('.invoice-vat').attr('data-enable-vat') === '1') {

        if ($('.invoice-vat').attr('data-vat-method') === '1') {
            $('.invoice-vat').text(((vat / 100) * finalTotal).toFixed(2));
            $('#invoice_vat').val(((vat / 100) * finalTotal).toFixed(2));
            $('.invoice-total').text((finalTotal).toFixed(2));
            $('#invoice_total').val((finalTotal).toFixed(2));
        } else {
            $('.invoice-vat').text(((vat / 100) * finalTotal).toFixed(2));
            $('#invoice_vat').val(((vat / 100) * finalTotal).toFixed(2));
            $('.invoice-total').text((finalTotal + ((vat / 100) * finalTotal)).toFixed(2));
            $('#invoice_total').val((finalTotal + ((vat / 100) * finalTotal)).toFixed(2));
        }
    } else {
        $('.invoice-total').text((finalTotal).toFixed(2));
        $('#invoice_total').val((finalTotal).toFixed(2));
    }

    // remove vat
    if ($('input.remove_vat').is(':checked')) {
        $('.invoice-vat').text("0.00");
        $('#invoice_vat').val("0.00");
        $('.invoice-total').text((finalTotal).toFixed(2));
        $('#invoice_total').val((finalTotal).toFixed(2));
    }

}

/////////////////////////////////////////////////////////////////////////
////////// Carga la informacion del cliente seleccionado ////////////////

$('#invoice_sl_client').change(function () {
    var $selectedOption = $(this).find('option:selected');
    var selectedValue = $selectedOption.val();

    if (selectedValue === "") {
        $('#invc_tipo_persona').val("");
        $('#invc_razon_social').val("");
        $('#invc_rfc').val("");
        $('#invc_direccion').val("");
        $('#invc_localidad').val("");
        $('#invc_correo').val("");
        $('#invc_telefono').val("");
        $('#invc_celular').val("");
    } else {
        $.ajax({
            data: {id: selectedValue},
            url: '/admin/client/getbyid/',
            method: 'POST',
            success: function (response) {

                var data = response.mensaje;
                $('#invc_tipo_persona').val(data.fiscal_regime);
                $('#invc_razon_social').val(data.social_reason);
                $('#invc_rfc').val(data.rfc);
                $('#invc_direccion').val(data.address);
                $('#invc_localidad').val(data.location);
                $('#invc_correo').val(data.email);
                $('#invc_telefono').val(data.phone);

            }
        });
    }
});
/////////////////////////////////////////////////////////////////


$('#save_invoice').submit(function (e) {
    e.preventDefault();
    var datas =  $("#save_invoice").serialize();

    $.ajax({
        data: datas,
        url: '/admin/bill/save',
        method: 'POST',
        success: function (response) {
                swal(response.data, response.type_message);


        }
    });

});





$('#invoice_table').on('click', ".delete-row", function (e) {
    e.preventDefault();

    $(this).closest('tr').remove();
    calculateTotal();
    swal("Producto eliminado!", "Se ha eliminado correctamente el producto seleccionado.", "success");


});

// add new product row on invoice
var cloned = $('#invoice_table tr:last').clone();
$(".add-row").click(function (e) {
    e.preventDefault();
    cloned.clone().appendTo('#invoice_table');
});

$('#invoice_table').on('input', '.selecter', function () {
    SetData(this);
    updateTotals(this);
    calculateTotal();

    $(".calculate").click();
});

calculateTotal();

$('#invoice_table').on('input', '.calculate', function () {

    updateTotals(this);
    calculateTotal();
});

$('#invoice_totals').on('input', '.calculate', function () {
    calculateTotal();
});

$('#invoice_product').on('input', '.calculate', function () {
    calculateTotal();
});

$('.remove_vat').on('change', function () {
    calculateTotal();
});




function SetData(elem) {

    var tr = $(elem).closest('tr'),
        product_sl = $('[name="data_producto[id_product][]"]', tr).val();

    if(product_sl !== "") {
        $.ajax({
            data: {id: product_sl},
            url: '/admin/product/getbyid/',
            method: 'POST',
            success: function (response) {
                var data = response.mensaje;
                if (data === "") {
                    var price = $('[name="data_producto[price][]"]', tr).val(0);
                    var description = $('[name="data_producto[descripcion][]"]', tr).val("");
                    updateTotals();


                } else {
                    var price = $('[name="data_producto[price][]"]', tr).val(data.price);
                    var description = $('[name="data_producto[description][]"]', tr).val(data.description);
                    updateTotals();
                }
            }
        });
    }

}

/**
 * Created by Eddie on 04/11/2017.
 */
