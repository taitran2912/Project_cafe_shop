<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title']?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/sidebar.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/menu.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/orders.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/inventory.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/branch.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/user.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/table.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/POS.css">


    <style>
        .page-btn {
            margin: 3px;
            padding: 5px 10px;
            border: none;
            background: #d4a373;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .page-btn:hover {
            background: #b87333;
        }
        .status-active {
            color: green;
            font-weight: bold;
        }
        .status-inactive {
            color: red;
        }
    </style>
</head>
<body>
    <div class="dashboard">