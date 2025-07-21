<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired - Gaza Coupon System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .error-container {
            background: white;
            border-radius: 15px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .error-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
        .error-title {
            color: #333;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .error-message {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .refresh-timer {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 2rem;
        }
        .timer {
            font-size: 2rem;
            font-weight: bold;
            color: #007bff;
        }
        .btn-primary {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,123,255,0.3);
        }
        .btn-secondary {
            background: #6c757d;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fa-solid fa-clock"></i>
        </div>
        
        <h1 class="error-title">Session Expired</h1>
        
        <p class="error-message">
            Your session has expired due to inactivity. This is a security measure to protect your account.
            The page will automatically refresh in a few seconds, or you can refresh manually.
        </p>
        
        <div class="refresh-timer">
            <div>Refreshing in:</div>
            <div class="timer" id="timer">5</div>
            <div>seconds</div>
        </div>
        
        <div>
            <button class="btn btn-primary" onclick="refreshNow()">
                <i class="fa-solid fa-refresh me-2"></i>
                Refresh Now
            </button>
            <a href="/" class="btn btn-secondary">
                <i class="fa-solid fa-home me-2"></i>
                Go Home
            </a>
        </div>
    </div>

    <script>
        let timeLeft = 5;
        const timerElement = document.getElementById('timer');
        
        const countdown = setInterval(() => {
            timeLeft--;
            timerElement.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(countdown);
                window.location.reload();
            }
        }, 1000);
        
        function refreshNow() {
            window.location.reload();
        }
        
        // Also refresh if user presses F5 or Ctrl+R
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
                e.preventDefault();
                refreshNow();
            }
        });
    </script>
</body>
</html> 