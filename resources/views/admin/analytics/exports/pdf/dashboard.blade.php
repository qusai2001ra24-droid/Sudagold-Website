<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #d4af37; margin-bottom: 5px; }
        .stats { display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 30px; }
        .stat-box { 
            flex: 1; 
            min-width: 150px; 
            padding: 15px; 
            background: #f9f9f9; 
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .stat-label { font-size: 12px; color: #666; }
        .stat-value { font-size: 24px; font-weight: bold; color: #333; }
        .footer { 
            margin-top: 30px; 
            text-align: center; 
            font-size: 10px; 
            color: #999; 
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sudagold Analytics</h1>
        <p>{{ $title }}</p>
        <p>Generated on {{ now()->format('F d, Y') }}</p>
    </div>
    
    <div class="stats">
        <div class="stat-box">
            <div class="stat-label">Today's Sales</div>
            <div class="stat-value">${{ number_format($stats['todaySales'], 2) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Today's Orders</div>
            <div class="stat-value">{{ $stats['todayOrders'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Monthly Revenue</div>
            <div class="stat-value">${{ number_format($stats['monthlyRevenue'], 2) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Yearly Revenue</div>
            <div class="stat-value">${{ number_format($stats['yearlyRevenue'], 2) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Products</div>
            <div class="stat-value">{{ $stats['totalProducts'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Customers</div>
            <div class="stat-value">{{ $stats['totalCustomers'] }}</div>
        </div>
    </div>
    
    <div class="footer">
        <p>Sudagold - Analytics Report</p>
    </div>
</body>
</html>
