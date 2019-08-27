<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCreateRequest;
use App\Models\Order;
use App\Models\User;

class OrderController extends Controller
{
    /**
     * Создать заказ
     */
    public function create(OrderCreateRequest $request)
    {
        $phone = str_replace(['(',')','-'], '', $request->input('phone'));
        $name = $request->input('name');

        $user = User::firstOrCreate(
            ['phone' => $phone],
            ['name' => $name]
        );

        $order = Order::create([
            'user_id' => $user->id,
            'tariff_id' => $request->input('tariff_id'),
            'address' => $request->input('address'),
            'date_for_deliver' => $request->input('date')
        ]);

        return response()->json(["success" => true, "message" => "Ваш заказ №$order->id успешно создан! Дата доставки: " . date('d.m.Y', strtotime($order->date_for_deliver))]);
    }
}
