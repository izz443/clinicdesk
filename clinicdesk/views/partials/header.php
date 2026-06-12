<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; }
        .wrapper { display: flex; width: 100%; align-items: stretch; }
        #sidebar { min-width: 250px; max-width: 250px; background: #343a40; color: #fff; min-height: 100vh; transition: all 0.3s; }
        #sidebar .sidebar-header { padding: 20px; background: #22252a; text-align: center; }
        #sidebar ul p { color: #fff; padding: 10px; }
        #sidebar ul li a { padding: 12px 20px; font-size: 1.1em; display: block; color: #c2c7d0; text-decoration: none; border-bottom: 1px solid #4b545c; }
        #sidebar ul li a:hover, #sidebar ul li.active > a { color: #fff; background: #495057; }
        #sidebar ul li a i { margin-left: 10px; }
        #content { width: 100%; padding: 20px; min-height: 100vh; transition: all 0.3s; }
        .navbar { background: #fff; border-bottom: 1px solid #dee2e6; margin-bottom: 20px; }
        .info-box { display: flex; box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2); border-radius: 0.25rem; background: #fff; margin-bottom: 1rem; padding: .5rem; min-height: 80px; }
        .info-box .info-box-icon { border-radius: 0.25rem; align-items: center; display: flex; font-size: 1.875rem; justify-content: center; text-align: center; width: 70px; color: #fff; }
        .info-box .info-box-content { display: flex; flex-direction: column; justify-content: center; line-height: 1.2; padding: 0 10px; flex: 1; }
        .info-box .info-box-text { text-transform: uppercase; font-size: 14px; color: #6c757d; }
        .info-box .info-box-number { font-weight: 700; font-size: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
