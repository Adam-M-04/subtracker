export default class SubscriptionForm {
    constructor(formId, modalInstance) {
        this.form = document.getElementById(formId);
        this.modal = modalInstance;

        if (this.form) {
            this.initEvents();
        }
    }

    initEvents() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    }

    async handleSubmit(e) {
        e.preventDefault();

        const submitBtn = this.form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = 'Saving...';
        submitBtn.disabled = true;

        const formData = {
            name: this.form.querySelector('input[placeholder="e.g. Netflix, Spotify, Adobe"]').value,
            price: this.form.querySelector('input[type="number"]').value,
            currency: this.form.querySelectorAll('select')[0].value.split(' ')[0],
            billingCycle: this.form.querySelectorAll('select')[1].value,
            category: this.form.querySelectorAll('select')[2].value,
            next_payment_date: this.form.querySelector('input[type="date"]').value
        };

        try {
            const response = await fetch('/api/subscriptions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (result.status === 'success') {
                this.form.reset();
                this.modal.close();
                window.location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Network Error:', error);
            alert('A network error occurred while saving.');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }
}