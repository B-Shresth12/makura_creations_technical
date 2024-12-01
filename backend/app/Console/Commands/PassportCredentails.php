<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PassportCredentails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passport:token-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will geenrate Passport token and insert into environment file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the output from the passport:client command
        $output = Artisan::output(
            Artisan::call('passport:client --password --name="autentication" --provider=users')
        );

        // Extracting Client ID
        preg_match('/Client ID\s+\.+\s+([a-f0-9\-]{36})/', $output, $clientId);
        // Extracting Client Secret
        preg_match('/Client secret\s+\.+\s+(\S+)/', $output, $clientSecret);
        if (!empty($clientId) && !empty($clientSecret)) {
            $envFile = app()->environmentFilePath();
            $envContent = File::get($envFile);
            $newEnvContent = preg_replace(
                '/^PASSPORT_CLIENT_ID=.*$/m', // Match the line starting with PASSPORT_CLIENT_ID=
                'PASSPORT_CLIENT_ID=' . $clientId[1], // Replace with the new value
                $envContent
            );
            $newEnvContent = preg_replace(
                '/^PASSPORT_CLIENT_SECRET=.*$/m', // Match the line starting with PASSPORT_CLIENT_SSECRET=
                'PASSPORT_CLIENT_SECRET=' . $clientSecret[1], // Replace with the new value
                $newEnvContent
            );

            // Storing new value in env
            file_put_contents('.env', $newEnvContent);
            $this->info("Passport credentails have been added to .env file");
        } else {
            $this->error("Could not extract the client credentials");
        }
    }
}
