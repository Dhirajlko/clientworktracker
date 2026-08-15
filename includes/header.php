<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Leo Client Master</title>

<link rel="icon" href="/reports/client-work-tracker/favicon.svg">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<link href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<link href="/reports/client-work-tracker/assets/css/style.css" rel="stylesheet">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">

<div class="container-fluid">

<a class="navbar-brand fw-bold" href="/reports/client-work-tracker/">
<i class="fa-solid fa-users"></i>
Leo Client Master
</a>

<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="menu">

<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link" href="/reports/client-work-tracker/">
<i class="fa fa-gauge"></i>
Dashboard
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="/reports/client-work-tracker/pages/clients.php">
<i class="fa fa-users"></i>
Clients
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="#">
<i class="fa fa-folder-open"></i>
Documents
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="#">
<i class="fa fa-file-import"></i>
Import
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="#">
<i class="fa fa-file-export"></i>
Export
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="#">
<i class="fa fa-gear"></i>
Settings
</a>
</li>

<li class="nav-item">
<a class="btn btn-warning ms-3"
href="/reports/admin-dashboard.php">
<i class="fa fa-home"></i>
Admin Dashboard
</a>
</li>

</ul>

</div>

</div>

</nav>

<div class="container-fluid py-4">