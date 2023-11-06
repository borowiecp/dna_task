<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\InputBag;

class UserController extends BaseController
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    /**
     * @throws Exception
     */
    public function addUser(Request $request): JsonResponse
    {
        /** @var InputBag $req */
        $req = $request->json();

        $res = $this->userService->addUser(
            $req->getString('fullName'),
            $req->getString('email'),
            $req->getString('merchantId'),
        );

        return response()->json($res->toResponse());
    }
}
