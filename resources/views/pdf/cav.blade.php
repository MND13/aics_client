<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CAV - {{ $client['first_name'] . ' ' . $client['middle_name'] . ' ' . $client['last_name'] . ' ' . $client['ext_name'] }}</title>
    <style>
        table {
            width: 50%;
            padding: 10px;
            border: solid 1px;
            float: left;
            text-align: center;
        }

        table td {
            border: solid 1px;
            padding: 3px;
        }

        .text-center {
            text-align: center
        }

        body {
            font-size: 9pt;
            font-family: Arial, Helvetica, sans-serif
            
        }

        .sig {
            width: 90%;
            margin: 0 auto;
            border-bottom: solid 1px #000;
            font-weight: bold;
        }
    </style>
</head>

<body>
    @for ($i = 0; $i < 2; $i++)
        <table cellpadding=0 cellspacing=0>
            <tbody>
                <tr>
                    <td colspan="2" class="text-center">
                        <b> CASH ASSISTANCE VOUCHER</b>
                    </td>
                </tr>
                <tr style="text-align: left">
                    <td>ENTITY NAME: DSWD FO XI<br>
                        FUND CLUSTER:

                    </td>
                    <td>NO :</td>
                </tr>

                <tr style="text-align: left">
                    <td>PAYYE/OFFICE:<br>
                        <div class="sig">
                            {{ $client['first_name'] . ' ' . $client['middle_name'] . ' ' . $client['last_name'] . ' ' . $client['ext_name'] }}</span>
                        </div>
                        ADDRESS: <br>
                        <div class="sig" style="font-size: 9pt;">
                            {{ $client['street_number'] }} <br>
                            {{'BRGY ' . $client['psgc']['brgy_name'] . ', '}} <br>
                            {{ $client['psgc']['city_name'] . ', ' . $client['psgc']['province_name'] }}
                        </div>
                    </td>
                    <td style="width:42%; font-weight:bold; text-align:center; font-size: 9pt;">RESPONSIBILTY
                        CENTER CODE: <br> <br><br><br>
                        <div class="sig"></div>
                    </td>
                </tr>
                <tr style="text-align: left">
                    <td colspan="2">
                        I. To be filled out upon request
                    </td>
                </tr>
                <tr class="text-center">
                    <td>Particulars</td>
                    <td>Amount</td>
                </tr>
                <tr>
                    <td>{{ $assistance['aics_type']['name'] }}</td>
                    <td>PHP {{ number_format($assistance['assessment']['amount'], 2) }} </td>
                </tr>
                <tr>
                    <td colspan="2" style="border-bottom:0px;text-align: left">[A] Requested by:</td>

                </tr>
                <tr>
                    <td colspan="2" style="border-bottom:0px; border-top:0px; text-align: center">
                        <br>
                        <div class="sig">
                            {{ $assistance['assessment']['interviewed_by']['first_name'] .
                                ' ' .
                                $assistance['assessment']['interviewed_by']['middle_name'] .
                                ' ' .
                                $assistance['assessment']['interviewed_by']['last_name'] .
                                ' ' .
                                $assistance['assessment']['interviewed_by']['ext_name'] }}
                        </div>
                        SIGNATURE OVER PRINTED NAME<br>
                        NAME OF REQUESTOR<br>

                    </td>

                </tr>
                <tr>
                    <td colspan="2" style="border-top:0px;
                    border-bottom:0px;text-align: left">
                        Approved By:</td>

                </tr>
                <tr>
                    <td colspan="2" style="border-top:0px; ">
                        <br>
                        <div class="sig">
                            {{ $assistance['assessment']['approved_by'] }}
                        </div>
                        SIGNATURE OVER PRINTED NAME<br>
                        IMMEDIATE SUPERVISOR/AUTHORIZED<br>
                        REPRESENTATIVE<br>
                    </td>

                </tr>

                <tr>
                    <td colspan="2" style="border-bottom:0px;text-align: left">[B] Paid by:</td>

                </tr>
                <tr>
                    <td colspan="2" style="border-bottom:0px; border-top:0px; text-align: center">
                        <br>
                        <div class="sig">
                            {{ $assistance['assessment']['sdo'] }}
                        </div>
                        SPECIAL DISBURSING OFFICER

                    </td>

                </tr>
                <tr>
                    <td colspan="2" style="border-top:0px;
                    border-bottom:0px;text-align: left">
                        Cash Receieved By:</td>

                </tr>
                <tr>
                    <td colspan="2" style="border-top:0px;  ">
                        <br>
                        <div class="sig">
                            {{ $client['first_name'] . ' ' . $client['middle_name'] . ' ' . $client['last_name'] . ' ' . $client['ext_name'] }}</span><br>
                        </div>
                        SIGNATURE OVER PRINTED NAME<br>
                        PAYEE<br>
                    </td>

                </tr>


                <tr>
                    <td colspan="2"  >DATE: 
                         {{ date('M d, Y', strtotime($assistance['assessment']['created_at'])) }}
                        
                    </td>
                </tr>

            </tbody>
        </table>
    @endfor

</body>

</html>
