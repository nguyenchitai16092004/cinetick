// Create floating particles
function createParticles() {
    const particlesContainer = document.getElementById('particles');
    const particleCount = 50;

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 8 + 's';
        particle.style.animationDuration = (Math.random() * 3 + 5) + 's';
        particlesContainer.appendChild(particle);
    }
}

// Enhanced button interaction with ripple effect
const homeBtn = document.getElementById('homeBtn');

homeBtn.addEventListener('click', (e) => {
   
    
    // Create multiple ripples for better effect
    for (let i = 0; i < 3; i++) {
        setTimeout(() => {
            const ripple = document.createElement('div');
            const rect = homeBtn.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height) + (i * 20);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, ${0.3 - i * 0.1});
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                transform: scale(0);
                animation: ripple 0.8s ease-out;
                pointer-events: none;
                z-index: 1000;
            `;
            
            homeBtn.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 800);
        }, i * 100);
    }
    
    
});

// Add ripple animation
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Parallax effect for bubbles and tickets
document.addEventListener('mousemove', (e) => {
    const mouseX = (e.clientX / window.innerWidth - 0.5) * 2;
    const mouseY = (e.clientY / window.innerHeight - 0.5) * 2;
    
    const bubbles = document.querySelectorAll('.bubble');
    bubbles.forEach((bubble, index) => {
        const speed = (index + 1) * 0.3;
        const x = mouseX * speed * 20;
        const y = mouseY * speed * 20;
        bubble.style.transform = `translate(${x}px, ${y}px)`;
    });

    const tickets = document.querySelectorAll('.ticket-icon');
    tickets.forEach((ticket, index) => {
        const speed = (index + 1) * 0.2;
        const x = mouseX * speed * 15;
        const y = mouseY * speed * 15;
        const currentTransform = ticket.style.transform || '';
        ticket.style.transform = currentTransform + ` translate(${x}px, ${y}px)`;
    });
});

// Enhanced content hover effect
const content = document.querySelector('.content');
content.addEventListener('mouseenter', () => {
    content.style.transform = 'scale(1.02) translateY(-5px)';
    content.style.boxShadow = '0 50px 100px rgba(0, 0, 0, 0.25), inset 0 2px 0 rgba(255, 255, 255, 0.3)';
});

content.addEventListener('mouseleave', () => {
    content.style.transform = 'scale(1) translateY(0)';
    content.style.boxShadow = '0 40px 80px rgba(0, 0, 0, 0.15), inset 0 2px 0 rgba(255, 255, 255, 0.2)';
});

// Initialize particles
createParticles();

// Dynamic color variations with darker tones
let colorShift = 0;
setInterval(() => {
    colorShift += 0.3;
    const hue1 = (220 + colorShift) % 360; // Dark blue range
    const hue2 = (240 + colorShift) % 360; // Dark purple range  
    const hue3 = (280 + colorShift) % 360; // Dark violet range
    
    document.body.style.background = `linear-gradient(135deg, 
        hsl(${hue1}, 45%, 15%) 0%, 
        hsl(${hue2}, 50%, 18%) 50%, 
        hsl(${hue3}, 40%, 20%) 100%)`;
}, 150);
