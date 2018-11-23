<?php

namespace App\Console\Commands;

use App\Models\Packet;
use App\Models\Room;
use App\Services\BalanceService;
use App\Services\GameService;
use App\Tools\Auto\QQ\QQHelp;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Robot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Robot';

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
        try{
            for ($i = 0; $i < 6; $i++) {
                $s = date('s');
                if ($s < 55) {
                    $this->send();
                    $r = date('s');
                    sleep(10 - max(0, ($r - $s)));
                }
            }

        }catch(\Exception $e){
            Log::error($e);
        }

    }

    public function send()
    {
        //自动发
        $rooms = Room::where('send', '<', time() - 10)->get();
        $game = new GameService(new BalanceService());
        foreach ($rooms as $room) {
            $robot = User::where('type', 1)->where('room_id', $room->id)->where('balance', '>', 50000)->inRandomOrder()->first();
            if ($robot) {
                $money = $room->min + intval(($room->max - $room->min) * mt_rand(1, 9) / 1000) * 100;
                $game->putPacket($room->id, $robot->id, $money, mt_rand(1, 9), mt_rand(8, 10), '恭喜发财');
            }
        }
        //自动抢
        $packets = Packet::where('state', 0)
            ->whereColumn('packets', '>', 'got')
            ->get();
        foreach ($packets as $packet) {

            $rest = $packet->packets - $packet->got;
            $robots = User::where('type', 1)->where('room_id', $packet->room_id)->where('balance', '>', 10000)->inRandomOrder()->limit($rest)->get();
            if ($robots) {
                $r = [
                    1 => 6,
                    2 => 6,
                    3 => 8,
                    4 => 8,
                    5 => 9,
                    6 => 10,
                    7 => 10,
                    8 => 10,
                    9 => 10,
                    10 => 10,
                    11 => 10,
                ];
                foreach ($robots as $robot) {
                    if ($rest > 0 && $r[$rest] > mt_rand(1, 9)) {
                        $rest--;
                        $game->getPacket($robot->id, $packet->id);
                    } else {
                        break;

                    }
                }

            }
        }
    }

}
