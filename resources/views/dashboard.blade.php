<!DOCTYPE html>
<html>
<head>
    <title>Super Admin Dashboard - Sembark URL Shortner</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }

        .dashboard-container {
            background-color: #fff;
            padding: 40px;
            border: 1px solid #ccc;
            border-radius: 6px;
            max-width: 1036px;
            margin: auto;
        }

        h2 {
            color: orange;
            margin-top: 0;
            border-bottom: 2px solid orange;
            padding-bottom: 10px;
        }

        #logoutBtn {
            float: right;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* important for wrapping/truncation */
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            overflow-wrap: break-word; /* wrap long URLs */
        }

        td a {
            display: inline-block;
            max-width: 100%;       /* ensures wrapping inside cell */
            word-break: break-word; /* break very long URLs */
            color: #007bff;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        .urls-container {
            overflow-x: auto; /* scroll if table too wide */
            margin-top: 10px;
        }

        button {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #shortUrlBtn {
            background-color: #28a745;
            color: white;
        }

        #inviteBtn {
            background-color: #4da6ff;
            color: white;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2 id="dashboardHeading" style="color:orange; margin-top:0; border-bottom:2px solid orange; padding-bottom:10px;">
    &gt;URL&lt; Dashboard
    <span id="logoutBtn" style="float:right; cursor:pointer;">Logout &rarr;</span>
</h2>

    <div style="margin-top:20px; display:flex; justify-content:space-between; align-items:center;">
        <h3>Clients</h3>
        <div>
            <button id="shortUrlBtn">Create Short URL</button>
            <button id="inviteBtn">Invite</button>
        </div>
    </div>

    <table>
        <thead>
            <tr style="background-color:#eee;">
                <th>Client Name</th>
                <th>Users</th>
                <th>Total Generated URLs</th>
                <th>Total URL Hits</th>
            </tr>
        </thead>
        <tbody id="clientsTableBody">
            <!-- JS will fill this -->
        </tbody>
    </table>

    <h3 class="url_list" style="margin-top:30px;">URLs Created</h3>
    <div class="urls-container">
        <table>
            <thead>
                <tr style="background-color:#eee;">
                    <th>Original URL</th>
                    <th>Short URL</th>
                    <th>Created By</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody id="urlsTableBody">
                <!-- JS will fill this -->
            </tbody>
        </table>
    </div>

    <div style="margin-top:10px; text-align:right;">
        <a href="#" id="viewAllLink">View All</a>
    </div>
</div>



<script>
const token = localStorage.getItem('api_token');
if (!token) window.location.href = '/login';

async function loadDashboard() {
    const res = await fetch('/api/me', {
        headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
    });

    const user = await res.json();

    // Change the <h2> heading based on role
    const dashboardHeading = document.getElementById('dashboardHeading');
    let roleTitle = '';
    switch(user.role) {
        case 'super_admin':
            roleTitle = 'Super Admin Dashboard';
            break;
        case 'admin':
            roleTitle = 'Admin Dashboard';
            break;
        case 'member':
            roleTitle = 'Member Dashboard';
            break;
        default:
            roleTitle = 'Dashboard';
    }
    dashboardHeading.innerHTML = `&gt;URL&lt; ${roleTitle} <span id="logoutBtn" style="float:right; cursor:pointer;">Logout &rarr;</span>`;
}

// Run dashboard
loadDashboard();
</script>

    {{-- <script>
        const token = localStorage.getItem('api_token');
        if (!token) window.location.href = '/login';

        const logoutBtn = document.getElementById('logoutBtn');
        logoutBtn.addEventListener('click', async () => {
            await fetch('/api/logout', {
                method: 'POST',
                headers: { 'Authorization': 'Bearer ' + token, 'Accept':'application/json' }
            });
            localStorage.removeItem('api_token');
            window.location.href = '/login';
        });

        const clientsTableBody = document.getElementById('clientsTableBody');

        async function fetchClients() {
            // Example API: GET /api/clients (your backend should return clients with users count, total URLs, hits)
            const res = await fetch('/api/clients', {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept':'application/json' }
            });
            const data = await res.json();

            clientsTableBody.innerHTML = '';

            data.forEach(client => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="border:1px solid #ccc; padding:8px;">${client.name} (${client.email})</td>
                    <td style="border:1px solid #ccc; padding:8px; text-align:center;">${client.users_count}</td>
                    <td style="border:1px solid #ccc; padding:8px; text-align:center;">${client.total_urls}</td>
                    <td style="border:1px solid #ccc; padding:8px; text-align:center;">${client.total_hits}</td>
                `;
                clientsTableBody.appendChild(tr);
            });
        }

        fetchClients();

        // document.getElementById('inviteBtn').addEventListener('click', () => {
        //     alert('Invite Admin functionality (you can implement modal or redirect to invite page)');
        // });

        document.getElementById('viewAllLink').addEventListener('click', () => {
            alert('Pagination / View All functionality (implement as needed)');
        });
    </script>

    

<script>
document.getElementById('inviteBtn').addEventListener('click', () => {
    window.location.href = '/invite';
});
</script>



<script>
const token = localStorage.getItem('api_token');
console.log("Token in dashboard:", token);
if (!token) window.location.href = '/login';

async function loadDashboard() {
    const res = await fetch('/api/me', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    });

    const user = await res.json();

console.log("User data:", user);
    const inviteBtn   = document.getElementById('inviteBtn');
    const shortUrlBtn = document.getElementById('shortUrlBtn');

    // hide both first
    inviteBtn.style.display = 'none';
    shortUrlBtn.style.display = 'none';
    alert(user.role);

    if (user.role === 'super_admin') {
        inviteBtn.style.display = 'inline-block';
    }

    if (user.role === 'admin' || user.role === 'member') {
        shortUrlBtn.style.display = 'inline-block';
    }
}

loadDashboard();
</script>


<script>
document.getElementById('inviteBtn').onclick = () => {
    window.location.href = '/invite';
};

document.getElementById('shortUrlBtn').onclick = () => {
    window.location.href = '/short-urls';
};
</script> --}}


<script>
// const token = localStorage.getItem('api_token');
if (!token) window.location.href = '/login';

const logoutBtn = document.getElementById('logoutBtn');
const clientsTableBody = document.getElementById('clientsTableBody');
const inviteBtn = document.getElementById('inviteBtn');
const shortUrlBtn = document.getElementById('shortUrlBtn');

logoutBtn.addEventListener('click', async () => {
    await fetch('/api/logout', {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token, 'Accept':'application/json' }
    });
    localStorage.removeItem('api_token');
    window.location.href = '/login';
});

document.getElementById('viewAllLink').addEventListener('click', () => {
    alert('Pagination / View All functionality (implement as needed)');
});

document.getElementById('inviteBtn').addEventListener('click', () => {
    window.location.href = '/invite';
});

document.getElementById('shortUrlBtn').addEventListener('click', () => {
    window.location.href = '/short-urls';
});

async function loadDashboard() {
    const res = await fetch('/api/me', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    });

    const user = await res.json();

    console.log("User data:", user);

    // hide both first
    inviteBtn.style.display = 'none';
    shortUrlBtn.style.display = 'none';

    if (user.role === 'super_admin' || user.role === 'admin') {
        inviteBtn.style.display = 'inline-block';
    }

    if (user.role === 'admin' || user.role === 'member') {
        shortUrlBtn.style.display = 'inline-block';
    }
    if(user.role === 'super_admin'){
        $('.url_list').hide();
    }
}

// Fetch clients and populate table
async function fetchClients() {
    const res = await fetch('/api/clients', {
        headers: { 'Authorization': 'Bearer ' + token, 'Accept':'application/json' }
    });
    const data = await res.json();
    clientsTableBody.innerHTML = '';
    data.forEach(client => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="border:1px solid #ccc; padding:8px;">${client.name} (${client.email})</td>
            <td style="border:1px solid #ccc; padding:8px; text-align:center;">${client.users_count}</td>
            <td style="border:1px solid #ccc; padding:8px; text-align:center;">${client.total_urls}</td>
            <td style="border:1px solid #ccc; padding:8px; text-align:center;">${client.total_hits}</td>
        `;
        clientsTableBody.appendChild(tr);
    });
}

const urlsTableBody = document.getElementById('urlsTableBody');

async function fetchUrls() {
    const res = await fetch('/api/urls', {
        headers: { 
            'Authorization': 'Bearer ' + token,
            'Accept':'application/json'
        }
    });
    
    if (!res.ok) {
        console.error("Failed to fetch URLs");
        return;
    }

    const data = await res.json();
    urlsTableBody.innerHTML = '';

    data.forEach(url => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="border:1px solid #ccc; padding:8px;">
                <a href="${url.original_url}" target="_blank">${url.original_url}</a>
            </td>
            <td style="border:1px solid #ccc; padding:8px;">
                <a href="/s/${url.short_code}" target="_blank">${window.location.origin}/s/${url.short_code}</a>
            </td>
            <td style="border:1px solid #ccc; padding:8px; text-align:center;">
                ${url.user ? url.user.name : '-'}
            </td>
            <td style="border:1px solid #ccc; padding:8px; text-align:center;">
                ${new Date(url.created_at).toLocaleString()}
            </td>
        `;
        urlsTableBody.appendChild(tr);
    });
}

// Run everything
loadDashboard();
fetchClients();
fetchUrls();

</script>



</body>
</html>
