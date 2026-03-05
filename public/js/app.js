import Modal from './classes/Modal.js';
import SubscriptionForm from './classes/SubscriptionForm.js';

document.addEventListener('DOMContentLoaded', () => {
    const addSubModal = new Modal('addSubscriptionModal', 'addSubscriptionBtn', 'closeModalBtn', 'cancelModalBtn');
    new SubscriptionForm('subscriptionForm', addSubModal);
});