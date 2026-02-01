<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Invitation</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      width: 350px;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 8px 0 15px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    button {
      width: 100%;
      padding: 10px;
      background: #007BFF;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background: #0056b3;
    }

    .message {
      margin-top: 10px;
      text-align: center;
      color: red;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Complete Registration</h2>
  <form id="registerForm">
    <input type="hidden" id="token" value=""> <!-- token from URL -->
    
    <label for="name">Full Name</label>
    <input type="text" id="name" placeholder="Your Name" required>

    <label for="password">Password</label>
    <input type="password" id="password" placeholder="Password" required>

    <label for="password_confirmation">Confirm Password</label>
    <input type="password" id="password_confirmation" placeholder="Confirm Password" required>

    <button type="submit">Register</button>
    <div class="message" id="message"></div>
  </form>
</div>

<script>
  // Extract token from URL
  const params = new URLSearchParams(window.location.search);
  const token = params.get('token');
  document.getElementById('token').value = token;

  const form = document.getElementById('registerForm');
  const message = document.getElementById('message');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const name = document.getElementById('name').value;
    const password = document.getElementById('password').value;
    const password_confirmation = document.getElementById('password_confirmation').value;

    if (!token) {
      message.textContent = "Invalid invitation link.";
      return;
    }

    try {
      const response = await fetch('http://localhost:8000/api/register-invited', {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          token,
          name,
          password,
          password_confirmation
        })
      });

      const data = await response.json();

      if (response.ok) {
        message.style.color = "green";
        message.innerHTML = `${data.message} Redirecting to login in <span id="countdown">3</span> seconds...`;

        // Countdown redirect
        let countdown = 3;
        const interval = setInterval(() => {
          countdown--;
          document.getElementById('countdown').textContent = countdown;
          if (countdown <= 0) {
            clearInterval(interval);
            window.location.href = 'login.html'; // replace with your login page
          }
        }, 1000);

        form.reset();
      } else {
        message.style.color = "red";
        message.textContent = data.message || 'Registration failed.';
      }
    } catch (err) {
      message.style.color = "red";
      message.textContent = "Something went wrong.";
      console.error(err);
    }
  });
</script>

</body>
</html>
