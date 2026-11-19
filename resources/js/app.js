import './bootstrap';
import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';
import Chart from 'chart.js/auto';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

Alpine.plugin(Collapse);
window.Alpine = Alpine;
window.Chart = Chart;
window.Swal = Swal;

// SweetAlert confirmations for destructive and sign-out actions.
document.addEventListener('submit', async (event) => {
    const form = event.target.closest('form[data-swal-confirm]');
    if (!form || form.dataset.swalConfirmed === 'true') return;

    event.preventDefault();
    const result = await Swal.fire({
        title: form.dataset.swalTitle || 'Konfirmasi tindakan',
        text: form.dataset.swalConfirm,
        icon: form.dataset.swalIcon || 'warning',
        showCancelButton: true,
        confirmButtonText: form.dataset.swalConfirmButton || 'Ya, lanjutkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#07823f',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
    });

    if (result.isConfirmed) {
        form.dataset.swalConfirmed = 'true';
        form.requestSubmit(event.submitter || undefined);
    }
});

// Premium Dashboard State
Alpine.data('premiumDashboard', (initialOpen = []) => ({
    collapsed: false,
    mobileOpen: false,
    menuSearch: '',
    openGroups: Array.isArray(initialOpen) ? initialOpen : [],

    toggleGroup(key) {
        if (this.collapsed) this.collapsed = false;
        if (this.openGroups.includes(key)) {
            this.openGroups = this.openGroups.filter(item => item !== key);
        } else {
            // Accordion behavior: buka menu ini dan tutup menu bertingkat lainnya
            this.openGroups = [key];
        }
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

// School Search Component for Alpine
window.schoolSearch = (initialName = '', initialId = '') => ({
    query: initialName,
    selectedId: initialId,
    results: [],
    open: false,
    loading: false,
    async search() {
        if (this.query.trim().length < 2) {
            this.results = [];
            this.open = false;
            return;
        }
        this.loading = true;
        try {
            const res = await fetch(`/sekolah/cari?q=${encodeURIComponent(this.query)}`, {
                headers: { 'Accept': 'application/json' }
            });
            this.results = res.ok ? await res.json() : [];
            this.open = true;
        } catch (e) {
            this.results = [];
        } finally {
            this.loading = false;
        }
    },
    choose(item) {
        this.query = item.nama_sekolah;
        this.selectedId = item.id_sekolah;
        this.open = false;
    },
    clear() {
        this.query = '';
        this.selectedId = '';
        this.results = [];
        this.open = false;
    }
});

// Real-time duplicate checker
window.duplicateChecker = () => ({
    nip: '',
    nik: '',
    nipMessage: '',
    nikMessage: '',
    nipError: false,
    nikError: false,
    async check(type) {
        const val = type === 'nip' ? this.nip.trim() : this.nik.trim();
        if (!val) {
            if (type === 'nip') { this.nipMessage = ''; this.nipError = false; }
            if (type === 'nik') { this.nikMessage = ''; this.nikError = false; }
            return;
        }
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/guru/check-duplicate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ type: type, value: val })
            });
            const data = await res.json();
            if (type === 'nip') {
                this.nipMessage = data.message;
                this.nipError = data.exists;
            } else {
                this.nikMessage = data.message;
                this.nikError = data.exists;
            }
        } catch (e) {
            // silent fail
        }
    }
});

// Public Frontend Alpine Component (Pure Light Theme)
try {
    localStorage.removeItem('public-theme');
    document.documentElement.classList.remove('dark');
} catch (e) {}

Alpine.data('publicSite', () => ({
    showBackToTop: false,
    init() {
        this.showBackToTop = window.scrollY > 300;
        try {
            localStorage.removeItem('public-theme');
            document.documentElement.classList.remove('dark');
        } catch (e) {}
    }
}));

Alpine.start();
