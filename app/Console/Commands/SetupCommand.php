<?php

namespace App\Console\Commands;

use App\Services\SetupService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will setup you application for you';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $setupService = app(SetupService::class);
        if ($setupService->checkInstallation()) {
            $this->info("Project has been already installed");
            die();
        }

        // // Application name
        // $name = $this->ask('What is the name of the Application?', "Makura Creation Technical Assessment By Bishal Shrestha");
        // $setupService->setupAppName($name);

        // //Database Setup
        // $this->info("Database Setup");
        // $dbConnection = $this->choice("Database Connection?", [
        //     'mysql',
        //     'pgsql'
        // ], 0);
        // $dbPort = $this->ask('DB PORT:', "3306");
        // $dbDB = $this->ask('Database Name:', "laravel");
        // $dbUsername = $this->ask('Database Username:', 'root');
        // $dbPassword = $this->ask('Database Password:');
        // $setupService->setupDatabase($dbConnection, $dbPort, $dbDB, $dbUsername, $dbPassword);

        // $this->alert('Migrating in database');
        // Artisan::call('migrate');
        // $this->info("Migration Complete");

        // $this->alert("Configuring Passport for JSON Web Token(JWT)");
        // Artisan::call('passport:token-install');
        // $this->info("Passport ID and Secret have been configured");

        $setupService->addWaterMark();
    }
}
