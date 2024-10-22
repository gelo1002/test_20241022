<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\File;

class FileController extends Controller
{
    public function create($name) {
        try {
            
            $file = File::where('name', $name)->first();
    
            if(!$file){
                $data = ['name' => $name];
                $validator = Validator::make($data, $this->rules('create'));
                if($validator->fails()) {
                    $errors = $validator->errors()->all();
                    $errorString = implode(', ', $errors);
                    return [0, "Error en el archivo: $name :".$errorString, null];
                    
                }
                $file = DB::transaction(function () use($name) {
                    $file = File::create([
                        'name'          => $name,
                        'upload_date'   => date('Y-m-d H:i:s'),
                        'lines_uploaded' => 0,
                        'lines_error'   => 0,
                        'status'        => false,
                    ]);

                    return $file;
                });
                
            }
            if($file->status) {
                return [ 0, "Este archivo ya fue cargado antes", $file->id];
            }
            return [ 1, $file ];
        } 
        catch (\Exception $e) {
            return [0, $e->getMessage(), null];
        }
    }

    public function update($file, $lines_uploaded=0, $lines_error=0) {
        try {
            
            DB::transaction(function () use($file, $lines_uploaded, $lines_error) {
                $file->lines_uploaded = $lines_uploaded;
                $file->lines_error = $lines_error;
                $file->status = 1;
                $file->save();
            });
            return [ 1, $file ];
        } 
        catch (\Exception $e) {
            return [0, $e->getMessage()];
        }
    }
    
    public function rules($action) {
        $rules = [
            'create' => [
                'name' => 'required|max:250'
            ],
            'update' => [
                'lines_uploaded' => 'required|integer|max:999999999',
                'lines_error' => 'required|integer|max:999999999',
            ]
        ];

        return $rules[$action];
    }
    
}
