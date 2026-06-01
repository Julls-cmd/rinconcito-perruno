document.addEventListener('DOMContentLoaded', function () {

    // ── SCROLL REVEAL ──────────────────────────────────────────
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, i * 100);
            }
        });
    }, { threshold: 0.15 });

    document.querySelectorAll('.how-step, .service-card, .feature-card, .contact-card').forEach(el => {
        el.classList.add('fade-in-element');
        observer.observe(el);
    });

    // ── CONTADOR ANIMADO EN STATS ──────────────────────────────
    function animateCounter(el, target, duration = 1500) {
        let start = 0;
        const startTime = performance.now();
        const step = (timestamp) => {
            const progress = Math.min((timestamp - startTime) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(eased * target);
            if (progress < 1) requestAnimationFrame(step);
            else el.textContent = target;
        };
        requestAnimationFrame(step);
    }

    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseInt(el.dataset.target);
                if (target) animateCounter(el, target);
                statsObserver.unobserve(el);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.stat-num[data-target]').forEach(el => {
        statsObserver.observe(el);
    });

    // ── NAVBAR BLUR EN SCROLL ──────────────────────────────────
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(61,28,2,0.98)';
        } else {
            navbar.style.background = 'rgba(61,28,2,0.92)';
        }
    });
});