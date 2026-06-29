<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function initiate(Request $request)
    {
        $request->validate([
            'gateway' => 'required|in:esewa,khalti,fonepay,connectips,cash,card',
            'amount' => 'required|numeric|min:0',
            'order_id' => 'required',
            'success_url' => 'sometimes|url',
            'failure_url' => 'sometimes|url',
            'product_name' => 'sometimes|string',
        ]);

        $data = $request->only([
            'amount',
            'order_id',
            'success_url',
            'failure_url',
            'product_name',
        ]);

        $result = $this->paymentService->initiatePayment($request->gateway, $data);

        return response()->json($result);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'gateway' => 'required|in:esewa,khalti,fonepay,connectips,cash,card',
            'transaction_id' => 'required',
        ]);

        $result = $this->paymentService->verifyPayment(
            $request->gateway,
            $request->transaction_id
        );

        return response()->json($result);
    }

    public function refund(Request $request)
    {
        $request->validate([
            'gateway' => 'required|in:esewa,khalti,fonepay,connectips,cash,card',
            'transaction_id' => 'required',
            'amount' => 'required|numeric|min:0',
        ]);

        $result = $this->paymentService->refundPayment(
            $request->gateway,
            $request->transaction_id,
            $request->amount
        );

        return response()->json($result);
    }

    public function status(Request $request)
    {
        $request->validate([
            'gateway' => 'required|in:esewa,khalti,fonepay,connectips,cash,card',
            'transaction_id' => 'required',
        ]);

        $status = $this->paymentService->getPaymentStatus(
            $request->gateway,
            $request->transaction_id
        );

        return response()->json([
            'status' => $status,
        ]);
    }

    public function gateways()
    {
        return response()->json([
            'gateways' => $this->paymentService->getSupportedGateways(),
        ]);
    }

    // Webhook handlers for payment gateways
    public function esewaCallback(Request $request)
    {
        $oid = $request->oid;
        $amt = $request->amt;
        $refId = $request->refId;

        $result = $this->paymentService->verifyPayment('esewa', $refId);

        if ($result['success']) {
            // Update order payment status
            // This should be handled by OrderService
            return redirect()->away(config('app.url') . '/payment/success?ref=' . $refId);
        }

        return redirect()->away(config('app.url') . '/payment/failed?ref=' . $refId);
    }

    public function khaltiCallback(Request $request)
    {
        $pidx = $request->pidx;
        $data = $request->all();

        $result = $this->paymentService->verifyPayment('khalti', $pidx);

        if ($result['success']) {
            return redirect()->away(config('app.url') . '/payment/success?pidx=' . $pidx);
        }

        return redirect()->away(config('app.url') . '/payment/failed?pidx=' . $pidx);
    }
}
