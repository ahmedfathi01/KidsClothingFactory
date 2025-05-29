<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'إشعار' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1E2A38;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: right;
            background-color: #f0fff5;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: rgba(255, 255, 255, 0.75);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 146, 69, 0.08);
        }
        .email-header {
            background-color: #009245;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px;
        }
        .greeting {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #009245;
        }
        .intro {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 25px;
            border-bottom: 1px solid rgba(0, 146, 69, 0.25);
            padding-bottom: 15px;
        }
        .section:last-child {
            border-bottom: none;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #009245;
        }
        .section-item {
            padding: 5px 0;
        }
        .divider {
            height: 1px;
            background-color: rgba(0, 146, 69, 0.25);
            margin: 15px 0;
        }
        .action-button {
            display: inline-block;
            background-color: #009245;
            color: white !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: bold;
            margin: 15px 0;
            text-align: center;
        }
        .outro {
            margin-top: 20px;
            font-size: 14px;
            color: #2C3E50;
        }
        .footer {
            background-color: rgba(0, 146, 69, 0.05);
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #2C3E50;
        }
        .payment-info {
            background-color: rgba(0, 146, 69, 0.05);
            border-right: 4px solid #009245;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .bank-details {
            background-color: rgba(255, 255, 255, 0.75);
            padding: 12px;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <img src="{{ asset('assets/kids/img/logo.png') }}" alt="بر الليث" style="max-width: 150px; margin-bottom: 10px;">
            <h1>{{ $title }}</h1>
        </div>
        <div class="email-body">
            <div class="greeting">{{ $greeting }}</div>

            <p class="intro">{{ $intro }}</p>

            <div class="divider"></div>

            @foreach($content['sections'] as $section)
                <div class="section {{ $section['title'] === 'معلومات الدفع' ? 'payment-info' : '' }}">
                    <div class="section-title">{{ $section['title'] }}</div>
                    @foreach($section['items'] as $item)
                        @if($item)
                            <div class="section-item">{{ $item }}</div>
                        @endif
                    @endforeach
                </div>
            @endforeach

            @if(isset($content['action']))
                <div style="text-align: center;">
                    <a href="{{ $content['action']['url'] }}" class="action-button">
                        {{ $content['action']['text'] }}
                    </a>
                </div>
            @endif

            <div class="outro">
                @foreach($content['outro'] as $line)
                    <p>{{ $line }}</p>
                @endforeach
            </div>
        </div>
        <div class="footer">
            © {{ date('Y') }} بر الليث - جميع الحقوق محفوظة
        </div>
    </div>
</body>
</html>
