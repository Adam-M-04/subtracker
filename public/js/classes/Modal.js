export default class Modal {
    constructor(modalId, openBtnId, closeBtnId, cancelBtnId) {
        this.modal = document.getElementById(modalId);
        this.openBtn = document.getElementById(openBtnId);
        this.closeBtn = document.getElementById(closeBtnId);
        this.cancelBtn = document.getElementById(cancelBtnId);

        if (this.modal) {
            this.initEvents();
        }
    }

    initEvents() {
        if (this.openBtn) {
            this.openBtn.addEventListener('click', () => this.open());
        }
        if (this.closeBtn) {
            this.closeBtn.addEventListener('click', () => this.close());
        }
        if (this.cancelBtn) {
            this.cancelBtn.addEventListener('click', () => this.close());
        }
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.close();
            }
        });
    }

    open() {
        this.modal.classList.add('active');
    }

    close() {
        this.modal.classList.remove('active');
    }
}