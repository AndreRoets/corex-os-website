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

    /* Mobile accordion: id of the currently expanded section (null = all collapsed).
       On desktop every section is always open, so `desktop` short-circuits isOpen(). */
    openSection: null,
    desktop: window.matchMedia('(min-width: 1024px)').matches,

    isOpen(id) {
        return this.desktop || this.openSection === id;
    },

    toggleSection(id) {
        this.openSection = this.openSection === id ? null : id;
    },

    /* Expand a section (from the mobile menu) and scroll to it once Alpine has
       rendered the change. Called with the event default prevented, so the
       native #hash jump — which can't target a collapsed element — is bypassed. */
    goToSection(id) {
        this.openSection = id;
        this.mobileNavOpen = false;
        requestAnimationFrame(() =>
            requestAnimationFrame(() => {
                document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
            })
        );
    },

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

/* Keep the accordion's desktop flag in sync across the lg breakpoint. */
window.matchMedia('(min-width: 1024px)').addEventListener('change', (e) => {
    Alpine.store('site').desktop = e.matches;
});

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
