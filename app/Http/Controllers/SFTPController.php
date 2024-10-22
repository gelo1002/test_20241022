<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use App\Http\Controllers\{FileController, StatisticsController, ErrorController};

class SFTPController extends Controller
{
    private $connection;
    private $sftp;
    private $errors;
    private $files;
    private $statistics;
    private $list_files;

    public function __construct($host, $port=22) {
        $this->connection = @ssh2_connect($host, $port);
        if (! $this->connection) {
            throw new \Exception("No se puede conectar al $host en el puerto $port.");
        }
        $this->errors = new ErrorController;
        $this->files = new FileController;
        $this->statistics = new StatisticsController;
        $this->list_files = [];
    }

    public function login($username, $password) {
        if (! @ssh2_auth_password($this->connection, $username, $password)) {
            throw new \Exception("No se pudo autentificar con el username $username y password $password.");
        }

        $this->sftp = @ssh2_sftp($this->connection);
        if (!$this->sftp) {
            throw new \Exception("No se pudo inicializar el subsistema SFTP.");
        }
    }

    public function readFile($sftp_path, $file_header) {
        $sftp = $this->sftp;
        
        $uploads_dir = 'ssh2.sftp://'.intval($sftp).$sftp_path;
        $dir_handle = @opendir($uploads_dir);

        if ($dir_handle === false) {
            throw new \Exception("Error al abrir el directorio");
        }
        
        // Listar archivos en el directorio
        while (($file = @readdir($dir_handle)) !== false) {
            if ($file != '.' && $file != '..') {
                $extension = explode(".", $file);
                // validar extensión
                if(end($extension) === 'txt') {
                     $create_file= $this->files->create($file);
                    if($create_file[0]) {

                        // Leer el contenido del archivo
                        $file_path = $uploads_dir . '/' . $file;
                        $handle = @fopen($file_path, 'r');
        
                        if ($handle) {
                            $validator_header = true;
                            $lines_uploaded = 0;
                            $lines_error = 0;
                            while (($line = fgets($handle)) !== false) {
                                // Parsear la línea como CSV
                                $data = str_getcsv($line);
        
                                // Revisar si la cabecera es correcta
                                if($validator_header) {
                                    if($data !== $file_header) {
                                        echo "cabeceras no coinciden en $file\n";
                                        $this->errors->create(3, "Las cabeceras del archivo son diferentes a las permitidas: ".implode(",", $data), $create_file[1]->id);
                                        $this->files->update($create_file[1]);
                                        //Si las cabeceras no coinciden pasar al file siguiente
                                        continue 2;
                                    }
                                    $validator_header = false;
                                }
                                else {
                                    //Guardar datos
                                    $create_statistics = $this->statistics->create($data);
                                    if(!$create_statistics[0]) {
                                        $this->errors->create(4, $create_statistics[1], $create_file[1]->id);
                                        $lines_error = $lines_error + 1;
                                    }
                                    else {
                                        $lines_uploaded = $lines_uploaded + 1;
                                    }
                                }
                            }
                            //Actualizar lista con los archivos que fueron almacenados
                            $this->list_files[] = $file;
                            $this->files->update($create_file[1],$lines_uploaded, $lines_error);
                            @fclose($handle);
                        } 
                        else {
                            $this->errors->create(1, "Error al leer el archivo ",$create_file[1]->id);
                            $this->files->update($create_file[1]);
                        }
                    }
                    else {
                        $this->errors->create(1, $create_file[1], $create_file[2]);
                    }
                }
                else {
                    $this->errors->create(2, "El archivo no tiene la extensión txt: $file");
                }
            }
        }

        // Cerrar el manejador del directorio
        @closedir($dir_handle);
    }

    public function generateZip($zip_path, $sftp_path) {

        $files = $this->list_files;
        
        // Crear un directorio temporal
        $temp_dir = sys_get_temp_dir() . '/sftp_files';
        mkdir($temp_dir, 0777, true);
    
        $this->downloadFiles($temp_dir, $sftp_path);
        
        $zip_files = [];
        foreach ($files as $file) {
            $zip_files[] = [
                'path' => $temp_dir . '/' . $file, 
                'name' => $file 
            ];
        }
    
        // path de zip
        $name = date('Ymd_His').".zip";
        $zipFilePath = public_path($name);
    
        // Crear el archivo ZIP
        $zip = new \ZipArchive();
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($zip_files as $file) {
                $zip->addFile($file['path'], $file['name']);
            }
            $zip->close();
        } else {
            $this->errors->create(1, 'Error al crear el archivo ZIP');
        }
    
        // Eliminar los archivos temporales
        array_map('unlink', glob("$temp_dir/*"));
        rmdir($temp_dir);
    }

    function downloadFiles($temp_dir, $sftp_path) {
        $sftp = $this->sftp;
        $files = $this->list_files;
        foreach ($files as $file) {
            $remote_file_path = "ssh2.sftp://" . intval($sftp) . $sftp_path. '/' .$file;
            $local_file_path = $temp_dir . '/' . $file;
            // Copiar archivos en el directorio temporal
            if (!copy($remote_file_path, $local_file_path)) {
                throw new \Exception('Error al descargar el archivo: ' . $file);
            }
        }
    }

    public function deleteFiles($sftp_path) {
        $files = $this->list_files;
        $sftp = $this->sftp;
        foreach ($files as $file) {
            $remote_file_path = "ssh2.sftp://" . intval($sftp) . $sftp_path. '/' .$file;
            
            if (file_exists("ssh2.sftp://" . intval($sftp) . $sftp_path. '/' .$file)) {
                // Eliminar archivos
                if (unlink($remote_file_path)) {
                    echo "Archivo eliminado: $remote_file_path\n";
                } else {
                    echo "Error al eliminar el archivo: $remote_file_path\n";
                }
            } else {
                echo "El archivo no existe: $remote_file_path\n";
            }
        }
    }
    
}

