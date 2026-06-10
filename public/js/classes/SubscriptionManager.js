export default class SubscriptionManager {
    constructor(subFormInstance) {
        this.subForm = subFormInstance;
        this.initEvents();
    }

    initEvents() {
        this.subForm.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleSubmit();
        });

        document.addEventListener('click', (e) => {
            const toggleBtn = e.target.closest('.toggle-status-btn');
            if (toggleBtn) {
                this.handleStatusToggle(toggleBtn.dataset.id, toggleBtn.dataset.status);
            }

            const deleteBtn = e.target.closest('.delete-btn');
            if (deleteBtn && !deleteBtn.classList.contains('delete-user-btn')) {
                this.handleDelete(deleteBtn.dataset.id);
            }

            const editBtn = e.target.closest('.edit-btn');
            if (editBtn) {
                const data = JSON.parse(editBtn.dataset.sub);
                this.subForm.openEditMode(data);
            }
        });
    }

    async handleSubmit() {
        const csrfToken = window.AppConfig?.csrfToken || '';
        const isEdit = this.subForm.subIdInput.value !== '';
        const url = isEdit ? '/api/subscriptions/update' : '/api/subscriptions';

        const payload = {
            id: this.subForm.subIdInput.value,
            name: this.subForm.form.elements['name'].value,
            price: this.subForm.form.elements['price'].value,
            currency: this.subForm.form.elements['currency'].value,
            billingCycle: this.subForm.form.elements['billingCycle'].value,
            category: this.subForm.form.elements['category'].value,
            next_payment_date: this.subForm.form.elements['next_payment_date'].value,
            status: this.subForm.form.elements['status'] ? this.subForm.form.elements['status'].value : 1
        };

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-Token': csrfToken
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (result.status === 'success') {
                window.location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Network Error:', error);
            alert('A network error occurred.');
        }
    }

    async handleStatusToggle(id, newStatus) {
        const csrfToken = window.AppConfig?.csrfToken || '';
        try {
            const response = await fetch('/api/subscriptions/status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-Token': csrfToken
                },
                body: JSON.stringify({ id: id, status: newStatus })
            });

            const result = await response.json();

            if (result.status === 'success') {
                window.location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Network Error:', error);
            alert('A network error occurred.');
        }
    }

    async handleDelete(id) {
        if (!confirm('Are you sure you want to delete this subscription?')) {
            return;
        }

        const csrfToken = window.AppConfig?.csrfToken || '';
        try {
            const response = await fetch('/api/subscriptions/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-Token': csrfToken
                },
                body: JSON.stringify({ id: id })
            });

            const result = await response.json();

            if (result.status === 'success') {
                window.location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Network Error:', error);
            alert('A network error occurred while deleting.');
        }
    }
}