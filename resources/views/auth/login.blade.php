<!DOCTYPE html>
<html>
<head>
    <title>Sembark URL Shortener - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="container-fluid d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4">
        <h2 class="card-title border-bottom">Sembark URL Shortner</h2>
        {{-- <div id="error" class="alert alert-danger" role="alert"></div> --}}
        
        <form id="loginForm">
            <div class="card-body">
            <div class="mb-3">
                <label class="form-label" for="email">Email</label><br>
                <input class="form-control" type="email" id="email" placeholder="e.g. sample@example.com" required>
            </div>

            <div style="margin-bottom: 20px;">
                <label class="form-label" for="password">Password</label><br>
                <input class="form-control" type="password" id="password" required>
            </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
        
        const form = document.getElementById('loginForm');
        const errorDiv = document.getElementById('error');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const res = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await res.json();

                if (!res.ok) {
                    errorDiv.textContent = data.message || 'Login failed';
                    return;
                }

                localStorage.setItem('api_token', data.token);
                window.location.href = '/dashboard';
            } catch (err) {
                errorDiv.textContent = 'Something went wrong';
                console.error(err);
            }
        });
    </script>

</body>
</html>
