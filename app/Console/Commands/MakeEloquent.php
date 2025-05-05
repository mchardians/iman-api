<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeEloquent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:eloquent {name}';

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
        $filePath = app_path("Repositories/Eloquent/{$name}.php");

        if (!File::exists(app_path('Repositories/Eloquent'))) {
            File::makeDirectory(app_path('Repositories/Eloquent'), 0755, true);
        }

        if (File::exists($filePath)) {
            $this->error("Eloquent repository class {$name} already exists!");
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

namespace App\Repositories\Eloquent;

class {$className}
{
    public function __construct()
    {
        // Initialization
    }

    // Add repository methods here
}
PHP;
    }
}
