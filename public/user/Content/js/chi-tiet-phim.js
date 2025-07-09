// Tạo các hiệu ứng hạt động trên nền hero
function createParticles() {
    const particlesContainer = document.querySelector(".hero-particles");
    const particleCount = 50;

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement("div");
        particle.className = "particle";

        // Kích thước và vị trí ngẫu nhiên
        const size = Math.random() * 4 + 1;
        particle.style.width = size + "px";
        particle.style.height = size + "px";
        particle.style.left = Math.random() * 100 + "%";
        particle.style.animationDelay = Math.random() * 15 + "s";
        particle.style.animationDuration = Math.random() * 10 + 10 + "s";

        particlesContainer.appendChild(particle);
    }
}
createParticles();

// Hiệu ứng fade-in cho các phần tử khi xuất hiện trên màn hình
const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
};
const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = "1";
            entry.target.style.transform = "translateY(0)";
        }
    });
}, observerOptions);

// Áp dụng fade-in cho các phần tử có class .fade-in-up
document.querySelectorAll(".fade-in-up").forEach((el) => {
    el.style.opacity = "0";
    el.style.transform = "translateY(50px)";
    observer.observe(el);
});

// Hiệu ứng ripple cho nút suất chiếu
document.querySelectorAll(".showtime-btn").forEach((btn) => {
    btn.addEventListener("click", function (e) {
        // Tạo hiệu ứng ripple
        const ripple = document.createElement("span");
        ripple.style.position = "absolute";
        ripple.style.borderRadius = "50%";
        ripple.style.background = "rgba(255, 255, 255, 0.6)";
        ripple.style.transform = "scale(0)";
        ripple.style.animation = "ripple 0.6s linear";
        ripple.style.left = "50%";
        ripple.style.top = "50%";
        ripple.style.width = "20px";
        ripple.style.height = "20px";
        ripple.style.marginLeft = "-10px";
        ripple.style.marginTop = "-10px";

        this.style.position = "relative";
        this.appendChild(ripple);

        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
});

// Thêm CSS cho hiệu ứng ripple động
const style = document.createElement("style");
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Xử lý popup trailer phim
const trailerBtn = document.getElementById("trailerBtn");
const trailerModal = document.getElementById("trailerModal");
const trailerVideo = document.getElementById("trailerVideo");
const closeTrailer = document.getElementById("closeTrailer");
const mainContent = document.getElementById("mainContent");

// Mở popup trailer
trailerBtn.addEventListener("click", function (e) {
    e.preventDefault();
    trailerModal.classList.add("active");
    trailerVideo.src = trailerUrl;
    mainContent.classList.add("page-blur");
    document.body.style.overflow = "hidden";
});

// Đóng popup trailer
function closeTrailerModal() {
    trailerModal.classList.remove("active");
    trailerVideo.src = "";
    mainContent.classList.remove("page-blur");
    document.body.style.overflow = "auto";
}
closeTrailer.addEventListener("click", closeTrailerModal);
trailerModal.addEventListener("click", function (e) {
    if (e.target === trailerModal) {
        closeTrailerModal();
    }
});
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && trailerModal.classList.contains("active")) {
        closeTrailerModal();
    }
});

// Hiệu ứng parallax cho nền hero khi scroll
window.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset;
    const heroBackground = document.querySelector(".hero-bg");
    if (heroBackground) {
        heroBackground.style.transform = `translateY(${scrolled * 0.5}px)`;
    }
});


window.initRatingModal = function (options) {
    let yourRating = 0;

    // Cập nhật số sao active dựa vào số điểm
    function updateStars(val) {
        document
            .querySelectorAll("#starContainer .rating-star")
            .forEach(function (el) {
                el.classList.toggle(
                    "active",
                    parseFloat(el.dataset.value) <= val
                );
            });
    }



    // Khi mở popup đánh giá, kiểm tra quyền đánh giá và hiển thị thông báo phù hợp
    document
        .getElementById("openRatingModal")
        .addEventListener("click", function () {
            fetch(options.canRateUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": options.csrf,
                },
                body: JSON.stringify({ id_phim: options.idPhim }),
            })
                .then((res) => res.json())
                .then((res) => {
                    const msg = document.getElementById("ratingMsg");
                    msg.textContent = res.message || "";
                    if (res.allow) {
                        msg.style.color = "green";
                        document.getElementById("ratingModal").style.display =
                            "flex";
                    } else {
                        msg.style.color = "red";
                        document.getElementById("ratingModal").style.display =
                            "flex";
                        // Nếu không cho đánh giá thì disable các nút và input
                        document.getElementById(
                            "starContainer"
                        ).style.pointerEvents = "none";
                        document.getElementById(
                            "customRatingInput"
                        ).disabled = true;
                        document.getElementById(
                            "sendRatingBtn"
                        ).disabled = true;
                    }
                });
        });

    // Đóng popup đánh giá và reset trạng thái cho phép nhập khi mở lại
    function closeRatingModal() {
        document.getElementById("ratingModal").style.display = "none";
        document.getElementById("ratingMsg").textContent = "";
        document.getElementById("starContainer").style.pointerEvents = "auto";
        document.getElementById("customRatingInput").disabled = false;
        document.getElementById("sendRatingBtn").disabled = false;
    }
    document.getElementById("cancelRatingModal").onclick =
        document.getElementById("closeRatingModal").onclick = closeRatingModal;

    // Xử lý nhập số điểm thủ công bằng input (0-10, số thập phân, chỉ dấu .)
    document
        .getElementById("customRatingInput")
        .addEventListener("input", function () {
            let val = this.value.replace(/[^0-9.]/g, "");
            const parts = val.split(".");
            if (parts.length > 2)
                val = parts[0] + "." + parts.slice(1).join("");
            if (val.startsWith(".")) val = "0" + val;
            let floatVal = parseFloat(val);
            if (!isNaN(floatVal)) {
                if (floatVal > 10) val = "10";
                if (floatVal < 0) val = "0";
            }
            this.value = val;
            yourRating = parseFloat(val) || 0;
            updateStars(yourRating);
        });
    // Chặn nhập dấu phẩy và ký tự e
    document
        .getElementById("customRatingInput")
        .addEventListener("keydown", function (e) {
            if (e.key === "," || e.key === "e") e.preventDefault();
        });

    // Xử lý khi click/chọn sao đánh giá
    document
        .querySelectorAll("#starContainer .rating-star")
        .forEach(function (star) {
            star.addEventListener("mouseenter", function () {
                updateStars(star.dataset.value);
            });
            star.addEventListener("mouseleave", function () {
                updateStars(yourRating);
            });
            star.addEventListener("click", function () {
                yourRating = parseFloat(star.dataset.value);
                updateStars(yourRating);
                document.getElementById("customRatingInput").value = yourRating;
            });
        });

    // Gửi đánh giá lên server
    document.getElementById("sendRatingBtn").onclick = function () {
        const msg = document.getElementById("ratingMsg");
        if (yourRating === 0) {
            msg.style.color = "red";
            msg.textContent = "Bạn phải chọn số sao hoặc nhập điểm!";
            return;
        }
        fetch(options.sendRatingUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": options.csrf,
            },
            body: JSON.stringify({ id_phim: options.idPhim, diem: yourRating }),
        })
            .then((res) => res.json())
            .then((res) => {
                msg.style.color = res.success ? "green" : "red";
                msg.textContent = res.message || "";
                if (res.success) {
                    setTimeout(closeRatingModal, 1200);
                }
            });
    };

    // Đóng popup khi click ra ngoài vùng modal
    document.getElementById("ratingModal").onclick = function (e) {
        if (e.target === this) closeRatingModal();
    };

};

// Sự kiện DOMContentLoaded để đảm bảo các nút đóng popup hoạt động
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("openRatingModal").onclick = function () {
        document.getElementById("ratingModal").style.display = "flex";
    };
    document.getElementById("closeRatingModal").onclick = function () {
        document.getElementById("ratingModal").style.display = "none";
    };
    document.getElementById("cancelRatingModal").onclick = function () {
        document.getElementById("ratingModal").style.display = "none";
    };
    document.getElementById("ratingModal").onclick = function (e) {
        if (e.target === this) this.style.display = "none";
    };
});
