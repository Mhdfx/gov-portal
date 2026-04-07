<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $notification->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .title {
            color: #2c3e50;
            margin: 0 0 10px 0;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            margin-top: 20px;
        }
        .priority-high {
            border-left: 4px solid #dc3545;
        }
        .priority-medium {
            border-left: 4px solid #ffc107;
        }
        .priority-low {
            border-left: 4px solid #17a2b8;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">{{ $notification->title }}</h1>
        <p><strong>Priority:</strong> {{ ucfirst($notification->priority) }}</p>
        <p><strong>Date:</strong> {{ $notification->created_at->format('M d, Y H:i') }}</p>
    </div>

    <div class="content priority-{{ $notification->priority }}">
        <p>{{ $notification->message }}</p>
        
        @if($notification->data && count($notification->data) > 0)
            <h3>Additional Information:</h3>
            <ul>
                @foreach($notification->data as $key => $value)
                    <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="footer">
        <p>This is an automated notification from the Boiema Platform.</p>
        <p>Please do not reply to this email.</p>
    </div>
</body>
</html>






























