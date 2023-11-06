<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Repositories\Eloquent\AdminRepository;
use App\Services\AuthService;
use Illuminate\Console\Command;

class SetupRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create app roles and permissions';

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
        $adminRepo = (new AdminRepository((new Admin())));
        $authService = (new AuthService($adminRepo))->setupAdminAccount();

        return 0;
    }
}
