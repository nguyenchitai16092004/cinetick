@extends('backend.layouts.master')
@section('title', 'Thống kê')

@section('main')
<div class="container-fluid">
    <h3 class="mb-4">📊 Thống kê theo phim</h3>

    {{-- Chọn phim --}}
    <form method="GET" class="mb-4">
        <label for="ID_Phim">Chọn phim:</label>
        <select name="ID_Phim" id="ID_Phim" class="form-control w-auto d-inline-block" onchange="this.form.submit()">
            @foreach ($phims as $phim)
                <option value="{{ $phim->ID_Phim }}" {{ $phim->ID_Phim == $selectedPhimId ? 'selected' : '' }}>
                    {{ $phim->TenPhim }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- Biểu đồ vé --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">🎟️ Số vé đã bán theo suất chiếu</div>
        <div class="card-body">
            <canvas id="ticketChart" height="100"></canvas>
        </div>
    </div>

    {{-- Biểu đồ doanh thu --}}
    <div class="card">
        <div class="card-header bg-success text-white">💰 Doanh thu theo ngày</div>
        <div class="card-body">
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ticketLabels = @json($ticketLabels);
    const ticketData = @json($ticketData);
    const ticketCtx = document.getElementById('ticketChart').getContext('2d');
    new Chart(ticketCtx, {
        type: 'bar',
        data: {
            labels: ticketLabels,
            datasets: [{
                label: 'Số vé đã bán',
                data: ticketData,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { ticks: { autoSkip: false } },
                y: { beginAtZero: true }
            }
        }
    });

    const revenueLabels = @json($revenueLabels);
    const revenueData = @json($revenueData);
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: revenueData,
                fill: true,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => value.toLocaleString('vi-VN') + ' ₫'
                    }
                }
            }
        }
    });
</script>
@stop
