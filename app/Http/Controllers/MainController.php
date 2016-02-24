<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Payments;
use Illuminate\Contracts\Container\Container;

use Illuminate\Support\Collection;

use Excel;

class MainController extends Controller
{
    private $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    //
    public function getChartData(){
        $payments = new Payments();

        $paymentTypes = $payments->getPaymentsByType();
        $paymentChartTypes = [];
        foreach($paymentTypes as $paymentType){
            $paymentChartTypes['labels'][] = $paymentType->payment_type;
            $paymentChartTypes['set'][] = $paymentType->payment_amount;
        }

        $paymentTypesByMonth = $payments->getPaymentsByTypeAndMonth();
        $paymentChartTypesByMonth = [];
        for($i = 0; $i < count($paymentChartTypes['set']); $i++){
            $paymentChartTypesByMonth['set'][] = array_fill(0,12,0);
        }

        $paymentChartTypesByMonth['labels'] = $this->months;
        foreach($paymentTypesByMonth as $paymentType){
            $typeKey = array_search($paymentType->payment_type, $paymentChartTypes['labels']);

            $paymentChartTypesByMonth['set'][$typeKey][$paymentType->payment_month-1] = $paymentType->payment_amount;
        }

        return [
            'data'=>[
                'type'=>$paymentChartTypes,
                'typeAndMonth'=>$paymentChartTypesByMonth
            ]
        ];
    }

    public function paymentSearch(Container $app, Payments $payment, $query){
        $elastic = $app->make("ElasticSearch");

        $hits = $elastic->search($payment, $query)['hits']['hits'];

        foreach($hits as &$hit){
            $display = $hit['_source']['payment_form'] .' '. strtolower($hit['_source']['payment_type']);
            $display .= array_key_exists('physician_first_name', $hit['_source']) ? ' for '. $hit['_source']['physician_first_name'] .' '. $hit['_source']['physician_last_name'] : '';
            $display .= ' by '. $hit['_source']['gpo_name_orig'];
            $display .= ' on '. $hit['_source']['payment_date'];

            $displayParts = explode(' ', $display);
            $queryParts = explode(' ', $query);

            foreach($displayParts as &$displayPart){
                foreach($queryParts as $queryPart){
                    $strpos = stripos($displayPart, $queryPart);
                    if(is_numeric($strpos)){
                        $strlen = strlen($queryPart);
//                        $displayPart = substr($displayPart, )"<span style='color:dodgerblue'>". substr($displayPart, $strpos, $strlen) ."</span>". substr($displayPart,$strpos + $strlen);
                        $displayPart = substr_replace($displayPart, "<span style='color:dodgerblue'>". substr($displayPart, $strpos, $strlen) ."</span>",$strpos,$strlen);
                        break;
                    }
                }
            }


            $hit['display'] = implode(' ',$displayParts);
        }

        return $hits;
    }

    public function downloadExcel(Container $app, Payments $payment, $query){
        $now = Carbon::now();
        $now->setToStringFormat("Ymd_His");

        $elastic = $app->make("ElasticSearch");

        $hits = new Collection($elastic->search($payment, $query)['hits']['hits']);
        $payments = $payment->find($hits->pluck('_source.id')->toArray());

        return Excel::create('open_payments_'.$now->__toString(), function($excel) use ($payments){

            $excel->sheet('data', function($sheet) use ($payments){
                $sheet->fromArray($payments->toArray());
            });

        })->export("xlsx");

    }
}
