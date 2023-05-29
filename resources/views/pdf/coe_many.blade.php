<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> -->
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <title>Document</title>
    <style>
         @page {
           /* size: 8.27in 11.69in;*/
             size: 8.5in 13in; 
            font-size: 8pt;
            margin: 5%;
            margin-bottom: 10%;
            opacity: 0.75;
            padding: 0 !important;
            /* background: url("{{ public_path('images/watermark.png') }}") no-repeat 0 0; */
        }
        body {
            font-size: 8pt;
            font-family: Tahoma, Arial, Verdana, sans-serif;

        }

        table {
            width: 100%;
            font-size:8pt;
        }

        #payroll th,
        #payroll td {
            border: solid 1px #a0a0a0;
        }

        table tr.page {
            page-break-before: always
        }
        
        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
        }

        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;

            height: auto;
        }

        footer table td {
            vertical-align: top;
        }

        p {
            page-break-after: always;
        }

        p:last-child {
            page-break-after: never;
        }

        .sig {
            width: 80%;
            border-bottom: solid 1px #000;
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <main>
        <!-- <div style="text-align: right; font-size:8pt">{ $clients->currentPage() }}</div>-->

        <table id="payroll" cellpadding=3 cellspacing=0>
            <tr>
                <td style="text-align:center; border: 0px;" colspan="7">Republic of the Philippines <br />
                    DEPARTMENT OF SOCIAL WELFARE AND DEVELOPMENT <br>
                    Field Office XI, Davao City
                    <h3> CERTIFICATE OF ELIGIBILITY </h3>
                </td>
            </tr>
            <tr>
                <td colspan="7" style="text-align:left; border: 0px; font-weight:bold;">


                    This is to certify that the 
                    {{ $in_words }} ({{  number_format(sizeof($clients)) }}) names of Beneficiaries of
                    {{$payroll["psgc"]["province_name"]}} 
                    listed below are eligible to receive the FOOD ASSISTANCE from Assistance to Individuals in Crisis Situation
                    PROGRAM.
                    

                </td>
            </tr>
            <tr style="text-transform:uppercase; background:#dedede; font-size:8pt;">
                <th>No. </th>
                <th>Brgy. </th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th style="width:50px">Ext </th>
                <th>Amount</th>
            </tr>
            <span style="display:none;">{{ $sum = 0 }}</span>
            @foreach ($clients as $key => $client)
                <tr>
                  
                    <td style="text-align:center; width:20px;">
                       <!-- { $client->sequence }-->
                       {{$key+1}}
                    </td>
                    <td>
                        {{ isset($client->aics_client->psgc->brgy_name)  ? $client->aics_client->psgc->brgy_name : "" }}
                    </td>

                    <td>{{ $client->aics_client->last_name }}</td>
                    <td>{{ $client->aics_client->first_name }}</td>
                    <td>{{ $client->aics_client->middle_name }}</td>
                    <td>{{ $client->aics_client->ext_name }}</td>
                    <td style="width:100px; text-align:right;">{{ number_format($payroll->amount,2) }}</td>

                </tr>
            @endforeach


            <tr>
                <td colspan="7" style="text-align:center; font-size:8pt;">********** NOTHING FOLLOWS **********</td>
            </tr>
        </table>
        <!--<div style="text-align: right; font-size:8pt">{ $clients->currentPage() }</div>-->

        @if ($key == sizeof($clients)-1)
            <!--<footer>-->
                <table style="table-layout:fixed; font-size:8pt;" cellpadding=8>
                    <tr>
                        <td>Prepared by:  </td>
                        <td>Recommended for Approval: </td>
                        <td>Approved by:</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr style="text-align: center;">
                        <td>
                            <div class="sig">{{$payroll->sdo}} </div><br>SDO
                        </td>
                        <td>
                            <div class="sig"></div><br>
                        </td>
                        <td>
                            <div class="sig">ATTY. VANESSA B. GOC-ONG</div> <br>
                            Regional Director		
                        </td>

                    </tr>
                </table>
           <!-- </footer>-->
        @endif



    </main>




</body>

</html>
