document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('addSubscriptionModal');
    const openBtn = document.getElementById('addSubscriptionBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelModalBtn');

    if (modal && openBtn) {
        openBtn.addEventListener('click', () => {
            modal.classList.add('active');
        });

        const closeModal = () => {
            modal.classList.remove('active');
        };

        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });
    }
});