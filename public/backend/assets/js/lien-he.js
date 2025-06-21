// Xác nhận xử lý
document.querySelectorAll('.btn-xuly').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Bạn chắc chắn?',
            text: "Đánh dấu đã xử lý liên hệ này?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.closest('form').submit();
            }
        });
    });
});

// Xác nhận xóa
document.querySelectorAll('.btn-xoa').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Bạn chắc chắn?',
            text: "Xóa liên hệ này? Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.closest('form').submit();
            }
        });
    });
});
