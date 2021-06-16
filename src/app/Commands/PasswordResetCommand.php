<?php

namespace VCComponent\Laravel\User\Commands;

use Illuminate\Console\Command;
use VCComponent\Laravel\User\Entities\User;
use Illuminate\Support\Facades\Hash;
class PasswordResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:reset {--email=} {--new_password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
            $email = $this->option('email');
            $new_password = $this->option('new_password');
            User::where('email', $email)->update([
                'email'  => $email,
                'password'  => Hash::make($new_password)
            ]);
            $this->info('Reset Password Success!');
        }
        catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
