export default class MobileMenu {
    constructor() {
        this.btn = document.getElementById('mobileMenuBtn');
        this.sidebar = document.getElementById('sidebar');
        this.overlay = document.getElementById('mobileOverlay');

        if (this.btn && this.sidebar && this.overlay) {
            this.initEvents();
        }
    }

    initEvents() {
        this.btn.addEventListener('click', () => this.toggle());
        this.overlay.addEventListener('click', () => this.close());
    }

    toggle() {
        this.sidebar.classList.toggle('open');
        this.overlay.classList.toggle('open');
    }

    close() {
        this.sidebar.classList.remove('open');
        this.overlay.classList.remove('open');
    }
}