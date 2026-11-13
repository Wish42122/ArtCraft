document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.filter-bar');
    const gallery = document.querySelector('.gallery-grid');
    if (!form || !gallery) return;
    const search = form.querySelector('input[type="search"]');
    const sort = form.querySelector('[aria-label="Sort artworks"]');
    const cards = Array.from(gallery.querySelectorAll('.art-card'));
    const params = new URLSearchParams(window.location.search);
    search.value = params.get('q') || '';
    function applyFilters() {
        const query = search.value.trim().toLowerCase();
        const selectedArt = params.get('art');
        const selectedCategory = params.get('category');
        cards.forEach(card => {
            const image = card.querySelector('img');
            const selected = !selectedArt || image?.getAttribute('src') === 'image.php?id=' + selectedArt;
            const categoryMatches = !selectedCategory || card.dataset.category === selectedCategory;
            card.hidden = !selected || !categoryMatches || !card.textContent.toLowerCase().includes(query);
        });
        let sorted = [...cards];
        if (sort.selectedIndex === 1) {
            sorted.sort((left, right) => (left.dataset.created || '').localeCompare(right.dataset.created || ''));
        } else if (sort.selectedIndex === 2) {
            const views = card => Number(card.querySelector('.stats span')?.textContent.replace(/[^0-9]/g, '') || 0);
            sorted.sort((left, right) => views(right) - views(left));
        }
        sorted.forEach(card => gallery.appendChild(card));
    }
    form.addEventListener('submit', event => {
        event.preventDefault();
        params.delete('art');
        applyFilters();
    });
    search.addEventListener('input', applyFilters);
    sort.addEventListener('change', applyFilters);
    applyFilters();
});

