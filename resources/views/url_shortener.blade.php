<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sembark URL Shortener</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #4da6ff, #28a745);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            padding: 30px;
        }
        .card-title {
            text-align: center;
            font-weight: 700;
            color: #333;
        }
        #result a {
            word-break: break-all;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>

<!-- Logout Button -->
<button class="btn btn-danger logout-btn" id="logoutBtn">Logout &rarr;</button>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card col-md-5">
        <h2 class="card-title mb-4">Sembark URL Shortener</h2>

        <div class="mb-3">
            <input type="text" class="form-control" id="urlInput" placeholder="Enter long URL">
        </div>
        <button id="shortenBtn" class="btn btn-primary w-100 mb-3">Shorten URL</button>
        <p id="result" class="text-center"></p>
    </div>
</div>

<script>
    // Logout
    document.getElementById('logoutBtn').addEventListener('click', () => {
        localStorage.removeItem('api_token');
        window.location.href = '/login';
    });

    document.getElementById('shortenBtn').addEventListener('click', () => {
        const url = document.getElementById('urlInput').value.trim();
        const result = document.getElementById('result');

        if (!url) {
            result.textContent = 'Please enter a URL';
            return;
        }

        const token = localStorage.getItem('api_token'); // retrieve stored token

        fetch('/api/short-urls', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify({ url })
        })
        .then(res => res.json())
        .then(data => {
            console.log("data:", data); 
            if (data?.short_code) {
                result.innerHTML = `Shortener URL : <a href="s/${data.short_code}" target="_blank">${data.short_code}</a>`;
            } else {
                result.textContent = data.message || 'Error creating short URL';
            }
        })
        .catch(() => {
            result.textContent = 'Error creating short URL';
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
