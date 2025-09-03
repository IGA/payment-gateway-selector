<?php

namespace App\Http\Controllers;

use App\Http\Requests\SelectPosRequest;
use App\Services\Pos\SelectionService;

class PaymentController extends Controller
{
    public function __construct(public SelectionService $posSelectionService) {}

    public function selectPos(SelectPosRequest $request)
    {
        $paymentDTO = $request->toDTO();

        $best = $this->posSelectionService->selectProvider($paymentDTO);
        if (null === $best) {
            return response()->json(['error' => 'Uygun bir POS bulunamadi.'], 404);
        }

        return response()->json([
            'filters' => $paymentDTO,
            'overall_min' => $best
        ]);
    }
}