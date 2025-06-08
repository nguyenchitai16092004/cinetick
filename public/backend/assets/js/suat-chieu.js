$(document).ready(function() {
    // Vô hiệu hóa dropdown phòng chiếu khi trang tải
    $('#ID_PhongChieu').prop('disabled', true);
    
    // Ẩn tất cả các phòng chiếu (ngoại trừ option đầu tiên)
    $('#ID_PhongChieu option:not(:first)').hide();

    // Lọc phòng chiếu theo rạp khi thay đổi rạp
    $('#ID_Rap').change(function() {
        var rapId = $(this).val();

        // Reset phòng chiếu về giá trị mặc định
        $('#ID_PhongChieu').val('');

        if (rapId) {
            // Kích hoạt dropdown phòng chiếu
            $('#ID_PhongChieu').prop('disabled', false);
            
            // Ẩn tất cả các phòng chiếu
            $('#ID_PhongChieu option').hide();
            
            // Hiện option "-- Chọn phòng chiếu --"
            $('#ID_PhongChieu option:first').show();
            
            // Hiện các phòng chiếu thuộc rạp đã chọn
            $('#ID_PhongChieu option[data-rap="' + rapId + '"]').show();
        } else {
            // Nếu không chọn rạp, vô hiệu hóa và ẩn tất cả các phòng chiếu
            $('#ID_PhongChieu').prop('disabled', true);
            $('#ID_PhongChieu option:not(:first)').hide();
        }
    });

    // Khi chọn phòng chiếu, kiểm tra xem phòng có thuộc rạp đã chọn không
    $('#ID_PhongChieu').change(function() {
        if ($(this).val()) {
            var phongRapId = $('#ID_PhongChieu option:selected').data('rap');
            var selectedRapId = $('#ID_Rap').val();
            
            // Nếu phòng chiếu không thuộc rạp đã chọn, đặt lại giá trị
            if (phongRapId != selectedRapId) {
                $(this).val('');
                alert('Vui lòng chọn phòng chiếu thuộc rạp đã chọn');
            }
        }
    });

    // Khởi tạo ban đầu: nếu đã có rạp được chọn (ví dụ khi edit), kích hoạt sự kiện change
    if ($('#ID_Rap').val()) {
        $('#ID_Rap').trigger('change');
    }
});