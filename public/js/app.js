import Modal from './classes/Modal.js';
import SubscriptionForm from './classes/SubscriptionForm.js';
import SubscriptionManager from './classes/SubscriptionManager.js';
import SubscriptionFilter from './classes/SubscriptionFilter.js';
import UserManager from './classes/UserManager.js';

document.addEventListener('DOMContentLoaded', () => {
    const addSubModal = new Modal('addSubscriptionModal', 'addSubscriptionBtn', 'closeModalBtn', 'cancelModalBtn');

    const subForm = new SubscriptionForm('subscriptionForm', addSubModal);
    new SubscriptionManager(subForm);
    new SubscriptionFilter();
    new UserManager();
});