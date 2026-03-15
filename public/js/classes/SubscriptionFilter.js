export default class SubscriptionFilter {
    constructor() {
        this.categoryFilter = document.getElementById('categoryFilter');
        this.sortFilter = document.getElementById('sortFilter');
        this.grid = document.getElementById('subsGrid');

        if (this.categoryFilter && this.sortFilter && this.grid) {
            this.initEvents();
            this.filterAndSort();
        }
    }

    initEvents() {
        this.categoryFilter.addEventListener('change', () => this.filterAndSort());
        this.sortFilter.addEventListener('change', () => this.filterAndSort());
    }

    filterAndSort() {
        const category = this.categoryFilter.value;
        const sortMethod = this.sortFilter.value;
        const cards = Array.from(this.grid.querySelectorAll('.sub-card'));

        cards.forEach(card => {
            if (category === 'all' || card.dataset.category === category) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });

        const visibleCards = cards.filter(card => card.style.display !== 'none');

        visibleCards.sort((a, b) => {
            if (sortMethod.startsWith('date')) {
                const dateA = new Date(a.dataset.date).getTime();
                const dateB = new Date(b.dataset.date).getTime();
                return sortMethod === 'date_asc' ? dateA - dateB : dateB - dateA;
            } else if (sortMethod.startsWith('price')) {
                const priceA = parseFloat(a.dataset.price);
                const priceB = parseFloat(b.dataset.price);
                return sortMethod === 'price_asc' ? priceA - priceB : priceB - priceA;
            }
            return 0;
        });

        visibleCards.forEach(card => this.grid.appendChild(card));
    }
}