<?php

namespace App\Console\Commands;

use App\Services\BalanceService;
use App\Services\GameService;
use App\Tools\Auto\QQ\QQHelp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:data';

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
     * @return mixed
     */
    public function handle()
    {
        //退还余额
        $g = new GameService(new BalanceService());
        $g->returnMoney();
        //机器人自动


    }

}
