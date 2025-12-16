<?php
// Template Header

if (!defined('APP_LAYOUT_INCLUDED')) {
    define('APP_LAYOUT_INCLUDED', true);
}

if (!defined('APP_BASE_URL')) {
    $scriptName = isset($_SERVER['SCRIPT_NAME']) ? (string) $_SERVER['SCRIPT_NAME'] : '';
    $baseUrl = rtrim(str_replace('\\', '/', (string) dirname($scriptName)), '/');
    if ($baseUrl === '.') {
        $baseUrl = '';
    }
    define('APP_BASE_URL', $baseUrl);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Praktikum OOP - Routing & Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bs-primary: #6f42c1;
            --bs-primary-rgb: 111, 66, 193;
            --bs-link-color: #6f42c1;
            --bs-link-hover-color: #5a34a1;
        }
        body {
            background-color: #f3f6f8;
        }
        .app-container {
            max-width: 980px;
        }
        .app-card {
            margin: 28px auto;
            border-radius: 6px;
        }
        .app-divider {
            border-top: 2px solid var(--bs-primary);
            opacity: 0.6;
        }
        .app-subtitle {
            font-weight: 600;
            font-size: 0.95rem;
            color: #1f2d3d;
        }
        .app-add-link {
            display: inline-block;
            font-size: 0.85rem;
            color: var(--bs-primary);
            text-decoration: none;
        }
        .app-add-link:hover {
            text-decoration: underline;
        }
        .app-table thead th {
            background: var(--bs-primary);
            color: #fff;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .app-table tbody td {
            font-size: 0.85rem;
        }
        .app-input {
            background-color: #eef3fb;
        }
        .app-input:focus {
            background-color: #fff;
        }
    </style>
</head>
<body>
<div class="container app-container">
    <div class="card app-card shadow-sm">
        <div class="card-body p-4">
