// Public frontend Alpine component. Merge into the project's existing Alpine setup.
document.addEventListener('alpine:init', () => {
    Alpine.data('publicSite', () => ({
        darkMode: false,
        initTheme() {
            const saved = localStorage.getItem('public-theme');
            this.darkMode = saved ? saved === 'dark' : false;
            this.applyTheme();
            this.$root.addEventListener('toggle-theme', () => {
                this.darkMode = !this.darkMode;
                localStorage.setItem('public-theme', this.darkMode ? 'dark' : 'light');
                this.applyTheme();
            });
        },
        applyTheme() {
            document.documentElement.classList.toggle('dark', this.darkMode);
            document.querySelector('meta[name="theme-color"]')?.setAttribute('content', this.darkMode ? '#101722' : '#f8fafc');
        },
    }));
});
