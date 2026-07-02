import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';

Alpine.plugin(collapse);
Alpine.plugin(focus);

/**
 * Global site store — theme + nav state.
 * The initial theme is applied by an inline script in the <head> to avoid FOUC;
 * here we mirror that state so Alpine components stay in sync.
 */
Alpine.store('site', {
    theme: document.documentElement.classList.contains('light') ? 'light' : 'dark',
    mobileNavOpen: false,

    toggleTheme() {
        this.theme = this.theme === 'dark' ? 'light' : 'dark';
        const root = document.documentElement;
        root.classList.toggle('light', this.theme === 'light');
        root.classList.toggle('dark', this.theme === 'dark');
        try {
            localStorage.setItem('corex-theme', this.theme);
        } catch (e) {
            /* storage unavailable — ignore */
        }
    },
});

window.Alpine = Alpine;
Alpine.start();

/* Scroll-reveal: progressive enhancement layered on top of CSS. */
document.documentElement.classList.remove('no-js');

const reveals = document.querySelectorAll('.reveal');
if (reveals.length && 'IntersectionObserver' in window) {
    const io = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    obs.unobserve(entry.target);
                }
            });
        },
        { rootMargin: '0px 0px -8% 0px', threshold: 0.08 }
    );
    reveals.forEach((el) => io.observe(el));
} else {
    reveals.forEach((el) => el.classList.add('is-visible'));
}
