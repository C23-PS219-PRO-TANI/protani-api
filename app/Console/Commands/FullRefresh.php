<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class FullRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'full-refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runing all common/ general cache and clear Artisan commands.';

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
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:cache');

        $message = Artisan::output() == "" ? 'Success Full-Refresh' : Artisan::output();
        $style   = new OutputFormatterStyle('green');
        $output  = new ConsoleOutput();
        $output->getFormatter()->setStyle('success', $style);
        return $output->writeln('<success>' . $message . '</>');
    }
}
