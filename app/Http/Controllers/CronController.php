<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\{SFTPController, ErrorController};

class CronController extends Controller
{
    private $errors;

    public function __construct() {
        $this->errors = new ErrorController;
    }
    public function uploadInformation() {
        try {
            $host       = env('SFTP_HOST', 'http://localhost');  
            $username   = env('SFTP_USERNAME', 'username');
            $password   = env('SFTP_PASSWORD', 'password');
            $sftp_path  = env('SFTP_PATH', 'sftp_path');
            $zip_path   = env('SFTP_ZIP_PATH', 'sftp_zip_path');

            $file_header = [ 
                "email",
                "jyv",
                "Badmail",
                "Baja",
                "Fecha envio",
                "Fecha open",
                "Opens",
                "Opens virales",
                "Fecha click",
                "Clicks",
                "Clicks virales",
                "Links",
                "IPs",
                "Navegadores",
                "Plataformas"
            ];

            $sftp = new SFTPController($host, 22);
            $sftp->login($username, $password);
            $sftp->readFile($sftp_path, $file_header);
            $sftp->generateZip($zip_path, $sftp_path);
            $sftp->deleteFiles($sftp_path);
        } 
        catch (\Exception $e) {
            $this->errors->create(1, $e->getMessage());
            Log::info('Detalle del error: '.$e);
        }
    }
}
