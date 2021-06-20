<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;

class Form extends Controller
{
    public function calc(Request $request)
    {
        $request->validate([
            'dateBegin' => ['required', 'date', 'after:'.date('Y-m-d')],
            'dateEnd' => ['required', 'date', 'after:dateBegin'],
            'people' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'seasonPrice' => ['nullable', 'numeric'],
            'seasonDateBegin' => ['nullable', 'date', 'required_with:seasonPrice'],
            'seasonDateEnd' => ['nullable', 'date', 'required_with:seasonDateBegin', 'after:seasonDateBegin'],
            'maxPeople' => ['nullable', 'numeric'],
            'pricePeople' => ['nullable', 'numeric', 'required_with:maxPeople'],
            'discount' => ['nullable', 'regex:/^\d+(\.\d{2})?(%)?$/'],
            'discountDays' => ['nullable', 'numeric', 'required_with:discount'],            
        ]);

        $dateBegin = new DateTime($request->dateBegin);
        $dateEnd = new DateTime($request->dateEnd);
        
        $convertDate = $this->formatDate($dateBegin, $dateEnd);

        $allChoosenDays = $convertDate['days']; // всего выбрано дней
        
        $includeSeasonDays = 0; // дней попадающих под сезонную цену
        
        $subtotal = 0;
        $discount = 0;
        $fee = 0;

        // подсчет дней попадания под сезонные даты
        if(!is_null($request->seasonDateBegin) && !is_null($request->seasonDateEnd)){
            $seasonDateBegin = new DateTime($request->seasonDateBegin);
            $seasonDateEnd = new DateTime($request->seasonDateEnd);
            foreach($convertDate['date'] as $choosenDate){
                $date = new DateTime($choosenDate);
                if(($date >= $seasonDateBegin) && ($date <= $seasonDateEnd)){
                    $includeSeasonDays++;
                }
            }       
            $subtotal += $includeSeasonDays * $request->seasonPrice; 
            if(!is_null($request->maxPeople)){
                $choosenPeople = $request->people;
                $maxPeople = $request->maxPeople;
                $addPeople = $maxPeople - $choosenPeople;
                $feeSeasonDay = $addPeople * $request->pricePeople;
                $fee = $feeSeasonDay * $includeSeasonDays;
            }    
        }
        $subtotal += ($allChoosenDays - $includeSeasonDays) * $request->price + $fee;

        if(!is_null($request->discount)){
            if($allChoosenDays >= $request->discountDays){
                $discount = $this->getDiscount($request->discount, $subtotal);                 
            }
        }
        $total = $subtotal - $discount;
        if($request->has('isSave')){
            $this->save($request);
        }
        $settings = Setting::paginate(15);
        return view('welcome', ['settings' => $settings, 'total' => $total, 'discount' => $discount]);
    }

    /**
     * Форматирование дат - разбиение диапазона, подсчет дней
     */
    private function formatDate(DateTime $startDate, DateTime $endDate, string $format='Y-m-d') : array
    {
        $result = [];
        $interval = $startDate->diff($endDate);
        $days = $interval->format('%a');
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $days);        
        foreach ($period as $date) {
            $result['date'][] = $date->format($format);
        }    
        $result['days'] = count($result['date']);    
        return $result;
    }

    /**
     * Подсчет скидки
     */
    private function getDiscount($discount, $total) : float
    {
        if(stristr($discount, '%') === false){            
            return floatval($discount);
        }
        
        $discount = preg_replace('/[^0-9]/', '', $discount);
        $discount = ($total * $discount) / 100;

        return floatval($discount);
    }

    /**
     * Сохранение расчета
     */
    private function save($request) : bool
    {
        $setting = Setting::create($request->all());
        return true;
    }
}
