<?php

namespace App\Exports;

use App\Models\AicsAssistance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;


class AcisCrimsExport implements FromCollection,  WithHeadings, WithMapping
{
    private $start_date;
    private $end_date;

    public function  __construct($date_option, $custom_date = array())
    {

        switch ($date_option) {
            case 'Custom Range':
                $this->start_date = $custom_date[0];
                $this->end_date = $custom_date[1];
                break;
            case '3 Months Ago':
                $this->start_date = Carbon::now()->subMonths(3)->format('Y-m-d');
                $this->end_date = Carbon::now()->format('Y-m-d');
                break;
            case 'This Month':
                $this->start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'This Week':
                $this->start_date = Carbon::now()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                $this->end_date = Carbon::now()->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
                break;
            case 'Today':
            default:
                $this->start_date = Carbon::now()->format('Y-m-d');
                break;
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {


        $collection = AicsAssistance::with(
            "aics_type:id,name",
            "aics_client:id,full_name,first_name,last_name,middle_name,ext_name,psgc_id,mobile_number,birth_date,gender,street_number,mobile_number",
            "aics_client.psgc",
            "assessment",
            "aics_beneficiary",
            "assessment.provider:id,company_name",

        )->with("assessment.fund_sources", function ($q) {
            $q->where("remarks", "!=", "CANCELLED")
                ->where("remarks", "!=", "REVERSAL")
                ->orWhereNull("remarks");
            return $q;
        })
            ->where("status", "=", "Served")
            ->orderBy("status_date");


        if ($this->end_date) {
            $collection->whereBetween("status_date", [$this->start_date, $this->end_date]);
        } else {
            $collection->where("status_date", "=", $this->start_date);
        }
        return $collection->get()->map(function ($item, $key) {
            $item->key = $key;
            return $item;
        });
    }

    public function headings(): array
    {
        return [
            'Entered',
            'Entered By',
            'Client No',
            'Date Accomplished',
            'Region',
            'Province',
            'City/Municipality',
            'Barangay',
            'District',
            'LastName',
            'FirstName',
            'MiddleName',
            'ExtraName',
            'Sex',
            'CivilStatus',
            'DOB',
            'Age',
            'ModeOfAdmission',
            'Type of Assistance1',
            'Amount1',
            'Source of Fund1',
            'Type of Assistance2',
            'Amount2',
            'Source of Fund2',
            'Type of Assistance3',
            'Amount3',
            'Source of Fund3',
            'Type of Assistance4',
            'Amount4',
            'Source of Fund4',
            'ClientCategory',
            'Mode of Assistance',
            'SERVICE PROVIDER',
            'CHARGING 1',
            'CHARGING 2',
            'CHARGING 3',
            'CHARGING 4',
            'CHARGING 5',
            'CHARGING 6',
            'CHARGING 7',
            'CHARGING 9',
            'CHARGING 10',
            'B.LAST NAME',
            'B.FIRST NAME3',
            'B.MIDDLE NAME',

        ];
    }

    public function map($assistance): array
    {
        $fs = array();
        $total =  0;

        if ($assistance->assessment->fund_sources) {
            foreach ($assistance->assessment->fund_sources as $key => $value) {
                $fs[] = $value->fund_source->name . "=" . $value->amount;
            }
            $total = $assistance->assessment->fund_sources->sum("amount");
        }

       
        $entered_by = "";
        if ($assistance->assessment->interviewed_by) {
            $entered_by =  $assistance->assessment->interviewed_by->first_name  ? $assistance->assessment->interviewed_by->first_name . " " : "";
            $entered_by .= $assistance->assessment->interviewed_by->middle_name ? $assistance->assessment->interviewed_by->middle_name  . " " : "";
            $entered_by .= $assistance->assessment->interviewed_by->last_name   ? $assistance->assessment->interviewed_by->last_name . " " : "";
            $entered_by .= $assistance->assessment->interviewed_by->ext_name    ?  $assistance->assessment->interviewed_by->ext_name . " " : "";
        }
        return [
            $assistance->created_at->format("m/d/Y h:i:s"),
            $entered_by,
            "",
            $assistance->status_date,
            $assistance->aics_client->psgc->region_name . "/" . $assistance->aics_client->psgc->region_psgc,
            $assistance->aics_client->psgc->province_name . "/" . $assistance->aics_client->psgc->province_psgc,
            $assistance->aics_client->psgc->city_name . "/" . $assistance->aics_client->psgc->city_name_psgc,
            $assistance->aics_client->psgc->brgy_name . "/" . $assistance->aics_client->psgc->brgy_psgc,
            "",
            $assistance->aics_client->last_name,
            $assistance->aics_client->first_name,
            $assistance->aics_client->middle_name ? $assistance->aics_client->middle_name : "",
            $assistance->aics_client->ext_name ? $assistance->aics_client->ext_name : "",
            $assistance->aics_client->gender == "Lalake" ? "Male" : "Female",
            $assistance->civil_status,
            Carbon::parse($assistance->aics_client->birth_date)->format("m/d/Y"),
            $assistance->age,
            $assistance->assessment->mode_of_admission,
            $assistance->aics_type->name,
            $total,
            "CURRENT FUND",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            $assistance->assessment->category ? $assistance->assessment->category->category : "",
            $assistance->assessment->mode_of_assistance,
            $assistance->assessment->provider ? $assistance->assessment->provider->company_name : "", #service provider
            implode(",", $fs),
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            $assistance->aics_beneficiary ? $assistance->aics_beneficiary->last_name : "",
            $assistance->aics_beneficiary ? $assistance->aics_beneficiary->first_name : "",
            $assistance->aics_beneficiary ? $assistance->aics_beneficiary->middle_name : "",


        ];
    }
}
