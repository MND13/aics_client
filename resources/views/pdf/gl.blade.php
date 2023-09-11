<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GL</title>
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
            margin: 0.4in 0.9in;

            opacity: 0.75;
            padding: 0 !important;
            font-size: 12pt;

            font-family: Arial, sans-serif;
            line-height: 1em;
            text-align: justify;
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

        .upper {
            text-transform: uppercase
        }

        .bold {
            font-weight: bold;
        }

        table.table-bordered td {
            border: solid 1px #000
        }
    </style>
</head>

<body>
    <br><br>
    <table style="width:100%">
        <tr>
            <td>
                <!--<img src='{ public_path('images/DSWD-DVO-LOGO.png') }}' style="width: 250px;">-->
            </td>
            <td style="text-align: right; line-height:1em;">
                <b style="font-size:14pt;color: darkblue; padding:0px; margin:0px;">CRISIS INTERVENTION SECTION</b>
                <br />
                <small style="font-size:9pt;">
                    Cor. Suazo St. R. Magsaysay Ave. Davao City
                </small>

            </td>
        </tr>
    </table>

    <p style="text-align:right">
        <small> CONTROL NO: {{ $assistance['assessment']['control_no'] }}</small>
    </p>

    <p style="text-align:left">
        <b> Date:</b> {{ date('M d, Y', strtotime($assistance['assessment']['created_at'])) }} <br> <br>

        <b>{{ $assistance['assessment']['provider']['addressee_name'] }} </b><br>
        @if (
            $assistance['assessment']['provider']['addressee_name'] !=
                $assistance['assessment']['provider']['addressee_position']
        )
            {{ $assistance['assessment']['provider']['addressee_position'] }}<br>
        @endif
        {{ $assistance['assessment']['provider']['company_name'] }}<br>
        {{ $assistance['assessment']['provider']['company_address'] }}
        <br><br>
    </p>

    <p>Dear <b> Sir/Madam</b>,<br><br></p>

    <p> This has reference to the request for <b>{{ $assistance['aics_type']['name'] }}</b> of herein client,
        <span
            class="upper bold">{{ trim($client['first_name'] . ' ' . $client['middle_name'] . ' ' . $client['last_name'] . ' ' . $client['ext_name']) }},
        </span>
        {{ $assistance['aics_client']['gender'] == 'Lalake' ? 'Male' : 'Female' }},
        {{ $assistance['age'] }},

        @if (isset($client['street_number']) && $client['street_number'] != '')
            {{ $client['street_number'] }},
        @endif

        <span style="text-transform:capitalize;">
            {{ 'BRGY. ' . $client['psgc']['brgy_name'] . ', ' }}
            {{ $client['psgc']['city_name'] . ', ' . $client['psgc']['province_name'] }}
        </span>

        @if (isset($bene))
            for the beneficiary,
            <span class="upper bold">
                {{ $bene['first_name'] . ' ' . $bene['middle_name'] . ' ' . $bene['last_name'] . ' ' . $bene['ext_name'] }}</span>

            of

            @if ($client['psgc']['id'] == $bene['psgc']['id'])
                the same address.
            @else
                {{ $bene['street_number'] }}, {{ $bene['psgc']['city_name'] . ', ' . $bene['psgc']['province_name'] }}
            @endif
        @endif

    <p>
        The Department of Social Welfare and Development has assessed and validated the said
        request for assistance through the Crisis Intervention Section. Thus, the Department is
        using this letter to guarantee the payment of the bill in the amount of <span
            style="font-weight:bold;text-transform:uppercase">
            {{ $amount_in_words }} PESOS ONLY
            (Php {{ number_format($assistance['assessment']['amount'], '2') }}) </span>. </p>
    <p>
        To facilitate the payment submit to the Crisis Intervention Section through
        CIS Finance Unit the following documents for the preparation of Disbursement Voucher with one week
        of service has been completed.</p>

    <ul>
        <li>Guarantee Letter (GL) from the DSWD with your company's 'received' stamp</li>
        <li>Statement of Accounts (SOA) or Billing Statement addressed to DSWD</li>
    </ul>


    <p>
        Please be informed that the payment will be directly deposited to your company's bank
        account. Should you have any query, you may coordinate with DSWD FO XI Crisis
        Intervention Section with the telephone number 227-1964 local 1133.
    </p>
    <p>
        Thank you for your consideration.</p>

    <p>
        Very truly yours,
    </p>

    <br><br>

    <b> {{ $assistance['assessment']['gl_signatory']['name'] }}</b><br>
    {{ $assistance['assessment']['gl_signatory']['position'] }}

    <br><br>

    <p> Valid within 30 days upon receipt.</p>

    <br>

    <hr>
    <!--<small style="font-size:8pt"> QR CODE IN LIEU OF SEAL. This letter is system generated.
        Kindly scan the QR Code and crossmatch the contents of this document versus
        within the Official Website for its validity.
        <br>

    </small>-->




</body>

</html>
