<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> -->
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <title>Document</title>
    <style>
        body {
            font-size: 9pt;
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            width: 100%;

        }

        #payroll th,
        #payroll td {
            border: solid 1px #a0a0a0;
        }

        #payroll th{
            padding: 2px !important;
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
            font-weight: bold;
        }

        h3 {
            margin: 0px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <main>
        <div style="display:none;">
            {{ $padding = 18 }}
            @if (isset($_GET['gt']))
                {{ $padding = 12 }}
            @endif
        </div>

        <table id="payroll" cellpadding=0 cellspacing=0>
            <tr>
                <td style="text-align:center; border: 0px;" colspan="7">
                    <div style="float: right;text-align: right; font-size:8pt;">{{ $clients->currentPage() }}</div>
                    Republic of the Philippines <br />
                    DEPARTMENT OF SOCIAL WELFARE AND DEVELOPMENT <br>
                    Field Office XI, Davao City <br>
                    <b> {{ $payroll->title }} </b>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:left; border: 0px;">
                    City/Municipality: {{ $payroll->psgc->city_name }}
                    <br>
                    Barangay: {{ $payroll->psgc->brgy_name }}
                </td>
                <td colspan="3" style="text-align:right; border: 0px; text-transform:uppercase;">
                    {{ date_format(date_create($payroll->schedule), 'F Y') }}

                </td>
            </tr>
        </table>
        <table id="payroll" cellpadding='{{ $padding }}' cellspacing=0>
            <tr>
                <th>No. </th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Amount</th>
                <th>Signature</th>
                <th>Date Paid</th>
            </tr>
            <span style="display:none;">{{ $sum = 0 }}</span>
            @foreach ($clients as $key => $client)
                <tr>
                    <!-- PAGINATOR DEFAULT INDEXING-
                     <td style="text-align:center; width:20px;">
                        { ($clients->currentPage() - 1) * $clients->links()->paginator->perPage() + $loop->iteration }}
                    </td>-->

                    <td style="text-align:center; width:20px;">
                        {{ $client->sequence }}
                    </td>

                    <td>{{ $client->aics_client->last_name }} {{ $client->aics_client->ext_name }}</td>
                    <td>{{ $client->aics_client->first_name }}</td>
                    <td>{{ $client->aics_client->middle_name }}</td>
                    <td style="width:100px; text-align:right;">
                        {{ number_format($client->deleted_at == null ? $payroll->amount : 0) }}</td>
                    <td style="width:200px">
                        {{ $client->new_payroll_client ? 'Moved to Payroll ' . $client->new_payroll_client->payroll->amount . ' | Client No:' . $client->new_payroll_client->sequence : '' }}
                    </td>
                    <td style="width:100px;"></td>
                    <span
                        style="display:none;">{{ $sum += $client->deleted_at == null ? $payroll->amount : 0 }}</span>
                </tr>
            @endforeach
            <tr style="font-weight:bold;">
                <td colspan="4" style=" padding: 2px 15px;">Sub Total</td>
                <td style=" text-align:right;  padding: 2px 15px;">{{ number_format($sum) }}</td>
                <td colspan="2" style= "padding: 2px 15px;"> </td>
            </tr>
            @if ($clients->onLastPage() && (isset($_GET['gt']) && $_GET['gt'] == 1))
                <tr style="font-weight:bold;">
                    <td colspan="4" style=" padding: 2px 15px;">Grand Total</td>
                    <td style=" text-align:right; padding: 2px 15px;">{{ number_format($grand_total) }}</td>
                    <td colspan="2" style=" padding: 2px 15px;"> </td>
                </tr>
            @endif

        </table>
        <div style="text-align: right; font-size:8pt">{{ $clients->currentPage() }}</div>

        @if (isset($_GET['gt']) && $_GET['gt'] == 1)
            <footer>
                <table style="text-align:center;" cellpadding="10">
                    <tr>
                        <td style="width:10%;"></td>
                        <td>Recommending Approval:</td>
                        <td>Approved By:</td>
                        <td style="width:10%;"></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td>
                            <div class="sig"> DAHLIA S. PADILLO</div>
                            <br>OIC-ARD for Operations
                        </td>
                        <td>
                            <div class="sig">ATTY. VANESSA B. GOC-ONG </div><br>
                            Regional Director
                        </td>
                        <td>

                        </td>


                    </tr>
                </table>
                <!--<table style="table-layout:fixed; font-size:8pt;">
                    <tr>
                        <td>
                            <p>I hereby certify that each person whose names appear on this roll are entitled to each
                                assistance.</p>
                        </td>
                        <td>
                            <ol>
                                <li>Adequate available funds in the amount of Php</li>
                                <li>Expenditure property certified</li>
                                <li>Supported by documents appearing legal and proper</li>
                                <li>Account codes proper</li>
                            </ol>

                        </td>
                        <td style="text-align: center;">
                            <p>Approved by:</p>

                        </td>
                        <td>
                            <p>I certify on my official oath that I have this ____ day of ________, 20____ paid this to
                                each man
                                whose names appears
                                on the amount set opposite his/her name, he/she having presented himself/herself,
                                established her identity, and his/her signature/thumbmark on this space provided.
                            </p>

                        </td>

                    </tr>
                    <tr style="text-align: center;">
                        <td>
                            <div class="sig">{ $payroll->certified_by1 }} </div>
                        </td>
                        <td>
                            <div class="sig">{ $payroll->certified_by2 }}</div>
                        </td>
                        <td>
                            <div class="sig">{ $payroll->approved_by }}</div>
                        </td>
                        <td>
                            <div class="sig">{ $payroll->sdo }}</div>
                            <br> SDO
                        </td>
                    </tr>
                </table>-->
            </footer>
        @endif



    </main>




</body>

</html>
