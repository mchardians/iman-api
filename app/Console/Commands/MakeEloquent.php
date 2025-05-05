<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeEloquent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:eloquent {name} {--model=} {--contract=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent repository class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $model = $this->option('model');
        $contract = $this->option('contract');
        $filePath = app_path("Repositories/Eloquent/{$name}.php");

        if (!File::exists(app_path('Repositories/Eloquent'))) {
            File::makeDirectory(app_path('Repositories/Eloquent'), 0755, true);
        }

        if (File::exists($filePath)) {
            $this->error("Eloquent repository class {$name} already exists!");
            return Command::FAILURE;
        }

        File::put($filePath, $this->getStub($name, $model, $contract));
        $this->info("Console command [{$filePath}] created successfully.");

        return Command::SUCCESS;
    }

    protected function getStub($className, $model = null, $contract = null)
    {
        $modelUsage = "";
        $modelNamespace = "";
        $modelProperty = "";
        $constructor = "";
        $contractUsage = "";
        $contractImplementation = "";

        if($model) {
            $modelNamespace = "App\\Models\\". Str::studly($model);
            $modelCamel = Str::camel(class_basename($model));
            $modelName = class_basename($modelNamespace);
            $modelUsage = "use {$modelNamespace};";
            $modelProperty = "protected \${$modelCamel};\n";
            $constructor = <<<PHP
public function __construct({$modelName} \${$modelCamel})
    {
        \$this->{$modelCamel} = \${$modelCamel};
    }

PHP;
        }

        if($contract) {
            $contractNamespace = "App\\Repositories\\Contracts\\". Str::studly($contract);
            $contractName = class_basename($contractNamespace);
            $contractUsage = "use {$contractNamespace};";
            $contractImplementation = "Implements {$contractName}";
        }

        return <<<PHP
<?php

namespace App\Repositories\Eloquent;
{$modelUsage}
{$contractUsage}

class {$className} {$contractImplementation}
{
    {$modelProperty}
    {$constructor}
    // Add repository methods here
}
PHP;
    }
}
