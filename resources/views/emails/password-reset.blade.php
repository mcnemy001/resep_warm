<!DOCTYPE html>
<html>
<head>
    <title>RecipeWarm - Password Reset</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #FF6B35; color: white; text-align: center; padding: 10px; }
        .content { background-color: #f4f4f4; padding: 20px; }
        .button { 
            display: inline-block; 
            background-color: #FF6B35; 
            color: white; 
            padding: 10px 20px; 
            text-decoration: none; 
            border-radius: 5px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>RecipeWarm</h1>
        </div>
        <div class="content">
            <h2>Password Reset Request</h2>
            <p>Hi {{ $userName }},</p>
            <p>You are receiving this email because we received a password reset request for your account.</p>
            
            <p style="text-align: center;">
                <a href="{{ $actionUrl }}" class="button">Reset Password</a>
            </p>
            
            <p>This link will expire in {{ $expireMinutes }} minutes. If you did not request a password reset, no further action is required.</p>
            
            <p>Warm regards,<br>RecipeWarm Team</p>
        </div>
    </div>
</body>
</html>