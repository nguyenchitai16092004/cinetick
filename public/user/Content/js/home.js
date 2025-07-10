
document.addEventListener("DOMContentLoaded", function () {
    // Lấy các phần tử cần thiết cho đặt vé
    const theaterBtn = document.getElementById("theater-btn");
    const theaterContent = document.getElementById("theater-content");
    const movieBtn = document.getElementById("movie-btn");
    const movieContent = document.getElementById("movie-content");
    const dateBtn = document.getElementById("date-btn");
    const dateContent = document.getElementById("date-content");
    const timeBtn = document.getElementById("time-btn");
    const timeContent = document.getElementById("time-content");
    const bookBtn = document.getElementById("book-btn");
    const bookingSummary = document.getElementById("booking-summary");

    // Indicator các bước
    const step2 = document.getElementById("step-2");
    const step3 = document.getElementById("step-3");
    const step4 = document.getElementById("step-4");
    const separator1 = document.getElementById("separator-1");
    const separator2 = document.getElementById("separator-2");
    const separator3 = document.getElementById("separator-3");

    // Các biến lưu lựa chọn
    let selectedTheater = "";
    let selectedMovie = "";
    let selectedDate = "";
    let selectedTime = "";

    /**
     * Đóng tất cả dropdown đang mở.
     */
    function closeAllDropdowns() {
        document.querySelectorAll(".dropdown-content").forEach((dropdown) => {
            dropdown.classList.remove("show");
        });
    }

    /**
     * Mở/đóng dropdown chọn rạp
     */
    theaterBtn.addEventListener("click", function () {
        closeAllDropdowns();
        theaterContent.classList.toggle("show");
    });

    /**
     * Xử lý chọn rạp phim
     */
    theaterContent.addEventListener("click", function (e) {
        const item = e.target.closest(".dropdown-item:not(.disabled)");
        if (!item) return;
        selectedTheater = item.textContent.trim();
        theaterContent
            .querySelectorAll(".dropdown-item")
            .forEach((el) => el.classList.remove("selected"));
        item.classList.add("selected");
        theaterBtn.innerHTML = `<span>${selectedTheater}</span><span><i class="fas fa-chevron-down"></i></span>`;
        theaterBtn.classList.add("active");
        theaterContent.classList.remove("show");

        // Bật bước chọn phim, tắt các bước sau
        step2.classList.add("active");
        separator1.classList.add("active");
        step3.classList.remove("active");
        separator2.classList.remove("active");
        step4.classList.remove("active");
        separator3.classList.remove("active");
        selectedMovie = "";
        selectedDate = "";
        selectedTime = "";
        movieBtn.innerHTML =
            '<span>2. Chọn Phim</span><span><i class="fas fa-chevron-down"></i></span>';
        movieBtn.classList.remove("active");
        movieBtn.classList.add("disabled");
        dateBtn.innerHTML =
            '<span>3. Chọn Ngày</span><span><i class="fas fa-chevron-down"></i></span>';
        dateBtn.classList.remove("active");
        dateBtn.classList.add("disabled");
        timeBtn.innerHTML =
            '<span>4. Chọn Suất</span><span><i class="fas fa-chevron-down"></i></span>';
        timeBtn.classList.remove("active");
        timeBtn.classList.add("disabled");
        bookBtn.classList.add("disabled");
        movieContent.innerHTML =
            '<div class="dropdown-item disabled">Vui lòng chọn rạp trước</div>';
        dateContent.innerHTML =
            '<div class="dropdown-item disabled">Vui lòng chọn phim trước</div>';
        timeContent.innerHTML =
            '<div class="dropdown-item disabled">Vui lòng chọn ngày trước</div>';
        // AJAX lấy phim theo rạp
        const idRap = item.dataset.value;
        $.get("/ajax/phim-theo-rap", { id_rap: idRap }, function (data) {
            if (data.error || data.length === 0) {
                movieContent.innerHTML = `<div class="dropdown-item disabled">${
                    data.error ||
                    "Hiện tại các thông tin rạp chiếu đang được cập nhật!"
                }</div>`;
            } else {
                movieContent.innerHTML = "";
                data.forEach(function (phim) {
                    movieContent.innerHTML += `<div class="dropdown-item" data-value="${phim.Slug}" data-id="${phim.ID_Phim}"><span class="marquee-text">${phim.TenPhim}</span></div>`;
                });
                movieBtn.classList.remove("disabled");
            }
        });
    });

    /**
     * Mở/đóng dropdown chọn phim
     */
    movieBtn.addEventListener("click", function () {
        if (!this.classList.contains("disabled")) {
            closeAllDropdowns();
            movieContent.classList.toggle("show");
        }
    });

    /**
     * Xử lý chọn phim
     */
    movieContent.addEventListener("click", function (e) {
        const item = e.target.closest(".dropdown-item:not(.disabled)");
        if (!item) return;
        selectedMovie = item.textContent.trim();
        movieContent
            .querySelectorAll(".dropdown-item")
            .forEach((el) => el.classList.remove("selected"));
        item.classList.add("selected");
        movieBtn.innerHTML = `<span>${selectedMovie}</span><span><i class="fas fa-chevron-down"></i></span>`;
        movieBtn.classList.add("active");
        movieContent.classList.remove("show");

        // Bật bước chọn ngày, reset các bước sau
        step3.classList.add("active");
        separator2.classList.add("active");
        step4.classList.remove("active");
        separator3.classList.remove("active");
        selectedDate = "";
        selectedTime = "";
        dateBtn.innerHTML =
            '<span>3. Chọn Ngày</span><span><i class="fas fa-chevron-down"></i></span>';
        dateBtn.classList.remove("active");
        dateBtn.classList.add("disabled");
        timeBtn.innerHTML =
            '<span>4. Chọn Suất</span><span><i class="fas fa-chevron-down"></i></span>';
        timeBtn.classList.remove("active");
        timeBtn.classList.add("disabled");
        bookBtn.classList.add("disabled");
        dateContent.innerHTML =
            '<div class="dropdown-item disabled">Đang tải ngày chiếu...</div>';
        timeContent.innerHTML =
            '<div class="dropdown-item disabled">Vui lòng chọn ngày trước</div>';

        // AJAX lấy ngày chiếu
        const idRap = theaterContent.querySelector(".dropdown-item.selected")
            .dataset.value;
        function formatVietnameseDate(dateStr) {
            const days = [
                "Chủ Nhật",
                "Thứ Hai",
                "Thứ Ba",
                "Thứ Tư",
                "Thứ Năm",
                "Thứ Sáu",
                "Thứ Bảy",
            ];
            const [year, month, day] = dateStr.split("-");
            const jsDate = new Date(dateStr);
            const thu = days[jsDate.getDay()];
            return `${thu}, ${day}-${month}-${year}`;
        }
        const idPhim = item.dataset.id;
        $.get(
            "/ajax/ngay-chieu-theo-rap-phim",
            { id_rap: idRap, id_phim: idPhim },
            function (data) {
                if (data.error || data.length === 0) {
                    dateContent.innerHTML = `<div class="dropdown-item disabled">${
                        data.error || "Phim đang được cập nhật!"
                    }</div>`;
                } else {
                    dateContent.innerHTML = "";
                    data.forEach(function (ngay) {
                        const ngayHienThi = formatVietnameseDate(ngay);
                        $("#date-content").append(
                            `<div class="dropdown-item" data-value="${ngay}"><span class="marquee-text">${ngayHienThi}</span></div>`
                        );
                    });
                    dateBtn.classList.remove("disabled");
                }
            }
        );
    });

    /**
     * Mở/đóng dropdown chọn ngày
     */
    dateBtn.addEventListener("click", function () {
        if (!this.classList.contains("disabled")) {
            closeAllDropdowns();
            dateContent.classList.toggle("show");
        }
    });

    /**
     * Xử lý chọn ngày chiếu
     */
    dateContent.addEventListener("click", function (e) {
        const item = e.target.closest(".dropdown-item:not(.disabled)");
        if (!item) return;
        selectedDate = item.textContent.trim();
        dateContent
            .querySelectorAll(".dropdown-item")
            .forEach((el) => el.classList.remove("selected"));
        item.classList.add("selected");
        dateBtn.innerHTML = `<span>${selectedDate}</span><span><i class="fas fa-chevron-down"></i></span>`;
        dateBtn.classList.add("active");
        dateContent.classList.remove("show");

        // Bật bước chọn suất chiếu, reset bước sau
        step4.classList.add("active");
        separator3.classList.add("active");
        selectedTime = "";
        timeBtn.innerHTML =
            '<span>4. Chọn Suất</span><span><i class="fas fa-chevron-down"></i></span>';
        timeBtn.classList.remove("active");
        timeBtn.classList.add("disabled");
        bookBtn.classList.add("disabled");
        timeContent.innerHTML =
            '<div class="dropdown-item disabled">Đang tải suất chiếu...</div>';

        // AJAX lấy suất chiếu
        const idRap = theaterContent.querySelector(".dropdown-item.selected")
            .dataset.value;
        const idPhim = movieContent.querySelector(".dropdown-item.selected")
            .dataset.id;
        const ngay = item.dataset.value;
        $.get(
            "/ajax/suat-chieu-theo-rap-phim-ngay",
            { id_rap: idRap, id_phim: idPhim, ngay: ngay },
            function (data) {
                if (data.error || data.length === 0) {
                    timeContent.innerHTML = `<div class="dropdown-item disabled">${
                        data.error || "Suất chiếu đang được cập nhật!"
                    }</div>`;
                } else {
                    timeContent.innerHTML = "";
                    data.forEach(function (suat) {
                        const gio = suat.GioChieu
                            ? suat.GioChieu.substring(0, 5)
                            : "";
                        $("#time-content").append(
                            `<div class="dropdown-item" data-value="${
                                suat.GioChieu
                            }" data-id="${suat.ID_SuatChieu}">
                            <span class="marquee-text">${gio}${
                                suat.DinhDang ? " - " + suat.DinhDang : ""
                            }</span>
                        </div>`
                        );
                    });
                    timeBtn.classList.remove("disabled");
                }
            }
        );
    });

    /**
     * Mở/đóng dropdown chọn suất chiếu
     */
    timeBtn.addEventListener("click", function () {
        if (!this.classList.contains("disabled")) {
            closeAllDropdowns();
            timeContent.classList.toggle("show");
        }
    });

    /**
     * Xử lý chọn suất chiếu
     */
    timeContent.addEventListener("click", function (e) {
        const item = e.target.closest(".dropdown-item:not(.disabled)");
        if (!item) return;
        selectedTime = item.textContent.trim();
        timeContent
            .querySelectorAll(".dropdown-item")
            .forEach((el) => el.classList.remove("selected"));
        item.classList.add("selected");
        timeBtn.innerHTML = `<span>${selectedTime}</span><span><i class="fas fa-chevron-down"></i></span>`;
        timeBtn.classList.add("active");
        timeContent.classList.remove("show");
        bookBtn.classList.remove("disabled");
    });

    /**
     * Xử lý click nút đặt vé (chuyển hướng sang trang đặt vé)
     */
    bookBtn.addEventListener("click", function () {
        if (bookBtn.classList.contains("disabled")) return;
        const phimSlug = movieContent.querySelector(".dropdown-item.selected")
            .dataset.value;
        const ngay = dateContent.querySelector(".dropdown-item.selected")
            .dataset.value;
        const gio = timeContent.querySelector(".dropdown-item.selected").dataset
            .value;
        window.location.href = `/dat-ve/${phimSlug}/${ngay}/${gio}`;
    });

    /**
     * Đóng dropdown khi click ra ngoài
     */
    window.addEventListener("click", function (event) {
        if (
            !event.target.matches(".dropdown-btn") &&
            !(
                event.target.parentElement &&
                event.target.parentElement.matches(".dropdown-btn")
            ) &&
            !event.target.matches(".fa-chevron-down")
        ) {
            closeAllDropdowns();
        }
    });
    /**
     * Nút click bài viết
     */
    document.querySelectorAll(".action-btn[data-slug]").forEach(function (btn) {
        const slug = btn.getAttribute("data-slug");
        const likedKey = `liked_goc_dien_anh_${slug}`;
        let liked = localStorage.getItem(likedKey) === "true";
        const icon = btn.querySelector("i");
        const likeCountSpan = btn.querySelector("span");

        // Giao diện ban đầu nếu đã like
        if (liked) {
            btn.classList.add("liked");
            icon.classList.remove("fa-regular");
            icon.classList.add("fa-solid");
        }

        btn.addEventListener("click", function (e) {
            e.preventDefault();
            if (!liked) {
                fetch(`/goc-dien-anh/${slug}/like`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": window.Laravel.csrfToken,
                        Accept: "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        likeCountSpan.textContent = data.LuotThich;
                        btn.classList.add("liked");
                        icon.classList.remove("fa-regular");
                        icon.classList.add("fa-solid");
                        liked = true;
                        localStorage.setItem(likedKey, "true");
                    });
            } else {
                fetch(`/goc-dien-anh/${slug}/unlike`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": window.Laravel.csrfToken,
                        Accept: "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        likeCountSpan.textContent = data.LuotThich;
                        btn.classList.remove("liked");
                        icon.classList.remove("fa-solid");
                        icon.classList.add("fa-regular");
                        liked = false;
                        localStorage.setItem(likedKey, "false");
                    });
            }
        });
    });
});

// ==================== CAROUSEL BANNER ĐẦU TRANG ====================

// Các biến toàn cục cho carousel-wrapper
let currentSlideIndex = 0;
const slides = document.querySelectorAll(".carousel-slide");
const dots = document.querySelectorAll(".dot");
const track = document.getElementById("carouselTrack");
const totalSlides = slides.length;
let autoPlayInterval;

/**
 * Cập nhật số thứ tự slide hiện tại
 */
function updateSlideNumber(index) {
    const currentNumber = document.getElementById("currentNumber");
    currentNumber.textContent = (index + 1).toString().padStart(2, "0");
}

/**
 * Reset lại progress bar của carousel banner (thanh chạy thời gian)
 */
function resetProgressBar() {
    const progressBar = document.getElementById("progressBar");
    if (progressBar) {
        progressBar.style.animation = "none";
        progressBar.offsetHeight; // Trigger reflow
        progressBar.style.animation = "progressSlide 4s linear";
    }
}

/**
 * Hiển thị slide theo index
 */
function showSlide(index) {
    if (track) {
        track.style.transform = `translateX(-${index * 100}%)`;
    }
    slides.forEach((slide, i) => {
        slide.classList.toggle("active", i === index);
    });
    dots.forEach((dot, i) => {
        dot.classList.toggle("active", i === index);
    });
    currentSlideIndex = index;
    updateSlideNumber(index);
    resetProgressBar();
}

/**
 * Chuyển sang slide kế tiếp (banner)
 */
function nextSlide() {
    const nextIndex = (currentSlideIndex + 1) % totalSlides;
    showSlide(nextIndex);
}

/**
 * Chuyển về slide trước (banner)
 */
function previousSlide() {
    const prevIndex = (currentSlideIndex - 1 + totalSlides) % totalSlides;
    showSlide(prevIndex);
}

/**
 * Chuyển đến slide bất kỳ (dùng cho dot)
 */
function currentSlide(index) {
    showSlide(index - 1);
    clearInterval(autoPlayInterval);
    autoPlayInterval = setInterval(nextSlide, 5000);
}

/**
 * Bắt đầu auto-play cho carousel banner
 */
function startAutoPlay() {
    autoPlayInterval = setInterval(nextSlide, 6000);
}

/**
 * Dừng auto-play carousel banner
 */
function stopAutoPlay() {
    clearInterval(autoPlayInterval);
}

// Khởi tạo auto-play & hiệu ứng hover (dừng chạy slide), swipe, keyboard cho banner
startAutoPlay();

const carouselWrapper = document.querySelector(".carousel-wrapper");
if (carouselWrapper) {
    // DỪNG auto-play khi di chuột vào banner
    carouselWrapper.addEventListener("mouseenter", stopAutoPlay);

    // TIẾP TỤC auto-play khi rời chuột khỏi banner
    carouselWrapper.addEventListener("mouseleave", startAutoPlay);

    // Touch/Swipe cho mobile banner
    let startX = 0,
        endX = 0,
        startTime = 0,
        endTime = 0;

    carouselWrapper.addEventListener("touchstart", (e) => {
        startX = e.touches[0].clientX;
        startTime = new Date().getTime();
    });

    carouselWrapper.addEventListener("touchend", (e) => {
        endX = e.changedTouches[0].clientX;
        endTime = new Date().getTime();
        handleSwipe();
    });

    function handleSwipe() {
        const threshold = 50;
        const timeThreshold = 300;
        const diff = startX - endX;
        const timeDiff = endTime - startTime;
        if (Math.abs(diff) > threshold && timeDiff < timeThreshold) {
            if (diff > 0) nextSlide(); // Vuốt trái → slide tiếp
            else previousSlide();      // Vuốt phải → slide trước

            clearInterval(autoPlayInterval);
            autoPlayInterval = setInterval(nextSlide, 5000);
        }
    }
}

// Keyboard support cho banner
document.addEventListener("keydown", (e) => {
    if (e.key === "ArrowLeft") {
        previousSlide();
        clearInterval(autoPlayInterval);
        autoPlayInterval = setInterval(nextSlide, 5000);
    } else if (e.key === "ArrowRight") {
        nextSlide();
        clearInterval(autoPlayInterval);
        autoPlayInterval = setInterval(nextSlide, 5000);
    }
});

// Khởi tạo slide đầu tiên banner
showSlide(0);

// ==================== CAROUSEL PHIM ĐANG CHIẾU / SẮP CHIẾU ====================

/**
 * Class điều khiển carousel phim nhiều card (đang chiếu, sắp chiếu)
 */
class CinemaCarousel {
    /**
     * @param {string} gridId - ID của grid chứa card phim
     * @param {string} prevBtnId - ID nút prev
     * @param {string} nextBtnId - ID nút next
     * @param {string} dotsId - ID dot indicator
     * @param {number} autoPlayInterval - Thời gian tự chuyển slide (ms)
     */
    constructor(gridId, prevBtnId, nextBtnId, dotsId, autoPlayInterval = 4000) {
        this.moviesGrid = document.getElementById(gridId);
        this.prevBtn = document.getElementById(prevBtnId);
        this.nextBtn = document.getElementById(nextBtnId);
        this.dotsIndicator = document.getElementById(dotsId);
        this.currentIndex = 0;
        this.cards = this.moviesGrid.querySelectorAll(".movie-card");
        this.itemsPerView = this.getItemsPerView();
        this.totalItems = this.cards.length;
        this.maxIndex = Math.max(
            0,
            Math.ceil(this.totalItems / this.itemsPerView) - 1
        );
        this.autoPlayIntervalTime = autoPlayInterval;
        this.autoPlayTimer = null;

        window.addEventListener("resize", () => {
            this.itemsPerView = this.getItemsPerView();
            this.totalItems =
                this.moviesGrid.querySelectorAll(".movie-card").length;
            this.maxIndex = Math.max(
                0,
                Math.ceil(this.totalItems / this.itemsPerView) - 1
            );
            this.currentIndex = Math.min(this.currentIndex, this.maxIndex);
            this.renderDots();
            this.updateCarousel();
        });

        this.renderDots();
        this.updateCarousel();
        this.prevBtn.addEventListener("click", () => this.prevSlide());
        this.nextBtn.addEventListener("click", () => this.nextSlide());

        // Dot navigation
        this.dotsIndicator.addEventListener("click", (e) => {
            if (e.target.classList.contains("dot-film")) {
                const dotIndex = Array.from(
                    this.dotsIndicator.children
                ).indexOf(e.target);
                this.goToSlide(dotIndex);
            }
        });

        this.startAutoPlay();
        this.moviesGrid.parentElement.addEventListener("mouseenter", () =>
            this.stopAutoPlay()
        );
        this.moviesGrid.parentElement.addEventListener("mouseleave", () =>
            this.startAutoPlay()
        );
    }

    /**
     * Lấy số lượng card trên 1 slide tùy kích thước màn hình
     */
    getItemsPerView() {
        if (window.innerWidth <= 600) return 1;
        if (window.innerWidth <= 1024) return 2;
        return 4;
    }

    /**
     * Vẽ dot indicator
     */
    renderDots() {
        if (!this.dotsIndicator) return;
        this.dotsIndicator.innerHTML = "";
        const dotCount = Math.max(
            1,
            Math.ceil(this.totalItems / this.itemsPerView)
        );
        for (let i = 0; i < dotCount; i++) {
            const dot = document.createElement("div");
            dot.className = "dot-film";
            if (i === this.currentIndex) dot.classList.add("active");
            this.dotsIndicator.appendChild(dot);
        }
    }

    /**
     * Cập nhật active dot
     */
    updateDots() {
        const dots = this.dotsIndicator.querySelectorAll(".dot-film");
        dots.forEach((dot, idx) => {
            dot.classList.toggle("active", idx === this.currentIndex);
        });
    }

    /**
     * Cập nhật vị trí carousel
     */
    updateCarousel() {
        const card = this.moviesGrid.querySelector(".movie-card");
        const slideWidth = card ? card.offsetWidth : 300;
        const gap = parseInt(getComputedStyle(this.moviesGrid).gap) || 30;
        const offset =
            (slideWidth + gap) * (this.currentIndex * this.itemsPerView);
        this.moviesGrid.style.transform = `translateX(-${offset}px)`;
        this.updateDots();
    }

    /**
     * Chuyển slide trước
     */
    prevSlide() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
        } else {
            this.currentIndex = this.maxIndex;
        }
        this.updateCarousel();
        this.resetAutoPlay();
    }

    /**
     * Chuyển slide sau
     */
    nextSlide() {
        if (this.currentIndex < this.maxIndex) {
            this.currentIndex++;
        } else {
            this.currentIndex = 0;
        }
        this.updateCarousel();
        this.resetAutoPlay();
    }

    /**
     * Chuyển tới index bất kỳ
     */
    goToSlide(index) {
        this.currentIndex = Math.min(index, this.maxIndex);
        this.updateCarousel();
        this.resetAutoPlay();
    }

    /**
     * Bắt đầu auto-play
     */
    startAutoPlay() {
        this.stopAutoPlay();
        this.autoPlayTimer = setInterval(
            () => this.nextSlide(),
            this.autoPlayIntervalTime
        );
    }

    /**
     * Dừng auto-play
     */
    stopAutoPlay() {
        if (this.autoPlayTimer) clearInterval(this.autoPlayTimer);
        this.autoPlayTimer = null;
    }

    /**
     * Reset lại auto-play
     */
    resetAutoPlay() {
        this.startAutoPlay();
    }
}

// Khởi tạo carousel phim đang chiếu/sắp chiếu
document.addEventListener("DOMContentLoaded", function () {
    new CinemaCarousel("moviesGrid", "prevBtn", "nextBtn", "dotsIndicator");
    new CinemaCarousel(
        "moviesGridUpcoming",
        "prevBtnUpcoming",
        "nextBtnUpcoming",
        "dotsIndicatorUpcoming"
    );
});

// ==================== HIỆU ỨNG KHÁC ====================

/**
 * Hiệu ứng hover nổi card phim
 */
document.querySelectorAll(".movie-card").forEach((card) => {
    card.addEventListener("mouseenter", () => {
        card.style.zIndex = "10";
    });
    card.addEventListener("mouseleave", () => {
        card.style.zIndex = "1";
    });
});

/**
 * Hiển thị popup thông tin phim (khi chưa có chi tiết)
 */
function showMovieModal(title) {
    const modal = document.createElement("div");
    modal.style.cssText = `
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.8); display: flex; align-items: center; justify-content: center; z-index: 1000;
    `;
    const modalContent = document.createElement("div");
    modalContent.style.cssText = `
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        padding: 40px; border-radius: 20px; text-align: center; color: white;
        border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(20px);
        max-width: 400px; width: 90%;
    `;
    modalContent.innerHTML = `
        <h2 style="margin-bottom: 20px; font-size: 2rem;">${title}</h2>
        <p style="margin-bottom: 30px; color: #a0a0a0;">Thông tin chi tiết về phim sẽ được cập nhật sớm!</p>
        <button class="close-modal-btn" style="
            background: linear-gradient(135deg, #ff6b6b, #ffd93d);
            border: none; padding: 12px 30px; border-radius: 25px; color: white;
            font-weight: 600; cursor: pointer; transition: transform 0.2s ease;">Đóng</button>
    `;
    modal.appendChild(modalContent);
    document.body.appendChild(modal);
    modalContent.querySelector(".close-modal-btn").onclick = () => {
        if (modal.parentNode) modal.parentNode.removeChild(modal);
    };
    modal.onclick = (e) => {
        if (e.target === modal && modal.parentNode)
            modal.parentNode.removeChild(modal);
    };
}

/**
 * Hiển thị popup trailer phim (YouTube embed)
 */
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".play-button[data-trailer]").forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.stopPropagation();
            const trailerUrl = this.getAttribute("data-trailer");
            let videoId = "";
            if (trailerUrl.includes("watch?v=")) {
                videoId = trailerUrl.split("watch?v=")[1].split("&")[0];
            } else if (trailerUrl.includes("youtu.be/")) {
                videoId = trailerUrl.split("youtu.be/")[1].split(/[?&]/)[0];
            } else {
                alert("Link trailer không hợp lệ!");
                return;
            }
            const modal = document.createElement("div");
            modal.style.cssText = `
                position: fixed; top:0; left:0; width:100vw; height:100vh;
                background:rgba(0,0,0,0.85); display:flex; align-items:center; justify-content:center;
                z-index:2000;
            `;
            modal.innerHTML = `
                <div style="position:relative;max-width:90vw;max-height:80vh;">
                    <iframe width="800" height="450" src="https://www.youtube.com/embed/${videoId}" 
                        frameborder="0" allowfullscreen style="border-radius:12px;max-width:90vw;max-height:80vh;"></iframe>
                    <button class="close-trailer-btn" style="
                        position:absolute;top:0;right:-1px;background:#fff;border:none;
                        border-radius:50%;width:40px;height:40px;font-size:1.5rem;cursor:pointer;
                        box-shadow:0 2px 8px rgba(0,0,0,0.2);">×</button>
                </div>
            `;
            document.body.appendChild(modal);
            modal.querySelector(".close-trailer-btn").onclick = () =>
                document.body.removeChild(modal);
            modal.onclick = (ev) => {
                if (ev.target === modal) document.body.removeChild(modal);
            };
        });
    });
});

/**
 * Hiệu ứng click nhỏ cho card Góc điện ảnh, blog, tab
 */
document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".nav-tab");
    tabs.forEach((tab) => {
        tab.addEventListener("click", function (e) {
            e.preventDefault();
            tabs.forEach((t) => t.classList.remove("active"));
            this.classList.add("active");
        });
    });

    document.addEventListener("mousemove", function (e) {
        const circles = document.querySelectorAll(".floating-circle");
        const x = e.clientX / window.innerWidth;
        const y = e.clientY / window.innerHeight;
        circles.forEach((circle, index) => {
            const moveX = (x - 0.5) * 50 * (index + 1);
            const moveY = (y - 0.5) * 50 * (index + 1);
            circle.style.transform = `translate(${moveX}px, ${moveY}px)`;
        });
    });

    const observerOptions = { threshold: 0.1, rootMargin: "0px 0px -50px 0px" };
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.animation =
                    "slideInUp 0.6s ease-out forwards";
            }
        });
    }, observerOptions);

    // Thêm hiệu ứng slideInUp
    const style = document.createElement("style");
    style.innerHTML = `
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(30px);}
            to { opacity: 1; transform: translateY(0);}
        }
    `;
    document.head.appendChild(style);
});

/**
 * Đổi màu nền động khi scroll trang
 */
window.addEventListener("scroll", function () {
    const scrollPercent =
        window.scrollY /
        (document.documentElement.scrollHeight - window.innerHeight);
    const hue = 220 + scrollPercent * 40; // Từ xanh sang tím
    document.body.style.background = `linear-gradient(135deg, hsl(${hue}, 100%, 4%) 0%, hsl(${hue}, 80%, 8%) 50%, hsl(${hue}, 60%, 12%) 100%)`;
});

/**
 * Hiệu ứng card khuyến mãi: chuyển động, ripple, badge đổi màu động
 */
document.addEventListener("DOMContentLoaded", function () {
    // CTA button hiệu ứng ripple
    const ctaButtons = document.querySelectorAll(".promotion-cta");
    ctaButtons.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.stopPropagation();
            const ripple = document.createElement("span");
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            ripple.style.width = ripple.style.height = size + "px";
            ripple.style.left = x + "px";
            ripple.style.top = y + "px";
            ripple.style.position = "absolute";
            ripple.style.borderRadius = "50%";
            ripple.style.background = "rgba(255, 255, 255, 0.6)";
            ripple.style.transform = "scale(0)";
            ripple.style.animation = "ripple 0.6s linear";
            this.style.position = "relative";
            this.appendChild(ripple);
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Thêm keyframes ripple
    const style = document.createElement("style");
    style.innerHTML = `
        @keyframes ripple {
            to { transform: scale(4); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
});

