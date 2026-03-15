export default class UserManager {
    constructor() {
        this.initEvents();
    }

    initEvents() {
        document.addEventListener('click', (e) => {
            const deleteBtn = e.target.closest('.delete-user-btn');
            if (deleteBtn && !deleteBtn.disabled) {
                this.handleDelete(deleteBtn.dataset.id);
            }
        });

        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('role-select')) {
                this.handleRoleChange(e.target.dataset.id, e.target.value);
            }
        });
    }

    async handleDelete(id) {
        if (!confirm('Are you sure you want to completely delete this user? This will also remove all their subscriptions.')) {
            return;
        }

        try {
            const response = await fetch('/api/users/delete', {
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

    async handleRoleChange(id, newRole) {
        try {
            const response = await fetch('/api/users/role', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ id: id, role: newRole })
            });

            const result = await response.json();

            if (result.status !== 'success') {
                alert('Error: ' + result.message);
                window.location.reload();
            }
        } catch (error) {
            console.error('Network Error:', error);
            alert('A network error occurred while updating the role.');
            window.location.reload();
        }
    }
}