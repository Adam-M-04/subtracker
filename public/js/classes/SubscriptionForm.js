export default class SubscriptionForm {
    constructor(formId, modalInstance) {
        this.form = document.getElementById(formId);
        this.modal = modalInstance;
        this.title = document.getElementById('modalTitle');

        if (this.form) {
            this.initEvents();
        }
    }

    initEvents() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));

        // Resetowanie formularza przy kliknięciu "Add Subscription"
        const addBtn = document.getElementById('addSubscriptionBtn');
        if (addBtn) {
            addBtn.addEventListener('click', () => {
                this.form.reset();
                this.form.querySelector('input[name="id"]').value = '';
                this.title.innerText = 'Add Subscription';
            });
        }
    }

    openEditMode(data) {
        this.title.innerText = 'Edit Subscription';
        this.form.querySelector('input[name="id"]').value = data.id;
        this.form.querySelector('input[name="name"]').value = data.name;
        this.form.querySelector('input[name="price"]').value = data.price;
        this.form.querySelector('select[name="currency"]').value = data.currency;
        this.form.querySelector('select[name="billingCycle"]').value = data.billingCycle;
        this.form.querySelector('select[name="category"]').value = data.category;
        this.form.querySelector('input[name="next_payment_date"]').value = data.next_payment_date;

        this.modal.open();
    }

    async handleSubmit(e) {
        e.preventDefault();

        const submitBtn = this.form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = 'Saving...';
        submitBtn.disabled = true;

        const formData = {
            id: this.form.querySelector('input[name="id"]').value,
            name: this.form.querySelector('input[name="name"]').value,
            price: this.form.querySelector('input[name="price"]').value,
            currency: this.form.querySelector('select[name="currency"]').value,
            billingCycle: this.form.querySelector('select[name="billingCycle"]').value,
            category: this.form.querySelector('select[name="category"]').value,
            next_payment_date: this.form.querySelector('input[name="next_payment_date"]').value
        };

        const endpoint = formData.id ? '/api/subscriptions/update' : '/api/subscriptions';

        try {
            const response = await fetch(endpoint, {
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