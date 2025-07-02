// Xử lý upload ảnh và hiện ảnh preview
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

let isContactSubmitting = false;

document.getElementById("contactForm").addEventListener("submit", function (e) {
    if (isContactSubmitting) return;

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

    isContactSubmitting = true;
    if (typeof showLoading === "function") showLoading();
});