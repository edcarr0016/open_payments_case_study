<?php

use Illuminate\Database\Seeder;
use \Exception;
use App\OpenPayments;
use App\Payments;
use App\Physician;
use App\GPO;
use App\RecipientLocation;

class OpenPaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        {
            do {
                $offset = Payments::count();

                $records = OpenPayments::getData(400, $offset);

                foreach($records as $record){
                    $gpo = GPO::create($record);
                    $recipientLocation = RecipientLocation::create($record);

                    $payment = new Payments();
                    $payment->fill($record);
                    if(array_key_exists('physician_profile_id',$record)){
                        $physician = Physician::create($record);
                        $payment->physician_id = $physician->id;
                    }
                    $payment->gpo_id = $gpo->id;
                    $payment->recipient_location_id = $recipientLocation->id;
                    $payment->save();
                };

//            }while(!$records->isEmpty());
            }while(Payments::count() < 2000);
        }
    }
}
