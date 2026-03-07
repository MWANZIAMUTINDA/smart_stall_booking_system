<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject ?? 'Notice of Violation' }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #222; line-height: 1.5; }
        .container { max-width: 700px; margin: 0 auto; padding: 20px; }
        .header { border-bottom: 3px solid #002d5a; padding-bottom: 10px; margin-bottom: 20px; }
        .signature { margin-top: 30px; }
        pre { white-space: pre-wrap; font-family: inherit; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Official Notice of Violation</h2>
            <p>Market Enforcement Office</p>
        </div>

        <div>
            <pre>{{ $finalLetter }}</pre>
        </div>

        <div class="signature">
            <p>Sincerely,</p>
            <p><strong>{{ optional($violation->officer)->name ?? 'Market Enforcement Officer' }}</strong></p>
            <p>Market Enforcement Office</p>
        </div>
    </div>
</body>
</html>
