<?php

namespace Ibex\CrudGenerator\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;

/**
 * Class CrudGenerator.
 *
 * @author  Awais <asargodha@gmail.com>
 */
class CrudGenerator extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud
                            {name : Table name}
                            {--route= : Custom route name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Laravel CRUD operations';

    /**
     * Execute the console command.
     *
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $this->info('Running Crud Generator ...');

        $this->table = $this->getNameInput();

        // Build the class name from table name
        $this->name = $this->_buildClassName();

        // Generate the crud
        $this->buildOptions()
            ->buildMigration()
            ->buildController()
            ->buildModel()
            ->buildPolicy()
            ->buildViews()
            ->writeRoute();

        $this->info('Created Successfully.');

        return true;
    }

    /**
     * Build Migration File.
     *
     * @return $this
     *
     * @throws FileNotFoundException
     */
    protected function buildMigration(): static
    {
        $migrationPath = $this->_getMigrationPath($this->name);

        // Check if migration already exists
        if ($this->files->exists($migrationPath)) {
            $this->info("Migration for `{$this->table}` already exists, skipping...");
            return $this;
        }

        $this->info('Creating Migration ...');

        $replace = array_merge($this->buildReplacements(), [
            '{{migrationColumns}}' => $this->buildMigrationColumns(),
            '{{timestamp}}' => now()->format('Y_m_d_His'),
        ]);

        $migrationTemplate = str_replace(
            array_keys($replace),
            array_values($replace),
            $this->getStub('migration')
        );

        $this->write($migrationPath, $migrationTemplate);

        return $this;
    }

    /**
     * Build migration columns from table schema.
     */
    protected function buildMigrationColumns(): string
    {
        $columns = '';

        foreach ($this->getColumns() as $column) {
            $name = $column['name'];
            $type = $column['type_name'];
            $nullable = $column['nullable'] ?? false;
            $default = $column['default'] ?? null;
            $length = $column['length'] ?? null;

            // Skip unwanted columns
            if (in_array($name, $this->unwantedColumns)) {
                continue;
            }

            $nullableStr = $nullable ? '->nullable()' : '';
            $defaultStr = $default !== null ? "->default('$default')" : '';

            // Determine column type based on database type
            $columnType = match ($type) {
                'int', 'integer', 'bigint', 'smallint', 'tinyint', 'mediumint' => 'integer',
                'decimal', 'float', 'double', 'real' => 'decimal',
                'bool', 'boolean' => 'boolean',
                'date' => 'date',
                'datetime', 'timestamp' => 'dateTime',
                'time' => 'time',
                'text', 'mediumtext', 'longtext' => 'text',
                'json' => 'json',
                'uuid' => 'uuid',
                default => 'string',
            };

            // Handle string with length
            if ($columnType === 'string' && $length) {
                $columns .= "\$table->$columnType($length, '$name')$nullableStr$defaultStr;\n";
            } else {
                $columns .= "\$table->$columnType('$name')$nullableStr$defaultStr;\n";
            }
        }

        return $columns;
    }

    protected function writeRoute(): static
    {
        $this->info('Please add route below: i:e; web.php');

        $this->info('');

        $line = "Route::resource('".$this->_getRoute()."', {$this->name}Controller::class);";

        $this->info('<bg=blue;fg=white>'.$line.'</>');

        $this->info('');

        return $this;
    }

    protected function _getPolicyPath($name): string
    {
        return $this->makeDirectory(app_path("Policies/{$name}Policy.php"));
    }

    /**
     * Build the Policy Class.
     *
     * @return $this
     *
     * @throws FileNotFoundException
     */
    protected function buildPolicy(): static
    {
        $policyPath = $this->_getPolicyPath($this->name);

        if ($this->files->exists($policyPath)) {
            $this->info("Policy for `{$this->name}` already exists, skipping...");
            return $this;
        }

        $this->info('Creating Policy ...');

        $replace = $this->buildReplacements();

        $policyTemplate = str_replace(
            array_keys($replace),
            array_values($replace),
            $this->getStub('Policy')
        );

        $this->write($policyPath, $policyTemplate);

        return $this;
    }

    /**
     * Build the Controller Class and save in app/Http/Controllers.
     *
     * @return $this
     *
     * @throws FileNotFoundException
     */
    protected function buildController(): static
    {
        $controllerPath = $this->_getControllerPath($this->name);

        if ($this->files->exists($controllerPath) && $this->ask('Already exist Controller. Do you want overwrite (y/n)?', 'y') == 'n') {
            return $this;
        }

        $this->info('Creating Controller ...');

        $replace = $this->buildReplacements();

        $controllerTemplate = str_replace(
            array_keys($replace),
            array_values($replace),
            $this->getStub('Controller')
        );

        $this->write($controllerPath, $controllerTemplate);

        return $this;
    }

    /**
     * @return $this
     *
     * @throws FileNotFoundException
     */
    protected function buildModel(): static
    {
        $modelPath = $this->_getModelPath($this->name);

        if ($this->files->exists($modelPath) && $this->ask('Already exist Model. Do you want overwrite (y/n)?', 'y') == 'n') {
            return $this;
        }

        $this->info('Creating Model ...');

        // Make the models attributes and replacement
        $replace = array_merge($this->buildReplacements(), $this->modelReplacements());

        $modelTemplate = str_replace(
            array_keys($replace),
            array_values($replace),
            $this->getStub('Model')
        );

        $this->write($modelPath, $modelTemplate);

        return $this;
    }

    /**
     * @return $this
     *
     * @throws FileNotFoundException
     */
    protected function buildViews(): static
    {
        $this->info('Creating Views ...');

        $viewRows = "\n";
        $form = "\n";

        foreach ($this->getFilteredColumns() as $column) {
            $title = Str::title(str_replace('_', ' ', $column));

            $viewRows .= $this->getField($title, $column, 'view-field');
            $form .= $this->getField($title, $column);
        }

        $replace = array_merge($this->buildReplacements(), [
            '{{viewRows}}' => $viewRows,
            '{{form}}' => $form,
        ]);

        $this->buildLayout();

        foreach ([
            'form',
            'table',
        ] as $view) {
            $viewTemplate = str_replace(
                array_keys($replace),
                array_values($replace),
                $this->getStub("views/$view")
            );

            $this->write($this->_getViewPath($view), $viewTemplate);
        }

        return $this;
    }

    /**
     * Make the class name from table name.
     */
    private function _buildClassName(): string
    {
        return Str::studly($this->table);
    }
}
