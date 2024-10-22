<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\{Statistics, Visitor};

class StatisticsController extends Controller
{


    public function create($data) {
        try {
            //validar datos
            $array_data = [
                'email'         => $data[0], //visitor_id
                'jyv'           => $data[1],
                'badmail'       => $data[2],
                'disable'       => $data[3],
                'date_shipment' => $data[4] != '-' ? $data[4] : null,
                'date_open'     => $data[5] != '-' ? $data[5] : null,
                'opens'         => $data[6],
                'opens_viral'   => $data[7],
                'date_click'    => $data[8] != '-' ? $data[8] : null,
                'clicks'        => $data[9],
                'clicks_viral'  => $data[10],
                'links'         => $data[11],
                'ips'           => $data[12],
                'browsers'      => $data[13],
                'platforms'     => $data[14],
            ];
            //print_r($array_data);

            $validator = Validator::make($array_data, $this->rules('create'));
            if($validator->fails()) {
                $errors = $validator->errors()->all();
                $errorString = implode(', ', $errors);
                return [0, "Error en fila del archivo: ".$errorString];
            }

            
            DB::transaction(function () use($array_data) {

                $visitor = Visitor::where('email', $array_data['email'])->first();

                if(!$visitor) {
                    $visitor = Visitor::create([
                        'email'             => $array_data['email'],
                        'date_first_visit'  => date('Y-m-d H:i:s'),
                        'date_last_visit'   => date('Y-m-d H:i:s'),
                        'total_visits'      => 1,
                        'visits_current_year'   => 1,
                        'visits_current_month'  => 1,
                        'current_month' => date('m'),
                        'current_year' => date('Y'),
                    ]);
                }
                else {
                    $visitor->date_last_visit = date('Y-m-d H:i:s');
                    $visitor->total_visits = $visitor->total_visits + 1;
                    $visitor->visits_current_year = ( $visitor->current_year == date('Y') ) ? $visitor->visits_current_year + 1 : 1;
                    $visitor->visits_current_month = ( $visitor->current_month == date('m') ) ? $visitor->visits_current_month + 1 : 1;
                    $visitor->current_month = date('m');
                    $visitor->current_year = date('Y');
                    $visitor->save();
                }

                $array_data['date_shipment'] = $array_data['date_shipment'] ? $this->changeFormatDate($array_data['date_shipment']) : $array_data['date_shipment'];
                $array_data['date_open'] = $array_data['date_open'] ? $this->changeFormatDate($array_data['date_open']) : $array_data['date_open'];
                $array_data['date_click'] = $array_data['date_click'] ? $this->changeFormatDate($array_data['date_click']) : $array_data['date_click'];
                $array_data['visitor_id'] = $visitor->id;
                $visitor = Statistics::create($array_data);
            });
            
            return [ true ];
        } 
        catch (\Exception $e) {
            return [0, $e->getMessage()];
        }
    }

    public function changeFormatDate($date) {
        $date_time = \DateTime::createFromFormat('d/m/Y H:i', $date);

        if ($date_time) {
            $new_date = $date_time->format('Y-m-d H:i:s');
            return $new_date;
        } 
        return null;
    }
    
    public function rules($action) {
        $rules = [
            'create' => [
                'email'         => 'required|email|max:250',
                'jyv'           => 'sometimes|max:50',
                'badmail'       => 'sometimes|max:50',
                'disable'       => 'sometimes|max:50',
                'date_shipment' => 'nullable|date_format:d/m/Y H:i',
                'date_open'     => 'nullable|date_format:d/m/Y H:i',
                'opens'         => 'sometimes|integer|max:999999999',
                'opens_viral'   => 'sometimes|integer|max:999999999',
                'date_click'    => 'nullable|date_format:d/m/Y H:i',
                'clicks'        => 'sometimes|integer|max:999999999',
                'clicks_viral'  => 'sometimes|integer|max:999999999',
                'links'         => 'sometimes|max:500',
                'ips'           => 'sometimes|max:500',
                'browsers'      => 'sometimes|max:500',
                'platforms'     => 'sometimes|max:500',
            ]
        ];

        return $rules[$action];
    }
    
}