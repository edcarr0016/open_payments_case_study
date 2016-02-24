<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use OpenPaymentsSeeder;

class UpdateOpenPaymentsData extends Command{

    protected $signature = 'updateOpenPaymentsData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will get another batch of open payments data';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $paymentsSeeder = new OpenPaymentsSeeder();

        $paymentsSeeder->run();
    }
}