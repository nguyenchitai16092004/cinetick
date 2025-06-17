
/**
 * Đóng tất cả dropdown đang hiển thị.
 */
function closeAllDropdowns() {
    document.querySelectorAll(".dropdown-content").forEach((dropdown) => {
        dropdown.classList.remove("show");
    });
}

// --- Luồng chính đặt vé khi DOMContentLoaded ---
document.addEventListener("DOMContentLoaded", function () {
    // Lấy các phần tử cần thiết
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
    // Step indicators
    const step2 = document.getElementById("step-2");
    const step3 = document.getElementById("step-3");
    const step4 = document.getElementById("step-4");
    const separator1 = document.getElementById("separator-1");
    const separator2 = document.getElementById("separator-2");
    const separator3 = document.getElementById("separator-3");
    // Biến lưu lựa chọn
    let selectedTheater = "";
    let selectedMovie = "";
    let selectedDate = "";
    let selectedTime = "";

    /**
     * Xử lý khi click vào nút chọn rạp: mở dropdown, tải phim tương ứng, reset các lựa chọn sau đó.
     */
    theaterBtn.addEventListener("click", function () {
        closeAllDropdowns();
        theaterContent.classList.toggle("show");
    });

    /**
     * Xử lý khi chọn một rạp: cập nhật giao diện, tải danh sách phim cho rạp đó.
     */
    theaterContent.addEventListener("click", function (e) {
        const item = e.target.closest(".dropdown-item:not(.disabled)");
        if (!item) return;
        selectedTheater = item.textContent.trim();
        for (const el of theaterContent.querySelectorAll(".dropdown-item")) el.classList.remove("selected");
        item.classList.add("selected");
        theaterBtn.innerHTML = `<span>${selectedTheater}</span><span><i class="fas fa-chevron-down"></i></span>`;
        theaterBtn.classList.add("active");
        theaterContent.classList.remove("show");
        // Hiển thị bước tiếp theo
        step2.classList.add("active");
        separator1.classList.add("active");
        step3.classList.remove("active");
        separator2.classList.remove("active");
        step4.classList.remove("active");
        separator3.classList.remove("active");
        // Reset & Disable các bước sau
        selectedMovie = "";
        movieBtn.innerHTML = '<span>2. Chọn Phim</span><span><i class="fas fa-chevron-down"></i></span>';
        movieBtn.classList.remove("active");
        movieBtn.classList.add("disabled");
        selectedDate = "";
        dateBtn.innerHTML = '<span>3. Chọn Ngày</span><span><i class="fas fa-chevron-down"></i></span>';
        dateBtn.classList.remove("active");
        dateBtn.classList.add("disabled");
        selectedTime = "";
        timeBtn.innerHTML = '<span>4. Chọn Suất</span><span><i class="fas fa-chevron-down"></i></span>';
        timeBtn.classList.remove("active");
        timeBtn.classList.add("disabled");
        bookBtn.classList.add("disabled");
        // Reset nội dung dropdown
        movieContent.innerHTML = '<div class="dropdown-item disabled">Vui lòng chọn rạp trước</div>';
        dateContent.innerHTML = '<div class="dropdown-item disabled">Vui lòng chọn phim trước</div>';
        timeContent.innerHTML = '<div class="dropdown-item disabled">Vui lòng chọn ngày trước</div>';
        // AJAX lấy danh sách phim theo rạp
        const idRap = item.dataset.value;
        $.get("/ajax/phim-theo-rap", { id_rap: idRap }, function (data) {
            if (data.error || data.length === 0) {
                movieContent.innerHTML = `<div class="dropdown-item disabled">${data.error || "Hiện tại các thông tin rạp chiếu đang được cập nhật!"}</div>`;
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
     * Xử lý khi click vào nút chọn phim: mở dropdown phim nếu được phép.
     */
    movieBtn.addEventListener("click", function () {
        if (!this.classList.contains("disabled")) {
            closeAllDropdowns();
            movieContent.classList.toggle("show");
        }
    });

    /**
     * Xử lý khi chọn một phim: cập nhật giao diện, tải danh sách ngày chiếu phim tại rạp đã chọn.
     */
    movieContent.addEventListener("click", function (e) {
        const item = e.target.closest(".dropdown-item:not(.disabled)");
        if (!item) return;
        selectedMovie = item.textContent.trim();
        for (const el of movieContent.querySelectorAll(".dropdown-item")) el.classList.remove("selected");
        item.classList.add("selected");
        movieBtn.innerHTML = `<span>${selectedMovie}</span><span><i class="fas fa-chevron-down"></i></span>`;
        movieBtn.classList.add("active");
        movieContent.classList.remove("show");
        // Hiển thị bước tiếp theo
        step3.classList.add("active");
        separator2.classList.add("active");
        step4.classList.remove("active");
        separator3.classList.remove("active");
        // Reset các bước sau
        selectedDate = "";
        dateBtn.innerHTML = '<span>3. Chọn Ngày</span><span><i class="fas fa-chevron-down"></i></span>';
        dateBtn.classList.remove("active");
        dateBtn.classList.add("disabled");
        selectedTime = "";
        timeBtn.innerHTML = '<span>4. Chọn Suất</span><span><i class="fas fa-chevron-down"></i></span>';
        timeBtn.classList.remove("active");
        timeBtn.classList.add("disabled");
        bookBtn.classList.add("disabled");
        // Reset nội dung dropdown
        dateContent.innerHTML = '<div class="dropdown-item disabled">Đang tải ngày chiếu...</div>';
        timeContent.innerHTML = '<div class="dropdown-item disabled">Vui lòng chọn ngày trước</div>';
        // AJAX lấy ngày chiếu
        const idRap = theaterContent.querySelector(".dropdown-item.selected").dataset.value;
        function formatVietnameseDate(dateStr) {
            const days = ["Chủ Nhật", "Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu", "Thứ Bảy"];
            const [year, month, day] = dateStr.split("-");
            const jsDate = new Date(dateStr);
            const thu = days[jsDate.getDay()];
            return `${thu}, ${day}-${month}-${year}`;
        }
        const idPhim = item.dataset.id;
        $.get("/ajax/ngay-chieu-theo-rap-phim", { id_rap: idRap, id_phim: idPhim }, function (data) {
            if (data.error || data.length === 0) {
                dateContent.innerHTML = `<div class="dropdown-item disabled">${data.error || "Phim đang được cập nhật!"}</div>`;
            } else {
                dateContent.innerHTML = "";
                data.forEach(function (ngay) {
                    const ngayHienThi = formatVietnameseDate(ngay);
                    $("#date-content").append(`<div class="dropdown-item" data-value="${ngay}"><span class="marquee-text">${ngayHienThi}</span></div>`);
                });
                dateBtn.classList.remove("disabled");
            }
        });
    });

    /**
     * Xử lý khi click vào nút chọn ngày: mở dropdown ngày nếu được phép.
     */
    dateBtn.addEventListener("click", function () {
        if (!this.classList.contains("disabled")) {
            closeAllDropdowns();
            dateContent.classList.toggle("show");
        }
    });

    /**
     * Xử lý khi chọn một ngày: cập nhật giao diện, tải danh sách suất chiếu của phim đã chọn vào ngày đó. Bật luôn step 4.
     */
    dateContent.addEventListener("click", function (e) {
        const item = e.target.closest(".dropdown-item:not(.disabled)");
        if (!item) return;
        selectedDate = item.textContent.trim();
        for (const el of dateContent.querySelectorAll(".dropdown-item")) el.classList.remove("selected");
        item.classList.add("selected");
        dateBtn.innerHTML = `<span>${selectedDate}</span><span><i class="fas fa-chevron-down"></i></span>`;
        dateBtn.classList.add("active");
        dateContent.classList.remove("show");
        // Hiển thị luôn bước 4
        step4.classList.add("active");
        separator3.classList.add("active");
        // Reset các bước sau
        selectedTime = "";
        timeBtn.innerHTML = '<span>4. Chọn Suất</span><span><i class="fas fa-chevron-down"></i></span>';
        timeBtn.classList.remove("active");
        timeBtn.classList.add("disabled");
        bookBtn.classList.add("disabled");
        timeContent.innerHTML = '<div class="dropdown-item disabled">Đang tải suất chiếu...</div>';
        // AJAX lấy suất chiếu
        const idRap = theaterContent.querySelector(".dropdown-item.selected").dataset.value;
        const idPhim = movieContent.querySelector(".dropdown-item.selected").dataset.id;
        const ngay = item.dataset.value;
        $.get("/ajax/suat-chieu-theo-rap-phim-ngay", { id_rap: idRap, id_phim: idPhim, ngay: ngay }, function (data) {
            if (data.error || data.length === 0) {
                timeContent.innerHTML = `<div class="dropdown-item disabled">${data.error || "Suất chiếu đang được cập nhật!"}</div>`;
            } else {
                timeContent.innerHTML = "";
                data.forEach(function (suat) {
                    const gio = suat.GioChieu ? suat.GioChieu.substring(0, 5) : "";
                    $("#time-content").append(
                        `<div class="dropdown-item" data-value="${suat.GioChieu}" data-id="${suat.ID_SuatChieu}">
                            <span class="marquee-text">${gio}${suat.DinhDang ? " - " + suat.DinhDang : ""}</span>
                        </div>`
                    );
                });
                timeBtn.classList.remove("disabled");
            }
        });
    });

    /**
     * Xử lý khi click vào nút chọn suất: mở dropdown suất nếu được phép.
     */
    timeBtn.addEventListener("click", function () {
        if (!this.classList.contains("disabled")) {
            closeAllDropdowns();
            timeContent.classList.toggle("show");
        }
    });

    /**
     * Xử lý khi chọn một suất chiếu: cập nhật giao diện, cho phép bấm nút đặt vé.
     */
    timeContent.addEventListener("click", function (e) {
        const item = e.target.closest(".dropdown-item:not(.disabled)");
        if (!item) return;
        selectedTime = item.textContent.trim();
        for (const el of timeContent.querySelectorAll(".dropdown-item")) el.classList.remove("selected");
        item.classList.add("selected");
        timeBtn.innerHTML = `<span>${selectedTime}</span><span><i class="fas fa-chevron-down"></i></span>`;
        timeBtn.classList.add("active");
        timeContent.classList.remove("show");
        // Cho phép đặt vé
        bookBtn.classList.remove("disabled");
    });

    /**
     * Xử lý khi click vào nút đặt vé: chuyển hướng sang trang đặt vé với thông tin đã chọn.
     */
    bookBtn.addEventListener("click", function () {
        if (bookBtn.classList.contains("disabled")) return;
        const phimSlug = movieContent.querySelector(".dropdown-item.selected").dataset.value;
        const ngay = dateContent.querySelector(".dropdown-item.selected").dataset.value;
        const gio = timeContent.querySelector(".dropdown-item.selected").dataset.value;
        window.location.href = `/dat-ve/${phimSlug}/${ngay}/${gio}`;
    });

    /**
     * Đóng tất cả dropdown khi click ra ngoài vùng dropdown.
     */
    window.addEventListener("click", function (event) {
        if (
            !event.target.matches(".dropdown-btn") &&
            !(event.target.parentElement && event.target.parentElement.matches(".dropdown-btn")) &&
            !event.target.matches(".fa-chevron-down")
        ) {
            closeAllDropdowns();
        }
    });
});

// --- Carousel phim đang chiếu, sắp chiếu ---

/**
 * Lớp điều khiển carousel hiển thị phim đang chiếu/sắp chiếu (nhiều phim/card).
 * Bao gồm auto-play, chuyển slide, dot indicator, responsive và hiệu ứng dừng khi hover.
 */
class CinemaCarousel {
    /**
     * Khởi tạo carousel với các tham số DOM và tùy chọn thời gian tự động chuyển slide.
     * @param {string} gridId ID của grid chứa các card phim
     * @param {string} prevBtnId ID nút prev
     * @param {string} nextBtnId ID nút next
     * @param {string} dotsId ID dot indicator
     * @param {number} autoPlayInterval Thời gian tự động chuyển slide (ms)
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
        this.maxIndex = Math.max(0, Math.ceil(this.totalItems / this.itemsPerView) - 1);
        this.autoPlayIntervalTime = autoPlayInterval;
        this.autoPlayTimer = null;

        window.addEventListener("resize", () => {
            this.itemsPerView = this.getItemsPerView();
            this.totalItems = this.moviesGrid.querySelectorAll(".movie-card").length;
            this.maxIndex = Math.max(0, Math.ceil(this.totalItems / this.itemsPerView) - 1);
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
                const dotIndex = Array.from(this.dotsIndicator.children).indexOf(e.target);
                this.goToSlide(dotIndex);
            }
        });

        // Auto play setup
        this.startAutoPlay();

        // Pause on hover, resume on mouse leave
        this.moviesGrid.parentElement.addEventListener("mouseenter", () => this.stopAutoPlay());
        this.moviesGrid.parentElement.addEventListener("mouseleave", () => this.startAutoPlay());
    }

    /**
     * Xác định số lượng phim/card hiển thị trên mỗi slide tùy theo kích thước màn hình.
     */
    getItemsPerView() {
        if (window.innerWidth <= 600) return 1;
        if (window.innerWidth <= 1024) return 2;
        return 4;
    }

    /**
     * Vẽ dot indicator cho carousel.
     */
    renderDots() {
        if (!this.dotsIndicator) return;
        this.dotsIndicator.innerHTML = "";
        const dotCount = Math.max(1, Math.ceil(this.totalItems / this.itemsPerView));
        for (let i = 0; i < dotCount; i++) {
            const dot = document.createElement("div");
            dot.className = "dot-film";
            if (i === this.currentIndex) dot.classList.add("active");
            this.dotsIndicator.appendChild(dot);
        }
    }

    /**
     * Cập nhật trạng thái dot indicator dựa trên slide hiện tại.
     */
    updateDots() {
        const dots = this.dotsIndicator.querySelectorAll(".dot-film");
        dots.forEach((dot, idx) => {
            dot.classList.toggle("active", idx === this.currentIndex);
        });
    }

    /**
     * Cập nhật vị trí carousel khi chuyển slide.
     */
    updateCarousel() {
        const card = this.moviesGrid.querySelector(".movie-card");
        const slideWidth = card ? card.offsetWidth : 300;
        const gap = parseInt(getComputedStyle(this.moviesGrid).gap) || 30;
        const offset = (slideWidth + gap) * (this.currentIndex * this.itemsPerView);
        this.moviesGrid.style.transform = `translateX(-${offset}px)`;
        this.updateDots();
    }

    /**
     * Chuyển về slide trước đó.
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
     * Chuyển sang slide tiếp theo.
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
     * Chuyển đến một slide bất kỳ theo index.
     */
    goToSlide(index) {
        this.currentIndex = Math.min(index, this.maxIndex);
        this.updateCarousel();
        this.resetAutoPlay();
    }

    /**
     * Bắt đầu auto-play carousel.
     */
    startAutoPlay() {
        this.stopAutoPlay();
        this.autoPlayTimer = setInterval(() => this.nextSlide(), this.autoPlayIntervalTime);
    }

    /**
     * Dừng auto-play carousel.
     */
    stopAutoPlay() {
        if (this.autoPlayTimer) clearInterval(this.autoPlayTimer);
        this.autoPlayTimer = null;
    }

    /**
     * Reset lại auto-play (bắt đầu lại từ đầu).
     */
    resetAutoPlay() {
        this.startAutoPlay();
    }
}

/**
 * Khởi tạo carousel cho grid phim.
 */
document.addEventListener("DOMContentLoaded", function () {
    new CinemaCarousel("moviesGrid", "prevBtn", "nextBtn", "dotsIndicator");
});

/**
 * Hiệu ứng khi hover vào card phim: thay đổi z-index để nổi lên trên.
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
 * Hiển thị modal popup thông tin phim (dùng khi chưa có chi tiết phim).
 * @param {string} title Tiêu đề phim
 */
function showMovieModal(title) {
    const modal = document.createElement("div");
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    `;

    const modalContent = document.createElement("div");
    modalContent.style.cssText = `
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        padding: 40px;
        border-radius: 20px;
        text-align: center;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        max-width: 400px;
        width: 90%;
    `;

    modalContent.innerHTML = `
        <h2 style="margin-bottom: 20px; font-size: 2rem;">${title}</h2>
        <p style="margin-bottom: 30px; color: #a0a0a0;">Thông tin chi tiết về phim sẽ được cập nhật sớm!</p>
        <button class="close-modal-btn" style="
            background: linear-gradient(135deg, #ff6b6b, #ffd93d);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        ">Đóng</button>
    `;

    modal.appendChild(modalContent);
    document.body.appendChild(modal);

    modalContent.querySelector(".close-modal-btn").onclick = () => {
        if (modal.parentNode) modal.parentNode.removeChild(modal);
    };
    modal.onclick = (e) => {
        if (e.target === modal && modal.parentNode) modal.parentNode.removeChild(modal);
    };
}

/**
 * Hiển thị popup trailer khi bấm nút play (dùng YouTube embed).
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
                        position:absolute;top:0px;right:-1px;background:#fff;border:none;
                        border-radius:50%;width:40px;height:40px;font-size:1.5rem;cursor:pointer;
                        box-shadow:0 2px 8px rgba(0,0,0,0.2);">×</button>
                </div>
            `;
            document.body.appendChild(modal);
            modal.querySelector(".close-trailer-btn").onclick = () => document.body.removeChild(modal);
            modal.onclick = (ev) => {
                if (ev.target === modal) document.body.removeChild(modal);
            };
        });
    });
});