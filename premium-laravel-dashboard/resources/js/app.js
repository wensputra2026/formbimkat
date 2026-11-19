import './bootstrap';
import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';

Alpine.plugin(Collapse);

window.Alpine = Alpine;

Alpine.data('premiumDashboard', () => ({
    collapsed: false,
    mobileOpen: false,
    menuSearch: '',
    openGroups: ['data-master', 'pengelolaan'],

    toggleGroup(key) {
        this.openGroups = this.openGroups.includes(key)
            ? this.openGroups.filter(item => item !== key)
            : [...this.openGroups, key];
    },

    isGroupOpen(key) {
        return this.openGroups.includes(key);
    },

    matchesMenu(label, children = []) {
        const query = this.menuSearch.trim().toLowerCase();
        if (!query) return true;
        return [label, ...children].some(item => String(item).toLowerCase().includes(query));
    },
}));

Alpine.start();
