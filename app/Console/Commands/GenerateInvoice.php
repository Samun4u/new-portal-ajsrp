<?php

namespace App\Console\Commands;

use App\Http\Controllers\User\InvoiceController;
use Illuminate\Console\Command;

class GenerateInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate invoice by client';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $InvoiceController = new InvoiceController();
        $InvoiceController->recurringInvoiceMaker();
        echo 'success';
    }
}
