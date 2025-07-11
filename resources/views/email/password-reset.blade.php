<!DOCTYPE html>
<html>
<head>
    <style>
        .button {
            background: #3490dc;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 15px 0;
        }
        .footer {
            font-size: 12px;
            color: #666;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <h1>SwiftNotes</h1>
    <p>You requested to reset your password for SwiftNotes Account.</p>

    <p>Click the button below to reset your password:</p>
    <a href="{{ $resetUrl }}" class="button">Reset Password</a>

    <p><strong>Important:</strong> This link will expire in 60 minutes.</p>

    <p>If you didn't request this password reset, you can safely ignore this email.</p>

    <div class="footer">
      <p>Thanks,<br>The SwiftNotes Team</p>
      <p>Email sent on: {{ \Carbon\Carbon::now()->timezone('Africa/Lagos')->format('F j, Y \a\t g:i A') }} (WAT)</p>
    </div>
</body>
</html>


