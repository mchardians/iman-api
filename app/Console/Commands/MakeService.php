<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Service repository class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $filePath = app_path("Services/{$name}.php");

        if (!File::exists(app_path('Services'))) {
            File::makeDirectory(app_path('Services'), 0755, true);
        }

        if (File::exists($filePath)) {
            $this->error("Service class {$name} already exists!");
            return Command::FAILURE;
        }

        File::put($filePath, $this->getStub($name));
        $this->info("Console command [{$filePath}] created successfully.");

        return Command::SUCCESS;
    }

    protected function getStub($className)
    {
        return <<<PHP
<?php

namespace App\Services;

class {$className}
{
    public function __construct()
    {
        // Initialization
    }

    // Add your service methods here
}
PHP;
    }
}
