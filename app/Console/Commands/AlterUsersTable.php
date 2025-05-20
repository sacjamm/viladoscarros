<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Console\Command; 

class AlterUsersTable extends Command
{
    /**
     * The name and signature of the console command.
     * php artisan alter:users-table
     * @var string
     */
   protected $signature = 'alter:users-table';

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
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
        });

        $this->info('Tabela users alterada com sucesso!');
    }
}
