(function () {
    'use strict';
    const searchButton = document.querySelector('[data-vr-search]');
    const menuButton = document.querySelector('[data-vr-menu]');
    function toggle(button, force) {
        if (!button) return;
        const panel = document.getElementById(button.getAttribute('aria-controls'));
        if (!panel) return;
        const expanded = typeof force === 'boolean' ? force : button.getAttribute('aria-expanded') !== 'true';
        button.setAttribute('aria-expanded', String(expanded));
        panel.hidden = !expanded;
        if (expanded && button === searchButton) panel.querySelector('input').focus();
    }
    searchButton?.addEventListener('click', () => { toggle(menuButton, false); toggle(searchButton); });
    menuButton?.addEventListener('click', () => { toggle(searchButton, false); toggle(menuButton); });
    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;
        [searchButton, menuButton].forEach(button => {
            if (button?.getAttribute('aria-expanded') === 'true') { toggle(button, false); button.focus(); }
        });
    });
    const filters = document.querySelector('.vr-filters details');
    const mobile = window.matchMedia('(max-width: 600px)');
    function adaptFilters() { if (filters) filters.open = !mobile.matches; }
    adaptFilters();
    mobile.addEventListener('change', adaptFilters);
})();
