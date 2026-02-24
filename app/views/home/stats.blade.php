<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .stats-card {
        border: none;
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .bg-gradient-primary { background: linear-gradient(45deg, #009981, #00cba9); }
    .bg-gradient-success { background: linear-gradient(45deg, #1cc88a, #36b9cc); }
    .bg-gradient-warning { background: linear-gradient(45deg, #f6c23e, #f4b619); }
    .text-brand { color: #009981; }
    .btn-filter.active {
        background-color: #009981;
        color: white;
        border-color: #009981;
    }
</style>

<div class="row g-4 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="card stats-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-uppercase text-muted fw-bold small mb-1">Tổng Doanh Thu (Thực tế)</div>
                        <div class="h3 mb-0 fw-bold text-gray-800">
                            {{ number_format($revenueStats['total_revenue'] ?? 0, 0, ',', '.') }}đ
                        </div>
                        <div class="small text-success mt-2">
                            <i class="bi bi-check-circle-fill"></i> Đã thanh toán & Hoàn thành
                        </div>
                    </div>
                    <div class="stats-icon bg-gradient-primary text-white shadow">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card stats-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-uppercase text-muted fw-bold small mb-1">Đơn Hàng Hoàn Tất</div>
                        <div class="h3 mb-0 fw-bold text-gray-800">
                            {{ $revenueStats['total_orders'] ?? 0 }}
                        </div>
                        <div class="small text-info mt-2">
                            <i class="bi bi-box-seam"></i> Đơn hàng thành công
                        </div>
                    </div>
                    <div class="stats-icon bg-gradient-success text-white shadow">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card stats-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-uppercase text-muted fw-bold small mb-1">Giá Trị Trung Bình/Đơn</div>
                        <div class="h3 mb-0 fw-bold text-gray-800">
                            @php
                                $avg = ($revenueStats['total_orders'] > 0) 
                                    ? $revenueStats['total_revenue'] / $revenueStats['total_orders'] 
                                    : 0;
                            @endphp
                            {{ number_format($avg, 0, ',', '.') }}đ
                        </div>
                        <div class="small text-warning mt-2">
                            <i class="bi bi-graph-up"></i> AOV (Average Order Value)
                        </div>
                    </div>
                    <div class="stats-icon bg-gradient-warning text-white shadow">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-brand">{{ $chartLabel }}</h6>
        <div class="btn-group btn-group-sm">
            <a href="?filter=7days" class="btn btn-outline-secondary btn-filter {{ $currentFilter == '7days' ? 'active' : '' }}">7 Ngày</a>
            <a href="?filter=30days" class="btn btn-outline-secondary btn-filter {{ $currentFilter == '30days' ? 'active' : '' }}">30 Ngày</a>
            <a href="?filter=month" class="btn btn-outline-secondary btn-filter {{ $currentFilter == 'month' ? 'active' : '' }}">Tháng</a>
            <a href="?filter=year" class="btn btn-outline-secondary btn-filter {{ $currentFilter == 'year' ? 'active' : '' }}">Năm</a>
        </div>
    </div>
    <div class="card-body">
        <div class="chart-area" style="height: 320px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chartData = @json($chartData);
        const filter = '{{ $currentFilter }}';
        
        const labels = chartData.map(item => {
            const date = new Date(item.date);
            if (filter === 'year') {
                return item.date; // Hiển thị nguyên năm
            } else if (filter === 'month') {
                // Hiển thị dạng MM/YYYY
                return date.toLocaleDateString('vi-VN', {month: '2-digit', year: 'numeric'});
            }
            // Hiển thị dạng DD/MM/YYYY cho ngày
            return date.toLocaleDateString('vi-VN', {day: '2-digit', month: '2-digit', year: 'numeric'});
        });

        const data = chartData.map(item => item.revenue);

        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(0, 153, 129, 0.2)');
        gradient.addColorStop(1, 'rgba(0, 153, 129, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: data,
                    backgroundColor: gradient,
                    borderColor: '#009981', 
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#009981',
                    pointHoverBackgroundColor: '#009981',
                    pointHoverBorderColor: '#fff',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#333',
                        bodyColor: '#333',
                        borderColor: '#ddd',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return value / 1000000 + 'M';
                                if (value >= 1000) return value / 1000 + 'k';
                                return value;
                            }
                        },
                        grid: {
                            borderDash: [2],
                            drawBorder: false,
                            color: '#f0f0f0'
                        }
                    }
                }
            }
        });
    });
</script>