<!DOCTYPE html>
<html>
<head>
    <title>Invite Admin</title>
</head>
<body style="font-family:Arial; background:#f0f0f0; padding:40px;">

<div style="background:#fff; padding:25px; max-width:450px; margin:auto; border:1px solid #ccc;">
    <h2>Invite Admin (New Company)</h2>

    <div id="error" style="color:red; margin-bottom:10px;"></div>
    <div id="success" style="color:green; margin-bottom:10px;"></div>

    <form id="inviteForm">
        <div style="margin-bottom:10px;">
            <label>Company Name</label><br>
            <input type="text" id="company_name" required style="width:100%;">
        </div>

        <div style="margin-bottom:10px;">
            <label>Admin Name</label><br>
            <input type="text" id="admin_name" required style="width:100%;">
        </div>

        <div style="margin-bottom:10px;">
            <label>Role</label><br>
            <select id="role" required style="width:100%;">
                <option value="">Select role</option>
                <option value="admin">Admin</option>
                <option value="member">Member</option>
            </select>
        </div>

        <div style="margin-bottom:10px;">
            <label>Admin Email</label><br>
            <input type="email" id="admin_email" required style="width:100%;">
        </div>

        <div style="margin-bottom:15px;">
            <label>Password</label><br>
            <input type="password" id="password" required style="width:100%;">
        </div>

        <button type="submit">Invite Admin</button>
    </form>
</div>

{{-- <script>

    
  // Example response from backend (/api/me)
  const user = {
    role: 'member' // super_admin | admin | member
  };

  const inviteBtn = document.getElementById('inviteBtn');

  // Only members are blocked
  if (user.role === 'member') {
    inviteBtn.style.display = 'none';
  }

const token = localStorage.getItem('api_token');
if (!token) window.location.href = '/login';

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
        return;
    }

    document.getElementById('success').innerText = 'Admin invited successfully';
    document.getElementById('inviteForm').reset();
});
</script> --}}

<script>
const token = localStorage.getItem('api_token');
if (!token) window.location.href = '/login';

async function init() {
    // Optional: get logged-in user to check role
    const res = await fetch('/api/me', {
        headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
    });
    const user = await res.json();

    if (!user || user.role === 'member') {
        alert('You are not allowed to invite admins.');
        window.location.href = '/dashboard'; // redirect
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

        document.getElementById('success').innerText = 'Admin invited successfully';
        document.getElementById('error').innerText = '';
        document.getElementById('inviteForm').reset();
    });
}

init();
</script>


</body>
</html>
