<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class EcommerceInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecommerce:install {--force : Do not ask for user confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install dummy data for the Ecommerce application';

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
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('force')) {
            $this->proceed();
        } else {
            if ($this->confirm('This will delete all your current data and install the default data. are u sure?')) {
                $this->proceed();
            }
        }
    }

    protected function proceed()
    {
        File::deleteDirectory(public_path('storage/products/me'));
        $this->callSilent('storage:link');
        $copySuccess = File::copyDirectory(public_path('img/products'), public_path('storage/products/me'));

        if ($copySuccess) {
            $this->info('Images successfully copied to storage folder');
        }

        // this first command will run the migrate and seed the database and run the two command below
        $this->call('migrate:fresh', [
            '--seed' => true,
        ]);
        // $this->call('db:seed', [
        //     '--class' => 'VoyagerDatabaseSeeder',
        // ]);
        // $this->call('db:seed', [
        //     '--class' => 'VoyagerDummyDatabaseSeeder',
        // ]);

        // $this->info('Dummy data installed');
    }
}
