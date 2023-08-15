<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SMSJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
         /*switch ($request->status) {
            case 'Rejected':
                $msg = $request->status. " " . $request->remarks;
                break;
            case 'Served':
                $msg = $request->status;
            break;
            default:
            #Ayaw usaba ang spacing kai madaot pg view sa phone;
            $msg = "Maayong Adlaw! Kani na mensahe gikan sa DSWD Davao Region Office, nadawat na namo ang imohang aplikasyon sa MEDICINE ASSISTANCE. Sa karon, gina proceso na inyohang dokyumento. Mamalihog mi na magpa-abot ug tawag gikan sa social workers sa kani na schedule: " . $request->schedule. " Daghang Salamat!";
            if($request->remarks && $request->remarks !="" ) $msg .=" Pahabol na Mensahe: " .  $request->remarks ;
            break;
        }*/
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::get('http://34.80.139.96/api/v2/SendSMS?ApiKey=LWtHZKzgbIh1sNQUPInRyqDFsj8W0K+8YCeSIdN08zA=&ClientId=3b3f49c9-b8e2-4558-9ed2-d618d7743fd5&SenderId=DSWD11AICS&Message='. $msg .'&MobileNumbers=63'.substr($request->aics_client->mobile_number, 1));
      
    }
}
