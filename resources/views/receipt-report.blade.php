<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utility Payment Receipt</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f5f7;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px 45px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #e6e6e6;
        }

        .header {
            border-bottom: 2px solid #e5e5e5;
            padding-bottom: 15px;
            margin-bottom: 25px;
            text-align: center;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 26px;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .header p {
            color: #888;
            font-size: 15px;
            margin: 0;
        }

        .content {
            color: #333;
            font-size: 16px;
            line-height: 1.6;
        }

        .highlight {
            font-weight: 600;
            color: #2c3e50;
        }

        .details {
            margin-top: 25px;
            background-color: #fafafa;
            border-radius: 10px;
            padding: 20px 25px;
            border: 1px solid #eaeaea;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
        }

        .details td {
            padding: 10px 5px;
            border-bottom: 1px solid #eaeaea;
        }

        .details td.label {
            color: #555;
            width: 50%;
        }

        .details td.value {
            text-align: right;
            color: #2c3e50;
            font-weight: 600;
        }

        .total {
            border-top: 2px solid #2c3e50;
            margin-top: 10px;
            padding-top: 10px;
        }

        .total td {
            font-size: 18px;
            font-weight: 700;
            color: #2c3e50;
        }

        .payment-summary {
            background-color: #fdf6ec;
            color: #8b572a;
            border-radius: 6px;
            padding: 12px 15px;
            margin-top: 25px;
            border: 1px solid #f1e0c6;
            font-weight: 500;
        }

        .footer {
            margin-top: 35px;
            font-size: 14px;
            color: #999;
            text-align: center;
            border-top: 1px solid #eaeaea;
            padding-top: 20px;
        }

        .status {
            text-transform: uppercase;
            font-weight: 700;
        }

        .status.paid {
            color: #27ae60;
        }

        .status.pending {
            color: #e67e22;
        }

        .status.failed {
            color: #c0392b;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Payment Receipt</h1>
            <p>Invoice No: {{ $invoice_no }}</p>
        </div>

        <div class="content">
            <p>Dear <span class="highlight">{{ $username }}</span>,</p>
            <p>
                Thank you for your recent payment. Below is a summary of your utility and rental receipt for this month.
            </p>

            <div class="details">
                <table>
                    <tr>
                        <td class="label">Rental Charge</td>
                        <td class="value">{{ number_format($rental, 0) }} MMK</td>
                    </tr>
                    <tr>
                        <td class="label">Electricity</td>
                        <td class="value">{{ number_format($electricity, 0) }} MMK</td>
                    </tr>
                    <tr>
                        <td class="label">Water</td>
                        <td class="value">{{ number_format($water, 0) }} MMK</td>
                    </tr>
                    <tr>
                        <td class="label">Internet</td>
                        <td class="value">{{ number_format($internet, 0) }} MMK</td>
                    </tr>
                    <tr>
                        <td class="label">Other Charges</td>
                        <td class="value">{{ number_format($other, 0) }} MMK</td>
                    </tr>
                    <tr class="total">
                        <td class="label">Total Paid</td>
                        <td class="value">{{ number_format($total, 0) }} MMK</td>
                    </tr>
                </table>
            </div>

            <div class="payment-summary">
                <p><strong>Payment Date:</strong> {{ \Carbon\Carbon::parse($paid_date)->format('d M Y') }}</p>
                <p><strong>Payment Method:</strong> {{ $payment_method }}</p>
                <p><strong>Status:</strong>
                    <span >
                        Paid
                    </span>
                </p>
            </div>

            <p style="margin-top:25px;">
                This receipt serves as confirmation of your payment.
                Please retain it for your records.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Jalanx Residences. All rights reserved.
        </div>
    </div>
</body>

</html>
