// Create floating particles
function createParticles() {
    const particlesContainer = document.querySelector(".hero-particles");
    const particleCount = 50;

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement("div");
        particle.className = "particle";

        // Random size and position
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

// Observe all fade-in elements
document.querySelectorAll(".fade-in-up").forEach((el) => {
    el.style.opacity = "0";
    el.style.transform = "translateY(50px)";
    observer.observe(el);
});

// Enhanced showtime button interactions
document.querySelectorAll(".showtime-btn").forEach((btn) => {
    btn.addEventListener("click", function (e) {
        // Create ripple effect
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

// Add ripple animation CSS
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

// Trailer functionality
const trailerBtn = document.getElementById("trailerBtn");
const trailerModal = document.getElementById("trailerModal");
const trailerVideo = document.getElementById("trailerVideo");
const closeTrailer = document.getElementById("closeTrailer");
const mainContent = document.getElementById("mainContent");

// Open trailer modal
trailerBtn.addEventListener("click", function (e) {
    e.preventDefault();
    trailerModal.classList.add("active");
    trailerVideo.src = trailerUrl;
    mainContent.classList.add("page-blur");
    document.body.style.overflow = "hidden";
});

// Close trailer modal function
function closeTrailerModal() {
    trailerModal.classList.remove("active");
    trailerVideo.src = "";
    mainContent.classList.remove("page-blur");
    document.body.style.overflow = "auto";
}

// Close trailer events
closeTrailer.addEventListener("click", closeTrailerModal);

// Close when clicking outside
trailerModal.addEventListener("click", function (e) {
    if (e.target === trailerModal) {
        closeTrailerModal();
    }
});

// Close with ESC key
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && trailerModal.classList.contains("active")) {
        closeTrailerModal();
    }
});

// Add parallax effect to hero background
window.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset;
    const heroBackground = document.querySelector(".hero-bg");
    heroBackground.style.transform = `translateY(${scrolled * 0.5}px)`;
});

