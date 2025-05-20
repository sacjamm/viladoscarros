<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        // $schedule->command('inspire')->hourly();
        $schedule->command('import:lojas')->twiceDaily(9, 17);
        $schedule->command('veiculos:remove-veiculos-nao-encontrado')->everyTenMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        // Carregue automaticamente os comandos em app/Console/Commands
        $this->load(__DIR__ . '/Commands');
        //$this->command('import:lojas', [Commands\ImportLojas::class, 'handle']);

        require base_path('routes/console.php');
    }
}
