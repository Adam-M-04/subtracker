import Modal from './classes/Modal.js';

document.addEventListener('DOMContentLoaded', () => {
    new Modal('addSubscriptionModal', 'addSubscriptionBtn', 'closeModalBtn', 'cancelModalBtn');
});