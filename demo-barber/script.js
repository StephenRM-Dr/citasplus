document.addEventListener('DOMContentLoaded', () => {
    const bookingWidget = document.getElementById('booking-widget');
    const dashboardPreview = document.getElementById('dashboard-preview');
    const serviceList = document.getElementById('service-list');
    const calendarSection = document.getElementById('calendar-section');
    const confirmationSection = document.getElementById('confirmation-section');
    const steps = document.querySelectorAll('.step');

    let currentStep = 1;
    let selectedService = '';
    let selectedTime = '';

    // Step Transition Helper
    function goToStep(step) {
        currentStep = step;
        
        // Update Steps UI
        steps.forEach((s, idx) => {
            if (idx + 1 <= step) s.classList.add('active');
            else s.classList.remove('active');
        });

        // Toggle Sections with smooth fade
        [serviceList, calendarSection, confirmationSection].forEach((section, idx) => {
            if (idx + 1 === step) {
                section.style.display = 'block';
                section.animate([
                    { opacity: 0, transform: 'translateY(20px)' },
                    { opacity: 1, transform: 'translateY(0)' }
                ], { duration: 400, easing: 'cubic-bezier(0.165, 0.84, 0.44, 1)' });
            } else {
                section.style.display = 'none';
            }
        });
    }

    // Step 1: Service Selection
    const serviceBtns = document.querySelectorAll('.service-btn');
    serviceBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            selectedService = btn.dataset.service;
            goToStep(2);
        });
    });

    // Step 2: Time Selection
    const timeGrid = document.querySelector('.time-grid');
    // Generate mock times
    const times = ['09:00', '10:30', '11:00', '13:30', '14:00', '16:30'];
    times.forEach(t => {
        const slot = document.createElement('div');
        slot.className = 'time-slot';
        slot.textContent = t;
        slot.addEventListener('click', () => {
            selectedTime = t;
            slot.classList.add('selected');
            setTimeout(() => goToStep(3), 300);
        });
        timeGrid.appendChild(slot);
    });

    // Step 3: Confirmation logic
    const confirmBtn = document.getElementById('confirm-booking');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const btn = e.target;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Procesando...';
            btn.disabled = true;

            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-check-circle"></i> ¡Cita Confirmada!';
                btn.style.background = '#10b981';
                confettiEffect();
            }, 1500);
        });
    }

    // Confetti effect simulation
    function confettiEffect() {
        // Simple scale and fade out effect for the widget
        bookingWidget.style.transition = 'all 0.5s';
        bookingWidget.style.transform = 'scale(0.95)';
        setTimeout(() => {
            bookingWidget.style.transform = 'scale(1)';
        }, 300);
    }

    // Dashboard Mock Animations (if visible)
    if (dashboardPreview) {
        const stats = document.querySelectorAll('.stat-val');
        stats.forEach(stat => {
            const target = parseInt(stat.textContent.replace('$', '').replace(',', ''));
            let current = 0;
            const step = target / 50;
            const interval = setInterval(() => {
                current += step;
                if (current >= target) {
                    stat.textContent = stat.textContent.startsWith('$') ? `$${target.toLocaleString()}` : target.toLocaleString() + (stat.textContent.includes('%')?'%':'');
                    clearInterval(interval);
                } else {
                    stat.textContent = stat.textContent.startsWith('$') ? `$${Math.floor(current).toLocaleString()}` : Math.floor(current).toLocaleString() + (stat.textContent.includes('%')?'%':'');
                }
            }, 30);
        });
    }
});
