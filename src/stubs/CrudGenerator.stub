<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CrudGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generator {model} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera la parte backend de un crud';

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
        try {
            $this->model = $this->argument('model');
            $this->modelCamelCase = $this->setModelCamelCase($this->model);
            $this->modelPlural = $this->setModelPlural($this->model);
            $this->modelSnakeCase = $this->setModelSnakeCase($this->modelPlural);
            $this->modelKebab = $this->setModelKebab($this->modelCamelCase);

            $optionAll = $this->option('all');

            if ($optionAll) {
                $this->generateAll();
            }
            else {
                // Migration
                if ($this->confirm('Crear Migration?')) {
                    $this->handleMigration();
                }

                // Model
                if ($this->confirm('Crear Modelo?')) {
                    $this->handleModel();
                }

                // Controller
                if ($this->confirm('Crear Controller?')) {
                    $this->handleController();
                }

                // Service
                if ($this->confirm('Crear Service?')) {
                    $this->handleService();
                }

                // Repository
                if ($this->confirm('Crear Repositorio?')) {
                    $this->handleRepository();
                }

                if ($this->confirm('Crear DataTables?')) {
                    $this->handleDataTable();
                }

                // Request
                if ($this->confirm('Crear Request?')) {
                    $this->handleRequest();
                }
            }

            $this->info('CrudGenerator finalizado correctamente.');

            return 0;
        }
        catch (\Throwable $e) {
            $this->info($e->getTraceAsString());
            Artisan::call('destroy:crud '.$this->model. ' noconfirm');
            return 1;
        }

    }

    /**
     * Genera el archivo migrations
     */
    public function handleMigration() {
        $columns = config('models.'.$this->modelCamelCase.'.migration');
        $columnsDefinition = '';
        $foreignsDefinition = '';

        foreach ($columns['columns'] as $nameColumn => $dataColumn) {
            $columnsDefinition .= '$table->'.$dataColumn['type'].'("'.$nameColumn.'")';
            if (array_key_exists('nullable', $dataColumn) && $dataColumn['nullable']) {
                $columnsDefinition .= '->nullable()';
            }
            if (array_key_exists('unique', $dataColumn) && $dataColumn['unique']) {
                $columnsDefinition .= '->unique()';
            }
            if (array_key_exists('default', $dataColumn) && $dataColumn['default']) {
                $columnsDefinition .= '->default("'.$dataColumn['default'].'")';
            }
            $columnsDefinition .= ';' . PHP_EOL . "\t\t\t";
        }

        if (array_key_exists('foreigns', $columns)) {
            foreach ($columns['foreigns'] as $foreignColumn => $dataForeign) {
                $foreignsDefinition .= '$table->foreign("'.$foreignColumn.'")->references("'.$dataForeign['references'].'")->on("'.$dataForeign['table'].'")';
                $foreignsDefinition .= $dataForeign['onDelete'] ? '->onDelete("'.$dataForeign['onDelete'].'")' : '';
                $foreignsDefinition .= ';' . PHP_EOL . "\t\t\t";
            }
        }


        $migration = $this->getStub('migration.create');
        $migration = $this->replaceTextVariables(['{{ modelUpperCamelCase }}', '{{ modelSnakeCase }}', '{{ columnsDefinition }}', '{{ foreignsDefinition }}'], [$this->setModelPlural($this->model), $this->modelSnakeCase, $columnsDefinition, $foreignsDefinition], $migration);
        $this->createFile(database_path('/migrations/'.Carbon::now()->format('Y_m_d_his').'_create_'.$this->modelSnakeCase.'_table.php'), $migration);

        $this->info('Migration creado correctamente.');
    }

    /**
     * Genera el archivo model
     */
    public function handleModel() {
        $fillable = implode('","', config('models.'.$this->modelCamelCase.'.model.fillable'));
        $fillable = '"'.$fillable.'"';
        $relations = config('models.'.$this->modelCamelCase.'.model.relations') ?? [];
        $relationsDefinition = '';

        foreach ($relations as $index => $relation) {
            if ($relation['relation'] === 'belongsToMany') {
                $relationsDefinition .= "\t".'public function '.$relation['functionName'].'() {'.PHP_EOL."\t\t".'return $this->'.$relation['relation'].'('.$relation['modelClass'].', "'.$relation['table'].'", "' .$this->modelSnakeCase.'", "'.$relation['foreignKey'].'", "'.$relation['relatedKey'].'")';
                if (array_key_exists('pivot', $relation) && $relation['pivot']) {
                    $relationsDefinition .= '->withPivot("'.$relation['pivot'].'")';
                }
                $relationsDefinition .= ';' . PHP_EOL;
                $relationsDefinition .= "\t".'}' . PHP_EOL;
            }
            else if ($relation['relation'] === 'morphTo') {
                $relationsDefinition .= "\t".'public function '.$relation['functionName'].'() {'.PHP_EOL."\t\t".'return $this->'.$relation['relation'].'();'.PHP_EOL."\t".'}'.PHP_EOL.PHP_EOL;
            }
            else if (in_array($relation['relation'], ['morphOne', 'morphMany', 'morphToMany', 'morphedByMany'])) {
                $relationsDefinition .= "\t".'public function '.$relation['functionName'].'() {'.PHP_EOL."\t\t".'return $this->'.$relation['relation'].'('.$relation['modelClass'].', "'.$relation['name'].'");'.PHP_EOL."\t".'}'.PHP_EOL.PHP_EOL;
            }
            else {
                $relationsDefinition .= "\t".'public function '.$relation['functionName'].'() {'.PHP_EOL."\t\t".'return $this->'.$relation['relation'].'('.$relation['modelClass'].', "'.$relation['foreign'].'");'.PHP_EOL."\t".'}'.PHP_EOL.PHP_EOL;
            }
        }

        $model = $this->getStub('model');
        $model = $this->replaceTextVariables(['{{ model }}', '{{ modelSnakeCase }}', '{{ fillable }}', '{{ relationsDefinition }}'], [$this->model, $this->modelSnakeCase, $fillable, $relationsDefinition], $model);
        $this->createFile(app_path('/Models/'.$this->model.'.php'), $model);

        $this->info('Modelo creado correctamente.');
    }

    /**
     * Genera el archivo repositorio
     */
    public function handleRepository() {
        $repository = $this->getStub('repository');
        $repository = $this->replaceTextVariables(['{{ modelUpperCamelCase }}'], [$this->model], $repository);
        $this->createPath(app_path('/Repositories/'.$this->model.'Repo'));
        $this->createFile(app_path('/Repositories/'.$this->model.'Repo/'.$this->model.'Repo.php'), $repository);

        $this->info('Repositorio creado correctamente');
    }

    /**
     * Genera el archivo service
     */
    public function handleService() {
        $service = $this->getStub('service');
        $service = $this->replaceTextVariables(['{{ modelUpperCamelCase }}', '{{ modelCamelCase }}'], [$this->model, $this->modelCamelCase], $service);
        $this->createPath(app_path('/Services/'.$this->model.'Service'));
        $this->createFile(app_path('/Services/'.$this->model.'Service/'.$this->model.'Service.php'), $service);

        $this->info('Service creado correctamente');
    }

    /**
     * Genera el archivo DataTables
     */
    public function handleDataTable() {
        $columnsDataTable = config('models.'.$this->modelCamelCase.'.dataTables');
        $columnsDefinition = '';

        foreach ($columnsDataTable as $name => $column) {
            $columnsDefinition .= 'Column::make("'.$name.'")->title(__("'.$column['label'].'")),' . PHP_EOL;
        }

        $datatable = $this->getStub('dataTable');
        $datatable = $this->replaceTextVariables(['{{ modelUpperCamelCase }}', '{{ modelCamelCase }}', '{{ modelKebab }}', '{{ columnsDataTable }}'], [$this->model, $this->modelCamelCase, $this->modelKebab, $columnsDefinition], $datatable);
        $this->createFile(app_path('/DataTables/'.$this->model.'DataTable.php'), $datatable);

        $this->info('DataTable creado correctamente');
    }

    /**
     * Genera el archivo request
     */
    public function handleRequest() {
        $request = $this->getStub('request');
        $request = $this->replaceTextVariables(['{{ modelUpperCamelCase }}'], [$this->model], $request);
        $this->createPath(app_path('/Http/Requests/'.$this->model.'Request'));
        $this->createFile(app_path('/Http/Requests/'.$this->model.'Request/'.$this->model.'Request.php'), $request);

        $this->info('Request creado correctamente.');
    }

    /**
     * Genera el archivo controlador
     */
    public function handleController() {
        $controller = $this->getStub('controller');
        $controller = $this->replaceTextVariables(['{{ modelUpperCamelCase }}', '{{ modelCamelCase }}'], [$this->model, $this->modelCamelCase], $controller);
        $this->createPath(base_path('app/Http/Controllers/'.$this->model.'Controller'));
        $this->createFile(base_path('app/Http/Controllers/'.$this->model.'Controller/'.$this->model.'Controller.php'), $controller);

        $this->info('Controlador creado correctamente.');
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
     * @param array $search
     * @param array $replace
     * @param $text
     * @return string|string[]
     */
    public function replaceTextVariables(array $search, array $replace, $text) {
        return str_replace($search, $replace, $text);
    }

    /**
     * @param string $name
     * @return string
     */
    public function getStub(string $name) {
        return file_get_contents(base_path('vendor/codegaf/crudgenerator/src/stubs/custom/'.$name.'.stub'));
    }

    /**
     * Crea un directorio si no existe
     * @param $path
     */
    public function createPath($path) {
        if (!is_dir($path)) {
            mkdir($path);
        }
    }

    /**
     * Crea el archivo
     * @param $path
     * @param $content
     */
    public function createFile($path, $content) {
        file_put_contents($path, $content);
    }

    /**
     * Si el usuario ha elegido la opción all se crean el crud completo
     */
    public function generateAll() {
        $this->handleMigration();
        $this->handleModel();
        $this->handleController();
        $this->handleService();
        $this->handleRepository();
        $this->handleDataTable();
        $this->handleRequest();
    }

}
