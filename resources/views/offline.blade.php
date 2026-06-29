<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're Offline - Nepal Restaurant</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .offline-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .offline-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
        
        h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 15px;
        }
        
        p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        
        .retry-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .retry-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .retry-button:active {
            transform: translateY(0);
        }
        
        .status {
            margin-top: 20px;
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            display: none;
        }
        
        .status.checking {
            background: #fff3cd;
            color: #856404;
            display: block;
        }
        
        .status.online {
            background: #d4edda;
            color: #155724;
            display: block;
        }
        
        .status.offline {
            background: #f8d7da;
            color: #721c24;
            display: block;
        }
    </style>
</head>
<body>
    <div class="offline-container">
        <div class="offline-icon">📶</div>
        <h1>You're Offline</h1>
        <p>Please check your internet connection and try again. Some features may not be available while offline.</p>
        <button class="retry-button" onclick="retryConnection()">Retry</button>
        <div id="status" class="status"></div>
    </div>
    
    <script>
        function retryConnection() {
            const statusEl = document.getElementById('status');
            statusEl.className = 'status checking';
            statusEl.textContent = 'Checking connection...';
            
            fetch('/api/ping', { 
                method: 'HEAD',
                cache: 'no-cache'
            })
            .then(response => {
                if (response.ok) {
                    statusEl.className = 'status online';
                    statusEl.textContent = 'Connection restored! Redirecting...';
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 1500);
                } else {
                    throw new Error('Connection failed');
                }
            })
            .catch(error => {
                statusEl.className = 'status offline';
                statusEl.textContent = 'Still offline. Please try again.';
            });
        }
        
        // Listen for online/offline events
        window.addEventListener('online', function() {
            const statusEl = document.getElementById('status');
            statusEl.className = 'status online';
            statusEl.textContent = 'Connection restored! Redirecting...';
            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 1500);
        });
        
        window.addEventListener('offline', function() {
            console.log('You are offline');
        });
    </script>
</body>
</html>
