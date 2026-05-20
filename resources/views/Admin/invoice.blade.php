<!DOCTYPE html>

<html lang="en">



<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Professional Invoice</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('/assets/img') }}/n.png" />

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"

        rel="stylesheet">

    <style>

        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;

        }



        body {

            font-family: 'Poppins', sans-serif;

            background: #f5f5f5;

            color: #111;

            padding: o !important;

        }



        .invoice-wrapper {

            max-width: 1100px;

            margin: auto;

            background: #fff;

            border: 1px solid #ddd;

        }



        :root {

            --theme: #e11d48;

            --dark: #111111;

        }



        /* HEADER */

        .invoice-header {

            background: linear-gradient(135deg, #111, #e11d48);

            color: #fff;

            padding: 25px 30px;

            border-bottom-left-radius: 25px;

            border-top-right-radius: 25px;

            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 20px;

            flex-wrap: wrap;

        }



        .company-box {

            display: flex;

            align-items: center;

            gap: 15px;

        }



        .logo {

            width: 65px;

            height: 65px;

            border-radius: 12px;

            background: #fff;

            color: var(--theme);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 28px;

            font-weight: 700;

        }



        .company-info h2 {

            font-size: 28px;

            margin-bottom: 4px;

        }



        .company-info p {

            font-size: 13px;

            opacity: .9;

            line-height: 1.6;

        }



        .invoice-info {

            text-align: right;

        }



        .invoice-info h1 {

            font-size: 32px;

            margin-bottom: 8px;

        }



        .invoice-info p {

            font-size: 14px;

            margin: 4px 0;

        }



        /* BODY */

        .content {

            padding: 10px 30px;

        }



        .grid-2 {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 20px;

            margin-bottom: 25px;

        }



        .card {

            border: 1px solid #d9d9d9;

            border-radius: 10px;

            padding: 18px;

        }



        .card h3 {

            color: var(--theme);

            font-size: 18px;

            margin-bottom: 12px;

        }



        .card p {

            font-size: 14px;

            margin-bottom: 7px;

            color: #444;

        }



        /* TABLE */

        .table-area {

            width: 100%;

            overflow: hidden;

        }



        table {

            width: 100%;

            border-collapse: collapse;

        }



        thead {

            background: var(--theme);

            color: #fff;

        }



        th,

        td {

            padding: 12px 10px;

            font-size: 14px;

            text-align: start !important;

        }



        tbody tr {

            border-bottom: 1px solid #ddd;

        }



        tbody tr:nth-child(even) {

            background: #fafafa;

        }



        .amount {

            text-align: right;

        }



        /* TOTAL */

        .summary {

            margin-top: 25px;

            margin-left: auto;

            width: 350px;

            border: 1px solid #ddd;

            border-radius: 10px;

            padding: 18px;

        }



        .summary-row {

            display: flex;

            justify-content: space-between;

            margin-bottom: 10px;

            font-size: 14px;

        }



        .summary-row.total {

            border-top: 1px solid #ddd;

            padding-top: 12px;

            margin-top: 12px;

            font-size: 22px;

            font-weight: 700;

            color: var(--theme);

        }



        /* FOOTER */

        .footer {

            margin-top: 30px;

            background: linear-gradient(135deg, #111, #e11d48);

            color: #fff;

            padding: 18px 30px;

            display: flex;

            justify-content: space-between;

            align-items: center;

            flex-wrap: wrap;

            gap: 10px;

            border-bottom-right-radius: 25px;

            border-top-left-radius: 25px;

        }



        .footer p {

            font-size: 13px;

        }



        /* BUTTON */

        .print-btn {

            margin-top: 20px;

            background: var(--theme);

            color: #fff;

            border: none;

            padding: 12px 20px;

            border-radius: 8px;

            cursor: pointer;

            font-weight: 600;

        }



        /* MOBILE */

        @media(max-width:768px) {

            body {

                padding: 10px;

            }



            .grid-2 {

                grid-template-columns: 1fr;

            }



            .invoice-header {

                flex-direction: column;

                align-items: flex-start;

            }



            .invoice-info {

                text-align: left;

            }



            .summary {

                width: 100%;

            }



            table {

                min-width: 700px;

            }



            .table-area {

                overflow-x: auto;

            }

        }



        /* PRINT */

        @media print {

            body {

                background: #fff;

                padding: 0;

            }



            .print-btn {

                display: none;

            }



            .invoice-wrapper {

                border: none;

                width: 100%;

                max-width: 100%;

            }



            /* FORCE 6 + 6 COLUMN LAYOUT */

            .grid-2 {

                display: grid !important;

                grid-template-columns: 50% 48% !important;

                gap: 10px;

                align-items: start;

            }



            .card {

                width: 100%;

                break-inside: avoid;

                page-break-inside: avoid;

            }



            .table-area {

                overflow: visible !important;

            }



            table {

                width: 100%;

                min-width: 100%;

            }



            th,

            td {

                font-size: 12px;

                padding: 8px;

                word-break: break-word;

            }



            .invoice-header,

            .footer,

            thead {

                print-color-adjust: exact;

                -webkit-print-color-adjust: exact;

            }



            .summary {

                width: 320px;

            }



            .invoice-header {

                display: grid !important;

                grid-template-columns: 50% 50% !important;

                align-items: center;

                gap: 15px;

                padding: 20px 25px;

            }



            .content {

                padding: 10px 1px !important;

            }



            .company-box {

                display: flex !important;

                align-items: center;

                /* gap:12px; */

                width: 100%;

            }



            .invoice-info {

                width: 100%;

                text-align: right !important;

            }



            .invoice-info h1 {

                font-size: 28px;

                margin-bottom: 8px;

            }



            .invoice-info p {

                margin: 3px 0;

                font-size: 13px;

            }



            .company-info h2 {

                font-size: 24px;

                margin-bottom: 4px;

            }



            .company-info p {

                font-size: 12px;

                line-height: 1.4;

                margin: 0;

            }



            .logo {

                width: 55px;

                height: 55px;

                font-size: 24px;

                flex-shrink: 0;

            }



            .invoice-header {

                print-color-adjust: exact;

                -webkit-print-color-adjust: exact;

            }



            .footer {

                /* position: absolute; */

                left: 0;

                right: 0;

                bottom: 0;

                width: 100%;

                padding: 10px;

                border-bottom-right-radius: 25px;

                border-top-left-radius: 25px;

                margin: 0 !important;

            }

        }



        .text-center {

            text-align: center !important;

        }



        /* TERMS + TOTAL ROW */

        .bottom-section {

            display: grid;

            grid-template-columns: 8fr 4fr;

            gap: 20px;

            margin-top: 25px;

            align-items: start;

        }



        .terms-box {

            border: 1px solid #ddd;

            border-radius: 10px;

            padding: 18px;

        }



        .terms-box h3 {

            color: #e11d48;

            font-size: 18px;

            margin-bottom: 12px;

        }



        .terms-box ul {

            padding-left: 18px;

            margin: 0;

        }



        .terms-box li {

            font-size: 14px;

            color: #444;

            margin-bottom: 10px;

            line-height: 1.6;

        }



        /* remove old alignment */

        .summary {

            margin-top: 0;

            margin-left: 0;

            width: 100%;

        }



        /* MOBILE */

        @media(max-width:768px) {

            .bottom-section {

                grid-template-columns: 1fr;

            }

        }



        /* PRINT */

        @media print {

            .bottom-section {

                display: grid !important;

                grid-template-columns: 66.66% 31% !important;

                /* 8*4 */

                gap: 15px;

                align-items: start;

                page-break-inside: avoid;

            }

         .terms-box,

            .summary {

                width: 100%;

                break-inside: avoid;

                page-break-inside: avoid;

            }



            .terms-box li {

                font-size: 12px;

                margin-bottom: 3px;

            }

        }

        @media print {

    html, body {

        height: 100%;

    }



    body {

        display: flex;

        flex-direction: column;

    }



    .invoice-wrapper {

        flex: 1;

        display: flex;

        flex-direction: column;

    }



    .footer {

        margin-top: auto;

        position: relative;

    }

}



    </style>

</head>



<body>

    <div class="invoice-wrapper">

        <!-- HEADER -->

        <div class="invoice-header">

            <div class="company-box">

                <!-- <div class="logo"><img src="{{ asset('/assets/img') }}/n.png" height="40px" /></div> -->

                <div class="company-info">

                    <h2><img src="{{ asset('/assets/Images') }}/white.png" height="40px" /></h2>

                    <p>1st Floor, ABS Plaza, Eraviputhoorkadai,</p>

                    <p> Trivandrum-Nagercoil Hwy, Tamil Nadu, 629158</p>

                </div>

            </div>

            <div class="invoice-info">

                <h5>INVOICE</h5>

                <p><strong>Invoice No:</strong>{{ $invoice->invoice_no }}</p>

                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</p>

            </div>

        </div>

        <!-- CONTENT -->

        <div class="content">

            <!-- DETAILS -->

            <div class="grid-2">

                <div class="card">

                    <h3>Client Details</h3>

                    <p><strong>Name:</strong> {{ $invoice->client_name }}</p>

                    <p><strong>Email:</strong> {{ $invoice->address ?? '-' }}</p>

                    <p><strong>Phone:</strong> {{ $invoice->mobile }}</p>

                    <p class="mb-2"><strong>City:</strong> {{ $invoice->project->address ?? '-' }}</p>

                </div>

                <div class="card">

                    <h3> Account To Transfer</h3>

                    <p><strong>Bank:</strong> {{ $invoice->bank->bank_name ?? '-' }}</p>

                    <p><strong>Account:</strong> {{ $invoice->bank->account_number ?? '-' }}</p>

                    <p><strong>IFSC:</strong>{{ $invoice->bank->ifsc_code ?? '-' }}</p>

                    <p><strong>BRANCH:</strong>{{ $invoice->bank->branch_name ?? '-' }}</p>

                    <p><strong>UPI ID:</strong>{{ $invoice->bank->upi ?? '-' }}</p>

                </div>

            </div>

            <!-- TABLE -->

            <div class="table-area">

                <table>

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Date</th>

                            <th>Module Name</th>

                            <th>Type</th>

                            <th>Description</th>

                            <th class="amount">Price</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($invoice->items as $key => $item)

                            <tr>

                                <td>{{ $key + 1 }}</td>

                                <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</td>

                                <td>{{ $item->module }}</td>

                                <td>{{ $item->type }}</td>

                                <td style="white-space: pre-line;">

                                    {{ chunk_split($item->description, 25, "\n") }}

                                </td>

                                <td class="amount">₹{{ number_format($item->rate) }}</td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <!-- TERMS + TOTAL -->

            <div class="bottom-section">

                <div class="terms-box">

                    <h3>Terms & Conditions</h3>

                    <ul>

                        <li>50% advance payment required before project start.</li>

                        <li>Remaining balance must be cleared before final delivery.</li>

                        <li>Any extra features outside agreed scope will be charged separately.</li>

                        <li>Support period valid for 30 days from delivery date.</li>

                        <li>Late payments may delay deployment or handover.</li>

                    </ul>

                </div>

                <div class="summary">

                    <div class="summary-row">

                        <span>Subtotal</span>

                        <span>₹{{ number_format($invoice->subtotal) }}</span>

                    </div>

                    <div class="summary-row">

                        <span>GST</span>

                        <span>₹{{ number_format($invoice->tax) }}</span>

                    </div>

                    <div class="summary-row">

                        <span>Discount</span>

                        <span>₹{{ number_format($invoice->discount) }}</span>

                    </div>

                    <div class="summary-row total">

                        <span>Total</span>

                        <span>₹{{ number_format($invoice->total) }}</span>

                    </div>

                </div>

            </div>

        </div>

        <div class="footer">

            <p>Thank you for choosing NovelX Technologies.</p>

            <p>https://www.novelx.in/ | account@novelx.in</p>

        </div>

    </div>

  <div class="text-center">
<button onclick="window.print()" class="print-btn">Print Invoice</button>
</div>

    </div>

</body>

<script>
window.onload = function () {

    const params = new URLSearchParams(window.location.search);

    if(params.get('print') == '1')
    {
        window.print();

        setTimeout(() => {
            window.close();
        }, 1000);
    }

}
</script>

</html>