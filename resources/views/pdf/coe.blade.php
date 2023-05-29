<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <title>Document</title>
    <style>
        .text-center {
            text-align: center
        }

        h1 {
            font-size: 22pt;

        }

        body {
            font-size: 10pt;
            font-family: Arial, sans-serif
        }

        h1>small,
        h2>small {
            font-size: 13pt;
            font-weight: normal;
        }

        .sig {
            width: 90%;
            margin: 0 auto;
            border-bottom: solid 1px #000;
        }

        .table {
            width: 100%;
        }

        .underline {
            text-decoration: underline;
            text-transform: uppercase;
        }

        @page {
            size: 8.27in 11.69in;
            margin: 5%;
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

        .sig {
            height: 30px;
            vertical-align: bottom;
            line-height: 30px;
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

    <table class="table" style="font-size: 10pt">
        <tr>
            <td></td>
            <td></td>
            <td  style="text-align:right">  Date: {{ date('M d, Y', strtotime($assistance['assessment']['created_at'])) }}</td>
        </tr>
    </table>

    


    <p style="text-align: center; line-height:2em">

        This is to certify that
        <span class="underline">
            {{ $client['first_name'] . ' ' . $client['middle_name'] . ' ' . $client['last_name'] . ' ' . $client['ext_name'] }}</span>,
        <span class="underline">{{ $client['gender'] }}</span>, <span class="underline">{{ $age }}</span>
        year/s
        <br>
        and presently residing at
        <span class="underline">
            {{ $client['street_number'] . ', BRGY ' . $client['psgc']['brgy_name'] . ', ' . $client['psgc']['city_name'] . ', ' . $client['psgc']['province_name'] }}
        </span><br>
        has been found eligible for assistance after assessment and validation conducted, for his/herself.

    </p>

    <table class="table table-bordered ">
        <thead>
            <tr>
                <td class="text-center">RECORDS OF THE CASE SUCH AS THE FOLLOWING ARE CONFIDENTIALLY FILED AT THE CRISIS
                    INTERVENTION
                    SECTION</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="column-count: 4">
                    <ul>
                        <li> General Intake Sheet</li>
                        @foreach ($records as $record)
                            <li>
                                {{ $record }}
                            </li>
                        @endforeach
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>

    <p class="text-center" style="line-height:2em">
        The client is hereby recommended to receive <span
            class="underline">{{ $assistance['aics_type']['name'] }}</span> assistance <br>
        in the amount of <span class="underline" style="text-transform:uppercase">{{ $amount_in_words }} PESOS
            ONLY</span>,
        PHP <span class="underline"> {{ number_format($assistance['assessment']['amount'],2) }} </span>
        CHARGABLE AGAINST: <span class="underline">{{ $assistance['assessment']['fund_source']['name'] }}</span>
    </p>
    <br>

    <table class="table text-center">
        <tbody>
            <tr>
                <td style="text-align: left;">Conforme:</td>
                <td style="text-align: left;">Prepared by:</td>
                <td style="text-align: left;">Approved by:</td>
            </tr>
            <tr>
                <td>
                    <div class="sig">
                        {{ $client['first_name'] . ' ' . $client['middle_name'] . ' ' . $client['last_name'] . ' ' . $client['ext_name'] }}
                    </div>
                </td>
                <td>
                    <div class="sig"></div>
                </td>
                <td>
                    <div class="sig"></div>
                </td>
            </tr>
            <tr>
                <td><b>Beneficiary/Representative</b><br>Signature Over Printed Name</td>
                <td><b>Social Worker</b><br>Signature Over Printed Name</td>
                <td><b>SWADO</b><br>Signature Over Printed Name</td>
            </tr>
        </tbody>
    </table>
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
        PHP <span class="underline"> {{ number_format( $assistance['assessment']['amount'], "2") }} </span>

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
                        <div class="sig"></div>
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
    @endif


</body>

</html>
