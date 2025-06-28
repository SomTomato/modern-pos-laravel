<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CreateInitialData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-initial-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the initial admin user and general customer for the application';

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
            // Check if the admin user already exists
            if (User::where('username', 'admin')->doesntExist()) {
                User::create([
                    'username' => 'admin',
                    'password' => Hash::make('password'),
                    'role' => 'admin'
                ]);
                $this->info('Admin user created successfully!');
            } else {
                $this->comment('Admin user already exists.');
            }

            // Check if the general customer already exists
            if (Customer::where('id', 1)->doesntExist()) {
                Customer::create([
                    'id' => 1, 
                    'name' => 'General Customer', 
                    'phone_number' => null
                ]);
                $this->info('General Customer created successfully!');
            } else {
                $this->comment('General Customer already exists.');
            }
        } catch (\Exception $e) {
            $this->error('An error occurred!');
            $this->error($e->getMessage());
            Log::error($e);
            return 1;
        }
        
        return 0;
    }
}
