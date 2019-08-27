<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    /**
     * Определяет необходимость отметок времени для модели.
     *
     * @var bool
     */
    public $timestamps = false;
    
    private $daysOfWeekRus = [
        '1' => 'Понедельник',
        '2' => 'Вторник',
        '3' => 'Среда',
        '4' => 'Четверг',
        '5' => 'Пятница',
        '6' => 'Суббота',
        '7' => 'Воскресенье'
    ];
    
    /**
     * Получить ближайшие доступные дни доставки для данного тарифа
     * 
     * @return array
     */
    public function getClosestAvailDays()
    {
        /**
         * @var array $currentWeekDays даты текущей недели
         */
        $currentWeekDays = [];
        $d = 0;
        for ($i = date('N'); $i <= 7; $i++) {
            $offset = "+$d day";

            $currentWeekDays[$i] = date('Y-m-d H:i:s', strtotime($offset));

            $d++;
        }

        /**
         * @var array $nextWeekDays даты следующей недели
         */
        $nextWeekDays = [];
        for ($i = 1; $i <= 7; $i++) {
            $offset = "+$d day";

            $nextWeekDays[$i] = date('Y-m-d H:i:s', strtotime($offset));

            $d++;
        }

        /**
         * @var array $tariffAvailDays дни недели, по которым доставляется данное меню
         */
        $tariffAvailDays = array_keys(json_decode($this->available_days, true));

        /**
         * Оставляем из дней текущей и следующей недель, только те дни недели,
         * по которым доставляется данное меню
         */
        $currentWeekDays = array_filter($currentWeekDays, function ($k) use($tariffAvailDays) {
            return in_array($k, $tariffAvailDays);
        }, ARRAY_FILTER_USE_KEY);
        $nextWeekDays = array_filter($nextWeekDays, function ($k) use($tariffAvailDays) {
            return in_array($k, $tariffAvailDays);
        }, ARRAY_FILTER_USE_KEY);

        foreach ($currentWeekDays as $k => $v) {
            $closestAvailDays[] = [
                'name' => $this->daysOfWeekRus[$k],
                'date' => date('d.m.Y', strtotime($v))
            ];
        }

        foreach ($nextWeekDays as $k => $v) {
            $closestAvailDays[] = [
                'name' => $this->daysOfWeekRus[$k],
                'date' => date('d.m.Y', strtotime($v))
            ];
        }

        return $closestAvailDays;
    }

}
