<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invite Admin - Sembark</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            max-width: 500px;
            width: 100%;
        }
        .card-title {
            text-align: center;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        #success {
            color: #28a745;
        }
        #error {
            color: #dc3545;
        }
    </style>
</head>
<body>

<!-- Logout Button -->
<button class="btn btn-danger logout-btn" id="logoutBtn">Logout &rarr;</button>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card">
        <h2 class="card-title">Invite Admin</h2>

        <div id="error" class="mb-2"></div>
        <div id="success" class="mb-2"></div>

        <form id="inviteForm">
            <div class="mb-3">
                <label for="company_name" class="form-label">Company Name</label>
                <input type="text" class="form-control" id="company_name" required>
            </div>

            <div class="mb-3">
                <label for="admin_name" class="form-label">Admin Name</label>
                <input type="text" class="form-control" id="admin_name" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" required>
                    <option value="">Select role</option>
                    <option value="admin">Admin</option>
                    <option value="member">Member</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="admin_email" class="form-label">Admin Email</label>
                <input type="email" class="form-control" id="admin_email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Invite Admin</button>
        </form>
    </div>
</div>

<script>
const token = localStorage.getItem('api_token');
if (!token) window.location.href = '/login';

// Logout functionality
document.getElementById('logoutBtn').addEventListener('click', () => {
    localStorage.removeItem('api_token');
    window.location.href = '/login';
});

async function init() {
    // Optional: get logged-in user to check role
    const res = await fetch('/api/me', {
        headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
    });
    const user = await res.json();

    if (!user || user.role === 'member') {
        alert('You are not allowed to invite admins.');
        window.location.href = '/dashboard';
        return;
    }

    document.getElementById('inviteForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const payload = {
            company_name: document.getElementById('company_name').value,
            name: document.getElementById('admin_name').value,
            role: document.getElementById('role').value,
            email: document.getElementById('admin_email').value,
            password: document.getElementById('password').value
        };

        const res = await fetch('/api/invitations', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const data = await res.json();

        if (!res.ok) {
            document.getElementById('error').innerText = data.message || 'Invite failed';
            document.getElementById('success').innerText = '';
            return;
        }

        // Show success message
        document.getElementById('success').innerText = 'Admin invited successfully';
        document.getElementById('error').innerText = '';
        document.getElementById('inviteForm').reset();

        // Redirect to dashboard after 1.5 seconds
        setTimeout(() => {
            window.location.href = '/dashboard';
        }, 1500);
    });
}

init();
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
