<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentDto;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\InputBag;

class PaymentController extends BaseController
{
    public function __construct(
        private readonly PaymentService $paymentService,
    ) {}

    /**
     * @throws Exception
     */
    public function addPayment(Request $request): JsonResponse
    {
        /** @var InputBag $req */
        $req = $request->json();

        $paymentDto = new PaymentDto(
            null,
            $req->getString('user_id'),
            $req->getString('merchant_id'),
            floatval($req->getString('amount')),
        );

        $res = $this->paymentService->addPayment($paymentDto);

        return response()->json($res->toResponse());
    }
}
