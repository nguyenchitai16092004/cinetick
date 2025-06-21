// Xử lý upload ảnh và hiện ảnh preview
document.getElementById('imageUpload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const img = document.getElementById('uploadedImage');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
            img.src = evt.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        img.src = '';
        img.style.display = 'none';
    }
});

document.getElementById("imageUpload").addEventListener("change", function (e) {
    const file = e.target.files[0];
    const img = document.getElementById("uploadedImage");
    if (file) {
        const reader = new FileReader();
        reader.onload = function (evt) {
            img.src = evt.target.result;
            img.style.display = "block";
        };
        reader.readAsDataURL(file);
    } else {
        img.src = "";
        img.style.display = "none";
    }
});

// Kiểm tra reCAPTCHA trước khi submit (có thể dùng hoặc bỏ, Google đã kiểm phía server)
document.getElementById("contactForm").addEventListener("submit", function (e) {
    var recaptcha = grecaptcha.getResponse();
    if (recaptcha.length === 0) {
        e.preventDefault();
        Swal.fire({
            icon: "warning",
            title: "Vui lòng xác nhận",
            text: 'Bạn chưa xác nhận "Tôi không phải là robot"!',
        });
        return;
    }

    e.preventDefault(); 

    Swal.fire({
        icon: "question",
        title: "Xác nhận gửi liên hệ",
        text: "Khi bạn xác nhận gửi liên hệ này, bạn sẽ nhận được một bản sao email với nội dung bạn đã gửi. Vui lòng không trả lời email này! Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất. Đội ngũ CineTick xin cảm ơn!",
        showCancelButton: true,
        confirmButtonText: "Xác nhận gửi",
        cancelButtonText: "Hủy",
    }).then((result) => {
        if (result.isConfirmed) {
            e.target.submit(); // Gửi form nếu xác nhận
        }
    });
});