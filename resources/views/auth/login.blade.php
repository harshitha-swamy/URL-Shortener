<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sembark URL Shortener - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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
        #error {
            display: none;
        }
        .form-text a {
            text-decoration: none;
            color: #007bff;
        }
        .form-text a:hover {
            text-decoration: underline;
        }
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 73%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .position-relative {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card col-md-4">
            <h2 class="card-title mb-4">Sembark URL Shortener</h2>

            <!-- Error Message -->
            <div id="error" class="alert alert-danger" role="alert"></div>

            <form id="loginForm">
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" type="email" id="email" placeholder="e.g. sample@example.com" required>
                </div>

                <div class="mb-3 position-relative">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control" type="password" id="password" placeholder="Enter your password" required>
                    <span class="password-toggle" id="togglePassword">Show</span>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
    const form = document.getElementById('loginForm');
    const errorDiv = document.getElementById('error');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    // Toggle password visibility
    togglePassword.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        togglePassword.textContent = type === 'password' ? 'Show' : 'Hide';
    });

    // Login form submit
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const password = passwordInput.value;

        errorDiv.style.display = 'none';

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
                errorDiv.style.display = 'block';
                return;
            }

            localStorage.setItem('api_token', data.token);
            window.location.href = '/dashboard';
        } catch (err) {
            errorDiv.textContent = 'Something went wrong';
            errorDiv.style.display = 'block';
            console.error(err);
        }
    });
</script>
</body>
</html>
