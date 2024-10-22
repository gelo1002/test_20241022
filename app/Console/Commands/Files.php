<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\CronController;

class Files extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron para leer archivos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Tareas a ser ejecutadas por el comando
        $file_upload = new CronController;
        $file_upload->uploadInformation();
    }
}
