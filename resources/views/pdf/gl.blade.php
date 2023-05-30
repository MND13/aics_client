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
            margin: 0.4in 0.9in ;
           
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



        table.table-bordered td {
            border: solid 1px #000
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
    <p style="text-align:right">
        CONTROL NO:
    </p>

    <p style="text-align:left">
      <b> Date:</b> {{ date('M d, Y', strtotime($assistance['assessment']['created_at'])) }}
                        
    </p>
    


    
</body>
</html>