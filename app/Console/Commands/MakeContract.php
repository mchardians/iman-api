<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeContract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:contract {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository contract (interface)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $directory = app_path('Repositories/Contracts');
        $filePath = "{$directory}/{$name}.php";

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (File::exists($filePath)) {
            $this->error("Contract repository {$name} already exists!");
            return Command::FAILURE;
        }

        File::put($filePath, $this->getStub($name));
        $this->info("Console command [{$filePath}] created successfully.");

        return Command::SUCCESS;
    }

    protected function getStub($interfaceName)
    {
        return <<<PHP
<?php

namespace App\Repositories\Contracts;

interface {$interfaceName}
{
    // Define your method signatures here
}
PHP;
    }
}
