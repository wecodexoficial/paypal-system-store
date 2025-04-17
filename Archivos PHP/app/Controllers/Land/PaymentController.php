<?php
/**
 * Created by PhpStorm.
 * User: Eddie
 * Date: 02/01/2018
 * Time: 21:12
 */

namespace Land;

use PaypalLibrary;
use Land\ShoppingController;
use Base;
use TextGenerator;
use Query;
use Mail;

require_once "app/Library/PaypalLibrary.php";

class PaymentController extends PaypalLibrary
{


    public static function setPayment()
    {
        $paypalmode = (Base::instance()->get('PAYPAL_MODE') == 'sandbox') ? '.sandbox' : '';
        $taxes = array('VAT' => Base::instance()->get('TAX_PORCENT'), 'Service Tax' => 5);
        unset($_SESSION["payment_status"]);


        if (!empty(ShoppingController::getShopping())) {

            $paypal_data = '';
            $ItemTotalPrice = 0;
            $i = 0;
            foreach (ShoppingController::getShopping() as $product) {


                $product_op_price = ($product["product"]["price"] - ($product["product"]["price"] * $product["product"]["porcent_discount"] / 100));


                $paypal_data .= '&L_PAYMENTREQUEST_0_NAME' . $i . '=' . urlencode($product["product"]["name"]);
                $paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER' . $i . '=' . urlencode($product["code"]);
                $paypal_data .= '&L_PAYMENTREQUEST_0_AMT' . $i . '=' . urlencode($product_op_price);
                $paypal_data .= '&L_PAYMENTREQUEST_0_QTY' . $i . '=' . urlencode($product["quantity"]);

                // item price X quantity
                $subtotal = $product["subtotal"];

                //total price
                $ItemTotalPrice = $ItemTotalPrice + $subtotal;

                //create items for session
                $paypal_product['items'][] = array(
                    'itm_name' => $product["product"]["name"],
                    'itm_price' => $product_op_price,
                    'itm_code' => $product["code"],
                    'itm_qty' => $product["quantity"]
                );
                $i++;
            }

            $total_tax = 0;
            foreach ($taxes as $key => $value) { //list and calculate all taxes in array
                $tax_amount = round($ItemTotalPrice * ($value / 100));
                $tax_item[$key] = $tax_amount;
                $total_tax = $total_tax + $tax_amount; //total tax amount
            }

            $GrandTotal = ($ItemTotalPrice + $total_tax + Base::instance()->get('HANDA_LIST_COST') + Base::instance()->get('INSURANCE_COST') + Base::instance()->get('SHIPPIN_COST') + Base::instance()->get('SHIPPIN_DISCOUNT'));

            $paypal_product['assets'] = array(
                'tax_total' => $total_tax,
                'handaling_cost' => Base::instance()->get('HANDA_LIST_COST'),
                'insurance_cost' => Base::instance()->get('INSURANCE_COST'),
                'shippin_discount' => Base::instance()->get('SHIPPIN_DISCOUNT'),
                'shippin_cost' => Base::instance()->get('SHIPPIN_COST'),
                'grand_total' => $GrandTotal);

            //create session array for later use
            $_SESSION["paypal_products"] = $paypal_product;

            //Parameters for SetExpressCheckout, which will be sent to PayPal
            $padata = '&METHOD=SetExpressCheckout' .
                '&RETURNURL=' . urlencode(Base::instance()->get('PAYPAL_RETURN')) .
                '&CANCELURL=' . urlencode(Base::instance()->get('PAYPAL_CANCEL')) .
                '&PAYMENTREQUEST_0_PAYMENTACTION=' . urlencode("SALE") .
                $paypal_data .
                '&NOSHIPPING=0' . //set 1 to hide buyer's shipping address, in-case products that does not require shipping
                '&PAYMENTREQUEST_0_ITEMAMT=' . urlencode($ItemTotalPrice) .
                '&PAYMENTREQUEST_0_TAXAMT=' . urlencode($total_tax) .
                '&PAYMENTREQUEST_0_SHIPPINGAMT=' . urlencode(Base::instance()->get('SHIPPIN_COST')) .
                '&PAYMENTREQUEST_0_HANDLINGAMT=' . urlencode(Base::instance()->get('HANDA_LIST_COST')) .
                '&PAYMENTREQUEST_0_SHIPDISCAMT=' . urlencode(Base::instance()->get('SHIPPIN_DISCOUNT')) .
                '&PAYMENTREQUEST_0_INSURANCEAMT=' . urlencode(Base::instance()->get('INSURANCE_COST')) .
                '&PAYMENTREQUEST_0_AMT=' . urlencode($GrandTotal) .
                '&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode(Base::instance()->get('PAYPAL_CURRENCY')) .
                '&LOCALECODE=GB' . //PayPal pages to match the language on your website.
                '&LOGOIMG=https://www.wecodex.com/images/logo_secundary.png' . //site logo
                '&CARTBORDERCOLOR=FFFFFF' . //border color of cart
                '&ALLOWNOTE=1';


            //We need to execute the "SetExpressCheckOut" method to obtain paypal token
            $paypal = new PaypalLibrary();
            $httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, Base::instance()->get('PAYPAL_USERNAME'), Base::instance()->get('PAYPAL_PASSWORD'), Base::instance()->get('PAYPAL_SIGNATURE'), Base::instance()->get('PAYPAL_MODE'));


            //Respond according to message we receive from Paypal
            if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {

                $paypalurl = 'https://www' . $paypalmode . '.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $httpParsedResponseAr["TOKEN"] . '';
                //header('Location: ' . $paypalurl);
                \Responses::message("Redirigiendo<br> <img width='100px' src='ui/assets/general/img/loading_.gif'>", "CORRECTO", false, "", "", $paypalurl);

            } else {
                \Responses::message(urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]), "ERROR");
            }

        }

    }

    public static function validatePayment()
    {

        $token = @$_GET["token"]; //self::httpParam("token");
        $payer_id = @$_GET["PayerID"]; //self::httpParam("PayerID");

        if (!empty($token) && !empty($payer_id)) {

            //get session variables
            $paypal_product = $_SESSION["paypal_products"];
            $paypal_data = '';
            $ItemTotalPrice = 0;

            foreach ($paypal_product['items'] as $key => $p_item) {
                $paypal_data .= '&L_PAYMENTREQUEST_0_QTY' . $key . '=' . urlencode($p_item['itm_qty']);
                $paypal_data .= '&L_PAYMENTREQUEST_0_AMT' . $key . '=' . urlencode($p_item['itm_price']);
                $paypal_data .= '&L_PAYMENTREQUEST_0_NAME' . $key . '=' . urlencode($p_item['itm_name']);
                $paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER' . $key . '=' . urlencode($p_item['itm_code']);

                // item price X quantity
                $subtotal = ($p_item['itm_price'] * $p_item['itm_qty']);

                //total price
                $ItemTotalPrice = ($ItemTotalPrice + $subtotal);
            }

            $padata = '&TOKEN=' . urlencode($token) .
                '&PAYERID=' . urlencode($payer_id) .
                '&PAYMENTREQUEST_0_PAYMENTACTION=' . urlencode("SALE") .
                $paypal_data .
                '&PAYMENTREQUEST_0_ITEMAMT=' . urlencode($ItemTotalPrice) .
                '&PAYMENTREQUEST_0_TAXAMT=' . urlencode($paypal_product['assets']['tax_total']) .
                '&PAYMENTREQUEST_0_SHIPPINGAMT=' . urlencode($paypal_product['assets']['shippin_cost']) .
                '&PAYMENTREQUEST_0_HANDLINGAMT=' . urlencode($paypal_product['assets']['handaling_cost']) .
                '&PAYMENTREQUEST_0_SHIPDISCAMT=' . urlencode($paypal_product['assets']['shippin_discount']) .
                '&PAYMENTREQUEST_0_INSURANCEAMT=' . urlencode($paypal_product['assets']['insurance_cost']) .
                '&PAYMENTREQUEST_0_AMT=' . urlencode($paypal_product['assets']['grand_total']) .
                '&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode(Base::instance()->get('PAYPAL_CURRENCY'));


            //We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
            $paypal = new PaypalLibrary();
            $httpParsedResponseAr = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, Base::instance()->get('PAYPAL_USERNAME'), Base::instance()->get('PAYPAL_PASSWORD'), Base::instance()->get('PAYPAL_SIGNATURE'), Base::instance()->get('PAYPAL_MODE'));

            //Check if everything went ok..
            if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {


                if ('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]) {
                    $message_status = "¡Pago recibido! ¡Su producto será enviado muy pronto!";
                    $payment_status = "Paid";

                } elseif ('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]) {
                    $message_status = "Transacción completa, pero el pago aún está pendiente";
                    $payment_status = "Pending";
                }
                $padata = '&TOKEN=' . urlencode($token);
                $paypal = new PaypalLibrary();
                $response_paypal = $paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, Base::instance()->get('PAYPAL_USERNAME'), Base::instance()->get('PAYPAL_PASSWORD'), Base::instance()->get('PAYPAL_SIGNATURE'), Base::instance()->get('PAYPAL_MODE'));

                if ("SUCCESS" == strtoupper($response_paypal["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($response_paypal["ACK"])) {

                    $dir_website = \Base::instance()->get('DIR_BASE');
                    $client_data = self::getSessionInstance("session_data_user");
                    $name_client = $client_data["name"];
                    foreach (ShoppingController::getShopping() as $product) {

                        $product_query = Query::qWhere("common_product", "code", $product["product"]["code"], false);
                        $invenry_now = ($product_query["inventary"] - $product["quantity"]);
                        $product_save = array(
                            "subtotal" => $product["subtotal"],
                            "quantity" => $product["quantity"],
                            "code" => $product["product"]["code"],
                            "transaction_id" => $response_paypal["TRANSACTIONID"],
                            "date_re" => date("Y-m-d")
                        );
                        self::model("common_product_sell")->add($product_save);
                        Query::qUpdateWhere("common_product", "inventary", $invenry_now, "code", $product["product"]["code"]);

                        // Guardar informacion de venta en inventario //
                        $product_save_inventary = array(
                            "id_product" => $product_query["id_product"],
                            "quantity" => $product["quantity"],
                            "id_user" => $client_data["id_user"],
                            "type" => 1, // 1 Entrada , 2 Salidas
                            "transaction_id" => $response_paypal["TRANSACTIONID"],
                            "date_re" => date("Y-m-d")
                        );
                        self::model("common_inventary")->add($product_save_inventary);

                    }


                    Mail::sendHtml(
                        $client_data["email"],
                        "Pago recivido",
                        "Estimado cliente $name_client, le informamos su pago ha sido recivido<br><br>
                       <b>Numero de orden:</b> " . $response_paypal["TRANSACTIONID"] . "<br>
                       <b>Estado de pago:</b> $payment_status<br>
                       <b>Estado de orden:</b> Orden confirmada<br><br>
                       Porfavor de acceder a su cuenta para mas detalles, gracias<br>
                       Ingresa desde <a href='$dir_website/login'>$dir_website/login</a>
                      ");

                    $payment_save = array(
                        "transaction_id" => TextGenerator::to_utf8($response_paypal["TRANSACTIONID"]),
                        "id_user" => $client_data["id_user"],
                        "correlation_id" => TextGenerator::to_utf8($response_paypal["CORRELATIONID"]),
                        "build_number" => TextGenerator::to_utf8($response_paypal["BUILD"]),
                        "email_buyer" => TextGenerator::to_utf8($response_paypal["EMAIL"]),
                        "currency_code_payment" => TextGenerator::to_utf8($response_paypal["COUNTRYCODE"]),
                        "currency_code_sell" => TextGenerator::to_utf8($response_paypal["PAYMENTREQUEST_0_CURRENCYCODE"]),
                        "token" => TextGenerator::to_utf8($response_paypal["TOKEN"]),
                        "player_status" => TextGenerator::to_utf8($response_paypal["PAYERSTATUS"]),
                        "atm" => TextGenerator::to_utf8($response_paypal["AMT"]),
                        "itemamt" => TextGenerator::to_utf8($response_paypal["ITEMAMT"]),
                        "taxatm" => TextGenerator::to_utf8($response_paypal["TAXAMT"]),
                        "payment_status" => $payment_status,
                        "date_re" => date("Y-m-d"),
                        "status" => 1 // 1 = Orden confirmada, 2= Orden de procesamiento, 3 = Control de calidad, 4= Producto enviado, 5 Producto entregado
                    );
                    self::model("common_sell")->add($payment_save);

                    ShoppingController::delShopping(false);
                    self::setSessionInstance("payment_status", array("payment_info" => $payment_save, "status" => $payment_status, "message" => $message_status));
                    self::httpRedir(Base::instance()->get('PAYPAL_COMPLETE'), true);


                } else {
                    \Responses::message("Se ha producido un error <br>" . $httpParsedResponseAr["L_LONGMESSAGE0"], "ERROR");

                }

            } else {
                \Responses::message("Se ha producido un error <br>" . urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]));
            }
        }
    }
}