<?php

/////////////////////////////////////////////////////////
// php artisan db:seed --class=BouSMAddNewBlock
////////////////////////////////////////////////////////

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Techno;
use App\Models\Status;
use App\Models\Product;
use App\Models\Number;
use App\Models\User;

class BouSMAddNewBlock extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $product   = Product::where('title',"БОУ-СМ")->first();
            $user      = User::where('name','admin')->first();
            $test      = Techno::where("title","проверка в НКУ")->first();
            $test_ok   = Status::where([["techno_id",$test->id],["level","ok"]])->first();
            $set_sn    = Techno::where("title","установка серийного номера")->first();
            $status_none= Status::find(1);
    
            $date= '2025-01-25';
    
            foreach (array(514) as $sn) {
                $event= new Event;
                $event->date        = $date;
                $event->product_id  = $product->id;
                $event->sn_p        = 6;
                $event->sn_m        = 0;
                $event->sn_n        = $sn;
                $event->techno_id   = $set_sn->id;
                $event->status_id   = $status_none->id;
                $event->active      = true;
                $event->user_id     = $user->id;
                $event->save();

                $event= new Event;
                $event->date        = $date;
                $event->product_id  = $product->id;
                $event->sn_p        = 6;
                $event->sn_m        = 0;
                $event->sn_n        = $sn;
                $event->techno_id   = $test->id;
                $event->status_id   = $test_ok->id;
                $event->active      = true;
                $event->user_id     = $user->id;
                $event->save();
            }
        });
    }
}
