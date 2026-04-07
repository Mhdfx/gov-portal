<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 30px;
        }
        .content h2 {
            color: #333;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .content a {
            color: #667eea;
            text-decoration: none;
        }
        .content a:hover {
            text-decoration: underline;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            font-size: 12px;
            color: #6c757d;
        }
        .unsubscribe {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        .unsubscribe a {
            color: #6c757d;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            background-color: #667eea;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .button:hover {
            background-color: #5a6fd8;
        }
        .highlight {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .newsletter-content {
            line-height: 1.8;
        }
        .newsletter-content h1, .newsletter-content h2, .newsletter-content h3 {
            color: #333;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        .newsletter-content ul, .newsletter-content ol {
            margin: 15px 0;
            padding-left: 30px;
        }
        .newsletter-content li {
            margin-bottom: 8px;
        }
        .newsletter-content blockquote {
            border-left: 4px solid #667eea;
            margin: 20px 0;
            padding-left: 20px;
            font-style: italic;
            color: #555;
        }
        .newsletter-content img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Boiema Platform</h1>
            <p>Your Gateway to Government Services</p>
        </div>
        
        <div class="content">
            <h2>{{ $subject }}</h2>
            
            <div class="newsletter-content">
                {!! nl2br(e($content)) !!}
            </div>
            
            <div class="highlight">
                <p><strong>Stay Connected:</strong></p>
                <p>Visit our platform to explore more opportunities and services available to you.</p>
                <p style="text-align: center;">
                    <a href="{{ route('home') }}" class="button">Visit Boiema Platform</a>
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Boiema Platform</strong></p>
            <p>Your trusted partner for government services and opportunities</p>
            <p>This email was sent to {{ $subscription->email }}</p>
            
            <div class="unsubscribe">
                <p>
                    <a href="{{ route('newsletter.unsubscribe', ['email' => $subscription->email]) }}">
                        Unsubscribe from this newsletter
                    </a>
                </p>
                <p>&copy; {{ date('Y') }} Boiema Platform. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>






























