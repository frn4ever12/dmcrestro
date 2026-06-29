<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'POS - Restaurant Management System')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            overflow: hidden;
            height: 100vh;
        }

        .pos-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            background: #fff;
        }

        /* POS Header */
        .pos-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            flex-shrink: 0;
        }

        .pos-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 24px;
            font-weight: 700;
        }

        .pos-logo i {
            font-size: 28px;
        }

        .pos-nav {
            display: flex;
            gap: 5px;
        }

        .nav-btn {
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .nav-btn:hover {
            background: rgba(255,255,255,0.2);
        }

        .nav-btn.active {
            background: white;
            color: #667eea;
        }

        .nav-btn i {
            font-size: 16px;
        }

        .exit-btn {
            background: rgba(220, 53, 69, 0.8);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .exit-btn:hover {
            background: rgba(220, 53, 69, 1);
        }

        /* Order Type Bar */
        .order-type-bar {
            background: #f8f9fa;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
            flex-shrink: 0;
        }

        .order-type-buttons {
            display: flex;
            gap: 8px;
        }

        .order-type-btn {
            background: white;
            border: 2px solid #e0e0e0;
            color: #333;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .order-type-btn:hover {
            border-color: #667eea;
        }

        .order-type-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }

        .order-type-btn i {
            font-size: 14px;
        }

        .table-btn {
            background: white;
            border: 2px solid #667eea;
            color: #667eea;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .table-btn:hover {
            background: #667eea;
            color: white;
        }

        .table-btn i {
            font-size: 14px;
        }

        /* Category Filters */
        .category-filters {
            background: white;
            padding: 10px 20px;
            display: flex;
            gap: 8px;
            border-bottom: 1px solid #e0e0e0;
            flex-shrink: 0;
        }

        .filter-btn {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            color: #333;
            padding: 6px 14px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .filter-btn:hover {
            background: #e9ecef;
        }

        .filter-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }

        /* Table Modal */
        .table-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
            padding: 20px;
        }

        .table-card {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .table-card:hover {
            border-color: #667eea;
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }

        .table-card.selected {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }

        .table-icon {
            font-size: 32px;
            margin-bottom: 10px;
            color: #667eea;
        }

        .table-card.selected .table-icon {
            color: white;
        }

        .table-number {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .table-status {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 10px;
            display: inline-block;
        }

        .table-status.available {
            background: #d4edda;
            color: #155724;
        }

        .table-card.selected .table-status.available {
            background: rgba(255,255,255,0.3);
            color: white;
        }

        .table-status.occupied {
            background: #f8d7da;
            color: #721c24;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .pos-left-panel {
                width: 180px;
            }

            .pos-right-panel {
                width: 320px;
            }

            .food-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }

            .nav-btn span, .exit-btn span {
                display: none;
            }

            .nav-btn i, .exit-btn i {
                font-size: 18px;
            }
        }

        @media (max-width: 992px) {
            .pos-left-panel {
                width: 150px;
            }

            .pos-right-panel {
                width: 280px;
            }

            .food-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }

            .category-item {
                padding: 10px;
            }

            .category-item .name {
                font-size: 13px;
            }

            .order-type-btn span, .table-btn span {
                font-size: 12px;
            }

            .table-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .pos-main {
                flex-direction: column;
            }

            .pos-left-panel {
                width: 100%;
                height: auto;
                max-height: 200px;
                overflow-y: auto;
            }

            .pos-right-panel {
                width: 100%;
                height: auto;
                max-height: 300px;
            }

            .pos-center-panel {
                flex: 1;
            }

            .food-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            }

            .pos-header {
                padding: 10px 15px;
            }

            .pos-logo {
                font-size: 18px;
            }

            .pos-logo i {
                font-size: 22px;
            }

            .nav-btn, .exit-btn {
                padding: 8px 12px;
            }

            .order-type-bar {
                flex-direction: column;
                gap: 10px;
            }

            .order-type-buttons {
                width: 100%;
                justify-content: center;
            }

            .table-selection {
                width: 100%;
                justify-content: center;
            }

            .category-filters {
                flex-wrap: wrap;
            }

            .filter-btn {
                font-size: 11px;
                padding: 5px 10px;
            }

            .pos-bottom-actions {
                flex-wrap: wrap;
                gap: 8px;
            }

            .action-btn {
                flex: 1;
                min-width: 100px;
                font-size: 12px;
                padding: 10px;
            }
        }

        @media (max-width: 576px) {
            .pos-header {
                flex-direction: column;
                gap: 10px;
            }

            .pos-nav {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }

            .pos-exit {
                width: 100%;
            }

            .exit-btn {
                width: 100%;
                justify-content: center;
            }

            .food-grid {
                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
                gap: 8px;
            }

            .food-item {
                padding: 8px;
            }

            .food-item-image {
                height: 60px;
            }

            .food-item-name {
                font-size: 11px;
            }

            .food-item-price {
                font-size: 12px;
            }

            .order-item-name {
                font-size: 13px;
            }

            .table-grid {
                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
                gap: 10px;
                padding: 10px;
            }

            .table-card {
                padding: 15px;
            }

            .table-icon {
                font-size: 24px;
            }

            .table-number {
                font-size: 12px;
            }
        }

        /* Main Content */
        .pos-main {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* Left Panel - Categories */
        .pos-left-panel {
            width: 220px;
            background: #f8f9fa;
            border-right: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .category-search {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
        }

        .category-search input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 13px;
        }

        .category-list {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
        }

        .category-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            margin-bottom: 5px;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }

        .category-item:hover {
            background: #e9ecef;
            transform: translateX(3px);
        }

        .category-item.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }

        .category-item .icon {
            font-size: 24px;
        }

        .category-item .name {
            font-weight: 600;
            font-size: 14px;
        }

        /* Center Panel - Food Items */
        .pos-center-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #fff;
        }

        .pos-center-header {
            padding: 10px 15px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pos-center-header h2 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .item-search {
            display: flex;
            gap: 10px;
        }

        .item-search input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 13px;
            width: 250px;
        }

        .food-grid {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
        }

        .food-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .food-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            border-color: #667eea;
        }

        .food-item.outrofstock {
            opacity: 0.5;
            pointer-events: none;
        }

        .food-item-image {
            width: 100%;
            height: 100px;
            object-fit: cover;
            background: #f0f0f0;
        }

        .food-item-details {
            padding: 10px;
        }

        .food-item-name {
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .food-item-price {
            font-size: 15px;
            font-weight: 700;
            color: #667eea;
        }

        .food-item-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 5px;
            font-size: 11px;
        }

        .stock-status {
            color: #28a745;
            font-weight: 500;
        }

        .stock-status.low {
            color: #ffc107;
        }

        .stock-status.out {
            color: #dc3545;
        }

        .veg-icon {
            width: 16px;
            height: 16px;
            border: 2px solid #28a745;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #28a745;
        }

        .veg-icon.non-veg {
            border-color: #dc3545;
            color: #dc3545;
        }

        /* Right Panel - Current Order */
        .pos-right-panel {
            width: 350px;
            background: #f8f9fa;
            border-left: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .order-header {
            padding: 12px 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-header h3 {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }

        .table-dropdown {
            min-width: 120px;
        }

        .table-dropdown select {
            background: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            color: #333;
        }

        .table-dropdown select:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.3);
        }

        .order-items {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
        }

        .order-item {
            background: white;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .order-item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .order-item-name {
            font-size: 13px;
            font-weight: 600;
            color: #333;
            flex: 1;
        }

        .order-item-remove {
            color: #dc3545;
            cursor: pointer;
            font-size: 14px;
        }

        .order-item-qty {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .qty-btn {
            width: 28px;
            height: 28px;
            border: none;
            background: #667eea;
            color: white;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover {
            background: #5568d3;
        }

        .qty-value {
            font-size: 16px;
            font-weight: 600;
            min-width: 30px;
            text-align: center;
        }

        .order-item-price {
            font-size: 14px;
            font-weight: 600;
            color: #667eea;
        }

        .order-item-note {
            font-size: 11px;
            color: #666;
            background: #f0f0f0;
            padding: 5px 8px;
            border-radius: 4px;
        }

        .order-summary {
            padding: 15px;
            background: white;
            border-top: 1px solid #e0e0e0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 13px;
        }

        .summary-row.total {
            font-size: 16px;
            font-weight: 700;
            color: #667eea;
            border-top: 2px solid #e0e0e0;
            padding-top: 10px;
            margin-top: 5px;
        }

        /* Bottom Action Buttons */
        .pos-bottom-bar {
            padding: 10px 15px;
            background: #f8f9fa;
            border-top: 1px solid #e0e0e0;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .action-btn {
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
            min-width: 100px;
            justify-content: center;
        }

        .action-btn i {
            font-size: 14px;
        }

        .action-btn.primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .action-btn.primary:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .action-btn.success {
            background: #28a745;
            color: white;
        }

        .action-btn.warning {
            background: #ffc107;
            color: #333;
        }

        .action-btn.danger {
            background: #dc3545;
            color: white;
        }

        .action-btn.info {
            background: #17a2b8;
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        /* Payment Modal */
        .payment-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .payment-modal.show {
            display: flex;
        }

        .payment-modal-content {
            background: white;
            border-radius: 16px;
            width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .payment-modal-header {
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 16px 16px 0 0;
        }

        .payment-modal-header h3 {
            font-size: 20px;
            font-weight: 600;
        }

        .payment-modal-body {
            padding: 20px;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .payment-method {
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .payment-method:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }

        .payment-method.active {
            border-color: #667eea;
            background: #667eea;
            color: white;
        }

        .payment-method i {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .payment-method span {
            font-size: 12px;
            font-weight: 500;
        }

        .payment-amount {
            margin-bottom: 20px;
        }

        .payment-amount label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 13px;
        }

        .payment-amount input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
        }

        .payment-amount input:focus {
            outline: none;
            border-color: #667eea;
        }

        .change-display {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .change-display .label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .change-display .amount {
            font-size: 24px;
            font-weight: 700;
            color: #28a745;
        }

        .payment-modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            gap: 10px;
        }

        .payment-modal-footer button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .payment-modal-footer .btn-cancel {
            background: #f0f0f0;
            color: #333;
        }

        .payment-modal-footer .btn-confirm {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

        /* Responsive */
        @media (max-width: 1400px) {
            .pos-left-panel {
                width: 180px;
            }
            
            .pos-right-panel {
                width: 300px;
            }
            
            .food-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="pos-container">
        @yield('content')
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
