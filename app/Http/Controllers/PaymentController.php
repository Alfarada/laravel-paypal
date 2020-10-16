<?php

namespace App\Http\Controllers;

use PayPal\Rest\ApiContext;
use Illuminate\Http\Request;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Support\Facades\Config;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Api\{Amount, Payer, Payment, PaymentExecution, RedirectUrls, Transaction};

class PaymentController extends Controller
{
    private $apiContext;

    public function __construct()
    {
        $paypalConfig = Config::get('paypal');

        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $paypalConfig['client_id'],     // ClientID
                $paypalConfig['secret']      // ClientSecret
            )
        );
    }

    public function payWithPaypal()
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new Amount();
        $amount->setTotal('1.00');
        $amount->setCurrency('USD');

        $transaction = new Transaction();
        $transaction->setAmount($amount);

        $callbackUrl = url('/paypal/status');

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($callbackUrl)
            ->setCancelUrl($callbackUrl);

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);


        try {
            $payment->create($this->apiContext);

            // echo $payment;

            return redirect()->away($payment->getApprovalLink());
        } catch (PayPalConnectionException $ex) {
            // This will print the detailed information on the exception.
            //REALLY HELPFUL FOR DEBUGGING
            echo $ex->getData();
        }
    }

    public function paypalStatus(Request $request)
    {
        $paymentId = $request->input('paymentId');
        $payerId = $request->input('PayerID');
        $token = $request->input('token');

        $status = 'No se pudo proceder con el pago de paypal';

        if (!$paymentId || !$payerId || !$token) {
            return redirect('/paypal/failed')->with(compact('status'));
        }

        $payment = Payment::get($paymentId, $this->apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        /**Execute the payment  */

        $result = $payment->execute($execution, $this->apiContext);
        // dd($result);

        if ($result->getState() === 'approved') {
            $status = 'Gracias ! El pago a través de Paypal se ha realizado correctamente.';
            return redirect('/results')->with(compact('status'));
        }

        $status = 'Lo sentimos ! El pago a través de Paypal no se pudo realizar.';
        return redirect('/results')->with(compact('status'));
    }
}
