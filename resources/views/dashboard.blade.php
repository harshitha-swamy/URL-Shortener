<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Sembark URL Shortener</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa; 
            margin: 0;
            padding: 0;
        } */

        body {
            background: #f7f7f7;
            min-height: 100vh;
        }

        .dashboard-container {
            background-color: #fff;
            max-width: 1100px;
            margin: 40px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* Header with logout aligned properly */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ff7f0e;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .dashboard-header h2 {
            color: #ff7f0e;
            margin: 0;
            font-size: 24px;
        }

        #logoutBtn {
            background-color: #dc3545;
            color: white;
            padding: 6px 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #logoutBtn:hover {
            background-color: #c82333;
        }

        /* Button group */
        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 20px;
        }

        button#shortUrlBtn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        button#inviteBtn {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            opacity: 0.9;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
            margin-bottom: 30px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #dee2e6;
            word-break: break-word;
            text-align: left;
        }

        th {
            background-color: #e9ecef;
        }

        td a {
            color: #0d6efd;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        h3 {
            margin-bottom: 10px;
            color: #343a40;
        }

        .urls-container {
            margin-top: 20px;
        }

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg border shadow-lg" style="background-color: #8daad5;" data-bs-theme="light">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <a class="navbar-brand" href="#">Shortner URL</a>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <!-- <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li> -->
      </ul>
      <form class="d-flex" role="search">
        <button id="logoutBtn">Logout &rarr;</button>
      </form>
    </div>
  </div>
</nav>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h2 id="dashboardTitle">Dashboard</h2>
    </div>

    <div class="button-group">
        <button id="shortUrlBtn">Create Short URL</button>
        <button id="inviteBtn">Invite</button>
    </div>

    <div id="companyTable">
        <h3>Clients</h3>
        <table>
            <thead>
                <tr>
                    <th>Company Name</th>
                    <th>Users</th>
                    <th>Total Generated URLs</th>
                    
                </tr>
            </thead>
            <tbody id="clientsTableBody">
                <!-- JS fills this -->
            </tbody>
        </table>
    </div>

    <div id="url_list" class="urls-container">
        <h3>URLs Created</h3>
        <table>
            <thead>
                <tr>
                    <th>Original URL</th>
                    <th>Short URL</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Total Hits</th>
                </tr>
            </thead>
            <tbody id="urlsTableBody">
                <!-- JS fills this -->
            </tbody>
        </table>
    </div>
</div>

<script>
const token = localStorage.getItem('api_token');
if (!token) window.location.href = '/login';

const dashboardTitle = document.getElementById('dashboardTitle');
const logoutBtn = document.getElementById('logoutBtn');
const inviteBtn = document.getElementById('inviteBtn');
const shortUrlBtn = document.getElementById('shortUrlBtn');
const urllist = document.getElementById('url_list');
const clientsTableBody = document.getElementById('clientsTableBody');
const urlsTableBody = document.getElementById('urlsTableBody');
const companyTable = document.getElementById('companyTable');

logoutBtn.addEventListener('click', async () => {
    await fetch('/api/logout', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    });
    localStorage.removeItem('api_token');
    window.location.href = '/login';
});

async function loadDashboard() {
    const res = await fetch('/api/me', {
        headers: {'Authorization': 'Bearer ' + token, 'Accept': 'application/json'}
    });
    if (!res.ok) return window.location.href = '/login';
    const user = await res.json();

    dashboardTitle.innerText = user.role === 'super_admin' ? 'Super Admin Dashboard' :
                               user.role === 'admin' ? 'Admin Dashboard' :
                               user.role === 'member' ? 'Member Dashboard' : 'Dashboard';

    inviteBtn.style.display = (user.role === 'super_admin' || user.role === 'admin') ? 'inline-block' : 'none';
    shortUrlBtn.style.display = (user.role === 'admin' || user.role === 'member') ? 'inline-block' : 'none';
    companyTable.style.display = (user.role === 'member') ? 'none' : 'block';
    urllist.style.display = (user.role === 'super_admin') ? 'none' : 'block';
}

async function fetchClients() {
    const res = await fetch('/api/clients', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }});
    if (!res.ok) return;
    const data = await res.json();
    clientsTableBody.innerHTML = '';
    data.forEach(c => {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${c.name} (${c.email})</td><td style="text-align:center;">${c.users_count}</td><td style="text-align:center;">${c.total_urls}</td>`;
        clientsTableBody.appendChild(tr);
    });
}

async function fetchUrls() {
    const res = await fetch('/api/urls', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }});
    if (!res.ok) return;
    const data = await res.json();
    urlsTableBody.innerHTML = '';
    data.forEach(u => {
        console.log("url:", u);
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><a href="${u.original_url}" target="_blank">${u.original_url}</a></td>
            <td><a href="/short/${u.short_code}" target="_blank">${window.location.origin}/short/${u.short_code}</a></td>
            <td style="text-align:center;">${u.user_name ? u.user_name : '-'}</td>
            <td style="text-align:center;">${new Date(u.created_at).toLocaleString()}</td>
            <td style="text-align:center;">${u.clicks}</td>
        `;
        urlsTableBody.appendChild(tr);
    });
}

inviteBtn.addEventListener('click', () => window.location.href = '/invite');
shortUrlBtn.addEventListener('click', () => window.location.href = '/short-urls');

loadDashboard();
fetchClients();
fetchUrls();
</script>

</body>
</html>
