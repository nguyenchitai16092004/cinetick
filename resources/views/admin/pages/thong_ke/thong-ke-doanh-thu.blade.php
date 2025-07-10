@extends('admin.layouts.master')
@section('title', 'Thống kê theo tháng')

@section('css')
    <style>
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .table-responsive {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .month-tab {
            margin-bottom: 20px;
        }

        .month-tab .nav-link {
            border-radius: 20px;
            margin-right: 8px;
            font-weight: 500;
        }

        .month-tab .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .year-selector {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
    </style>
@endsection

@section('main')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-chart-simple"></i> Thống kê doanh thu theo tháng</h2>
            <button class="btn btn-success" onclick="exportData()">
                <i class="fas fa-download"></i> Xuất Excel
            </button>
        </div>

        {{-- Chọn năm --}}
        <div class="year-selector">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label for="year-select" class="form-label fw-bold"><i class="fas fa-calendar-days"></i> Chọn
                        năm:</label>
                    <select id="year-select" class="form-select" onchange="changeYear()">
                        @for ($i = 2020; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" {{ $i == $selectedYear ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-9">
                    <div class="d-flex justify-content-end">
                        <div class="stats-card me-3" style="min-width: 200px;">
                            <div class="stats-number">
                                {{ number_format($doanhThuTheoPhim->sum(function ($phim) {return $phim->sum('tong_doanh_thu');})) }}đ
                            </div>
                            <div>Tổng doanh thu năm {{ $selectedYear }}</div>
                        </div>
                        <div class="stats-card" style="min-width: 200px;">
                            <div class="stats-number">
                                {{ $doanhThuTheoPhim->sum(function ($phim) {return $phim->sum('so_ve_ban');}) }}</div>
                            <div>Tổng vé bán năm {{ $selectedYear }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Biểu đồ doanh thu theo phim --}}
        <div class="chart-container">
            <h4 class="mb-4"><i class="fas fa-sack-dollar"></i> Doanh thu theo từng phim trong năm {{ $selectedYear }}
            </h4>
            <canvas id="revenueChart" height="100"></canvas>
        </div>

        {{-- Tab theo tháng --}}
        <div class="table-responsive">
            <h4 class="mb-4"><i class="fas fa-ticket"></i> Chi tiết số vé bán theo suất chiếu</h4>

            {{-- Tab navigation --}}
            <ul class="nav nav-pills month-tab" id="monthTabs" role="tablist">
                @for ($i = 1; $i <= 12; $i++)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $i == 1 ? 'active' : '' }}" id="month-{{ $i }}-tab"
                            data-bs-toggle="pill" data-bs-target="#month-{{ $i }}" type="button"
                            role="tab">
                            Tháng {{ $i }}
                        </button>
                    </li>
                @endfor
            </ul>

            {{-- Tab content --}}
            <div class="tab-content" id="monthTabsContent">
                @for ($month = 1; $month <= 12; $month++)
                    <div class="tab-pane fade {{ $month == 1 ? 'show active' : '' }}" id="month-{{ $month }}"
                        role="tabpanel">

                        <h5 class="mb-3">Chi tiết tháng {{ $month }}/{{ $selectedYear }}</h5>

                        @if ($soVeTheoSuatChieu->isNotEmpty())
                            @foreach ($soVeTheoSuatChieu as $tenPhim => $thangData)
                                @if (isset($thangData[$month]) && $thangData[$month]->isNotEmpty())
                                    <div class="mb-4">
                                        <h6 class="text-primary">🎬 {{ $tenPhim }}</h6>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Ngày chiếu</th>
                                                        <th>Giờ chiếu</th>
                                                        <th>Rạp</th>
                                                        <th>Phòng</th>
                                                        <th>Số vé bán</th>
                                                        <th>Doanh thu suất</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($thangData[$month] as $suatChieu)
                                                        <tr>
                                                            <td>{{ date('d/m/Y', strtotime($suatChieu->NgayChieu)) }}</td>
                                                            <td>{{ date('H:i', strtotime($suatChieu->GioChieu)) }}</td>
                                                            <td>{{ $suatChieu->TenRap }}</td>
                                                            <td>{{ $suatChieu->TenPhongChieu }}</td>
                                                            <td>
                                                                <span class="badge bg-info">{{ $suatChieu->so_ve_ban }}
                                                                    vé</span>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-success">{{ number_format($suatChieu->doanh_thu_suat) }}
                                                                    đ</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot class="table-secondary">
                                                    <tr>
                                                        <th colspan="4">Tổng phim {{ $tenPhim }} - Tháng
                                                            {{ $month }}</th>
                                                        <th>{{ $thangData[$month]->sum('so_ve_ban') }} vé</th>
                                                        <th>{{ number_format($thangData[$month]->sum('doanh_thu_suat')) }}
                                                            đ</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Không có dữ liệu cho tháng
                                {{ $month }}/{{ $selectedYear }}
                            </div>
                        @endif
                    </div>
                @endfor
            </div>
        </div>

        {{-- Bảng tổng kết theo phim --}}
        <div class="table-responsive">
            <h4 class="mb-4">Tổng kết doanh thu theo phim năm {{ $selectedYear }}</h4>
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Tên phim</th>
                        @for ($i = 1; $i <= 12; $i++)
                            <th>T{{ $i }}</th>
                        @endfor
                        <th>Tổng doanh thu</th>
                        <th>Tổng vé bán</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($doanhThuTheoPhim as $phimId => $thangData)
                        @php
                            $tenPhim = $thangData->first()->TenPhim;
                            $tongDoanhThu = $thangData->sum('tong_doanh_thu');
                            $tongVeBan = $thangData->sum('so_ve_ban');
                        @endphp
                        <tr>
                            <td class="fw-bold">{{ $tenPhim }}</td>
                            @for ($month = 1; $month <= 12; $month++)
                                @php
                                    $monthData = $thangData->where('thang', $month)->first();
                                    $doanhThuThang = $monthData ? $monthData->tong_doanh_thu : 0;
                                @endphp
                                <td>
                                    @if ($doanhThuThang > 0)
                                        <span class="badge bg-success">{{ number_format($doanhThuThang / 1000) }}k</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endfor
                            <td>
                                <span class="badge bg-primary fs-6">{{ number_format($tongDoanhThu) }} đ</span>
                            </td>
                            <td>
                                <span class="badge bg-info fs-6">{{ $tongVeBan }} vé</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Dữ liệu biểu đồ
        const chartData = @json($chartData);

        // Biểu đồ doanh thu
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: chartData.revenueData
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Doanh thu theo từng phim trong năm {{ $selectedYear }}'
                    },
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Tháng'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Doanh thu (đ)'
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + ' đ';
                            }
                        }
                    }
                }
            }
        });

        // Thay đổi năm
        function changeYear() {
            const year = document.getElementById('year-select').value;
            window.location.href = `{{ route('thong-ke.index') }}?year=${year}`;
        }

        // Xuất Excel
        function exportData() {
            const year = document.getElementById('year-select').value;
            window.open(`{{ route('thong-ke.export') }}?year=${year}`, '_blank');
        }
    </script>
@endsection
