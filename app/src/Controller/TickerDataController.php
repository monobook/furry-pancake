<?php

namespace App\Controller;

use App\Service\TickerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TickerDataController extends AbstractController
{
    /**
     * @Route("/ticker/sma", name="ticker_sma")
     */
    public function sma(Request $request, TickerService $tickerService): Response
    {
        $period = $request->query->get('period');
        if (!filter_var($period, FILTER_VALIDATE_INT)) {
            return $this->json(['message' => 'Period is not valid'], Response::HTTP_BAD_REQUEST);
        }

        if (!$tickerService->isValidPeriod($period)) {
            return $this->json(['message' => 'Data not found for this period'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'sma' => $tickerService->calculateSmaForPeriod($period),
        ]);
    }
}
