// assets/js/steppers.js
document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('.stepper-form');
    
    forms.forEach(form => {
        const steps = form.querySelectorAll('.step-content');
        const indicators = form.querySelectorAll('.step-indicator');
        const nextBtns = form.querySelectorAll('.next-btn');
        const prevBtns = form.querySelectorAll('.prev-btn');
        let currentStep = 0;

        function showStep(stepIndex) {
            steps.forEach((step, index) => {
                if(index === stepIndex) {
                    step.classList.add('active');
                    indicators[index].classList.add('active');
                    indicators[index].classList.remove('completed');
                } else {
                    step.classList.remove('active');
                    indicators[index].classList.remove('active');
                    if(index < stepIndex) {
                        indicators[index].classList.add('completed');
                    } else {
                        indicators[index].classList.remove('completed');
                    }
                }
            });
        }

        function validateStep(stepIndex) {
            const currentStepEl = steps[stepIndex];
            const inputs = currentStepEl.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if(!input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = 'var(--danger)';
                } else {
                    input.style.borderColor = 'var(--border-color)';
                }
            });
            
            return isValid;
        }

        nextBtns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                if(btn.type === 'submit') return;
                
                e.preventDefault();
                if(validateStep(currentStep)) {
                    currentStep++;
                    if(currentStep >= steps.length) currentStep = steps.length - 1;
                    showStep(currentStep);
                }
            });
        });

        prevBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                currentStep--;
                if(currentStep < 0) currentStep = 0;
                showStep(currentStep);
            });
        });

        if(steps.length > 0) {
            showStep(0);
        }
    });
});
