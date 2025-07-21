<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
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
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: bold;
            color: #007bff;
        }
        .field-value {
            background-color: white;
            padding: 10px;
            border-radius: 3px;
            border-left: 4px solid #007bff;
        }
        .message-box {
            background-color: white;
            padding: 15px;
            border-radius: 3px;
            border-left: 4px solid #28a745;
            margin-top: 10px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Contact Form Submission</h1>
        <p>Gaza Coupon System</p>
    </div>
    
    <div class="content">
        <p>A new contact form submission has been received from your website.</p>
        
        <div class="field">
            <div class="field-label">Name:</div>
            <div class="field-value">{{ $name }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Email:</div>
            <div class="field-value">{{ $email }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Subject:</div>
            <div class="field-value">{{ $subject }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Message:</div>
            <div class="message-box">{{ $message }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Submitted:</div>
            <div class="field-value">{{ $submitted_at }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">IP Address:</div>
            <div class="field-value">{{ $ip_address }}</div>
        </div>
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="{{ url('/admin/contact-messages') }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 3px; display: inline-block;">
                View in Admin Panel
            </a>
        </div>
    </div>
    
    <div class="footer">
        <p>This email was sent automatically from the Gaza Coupon System contact form.</p>
        <p>Please respond to the user at: <a href="mailto:{{ $email }}">{{ $email }}</a></p>
    </div>
</body>
</html> 