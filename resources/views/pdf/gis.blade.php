<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> -->
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <title>GIS</title>
    <style>
        @page {
            size: 8.27in 11.69in;
            /* size: 8.5in 13in; */
            font-size: 8pt;
            margin: 5%;
            opacity: 0.75;
            padding: 0 !important;
            /* background: url("{{ public_path('images/watermark.png') }}") no-repeat 0 0; */
        }

        .data-textbox {
            position: absolute;
            /*font-weight: bold;
            border: 1px solid red;*/
            line-height: 0.8;
            color: blue;
        }

        #aics-type-name {
            left: 160pt;
            top: 138pt;
            width: 370pt;
        }


        #aics-beneficiary-last-name {
            left: 5pt;
            top: 181pt;
            width: 165pt;
            text-align: center;
        }

        #aics-beneficiary-first-name {
            left: 160pt;
            top: 181pt;
            width: 165pt;
            text-align: center;
        }

        #aics-beneficiary-middle-name {
            left: 315pt;
            top: 181pt;
            width: 165pt;
            text-align: center;
        }

        #aics-beneficiary-ext-name {
            left: 470pt;
            top: 181pt;
            width: 60pt;
            text-align: center;
        }


        #aics-beneficiary-street-number {
            left: 5pt;
            top: 207pt;
            width: 145pt;
            text-align: center;
        }

        #aics-beneficiary-psgc-brgy {
            left: 140pt;
            top: 207pt;
            width: 110pt;
            text-align: center;
        }

        #aics-beneficiary-psgc-city {
            left: 240pt;
            top: 207pt;
            width: 120pt;
            text-align: center;
        }

        #aics-beneficiary-psgc-province {
            left: 350pt;
            top: 207pt;
            width: 130pt;
            text-align: center;
        }

        #aics-beneficiary-psgc-region {
            left: 470pt;
            top: 207pt;
            width: 60pt;
            text-align: center;
        }


        #aics-beneficiary-mobile-number {
            left: 5pt;
            top: 238pt;
            width: 135pt;
            text-align: center;
        }

        #aics-beneficiary-birth-month {
            left: 140pt;
            top: 238pt;
            width: 25pt;
            text-align: center;
        }

        #aics-beneficiary-birth-day {
            left: 165pt;
            top: 238pt;
            width: 25pt;
            text-align: center;
        }

        #aics-beneficiary-birth-year {
            left: 190pt;
            top: 238pt;
            width: 40pt;
            text-align: center;
        }

        #aics-beneficiary-age {
            left: 230pt;
            top: 238pt;
            width: 40pt;
            text-align: center;
        }

        #aics-beneficiary-gender {
            left: 270pt;
            top: 238pt;
            width: 60pt;
            text-align: center;
        }

        #aics-beneficiary-occupation {
            left: 330pt;
            top: 238pt;
            width: 105pt;
            text-align: center;
        }

        #aics-beneficiary-monthly-salary {
            left: 430pt;
            top: 238pt;
            width: 100pt;
            text-align: center;
        }


        #aics-client-last-name {
            left: 5pt;
            top: 288pt;
            width: 165pt;
            text-align: center;
        }

        #aics-client-first-name {
            left: 160pt;
            top: 288pt;
            width: 165pt;
            text-align: center;
        }

        #aics-client-middle-name {
            left: 315pt;
            top: 288pt;
            width: 165pt;
            text-align: center;
        }

        #aics-client-ext-name {
            left: 470pt;
            top: 288pt;
            width: 60pt;
            text-align: center;
        }


        #aics-client-street-number {
            left: 5pt;
            top: 314pt;
            width: 145pt;
            text-align: center;
        }

        #aics-client-psgc-brgy {
            left: 140pt;
            top: 314pt;
            width: 110pt;
            text-align: center;
        }

        #aics-client-psgc-city {
            left: 240pt;
            top: 314pt;
            width: 120pt;
            text-align: center;
        }

        #aics-client-psgc-province {
            left: 350pt;
            top: 314pt;
            width: 130pt;
            text-align: center;
        }

        #aics-client-psgc-region {
            left: 470pt;
            top: 314pt;
            width: 60pt;
            text-align: center;
        }


        #aics-client-mobile-number {
            left: 5pt;
            top: 350pt;
            width: 130pt;
            text-align: center;
        }

        #aics-client-birth-month {
            left: 135pt;
            top: 350pt;
            width: 25pt;
            text-align: center;
        }

        #aics-client-birth-day {
            left: 160pt;
            top: 350pt;
            width: 25pt;
            text-align: center;
        }

        #aics-client-birth-year {
            left: 185pt;
            top: 350pt;
            width: 40pt;
            text-align: center;
        }

        #aics-client-rel-client {
            left: 225pt;
            top: 350pt;
            width: 180pt;
            text-align: center;
        }

        #assessment-category {
            left: 5pt;
            top: 412pt;
            width: 100pt;
            text-align: center;
        }

        #assessment-subcategory {
            left: 100pt;
            top: 412pt;
            width: 120pt;
            text-align: center;
        }

        #assessment-assesment {
            left: 215pt;
            top: 412pt;
            width: 310pt;
            text-align: left;
            line-height: 8pt;
            line-height: 8pt;
        }

        #assessment-purpose {
            left: 5pt;
            top: 640pt;
            width: 310pt;
            text-align: center;
            line-height: 8pt;
        }

        #assessment-amount {
            left: 316pt;
            top: 640pt;
            width: 80pt;
            text-align: center;
            line-height: 8pt;
        }

        #assessment-mode_of_assistance {
            left: 396pt;
            top: 640pt;
            width: 30pt;
            text-align: center;
            line-height: 8pt;
        }

        #assessment-fund_sources {
            left: 420pt;
            top: 638pt;
            width: 100pt;
            text-align: center;
            line-height: 8pt;
        }

        #assessment-bene-sign {
            left: 5pt;
            top: 730pt;
            width: 190pt;
            text-align: center;
        }

        #assessment-interviewed_by {
            left: 270pt;
            top: 720pt;
            width: 110pt;
            text-align: center;
        }

        #assessment-interviewed_by {
            left: 270pt;
            top: 720pt;
            width: 110pt;
            text-align: center;
        }

        #assessment-signatory {
            left: 400pt;
            top: 720pt;
            width: 120pt;
            text-align: center;
        }

        #created-at {
            left: 432pt;
            top: 87pt;
            width: 100pt;
            text-align: center;
        }
    </style>
</head>

<body>

    <div style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; text-align: center;z-index: -1000; ">
        <img src="{{ public_path('images/gis-min-2.jpg') }}" style="width: 95%;">
    </div>


    <div id="aics-type-name" class="data-textbox">{{ strtoupper($aics_type['name']) }}</div>
    <div id="created-at" class="data-textbox">{{ date('M d, Y H:i A', strtotime($created_at)) }}</div>



    @if ($aics_beneficiary)
        <!-- BENE & REPRESENTATIVE  -->

        <div id="aics-beneficiary-last-name" class="data-textbox">{{ strtoupper($aics_beneficiary['last_name']) }}</div>
        <div id="aics-beneficiary-first-name" class="data-textbox">{{ strtoupper($aics_beneficiary['first_name']) }}
        </div>
        <div id="aics-beneficiary-middle-name" class="data-textbox">{{ strtoupper($aics_beneficiary['middle_name']) }}
        </div>
        <div id="aics-beneficiary-ext-name" class="data-textbox">{{ strtoupper($aics_beneficiary['ext_name']) }}</div>

        <div id="aics-beneficiary-street-number" class="data-textbox">
            {{ strtoupper($aics_beneficiary['street_number']) }}
        </div>
        <div id="aics-beneficiary-psgc-brgy" class="data-textbox">
            {{ strtoupper($aics_beneficiary['psgc']['brgy_name']) }}
        </div>
        <div id="aics-beneficiary-psgc-city" class="data-textbox">
            {{ strtoupper($aics_beneficiary['psgc']['city_name']) }}
        </div>
        <div id="aics-beneficiary-psgc-province" class="data-textbox">
            {{ strtoupper($aics_beneficiary['psgc']['province_name']) }}</div>
        <div id="aics-beneficiary-psgc-region" class="data-textbox">
            {{ strtoupper($aics_beneficiary['psgc']['region_name_short']) }}</div>

        <div id="aics-beneficiary-mobile-number" class="data-textbox">
            {{ strtoupper($aics_beneficiary['mobile_number']) }}
        </div>
        <div id="aics-beneficiary-birth-month" class="data-textbox">
            {{ date('d', strtotime($aics_beneficiary['birth_date'])) }}</div>
        <div id="aics-beneficiary-birth-day" class="data-textbox">
            {{ date('m', strtotime($aics_beneficiary['birth_date'])) }}</div>
        <div id="aics-beneficiary-birth-year" class="data-textbox">
            {{ date('Y', strtotime($aics_beneficiary['birth_date'])) }}</div>

        <div id="aics-beneficiary-age" class="data-textbox">{{ $aics_beneficiary['age'] }}</div>
        <div id="aics-beneficiary-gender" class="data-textbox">{{ strtoupper($aics_beneficiary['gender']) }}</div>
        <div id="aics-beneficiary-occupation" class="data-textbox">{{ strtoupper($aics_beneficiary['occupation']) }}
        </div>
        <div id="aics-beneficiary-monthly-salary" class="data-textbox">
            {{ strtoupper($monthly_salary) }}</div>

        <!-- REPRESENTATIVE  -->

        <div id="aics-client-last-name" class="data-textbox">{{ strtoupper($aics_client['last_name']) }}</div>
        <div id="aics-client-first-name" class="data-textbox">{{ strtoupper($aics_client['first_name']) }}</div>
        <div id="aics-client-middle-name" class="data-textbox">{{ strtoupper($aics_client['middle_name']) }}</div>
        <div id="aics-client-ext-name" class="data-textbox">{{ strtoupper($aics_client['ext_name']) }}</div>

        <div id="aics-client-street-number" class="data-textbox">{{ strtoupper($aics_client['street_number']) }}</div>
        <div id="aics-client-psgc-brgy" class="data-textbox">{{ strtoupper($aics_client['psgc']['brgy_name']) }}</div>
        <div id="aics-client-psgc-city" class="data-textbox">{{ strtoupper($aics_client['psgc']['city_name']) }}</div>
        <div id="aics-client-psgc-province" class="data-textbox">
            {{ strtoupper($aics_client['psgc']['province_name']) }}</div>
        <div id="aics-client-psgc-region" class="data-textbox">
            {{ strtoupper($aics_client['psgc']['region_name_short']) }}
        </div>

        <div id="aics-client-mobile-number" class="data-textbox">{{ strtoupper($aics_client['mobile_number']) }}</div>
        <div id="aics-client-birth-month" class="data-textbox">{{ date('d', strtotime($aics_client['birth_date'])) }}
        </div>
        <div id="aics-client-birth-day" class="data-textbox">{{ date('m', strtotime($aics_client['birth_date'])) }}
        </div>
        <div id="aics-client-birth-year" class="data-textbox">{{ date('Y', strtotime($aics_client['birth_date'])) }}
        </div>
        <div id="aics-client-rel-client" class="data-textbox">{{ strtoupper($rel_beneficiary) }}</div>

        <div id="assessment-bene-sign" class="data-textbox">
            {{ strtoupper($aics_client['first_name']) }}
            {{ strtoupper($aics_client['middle_name']) }}
            {{ strtoupper($aics_client['last_name']) }}
            {{ strtoupper($aics_client['ext_name']) }}
        </div>

        <!--END BENE & REPRESENTATIVE -->
    @else
        <!-- BENE ONLY -->

        <div id="aics-beneficiary-last-name" class="data-textbox">{{ strtoupper($aics_client['last_name']) }}</div>
        <div id="aics-beneficiary-first-name" class="data-textbox">{{ strtoupper($aics_client['first_name']) }}</div>
        <div id="aics-beneficiary-middle-name" class="data-textbox">{{ strtoupper($aics_client['middle_name']) }}</div>
        <div id="aics-beneficiary-ext-name" class="data-textbox">{{ strtoupper($aics_client['ext_name']) }}</div>

        <div id="aics-beneficiary-street-number" class="data-textbox">{{ strtoupper($aics_client['street_number']) }}
        </div>
        <div id="aics-beneficiary-psgc-brgy" class="data-textbox">{{ strtoupper($aics_client['psgc']['brgy_name']) }}
        </div>
        <div id="aics-beneficiary-psgc-city" class="data-textbox">{{ strtoupper($aics_client['psgc']['city_name']) }}
        </div>
        <div id="aics-beneficiary-psgc-province" class="data-textbox">
            {{ strtoupper($aics_client['psgc']['province_name']) }}</div>
        <div id="aics-beneficiary-psgc-region" class="data-textbox">
            {{ strtoupper($aics_client['psgc']['region_name_short']) }}</div>

        <div id="aics-beneficiary-mobile-number" class="data-textbox">{{ strtoupper($aics_client['mobile_number']) }}
        </div>
        <div id="aics-beneficiary-birth-month" class="data-textbox">
            {{ date('d', strtotime($aics_client['birth_date'])) }}
        </div>
        <div id="aics-beneficiary-birth-day" class="data-textbox">
            {{ date('m', strtotime($aics_client['birth_date'])) }}
        </div>
        <div id="aics-beneficiary-birth-year" class="data-textbox">
            {{ date('Y', strtotime($aics_client['birth_date'])) }}
        </div>

        <div id="aics-beneficiary-age" class="data-textbox">{{ $age }}</div>
        <div id="aics-beneficiary-gender" class="data-textbox">{{ strtoupper($aics_client['gender']) }}</div>
        <div id="aics-beneficiary-occupation" class="data-textbox">{{ strtoupper($occupation) }}
        </div>
        <div id="aics-beneficiary-monthly-salary" class="data-textbox">
            {{ strtoupper($monthly_salary) }}</div>



        <div id="assessment-bene-sign" class="data-textbox">
            {{ strtoupper($aics_client['first_name']) }}
            {{ strtoupper($aics_client['middle_name']) }}
            {{ strtoupper($aics_client['last_name']) }}
            {{ strtoupper($aics_client['ext_name']) }}
        </div>

        <!--END BENE ONLY -->
    @endif

    <div id="assessment-category" class="data-textbox">{{ strtoupper($assessment['category']['category']) }}</div>
    @if (isset($assessment['subcategory']))
        <div id="assessment-subcategory" class="data-textbox">
            {{ strtoupper($assessment['subcategory']['subcategory']) }}
        </div>
    @endif

    <div id="assessment-assesment" class="data-textbox">{{ strtoupper($assessment['assessment']) }}</div>
    <div id="assessment-purpose" class="data-textbox">{{ strtoupper($assessment['purpose']) }}</div>
    <div id="assessment-amount" class="data-textbox">{{ strtoupper($assessment['amount']) }}</div>
    <div id="assessment-mode_of_assistance" class="data-textbox">{{ strtoupper($assessment['mode_of_assistance']) }}
    </div>
    <div id="assessment-fund_sources" class="data-textbox">
        @if (isset($assessment['fund_sources']))
            <table style="table-layout: fixed; width:100%; font-size:6pt; line-height:6pt; " cellpadding=0 cellspacing=0>
                @foreach ($assessment['fund_sources'] as $fs)
                    <tr>
                        <td style=" text-align:right;">{{ $fs['fund_source']['name'] }} =
                            {{ number_format($fs['amount'], 2) }}</td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>

    <div id="assessment-interviewed_by" class="data-textbox">
        {{ strtoupper($assessment['interviewed_by']['first_name']) }}
        {{ strtoupper($assessment['interviewed_by']['middle_name']) }}
        {{ strtoupper($assessment['interviewed_by']['last_name']) }}
        {{ strtoupper($assessment['interviewed_by']['ext_name']) }}
    </div>

    <div id="assessment-signatory" class="data-textbox">
        {{ strtoupper($assessment['signatory']['name']) }}
    </div>


    <div>

    </div>
</body>

</html>
