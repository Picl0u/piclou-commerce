<?php
namespace App\Http\Picl0u\Payment;

use Illuminate\Http\Request;
use Payline\PaylineSDK;
use Ramsey\Uuid\Uuid;

class CitelisPayment implements PaymentInterface
{
    private $api = [];

    public function process(float $total)
    {
        $this->config();
        $payline = new PaylineSDK(
            $this->api['MERCHANT_ID'],
            $this->api['ACCESS_KEY'],
            $this->api['PROXY_HOST'],
            $this->api['PROXY_PORT'],
            $this->api['PROXY_LOGIN'],
            $this->api['PROXY_PASSWORD'],
            $this->api['ENVIRONMENT']
        );
        $payline->returnURL = $this->api['RETURN_URL'];
        $payline->cancelURL = $this->api['CANCEL_URL'];
        $payline->notificationURL = $this->api['NOTIFICATION_URL'];

        $ammout = $total*100;
        $token = Uuid::uuid4()->toString();

        //VERSION
        $array['version'] = $this->api['WS_VERSION'];
        $array['returnURL'] = $this->api['RETURN_URL'];
        $array['cancelURL'] = $this->api['CANCEL_URL'];
        $array['notificationURL'] = $this->api['NOTIFICATION_URL'];

        // PAYMENT
        $array['payment']['amount'] = $ammout;
        $array['payment']['currency'] = 978;
        $array['payment']['action'] = $this->api['PAYMENT_ACTION'];
        $array['payment']['mode'] = $this->api['PAYMENT_MODE'];

        // ORDER
        $array['order']['ref'] = $token;
        $array['order']['amount'] = $ammout;
        $array['order']['currency'] = 978;
        $array['order']['date'] = date("d/m/Y h:i");

        // CONTRACT NUMBERS
        $array['payment']['contractNumber'] = $this->api['CONTRACT_NUMBER'];
        $contracts = explode(";",$this->api['CONTRACT_NUMBER_LIST']);
        $array['contracts'] = $contracts;
        $secondContracts = explode(";",$this->api['SECOND_CONTRACT_NUMBER_LIST']);
        $array['secondContracts'] = $secondContracts;

        // EXECUTE
        $response = $payline->doWebPayment($array);
        if(isset($response) && $response['result']['code'] == '00000'){
            return[
                'redirect' => $response['redirectURL'],
                'token' => $response['token']
            ];
        }elseif(isset($response)) {
            die('ERROR : '.$response['result']['code']. ' '.$response['result']['longMessage']);
        }

    }

    public function auto(Request $request)
    {
        $this->config();
        $payline = new PaylineSDK(
            $this->api['MERCHANT_ID'],
            $this->api['ACCESS_KEY'],
            $this->api['PROXY_HOST'],
            $this->api['PROXY_PORT'],
            $this->api['PROXY_LOGIN'],
            $this->api['PROXY_PASSWORD'],
            $this->api['ENVIRONMENT']
        );
        // GET TOKEN
        if(isset($request->token)){
            $array['token'] = $request->token;
        }else{
            die('Missing TOKEN');
        }

        //VERSION
        if(isset($request->version)){
            $array['version'] = $request->version;
        }else {
            $array['version'] = '';
        }
        $response = $payline->getWebPaymentDetails($array);

        if(isset($response) && !empty($response) && $response['result']['code'] == '00000'){
            return[
                'id' => $array['token'],
                'token' => $array['token'],
                'payment' => $response
            ];
        }else{
            die();
        }
    }

    public function accept()
    {
        // TODO: Implement accept() method.
    }

    public function refuse()
    {
        // TODO: Implement refuse() method.
    }

    private function config()
    {
        $this->api = [
            'MERCHANT_ID' => config('services.citelis.merchant_id'),
            'ACCESS_KEY' => config('services.citelis.access_key'),
            'ACCESS_KEY_REF' => config('services.citelis.access_ref'),
            'PROXY_HOST' => '',
            'PROXY_PORT' => '',
            'PROXY_LOGIN' => '',
            'PROXY_PASSWORD' => '',
            'ENVIRONMENT' => config('services.citelis.environment'),
            'WS_VERSION' => '',
            'PAYMENT_CURRENCY' => '978',
            'ORDER_CURRENCY' => '978',
            'SECURITY_MODE' => '',
            'LANGUAGE_CODE' => '',
            'PAYMENT_ACTION' => '101',
            'PAYMENT_MODE' => 'CPT',
            'CANCEL_URL' => action('\Modules\ShoppingCart\Http\Controllers\ShoppingCartController@orderCancel'),
            'NOTIFICATION_URL' => action('\Modules\ShoppingCart\Http\Controllers\ShoppingCartController@orderReturn'),
            'RETURN_URL' => action('\Modules\ShoppingCart\Http\Controllers\ShoppingCartController@orderAccept'),
            'CUSTOM_PAYMENT_TEMPLATE_URL' => '',
            'CUSTOM_PAYMENT_PAGE_CODE' => '',
            'CONTRACT_NUMBER' => config('services.citelis.contract_number'),
            'CONTRACT_NUMBER_LIST' => '',
            'SECOND_CONTRACT_NUMBER_LIST' => '',
        ];
    }

}