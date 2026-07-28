<?php
/**
 * Al Foz Islamic Institute - Shared ERP Header
 */
ob_start();
require_once __DIR__ . '/../auth/session.php';
////check_session();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title>Al Foz ERP System</title>
  <script src="/assets/js/session-keepalive.js"></script>
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Lucide Icons CDN -->
  <script src="https://unpkg.com/lucide@latest"></script>
  
  <!-- Vendor Styles -->
  <link rel="stylesheet" href="/assets/vendor/bootstrap/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Custom Styles -->
  <link rel="stylesheet" href="/assets/css/global.css">
  <link rel="stylesheet" href="/assets/css/dashboard.css">
  <link rel="stylesheet" href="/assets/css/sidebar.css">
  <link rel="stylesheet" href="/assets/css/navbar.css">
  <link rel="stylesheet" href="/assets/css/tables.css">
  <link rel="stylesheet" href="/assets/css/forms.css">
  <link rel="stylesheet" href="/assets/css/buttons.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
  
  <!-- Tailwind CSS via Play CDN for layout construction -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#184D55',
            secondary: '#FFFFFF',
            'islamic-bg': '#F8FAF9'
          },
          fontFamily: {
            sans: ['Poppins', 'sans-serif'],
            serif: ['Amiri', 'serif']
          }
        }
      }
    }
  </script>
  <!-- Chart.js CDN for Analytics Charts -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    :root {
      --primary: #184D55;
      --secondary: #FFFFFF;
      --bg-color: #F7FAFF;
      --card-bg: #FFFFFF;
      --text-main: #184D55;
      --text-muted: rgba(24, 77, 85, 0.6);
      --border-color: rgba(24, 77, 85, 0.08);
      --gold-light: rgba(24, 77, 85, 0.08);
    }
    
    body {
      font-family: 'Poppins', sans-serif !important;
      background-color: var(--bg-color) !important;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cg fill='none' stroke='rgba%2824, 77, 85, 0.02%29' stroke-width='0.75'%3E%3Crect x='28' y='28' width='24' height='24' transform='rotate%280 40 40%29' /%3E%3Crect x='28' y='28' width='24' height='24' transform='rotate%2845 40 40%29' /%3E%3Cline x1='0' y1='40' x2='80' y2='40' /%3E%3Cline x1='40' y1='0' x2='40' y2='80' /%3E%3Cline x1='0' y1='0' x2='80' y2='80' /%3E%3Cline x1='0' y1='80' x2='80' y2='0' /%3E%3Crect x='-6' y='-6' width='12' height='12' transform='rotate%280 0 0%29' /%3E%3Crect x='-6' y='-6' width='12' height='12' transform='rotate%2845 0 0%29' /%3E%3Crect x='74' y='-6' width='12' height='12' transform='rotate%280 80 0%29' /%3E%3Crect x='74' y='-6' width='12' height='12' transform='rotate%2845 80 0%29' /%3E%3Crect x='-6' y='74' width='12' height='12' transform='rotate%280 0 80%29' /%3E%3Crect x='-6' y='74' width='12' height='12' transform='rotate%2845 0 80%29' /%3E%3Crect x='74' y='74' width='12' height='12' transform='rotate%280 80 80%29' /%3E%3Crect x='74' y='74' width='12' height='12' transform='rotate%2845 80 80%29' /%3E%3C/g%3E%3C/svg%3E") !important;
      background-attachment: fixed !important;
      background-size: 80px 80px !important;
      color: #184D55 !important;
      overflow-x: hidden;
    }
    
    

        .page-transition {
      animation: pageExchange 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes pageExchange {
      from {
        opacity: 0;
        transform: translateY(8px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    /* Premium Luxury Cards Override */
    .bg-white:not(.erp-sidebar) {
      border-radius: 24px !important;
      border: 1px solid rgba(24, 77, 85, 0.07) !important;
      box-shadow: 0 10px 40px rgba(24, 77, 85, 0.03) !important;
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .bg-white:not(.erp-sidebar):hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 40px rgba(24, 77, 85, 0.08) !important;
      border-color: rgba(24, 77, 85, 0.15) !important;
    }
    
    /* Table Redesign Styles */
    table {
      border-collapse: separate !important;
      border-spacing: 0 8px !important;
    }
    tr {
      background: transparent !important;
    }
    tbody tr {
      background: #ffffff !important;
      border-radius: 16px !important;
      box-shadow: 0 2px 8px rgba(24, 77, 85, 0.02) !important;
      transition: all 0.25s ease !important;
    }
    tbody tr:hover {
      transform: scale(1.008) !important;
      box-shadow: 0 8px 24px rgba(24, 77, 85, 0.06) !important;
      background: #ffffff !important;
    }
    td, th {
      border: none !important;
    }
    td:first-child, th:first-child {
      border-top-left-radius: 16px !important;
      border-bottom-left-radius: 16px !important;
    }
    td:last-child, th:last-child {
      border-top-right-radius: 16px !important;
      border-bottom-right-radius: 16px !important;
    }
    thead tr {
      background: transparent !important;
    }
    thead th {
      padding: 12px 20px !important;
      color: rgba(24, 77, 85, 0.5) !important;
      font-weight: 700 !important;
      font-size: 10px !important;
      letter-spacing: 0.1em !important;
    }
    tbody td {
      padding: 16px 20px !important;
    }
    
    /* Form Inputs Overrides */
    input[type="text"], input[type="email"], input[type="password"], input[type="tel"], input[type="date"], input[type="time"], input[type="number"], select:not(.bg-transparent), textarea {
      background: #F7FAFF !important;
      border: 1.5px solid rgba(24, 77, 85, 0.12) !important;
      border-radius: 14px !important;
      padding: 12px 18px !important;
      color: #184D55 !important;
      font-weight: 500 !important;
      transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
      outline: none !important;
    }
    input:focus, select:focus, textarea:focus {
      border-color: #184D55 !important;
      box-shadow: 0 0 0 4px rgba(24, 77, 85, 0.12) !important;
      background: #ffffff !important;
    }
    
    /* Button Hover & Scale Animations */
    button, .btn, a[class*="bg-primary"], button[class*="bg-primary"] {
      transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
    button:hover, .btn:hover, a[class*="bg-primary"]:hover, button[class*="bg-primary"]:hover {
      transform: translateY(-2px) !important;
      box-shadow: 0 8px 24px rgba(24, 77, 85, 0.15) !important;
    }
    button:active, .btn:active, a[class*="bg-primary"]:active, button[class*="bg-primary"]:active {
      transform: translateY(0) scale(0.97) !important;
    }
    
    /* Luxury Floating labels */
    .form-group-floating {
      position: relative;
      margin-bottom: 1.5rem;
    }

    </style>
</head>
<body class="bg-transparent text-primary selection:bg-primary selection:text-secondary min-h-screen">
<div class="flex min-h-screen w-full">
