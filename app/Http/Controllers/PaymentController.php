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
            return response()->json(['error' => 'Not found an available provider.'], 404);
        }

        \Log::info("Selected provider", [
            'provider' => $best,
        ]);

        return response()->json([
            'filters' => $paymentDTO,
            'overall_min' => $best
        ]);
    }
}