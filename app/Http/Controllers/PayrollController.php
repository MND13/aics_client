<?php

namespace App\Http\Controllers;

use App\Exports\ClientExport;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Models\Payroll;
use Exception;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Payroll::with("psgc")->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(request $request)
    {
        try {
            $p = new Payroll;
            $p->fill($request->toArray());
            $res = $p->save();
            if ($res) {
                return ["Message" => "Saved"];
            } else {
                return ["Message" => "Failed"];
            }
        } catch (\Throwable $th) {
            return ["Message" => $th];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payroll = Payroll::with("clients", "psgc")->find($id);

        if ($payroll) {
            return $payroll;
        } else {
            return ["Message" => "Not Found"];
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function edit(Payroll $payroll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payroll $payroll)
    {
        $payroll = Payroll::find($request->id);
        if ($payroll) {
            try {

                $payroll->fill($request->toArray());
                $payroll->save();
                return ["Message" => "saved"];
            } catch (Exception $e) {
                return ["Message" => $e];
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function destroy(request $request)
    {
        try {
            $payroll = Payroll::find($request->id);
            if ($payroll) {
                $payroll->delete();
                return ["Message" => "Deleted"];
            } else {
                return ["Message" => "Not Found"];
            }
        } catch (Exception $e) {
            return ["Message" => $e];
        }
    }

    public function print($id)
    {
        $payroll = Payroll::with("psgc","clients")->find($id);
        if ($payroll) {
            return view('pdf.payroll', ["data" => $payroll,  "grand_total"=> ($payroll->clients()->count() * $payroll->amount)]);

            /*$pdf = Pdf::loadView('pdf.payroll', 
                ["data" => $payroll,  
                "grand_total"=> ($payroll->clients()->count() * $payroll->amount) 
            ]);
            return $pdf->setPaper('a4', 'landscape')->stream('payroll.pdf');*/
        };
        
        /*$payroll = Payroll::with("psgc","clients")->find($id);
        if ($payroll) {
            #return view('pdf.payroll', ["data" => $payroll]);

            $pdf = Pdf::loadView('pdf.payroll', 
                ["data" => $payroll,  
                "grand_total"=> ($payroll->clients()->count() * $payroll->amount) 
            ]);
            return $pdf->setPaper('a4', 'landscape')->stream('payroll.pdf');
        };*/
    }

    public function print_footer($id)
    {
        $payroll = Payroll::with("psgc")->find($id);
        if ($payroll) {
            #return view('pdf.payroll', ["data" => $payroll]);


            $pdf = Pdf::loadView('pdf.payroll_footer', ["data" => $payroll]);
            return $pdf->setPaper('a4', 'landscape')->stream('payroll.pdf');
        };
    }
    
    public function printv2($id)
    {
        $payroll_clients = Payroll::find($id)->clients()->paginate(10);
        $payroll = Payroll::with("psgc")->find($id);
       
       
        if ($payroll) {

            abort_unless($payroll_clients->count(), 204);


            $pdf = Pdf::loadView('pdf.payrollv2', 
                ["clients" => $payroll_clients,
                "payroll"=>$payroll,
                "grand_total"=>$payroll_clients->total() * $payroll->amount,
            ]);
            return $pdf->setPaper('a4', 'landscape')->stream('payroll.pdf');

            //return view('pdf.payrollv2', ["data" => $payroll]);
        }
    }

    public function export($payroll_id) 
    {
        $payroll = Payroll::findOrFail($payroll_id);
        $filename = "";
        $filename .= Str::slug($payroll->title)."-";
        $filename .= Str::slug($payroll->charging)."-";
        $filename .= Str::slug($payroll->sdo)."-";
        $filename .= Str::slug($payroll->amount)."-";
        $filename .= Str::slug(Carbon::now());
        $export_file_name = "$filename.xlsx";
        Excel::store(new ClientExport($payroll), "public/$export_file_name", 'local');
        return [
            "file" => url(Storage::url("public/$export_file_name")),
        ];
    }
}
