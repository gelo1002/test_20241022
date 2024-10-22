
# Test

Proyecto de prueba funciona para conectarse a un servidor SFTP, esta basado en  PHP 8.3 y MySQL, si bien laravel tiene la forma para conectarse con "Filesystem Disks", decidí conectarme con PHP 

## Introducción

Los archivos/carpetas principales del proyecto se describen acontinuación

- App\Database\Migrations

        En esta carpeta se encuentran las migraciones que crean las tablas de la base de datos, deje algunas que son propias de laravel y agregue las del sistema requerido.

- App\Database\Seeders

        En esta carpeta se encuentran las seeders para crear la información base de las tablas de la base de datos

- App\Models

        Este esta carpeta se encuentran los archivos de los madelados de las tablas de la base de datos

- App\Console\Commands\File.php

        Este archivo es donde se configura el comando para ejecutar la tarea programada

- App\Routes\console.php

        Este archivo es donde se configura cada cuanto tiempo se va a ejecutar la tarea programada 

- App\Http\Controllers\CronController.php

        Este archivo de inicio, es llamado desde el archivo de comando de la tarea, aqui se listan una por una las tareas solicitadas

- App\Http\Controllers\SFTPController.php

        Este archivo de principal, es este se realiza la conexión la servidor SFTP y se realizan las diferentes acciones que solicitan, la función "readFile()", es la principal que obtiene las archivos del servidor y valida que sean de formato "txt", si con correctos procede a validar los datos se apoya en los controladores "FileController" y "StatisticsController", la funcion de "generateZip()" crea un directorio temporal y descarda los archivos leidos ahi, para posteriormente generar el zip, este se guarda por ahora en la capeta "public" de este repositorio, la funcion de "generateZip()" elimina los archivos leidos del servidor SFTP.

- App\Http\Controllers\FileController.php

        Este controlador guarda/actualiza los nombres y datos de los archivos permitidos segun las reglas mencionadas, en cuanto a los datos permitidos para guardar la información las reglas estan en la función l"rules()"

- App\Http\Controllers\StatisticsController.php

        Este controlador guarda/actualiza la información de los archivos permitidos segun las reglas mencionadas(formato de fechas y validación de email), en cuanto a los datos permitidos para guardar la información las reglas estan en la función 


## Modo local del proyecto

Como recomendación para ejecutar el proyecto deben tener instaldo docker y composer en su maquina local, las instrucciones son para apoyarse con la herramienta "sail" y hacer un despliegue mas sencillo, pero también lo puede correr de forma normal solo recuerde actualizar los datos del archivo .env con sus acceso a la db local y utilizar los comando php correctos

1.- Copia el archivo de ejemplo .env y actualizar los accesos al servidor SFTP estos datos estan al final del archivo:
```bash
    cp .env.example .env
```

2.- Instalar dependencias:
```bash
    composer install
```

3.- Generar alias de sail:
```bash
    alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

4.- Levantar contenedores(puede tardar):
```bash
    sail up
```

5.- Hay que esperar que termine de levantar los contenedores para crear las migraciones, en otr terminal dentro del mismo proyecto ejecute:
```bash
    sail artisan migrate
```

6.- Generar los seeders
```bash
    sail artisan db:seed
```

7.- Para ejecutar el comando que realiza las tareas programadas(debe esperar a la hora dentro del archivo routes/console.php)
```bash
    sail artisan schedule:work
```

8.- Si desea correr el comando directamente:
```bash
    sail artisan app:files 
```
8.- Si necesita reiniciar su base de datos:
```bash
    sail artisan migrate:rollback
    sail artisan migrate
    sail artisan db:seed
```

## Authors

- [@gelo1002](https://www.github.com/gelo1002)





