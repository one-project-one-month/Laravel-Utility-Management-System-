<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utility Receipt - September</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 650px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333333;
            font-size: 22px;
            margin-bottom: 10px;
        }

        p {
            color: #555555;
            font-size: 16px;
            line-height: 1.5;
        }

        .highlight {
            font-weight: bold;
            color: #007bff;
        }

        .details {
            margin-top: 20px;
            border-top: 1px solid #dddddd;
            padding-top: 15px;
        }

        .details p {
            margin: 5px 0;
        }

        .footer {
            margin-top: 25px;
            font-size: 14px;
            color: #888888;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Hello {{ $username }},</h1>

        <p>This is your <strong>monthly utility alert</strong> for <strong>September</strong>. Please review your
            charges below:</p>

        <div class="details">
            <p>🏠 <span class="highlight">Rental Charge:</span> {{ number_format($rental, 0) }} MMK</p>
            <p>💡 <span class="highlight">Electricity:</span> {{ number_format($electricity, 0) }} MMK</p>
            <p>💧 <span class="highlight">Water:</span> {{ number_format($water, 0) }} MMK</p>
            <p>📶 <span class="highlight">Internet:</span> {{ number_format($internet, 0) }} MMK</p>
            <p>📝 <span class="highlight">Other Charges:</span> {{ number_format($other, 0) }} MMK</p>
            <p>💰 <span class="highlight">Total Amount:</span> {{ number_format($total, 0) }} MMK</p>
        </div>

        <p>Please make sure to settle your bill before
            <strong>{{ \Carbon\Carbon::parse($due_date)->format('d M Y') }}</strong> to avoid any late fees.
        </p>

        <p>Thank you for your prompt attention.</p>

        <div class="footer">
            &copy; {{ date('Y') }} Jalanx. All rights reserved.
        </div>
    </div>
</body>

</html>
