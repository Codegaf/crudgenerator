<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class DestroyCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'destroy:crud {model} {noconfirm?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina un crud completo excepto el migration';

    protected $model;
    protected $modelCamelCase;
    protected $modelSnakeCase;
    protected $modelPlural;
    protected $modelKebab;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->model = $this->argument('model');
        $this->modelCamelCase = $this->setModelCamelCase($this->model);
        $this->modelPlural = $this->setModelPlural($this->model);
        $this->modelSnakeCase = $this->setModelSnakeCase($this->modelPlural);
        $this->modelKebab = $this->setModelKebab($this->modelCamelCase);

        if ($this->argument('noconfirm') === 'noconfirm') {
            $this->handleDestroyModel();
            $this->handleDestroyController();
            $this->handleDestroyService();
            $this->handleDestroyRepository();
            $this->handleDestroyRequest();
            $this->handleDestroyDatatable();
        }
        else {
            if ($this->confirm('Esta seguro que desea eliminar todo el crud?')) {
                $this->handleDestroyModel();
                $this->handleDestroyController();
                $this->handleDestroyService();
                $this->handleDestroyRepository();
                $this->handleDestroyRequest();
                $this->handleDestroyDatatable();

                $this->info('DestroyCrud finalizado correctamente');
            }
        }


        return 0;
    }

    /**
     * @param string $string
     * @return string
     */
    public function setModelCamelCase(string $string) {
        return Str::camel($string);
    }

    /**
     * @param string $string
     * @return string
     */
    public function setModelSnakeCase(string $string) {
        return Str::snake($string);
    }

    /**
     * @param string $string
     * @return string
     */
    public function setModelPlural(string $string) {
        return Str::plural($string);
    }

    /**
     * @param string $string
     * @return string
     */
    public function setModelKebab(string $string) {
        return Str::kebab($string);
    }

    /**
     * Elimina el controlador
     */
    public function handleDestroyController() {
        if (file_exists(app_path('/Http/Controllers/'.$this->model.'Controller/'.$this->model.'Controller.php'))) {
            unlink(app_path('/Http/Controllers/'.$this->model.'Controller/'.$this->model.'Controller.php'));

            $this->info('Controlador eliminado correctamente');
        }
        if (is_dir(app_path('/Http/Controllers/'.$this->model.'Controller'))) {
            rmdir(app_path('/Http/Controllers/'.$this->model.'Controller'));

            $this->info('Directorio de Controlador eliminado correctamente');
        }
    }

    /**
     * Elimina el modelo
     */
    public function handleDestroyModel() {
        if (file_exists(app_path('/Models/'.$this->model.'.php'))) {
            unlink(app_path('/Models/'.$this->model.'.php'));

            $this->info('Modelo eliminado correctamente');
        }
    }

    /**
     * Elimina el servicio
     */
    public function handleDestroyService() {
        if (file_exists(app_path('/Services/'.$this->model.'Service/'.$this->model.'Service.php'))) {
            unlink(app_path('/Services/'.$this->model.'Service/'.$this->model.'Service.php'));

            $this->info('Servicio eliminado correctamente');
        }
        if (is_dir(app_path('/Services/'.$this->model.'Service'))) {
            rmdir(app_path('/Services/'.$this->model.'Service'));

            $this->info('Directorio Servicio eliminado correctamente');
        }
    }

    /**
     * Elimina el repositorio
     */
    public function handleDestroyRepository() {
        if (file_exists(app_path('/Repositories/'.$this->model.'Repo/'.$this->model.'Repo.php'))) {
            unlink(app_path('/Repositories/'.$this->model.'Repo/'.$this->model.'Repo.php'));

            $this->info('Repositorio eliminado correctamente');
        }
        if (is_dir(app_path('/Repositories/'.$this->model.'Repo'))) {
            rmdir(app_path('/Repositories/'.$this->model.'Repo'));

            $this->info('Directorio Repositorio eliminado correctamente');
        }
    }

    /**
     * Elimina el request
     */
    public function handleDestroyRequest() {
        if (file_exists(app_path('/Http/Requests/'.$this->model.'Request/'.$this->model.'Request.php'))) {
            unlink(app_path('/Http/Requests/'.$this->model.'Request/'.$this->model.'Request.php'));

            $this->info('Request eliminado correctamente');
        }
        if (is_dir(app_path('/Http/Requests/'.$this->model.'Request'))) {
            rmdir(app_path('/Http/Requests/'.$this->model.'Request'));

            $this->info('Directorio Request eliminado correctamente');
        }
    }

    /**
     * Elimina el archivo DataTables
     */
    public function handleDestroyDatatable() {
        if (file_exists(app_path('/Datatables/'.$this->model.'DataTable.php'))) {
            unlink(app_path('/Datatables/'.$this->model.'DataTable.php'));

            $this->info('DataTable eliminado correctamente');
        }
    }


}
