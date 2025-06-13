document.addEventListener("DOMContentLoaded", function () {
    // Get all necessary elements
    const theaterBtn = document.getElementById("theater-btn");
    const theaterContent = document.getElementById("theater-content");
    const movieBtn = document.getElementById("movie-btn");
    const movieContent = document.getElementById("movie-content");
    const dateBtn = document.getElementById("date-btn");
    const dateContent = document.getElementById("date-content");
    const timeBtn = document.getElementById("time-btn");
    const timeContent = document.getElementById("time-content");
    const bookBtn = document.getElementById("book-btn");
    const theaterInfo = document.getElementById("theater-info");
    const bookingSummary = document.getElementById("booking-summary");

    // Step indicators
    const step1 = document.getElementById("step-1");
    const step2 = document.getElementById("step-2");
    const step3 = document.getElementById("step-3");
    const step4 = document.getElementById("step-4");
    const separator1 = document.getElementById("separator-1");
    const separator2 = document.getElementById("separator-2");
    const separator3 = document.getElementById("separator-3");

    // Summary elements
    const summaryMovieTitle = document.getElementById("summary-movie-title");
    const summaryMovieDetails = document.getElementById(
        "summary-movie-details"
    );
    const summaryTheater = document.getElementById("summary-theater");
    const summaryDate = document.getElementById("summary-date");
    const summaryTime = document.getElementById("summary-time");

    // Selections
    let selectedTheater = "";
    let selectedMovie = "";
    let selectedDate = "";
    let selectedTime = "";

    // Close all dropdowns
    function closeAllDropdowns() {
        const dropdowns = document.querySelectorAll(".dropdown-content");
        dropdowns.forEach((dropdown) => {
            dropdown.classList.remove("show");
        });
    }

    // Close dropdowns when clicking outside
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

    // Theater dropdown functionality
    theaterBtn.addEventListener("click", function () {
        closeAllDropdowns();
        theaterContent.classList.toggle("show");
    });

    // Theater selection
    theaterContent.querySelectorAll(".dropdown-item").forEach((item) => {
        item.addEventListener("click", function () {
            selectedTheater = this.textContent;
            theaterBtn.innerHTML = `<span>${selectedTheater}</span><span><i class="fas fa-chevron-down"></i></span>`;
            theaterBtn.classList.add("active");
            theaterContent.classList.remove("show");

            // Update step indicators
            step2.classList.add("active");
            separator1.classList.add("active");

            // Enable movie selection
            movieBtn.classList.remove("disabled");

            // Update summary
            updateSummary();

            // Reset subsequent selections
            resetSelection("movie");
        });
    });

    // Movie dropdown functionality
    movieBtn.addEventListener("click", function () {
        if (!this.classList.contains("disabled")) {
            closeAllDropdowns();
            movieContent.classList.toggle("show");
        }
    });

    // Movie selection
    movieContent.querySelectorAll(".dropdown-item").forEach((item) => {
        item.addEventListener("click", function () {
            selectedMovie = this.textContent;
            movieBtn.innerHTML = `<span>${selectedMovie}</span><span><i class="fas fa-chevron-down"></i></span>`;
            movieBtn.classList.add("active");
            movieContent.classList.remove("show");

            // Update step indicators
            step3.classList.add("active");
            separator2.classList.add("active");

            // Enable date selection
            dateBtn.classList.remove("disabled");

            // Update summary
            updateSummary();

            // Reset subsequent selections
            resetSelection("date");
        });
    });

    // Date dropdown functionality
    dateBtn.addEventListener("click", function () {
        if (!this.classList.contains("disabled")) {
            closeAllDropdowns();
            dateContent.classList.toggle("show");
        }
    });

    // Date selection
    dateContent.querySelectorAll(".dropdown-item").forEach((item) => {
        item.addEventListener("click", function () {
            selectedDate = this.textContent;
            dateBtn.innerHTML = `<span>${selectedDate}</span><span><i class="fas fa-chevron-down"></i></span>`;
            dateBtn.classList.add("active");
            dateContent.classList.remove("show");

            // Update step indicators
            step4.classList.add("active");
            separator3.classList.add("active");

            // Enable time selection
            timeBtn.classList.remove("disabled");

            // Update summary
            updateSummary();

            // Reset subsequent selections
            resetSelection("time");
        });
    });

    // Time dropdown functionality
    timeBtn.addEventListener("click", function () {
        if (!this.classList.contains("disabled")) {
            closeAllDropdowns();
            timeContent.classList.toggle("show");
        }
    });

    // Time selection
    timeContent.querySelectorAll(".dropdown-item").forEach((item) => {
        item.addEventListener("click", function () {
            selectedTime = this.textContent;
            timeBtn.innerHTML = `<span>${selectedTime}</span><span><i class="fas fa-chevron-down"></i></span>`;
            timeBtn.classList.add("active");
            timeContent.classList.remove("show");

            // Enable booking button
            bookBtn.classList.remove("disabled");

            // Make booking button glow
            bookBtn.style.animation = "pulse 1.5s infinite";

            // Update summary
            updateSummary();
        });
    });

    // Update booking summary
    function updateSummary() {
        if (selectedTheater) {
            summaryTheater.textContent = selectedTheater;
        }

        if (selectedMovie) {
            summaryMovieTitle.textContent = selectedMovie;
            if (movieInfo) {
                summaryMovieDetails.textContent = `${movieInfo.duration} | ${movieInfo.genre} | ${movieInfo.rating}`;
            }
        }

        if (selectedDate) {
            summaryDate.textContent = selectedDate;
        }

        if (selectedTime) {
            summaryTime.textContent = selectedTime;
        }

        // Show booking summary if at least theater and movie are selected
        if (selectedTheater && selectedMovie) {
            bookingSummary.classList.add("show");
        }
    }

    // Reset subsequent selections
    function resetSelection(startFrom) {
        if (startFrom === "movie" || startFrom === "all") {
            selectedMovie = "";
            movieBtn.innerHTML =
                '<span>2. Chọn Phim</span><span><i class="fas fa-chevron-down"></i></span>';
            movieBtn.classList.remove("active");
            step3.classList.remove("active");
            separator2.classList.remove("active");

            selectedDate = "";
            dateBtn.innerHTML =
                '<span>3. Chọn Ngày</span><span><i class="fas fa-chevron-down"></i></span>';
            dateBtn.classList.remove("active");
            dateBtn.classList.add("disabled");
            step4.classList.remove("active");
            separator3.classList.remove("active");

            selectedTime = "";
            timeBtn.innerHTML =
                '<span>4. Chọn Suất</span><span><i class="fas fa-chevron-down"></i></span>';
            timeBtn.classList.remove("active");
            timeBtn.classList.add("disabled");

            bookBtn.classList.add("disabled");
            bookBtn.style.animation = "";

            // Hide booking summary
            bookingSummary.classList.remove("show");
        } else if (startFrom === "date") {
            selectedDate = "";
            dateBtn.innerHTML =
                '<span>3. Chọn Ngày</span><span><i class="fas fa-chevron-down"></i></span>';
            dateBtn.classList.remove("active");
            step4.classList.remove("active");
            separator3.classList.remove("active");

            selectedTime = "";
            timeBtn.innerHTML =
                '<span>4. Chọn Suất</span><span><i class="fas fa-chevron-down"></i></span>';
            timeBtn.classList.remove("active");
            timeBtn.classList.add("disabled");

            bookBtn.classList.add("disabled");
            bookBtn.style.animation = "";
        } else if (startFrom === "time") {
            selectedTime = "";
            timeBtn.innerHTML =
                '<span>4. Chọn Suất</span><span><i class="fas fa-chevron-down"></i></span>';
            timeBtn.classList.remove("active");

            bookBtn.classList.add("disabled");
            bookBtn.style.animation = "";
        }

        // Update summary
        updateSummary();
    }
});
$(document).ready(function () {
    $(".popup-youtube").magnificPopup({
        type: "iframe",
        mainClass: "mfp-fade",
        removalDelay: 300,
        preloader: false,
        fixedContentPos: false,
    });
});
let currentSlideIndex = 0;
const slides = document.querySelectorAll(".carousel-slide");
const dots = document.querySelectorAll(".dot");
const track = document.getElementById("carouselTrack");
const totalSlides = slides.length;
let autoPlayInterval;
let progressInterval;

function createParticles() {
    const particlesContainer = document.getElementById("particles");
    for (let i = 0; i < 20; i++) {
        const particle = document.createElement("div");
        particle.className = "particle";
        particle.style.left = Math.random() * 100 + "%";
        particle.style.animationDelay = Math.random() * 6 + "s";
        particle.style.animationDuration = Math.random() * 4 + 4 + "s";
        particlesContainer.appendChild(particle);
    }
}

function updateSlideNumber(index) {
    const currentNumber = document.getElementById("currentNumber");
    currentNumber.textContent = (index + 1).toString().padStart(2, "0");
}

function resetProgressBar() {
    const progressBar = document.getElementById("progressBar");
    progressBar.style.animation = "none";
    progressBar.offsetHeight; // Trigger reflow
    progressBar.style.animation = "progressSlide 5s linear";
}

function showSlide(index) {
    // Update slide position
    track.style.transform = `translateX(-${index * 100}%)`;

    // Update active states
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

function nextSlide() {
    const nextIndex = (currentSlideIndex + 1) % totalSlides;
    showSlide(nextIndex);
}

function previousSlide() {
    const prevIndex = (currentSlideIndex - 1 + totalSlides) % totalSlides;
    showSlide(prevIndex);
}

function currentSlide(index) {
    showSlide(index - 1);
    // Restart auto-play
    clearInterval(autoPlayInterval);
    autoPlayInterval = setInterval(nextSlide, 5000);
}

function startAutoPlay() {
    autoPlayInterval = setInterval(nextSlide, 5000);
}

function stopAutoPlay() {
    clearInterval(autoPlayInterval);
}

// Auto-play carousel
startAutoPlay();

// Pause auto-play on hover
const carouselContainer = document.querySelector(".carousel-container");
carouselContainer.addEventListener("mouseenter", stopAutoPlay);
carouselContainer.addEventListener("mouseleave", startAutoPlay);

// Touch/Swipe support for mobile
let startX = 0;
let endX = 0;
let startTime = 0;
let endTime = 0;

carouselContainer.addEventListener("touchstart", (e) => {
    startX = e.touches[0].clientX;
    startTime = new Date().getTime();
});

carouselContainer.addEventListener("touchend", (e) => {
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
        if (diff > 0) {
            nextSlide();
        } else {
            previousSlide();
        }
        // Restart auto-play after swipe
        clearInterval(autoPlayInterval);
        autoPlayInterval = setInterval(nextSlide, 5000);
    }
}

// Keyboard navigation
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

// Initialize
createParticles();
showSlide(0);

// Add smooth page load effect
window.addEventListener("load", () => {
    document.body.style.opacity = "0";
    document.body.style.transition = "opacity 1s ease";
    setTimeout(() => {
        document.body.style.opacity = "1";
    }, 100);
});

// --- Carousel phim đang chiếu, sắp chiếu ---
class CinemaCarousel {
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

        // Auto play setup
        this.startAutoPlay();

        // Pause on hover, resume on mouse leave
        this.moviesGrid.parentElement.addEventListener("mouseenter", () =>
            this.stopAutoPlay()
        );
        this.moviesGrid.parentElement.addEventListener("mouseleave", () =>
            this.startAutoPlay()
        );
    }

    getItemsPerView() {
        if (window.innerWidth <= 600) return 1;
        if (window.innerWidth <= 1024) return 2;
        return 4;
    }

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

    updateDots() {
        const dots = this.dotsIndicator.querySelectorAll(".dot-film");
        dots.forEach((dot, idx) => {
            dot.classList.toggle("active", idx === this.currentIndex);
        });
    }

    updateCarousel() {
        const card = this.moviesGrid.querySelector(".movie-card");
        const slideWidth = card ? card.offsetWidth : 300;
        const gap = parseInt(getComputedStyle(this.moviesGrid).gap) || 30;
        const offset =
            (slideWidth + gap) * (this.currentIndex * this.itemsPerView);
        this.moviesGrid.style.transform = `translateX(-${offset}px)`;
        this.updateDots();
    }

    prevSlide() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
        } else {
            this.currentIndex = this.maxIndex;
        }
        this.updateCarousel();
        this.resetAutoPlay();
    }

    nextSlide() {
        if (this.currentIndex < this.maxIndex) {
            this.currentIndex++;
        } else {
            this.currentIndex = 0;
        }
        this.updateCarousel();
        this.resetAutoPlay();
    }

    goToSlide(index) {
        this.currentIndex = Math.min(index, this.maxIndex);
        this.updateCarousel();
        this.resetAutoPlay();
    }

    startAutoPlay() {
        this.stopAutoPlay();
        this.autoPlayTimer = setInterval(
            () => this.nextSlide(),
            this.autoPlayIntervalTime
        );
    }

    stopAutoPlay() {
        if (this.autoPlayTimer) clearInterval(this.autoPlayTimer);
        this.autoPlayTimer = null;
    }

    resetAutoPlay() {
        this.startAutoPlay();
    }
}

// Khởi tạo:
document.addEventListener("DOMContentLoaded", function () {
    new CinemaCarousel("moviesGrid", "prevBtn", "nextBtn", "dotsIndicator");
});

// Khởi tạo:
document.addEventListener("DOMContentLoaded", function () {
    new CinemaCarousel("moviesGrid", "prevBtn", "nextBtn", "dotsIndicator");
});

document.addEventListener("DOMContentLoaded", function () {
    // Get all necessary elements
    const theaterBtn = document.getElementById("theater-btn");
    const theaterContent = document.getElementById("theater-content");
    const movieBtn = document.getElementById("movie-btn");
    const movieContent = document.getElementById("movie-content");
    const dateBtn = document.getElementById("date-btn");
    const dateContent = document.getElementById("date-content");
    const timeBtn = document.getElementById("time-btn");
    const timeContent = document.getElementById("time-content");
    const bookBtn = document.getElementById("book-btn");
    const theaterInfo = document.getElementById("theater-info");
    const bookingSummary = document.getElementById("booking-summary");

    // Step indicators
    const step1 = document.getElementById("step-1");
    const step2 = document.getElementById("step-2");
    const step3 = document.getElementById("step-3");
    const step4 = document.getElementById("step-4");
    const separator1 = document.getElementById("separator-1");
    const separator2 = document.getElementById("separator-2");
    const separator3 = document.getElementById("separator-3");

    // Selections
    let selectedTheater = "";
    let selectedMovie = "";
    let selectedDate = "";
    let selectedTime = "";

    // --- Dropdown click handlers ---
    // Theater
    theaterBtn.addEventListener("click", function () {
        closeAllDropdowns();
        theaterContent.classList.toggle("show");
    });

    theaterContent.addEventListener("click", function (e) {
        const item = e.target.closest(".dropdown-item:not(.disabled)");
        if (!item) return;
        selectedTheater = item.textContent.trim();
        // Mark selected
        for (const el of theaterContent.querySelectorAll(".dropdown-item"))
            el.classList.remove("selected");
        item.classList.add("selected");

        theaterBtn.innerHTML = `<span>${selectedTheater}</span><span><i class="fas fa-chevron-down"></i></span>`;
        theaterBtn.classList.add("active");
        theaterContent.classList.remove("show");

        // Step Indicator
        step2.classList.add("active");
        separator1.classList.add("active");
        step3.classList.remove("active");
        separator2.classList.remove("active");
        step4.classList.remove("active");
        separator3.classList.remove("active");

        // Reset & Disable next steps
        selectedMovie = "";
        movieBtn.innerHTML =
            '<span>2. Chọn Phim</span><span><i class="fas fa-chevron-down"></i></span>';
        movieBtn.classList.remove("active");
        movieBtn.classList.add("disabled");
        selectedDate = "";
        dateBtn.innerHTML =
            '<span>3. Chọn Ngày</span><span><i class="fas fa-chevron-down"></i></span>';
        dateBtn.classList.remove("active");
        dateBtn.classList.add("disabled");
        selectedTime = "";
        timeBtn.innerHTML =
            '<span>4. Chọn Suất</span><span><i class="fas fa-chevron-down"></i></span>';
        timeBtn.classList.remove("active");
        timeBtn.classList.add("disabled");
        bookBtn.classList.add("disabled");

        // Reset contents
        movieContent.innerHTML =
            '<div class="dropdown-item disabled">Vui lòng chọn rạp trước</div>';
        dateContent.innerHTML =
            '<div class="dropdown-item disabled">Vui lòng chọn phim trước</div>';
        timeContent.innerHTML =
            '<div class="dropdown-item disabled">Vui lòng chọn ngày trước</div>';

        // AJAX load movies
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

    // Movie
    movieBtn.addEventListener("click", function () {
        if (!this.classList.contains("disabled")) {
            closeAllDropdowns();
            movieContent.classList.toggle("show");
        }
    });
    movieContent.addEventListener("click", function (e) {
        const item = e.target.closest(".dropdown-item:not(.disabled)");
        if (!item) return;
        selectedMovie = item.textContent.trim();
        // Mark selected
        for (const el of movieContent.querySelectorAll(".dropdown-item"))
            el.classList.remove("selected");
        item.classList.add("selected");

        movieBtn.innerHTML = `<span>${selectedMovie}</span><span><i class="fas fa-chevron-down"></i></span>`;
        movieBtn.classList.add("active");
        movieContent.classList.remove("show");

        // Step Indicator
        step3.classList.add("active");
        separator2.classList.add("active");
        step4.classList.remove("active");
        separator3.classList.remove("active");

        // Reset next steps
        selectedDate = "";
        dateBtn.innerHTML =
            '<span>3. Chọn Ngày</span><span><i class="fas fa-chevron-down"></i></span>';
        dateBtn.classList.remove("active");
        dateBtn.classList.add("disabled");
        selectedTime = "";
        timeBtn.innerHTML =
            '<span>4. Chọn Suất</span><span><i class="fas fa-chevron-down"></i></span>';
        timeBtn.classList.remove("active");
        timeBtn.classList.add("disabled");
        bookBtn.classList.add("disabled");

        // Reset contents
        dateContent.innerHTML =
            '<div class="dropdown-item disabled">Đang tải ngày chiếu...</div>';
        timeContent.innerHTML =
            '<div class="dropdown-item disabled">Vui lòng chọn ngày trước</div>';

        // AJAX load dates
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

    // Date
    dateBtn.addEventListener("click", function () {
        if (!this.classList.contains("disabled")) {
            closeAllDropdowns();
            dateContent.classList.toggle("show");
        }
    });
    dateContent.addEventListener("click", function (e) {
        const item = e.target.closest(".dropdown-item:not(.disabled)");
        if (!item) return;
        selectedDate = item.textContent.trim();
        // Mark selected
        for (const el of dateContent.querySelectorAll(".dropdown-item"))
            el.classList.remove("selected");
        item.classList.add("selected");

        dateBtn.innerHTML = `<span>${selectedDate}</span><span><i class="fas fa-chevron-down"></i></span>`;
        dateBtn.classList.add("active");
        dateContent.classList.remove("show");

        // Step Indicator
        step4.classList.remove("active");
        separator3.classList.remove("active");

        // Reset next steps
        selectedTime = "";
        timeBtn.innerHTML =
            '<span>4. Chọn Suất</span><span><i class="fas fa-chevron-down"></i></span>';
        timeBtn.classList.remove("active");
        timeBtn.classList.add("disabled");
        bookBtn.classList.add("disabled");

        timeContent.innerHTML =
            '<div class="dropdown-item disabled">Đang tải suất chiếu...</div>';

        // AJAX load times
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
                        // Lấy giờ ở dạng 20:00 thay vì 20:00:00
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

    // Time
    timeBtn.addEventListener("click", function () {
        if (!this.classList.contains("disabled")) {
            closeAllDropdowns();
            timeContent.classList.toggle("show");
        }
    });
    timeContent.addEventListener("click", function (e) {
        const item = e.target.closest(".dropdown-item:not(.disabled)");
        if (!item) return;
        selectedTime = item.textContent.trim();
        // Mark selected
        for (const el of timeContent.querySelectorAll(".dropdown-item"))
            el.classList.remove("selected");
        item.classList.add("selected");

        timeBtn.innerHTML = `<span>${selectedTime}</span><span><i class="fas fa-chevron-down"></i></span>`;
        timeBtn.classList.add("active");
        timeContent.classList.remove("show");

        // Step Indicator
        step4.classList.add("active");
        separator3.classList.add("active");

        // Enable Book button
        bookBtn.classList.remove("disabled");
    });

    // Book button
    bookBtn.addEventListener("click", function () {
        if (bookBtn.classList.contains("disabled")) return;
        // Chuyển hướng đến trang đặt vé
        const phimSlug = movieContent.querySelector(".dropdown-item.selected")
            .dataset.value;
        const ngay = dateContent.querySelector(".dropdown-item.selected")
            .dataset.value;
        const gio = timeContent.querySelector(".dropdown-item.selected").dataset
            .value;
        window.location.href = `/dat-ve/${phimSlug}/${ngay}/${gio}`;
    });

    // Helper: Close dropdowns
    function closeAllDropdowns() {
        document.querySelectorAll(".dropdown-content").forEach((dropdown) => {
            dropdown.classList.remove("show");
        });
    }
});
// Add some interactive effects
document.querySelectorAll(".movie-card").forEach((card) => {
    card.addEventListener("mouseenter", () => {
        card.style.zIndex = "10";
    });

    card.addEventListener("mouseleave", () => {
        card.style.zIndex = "1";
    });
});
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

    // Đóng modal ngay lập tức
    modalContent.querySelector(".close-modal-btn").onclick = () => {
        if (modal.parentNode) modal.parentNode.removeChild(modal);
    };
    modal.onclick = (e) => {
        if (e.target === modal && modal.parentNode)
            modal.parentNode.removeChild(modal);
    };
}
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
            modal.querySelector(".close-trailer-btn").onclick = () =>
                document.body.removeChild(modal);
            modal.onclick = (ev) => {
                if (ev.target === modal) document.body.removeChild(modal);
            };
        });
    });
});
// Smooth scrolling and interactive effects
document.addEventListener("DOMContentLoaded", function () {
    // Add click effects to articles
    const articles = document.querySelectorAll(
        ".main-article, .sidebar-article"
    );
    articles.forEach((article) => {
        article.addEventListener("click", function (e) {
            e.preventDefault();
            this.style.transform = "scale(0.98)";
            setTimeout(() => {
                this.style.transform = "";
            }, 150);
        });
    });

    // Tab switching animation
    const tabs = document.querySelectorAll(".nav-tab");
    tabs.forEach((tab) => {
        tab.addEventListener("click", function (e) {
            e.preventDefault();
            tabs.forEach((t) => t.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // Parallax effect for floating elements
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

    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
    };

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.animation =
                    "slideInUp 0.6s ease-out forwards";
            }
        });
    }, observerOptions);

    // Observe all articles
    articles.forEach((article) => {
        observer.observe(article);
    });

    // Add slideInUp animation
    const style = document.createElement("style");
    style.innerHTML = `
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
});

// Dynamic background color change based on scroll
window.addEventListener("scroll", function () {
    const scrollPercent =
        window.scrollY /
        (document.documentElement.scrollHeight - window.innerHeight);
    const hue = 220 + scrollPercent * 40; // Changes from blue to purple
    document.body.style.background = `linear-gradient(135deg, hsl(${hue}, 100%, 4%) 0%, hsl(${hue}, 80%, 8%) 50%, hsl(${hue}, 60%, 12%) 100%)`;
});
document.addEventListener("DOMContentLoaded", function () {
    // Card interaction effects
    const cards = document.querySelectorAll(".promotion-card");

    cards.forEach((card, index) => {
        // Staggered animation on load
        card.style.opacity = "0";
        card.style.transform = "translateY(50px)";

        setTimeout(() => {
            card.style.transition =
                "all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)";
            card.style.opacity = "1";
            card.style.transform = "translateY(0)";
        }, index * 200);

        // Click effect
        card.addEventListener("click", function (e) {
            if (!e.target.classList.contains("promotion-cta")) {
                this.style.transform = "translateY(-15px) scale(0.98)";
                setTimeout(() => {
                    this.style.transform = "translateY(-15px) scale(1.02)";
                }, 150);
            }
        });

        // Tilt effect on mouse move
        card.addEventListener("mousemove", function (e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;

            this.style.transform = `translateY(-15px) scale(1.02) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
        });

        card.addEventListener("mouseleave", function () {
            this.style.transform =
                "translateY(0) scale(1) rotateX(0) rotateY(0)";
        });
    });

    // CTA button effects
    const ctaButtons = document.querySelectorAll(".promotion-cta");
    ctaButtons.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.stopPropagation();

            // Ripple effect
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

    // Parallax effect
    document.addEventListener("mousemove", function (e) {
        const x = e.clientX / window.innerWidth;
        const y = e.clientY / window.innerHeight;

        stars.forEach((star, index) => {
            const moveX = (x - 0.5) * 30 * (index + 1);
            const moveY = (y - 0.5) * 30 * (index + 1);
            star.style.transform += ` translate(${moveX}px, ${moveY}px)`;
        });
    });

    // Add ripple animation keyframes
    const style = document.createElement("style");
    style.innerHTML = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
});

// Dynamic badge colors
setInterval(() => {
    const badges = document.querySelectorAll(".promotion-badge");
    badges.forEach((badge) => {
        const hue = Math.random() * 60 + 15; // Random hue between 15-75 (red to yellow)
        badge.style.background = `linear-gradient(45deg, hsl(${hue}, 80%, 50%), hsl(${
            hue + 20
        }, 70%, 55%))`;
    });
}, 5000);
