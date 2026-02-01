<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>URL Shortener</title>
</head>
<body>

<div class="container">
    <input type="text" id="urlInput" placeholder="Enter long URL">
    <button id="shortenBtn">Shorten</button>
    <p id="result"></p>
</div>

<script>
document.getElementById('shortenBtn').addEventListener('click', () => {
    const url = document.getElementById('urlInput').value.trim();
    const result = document.getElementById('result');

    if (!url) {
        result.textContent = 'Please enter a URL';
        return;
    }

   const token = localStorage.getItem('api_token'); // or wherever you store it

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
        if (data) {
            console.log("Response data:", data);
           
        }
        result.innerHTML = `<a href="s/${data.short_code}" target="_blank">${data.short_code}</a>`;
    })
    .catch(() => {
        result.textContent = 'Error creating short URL';
    });
});
</script>

<style>
.container {
    max-width: 400px;
    margin: 100px auto;
    text-align: center;
}
input {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
}
button {
    padding: 10px 20px;
}
</style>

</body>
</html>
