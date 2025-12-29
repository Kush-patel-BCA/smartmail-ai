// SmartMail AI - Enhanced Animation Utilities

document.addEventListener('DOMContentLoaded', function() {
    // 1. Initial Entry Animations
    animateEntry();

    // 2. Button Ripple Effect
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            let x = e.clientX - e.target.offsetLeft;
            let y = e.clientY - e.target.offsetTop;
            
            let ripples = document.createElement('span');
            ripples.style.left = x + 'px';
            ripples.style.top = y + 'px';
            this.appendChild(ripples);
            
            setTimeout(() => {
                ripples.remove()
            }, 1000);
        });
    });

    // 3. Hover Sound Effect (Optional, subtle)
    // const hoverSound = new Audio('assets/sounds/hover.mp3');
    // document.querySelectorAll('.menu-item, .email-item').forEach(item => {
    //     item.addEventListener('mouseenter', () => {
    //         // hoverSound.play();
    //     });
    // });
});

function animateEntry() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (sidebar) sidebar.classList.add('slide-in-left');
    if (mainContent) mainContent.classList.add('fade-in');
}

// Observe email list for new items to animate them
const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
        if (mutation.addedNodes.length) {
            mutation.addedNodes.forEach((node) => {
                if (node.classList && node.classList.contains('email-item')) {
                    node.classList.add('fade-in');
                }
            });
        }
    });
});

const emailList = document.getElementById('emailList');
if (emailList) {
    observer.observe(emailList, { childList: true });
}

// Particle Effect on Success (e.g. email sent)
function showConfetti() {
    // Simple confetti implementation or library call
    // For now, let's just do a console log as a placeholder for a library like canvas-confetti
    console.log("Confetti!"); 
}

// Smooth scroll helper
function smoothScroll(target) {
    document.querySelector(target).scrollIntoView({
        behavior: 'smooth'
    });
}
