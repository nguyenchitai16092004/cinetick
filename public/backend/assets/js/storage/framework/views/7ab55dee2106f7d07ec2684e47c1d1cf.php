
<?php $__env->startSection('title', 'Thống kê theo tháng'); ?>

<?php $__env->startSection('css'); ?>
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
        margin-right: 10px;
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📊 Thống kê doanh thu theo tháng</h2>
        <button class="btn btn-success" onclick="exportData()">
            <i class="fas fa-download"></i> Xuất Excel
        </button>
    </div>

    
    <div class="year-selector">
        <div class="row align-items-center">
            <div class="col-md-3">
                <label for="year-select" class="form-label fw-bold">📅 Chọn năm:</label>
                <select id="year-select" class="form-select" onchange="changeYear()">
                    <?php for($i = 2020; $i <= date('Y') + 1; $i++): ?>
                        <option value="<?php echo e($i); ?>" <?php echo e($i == $selectedYear ? 'selected' : ''); ?>>
                            <?php echo e($i); ?>

                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-9">
                <div class="d-flex justify-content-end">
                    <div class="stats-card me-3" style="min-width: 200px;">
                        <div class="stats-number"><?php echo e(number_format($doanhThuTheoPhim->sum(function($phim) { return $phim->sum('tong_doanh_thu'); }))); ?></div>
                        <div>Tổng doanh thu năm <?php echo e($selectedYear); ?></div>
                    </div>
                    <div class="stats-card" style="min-width: 200px;">
                        <div class="stats-number"><?php echo e($doanhThuTheoPhim->sum(function($phim) { return $phim->sum('so_ve_ban'); })); ?></div>
                        <div>Tổng vé bán năm <?php echo e($selectedYear); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="chart-container">
        <h4 class="mb-4">💰 Doanh thu theo từng phim trong năm <?php echo e($selectedYear); ?></h4>
        <canvas id="revenueChart" height="100"></canvas>
    </div>

    
    <div class="table-responsive">
        <h4 class="mb-4">🎟️ Chi tiết số vé bán theo suất chiếu</h4>
        
        
        <ul class="nav nav-pills month-tab" id="monthTabs" role="tablist">
            <?php for($i = 1; $i <= 12; $i++): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo e($i == 1 ? 'active' : ''); ?>" 
                            id="month-<?php echo e($i); ?>-tab" 
                            data-bs-toggle="pill" 
                            data-bs-target="#month-<?php echo e($i); ?>" 
                            type="button" 
                            role="tab">
                        Tháng <?php echo e($i); ?>

                    </button>
                </li>
            <?php endfor; ?>
        </ul>

        
        <div class="tab-content" id="monthTabsContent">
            <?php for($month = 1; $month <= 12; $month++): ?>
                <div class="tab-pane fade <?php echo e($month == 1 ? 'show active' : ''); ?>" 
                     id="month-<?php echo e($month); ?>" 
                     role="tabpanel">
                     
                    <h5 class="mb-3">📋 Chi tiết tháng <?php echo e($month); ?>/<?php echo e($selectedYear); ?></h5>
                    
                    <?php if($soVeTheoSuatChieu->isNotEmpty()): ?>
                        <?php $__currentLoopData = $soVeTheoSuatChieu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenPhim => $thangData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(isset($thangData[$month]) && $thangData[$month]->isNotEmpty()): ?>
                                <div class="mb-4">
                                    <h6 class="text-primary">🎬 <?php echo e($tenPhim); ?></h6>
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
                                                <?php $__currentLoopData = $thangData[$month]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $suatChieu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e(date('d/m/Y', strtotime($suatChieu->NgayChieu))); ?></td>
                                                        <td><?php echo e(date('H:i', strtotime($suatChieu->GioChieu))); ?></td>
                                                        <td><?php echo e($suatChieu->TenRap); ?></td>
                                                        <td><?php echo e($suatChieu->TenPhongChieu); ?></td>
                                                        <td>
                                                            <span class="badge bg-info"><?php echo e($suatChieu->so_ve_ban); ?> vé</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success"><?php echo e(number_format($suatChieu->doanh_thu_suat)); ?> đ</span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                            <tfoot class="table-secondary">
                                                <tr>
                                                    <th colspan="4">Tổng phim <?php echo e($tenPhim); ?> - Tháng <?php echo e($month); ?></th>
                                                    <th><?php echo e($thangData[$month]->sum('so_ve_ban')); ?> vé</th>
                                                    <th><?php echo e(number_format($thangData[$month]->sum('doanh_thu_suat'))); ?> đ</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Không có dữ liệu cho tháng <?php echo e($month); ?>/<?php echo e($selectedYear); ?>

                        </div>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    
    <div class="table-responsive">
        <h4 class="mb-4">📈 Tổng kết doanh thu theo phim năm <?php echo e($selectedYear); ?></h4>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Tên phim</th>
                    <?php for($i = 1; $i <= 12; $i++): ?>
                        <th>T<?php echo e($i); ?></th>
                    <?php endfor; ?>
                    <th>Tổng doanh thu</th>
                    <th>Tổng vé bán</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $doanhThuTheoPhim; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phimId => $thangData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $tenPhim = $thangData->first()->TenPhim;
                        $tongDoanhThu = $thangData->sum('tong_doanh_thu');
                        $tongVeBan = $thangData->sum('so_ve_ban');
                    ?>
                    <tr>
                        <td class="fw-bold"><?php echo e($tenPhim); ?></td>
                        <?php for($month = 1; $month <= 12; $month++): ?>
                            <?php
                                $monthData = $thangData->where('thang', $month)->first();
                                $doanhThuThang = $monthData ? $monthData->tong_doanh_thu : 0;
                            ?>
                            <td>
                                <?php if($doanhThuThang > 0): ?>
                                    <span class="badge bg-success"><?php echo e(number_format($doanhThuThang/1000)); ?>k</span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                        <td>
                            <span class="badge bg-primary fs-6"><?php echo e(number_format($tongDoanhThu)); ?> đ</span>
                        </td>
                        <td>
                            <span class="badge bg-info fs-6"><?php echo e($tongVeBan); ?> vé</span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dữ liệu biểu đồ
    const chartData = <?php echo json_encode($chartData, 15, 512) ?>;
    
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
                    text: 'Doanh thu theo từng phim trong năm <?php echo e($selectedYear); ?>'
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
                        text: 'Doanh thu (VNĐ)'
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
        window.location.href = `<?php echo e(route('thong-ke.index')); ?>?year=${year}`;
    }
    
    // Xuất Excel
    function exportData() {
        const year = document.getElementById('year-select').value;
        window.open(`<?php echo e(route('thong-ke.export')); ?>?year=${year}`, '_blank');
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\GitClone\cinetick\resources\views/admin/pages/thong_ke/thong-ke-doanh-thu.blade.php ENDPATH**/ ?>