<?php

namespace App\Http\Controllers;

use App\Http\Requests\MerchantGetIncomeRequest;
use App\Services\MerchantService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\InputBag;

class MerchantController extends BaseController
{
    public function __construct(
        private readonly MerchantService $merchantService,
    ){}

    public function addMerchant(Request $request): JsonResponse
    {
        /** @var InputBag $req */
        $req = $request->json();

        $res = $this->merchantService->addMerchant(
            $req->getString('name'),
        );

        return response()->json($res->toResponse());
    }

    public function getIncome(MerchantGetIncomeRequest $request): JsonResponse
    {
        $income = $this->merchantService->getIncome(
            $request->get('merchant_id'),
            Carbon::parse($request->get('from')),
            Carbon::parse($request->get('to'))
        );

        return response()->json([
            'income' => $income,
        ]);
    }
}
