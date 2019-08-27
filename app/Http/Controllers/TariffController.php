<?php

namespace App\Http\Controllers;

use App\Models\Tariff;
use Illuminate\Http\Request;

class TariffController extends Controller
{
    /**
     * Получить доступные тарифы
     */
    public function index()
    {
        $tariffs = Tariff::all();

        return $tariffs ? $tariffs : response()->json(['error' => 'Ошибка загрузки тарифов!']);
    }

    /**
     * Получить список ближайших дней недели, в которые можно заказать данный тариф
     */
    public function getAvailableDays(Request $request, Tariff $tariff)
    {
        $availDays = $tariff->getClosestAvailDays();

        return $availDays ? $availDays : response()->json(['error' => 'Ошибка загрузки доступных дат!']);
    }
}
