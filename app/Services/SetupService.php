<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SetupService
{

    private $envFile, $envContent;
    function __construct()
    {
        $this->checkEnv();
    }

    private  function checkEnv()
    {
        $envPath = base_path('.env');
        $envExamplePath = base_path('.env.example');

        if (!File::exists($envPath)) {
            // .env does not exist, copy .env.example to .env
            if (File::exists($envExamplePath)) {
                File::copy($envExamplePath, $envPath);
                echo ".env file copied from .env.example\n";
                Artisan::call("key:generate");
            } else {
                echo ".env.example file is missing\n";
            }
        } else {
            echo ".env file already exists\n";
        }

        $this->envFile = app()->environmentFilePath();
        $this->envContent = File::get($this->envFile);
    }
    public function checkInstallation()
    {
        return file_exists(storage_path('installed'));
    }

    public function setupAppName($name)
    {
        $newEnvContent = preg_replace(
            '/^APP_NAME=.*$/m',
            'APP_NAME="' . $name . '"',
            $this->envContent
        );

        $this->envContent = $newEnvContent;
        file_put_contents('.env', $newEnvContent);
    }

    public function setupDatabase($conn, $port = '', $db = '', $username = '', $password = '')
    {
        // dd($conn, $port, $db, $username, $password);
        $newEnvContent = preg_replace(
            '/^DB_CONNECTION=.*$/m',
            'DB_CONNECTION=' . $conn,
            $this->envContent
        );
        $newEnvContent = preg_replace(
            '/^DB_PORT=.*$/m',
            'DB_PORT=' . $port,
            $newEnvContent
        );
        $newEnvContent = preg_replace(
            '/^DB_DATABASE=.*$/m',
            'DB_DATABASE=' . $db,
            $newEnvContent
        );
        $newEnvContent = preg_replace(
            '/^DB_USERNAME=.*$/m',
            'DB_USERNAME=' . $username,
            $newEnvContent
        );
        $newEnvContent = preg_replace(
            '/^DB_PASSWORD=.*$/m',
            'DB_PASSWORD=' . $password ?? '',
            $newEnvContent
        );

        $this->envContent = $newEnvContent;
        file_put_contents('.env', $newEnvContent);
        $this->checkDatabase($db);
    }

    private function checkDatabase($databaseName)
    {
        try {
            $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?";
            $result = DB::select($query, [$databaseName]);

            return !empty($result);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    function addWaterMark()
    {
        file_put_contents(storage_path('installed'), '');
    }
}
