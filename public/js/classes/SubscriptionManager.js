export default class SubscriptionManager {
    constructor() {
        this.initEvents();
    }

    initEvents() {
        document.addEventListener('click', (e) => {
            const deleteBtn = e.target.closest('.delete-btn');
            if (deleteBtn) {
                this.handleDelete(deleteBtn.dataset.id);
            }
        });
    }

    async handleDelete(id) {
        if (!confirm('Are you sure you want to delete this subscription?')) {
            return;
        }

        try {
            const response = await fetch('/api/subscriptions/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
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