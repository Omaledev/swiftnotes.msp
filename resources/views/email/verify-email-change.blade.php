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
    <p>You recently requested to change your email address. Please click the button below to verify your new email address.</p>

    <a href="{{ $verificationUrl }}" style="color: #2563eb; text-decoration: underline;">
      Verify Email Change
    </a>

    <p><strong>Important:</strong> This link will expire in 60 minutes.</p>

    <p>If you didn't request this change, please secure your account.</p>

    <div class="footer">
      <p>Thanks,<br>The SwiftNotes Team</p>
      <p>Email sent on: {{ \Carbon\Carbon::now()->timezone('Africa/Lagos')->format('F j, Y \a\t g:i A') }} (WAT)</p>
    </div>
</body>
</html>


