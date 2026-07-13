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

    /* Mobile: map of expanded section ids. Multiple can be open at once so opening
       one section never collapses another above it (which would shift the page up
       and make the newly-opened section appear above the viewport). */
    openSections: {},
    desktop: window.matchMedia('(min-width: 1024px)').matches,

    /* "Deep" (reference-heavy) sections collapse on desktop too, independently,
       collapsed by default — keeping the desktop page to a short persuasive spine.
       Spine sections (not listed here) are always open on desktop. */
    deepOpen: { features: false, compliance: false, control: false },

    isDeep(id) {
        return Object.prototype.hasOwnProperty.call(this.deepOpen, id);
    },

    isOpen(id) {
        if (this.desktop) {
            // Deep sections toggle independently; everything else is always open.
            return this.isDeep(id) ? this.deepOpen[id] : true;
        }
        return !!this.openSections[id];
    },

    toggleSection(id) {
        if (this.desktop) {
            if (this.isDeep(id)) this.deepOpen[id] = !this.deepOpen[id];
            return;
        }
        this.openSections[id] = !this.openSections[id];
    },

    /* Expand a section (from a nav link) and scroll to it once Alpine has rendered
       the change. Works on both breakpoints; called with the event default
       prevented so the native #hash jump — which can't target a collapsed element —
       is bypassed. */
    revealSection(id) {
        if (this.desktop) {
            if (this.isDeep(id)) this.deepOpen[id] = true;
        } else {
            this.openSections[id] = true;
        }
        this.mobileNavOpen = false;
        requestAnimationFrame(() =>
            requestAnimationFrame(() => {
                document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
            })
        );
    },

    /* Back-compat alias used by the mobile menu. */
    goToSection(id) {
        this.revealSection(id);
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

/**
 * Hero data web — the CoreX modules as a ring, every module wired to the ones it
 * feeds. Drag to spin, click a module to see what its data touches.
 *
 * The ring is drawn imperatively (rather than with x-for) so the SVG children are
 * created in the SVG namespace and can be re-laid-out on every drag frame without
 * Alpine re-rendering the whole tree.
 *
 * Nodes/edges/features here are illustrative; the real map should eventually come
 * from the CoreX atlas so the visual can't drift from the system.
 */
Alpine.data('dataWeb', () => ({
    nodes: [
        ['contact', 'Contacts', 'C'],
        ['property', 'Properties', 'P'],
        ['deal', 'Deals', 'D'],
        ['fica', 'FICA', 'F'],
        ['mic', 'Market intel', 'MI'],
        ['buyer', 'Buyers', 'B'],
        ['esign', 'E-sign', 'E'],
        ['docs', 'Filing', 'Fi'],
        ['money', 'Commission', 'Cm'],
        ['portal', 'Portals', 'Pt'],
    ],
    edges: [
        ['contact', 'property'], ['contact', 'fica'], ['contact', 'buyer'],
        ['contact', 'deal'], ['contact', 'docs'],
        ['property', 'deal'], ['property', 'mic'], ['property', 'portal'],
        ['property', 'docs'], ['property', 'buyer'],
        ['deal', 'money'], ['deal', 'esign'], ['deal', 'docs'], ['deal', 'portal'],
        ['buyer', 'mic'], ['fica', 'deal'], ['fica', 'docs'], ['esign', 'docs'],
        ['mic', 'portal'], ['money', 'docs'],
    ],
    /* Four features per module and stories of a similar length, so the panel below
       the ring stays the same height whichever module is selected. */
    features: {
        contact: ['Outreach', 'POPIA opt-in', 'NCC registry', 'Roles on property'],
        property: ['Syndication', 'Mandate', 'CMA value', 'File ref'],
        deal: ['Pipeline', 'Wave 2 cascade', 'Proforma', 'Distribution'],
        fica: ['Multi officers', 'Refer to CO', 'Doc packs', 'Audit trail'],
        mic: ['Buyer demand', 'Prospecting', 'Region filter', 'Tracked stock'],
        buyer: ['Wishlist score', 'Core Matches', 'Viewings', 'MIC tile'],
        esign: ['Consent gates', 'OTP wet-ink', 'Auto filing', 'Lapse / revive'],
        docs: ['PDF splitter', 'One filing truth', 'Shared drive', 'Templates'],
        money: ['VAT commission', 'Payslips', 'SARS IT3(a)', 'Revenue share'],
        portal: ['P24 feed', 'Private Property', 'Lead ingest', 'Portal stats'],
    },
    story: {
        contact: '<b>Contact</b> updated → linked properties pick it up, FICA refreshes, the buyer wishlist rescores, and outreach honours POPIA.',
        property: '<b>Property</b> changed → it syndicates to the portals, market intel restocks, matched buyers re-rank, the file reference follows.',
        deal: '<b>Deal</b> granted → the status cascades to the portals, the purchaser is flagged, commission calculates, documents distribute.',
        fica: '<b>FICA</b> approved → the deal unblocks, the compliance trail files itself, and the compliance officer dashboard updates.',
        mic: '<b>Market intel</b> reads properties, portals and buyer demand — the whole picture, and nothing is ever typed twice.',
        buyer: '<b>Buyer</b> wishlist saved → scored against every listing → market intel shows exactly what to prospect for this buyer.',
        esign: '<b>E-sign</b> ceremony completes → signed documents auto-file to the deal, the property and every party. No manual filing.',
        docs: '<b>Filing</b> has one truth — the PDF splitter, the uploads and the e-sign ceremony all obey the same rules.',
        money: 'Deal registered → <b>commission</b>, VAT, payslips and the SARS trail, all from data already in the system.',
        portal: '<b>Portal</b> leads flow in → contacts are created → deals attach → the whole web enriches itself.',
    },

    /* Ring geometry, in viewBox units. */
    cx: 320,
    cy: 205,
    radius: 170,
    squash: 0.68,

    sel: 'contact',
    linked: 0,
    rot: -Math.PI / 2,
    pos: {},
    drag: null,
    dragged: false,
    spinning: false,
    reduced: false,
    timer: null,

    init() {
        this.reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        this.$nextTick(() => {
            this.layout();
            this.select('contact', !this.reduced);
            /* Idle demo: keep rippling until the visitor takes over. */
            if (!this.reduced) {
                this.timer = setInterval(() => {
                    const i = this.nodes.findIndex((n) => n[0] === this.sel);
                    this.select(this.nodes[(i + 1) % this.nodes.length][0]);
                }, 4500);
            }
        });
    },

    destroy() {
        if (this.timer) clearInterval(this.timer);
    },

    stopDemo() {
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
    },

    layout() {
        const pos = {};
        this.nodes.forEach(([id], i) => {
            const a = this.rot + (i * 2 * Math.PI) / this.nodes.length;
            pos[id] = [
                this.cx + this.radius * Math.cos(a),
                this.cy + this.radius * this.squash * Math.sin(a),
            ];
        });
        this.pos = pos;
        this.draw();
    },

    draw() {
        const edgeLayer = this.$refs.edgeLayer;
        const nodeLayer = this.$refs.nodeLayer;
        if (!edgeLayer || !nodeLayer) return;

        edgeLayer.innerHTML = this.edges
            .map(([a, b]) => {
                const [x1, y1] = this.pos[a];
                const [x2, y2] = this.pos[b];
                return `<line class="web-edge" data-edge="${a}-${b}" x1="${x1}" y1="${y1}" x2="${x2}" y2="${y2}" />`;
            })
            .join('');

        nodeLayer.innerHTML = this.nodes
            .map(([id, label, glyph]) => {
                const [x, y] = this.pos[id];
                return `<g class="web-node" data-node="${id}" role="button" tabindex="0" aria-label="${label}">
                    <circle cx="${x}" cy="${y}" r="26" />
                    <text class="web-glyph" x="${x}" y="${y + 5}" text-anchor="middle">${glyph}</text>
                    <text class="web-label" x="${x}" y="${y + 44}" text-anchor="middle">${label}</text>
                </g>`;
            })
            .join('');

        this.paint();
    },

    /* Light up the selected module, its edges and everything on the far end of them. */
    paint() {
        const touched = new Set([this.sel]);

        this.edges.forEach(([a, b]) => {
            const hot = a === this.sel || b === this.sel;
            if (hot) {
                touched.add(a);
                touched.add(b);
            }
            this.$refs.edgeLayer
                .querySelector(`[data-edge="${a}-${b}"]`)
                ?.classList.toggle('is-hot', hot);
        });

        this.$refs.nodeLayer.querySelectorAll('.web-node').forEach((g) => {
            const id = g.dataset.node;
            g.classList.toggle('is-sel', id === this.sel);
            g.classList.toggle('is-hot', id !== this.sel && touched.has(id));
        });

        this.linked = touched.size - 1;
    },

    select(id, animate = true) {
        this.sel = id;
        this.paint();
        if (animate && !this.reduced) this.pulse(id);
    },

    /* Send a dot down every edge leaving the touched module. */
    pulse(id) {
        const layer = this.$refs.pulseLayer;
        if (!layer) return;
        layer.innerHTML = '';

        this.edges.forEach(([a, b]) => {
            if (a !== id && b !== id) return;
            const [x1, y1] = this.pos[id];
            const [x2, y2] = this.pos[a === id ? b : a];

            const dot = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            dot.setAttribute('class', 'web-pulse');
            dot.setAttribute('r', '4.5');
            layer.appendChild(dot);

            const t0 = performance.now();
            const step = (t) => {
                const k = Math.min(1, (t - t0) / 850);
                dot.setAttribute('cx', x1 + (x2 - x1) * k);
                dot.setAttribute('cy', y1 + (y2 - y1) * k);
                if (k < 1) requestAnimationFrame(step);
                else dot.remove();
            };
            requestAnimationFrame(step);
        });
    },

    spin() {
        this.stopDemo();
        if (this.spinning) return;
        if (this.reduced) {
            this.rot += Math.PI / 5;
            this.layout();
            return;
        }
        this.spinning = true;
        const t0 = performance.now();
        const from = this.rot;
        const step = (t) => {
            const k = Math.min(1, (t - t0) / 1600);
            this.rot = from + 2 * Math.PI * (1 - Math.pow(1 - k, 3));
            this.layout();
            if (k < 1) requestAnimationFrame(step);
            else this.spinning = false;
        };
        requestAnimationFrame(step);
    },

    onDown(e) {
        this.stopDemo();
        this.drag = { x: e.clientX, rot: this.rot, moved: false };
    },

    onMove(e) {
        if (!this.drag) return;
        const dx = e.clientX - this.drag.x;
        if (Math.abs(dx) > 3) this.drag.moved = true;
        this.rot = this.drag.rot + dx * 0.006;
        this.layout();
    },

    onUp() {
        if (!this.drag) return;
        this.dragged = this.drag.moved;
        this.drag = null;
    },

    /* Fires after pointerup — ignore the click that ended a drag. */
    onClick(e) {
        if (this.dragged) {
            this.dragged = false;
            return;
        }
        const g = e.target.closest('.web-node');
        if (g) {
            this.stopDemo();
            this.select(g.dataset.node);
        }
    },

    onKey(e) {
        if (e.key !== 'Enter' && e.key !== ' ') return;
        const g = e.target.closest?.('.web-node');
        if (!g) return;
        e.preventDefault();
        this.stopDemo();
        this.select(g.dataset.node);
    },
}));

window.Alpine = Alpine;
Alpine.start();

/* Keep the accordion's desktop flag in sync across the lg breakpoint. */
window.matchMedia('(min-width: 1024px)').addEventListener('change', (e) => {
    Alpine.store('site').desktop = e.matches;
});

/* Cross-page hash links (e.g. footer → home#features) land with a collapsed
   target section, so the native #hash jump has nothing to scroll to. Expand and
   scroll to it once Alpine is ready. */
(() => {
    const id = window.location.hash.slice(1);
    if (id && document.getElementById(id)) {
        requestAnimationFrame(() => Alpine.store('site').revealSection(id));
    }
})();

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
