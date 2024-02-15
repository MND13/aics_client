<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <title>COE</title>
    <style>
        .text-center {
            text-align: center
        }

        h1 {
            font-size: 22pt;

        }

        body,
        th {
            font-size: 10pt;
            font-family: Arial, sans-serif
        }

        h2 {
            font-size: 14pt
        }

        h1>small,
        h2>small {
            font-size: 10pt;
            font-weight: normal;
        }

        .sig {
            width: 90%;
            margin: 0 auto;
            border-bottom: solid 1px #000;
            text-transform: uppercase;
            vertical-align: bottom;
        }

        .table {
            width: 100%;
        }

        .underline {
            text-decoration: underline;
            text-transform: uppercase;
        }

        .dotted-table {
            padding-bottom: 10px;
            border-bottom: dashed 1px #000;
        }

        @page {
            size: 8.27in 12in;
            margin: 0.5in;
            opacity: 0.75;
            padding: 0 !important;
            font-size: 10pt;

            font-family: Arial, sans-serif
        }

        .table-bordered {
            border: solid 1px;
        }

        .col-md-6 {
            width: 50%;
            display: inline-block
        }


        table.table-bordered td,
        {
        border: solid 1px #000
        }

        .footer-ds {
            font-size: 9pt;
            text-align: center;
            font-family: 'Times New Roman', Times, serif
        }

        .signatories .sig {
            font-size: 1vw;
            vertical-align: bottom
        }

        ul {
            list-style: none;
            padding: 5px;
            margin: 0;
        }

        p {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .sub-label {
            font-size: 7pt;
            text-align: center;
        }

        .sub-label td.underlined {
            border-top: solid 1px #000;
        }

        input[type="checkbox"]{
            margin-bottom: -5;
            padding: 0;
        }

        input[type="radio"]{
            margin-bottom: -3;
            padding: 0;
        }

        .chk {
            display: inline-flex;
            vertical-align: middle;
        }

        .boxed {
            border: solid 1px #000;
            padding: 0px 5px;
        }
    </style>
</head>

<body>

    <table style="width:100%">
        <tr>
            <td><img src='{{ public_path('images/DSWD-DVO-LOGO.png') }}' style="width: 250px;">
            </td>
            <td style="text-align: right;">
                <h2 style="color: darkblue">CRISIS INTERVENTION SECTION <br>
                    <small style="font-size:12pt;">
                        Cor. Suazo St. R. Magsaysay Ave. Davao City
                    </small>
                </h2>
            </td>
        </tr>
    </table>


    <div class="text-center">

        <h2> CERTIFICATE OF ELIGIBILITY <br>
            <small>
                @if ($assistance['assessment']['mode_of_assistance'] == 'CAV')
                    (Financial Assistance)
                @else
                    (Guarantee Letter)
                @endif
            </small>
        </h2>
    </div>

    <table class="table" style="table-layout:fixed; font-size:9pt">
        <tr>
            <td  style="width:15%" >
                <table class="table"  style="table-layout: fixed;"  cellspacing=0 cellpadding=0>
                    <td style="width:25%" ><b> QN: </b></td>
                    <td style="border:solid 1px"></td>
                </table> 
            </td>
            <td style="width:50%">
                <table class="table  text-center" style="table-layout: fixed;"  cellspacing=0 cellpadding=0>
                    <td style="width:15%" ><b> PCN: </b></td>
                    <td style="border:solid 1px"></td>
                    <td style="border:solid 1px"></td>
                    <td style="border:solid 1px"></td>
                    <td style="border:solid 1px"></td>
                    <td style="border:solid 1px"></td>
                    <td style="border:solid 1px"></td>
                    <td style="border:solid 1px"></td>
                    <td style="border:solid 1px"></td>
                    <td style="border:solid 1px"></td>
                    <td style="border:solid 1px"></td>
                    <td style="border:solid 1px"></td>
                    <td style="border:solid 1px"></td>
                    <td style="border:solid 1px"></td>
                    <td style="border:solid 1px"></td>
             
                </table> 
            </td>
            <td>
                <table class="table  text-center" cellspacing=0 cellpadding=0>
                    <td><b> Date: </b></td>
                    <td style="border:solid 1px">{{ date('M', strtotime($assistance['schedule'])) }}</td>
                    <td style="border:solid 1px">{{ date('d', strtotime($assistance['schedule'])) }}</td>
                    <td style="border:solid 1px">{{ date('Y', strtotime($assistance['schedule'])) }}</td>
                </table>
            </div>

            </td>
        </tr>
        <tr>
           
            <td colspan="3">
                <input type="checkbox">New
                <input type="checkbox">Returning
                <input type="radio">On-site
                <input type="checkbox">Walk-in
                <input type="checkbox">Referral
                <input type="radio">Off-site
         
            </td>
            
        </tr>
    </table>
<br>
    <table class="table " cell-padding=0 cellspacing=0>
        <tr>
            <td>This is to certify that,</td>
            <td class="text-center">
                {{ $client['first_name'] . ' ' . $client['middle_name'] . ' ' . $client['last_name'] . ' ' . $client['ext_name'] }}
            </td>
            <td>,
                <div class="chk"> <input type="checkbox" @if ($client['gender'] == 'Lalake') checked="true" @endif>Male
                </div>
                <div class="chk"><input type="checkbox"
                        @if ($client['gender'] == 'Babae') checked="true" @endif>Female</label>
            </td>
            <td class="text-center">{{ $age }} year/s</td>
        </tr>
        <tr class="sub-label">
            <td></td>
            <td class="underlined"><b> Kumpletong Pangalan</b> <i>(First name, Middle name, Last name)</i></td>
            <td> <b> Kasarian</b> (Sex)</td>
            <td class="underlined"><b>Edad </b>(Age)</td>
        </tr>
    </table>

    <table class="table">
        <tr>
            <td>and presently residing at</td>
            <td>
                {{ $client['street_number'] . ', BRGY ' . $client['psgc']['brgy_name'] . ', ' . $client['psgc']['city_name'] . ', ' . $client['psgc']['province_name'] }}

            </td>
        </tr>
        <tr class="sub-label">
            <td> </td>
            <td class="underlined"><b> Kumpletong Tirahan</b> <i>(Complete Address)</i></td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td colspan="2">
                has been found eligible for the assessment and the validation conducted for his/herself or through the
                representation of his/her
            </td>
        </tr>
        <tr>
            <td>{{ $relationship }}</td>
            <td>
                @if ($bene)
                    {{ $bene['first_name'] . ' ' . $bene['middle_name'] . ' ' . $bene['last_name'] . ' ' . $bene['ext_name'] }}</span>
                @endif
            </td>
        </tr>
        <tr class="sub-label">
            <td class="underlined"><b>Relasyon ng Kinatawan sa Benepisyaryo </b><i> (Relationship of the Representative
                    to the Beneficiary)</i></td>
            <td class="underlined"><b>Buong Pangalan ng Benepisyaryo</b> <i>(Name of the Beneficiary)</i></td>
        </tr>
    </table>
    <br>
    <table class="table" style=" border:solid #000 1px;" cellpadding=0 cellspacing=0>
        <thead>
            <tr>
                <td class="text-center" style=" background:#dedede; font-size:8pt; border:solid #000 1px;"
                    colspan="4">
                    <b>Records of the case such as the following are confidentially filed at the Crisis Intervention
                        Division (CID)</b>
                </td>
            </tr>
        </thead>
        <tbody>

            @foreach ($record_options as $index => $record_option)
                @if ($index % 4 == 0)
                    </tr>
                    <tr>
                @endif
                <td>
                    @if ($index != 8)
                        <input type="checkbox" name="" id=""
                            @if (in_array($record_option, $records)) checked="true" @endif>
                    @endif

                    {{ $record_option }}

                </td>
            @endforeach
            <td><span style="text-decoration: underline;"> {{ $records_others }} </span></td>
            </tr>








        </tbody>
    </table>

    <p class="text-center" style="line-height:2em">
        The Client is hereby recommended to receive <span class="underline">{{ $assistance_type }} </span> <br>
        in the amount of <span class="underline" style="text-transform:uppercase">{{ $amount_in_words }} PESOS
            ONLY</span>,
        Php <span class="underline"> {{ number_format($assistance['assessment']['amount'], 2) }} </span>
        CHARGABLE AGAINST: PSP <span class="underline">
            {{ date('Y', strtotime($assistance['assessment']['created_at'])) }} </span>

    </p>
    <br>

    <table class="table text-center">
        <tbody>
            <tr>
                <td style="text-align: left;"><b>Conforme:</b></td>
                <td style="text-align: left;"><b>Prepared by:</b></td>
                <td style="text-align: left;"><b>Approved by:</b></td>
            </tr>
            <tr>
                <td>
                    <div class="sig"><br>
                        {{ $client['first_name'] . ' ' . $client['middle_name'] . ' ' . $client['last_name'] . ' ' . $client['ext_name'] }}
                    </div>
                </td>
                <td>
                    <div class="sig"><br>
                        {{ $assistance['assessment']['interviewed_by']['first_name'] .
                            ' ' .
                            $assistance['assessment']['interviewed_by']['middle_name'] .
                            ' ' .
                            $assistance['assessment']['interviewed_by']['last_name'] .
                            ' ' .
                            $assistance['assessment']['interviewed_by']['ext_name'] }}
                    </div>
                </td>
                <td>
                    <div class="sig"><br>
                        {{ $assistance['assessment']['signatory']['name'] }}


                    </div>
                </td>
            </tr>
            <tr>
                <td><b>Beneficiary/Representative</b><br><i style="font-size: 7pt;">Signature Over Printed Name</i></td>
                <td><b>Social Worker</b><br><i style="font-size: 7pt;">Signature Over Printed Name</i></td>
                <td><b>Approving Authority</b><br><i style="font-size: 7pt;">Signature Over Printed Name</i></td>
            </tr>
        </tbody>
    </table>

    @if ($assistance['assessment']['amount'] >= 50000)
        <br><br>
        <table class="table text-center " style="table-layout: fixed">
            <tr>
                <td></td>
                <td style="text-align:left;">Approved by:</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <div class="sig"><br>GEMMA D. DELA CRUZ</div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><b>SWO V/PSD CHIEF</b><br>Signature Over Printed Name</td>
                <td></td>
            </tr>
        </table>
    @endif
    <br><br>

    @if ($assistance['assessment']['mode_of_assistance'] == 'CAV')
        <div class="text-center" style="width: 100%; background: black; color: #fff;">
            <b> Acknowledgement Receipt</b>
        </div>
        <br>
        <div style="text-align:right">
            Date: {{ date('M d, Y', strtotime($assistance['assessment']['created_at'])) }}
        </div>



        Financial Assistance:

        <span class="underline" style="text-transform:uppercase">
            {{ $amount_in_words }} PESOS ONLY</span>,
        PHP <span class="underline"> {{ number_format($assistance['assessment']['amount'], '2') }} </span>

        <br>

        <ul>
            <li>{{ $assistance['aics_type']['name'] }}</li>
        </ul>
        <br>

        <table class="table  text-center">
            <tbody>
                <tr>
                    <td style="text-align: left;">Accepted by:<br></td>
                    <td style="text-align: left;">Paid by:<br></td>
                    <td style="text-align: left;">Witnessed by:<br></td>
                </tr>
                <tr>
                    <td>
                        <div class="sig">
                            {{ $client['first_name'] . ' ' . $client['middle_name'] . ' ' . $client['last_name'] . ' ' . $client['ext_name'] }}
                        </div>
                    </td>
                    <td>
                        <div class="sig">{{ $assistance['assessment']['sdo'] }}</div>
                    </td>
                    <td>
                        <div class="sig">
                            {{ $assistance['assessment']['interviewed_by']['first_name'] .
                                ' ' .
                                $assistance['assessment']['interviewed_by']['middle_name'] .
                                ' ' .
                                $assistance['assessment']['interviewed_by']['last_name'] .
                                ' ' .
                                $assistance['assessment']['interviewed_by']['ext_name'] }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><b>Beneficiary/Representative</b><br>Signature Over Printed Name</td>
                    <td><b>Special Disbursing Officer</b><br>Signature Over Printed Name</td>
                    <td><b>Social Worker</b><br>Signature Over Printed Name</td>
                </tr>
                <tr>
                    <td colspan="3"><br></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:right; border-top:solid 1px #000;"><small>*E.O 163 series
                            2022</small></td>
                </tr>
            </tbody>
        </table>
    @else
        CHARGABLE AGAINST : AICS FUND <br>
        CLIENT CATEGORY : {{ $assistance['assessment']['category']['category'] }} <br>
        PAYABLE TO : {{ $assistance['assessment']['provider']['company_name'] }}<br>
        MODE OF ADMISSION: {{ $assistance['mode_of_admission'] }}<br>
        <br>
        <b> NOTE:
            CASE STUDY REPORT
            ON FILE</b>
    @endif


</body>

</html>
