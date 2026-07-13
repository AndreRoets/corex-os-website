@verbatim
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CoreX OS — Mobile App</title>
<meta name="description" content="A clickable, fully simulated replica of the CoreX OS mobile app, wrapped in a guided tour. Learn the agent app and the client portal by doing.">
<meta name="robots" content="noindex">

<!-- Theme, applied before paint so there is no flash of the wrong one. Light is
     the default; dark only if the visitor chose it before. The key is shared with
     the marketing site, so a choice made there carries over to here and back. -->
<script>
  (function () {
    var dark = false;
    try { dark = localStorage.getItem('corex-theme') === 'dark'; } catch (e) {}
    document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
  })();
</script>
<style>
/* ============================================================================
   CoreX OS Mobile App — an interactive, fully simulated replica of the Flutter
   app, wrapped in a guided tour. All CSS and JS live in this one file; the only
   asset it pulls is the CoreX logomark from /images.
   The real app uses Inter (body) + Plus Jakarta Sans (display). We cannot load
   webfonts here, so we declare them first and degrade to the system stack.
   ========================================================================== */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

:root{
  /* ---- Brand (server-driven per agency; these are the demo defaults) ---- */
  --accent:#0EA5E9;
  --accent-lite:#38b6f2;      /* accent lightened  8% — button gradient top */
  --accent-dark:#0c93d1;      /* accent darkened   8% — button gradient base */
  --accent-soft:rgba(14,165,233,0.15);
  --accent-glow:rgba(14,165,233,0.25);
  --accent-border:rgba(14,165,233,0.40);

  /* ---- Money gold: a semantic role, NOT a brand colour. Never re-themed. -- */
  --money:#E8B86D;
  --money-soft:rgba(232,184,109,0.15);
  --money-glow:rgba(232,184,109,0.30);

  --navy:#0B2A4A;

  /* ---- Semantic ---- */
  --success:#22C55E;
  --success-delta:#6BD968;
  --warning:#F59E0B;
  --danger:#EF4444;
  --destructive:#DC2626;
  --info:#3B82F6;
  --neutral:#6B7280;
  --neutral-2:#8890A4;

  /* ---- Pillars (contractual) ---- */
  --p-property:#F97316;
  --p-deal:#3B82F6;
  --p-contact:#8B5CF6;
  --p-agent:#8890A4;

  /* ---- Radius scale ---- */
  --r-card:14px; --r-button:12px; --r-small:10px; --r-large:20px; --r-pill:999px;

  --font:'Plus Jakarta Sans','Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;
}

/* ---- Dark palette ---- */
[data-theme="dark"]{
  /* Native widgets (the <select> popup, date/time pickers, scrollbars) are drawn
     by the OS and ignore our CSS. color-scheme is the only way to tell them
     which theme they're in — without it the chapter dropdown opens stark white. */
  color-scheme:dark;
  --page-base:#0A0F1C;
  --page-top-tint:#16243F;
  --surface-top:#1B2440;
  --surface-base:#131A2A;
  --text-primary:#F5F7FB;
  --text-secondary:#8B9AB5;
  --text-tertiary:#5C6B85;
  --text-muted:#3D4660;
  --border:rgba(255,255,255,0.05);
  --hairline:rgba(255,255,255,0.08);
  --fill-subtle:rgba(255,255,255,0.03);
  --fill-chip:rgba(255,255,255,0.06);
  --seat:0 1px 0 rgba(0,0,0,0.30);
  --soft:0 8px 24px -4px rgba(0,0,0,0.45);
  --card-border:transparent;
  --ai-bg:rgba(59,30,99,0.85);
  --ai-fg:#C9B6F5;
  --input-fill:rgba(255,255,255,0.04);
  --scrim:rgba(3,6,14,0.66);
  --elev:rgba(0,0,0,0.45);        /* lifted surfaces: nav, sheets, drawer, dialog */
  --panel-text:#BFCADB;           /* the tour panel is long-form reading, not UI chrome */
  --top-line:rgba(255,255,255,0.05);
}
/* ---- Light palette (the default here; the choice is remembered) ---- */
[data-theme="light"]{
  color-scheme:light;
  --page-base:#E4EAF3;
  --page-top-tint:#D3DCEA;
  --surface-top:#FFFFFF;
  --surface-base:#F6F8FC;
  --text-primary:#0B1426;
  --text-secondary:#4A5878;
  --text-tertiary:#7B8AA6;
  --text-muted:#B0BACB;
  --border:rgba(0,0,0,0.08);
  --hairline:rgba(0,0,0,0.10);
  --fill-subtle:rgba(0,0,0,0.02);
  --fill-chip:rgba(0,0,0,0.05);
  --seat:0 2px 6px rgba(0,0,0,0.06);
  --soft:0 8px 24px -4px rgba(0,0,0,0.05);
  --card-border:rgba(0,0,0,0.08);
  --ai-bg:#EDE4FB;
  --ai-fg:#5B2BB5;
  --input-fill:rgba(0,0,0,0.04);
  --scrim:rgba(20,28,44,0.35);
  /* Light surfaces are lifted with a soft, cool shadow — a near-black one
     reads as dirt on a white card rather than as depth. */
  --elev:rgba(11,20,38,0.16);
  --top-line:rgba(255,255,255,0.9);
  --panel-text:#46536F;
  /* Money keeps its gold identity in light mode, but #E8B86D on white is barely
     legible — so the light theme uses a deeper gold and drops the glow, which
     only smears on a light surface. It is still the one colour money wears, and
     it still survives an agency re-brand; that is what the tour teaches. */
  --money:#9A6B1E;
  --money-soft:rgba(154,107,30,0.12);
  --money-glow:rgba(154,107,30,0.18);
}

html,body{height:100%}
body{
  font-family:var(--font);
  background:var(--page-base);
  color:var(--text-primary);
  -webkit-font-smoothing:antialiased;
  overflow-x:hidden;
}
/* The page's own chrome follows the phone's theme. */
body::before{
  content:'';position:fixed;inset:0;z-index:-1;
  background:radial-gradient(120% 110% at 50% 0%, var(--page-top-tint) 0%, var(--page-base) 55%);
}
button{font:inherit;color:inherit;background:none;border:none;cursor:pointer}
input,textarea,select{font:inherit;color:inherit}
svg{display:block}
::-webkit-scrollbar{width:8px;height:8px}
::-webkit-scrollbar-thumb{background:var(--text-muted);border-radius:99px}
::-webkit-scrollbar-track{background:transparent}

/* ==========================================================================
   PAGE CHROME — top bar, stage, explanation panel
   ========================================================================== */
.topbar{
  position:sticky;top:0;z-index:60;
  display:flex;align-items:center;gap:14px;flex-wrap:wrap;
  padding:12px 20px;
  background:color-mix(in srgb, var(--page-base) 82%, transparent);
  backdrop-filter:blur(14px);
  border-bottom:1px solid var(--border);
}
/* The logo is the way back to the website. */
.wordmark{
  display:flex;align-items:center;gap:9px;
  font-weight:800;font-size:18px;letter-spacing:-.3px;
  color:inherit;text-decoration:none;
  border-radius:10px;padding:2px 6px 2px 2px;margin-left:-2px;
  transition:background .18s ease,opacity .18s ease;
}
.wordmark:hover{background:var(--fill-chip)}
.wordmark:focus-visible{outline:2px solid var(--accent);outline-offset:2px}
.wordmark .os{
  background:linear-gradient(135deg,var(--accent-lite),var(--accent-dark));
  -webkit-background-clip:text;background-clip:text;color:transparent;
}
/* The real CoreX logomark. It is drawn in navy on transparent, so it needs a
   light chip behind it — on the dark top bar it would otherwise disappear. */
.mark{
  width:30px;height:30px;border-radius:9px;flex:none;
  display:grid;place-items:center;padding:3px;
  background:#fff;
  box-shadow:0 6px 16px -6px var(--accent-glow), 0 0 0 1px var(--border);
}
.mark img{width:100%;height:100%;object-fit:contain;display:block}
.tb-spacer{flex:1}
.tb-group{display:flex;align-items:center;gap:6px;padding:4px;border-radius:var(--r-pill);background:var(--fill-chip);border:1px solid var(--border)}
.tb-btn{
  display:flex;align-items:center;gap:6px;
  padding:7px 13px;border-radius:var(--r-pill);
  font-size:12.5px;font-weight:600;color:var(--text-secondary);
  transition:all .18s ease;white-space:nowrap;
}
.tb-btn:hover{color:var(--text-primary)}
.tb-btn[aria-pressed="true"],.tb-btn.on{
  background:var(--accent);color:#fff;
  box-shadow:0 6px 16px -8px var(--accent-glow);
}
.tb-icon{
  width:36px;height:36px;border-radius:11px;display:grid;place-items:center;
  border:1px solid var(--border);background:var(--fill-subtle);color:var(--text-secondary);
  transition:all .18s ease;
}
.tb-icon:hover{color:var(--text-primary);border-color:var(--hairline)}
.tb-label{font-size:10px;font-weight:700;letter-spacing:1.4px;text-transform:uppercase;color:var(--text-tertiary);margin-right:2px}
.tb-select{
  appearance:none;padding:8px 30px 8px 12px;border-radius:10px;
  /* Opaque, not a translucent fill: the popup list inherits this colour, and a
     see-through background would render as white. */
  background-color:var(--surface-base);
  border:1px solid var(--border);
  font-size:12.5px;font-weight:600;color:var(--text-primary);cursor:pointer;
  background-image:linear-gradient(45deg,transparent 50%,var(--text-tertiary) 50%),linear-gradient(135deg,var(--text-tertiary) 50%,transparent 50%);
  background-position:calc(100% - 15px) 16px,calc(100% - 10px) 16px;
  background-size:5px 5px,5px 5px;background-repeat:no-repeat;
}
.tb-select:focus{outline:none;border-color:var(--accent)}
.tb-select option{
  background-color:var(--surface-base);
  color:var(--text-primary);
  font-weight:600;
}
/* ---- Feature search ---- */
.search{position:relative;flex:1 1 260px;max-width:360px;min-width:230px}
.search-ico{
  position:absolute;left:12px;top:50%;transform:translateY(-50%);
  color:var(--text-tertiary);pointer-events:none;display:flex;
}
.search input{
  width:100%;height:38px;padding:0 40px 0 38px;
  border-radius:var(--r-pill);
  background:var(--fill-subtle);
  border:1px solid var(--border);
  font-size:13px;font-weight:500;color:var(--text-primary);
  outline:none;transition:border-color .18s ease,background .18s ease;
}
.search input::placeholder{color:var(--text-tertiary)}
.search input:focus{border-color:var(--accent);background:var(--fill-chip)}
.search-kbd{
  position:absolute;right:11px;top:50%;transform:translateY(-50%);
  font-family:inherit;font-size:11px;font-weight:700;color:var(--text-tertiary);
  border:1px solid var(--border);border-radius:5px;padding:1px 6px;pointer-events:none;
}
.search input:focus ~ .search-kbd{display:none}
.search-results{
  /* Wider than the input, so a result title and its chapter fit on one line. */
  position:absolute;top:calc(100% + 8px);left:0;z-index:70;
  width:430px;max-width:calc(100vw - 40px);
  max-height:62vh;overflow-y:auto;padding:6px;
  border-radius:var(--r-card);
  background:linear-gradient(to bottom,var(--surface-top),var(--surface-base));
  border:1px solid var(--border);
  box-shadow:0 24px 60px -16px var(--elev);
}
.search-results[hidden]{display:none}
.sr-item{
  display:block;width:100%;text-align:left;padding:9px 11px;border-radius:9px;
  transition:background .12s ease;
}
.sr-item:hover,.sr-item.sel{background:var(--accent-soft)}
.sr-top{display:flex;align-items:baseline;gap:10px;margin-bottom:3px}
.sr-title{
  font-size:13px;font-weight:700;color:var(--text-primary);flex:1;min-width:0;
  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
}
.sr-chap{
  font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;
  color:var(--accent);flex:none;white-space:nowrap;
}
.sr-snip{font-size:11.5px;color:var(--text-secondary);line-height:1.4;
  display:-webkit-box;-webkit-line-clamp:1;line-clamp:1;-webkit-box-orient:vertical;overflow:hidden}
.sr-empty{padding:16px 12px;text-align:center;font-size:12.5px;color:var(--text-tertiary)}
.sr-hit{color:var(--accent);font-weight:700}

.swatches{display:flex;gap:5px;align-items:center}
.swatch{
  width:20px;height:20px;border-radius:7px;border:2px solid transparent;
  transition:transform .16s ease,border-color .16s ease;
}
.swatch:hover{transform:scale(1.12)}
.swatch[aria-pressed="true"]{border-color:var(--text-primary);transform:scale(1.12)}

.layout{
  display:grid;grid-template-columns:minmax(0,1fr) minmax(360px,470px);
  gap:32px;align-items:start;
  max-width:1420px;margin:0 auto;padding:28px 24px 60px;
}
/* Desktop: the whole thing lives on one screen. The page itself never scrolls —
   the phone is scaled down to whatever height is left over, and the explanation
   panel scrolls internally. --ps (phone scale) is measured in JS on resize. */
@media(min-width:1081px){
  html,body{height:100%;overflow:hidden}
  .layout{
    height:calc(100dvh - var(--tb, 62px));
    /* A single row that is exactly the container's height. Without this the row
       is sized by its content, so the phone would just push it past the fold. */
    grid-template-rows:minmax(0,1fr);
    padding:18px 24px;
    align-items:center;
  }
  .stage{position:static;top:auto;min-height:0;justify-content:center}
  .panel{position:static;top:auto;height:100%;max-height:100%}
}
@media(max-width:1080px){
  .layout{grid-template-columns:1fr;gap:24px}
  .stage{position:static!important}
}

/* ---- Phone ---- */
.stage{position:sticky;top:86px;display:flex;flex-direction:column;align-items:center;gap:14px}
/* The wrapper reserves the phone's *scaled* footprint in the layout; the phone
   inside is transform-scaled from its true 414x868. Scaling the phone directly
   would leave its unscaled size behind in the flow. */
.phone-wrap{
  position:relative;flex:none;
  width:calc(414px * var(--ps, 1));
  height:calc(868px * var(--ps, 1));
}
.phone{
  position:absolute;top:0;left:0;
  width:414px;height:868px;flex:none;
  transform:scale(var(--ps, 1));transform-origin:top left;
  border-radius:56px;padding:12px;
  background:linear-gradient(160deg,#2b3242,#0e1117 45%,#242a36);
  box-shadow:
    0 0 0 1px rgba(255,255,255,.06),
    0 50px 110px -30px rgba(0,0,0,.75),
    0 0 90px -20px var(--accent-glow);
}
[data-theme="light"] .phone{box-shadow:0 0 0 1px rgba(0,0,0,.10),0 50px 110px -34px rgba(11,20,38,.35),0 0 90px -24px var(--accent-glow)}
.screen{
  position:relative;width:390px;height:844px;overflow:hidden;
  border-radius:46px;
  background:radial-gradient(120% 110% at 50% 0%, var(--page-top-tint) 0%, var(--page-base) 55%);
  color:var(--text-primary);
  display:flex;flex-direction:column;
  isolation:isolate;
}
.island{
  position:absolute;top:10px;left:50%;transform:translateX(-50%);
  width:118px;height:32px;border-radius:99px;background:#05070c;z-index:40;pointer-events:none;
}
.island::after{content:'';position:absolute;right:11px;top:11px;width:9px;height:9px;border-radius:99px;background:#12202e;box-shadow:inset 0 0 3px #0b3a52}
.statusbar{
  height:52px;flex:none;display:flex;align-items:flex-end;justify-content:space-between;
  padding:0 26px 5px;font-size:12.5px;font-weight:700;color:var(--text-primary);z-index:30;
}
.statusbar .sb-right{display:flex;align-items:center;gap:5px;opacity:.9}
.homebar{
  position:absolute;bottom:7px;left:50%;transform:translateX(-50%);
  width:134px;height:5px;border-radius:99px;background:var(--text-primary);opacity:.28;z-index:45;pointer-events:none;
}
.phone-caption{font-size:12px;color:var(--text-tertiary);text-align:center;max-width:400px;line-height:1.5}
.phone-caption b{color:var(--text-secondary);font-weight:700}

/* The scrollable app body inside the phone. It's a flex column so that screens
   which centre themselves (splash, login, Ellie) can claim the full height. */
.view{
  flex:1;overflow-y:auto;overflow-x:hidden;position:relative;
  display:flex;flex-direction:column;
  scrollbar-width:none;-webkit-overflow-scrolling:touch;
}
.view > *{flex-shrink:0}
.view::-webkit-scrollbar{display:none}
.view.has-nav{padding-bottom:98px}
/* A floating action button sits 92px up and is 56 tall, so the last row of a
   list needs to clear ~150px or the FAB covers it. Declared after .has-nav so
   it wins on screens that have both. */
.view.has-fab{padding-bottom:152px}
.pad{padding:0 16px}

/* ==========================================================================
   APP COMPONENTS — reproduced from the Flutter design system
   ========================================================================== */

/* Card: gradient + top highlight line + seat shadow. Elevation is always 0. */
.card{
  position:relative;border-radius:var(--r-card);padding:16px;
  background:linear-gradient(to bottom,var(--surface-top),var(--surface-base));
  box-shadow:var(--seat), inset 0 1px 0 var(--top-line);
  border:1px solid var(--card-border);
}
.card.soft{box-shadow:var(--soft), inset 0 1px 0 var(--top-line)}
/* Accent card: 2px left border + a leftward accent halo */
.card.accent{
  border-left:2px solid var(--accent);
  box-shadow:-10px 0 24px -10px var(--accent-glow), var(--seat), inset 0 1px 0 var(--top-line);
}
.card.hero{box-shadow:0 16px 40px -12px var(--accent-glow), inset 0 1px 0 var(--top-line)}
.press{transition:transform .14s ease}
.press:active{transform:scale(.96)}

/* Eyebrow — the signature element */
.eyebrow{font-size:10px;font-weight:700;letter-spacing:1.4px;text-transform:uppercase;color:var(--accent)}
.eyebrow.mute{color:var(--text-tertiary)}

.h-greet{font-size:24px;font-weight:700;letter-spacing:-.4px}
.h-screen{font-size:22px;font-weight:700}
.h-bar{font-size:20px;font-weight:700;letter-spacing:-.3px}
.h-card{font-size:18px;font-weight:700;letter-spacing:-.25px}
.h-sec{font-size:16px;font-weight:700}
.h-row{font-size:15px;font-weight:700;letter-spacing:-.2px}
.t-body{font-size:14px;font-weight:600;letter-spacing:-.1px}
.t-sub{font-size:12.5px;font-weight:500;color:var(--text-secondary);line-height:1.45}
.t-ter{color:var(--text-tertiary)}
.money{color:var(--money);font-weight:700;text-shadow:0 0 10px var(--money-glow)}

/* Icon box — the recurring motif */
.ibox{border-radius:var(--r-small);display:grid;place-items:center;flex:none}
.ibox.s32{width:32px;height:32px;border-radius:9px}
.ibox.s40{width:40px;height:40px;border-radius:12px}
.ibox.s64{width:64px;height:64px;border-radius:14px}

/* Buttons */
.btn{
  width:100%;height:56px;border-radius:var(--r-button);
  display:flex;align-items:center;justify-content:center;gap:8px;
  background:linear-gradient(to bottom,var(--accent-lite),var(--accent-dark));
  color:#fff;font-size:15px;font-weight:700;letter-spacing:.2px;
  box-shadow:0 12px 28px -10px var(--accent-glow);
  transition:transform .14s ease,opacity .14s ease;
}
.btn:active{transform:scale(.98)}
.btn:disabled{opacity:.55;box-shadow:none;cursor:not-allowed}
.btn.sm{height:46px;font-size:14px}
.btn.danger{background:linear-gradient(to bottom,#f26060,#d93a3a);box-shadow:0 12px 28px -10px rgba(239,68,68,.35)}
.btn.green{background:linear-gradient(to bottom,#3ad176,#1da851);box-shadow:0 12px 28px -10px rgba(34,197,94,.35)}
.btn2{
  width:100%;height:52px;border-radius:var(--r-button);
  display:flex;align-items:center;justify-content:center;gap:8px;
  background:var(--fill-subtle);border:1px solid var(--hairline);
  color:var(--text-primary);font-size:14px;font-weight:600;
  transition:transform .14s ease,background .14s ease;
}
.btn2:active{transform:scale(.98)}
.btn2.sm{height:42px;font-size:13px}
.btn2.red{color:var(--danger);border-color:rgba(239,68,68,.30)}
.linkbtn{font-size:13px;font-weight:700;color:var(--accent);display:inline-flex;align-items:center;gap:4px}

/* Chips */
.chip{
  display:inline-flex;align-items:center;gap:5px;
  padding:4px 10px;border-radius:var(--r-pill);
  font-size:11px;font-weight:600;letter-spacing:.2px;
  background:var(--fill-chip);color:var(--text-secondary);
  transition:all .18s ease;white-space:nowrap;
}
.chip.accent{background:var(--accent-soft);color:var(--accent)}
.chip.money{background:var(--money-soft);color:var(--money)}
.status{
  display:inline-flex;align-items:center;gap:5px;
  padding:5px 10px;border-radius:var(--r-pill);
  font-size:12px;font-weight:600;letter-spacing:.1px;white-space:nowrap;
}
.status.dense{font-size:11px;padding:3px 8px}
/* Pillar chip — 3px radius, never a pill. Renders NOTHING when there's no pillar. */
.pillar{
  display:inline-flex;padding:2px 5px;border-radius:3px;
  font-size:9px;font-weight:600;letter-spacing:.6px;text-transform:uppercase;
}
/* AI badge — purple, always. Never re-branded. */
.ai{
  display:inline-flex;align-items:center;gap:3px;
  padding:3px 6px;border-radius:6px;
  background:var(--ai-bg);color:var(--ai-fg);
  border:1px solid color-mix(in srgb,var(--ai-fg) 35%,transparent);
  font-size:10px;font-weight:700;letter-spacing:.3px;
}
.ai svg{width:11px;height:11px}

/* Toggle chip (feature pickers, filters) */
.tchip{
  padding:7px 12px;border-radius:var(--r-pill);
  border:1px solid var(--hairline);background:var(--fill-subtle);
  font-size:12px;font-weight:600;color:var(--text-secondary);
  display:inline-flex;align-items:center;gap:6px;transition:all .18s ease;
}
.tchip.on{background:var(--accent-soft);border-color:var(--accent-border);color:var(--accent)}
.tchip:active{transform:scale(.96)}

/* Segmented control */
.seg{display:flex;gap:4px;padding:4px;border-radius:var(--r-button);background:var(--fill-chip)}
.seg button{
  flex:1;padding:9px 8px;border-radius:9px;
  font-size:12.5px;font-weight:600;color:var(--text-secondary);transition:all .18s ease;
}
.seg button.on{background:var(--accent);color:#fff;box-shadow:0 6px 14px -8px var(--accent-glow)}

/* Inputs — label ABOVE, no border at rest, 1.5px accent on focus */
.field{margin-bottom:14px}
.field > label{display:block;font-size:12.5px;font-weight:600;color:var(--text-secondary);margin-bottom:7px}
.field .req{color:var(--destructive);margin-left:2px}
.inp{
  width:100%;min-height:52px;padding:15px 16px;
  background:var(--input-fill);border:1.5px solid transparent;border-radius:var(--r-button);
  font-size:14px;font-weight:600;color:var(--text-primary);
  transition:border-color .18s ease,background .18s ease;outline:none;
}
.inp::placeholder{color:var(--text-muted);font-weight:500}
.inp:focus{border-color:var(--accent);background:var(--fill-subtle)}
textarea.inp{min-height:auto;resize:none;line-height:1.5}
.inp.err{border-color:var(--danger)}
.err-msg{font-size:12px;color:var(--danger);margin-top:6px;font-weight:600}
.inp-wrap{position:relative;display:flex;align-items:center}
.inp-wrap .pre{position:absolute;left:15px;color:var(--text-tertiary);pointer-events:none}
.inp-wrap .pre ~ .inp{padding-left:46px}
.inp-wrap .suf{position:absolute;right:10px;padding:8px;color:var(--text-tertiary);border-radius:8px}
.picker{
  width:100%;min-height:52px;padding:15px 16px;border-radius:var(--r-button);
  background:var(--input-fill);border:1.5px solid transparent;
  display:flex;align-items:center;justify-content:space-between;gap:10px;
  font-size:14px;font-weight:600;text-align:left;transition:all .18s ease;
}
.picker.placeholder{color:var(--text-muted);font-weight:500}
.picker:disabled{opacity:.5;cursor:not-allowed}
.picker:not(:disabled):active{border-color:var(--accent)}
.hint{font-size:11.5px;color:var(--text-tertiary);margin-top:6px}
.counter{font-size:11px;color:var(--text-tertiary);text-align:right;margin-top:5px}

/* Switch */
.sw{width:44px;height:26px;border-radius:99px;background:var(--fill-chip);position:relative;transition:background .2s ease;flex:none}
.sw::after{content:'';position:absolute;top:3px;left:3px;width:20px;height:20px;border-radius:99px;background:var(--text-tertiary);transition:transform .2s ease,background .2s ease}
.sw.on{background:var(--accent-soft)}
.sw.on::after{transform:translateX(18px);background:var(--accent)}

/* Rows */
.row{display:flex;align-items:center;gap:12px}
.rowbtn{
  width:100%;display:flex;align-items:center;gap:12px;padding:14px 16px;
  border-radius:var(--r-card);text-align:left;
  background:linear-gradient(to bottom,var(--surface-top),var(--surface-base));
  box-shadow:var(--seat), inset 0 1px 0 var(--top-line);
  border:1px solid var(--card-border);
  transition:transform .14s ease;
}
.rowbtn:active{transform:scale(.98)}
.stack{display:flex;flex-direction:column;gap:10px}
.grow{flex:1;min-width:0}
.trunc{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.clamp2{display:-webkit-box;-webkit-line-clamp:2;line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.divider{height:1px;background:var(--border);margin:14px 0}
.divider.accent{background:var(--accent-soft)}
.sec-head{display:flex;align-items:center;justify-content:space-between;margin:22px 0 12px}

/* App bar */
.appbar{
  height:60px;flex:none;display:flex;align-items:center;gap:10px;padding:0 14px;z-index:20;
}
.appbar .title{font-size:18px;font-weight:700;letter-spacing:-.3px;flex:1}
.iconbtn{
  width:38px;height:38px;border-radius:11px;display:grid;place-items:center;
  color:var(--text-secondary);position:relative;transition:background .16s ease;flex:none;
}
.iconbtn:active{background:var(--fill-chip)}
.dot-badge{position:absolute;top:6px;right:6px;width:8px;height:8px;border-radius:99px;background:var(--accent);box-shadow:0 0 0 2px var(--surface-base)}
.avatar{
  width:36px;height:36px;border-radius:12px;flex:none;
  display:grid;place-items:center;font-size:13px;font-weight:700;
  background:var(--accent-soft);border:1px solid var(--accent-border);color:var(--accent);
}
.avatar.circle{border-radius:99px}
.avatar.s44{width:44px;height:44px;font-size:15px}
.avatar.s96{width:96px;height:96px;border-radius:28px;font-size:32px;font-weight:800;box-shadow:0 16px 40px -12px var(--accent-glow)}

/* Bottom nav — a FLOATING PILL, not an edge-to-edge bar */
.bnav{
  position:absolute;left:16px;right:16px;bottom:12px;height:64px;z-index:35;
  border-radius:var(--r-large);display:flex;align-items:center;
  background:linear-gradient(to bottom,var(--surface-top),var(--surface-base));
  box-shadow:0 10px 24px -8px var(--elev), inset 0 1px 0 var(--top-line);
  border:1px solid var(--card-border);
}
.bnav button{
  flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;
  color:var(--text-tertiary);font-size:10px;font-weight:500;transition:color .2s ease;
}
.bnav button.on{color:var(--accent);font-weight:700}
.bnav button svg{width:22px;height:22px}
.bnav button:active{transform:scale(.94)}

/* FAB */
.fab{
  position:absolute;right:20px;bottom:92px;z-index:36;
  width:56px;height:56px;border-radius:18px;display:grid;place-items:center;
  background:linear-gradient(to bottom,var(--accent-lite),var(--accent-dark));color:#fff;
  box-shadow:0 14px 30px -8px var(--accent-glow),0 0 24px -6px var(--accent-glow);
  transition:transform .14s ease;
}
.fab:active{transform:scale(.94)}

/* Scrim + bottom sheet (this app has NO centred modals except destructive confirms) */
.scrim{position:absolute;inset:0;z-index:50;background:var(--scrim);backdrop-filter:blur(2px);animation:fade .2s ease}
.sheet{
  position:absolute;left:0;right:0;bottom:0;z-index:51;max-height:88%;
  display:flex;flex-direction:column;
  border-radius:var(--r-large) var(--r-large) 0 0;
  background:linear-gradient(to bottom,var(--surface-top),var(--surface-base));
  box-shadow:0 -20px 50px -12px var(--elev);
  animation:up .28s cubic-bezier(.22,1,.36,1);
}
.sheet .grab{width:36px;height:4px;border-radius:99px;background:var(--text-muted);margin:10px auto 4px;flex:none}
.sheet .s-head{display:flex;align-items:center;gap:10px;padding:8px 18px 12px;flex:none}
.sheet .s-head .h-card{flex:1}
.sheet .s-body{overflow-y:auto;padding:4px 18px 8px;scrollbar-width:none}
.sheet .s-body::-webkit-scrollbar{display:none}
.sheet .s-foot{padding:12px 18px 22px;flex:none}
.dialog{
  position:absolute;left:24px;right:24px;top:50%;transform:translateY(-50%);z-index:51;
  border-radius:var(--r-large);padding:22px;
  background:linear-gradient(to bottom,var(--surface-top),var(--surface-base));
  box-shadow:0 30px 70px -20px var(--elev);
  border:1px solid var(--card-border);
  animation:pop .22s cubic-bezier(.22,1,.36,1);
}
.dialog .d-actions{display:flex;gap:10px;margin-top:20px}
.dialog .d-actions > *{flex:1}
.drawer{
  position:absolute;top:0;bottom:0;left:0;width:290px;z-index:52;
  background:linear-gradient(to bottom,var(--surface-top),var(--surface-base));
  box-shadow:20px 0 60px -20px var(--elev);
  animation:slide .26s cubic-bezier(.22,1,.36,1);
  display:flex;flex-direction:column;padding:60px 14px 24px;
}
.snack{
  position:absolute;left:16px;right:16px;bottom:92px;z-index:60;
  padding:13px 16px;border-radius:var(--r-button);
  background:#1E2740;color:#F5F7FB;font-size:13px;font-weight:600;
  box-shadow:0 16px 36px -10px var(--elev);
  display:flex;align-items:center;gap:9px;
  animation:up .24s cubic-bezier(.22,1,.36,1);
}
.snack.amber{background:#F59E0B;color:#20160A}
.snack.green{background:#1da851;color:#fff}
.snack.red{background:#d93a3a;color:#fff}

@keyframes fade{from{opacity:0}to{opacity:1}}
@keyframes up{from{transform:translateY(30px);opacity:0}to{transform:translateY(0);opacity:1}}
@keyframes pop{from{transform:translateY(-50%) scale(.94);opacity:0}to{transform:translateY(-50%) scale(1);opacity:1}}
@keyframes slide{from{transform:translateX(-100%)}to{transform:translateX(0)}}
/* Tab change: a 260ms cross-fade + subtle scale, NOT a slide */
@keyframes tabin{from{opacity:0;transform:scale(.985)}to{opacity:1;transform:scale(1)}}
.tabin{animation:tabin .26s cubic-bezier(.215,.61,.355,1)}

/* Property thumbnails — CSS gradient placeholders with a house watermark */
.thumb{
  position:relative;border-radius:var(--r-small);overflow:hidden;flex:none;
  display:grid;place-items:center;color:rgba(255,255,255,.30);
}
.thumb::after{
  content:'';position:absolute;inset:0;
  background:linear-gradient(160deg,rgba(255,255,255,.14),transparent 45%);
}
.thumb.s76{width:76px;height:76px}
.g0{background:linear-gradient(150deg,#1e3a8a,#0ea5e9)}
.g1{background:linear-gradient(150deg,#134e4a,#22c55e)}
.g2{background:linear-gradient(150deg,#7c2d12,#f97316)}
.g3{background:linear-gradient(150deg,#4c1d95,#8b5cf6)}
.g4{background:linear-gradient(150deg,#0f172a,#475569)}
.g5{background:linear-gradient(150deg,#831843,#e11d48)}
.g6{background:linear-gradient(150deg,#164e63,#a5b4fc)}
.g7{background:linear-gradient(150deg,#3f2d0b,#e8b86d)}

/* ==========================================================================
   EXPLANATION PANEL — where the teaching happens
   ========================================================================== */
.panel{
  border-radius:var(--r-large);
  background:linear-gradient(to bottom,var(--surface-top),var(--surface-base));
  box-shadow:var(--soft), inset 0 1px 0 var(--top-line);
  border:1px solid var(--card-border);
  display:flex;flex-direction:column;max-height:calc(100vh - 120px);
  position:sticky;top:86px;
}
@media(max-width:1080px){.panel{position:static;max-height:none}}
.panel-head{padding:20px 22px 14px;border-bottom:1px solid var(--border)}
.panel-body{padding:20px 22px;overflow-y:auto;flex:1}
.panel-foot{padding:14px 22px 18px;border-top:1px solid var(--border);display:flex;align-items:center;gap:10px}
.progress{height:4px;border-radius:99px;background:var(--fill-chip);overflow:hidden;margin-top:14px}
.progress i{display:block;height:100%;border-radius:99px;background:linear-gradient(90deg,var(--accent-lite),var(--accent));transition:width .35s cubic-bezier(.22,1,.36,1)}
.panel h2{font-size:20px;font-weight:700;letter-spacing:-.3px;margin-bottom:6px}
.panel h3{font-size:15px;font-weight:700;margin:20px 0 8px;letter-spacing:-.1px}
.panel p{font-size:14px;line-height:1.65;color:var(--panel-text);margin-bottom:12px}
.panel p:last-child{margin-bottom:0}
.panel strong{color:var(--text-primary);font-weight:700}
.panel em{color:var(--text-primary);font-style:italic}
.panel ul{margin:0 0 12px;padding-left:18px}
.panel li{font-size:14px;line-height:1.6;color:var(--panel-text);margin-bottom:7px}
.panel li::marker{color:var(--accent)}
.panel code{
  font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;font-size:12.5px;
  background:var(--fill-chip);padding:2px 6px;border-radius:5px;color:var(--text-primary);
}
.callout{
  border-radius:var(--r-card);padding:14px 16px;margin:14px 0;
  border-left:3px solid var(--accent);background:var(--accent-soft);
}
.callout .ttl{font-size:11px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:var(--accent);margin-bottom:6px;display:flex;align-items:center;gap:6px}
.callout p{color:var(--text-primary);font-size:13.5px;margin:0;line-height:1.6}
.callout.warn{border-left-color:var(--warning);background:rgba(245,158,11,.12)}
.callout.warn .ttl{color:var(--warning)}
.callout.gold{border-left-color:var(--money);background:var(--money-soft)}
.callout.gold .ttl{color:var(--money)}
.callout.purple{border-left-color:#8B5CF6;background:rgba(139,92,246,.13)}
.callout.purple .ttl{color:#A78BFA}
[data-theme="light"] .callout.purple .ttl{color:#5B2BB5}
.tryit{
  display:flex;gap:10px;align-items:flex-start;
  border-radius:var(--r-card);padding:13px 15px;margin:14px 0;
  background:var(--fill-subtle);border:1px dashed var(--hairline);
}
.tryit .k{font-size:10px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:var(--success);margin-bottom:4px}
.tryit p{font-size:13.5px;margin:0;color:var(--text-secondary)}
/* A little browser window in the explanation panel, for showing the web app's
   AI review screen — which has no equivalent on the phone. */
.webmock{
  border-radius:12px;overflow:hidden;margin:14px 0;
  border:1px solid var(--border);
  background:linear-gradient(to bottom,var(--surface-top),var(--surface-base));
  box-shadow:var(--soft);
}
.webmock-bar{
  display:flex;align-items:center;gap:6px;padding:8px 11px;
  background:var(--fill-chip);border-bottom:1px solid var(--border);
}
.webmock-bar .dot{width:9px;height:9px;border-radius:99px;flex:none}
.webmock-url{
  margin-left:8px;font-size:10.5px;font-weight:600;color:var(--text-tertiary);
  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
}
.webmock-body{padding:14px}

.chapnav{display:flex;flex-wrap:wrap;gap:6px;margin-top:14px}
.step-dots{display:flex;gap:5px;flex:1}
.step-dots i{height:5px;flex:1;border-radius:99px;background:var(--fill-chip);transition:background .2s ease}
.step-dots i.on{background:var(--accent)}
.step-dots i.done{background:var(--accent-border)}
.kbd{font-size:11px;font-weight:700;color:var(--text-tertiary)}

/* Highlight ring driven by the tour. Sits above content but BELOW the floating
   nav (35) and any sheet (51), so it can never paint over the app's chrome. */
.hl{position:relative;z-index:30;animation:hl 1.6s ease-in-out infinite;border-radius:12px}
@keyframes hl{
  0%,100%{box-shadow:0 0 0 2px var(--accent),0 0 0 6px var(--accent-glow)}
  50%{box-shadow:0 0 0 2px var(--accent),0 0 0 12px transparent}
}

@media (prefers-reduced-motion:reduce){
  *,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important}
  .press:active,.btn:active,.fab:active{transform:none!important}
}
</style>
</head>
<body>

<header class="topbar">
  <a class="wordmark" href="/" title="Back to the CoreX OS home page" aria-label="CoreX OS — back to home page">
    <span class="mark"><img src="/images/corex-mark.png" alt="" width="512" height="512" decoding="async"></span>
    CoreX <span class="os">OS</span>
  </a>
  <span class="chip accent" style="margin-left:2px">Interactive Guide</span>

  <div class="search" id="search">
    <span class="search-ico" id="searchIco"></span>
    <input id="searchInput" type="text" role="combobox" aria-expanded="false" aria-controls="searchResults"
           aria-autocomplete="list" autocomplete="off" spellcheck="false"
           aria-label="Search features"
           placeholder="Search a feature&hellip;">
    <kbd class="search-kbd">/</kbd>
    <div class="search-results" id="searchResults" role="listbox" hidden></div>
  </div>

  <div class="tb-spacer"></div>

  <span class="tb-label">Chapter</span>
  <select class="tb-select" id="chapterSel" aria-label="Jump to chapter"></select>

  <div class="tb-group" role="group" aria-label="Which side of the app">
    <button class="tb-btn" id="sideAgent" data-side="agent" aria-pressed="true">Agent app</button>
    <button class="tb-btn" id="sideClient" data-side="client" aria-pressed="false">Client portal</button>
  </div>

  <div class="tb-group" title="Agency theme — the app is white-labelled, colours come from the API">
    <span class="tb-label" style="padding-left:8px">Agency</span>
    <div class="swatches" id="swatches"></div>
  </div>

  <button class="tb-icon" id="themeBtn" title="Light / dark theme" aria-label="Toggle theme"></button>
  <button class="tb-icon" id="restartBtn" title="Restart the tour" aria-label="Restart tour"></button>
</header>

<div class="layout">
  <div class="stage">
    <div class="phone-wrap" id="phoneWrap">
      <div class="phone">
        <div class="island"></div>
        <div class="screen" id="screen">
          <div class="statusbar">
            <span id="sbClock">09:41</span>
            <span class="sb-right" id="sbRight"></span>
          </div>
          <div id="app" style="flex:1;display:flex;flex-direction:column;min-height:0"></div>
          <div class="homebar"></div>
        </div>
      </div>
    </div>
    <p class="phone-caption" id="phoneCaption">
      Everything in this phone is <b>live and clickable</b> — tap around freely.
      All data is simulated in your browser; nothing is sent anywhere.
    </p>
  </div>

  <aside class="panel" id="panel">
    <div class="panel-head">
      <div class="eyebrow" id="chapEyebrow">CHAPTER 1 OF 1</div>
      <h2 id="stepTitle">Loading…</h2>
      <div class="progress"><i id="progressBar" style="width:0%"></i></div>
    </div>
    <div class="panel-body" id="stepBody"></div>
    <div class="panel-foot">
      <button class="btn2 sm" id="prevBtn" style="width:auto;padding:0 16px">Back</button>
      <div class="step-dots" id="stepDots"></div>
      <button class="btn sm" id="nextBtn" style="width:auto;padding:0 20px">Next</button>
    </div>
  </aside>
</div>

<script>
/* ============================================================================
   1. ICONS — hand-written Tabler-style SVG (24x24, 2px stroke, round caps).
   The real app uses Tabler Icons. No emoji as UI icons, no Material.
   ========================================================================== */
const P = {
  'menu-2':'M4 6h16M4 12h16M4 18h16',
  'qrcode':'M4 4h6v6h-6zM14 4h6v6h-6zM4 14h6v6h-6zM14 14h2v2h-2zM18 14h2v2h-2zM14 18h2v2h-2zM18 18h2v2h-2z',
  'bell':'M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6M9 17v1a3 3 0 0 0 6 0v-1',
  'home-2':'M5 12h-2l9 -9l9 9h-2M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7M10 21v-6h4v6',
  'calendar-event':'M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2zM16 3v4M8 3v4M4 11h16M8 15h2v2h-2z',
  'calendar':'M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2zM16 3v4M8 3v4M4 11h16',
  'calendar-time':'M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4M3 10h18M16 3v4M8 3v4M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0M18 16.5v1.5l.5 .5',
  'sparkles':'M16 18a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2zM4 6a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2zM14.66 7.34l-1.7 -1.7a1 1 0 0 0 -1.42 0l-8.24 8.24a1 1 0 0 0 0 1.42l1.7 1.7a1 1 0 0 0 1.42 0l8.24 -8.24a1 1 0 0 0 0 -1.42z',
  'user-circle':'M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855',
  'building-skyscraper':'M3 21h18M5 21v-14l8 -4v18M19 21v-10l-6 -4M9 9h.01M9 12h.01M9 15h.01M9 18h.01',
  'users':'M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2M16 3.13a4 4 0 0 1 0 7.75M21 21v-2a4 4 0 0 0 -3 -3.85',
  'heart-handshake':'M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572M12 6l-2 2l2 2M12 10l2 -2',
  'target-arrow':'M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0M12 12m-5 0a5 5 0 1 0 10 0a5 5 0 1 0 -10 0M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0M12 3l0 3M12 18l0 3M3 12l3 0M18 12l3 0',
  'chevron-right':'M9 6l6 6l-6 6','chevron-left':'M15 6l-6 6l6 6',
  'chevron-down':'M6 9l6 6l6 -6','chevron-up':'M6 15l6 -6l6 6',
  'plus':'M12 5v14M5 12h14','minus':'M5 12h14','x':'M18 6l-12 12M6 6l12 12',
  'check':'M5 12l5 5l10 -10',
  'circle-check':'M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0M9 12l2 2l4 -4',
  'circle-x':'M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0M10 10l4 4m0 -4l-4 4',
  'circle':'M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0',
  'alert-circle':'M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0M12 8v4M12 16h.01',
  'alert-triangle':'M12 9v4M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0zM12 16h.01',
  'clock':'M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0M12 7v5l3 3',
  'map-pin':'M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z',
  'mail':'M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2zM3 7l9 6l9 -6',
  'lock':'M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2zM11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0M8 11v-4a4 4 0 1 1 8 0v4',
  'eye':'M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6',
  'eye-off':'M10.585 10.587a2 2 0 0 0 2.829 2.828M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87M3 3l18 18',
  'search':'M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0M21 21l-6 -6',
  'filter':'M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z',
  'arrow-left':'M5 12l14 0M5 12l6 6M5 12l6 -6',
  'arrow-right':'M5 12l14 0M13 18l6 -6M13 6l6 6',
  'arrow-up':'M12 5l0 14M18 11l-6 -6M6 11l6 -6',
  'dots-vertical':'M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0',
  'trash':'M4 7l16 0M10 11l0 6M14 11l0 6M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3',
  'pencil':'M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4M13.5 6.5l4 4',
  'download':'M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2M7 11l5 5l5 -5M12 4l0 12',
  'upload':'M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2M7 9l5 -5l5 5M12 4l0 12',
  'camera':'M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2M9 13a3 3 0 1 0 6 0a3 3 0 0 0 -6 0',
  'photo':'M15 8h.01M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3zM3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3',
  'photo-plus':'M15 8h.01M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5M3 16l5 -5c.928 -.893 2.072 -.893 3 0l4 4M16 19h6M19 16v6',
  'microphone':'M9 2m0 3a3 3 0 0 1 3 -3a3 3 0 0 1 3 3v5a3 3 0 0 1 -3 3a3 3 0 0 1 -3 -3zM5 10a7 7 0 0 0 14 0M8 21l8 0M12 17l0 4',
  'microphone-off':'M3 3l18 18M9 5a3 3 0 0 1 6 0v5a3 3 0 0 1 -.13 .874M15 15a3 3 0 0 1 -3 -3M5 10a7 7 0 0 0 10.846 5.85M19 10a7 7 0 0 1 -.5 2.582M8 21l8 0M12 17l0 4',
  'robot':'M6 4m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2zM12 2v2M9 12v.01M15 12v.01M9.5 16a3.5 3.5 0 0 0 5 0M3 12h1M20 12h1',
  'brand-whatsapp':'M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1',
  'phone':'M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2',
  'star':'M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z',
  'shield-check':'M11.46 20.846a12 12 0 0 1 -7.96 -14.846a12 12 0 0 0 8.5 -3a12 12 0 0 0 8.5 3a12 12 0 0 1 -.09 3.777M15 19l2 2l4 -4',
  'file-text':'M14 3v4a1 1 0 0 0 1 1h4M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2M9 9l1 0M9 13l6 0M9 17l6 0',
  'settings':'M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0',
  'logout':'M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2M9 12h12l-3 -3M18 15l3 -3',
  'sun':'M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0M3 12h1M12 3v1M20 12h1M12 20v1M5.6 5.6l.7 .7M18.4 5.6l-.7 .7M17.7 17.7l.7 .7M6.3 17.7l-.7 .7',
  'moon':'M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z',
  'bed':'M3 7v11m0 -4h18v4M3 12h18a2 2 0 0 1 2 2v0M7 10h.01M7 10a2 2 0 1 0 4 0a2 2 0 0 0 -4 0',
  'bath':'M4 12h16a1 1 0 0 1 1 1v3a4 4 0 0 1 -4 4h-10a4 4 0 0 1 -4 -4v-3a1 1 0 0 1 1 -1zM6 12v-7a2 2 0 0 1 2 -2h3v2.25M4 21l1 -1.5M20 21l-1 -1.5',
  'car':'M5 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2M7 17h8M5 11h13',
  'ruler':'M5 4h14a1 1 0 0 1 1 1v5a1 1 0 0 1 -1 1h-7a1 1 0 0 0 -1 1v7a1 1 0 0 1 -1 1h-5a1 1 0 0 1 -1 -1v-14a1 1 0 0 1 1 -1M4 8h4M4 12h3M4 16h4M8 4v3M12 4v4M16 4v3',
  'refresh':'M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4',
  'share':'M6 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0M18 6m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0M8.7 10.7l6.6 -3.4M8.7 13.3l6.6 3.4',
  'device-floppy':'M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0M14 4l0 4l-6 0l0 -4',
  'world':'M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0M3.6 9h16.8M3.6 15h16.8M11.5 3a17 17 0 0 0 0 18M12.5 3a17 17 0 0 1 0 18',
  'external-link':'M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6M11 13l9 -9M15 4h5v5',
  'link':'M9 15l6 -6M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463',
  'unlink':'M17 22v-2M9 15l6 -6M17 3v2M3 17h2M3 7h2M19 17h2M19 7h2M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463',
  'thumb-up':'M7 11v8a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-7a1 1 0 0 1 1 -1h3a4 4 0 0 0 4 -4v-1a2 2 0 0 1 4 0v5h3a2 2 0 0 1 2 2l-1 5a2 3 0 0 1 -2 2h-7a3 3 0 0 1 -3 -3',
  'thumb-down':'M7 13v-8a1 1 0 0 0 -1 -1h-2a1 1 0 0 0 -1 1v7a1 1 0 0 0 1 1h3a4 4 0 0 1 4 4v1a2 2 0 0 0 4 0v-5h3a2 2 0 0 0 2 -2l-1 -5a2 3 0 0 0 -2 -2h-7a3 3 0 0 0 -3 3',
  'bookmark':'M18 7v14l-6 -4l-6 4v-14a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4z',
  'list-check':'M3.5 5.5l1.5 1.5l2.5 -2.5M3.5 11.5l1.5 1.5l2.5 -2.5M3.5 17.5l1.5 1.5l2.5 -2.5M11 6l9 0M11 12l9 0M11 18l9 0',
  'progress':'M12 3a9 9 0 1 0 9 9M12 3v9l6.5 6.5M12 3a9 9 0 0 1 9 9h-9',
  'id':'M3 6a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2zM9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0M15 8l2 0M15 12l2 0M7 16l10 0',
  'note':'M13 20l7 -7M13 20v-6a1 1 0 0 1 1 -1h6v-7a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2z',
  'key':'M16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1 -4.069 0l-.301 -.301l-6.558 6.558a2 2 0 0 1 -1.239 .578l-.175 .008h-1.172a1 1 0 0 1 -.993 -.883l-.007 -.117v-1.172a2 2 0 0 1 .467 -1.284l.119 -.13l.414 -.414h2v-2h2v-2l2.144 -2.144l-.301 -.301a2.877 2.877 0 0 1 0 -4.069l2.643 -2.643a2.877 2.877 0 0 1 4.069 0zM15 9h.01',
  'pool':'M3 21c.5 -.5 1.5 -1 2.5 -1s2 1 3.5 1s2.5 -1 3.5 -1s2 1 3.5 1s2 -.5 2.5 -1M3 17c.5 -.5 1.5 -1 2.5 -1s2 1 3.5 1s2.5 -1 3.5 -1s2 1 3.5 1s2 -.5 2.5 -1M8 17v-12a2 2 0 1 1 4 0M16 17v-12a2 2 0 0 0 -4 0M8 9h4',
  'tree':'M12 13l-2 -2M12 12l2 -2M12 21v-13M9.824 16a3 3 0 0 1 -2.743 -3.69a3 3 0 0 1 .304 -4.833a3 3 0 0 1 4.615 -3.477a3 3 0 0 1 4.614 3.477a3 3 0 0 1 .305 4.833a3 3 0 0 1 -2.919 3.69h-4.176z',
  'tools-kitchen':'M4 3h16v3a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4v-3zM5 10v11M19 10v11M9 14h6M9 18h6',
  'sofa':'M4 9v-2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v2M2 12a2 2 0 0 1 4 0v3h12v-3a2 2 0 1 1 4 0v5a2 2 0 0 1 -2 2h-16a2 2 0 0 1 -2 -2zM6 19v2M18 19v2',
  'desk':'M4 5h16M4 5v14M20 5v14M4 12h16M8 12v3M16 12v3',
  'door':'M14 12v.01M3 21h18M6 21v-16a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v16',
  'stairs':'M4 20h4v-4h4v-4h4v-4h4M4 20v-4h4M8 16v-4h4M12 12v-4h4',
  'grill':'M12 3a6 6 0 0 1 6 6h-12a6 6 0 0 1 6 -6zM6 9a6 6 0 0 0 12 0M9 15l-1 6M15 15l1 6M10 18h4',
  'wifi':'M12 18l.01 0M9.172 15.172a4 4 0 0 1 5.656 0M6.343 12.343a8 8 0 0 1 11.314 0M3.515 9.515c4.686 -4.687 12.284 -4.687 17 0',
  'sun-electricity':'M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0M12 5v-2M17 7l1.5 -1.5M19 12h2M6 12h-2M5.5 5.5l1.5 1.5M13 21l3 -5h-4l3 -5',
  'building-store':'M3 21l18 0M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4M5 21v-10.15M19 21v-10.15M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4',
  'clipboard-check':'M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2zM9 14l2 2l4 -4',
  'coin':'M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1M12 6v2M12 16v2',
  'chart-bar':'M3 12m0 1a1 1 0 0 1 1 -1h3a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-3a1 1 0 0 1 -1 -1zM9 8m0 1a1 1 0 0 1 1 -1h3a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-3a1 1 0 0 1 -1 -1zM15 4m0 1a1 1 0 0 1 1 -1h3a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-3a1 1 0 0 1 -1 -1zM4 20l14 0',
  'eye-check':'M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0M11.905 17.885c-3.293 -.3 -6.208 -2.263 -8.905 -5.885c2.4 -4 5.4 -6 9 -6c3.598 0 6.596 1.997 8.995 5.99M15 19l2 2l4 -4',
  'checkbox':'M9 11l3 3l8 -8M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9',
  'square':'M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z',
  'point':'M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0',
  'hand-grab':'M8 11v-3.5a1.5 1.5 0 0 1 3 0v2.5M11 10.5v-2a1.5 1.5 0 1 1 3 0v3M14 11.5v-2a1.5 1.5 0 0 1 3 0v5.5a6 6 0 0 1 -6 6h-2c-2.114 0 -3.24 -1.083 -4.5 -3l-2 -3a1.5 1.5 0 0 1 2.5 -2M8 11v6',
  'info-circle':'M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0M12 9h.01M11 12h1v4h1',
  'flag':'M5 5a5 5 0 0 1 7 0a5 5 0 0 0 7 0v9a5 5 0 0 1 -7 0a5 5 0 0 0 -7 0v-9zM5 21v-7',
  'archive':'M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v1a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2zM5 9v9a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-9M10 13h4',
  'arrow-back-up':'M9 13l-4 -4l4 -4M5 9h11a4 4 0 0 1 0 8h-1',
  'ban':'M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0M5.7 5.7l12.6 12.6',
  'send':'M10 14l11 -11M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5',
  'certificate':'M12 9m-6 0a6 6 0 1 0 12 0a6 6 0 1 0 -12 0M12.002 19.664l-2.002 -2.664l-3 1l2.5 -5M12 19.664l2 -2.664l3 1l-2.5 -5',
  'crown':'M12 6l4 6l5 -4l-2 10h-14l-2 -10l5 4z',
};
function icon(name, size, stroke){
  const d = P[name] || P['circle'];
  const s = size || 20;
  return '<svg width="'+s+'" height="'+s+'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="'+(stroke||2)+'" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="'+d+'"/></svg>';
}
const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

/* Monotonic ids. A wall-clock timestamp collides when two records are created
   in the same millisecond, and a collision here would make Ellie's one-tap
   Undo delete somebody else's event too. */
let _uid = 5000;
const uid = () => ++_uid;

/* ============================================================================
   2. STATE
   ========================================================================== */
const AGENCIES = [
  {name:'Sky',     accent:'#0EA5E9', lite:'#38b6f2', dark:'#0c93d1'},
  {name:'Emerald', accent:'#10B981', lite:'#34cd9c', dark:'#0ea273'},
  {name:'Crimson', accent:'#E11D48', lite:'#ea4468', dark:'#c5183f'},
  {name:'Violet',  accent:'#7C3AED', lite:'#9260f1', dark:'#6b2fd1'},
];

const S = {
  // Whatever the pre-paint script settled on (light, unless remembered dark).
  theme: document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light',
  agency:0,
  side:'agent',          // 'agent' | 'client'
  screen:'splash',
  stack:[],              // navigation history
  tab:'home',
  params:{},
  sheet:null,            // {render, ...}
  dialog:null,
  drawer:false,
  snack:null,
  nextApptState:'event', // loading | clear | event
  chapter:0, step:0,
  swipeHintSeen:false,
};

/* ============================================================================
   3. DEMO DATA — South Africa, KZN South Coast. Agency name is a neutral placeholder — the app is white-labelled.
   ========================================================================== */
const ME = {first:'Andre', last:'Roets', name:'Andre Roets', email:'andre@demoagency.co.za',
            role:'Agent', agency:'Demo Agency', initials:'AR'};

const money = n => 'R ' + n.toLocaleString('en-ZA').replace(/,/g,' ');

let DB = {};
function seed(){
  DB = {
  properties:[
    {id:1, num:'12', street:'Beach Road', suburb:'Uvongo', city:'Margate', province:'KwaZulu-Natal',
     title:'Stunning 4 Bed House in Uvongo', price:2500000, status:'active', type:'House', listing:'For Sale',
     beds:4, baths:2, garages:2, floor:240, erf:800, photos:8, photosNeeded:12, g:0, mandate:'Sole', mine:true,
     listed:'3 Jul 2026', expires:'3 Oct 2026', loaded:'3d ago', modified:'1d ago', days:10, live:false,
     desc:'A rare double-storey home perched above the Uvongo lagoon, with uninterrupted sea views from every north-facing room. The open-plan living area flows onto a wrap-around deck with a built-in braai and a sparkling pool. Walking distance to the beach, the shops and the Uvongo Bird Park.',
     compliance:{authority:false, fica:false, photos:false, details:false},
     sellers:[{name:'Sarah Naidoo', fica:'approved'}, {name:'Piet Grobler', fica:'pending'}],
     portals:[{n:'Website',live:true,ref:'REF-1042'},{n:'Agency Premium',live:true,ref:'REF-P-1042'},
              {n:'Private Property',live:false,ref:null},{n:'Property24',live:false,ref:null}]},
    {id:2, num:'8', street:'Marine Drive', suburb:'Margate', city:'Margate', province:'KwaZulu-Natal',
     title:'Sunlit 3 Bed Apartment on Marine Drive', price:1850000, status:'active', type:'Apartment', listing:'For Sale',
     beds:3, baths:2, garages:1, floor:145, erf:0, photos:14, photosNeeded:12, g:1, mandate:'Open', mine:true,
     listed:'21 Jun 2026', expires:'21 Sep 2026', loaded:'22d ago', modified:'4d ago', days:22, live:true,
     desc:'North-facing three bedroom apartment a block from the Margate main beach, in a secure complex with a communal pool.',
     compliance:{authority:true, fica:true, photos:true, details:true},
     sellers:[{name:'Lisa van Wyk', fica:'approved'}],
     portals:[{n:'Website',live:true,ref:'REF-1019'},{n:'Agency Premium',live:true,ref:'REF-P-1019'},
              {n:'Private Property',live:true,ref:'PP-884213'},{n:'Property24',live:true,ref:'P24-123456'}]},
    {id:3, num:'21', street:'Ramsgate Heights', suburb:'Ramsgate', city:'Margate', province:'KwaZulu-Natal',
     title:'', price:1200000, status:'draft', type:'Townhouse', listing:'For Sale',
     beds:2, baths:1, garages:1, floor:98, erf:210, photos:0, photosNeeded:12, g:2, mandate:'Open', mine:true,
     listed:'—', expires:'—', loaded:'1d ago', modified:'1d ago', days:1, live:false,
     desc:'', compliance:{authority:false, fica:false, photos:false, details:false}, sellers:[], portals:[]},
    {id:4, num:'5', street:'Umtentweni Close', suburb:'Umtentweni', city:'Port Shepstone', province:'KwaZulu-Natal',
     title:'Grand 5 Bed Family Home with Sea Views', price:3950000, status:'active', type:'House', listing:'For Sale',
     beds:5, baths:3, garages:2, floor:380, erf:1200, photos:19, photosNeeded:12, g:3, mandate:'Exclusive', mine:false,
     listed:'14 May 2026', expires:'14 Nov 2026', loaded:'60d ago', modified:'9d ago', days:60, live:true,
     desc:'An exceptional family estate on a full acre, with a flatlet, a tennis court and a borehole.',
     compliance:{authority:true, fica:true, photos:true, details:true},
     sellers:[{name:'Themba Zulu', fica:'approved'}],
     portals:[{n:'Website',live:true,ref:'REF-0987'},{n:'Agency Premium',live:false,ref:null},
              {n:'Private Property',live:true,ref:'PP-871004'},{n:'Property24',live:true,ref:'P24-119870'}]},
    {id:5, num:'14', street:'Seaview Terrace', suburb:'Uvongo', city:'Margate', province:'KwaZulu-Natal',
     title:'Modern 3 Bed with Lagoon Frontage', price:2100000, status:'draft', type:'House', listing:'For Sale',
     beds:3, baths:2, garages:2, floor:190, erf:640, photos:3, photosNeeded:12, g:6, mandate:'Sole', mine:true,
     listed:'—', expires:'—', loaded:'5h ago', modified:'5h ago', days:0, live:false,
     desc:'', compliance:{authority:false, fica:true, photos:false, details:true}, sellers:[{name:'Sarah Naidoo', fica:'approved'}], portals:[]},
    {id:6, num:'3', street:'Ocean View', suburb:'Margate', city:'Margate', province:'KwaZulu-Natal',
     title:'2 Bed Rental Steps from the Beach', price:12500, status:'active', type:'Apartment', listing:'For Rental',
     beds:2, baths:1, garages:1, floor:82, erf:0, photos:9, photosNeeded:12, g:5, mandate:'Sole', mine:true,
     listed:'1 Jul 2026', expires:'1 Jan 2027', loaded:'12d ago', modified:'2d ago', days:12, live:true,
     desc:'Fully furnished two bed rental with a lock-up garage, available immediately.',
     compliance:{authority:true, fica:true, photos:true, details:true},
     sellers:[{name:'Lisa van Wyk', fica:'approved'}],
     portals:[{n:'Website',live:true,ref:'REF-1033'},{n:'Agency Premium',live:false,ref:null},
              {n:'Private Property',live:false,ref:null},{n:'Property24',live:true,ref:'P24-140221'}]},
  ],
  contacts:[
    {id:1, first:'John', last:'Meyer', phone:'082 555 0134', email:'john.meyer@gmail.com', idnum:'8203155012083',
     type:'Buyer', wa:7, waLast:'2 Jul 2026', notes:'Cash buyer, relocating from Gauteng. Wants sea views.',
     consent:{marketing:true, whatsapp:true, data:true}},
    {id:2, first:'Sarah', last:'Naidoo', phone:'083 555 0198', email:'s.naidoo@outlook.com', idnum:'7809220145087',
     type:'Seller', wa:3, waLast:'28 Jun 2026', notes:'Selling 12 Beach Road. Motivated — emigrating in October.',
     consent:{marketing:true, whatsapp:true, data:true}},
    {id:3, first:'Themba', last:'Zulu', phone:'071 555 0177', email:'themba.z@gmail.com', idnum:'9001015800081',
     type:'Tenant', wa:0, waLast:null, notes:'', consent:{marketing:false, whatsapp:false, data:true}},
    {id:4, first:'Lisa', last:'van Wyk', phone:'084 555 0120', email:'lisa.vw@icloud.com', idnum:'8506110099084',
     type:'Landlord', wa:12, waLast:'11 Jul 2026', notes:'Owns three rental units on Marine Drive.',
     consent:{marketing:true, whatsapp:true, data:true}},
    {id:5, first:'Piet', last:'Grobler', phone:'',            email:'piet.grobler@gmail.com', idnum:'',
     type:'Investor', wa:0, waLast:null, notes:'Buys to let. No phone on file yet.',
     consent:{marketing:false, whatsapp:false, data:false}},
  ],
  events:[
    {id:1, t:'09:30', end:'10:30', title:'Viewing — 12 Beach Road, Uvongo', loc:'12 Beach Road, Uvongo',
     colour:'#F97316', pillar:'property', cls:'Viewing', att:2, day:0,
     desc:'Second viewing with the Meyers. Bring the mandate and the FICA pack.'},
    {id:2, t:'11:00', end:'11:30', title:'Buyer call — John Meyer', loc:'Phone', colour:'#8B5CF6',
     pillar:'contact', cls:'Prospecting', att:1, day:0, desc:'Follow up on his offer intentions.'},
    {id:3, t:'14:30', end:'15:30', title:'Mandate signing — Sarah Naidoo', loc:'Margate office',
     colour:'#3B82F6', pillar:'deal', cls:'Deal', att:2, day:0, desc:'Sole mandate, 90 days.'},
    {id:4, t:'08:00', end:'09:00', title:'Team standup', loc:'Margate office', colour:'#8890A4',
     pillar:null, cls:'Manual', att:6, day:1, desc:''},
    {id:5, t:'11:00', end:'12:00', title:'Show day — 8 Marine Drive', loc:'8 Marine Drive, Margate',
     colour:'#F97316', pillar:'property', cls:'Viewing', att:0, day:2, desc:'Open show day, 11:00–14:00.'},
    {id:6, t:'10:00', end:'11:00', title:'Lease renewal — 3 Ocean View', loc:'Margate office',
     colour:'#3B82F6', pillar:'deal', cls:'Lease', att:2, day:5, desc:''},
    {id:7, t:'15:00', end:'16:00', title:'Compliance review', loc:'', colour:'#F59E0B',
     pillar:null, cls:'Compliance', att:1, day:9, desc:'Quarterly FICA audit.'},
  ],
  tasks:[
    {id:1, title:'Send mandate to Sarah Naidoo', col:'todo', prio:'High', type:'Deal Action',
     due:'Today', overdue:false, pillar:'deal', desc:'Sole mandate, 90 days, 6% commission.'},
    {id:2, title:'Upload FICA docs for Piet Grobler', col:'todo', prio:'Critical', type:'Compliance',
     due:'Yesterday', overdue:true, pillar:'contact', desc:''},
    {id:3, title:'Follow up — Portal Lead (P24)', col:'todo', prio:'Normal', type:'Follow Up',
     due:'Tomorrow', overdue:false, pillar:'contact', desc:'Enquiry on 8 Marine Drive.'},
    {id:4, title:'Book photographer — 14 Seaview Terrace', col:'progress', prio:'Normal', type:'Custom',
     due:'15 Jul', overdue:false, pillar:'property', desc:''},
    {id:5, title:'Review offer — 5 Umtentweni Close', col:'progress', prio:'High', type:'Review',
     due:'14 Jul', overdue:false, pillar:'deal', desc:'Offer at R3.75m, below asking.'},
    {id:6, title:'Call Lisa van Wyk re: renewal', col:'done', prio:'Normal', type:'Follow Up',
     due:'11 Jul', overdue:false, pillar:'contact', desc:''},
  ],
  archived:[
    {id:101, title:'Chase P24 listing approval', date:'Jan 5, 2026'},
    {id:102, title:'Send comparables to John Meyer', date:'Jan 5, 2026'},
    {id:103, title:'Update Uvongo window display', date:'Dec 18, 2025'},
  ],
  notifs:[
    {id:1, sev:'overdue', pillar:'contact', title:'FICA expired — Piet Grobler',
     body:'This contact cannot proceed to offer until FICA is re-verified.', time:'5m ago', unread:true},
    {id:2, sev:'warning', pillar:'property', title:'12 Beach Road is missing 4 photos',
     body:'Portals require 12 photos. You have 8. This is blocking go-live.', time:'1h ago', unread:true},
    {id:3, sev:'info', pillar:'property', title:'New portal lead on 8 Marine Drive',
     body:'Property24 enquiry from Nomsa Dlamini. Respond within the hour.', time:'3h ago', unread:true},
    {id:4, sev:'info', pillar:'deal', title:'Offer received — 5 Umtentweni Close',
     body:'R3 750 000 submitted, awaiting your review.', time:'6h ago', unread:false},
    {id:5, sev:'warning', pillar:'agent', title:'Your mandate on 12 Beach Road expires in 14 days',
     body:'Renew it with the seller before it lapses.', time:'2d ago', unread:false},
    {id:6, sev:'info', pillar:null, title:'CoreX OS v1.0.0 released',
     body:'Ellie voice commands and AI photo analysis are now live.', time:'3d ago', unread:false},
  ],
  leads:[
    {id:1, day:1, src:'P24', name:'Nomsa Dlamini', phone:'076 555 0144', wa:true, email:'nomsa.d@gmail.com',
     prop:'8 Marine Drive, Margate', msg:'Hi, is this apartment still available? I would like to view it this weekend if possible.', unread:true},
    {id:2, day:1, src:'PP', name:'Kobus Steyn', phone:'082 555 0166', wa:false, email:'kobus@steyn.co.za',
     prop:'5 Umtentweni Close', msg:'What are the rates and levies on this property?', unread:true},
    {id:3, day:3, src:'P24', name:'Aisha Patel', phone:'073 555 0122', wa:true, email:'aisha.patel@gmail.com',
     prop:'3 Ocean View, Margate', msg:'Is the rental pet friendly? I have one small dog.', unread:false},
    {id:4, day:4, src:'PP', name:'Grant Miller', phone:'079 555 0188', wa:true, email:'gmiller@webmail.co.za',
     prop:'8 Marine Drive, Margate', msg:'Please call me back regarding a cash offer.', unread:true},
  ],
  matches:[
    {id:1, contactId:1, contact:'John Meyer', name:'Sea view family home', listing:'For Sale', status:'active',
     suburbs:['Uvongo','Margate'], min:1200000, max:2500000, beds:3, baths:2, type:'House',
     features:['Sea view','Pool'], notes:'Cash buyer, can move fast.',
     results:[
       {pid:1, score:92, reaction:'interested'},
       {pid:5, score:84, reaction:null},
       {pid:2, score:71, reaction:'saved'},
       {pid:4, score:58, reaction:'no'},
     ]},
    {id:2, contactId:3, contact:'Themba Zulu', name:'Margate rental', listing:'For Rental', status:'paused',
     suburbs:['Margate'], min:8000, max:15000, beds:2, baths:1, type:'Apartment', features:[], notes:'',
     results:[{pid:6, score:88, reaction:null}]},
  ],
  documents:[
    {id:1, name:'Mandate_12BeachRd_signed.pdf', type:'Mandate', prop:'12 Beach Road, Uvongo', size:'1.2 MB', by:'Andre Roets', date:'2 Jul 2026'},
    {id:2, name:'ID_Copy_Sarah.jpg', type:'FICA', prop:null, size:'480 KB', by:'Andre Roets', date:'28 Jun 2026'},
  ],
  photos:[],       // uploaded during the wizard
  wizard:null,     // the in-flight property draft
};
}
seed();

/* Client-side demo identity — the client portal logs in as John Meyer. */
const CLIENT = {name:'John Meyer', first:'John', initials:'JM', email:'john.meyer@gmail.com',
                agent:{name:'Andre Roets', initials:'AR', phone:'082 555 0101', email:'andre@demoagency.co.za', agency:'Demo Agency'},
                seller:true, reviewed:false, rating:0,
                consent:{marketing:true, whatsapp:true, data:true}};

const QUOTES = [
  'The best time to follow up was yesterday. The second best time is right now.',
  'Your pipeline is only as warm as your last conversation.',
  'Listings come and go; relationships compound.',
  "You can't close what you don't follow up on.",
  'Done today is worth more than perfect next week.',
  "Every 'no' is one conversation closer to your next 'yes'.",
];

/* South African cascading location data (Province -> City -> Suburb) */
const SA = {
  'KwaZulu-Natal':{
    'Margate':['Uvongo','Ramsgate','Margate Central','Manaba Beach','Southbroom'],
    'Port Shepstone':['Umtentweni','Oslo Beach','Marburg','Shelly Beach'],
    'Durban':['Umhlanga','Ballito','Berea','Glenwood','Morningside'],
  },
  'Western Cape':{
    'Cape Town':['Sea Point','Camps Bay','Green Point','Observatory','Constantia'],
    'Stellenbosch':['Die Boord','Mostertsdrift','Paradyskloof'],
  },
  'Gauteng':{
    'Sandton':['Bryanston','Morningside','Rivonia','Hyde Park'],
    'Pretoria':['Waterkloof','Menlo Park','Brooklyn','Hatfield'],
  },
  'Eastern Cape':{
    'Gqeberha':['Summerstrand','Walmer','Humewood'],
    'East London':['Nahoon','Beacon Bay','Vincent'],
  },
};

/* Spaces and their features */
const SPACE_TYPES = [
  {k:'Bedroom', icon:'bed', step:1},
  {k:'Bathroom', icon:'bath', step:0.5},
  {k:'Kitchen', icon:'tools-kitchen', step:1},
  {k:'Garage', icon:'car', step:1},
  {k:'Parking', icon:'car', step:1},
  {k:'Pool', icon:'pool', step:1},
  {k:'Garden', icon:'tree', step:1},
  {k:'Lounge', icon:'sofa', step:1},
  {k:'Dining Room', icon:'tools-kitchen', step:1},
  {k:'Study', icon:'desk', step:1},
  {k:'Flatlet', icon:'door', step:1},
];
const SPACE_FEATURES = {
  Bedroom:{'Bedroom':['En-suite','Built-in cupboards','Balcony','Air conditioning','Ceiling fan']},
  Bathroom:{'Bathroom':['Bath','Shower','Double basin','Heated towel rail','Underfloor heating']},
  Kitchen:{'Kitchen':['Gas hob','Eye-level oven','Scullery','Pantry','Breakfast nook','Granite tops']},
  Garage:{'Garage':['Automated door','Direct access','Storage']},
  Parking:{'Parking':['Covered','Shade net','Visitor bay']},
  Pool:{'Pool':['Salt chlorinated','Solar heated','Pool net','Splash pool']},
  Garden:{'Garden':['Irrigation','Borehole fed','Established trees','Vegetable patch']},
  Lounge:{'Lounge':['Fireplace','Open plan','Air conditioning','Built-in speakers']},
  'Dining Room':{'Dining Room':['Open plan','Separate','Serving hatch']},
  Study:{'Study':['Built-in desk','Fibre point','Separate entrance']},
  Flatlet:{'Flatlet':['Separate entrance','Own kitchen','Own bathroom','Prepaid meter']},
};
const PROP_FEATURES = {
  Security:['Alarm system','Electric fence','Beam sensors','Armed response','Access controlled','CCTV'],
  Outdoor:['Braai area','Patio','Wrap-around deck','Boma','Jungle gym','Tennis court'],
  Interior:['Fibre ready','Air conditioning','Underfloor heating','Blinds','Wooden floors'],
  Views:['Sea view','Lagoon view','Mountain view','Garden view','North facing'],
  Sustainability:['Solar panels','Solar geyser','Borehole','Rainwater tanks','Backup inverter','Gas geyser'],
};
/* What the image AI "sees" in the uploaded photos (confidence 0-1) */
const AI_SUGGESTIONS = [
  {f:'Swimming pool',     c:0.97, on:true,  photos:[{g:0,label:'IMG_2841.jpg',c:0.97},{g:1,label:'IMG_2846.jpg',c:0.88}]},
  {f:'Sea view',          c:0.94, on:true,  photos:[{g:6,label:'IMG_2839.jpg',c:0.94},{g:3,label:'IMG_2850.jpg',c:0.79}]},
  {f:'Braai area',        c:0.71, on:true,  photos:[{g:2,label:'IMG_2844.jpg',c:0.71}]},
  {f:'Solar panels',      c:0.55, on:true,  photos:[{g:7,label:'IMG_2852.jpg',c:0.55}]},
  {f:'Air conditioning',  c:0.31, on:false, photos:[{g:4,label:'IMG_2847.jpg',c:0.31}]},
  {f:'Wooden floors',     c:0.68, on:true,  photos:[{g:5,label:'IMG_2848.jpg',c:0.68}]},
];
/* ============================================================================
   4. SHARED COMPONENT HELPERS (mirrors the Flutter widget library)
   ========================================================================== */
const PILLARS = {property:{c:'var(--p-property)',l:'Property'}, deal:{c:'var(--p-deal)',l:'Deal'},
                 contact:{c:'var(--p-contact)',l:'Contact'}, agent:{c:'var(--p-agent)',l:'Agent'}};

/* An item with no pillar renders NOTHING — it never fabricates a destination. */
function pillarChip(p){
  if(!p || !PILLARS[p]) return '';
  const x = PILLARS[p];
  return '<span class="pillar" style="background:color-mix(in srgb,'+x.c+' 15%,transparent);color:'+x.c+'">'+x.l+'</span>';
}
function aiBadge(){ return '<span class="ai">'+icon('robot',11)+'AI</span>'; }
function ibox(name, colour, size, isize){
  return '<span class="ibox s'+(size||40)+'" style="background:color-mix(in srgb,'+colour+' 14%,transparent);color:'+colour+'">'+icon(name,isize||20)+'</span>';
}
function statusChip(text, colour, dense){
  return '<span class="status'+(dense?' dense':'')+'" style="background:color-mix(in srgb,'+colour+' 14%,transparent);color:'+colour+'">'+esc(text)+'</span>';
}
const STATUS_COLOUR = {active:'var(--success)', draft:'var(--warning)', 'under offer':'var(--info)',
                       sold:'var(--neutral)', withdrawn:'var(--neutral)', paused:'var(--neutral)',
                       fulfilled:'var(--success)', expired:'var(--danger)'};
const statusColour = s => STATUS_COLOUR[String(s).toLowerCase()] || 'var(--neutral)';

function thumb(g, cls, iconSize){
  return '<div class="thumb g'+(g%8)+' '+(cls||'s76')+'">'+icon('building-skyscraper', iconSize||26)+'</div>';
}
/* Empty states are warm and directive — never blank, never apologetic. */
function empty(iconName, title, sub, ctaLabel, ctaAct){
  return '<div style="display:flex;flex-direction:column;align-items:center;text-align:center;padding:44px 24px;gap:6px">'
    + '<span class="ibox" style="width:72px;height:72px;border-radius:20px;background:var(--accent-soft);color:var(--accent);margin-bottom:10px">'+icon(iconName,32)+'</span>'
    + '<div class="h-card">'+esc(title)+'</div>'
    + (sub?'<div class="t-sub" style="max-width:250px">'+esc(sub)+'</div>':'')
    + (ctaLabel?'<button class="btn sm" data-act="'+ctaAct+'" style="width:auto;padding:0 22px;margin-top:14px">'+esc(ctaLabel)+'</button>':'')
    + '</div>';
}
function field(label, inner, opts){
  const o = opts||{};
  return '<div class="field">'
    + (label ? '<label>'+esc(label)+(o.req?'<span class="req">*</span>':'')+'</label>' : '')
    + inner
    + (o.err?'<div class="err-msg">'+esc(o.err)+'</div>':'')
    + (o.hint?'<div class="hint">'+esc(o.hint)+'</div>':'')
    + '</div>';
}
function textInput(id, ph, val, extra){
  return '<input class="inp" id="'+id+'" placeholder="'+esc(ph||'')+'" value="'+esc(val||'')+'" '+(extra||'')+'>';
}
function pickerBtn(act, val, ph, disabled){
  return '<button class="picker'+(val?'':' placeholder')+'" data-act="'+act+'"'+(disabled?' disabled':'')+'>'
    + '<span class="trunc">'+esc(val||ph)+'</span>'+icon('chevron-down',18)+'</button>';
}
function switchRow(label, sub, on, act){
  return '<button class="row" data-act="'+act+'" style="width:100%;padding:12px 0;text-align:left">'
    + '<span class="grow"><span class="t-body" style="display:block">'+esc(label)+'</span>'
    + (sub?'<span class="t-sub">'+esc(sub)+'</span>':'')+'</span>'
    + '<span class="sw'+(on?' on':'')+'"></span></button>';
}
function segmented(items, active, actPrefix){
  return '<div class="seg">'+items.map(i =>
    '<button class="'+(i.k===active?'on':'')+'" data-act="'+actPrefix+':'+i.k+'">'+esc(i.l)+'</button>').join('')+'</div>';
}

/* ============================================================================
   5. NAVIGATION + CHROME
   ========================================================================== */
function go(screen, params){
  if(S.screen) S.stack.push({screen:S.screen, params:S.params});
  S.screen = screen; S.params = params||{};
  S.sheet=null; S.dialog=null; S.drawer=false;
  render(true);
}
function back(){
  const prev = S.stack.pop();
  if(prev){ S.screen = prev.screen; S.params = prev.params; }
  S.sheet=null; S.dialog=null;
  render(true);
}
function tab(t){
  S.tab = t; S.stack = [];
  S.screen = ({home:'home', today:'today', calendar:'calendar', ellie:'ellie', me:'me',
               chome:'chome', cme:'cme'})[t];
  S.sheet=null; S.dialog=null; S.drawer=false;
  render(true);
}
function sheet(cfg){ S.sheet = cfg; render(); }
function closeSheet(){ S.sheet=null; render(); }
function dialog(cfg){ S.dialog = cfg; render(); }
function closeDialog(){ S.dialog=null; render(); }
let snackTimer=null;
function snack(msg, kind, iconName){
  S.snack = {msg, kind:kind||'', icon:iconName};
  render();
  clearTimeout(snackTimer);
  snackTimer = setTimeout(()=>{ S.snack=null; render(); }, 2800);
}

const AGENT_TABS = () => [
  {k:'home', l:'Home', i:'home-2'},
  {k:'today', l:'Today', i:'calendar-event'},
  {k:'calendar', l:'Calendar', i:'calendar'},
  {k:'ellie', l:'Ellie', i:'sparkles'},
  {k:'me', l:'Me', i:'user-circle'},
];
const CLIENT_TABS = () => [
  {k:'chome', l:'Home', i:'home-2'},
  {k:'cme', l:'Profile', i:'user-circle'},
];

function bottomNav(){
  const tabs = S.side==='agent' ? AGENT_TABS() : CLIENT_TABS();
  if(!tabs.some(t=>t.k===S.tab)) return '';
  return '<nav class="bnav" id="bnav">'+tabs.map(t =>
    '<button class="'+(t.k===S.tab?'on':'')+'" data-act="tab:'+t.k+'" data-nav="'+t.k+'">'
    + icon(t.i,22)+'<span>'+t.l+'</span></button>').join('')+'</nav>';
}

/* The main app bar (agent home): hamburger, wordmark, QR, bell, avatar. */
function mainAppBar(unread){
  return '<header class="appbar">'
    + '<button class="iconbtn" data-act="drawer" data-tour="menu">'+icon('menu-2',22)+'</button>'
    + '<span style="font-size:18px;font-weight:800;letter-spacing:-.3px">CoreX <span class="os" style="background:linear-gradient(135deg,var(--accent-lite),var(--accent-dark));-webkit-background-clip:text;background-clip:text;color:transparent">OS</span></span>'
    + '<span class="grow"></span>'
    + '<button class="iconbtn" data-act="go:qr" data-tour="qr">'+icon('qrcode',21)+'</button>'
    + '<button class="iconbtn" data-act="go:notifications" data-tour="bell">'+icon('bell',21)
      + (unread?'<span class="dot-badge"></span>':'')+'</button>'
    + '<button class="avatar press" data-act="go:me">'+ (S.side==='agent'?ME.initials:CLIENT.initials) +'</button>'
    + '</header>';
}
/* A titled sub-screen bar: back arrow + title + optional actions. */
function subAppBar(title, actions){
  return '<header class="appbar">'
    + '<button class="iconbtn" data-act="back">'+icon('arrow-left',21)+'</button>'
    + '<span class="title">'+esc(title)+'</span>'
    + (actions||'')+'</header>';
}

/* ---- The render loop -------------------------------------------------- */
const SCREENS = {};
let lastScreen = null;

function render(animate){
  const app = document.getElementById('app');
  const scr = SCREENS[S.screen];
  if(!scr){ app.innerHTML = '<div class="pad">Unknown screen: '+esc(S.screen)+'</div>'; return; }

  const chromeless = scr.chromeless;
  const body = scr.render();
  const anim = (animate && lastScreen !== S.screen) ? ' tabin' : '';
  lastScreen = S.screen;

  const fab = scr.fab ? scr.fab() : '';
  app.innerHTML =
      (chromeless ? '' : (scr.appbar ? scr.appbar() : ''))
    + '<div class="view'+(scr.nav===false?'':' has-nav')+(fab?' has-fab':'')+anim+'" id="view">'+body+'</div>'
    + fab
    + (scr.nav===false ? '' : bottomNav())
    + (S.drawer ? '<div class="scrim" data-act="closeDrawer"></div>'+drawerView() : '')
    + (S.sheet ? '<div class="scrim" data-act="closeSheet"></div>'+sheetView() : '')
    + (S.dialog ? '<div class="scrim" data-act="dialogScrim"></div>'+dialogView() : '')
    + (S.snack ? snackView() : '');

  if(scr.after) scr.after();
  updateStatusBar();
}
function sheetView(){
  const c = S.sheet;
  return '<div class="sheet" id="sheet" style="'+(c.height?'height:'+c.height+';':'')+'">'
    + '<div class="grab"></div>'
    + (c.title!==undefined ? '<div class="s-head">'
        + (c.lead||'')
        + '<span class="h-card">'+esc(c.title)+'</span>'
        + (c.headAction||'')
        + '<button class="iconbtn" data-act="closeSheet">'+icon('x',20)+'</button></div>' : '')
    + '<div class="s-body" id="sheetBody">'+c.body()+'</div>'
    + (c.foot ? '<div class="s-foot">'+c.foot()+'</div>' : '')
    + '</div>';
}
function dialogView(){
  const d = S.dialog;
  return '<div class="dialog">'
    + (d.icon ? '<div style="margin-bottom:12px">'+ibox(d.icon, d.iconColour||'var(--danger)',40,20)+'</div>' : '')
    + '<div class="h-card" style="margin-bottom:8px">'+esc(d.title)+'</div>'
    + '<div class="t-sub">'+d.body+'</div>'
    + '<div class="d-actions">'
      + '<button class="btn2 sm" data-act="'+(d.cancelAct||'closeDialog')+'">'+esc(d.cancel||'Close')+'</button>'
      + (d.confirm ? '<button class="btn sm'+(d.destructive?' danger':'')+'" data-act="'+d.confirmAct+'">'+esc(d.confirm)+'</button>' : '')
    + '</div></div>';
}
function snackView(){
  const s = S.snack;
  return '<div class="snack '+s.kind+'">'+(s.icon?icon(s.icon,17):'')+'<span>'+esc(s.msg)+'</span></div>';
}
function drawerView(){
  const isAgent = S.side==='agent';
  const u = isAgent ? ME : {name:CLIENT.name, email:CLIENT.email, initials:CLIENT.initials};
  const item = (i,l,act,colour) =>
    '<button class="row press" data-act="'+act+'" style="width:100%;padding:13px 12px;border-radius:12px;text-align:left;color:'+(colour||'var(--text-primary)')+'">'
    + icon(i,20)+'<span style="font-size:14px;font-weight:600">'+esc(l)+'</span></button>';
  return '<aside class="drawer">'
    + '<div class="row" style="padding:6px 10px 18px;gap:12px">'
      + '<span class="avatar circle s44">'+u.initials+'</span>'
      + '<span class="grow"><span class="h-row" style="display:block">'+esc(u.name)+'</span>'
      + '<span class="t-sub trunc" style="display:block">'+esc(u.email)+'</span></span></div>'
    + (isAgent
        ? item('user-circle','Profile','go:me') + item('bell','Notifications','go:notifications') + item('settings','Settings','go:settings')
          + '<div class="divider"></div>' + item('logout','Sign out','signout','var(--danger)')
        : item('user-circle','Profile','tab:cme')
          + (CLIENT.seller ? item('building-skyscraper','My Listings','go:cListings') : '')
          + item('settings','Settings','go:settings')
          + item('building-store','Switch agency','switchAgency')
          + '<div class="divider"></div>'
          + '<button class="row" data-act="theme" style="width:100%;padding:13px 12px;text-align:left">'
            + icon(S.theme==='dark'?'moon':'sun',20)
            + '<span class="grow" style="font-size:14px;font-weight:600">'+(S.theme==='dark'?'Dark':'Light')+' mode</span>'
            + '<span class="sw'+(S.theme==='dark'?' on':'')+'"></span></button>'
          + '<div class="divider"></div>' + item('logout','Sign out','signout','var(--danger)'))
    + '<span class="grow"></span>'
    + '<div class="t-sub" style="padding:0 12px;font-size:11px">v1.0.0</div>'
    + '</aside>';
}
function updateStatusBar(){
  document.getElementById('sbRight').innerHTML =
    '<span style="font-size:10px;letter-spacing:.5px">'+(S.side==='agent'?'AGENT':'CLIENT')+'</span>'
    + icon('wifi',13) + icon('progress',13);
}

/* ---- Event delegation: every interactive element uses data-act ---------- */
const ACTS = {};
document.getElementById('app').addEventListener('click', e => {
  const el = e.target.closest('[data-act]');
  if(!el) return;
  const raw = el.getAttribute('data-act');
  const [name, ...rest] = raw.split(':');
  const arg = rest.join(':');
  if(ACTS[name]) { e.stopPropagation(); ACTS[name](arg, el, e); }
});

/* Live-typing fields. These update state in place rather than re-rendering the
   whole phone, so the caret and focus survive. */
document.getElementById('app').addEventListener('input', e => {
  const id = e.target.id;
  if(id === 'pickerSearch'){
    picker.q = e.target.value;
    const l = document.getElementById('pickerList');
    if(l) l.innerHTML = pickerList();
  } else if(id === 'waMsg'){
    waMsg = e.target.value;
  } else if(id === 'w_excerpt' && W){
    W.excerpt = e.target.value;
    const c = document.getElementById('excerptCounter');
    if(c) c.textContent = W.excerpt.length + ' / 500';
  }
});
/* Date/time pickers must re-render — the conflict banner depends on them. */
document.getElementById('app').addEventListener('change', e => {
  if(['qaStart','qaEnd','qaDate','qaDue'].includes(e.target.id)){
    readQa();
    openQuickAdd();
  }
});

ACTS.back = back;
ACTS.tab = t => tab(t);
ACTS.go = s => go(s);
ACTS.drawer = () => { S.drawer=true; render(); };
ACTS.closeDrawer = () => { S.drawer=false; render(); };
ACTS.closeSheet = closeSheet;
ACTS.closeDialog = closeDialog;
ACTS.dialogScrim = () => { if(!S.dialog || S.dialog.dismissible!==false) closeDialog(); };
ACTS.noop = () => {};
ACTS.theme = () => setTheme(S.theme==='dark'?'light':'dark');
ACTS.signout = () => {
  S.drawer=false;
  dialog({title:'Sign out?', body:"You'll need to sign in again to reach your workspace.",
    icon:'logout', cancel:'Cancel', confirm:'Sign out', destructive:true, confirmAct:'doSignout'});
};
ACTS.doSignout = () => {
  S.dialog=null; S.stack=[]; S.screen='login'; S.tab='';
  loginState = freshLogin();          // always land back on a form that works
  render(true);
};
ACTS.switchAgency = () => { S.drawer=false; snack('You belong to one agency only.','', 'info-circle'); };
/* ============================================================================
   6. DATES — the demo clock is pinned to Monday 13 July 2026 (Africa/Johannesburg)
   ========================================================================== */
const TODAY = new Date(2026, 6, 13);
const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
const DAYS = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
const addDays = (d,n) => { const x = new Date(d); x.setDate(x.getDate()+n); return x; };
const sameDay = (a,b) => a.getFullYear()===b.getFullYear() && a.getMonth()===b.getMonth() && a.getDate()===b.getDate();
const dayOffset = d => Math.round((new Date(d.getFullYear(),d.getMonth(),d.getDate()) - TODAY)/86400000);
const fmtDate = d => d.getDate()+' '+MONTHS[d.getMonth()]+' '+d.getFullYear();
const fmtISO = d => d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-'+String(d.getDate()).padStart(2,'0');
const eventsOn = off => DB.events.filter(e => e.day===off).sort((a,b)=>a.t.localeCompare(b.t));

/* The demo clock is pinned to 08:45, so the phone tells one coherent story: the
   status bar, the greeting and the "in 45 min" chip on the 09:30 viewing all
   agree with each other. A live wall clock would make them contradict. */
const NOW_H = 8, NOW_M = 45;
const nowMins = () => NOW_H*60 + NOW_M;

/* Relative chip on the Next Appointment card: "Now" / "in 45 min" / "in 2h 15m" */
function relTime(hhmm){
  const [h,m] = hhmm.split(':').map(Number);
  let mins = (h*60+m) - nowMins();
  if(mins <= 0 && mins > -60) return 'Now';
  if(mins <= 0) mins += 1440;
  if(mins < 60) return 'in '+mins+' min';
  const hh = Math.floor(mins/60), mm = mins%60;
  return mm ? 'in '+hh+'h '+mm+'m' : 'in '+hh+'h';
}
function greeting(){
  return NOW_H < 12 ? 'Good morning' : NOW_H < 17 ? 'Good afternoon' : 'Good evening';
}
const unreadCount = () => DB.notifs.filter(n=>n.unread).length;
const quoteOfDay = () => QUOTES[TODAY.getDate() % QUOTES.length];

/* ============================================================================
   7. SCREEN — Splash
   ========================================================================== */
SCREENS.splash = {
  chromeless:true, nav:false,
  render(){
    const letters = 'CoreX'.split('').map((c,i) =>
      '<span style="display:inline-block;animation:rise .5s cubic-bezier(.22,1,.36,1) '+(i*0.09)+'s both">'+c+'</span>').join('');
    return '<div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px">'
      + '<style>@keyframes rise{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}'
      + '@keyframes sweep{from{width:0;opacity:0}to{width:120px;opacity:1}}'
      + '@keyframes fadein{from{opacity:0}to{opacity:1}}</style>'
      + '<div style="font-size:40px;font-weight:800;letter-spacing:-1px">'+letters+'</div>'
      + '<div style="height:3px;border-radius:99px;background:linear-gradient(90deg,var(--accent-lite),var(--accent));animation:sweep .6s cubic-bezier(.22,1,.36,1) .55s both;box-shadow:0 0 14px var(--accent-glow)"></div>'
      + '<div style="margin-top:18px;font-size:12px;letter-spacing:2.4px;color:var(--text-tertiary);font-weight:600;animation:fadein .5s ease 1.15s both">Powering your property universe</div>'
      + '</div>';
  },
  after(){ setTimeout(()=>{ if(S.screen==='splash'){ S.screen='login'; render(true); } }, 1700); }
};

/* ============================================================================
   8. SCREEN — Login, and the login cascade
   ========================================================================== */
/* The form arrives pre-filled with working agent credentials, so "Continue to
   your workspace" just works. Nobody should have to guess a password to start a
   tour. Clear the fields (or type something else) to exercise the cascade. */
const DEMO_PW = 'corex1234';
const freshLogin = () => ({
  email: ME.email, pw: DEMO_PW,
  show:false, err:'', loading:false, activate:false, otp:false
});
let loginState = freshLogin();

SCREENS.login = {
  chromeless:true, nav:false,
  render(){
    const L = loginState;
    if(L.otp){
      return '<div class="pad" style="flex:1;display:flex;flex-direction:column;justify-content:center;max-width:380px;margin:0 auto;width:100%">'
        + '<button class="iconbtn" data-act="otpBack" style="margin-bottom:16px">'+icon('arrow-left',21)+'</button>'
        + '<div style="font-size:30px;font-weight:800;letter-spacing:-.6px;margin-bottom:8px">Check your email</div>'
        + '<div class="t-sub" style="margin-bottom:26px">We sent a 6-digit code to '+esc(L.email)+'.</div>'
        + '<div class="row" style="gap:8px;margin-bottom:24px">'
          + [0,1,2,3,4,5].map(i=>'<input class="inp" maxlength="1" style="text-align:center;padding:15px 0;font-size:20px" '+(i===0?'autofocus':'')+'>').join('')
        + '</div>'
        + '<button class="btn" data-act="otpVerify">Verify &amp; set password</button>'
        + '<div class="t-sub" style="text-align:center;margin-top:16px;font-size:12px">Didn\'t get it? <span style="color:var(--accent);font-weight:700">Resend code</span></div>'
        + '</div>';
    }
    return '<div class="pad" style="flex:1;display:flex;flex-direction:column;justify-content:center;max-width:380px;margin:0 auto;width:100%">'
      + '<div style="display:flex;flex-direction:column;align-items:center;margin-bottom:30px">'
        + '<div style="width:64px;height:64px;border-radius:20px;background:#fff;display:grid;place-items:center;padding:9px;box-shadow:0 16px 40px -12px var(--accent-glow),0 0 0 1px var(--border);margin-bottom:18px">'
          + '<img src="/images/corex-mark.png" alt="CoreX OS" style="width:100%;height:100%;object-fit:contain;display:block"></div>'
        + '<div style="font-size:32px;font-weight:800;letter-spacing:-.8px">CoreX<span style="background:linear-gradient(135deg,var(--accent-lite),var(--accent-dark));-webkit-background-clip:text;background-clip:text;color:transparent">OS</span></div>'
        + '<div style="font-size:11px;font-weight:700;letter-spacing:2.4px;color:var(--text-tertiary);margin-top:6px">YOUR REAL ESTATE OS</div>'
      + '</div>'
      + field('Email',
          '<span class="inp-wrap"><span class="pre">'+icon('mail',19)+'</span>'
          + '<input class="inp'+(L.err?' err':'')+'" id="loginEmail" type="email" placeholder="you@agency.co.za" value="'+esc(L.email)+'"></span>')
      + field('Password',
          '<span class="inp-wrap"><span class="pre">'+icon('lock',19)+'</span>'
          + '<input class="inp'+(L.err?' err':'')+'" id="loginPw" type="'+(L.show?'text':'password')+'" placeholder="••••••••" value="'+esc(L.pw)+'">'
          + '<button class="suf" data-act="pwToggle">'+icon(L.show?'eye-off':'eye',19)+'</button></span>',
          {err:L.err})
      + (L.activate
          ? '<button class="btn2" data-act="activate" style="margin-bottom:12px">'+icon('mail',18)+'Activate account</button>' : '')
      + '<button class="btn" data-act="login"'+(L.loading?' disabled':'')+' style="margin-top:4px" data-tour="loginBtn">'
        + (L.loading
            ? '<span style="width:22px;height:22px;border:2.5px solid rgba(255,255,255,.35);border-top-color:#fff;border-radius:99px;display:block;animation:spin .8s linear infinite"></span><style>@keyframes spin{to{transform:rotate(360deg)}}</style>'
            : 'Continue to your workspace')
      + '</button>'
      + '<div style="height:12px"></div>'
      + '<button class="btn2" data-act="scanQr">'+icon('qrcode',18)+'Scan agent QR</button>'
      + '<div class="divider" style="margin:22px 0 14px"></div>'
      + '<div class="eyebrow mute" style="text-align:center;margin-bottom:10px">TOUR SHORTCUTS — ONE TAP</div>'
      + '<div class="row" style="gap:10px">'
        + '<button class="btn2 sm" data-act="demoAgent">'+icon('user-circle',17)+'Sign in as Agent</button>'
        + '<button class="btn2 sm" data-act="demoClient">'+icon('users',17)+'Sign in as Client</button>'
      + '</div>'
      + '<div class="t-sub" style="text-align:center;margin-top:22px;font-size:11px;color:var(--text-muted)">v1.0.0</div>'
      + '</div>';
  }
};
function readLogin(){
  const e = document.getElementById('loginEmail'), p = document.getElementById('loginPw');
  if(e) loginState.email = e.value; if(p) loginState.pw = p.value;
}
ACTS.pwToggle = () => { readLogin(); loginState.show = !loginState.show; render(); };
ACTS.scanQr = () => snack('Camera opens here — scan your agent\'s QR to sign up as their client.','','qrcode');
ACTS.demoAgent = () => { S.side='agent'; syncSide(); enterApp('agent'); };
ACTS.demoClient = () => { S.side='client'; syncSide(); enterApp('client'); };
ACTS.otpBack = () => { loginState.otp=false; render(); };
ACTS.otpVerify = () => { loginState.otp=false; S.side='client'; syncSide(); enterApp('client'); };
ACTS.activate = () => { loginState.otp = true; loginState.activate=false; render(true); };

function enterApp(side){
  loginState = freshLogin();          // so signing out lands on a form that works
  S.side = side;
  S.stack = [];
  tab(side==='agent' ? 'home' : 'chome');
}

/* THE LOGIN CASCADE — agent first, then client. This is what the tour demystifies. */
ACTS.login = () => {
  readLogin();
  const L = loginState;
  const email = L.email.trim().toLowerCase();
  L.err=''; L.activate=false;
  if(!email){ L.err='Email or password is incorrect.'; render(); return; }

  L.loading = true; render();
  setTimeout(() => {
    L.loading = false;
    // 1. Try agent.
    if(email.includes('andre') || email.includes('agent')){
      if(L.pw && L.pw.length < 4){ L.err='Incorrect password.'; render(); return; }   // 401 → stop here
      S.side='agent'; syncSide(); enterApp('agent'); return;
    }
    // 2. Not an agent → look the email up as a client.
    if(email.includes('john') || email.includes('client')){ S.side='client'; syncSide(); enterApp('client'); return; }
    if(email.includes('new') || email.includes('activate')){
      L.err = "This account hasn't been activated yet. Send a code to your email to set a password.";
      L.activate = true; render(); return;
    }
    // 3. Not found anywhere.
    L.err = "We couldn't find that email. Ask your agency to add you, or scan your agent's QR code to get started.";
    render();
  }, 900);
};

/* ============================================================================
   9. SCREEN — Agent Home (the launcher / cockpit)
   ========================================================================== */
function meetEllieCard(){
  return '<button class="card accent press" data-act="tab:ellie" data-tour="ellieCard" style="width:100%;text-align:left;display:block">'
    + '<div class="row" style="margin-bottom:12px">'
      + ibox('sparkles','var(--accent)',40,21)
      + '<span class="grow"><span class="h-row" style="display:block">Meet Ellie</span>'
      + '<span class="t-sub">Your AI assistant for CoreX</span></span>'
      + icon('chevron-right',18)
    + '</div>'
    + '<div class="divider accent" style="margin:0 0 12px"></div>'
    + '<div style="font-size:14px;font-weight:600;font-style:italic;line-height:1.5;margin-bottom:10px">'+esc(quoteOfDay())+'</div>'
    + '<div class="eyebrow">ELLIE · DAILY</div>'
    + '</button>';
}
function nextApptCard(){
  const st = S.nextApptState;
  if(st==='loading'){
    return '<div class="card accent" data-tour="nextAppt"><div class="row">'
      + ibox('calendar-time','var(--accent)',40,21)
      + '<span class="grow"><span class="eyebrow" style="display:block;margin-bottom:3px">NEXT APPOINTMENT</span>'
      + '<span class="t-body">Checking your schedule…</span></span></div></div>';
  }
  if(st==='clear'){
    return '<div class="card accent" data-tour="nextAppt"><div class="row">'
      + ibox('circle-check','var(--success)',40,21)
      + '<span class="grow"><span class="eyebrow" style="display:block;margin-bottom:3px">NEXT APPOINTMENT</span>'
      + '<span class="t-body">Your schedule is clear for the rest of the day</span></span></div></div>';
  }
  const e = eventsOn(0)[0];
  return '<button class="card accent press" data-act="openEvent:'+e.id+'" data-tour="nextAppt" style="width:100%;text-align:left">'
    + '<div class="row">'
      + ibox('calendar-event', e.colour, 64, 26)
      + '<span class="grow">'
        + '<span class="eyebrow" style="display:block;margin-bottom:4px">NEXT APPOINTMENT</span>'
        + '<span class="h-row clamp2" style="display:block;margin-bottom:3px">'+esc(e.title)+'</span>'
        + '<span class="t-sub trunc" style="display:block;margin-bottom:7px">'+esc(e.loc)+'</span>'
        + '<span class="row" style="gap:6px"><span class="chip accent">'+esc(e.t)+'</span>'
        + '<span class="chip">'+esc(relTime(e.t))+'</span></span>'
      + '</span>'
      + icon('chevron-right',18)
    + '</div></button>';
}
const MODULES = [
  {i:'building-skyscraper', l:'Properties', act:'go:properties'},
  {i:'users', l:'Contacts', act:'go:contacts'},
  {i:'heart-handshake', l:'Core Matches', act:'go:matches'},
  {i:'target-arrow', l:'Portal Leads', act:'go:leads', dot:true},
];
SCREENS.home = {
  appbar: () => mainAppBar(unreadCount()>0),
  render(){
    return '<div class="pad">'
      + '<div class="t-sub" style="font-size:12px;font-weight:600;color:var(--text-tertiary)">'+esc(ME.agency)+'</div>'
      + '<div class="h-greet" style="margin:2px 0 18px">'+greeting()+', '+ME.first+'.</div>'
      + meetEllieCard()
      + '<div style="height:12px"></div>'
      + nextApptCard()
      + '<div class="sec-head"><span class="h-sec">Workspace</span>'
        + '<button class="linkbtn" data-act="go:properties">All '+icon('arrow-right',15)+'</button></div>'
      + '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px" data-tour="workspace">'
        + MODULES.map(m =>
          '<button class="card press" data-act="'+m.act+'" data-tour="mod-'+m.l.replace(/\s/g,'')+'" style="position:relative;padding:14px 10px;display:flex;flex-direction:column;align-items:flex-start;gap:10px;aspect-ratio:1/1;justify-content:center">'
          + (m.dot ? '<span style="position:absolute;top:9px;right:9px;width:10px;height:10px;border-radius:99px;background:var(--money);box-shadow:0 0 0 2px var(--surface-top),0 0 8px var(--money-glow)"></span>' : '')
          + '<span class="ibox s40" style="background:var(--accent-soft);color:var(--accent)">'+icon(m.i,22)+'</span>'
          + '<span style="font-size:12px;font-weight:600;text-align:left;line-height:1.3">'+esc(m.l)+'</span>'
          + '</button>').join('')
      + '</div>'
      + '<div style="height:20px"></div>'
      + '</div>';
    /* Note: there is deliberately NO FAB on Home. Home orients you; it doesn't create. */
  }
};
ACTS.nextState = st => { S.nextApptState = st; render(); };

/* ============================================================================
   10. SCREEN — Today
   ========================================================================== */
function eventSheet(e){
  const row = (i,l) => '<div class="row" style="padding:9px 0;gap:11px">'+icon(i,18)
    + '<span class="t-sub" style="color:var(--text-primary);font-weight:600">'+esc(l)+'</span></div>';
  sheet({
    title:'', height:'auto',
    lead:'<span style="width:10px;height:10px;border-radius:99px;background:'+e.colour+';flex:none;box-shadow:0 0 10px '+e.colour+'"></span>',
    body: () =>
        '<div class="h-card" style="margin:-38px 0 4px 22px">'+esc(e.title)+'</div>'
      + '<div style="margin-left:0;margin-top:12px">'
      + row('clock', e.t==='All day' ? 'All day' : e.t+' – '+e.end)
      + (e.loc ? row('map-pin', e.loc) : '')
      + row('flag', e.cls)
      + (e.att ? row('users', e.att+' attendees') : '')
      + (e.desc ? '<div class="divider"></div><div class="t-sub">'+esc(e.desc)+'</div>' : '')
      + (e.pillar ? '<div style="margin-top:12px">'+pillarChip(e.pillar)+'</div>' : '')
      + '</div>',
    foot: () => '<div class="row" style="gap:10px">'
      + '<button class="btn2 sm" data-act="dismissEvent:'+e.id+'">'+icon('x',17)+'Dismiss</button>'
      + '<button class="btn sm" data-act="completeEvent:'+e.id+'">'+icon('check',17)+'Complete</button></div>'
  });
}
ACTS.openEvent = id => eventSheet(DB.events.find(e=>e.id==id));
ACTS.dismissEvent = id => { DB.events = DB.events.filter(e=>e.id!=id); closeSheet(); snack('Event dismissed.'); };
ACTS.completeEvent = id => { DB.events = DB.events.filter(e=>e.id!=id); closeSheet(); snack('Event completed.','green','check'); };
ACTS.markAllRead = () => { DB.notifs.forEach(n=>n.unread=false); render(); snack('All notifications marked read.'); };

const SEV = {overdue:'var(--danger)', warning:'var(--warning)', info:'var(--info)'};
const SEV_ICON = {overdue:'alert-circle', warning:'alert-triangle', info:'info-circle'};

SCREENS.today = {
  appbar: () => '<header class="appbar" style="height:56px">'
    + '<button class="iconbtn" data-act="tab:home">'+icon('arrow-left',21)+'</button>'
    + '<span class="title" style="font-size:18px">Today</span>'
    + '<button class="iconbtn" data-act="refreshToday">'+icon('refresh',20)+'</button></header>',
  render(){
    const evs = eventsOn(0);
    const unread = DB.notifs.filter(n=>n.unread);
    return '<div class="pad">'
      + '<div class="sec-head" style="margin-top:4px"><span class="h-sec">Today\'s Schedule</span></div>'
      + (evs.length ? '<div class="stack">' + evs.map(e =>
          '<button class="rowbtn" data-act="openEvent:'+e.id+'">'
          + '<span style="width:4px;height:44px;border-radius:99px;background:'+e.colour+';flex:none;box-shadow:0 0 10px '+e.colour+'"></span>'
          + '<span style="width:58px;flex:none;font-size:12.5px;font-weight:700;color:var(--accent)">'+esc(e.t)+'</span>'
          + '<span class="grow">'
            + '<span class="t-body clamp2" style="display:block;margin-bottom:3px">'+esc(e.title)+'</span>'
            + (e.loc ? '<span class="row" style="gap:4px;color:var(--text-tertiary)">'+icon('map-pin',13)
                + '<span style="font-size:11.5px" class="trunc">'+esc(e.loc)+'</span></span>' : '')
            + (e.att ? '<span class="row" style="gap:4px;color:var(--text-tertiary);margin-top:2px">'+icon('users',13)
                + '<span style="font-size:11.5px">'+e.att+'</span></span>' : '')
          + '</span></button>').join('') + '</div>'
        : '<div class="card"><div class="t-sub" style="text-align:center;padding:10px 0">No events today.</div></div>')
      + '<div class="sec-head"><span class="h-sec">Unread Notifications</span>'
        + (unread.length ? '<button class="linkbtn" data-act="markAllRead">Mark all read</button>' : '') + '</div>'
      + (unread.length
        ? '<div class="stack">' + unread.map(n =>
            '<button class="rowbtn" data-act="go:notifications" style="align-items:flex-start">'
            + ibox(SEV_ICON[n.sev], SEV[n.sev], 32, 17)
            + '<span class="grow">'
              + '<span class="row" style="gap:6px;margin-bottom:3px"><span style="font-size:13px;font-weight:600;line-height:1.35" class="clamp2">'+esc(n.title)+'</span></span>'
              + '<span class="t-sub clamp2" style="font-size:12px">'+esc(n.body)+'</span>'
              + '<span class="row" style="gap:6px;margin-top:6px">'+pillarChip(n.pillar)
                + '<span style="font-size:11px;color:var(--text-tertiary)">'+esc(n.time)+'</span></span>'
            + '</span></button>').join('') + '</div>'
        : empty('circle-check', "You're all caught up.", 'Nothing new to look at right now.'))
      + '<div style="height:16px"></div></div>';
  }
};
ACTS.refreshToday = () => snack('Refreshed. (The real app also polls every 60s.)','','refresh');

/* ============================================================================
   11. SCREEN — Calendar (Month / Week / Day / Agenda)
   ========================================================================== */
let cal = {view:'D', focus:new Date(TODAY), selected:null};

function eventCard(e){
  return '<button class="rowbtn" data-act="openEvent:'+e.id+'" style="gap:10px">'
    + '<span style="width:3px;height:44px;border-radius:99px;background:'+e.colour+';flex:none;box-shadow:0 0 8px '+e.colour+'"></span>'
    + '<span class="grow">'
      + '<span class="row" style="gap:7px;margin-bottom:4px">'
        + '<span style="font-size:12px;font-weight:700;color:var(--accent)">'+esc(e.t)+'</span>'
        + '<span class="chip">'+esc(e.cls)+'</span></span>'
      + '<span class="t-body clamp2" style="display:block">'+esc(e.title)+'</span>'
    + '</span></button>';
}
function dayList(off){
  const evs = eventsOn(off);
  if(!evs.length) return '<div class="card"><div class="t-sub" style="text-align:center;padding:8px 0">No events</div></div>';
  return '<div class="stack">'+evs.map(eventCard).join('')+'</div>';
}
SCREENS.calendar = {
  appbar: () => {
    const f = cal.focus;
    const pendingRsvp = true;
    return '<div style="flex:none;padding:6px 14px 10px;z-index:20">'
      + '<div class="row" style="height:44px">'
        + '<span class="h-bar grow">'+MONTHS[f.getMonth()]+' '+f.getFullYear()+'</span>'
        + '<button class="iconbtn" data-act="invitations" title="Invitations">'+icon('mail',21)
          + (pendingRsvp?'<span class="dot-badge" style="background:var(--warning)"></span>':'')+'</button>'
      + '</div>'
      + '<div class="row" style="gap:8px;margin-top:4px">'
        + '<button class="iconbtn" data-act="calMove:-1" style="background:var(--fill-chip);border-radius:99px;width:34px;height:34px">'+icon('chevron-left',18)+'</button>'
        + '<button class="chip accent" data-act="calToday" style="padding:7px 14px">Today</button>'
        + '<button class="iconbtn" data-act="calMove:1" style="background:var(--fill-chip);border-radius:99px;width:34px;height:34px">'+icon('chevron-right',18)+'</button>'
        + '<span class="grow"></span>'
        + '<div class="seg" style="padding:3px" data-tour="calViews">'
          + ['M','W','D','A'].map(v=>'<button class="'+(cal.view===v?'on':'')+'" data-act="calView:'+v+'" style="padding:6px 10px;font-size:11.5px;font-weight:700">'+v+'</button>').join('')
        + '</div>'
      + '</div></div>';
  },
  render(){
    if(cal.view==='M') return monthView();
    if(cal.view==='W') return weekView();
    if(cal.view==='A') return agendaView();
    return dayView();
  },
  fab: () => '<button class="fab" data-act="quickAdd:Event" data-tour="calFab">'+icon('plus',24)+'</button>'
};
function monthView(){
  const f = cal.focus;
  const first = new Date(f.getFullYear(), f.getMonth(), 1);
  const startPad = (first.getDay()+6)%7;              // Monday-anchored
  const cells = [];
  for(let i=0;i<42;i++){
    const d = addDays(first, i-startPad);
    const off = dayOffset(d);
    const evs = eventsOn(off);
    const isToday = sameDay(d, TODAY);
    const isSel = cal.selected!=null && cal.selected===off;
    const dim = d.getMonth()!==f.getMonth();
    cells.push('<button data-act="calSelect:'+off+'" style="aspect-ratio:1/1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;border-radius:10px;'
      + (isSel?'background:var(--navy);':'')+'">'
      + '<span style="width:28px;height:28px;border-radius:99px;display:grid;place-items:center;font-size:12.5px;font-weight:'+(isToday?'700':'600')+';'
        + (isToday?'background:var(--accent);color:#fff;':(dim?'color:var(--text-muted);':(isSel?'color:#fff;':'')))+'">'+d.getDate()+'</span>'
      + '<span class="row" style="gap:2px;height:4px">'+evs.slice(0,3).map(e=>'<span style="width:4px;height:4px;border-radius:99px;background:'+e.colour+'"></span>').join('')+'</span>'
      + '</button>');
  }
  const sel = cal.selected;
  const selDate = sel!=null ? addDays(TODAY, sel) : null;
  return '<div class="pad">'
    + '<div style="display:grid;grid-template-columns:repeat(7,1fr);margin-bottom:4px">'
      + ['M','T','W','T','F','S','S'].map(d=>'<div style="text-align:center;font-size:10px;font-weight:700;letter-spacing:1.4px;color:var(--text-tertiary)">'+d+'</div>').join('')
    + '</div>'
    + '<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:2px">'+cells.join('')+'</div>'
    + '<div class="divider"></div>'
    + (selDate
        ? '<div class="h-row" style="margin-bottom:4px">'+DAYS[selDate.getDay()]+', '+selDate.getDate()+' '+MONTHS[selDate.getMonth()]+'</div>'
          + '<div class="t-sub" style="margin-bottom:12px">'+eventsOn(sel).length+' events</div>'
          + dayList(sel)
        : '<div class="t-sub" style="text-align:center;padding:16px 0">Tap a day to see events</div>')
    + '<div style="height:20px"></div></div>';
}
function weekView(){
  const f = cal.focus;
  const monday = addDays(f, -((f.getDay()+6)%7));
  const focusOff = dayOffset(f);
  return '<div class="pad">'
    + '<div class="row" style="gap:6px;margin-bottom:16px">'
      + [0,1,2,3,4,5,6].map(i=>{
          const d = addDays(monday,i); const off = dayOffset(d);
          const on = off===focusOff; const has = eventsOn(off).length;
          return '<button data-act="calFocus:'+off+'" class="press" style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;padding:10px 0;border-radius:12px;'
            + (on?'background:var(--accent);color:#fff;box-shadow:0 8px 18px -8px var(--accent-glow);':'background:var(--fill-subtle);')+'">'
            + '<span style="font-size:10px;font-weight:700;opacity:.75">'+DAYS[d.getDay()][0]+'</span>'
            + '<span style="font-size:14px;font-weight:700">'+d.getDate()+'</span>'
            + '<span style="width:4px;height:4px;border-radius:99px;background:'+(has?(on?'#fff':'var(--accent)'):'transparent')+'"></span>'
            + '</button>';
        }).join('')
    + '</div>'
    + '<div class="h-row" style="margin-bottom:10px">'+DAYS[f.getDay()]+', '+f.getDate()+' '+MONTHS[f.getMonth()]+'</div>'
    + dayList(focusOff) + '<div style="height:20px"></div></div>';
}
function dayView(){
  const f = cal.focus;
  return '<div class="pad">'
    + '<div class="row" style="justify-content:center;gap:14px;margin-bottom:16px">'
      + '<button class="iconbtn" data-act="calMove:-1">'+icon('chevron-left',18)+'</button>'
      + '<span class="h-row">'+f.getDate()+' '+MONTHS[f.getMonth()]+' '+f.getFullYear()+'</span>'
      + '<button class="iconbtn" data-act="calMove:1">'+icon('chevron-right',18)+'</button>'
    + '</div>'
    + dayList(dayOffset(f)) + '<div style="height:20px"></div></div>';
}
function agendaView(){
  const groups = {};
  DB.events.filter(e=>e.day>=0).sort((a,b)=>a.day-b.day || a.t.localeCompare(b.t)).forEach(e=>{
    (groups[e.day] = groups[e.day] || []).push(e);
  });
  const keys = Object.keys(groups);
  if(!keys.length) return '<div class="pad">'+empty('calendar','No events this month','Nothing scheduled in the next two months.')+'</div>';
  return '<div class="pad">' + keys.map(k=>{
    const off = +k, d = addDays(TODAY, off), isToday = off===0;
    return '<div style="margin-bottom:18px">'
      + '<div class="row" style="gap:7px;margin-bottom:9px">'
        + (isToday?'<span style="width:6px;height:6px;border-radius:99px;background:var(--accent);box-shadow:0 0 8px var(--accent)"></span>':'')
        + '<span class="eyebrow'+(isToday?'':' mute')+'">'+(isToday?'TODAY · ':'')+DAYS[d.getDay()]+' · '+d.getDate()+' '+MONTHS[d.getMonth()]+'</span></div>'
      + '<div class="stack">'+groups[k].map(eventCard).join('')+'</div></div>';
  }).join('') + '<div style="height:20px"></div></div>';
}
ACTS.calView = v => { cal.view=v; render(); };
ACTS.calMove = n => {
  const step = cal.view==='M' ? 'month' : cal.view==='W' ? 7 : 1;
  if(step==='month') cal.focus = new Date(cal.focus.getFullYear(), cal.focus.getMonth()+ +n, 1);
  else cal.focus = addDays(cal.focus, step * +n);
  render();
};
ACTS.calToday = () => { cal.focus = new Date(TODAY); cal.selected=null; render(); };
ACTS.calSelect = off => { cal.selected = +off; render(); };
ACTS.calFocus = off => { cal.focus = addDays(TODAY, +off); render(); };
ACTS.invitations = () => sheet({title:'Invitations', body:()=>
  '<div class="stack" style="padding-bottom:8px">'
  + '<div class="card"><div class="row" style="gap:10px">'+ibox('calendar-event','var(--warning)',40,20)
  + '<span class="grow"><span class="t-body" style="display:block">Show day — 8 Marine Drive</span>'
  + '<span class="t-sub">Wed 15 Jul · 11:00 · from Lindi Khumalo</span></span></div>'
  + '<div class="row" style="gap:8px;margin-top:12px">'
    + '<button class="btn2 sm" data-act="rsvp:no">Decline</button>'
    + '<button class="btn sm" data-act="rsvp:yes">Accept</button></div></div></div>'});
ACTS.rsvp = a => { closeSheet(); snack(a==='yes'?'Invitation accepted.':'Invitation declined.', a==='yes'?'green':''); };
/* ============================================================================
   12. SCREEN — Tasks (kanban, draggable + swipeable)
   ========================================================================== */
const COLS = [
  {k:'todo', l:'To Do', c:'#6B7280'},
  {k:'progress', l:'In Progress', c:'#0EA5E9'},
  {k:'done', l:'Done', c:'#22C55E'},
];
const PRIO = {Low:'#6B7280', Normal:'#0EA5E9', High:'#F59E0B', Critical:'#EF4444'};
let tasksTab = 'active';

SCREENS.tasks = {
  nav:false,
  appbar: () => {
    const open = DB.tasks.filter(t=>t.col!=='done').length;
    const over = DB.tasks.filter(t=>t.overdue && t.col!=='done').length;
    return '<header class="appbar" style="height:auto;padding:8px 14px 4px;align-items:flex-start">'
      + '<button class="iconbtn" data-act="back">'+icon('arrow-left',21)+'</button>'
      + '<span class="grow" style="padding-top:2px">'
        + '<span class="h-screen" style="display:block">Tasks</span>'
        + '<span style="font-size:12.5px;font-weight:600;color:'+(over?'var(--danger)':'var(--text-secondary)')+'">'
          + open+' open · '+over+' overdue</span></span>'
      + '<button class="iconbtn" data-act="taskMenu">'+icon('dots-vertical',20)+'</button></header>';
  },
  render(){
    return '<div class="pad" style="padding-top:12px">'
      + segmented([{k:'active',l:'Active'},{k:'archived',l:'Archived'}], tasksTab, 'tasksTab')
      + '<div style="height:16px"></div>'
      + (tasksTab==='active' ? kanban() : archivedList())
      + '<div style="height:24px"></div></div>';
  },
  fab: () => tasksTab==='active' ? '<button class="fab" data-act="quickAdd:Task" data-tour="taskFab">'+icon('plus',24)+'</button>' : '',
  after(){ wireDragAndSwipe(); }
};
function kanban(){
  return COLS.map(c => {
    const items = DB.tasks.filter(t=>t.col===c.k);
    return '<div class="dropcol" data-col="'+c.k+'" style="margin-bottom:18px;border-radius:var(--r-card);transition:background .18s ease">'
      + '<div class="row" style="gap:7px;margin-bottom:10px;padding:0 2px">'
        + '<span style="width:8px;height:8px;border-radius:99px;background:'+c.c+';box-shadow:0 0 8px '+c.c+'"></span>'
        + '<span class="h-row">'+c.l+'</span>'
        + '<span class="chip">'+items.length+'</span></div>'
      + '<div class="stack coldrop" style="min-height:56px">'
        + (items.length ? items.map(taskCard).join('')
            : '<div class="card" style="border:1px dashed var(--hairline);background:none;box-shadow:none">'
              + '<div class="t-sub emptycol" style="text-align:center;padding:6px 0">Nothing here.</div></div>')
      + '</div></div>';
  }).join('');
}
function taskCard(t){
  return '<div class="swipe-wrap" data-id="'+t.id+'" style="position:relative;border-radius:var(--r-card);overflow:hidden">'
    + '<div class="swipe-bg" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:space-between;padding:0 18px;border-radius:var(--r-card);opacity:0">'
      + '<span class="row" style="gap:7px;color:#fff;font-weight:700;font-size:13px">'+icon('check',18)+'Complete</span>'
      + '<span class="row" style="gap:7px;color:#fff;font-weight:700;font-size:13px">Didn\'t happen'+icon('ban',18)+'</span>'
    + '</div>'
    + '<div class="card task press" data-id="'+t.id+'" data-act="openTask:'+t.id+'"'
      + ' style="position:relative;cursor:grab;touch-action:pan-y;user-select:none;padding:13px 14px;'
      + (t.overdue && t.col!=='done' ? 'border:1px solid rgba(239,68,68,.45);' : '')+'">'
      + '<div class="row" style="gap:8px;margin-bottom:7px">'
        + '<span style="width:6px;height:6px;border-radius:99px;background:'+PRIO[t.prio]+';flex:none"></span>'
        + '<span class="t-body grow clamp2" style="'+(t.col==='done'?'opacity:.6;text-decoration:line-through':'')+'">'+esc(t.title)+'</span>'
      + '</div>'
      + '<div class="row" style="gap:6px;flex-wrap:wrap">'
        + pillarChip(t.pillar)
        + '<span class="chip" style="font-size:10px">'+esc(t.type)+'</span>'
        + '<span class="chip'+(t.overdue&&t.col!=='done'?'':'')+'" style="font-size:10px;'
          + (t.overdue&&t.col!=='done'?'background:rgba(239,68,68,.14);color:var(--danger)':'')+'">'
          + icon('clock',11)+esc(t.due)+'</span>'
      + '</div></div></div>';
}
function archivedList(){
  if(!DB.archived.length) return empty('archive','No archived tasks.','Completed work you archive lands here.');
  const groups = {};
  DB.archived.forEach(a => (groups[a.date] = groups[a.date]||[]).push(a));
  return Object.keys(groups).map(d =>
    '<div style="margin-bottom:16px"><div class="eyebrow mute" style="margin-bottom:8px">'+esc(d)+'</div>'
    + '<div class="stack">'+groups[d].map(a =>
        '<div class="card row" style="padding:13px 14px">'
        + '<span class="t-body grow" style="opacity:.7">'+esc(a.title)+'</span>'
        + '<button class="chip accent" data-act="restore:'+a.id+'">'+icon('arrow-back-up',13)+'Restore</button></div>').join('')
    + '</div></div>').join('');
}
ACTS.tasksTab = t => { tasksTab=t; render(); };
ACTS.restore = id => {
  const a = DB.archived.find(x=>x.id==id);
  DB.archived = DB.archived.filter(x=>x.id!=id);
  DB.tasks.push({id:1000+(+id), title:a.title, col:'todo', prio:'Normal', type:'Custom', due:'No date', overdue:false, pillar:null, desc:''});
  render(); snack('Task restored to To Do.','green','arrow-back-up');
};
ACTS.taskMenu = () => sheet({title:'Tasks', body:()=>
  '<button class="rowbtn" data-act="archiveDone" style="margin-bottom:10px">'+icon('archive',19)
  + '<span class="t-body">Archive all Done</span></button>'});
ACTS.archiveDone = () => {
  const done = DB.tasks.filter(t=>t.col==='done');
  DB.tasks = DB.tasks.filter(t=>t.col!=='done');
  done.forEach(t => DB.archived.unshift({id:900+t.id, title:t.title, date:'Jul 13, 2026'}));
  closeSheet(); snack(done.length+' task(s) archived','','archive');
};
ACTS.openTask = id => {
  const t = DB.tasks.find(x=>x.id==id);
  sheet({title:t.title, body:()=>
      '<div class="row" style="gap:6px;margin-bottom:14px;flex-wrap:wrap">'
      + pillarChip(t.pillar)
      + statusChip(t.prio+' priority', PRIO[t.prio], true)
      + '<span class="chip">'+esc(t.type)+'</span>'
      + '<span class="chip">'+icon('clock',11)+esc(t.due)+'</span></div>'
    + (t.desc ? '<div class="t-sub" style="margin-bottom:14px">'+esc(t.desc)+'</div>' : '')
    + '<div class="eyebrow mute" style="margin-bottom:8px">MOVE "'+esc(t.title.slice(0,22))+(t.title.length>22?'…':'')+'"</div>'
    + '<div class="stack">'+COLS.filter(c=>c.k!==t.col).map(c =>
        '<button class="rowbtn" data-act="moveTask:'+t.id+'|'+c.k+'">'
        + '<span style="width:8px;height:8px;border-radius:99px;background:'+c.c+'"></span>'
        + '<span class="t-body grow">'+c.l+'</span>'+icon('chevron-right',17)+'</button>').join('')+'</div>',
    foot:()=> '<button class="btn2 sm red" data-act="deleteTask:'+t.id+'">'+icon('trash',17)+'Delete task</button>'});
};
ACTS.moveTask = a => {
  const [id, col] = a.split('|');
  const t = DB.tasks.find(x=>x.id==id); t.col = col;
  closeSheet(); snack('Moved to '+COLS.find(c=>c.k===col).l+'.','green','check');
};
ACTS.deleteTask = id => { DB.tasks = DB.tasks.filter(t=>t.id!=id); closeSheet(); snack('Task deleted.','red','trash'); };

/* One pointer-based gesture engine for both interactions, so they can't fight
   each other (HTML5 drag-and-drop suppresses pointermove, which would kill the
   swipe). Intent is decided from the first ~10px of movement:
     horizontal  -> swipe   (right = Complete, left = "Didn't happen")
     held still  -> lift    (long-press to pick up, then drag between columns)
     vertical    -> scroll  (we get out of the way entirely) */
function colUnder(x, y){
  const el = document.elementFromPoint(x, y);
  return el ? el.closest('.dropcol') : null;
}
function paintCol(col){
  document.querySelectorAll('.dropcol').forEach(c => {
    const on = c === col;
    c.style.background = on ? 'var(--accent-soft)' : '';
    const em = c.querySelector('.emptycol');
    if(em) em.textContent = on ? 'Drop to move here' : 'Nothing here.';
  });
}
function wireDragAndSwipe(){
  const view = document.getElementById('view');
  if(!view) return;

  view.querySelectorAll('.swipe-wrap').forEach(wrap => {
    const card = wrap.querySelector('.task');
    const bg = wrap.querySelector('.swipe-bg');
    let x0=0, y0=0, dx=0, dy=0, mode=null, timer=null, moved=false, pid=null;

    const settle = () => {
      card.style.transition = 'transform .22s cubic-bezier(.22,1,.36,1)';
      card.style.transform = '';
      card.style.zIndex = ''; card.style.opacity = ''; card.style.pointerEvents = '';
      bg.style.opacity = 0;
      paintCol(null);
    };
    card.addEventListener('pointerdown', e => {
      if(e.pointerType === 'mouse' && e.button !== 0) return;
      x0 = e.clientX; y0 = e.clientY; dx = 0; dy = 0; mode = null; moved = false;
      pid = e.pointerId;
      card.setPointerCapture(pid);
      card.style.transition = 'none';
      timer = setTimeout(() => {                   // long-press → pick the card up
        if(mode !== null) return;
        mode = 'drag'; moved = true;
        card.style.zIndex = 50;
        card.style.opacity = '.92';
        card.style.pointerEvents = 'none';          // so elementFromPoint sees the column
        card.style.transform = 'scale(1.03)';
      }, 260);
    });
    card.addEventListener('pointermove', e => {
      if(pid === null) return;
      dx = e.clientX - x0; dy = e.clientY - y0;
      if(mode === null){
        if(Math.abs(dx) > 10 && Math.abs(dx) > Math.abs(dy)){ mode = 'swipe'; clearTimeout(timer); }
        else if(Math.abs(dy) > 10){                 // it's a scroll — let the list have it
          mode = 'scroll'; clearTimeout(timer);
          try{ card.releasePointerCapture(pid); }catch(_){}
          pid = null; card.style.transition = '';
          return;
        }
      }
      // dx/dy are screen pixels, but the card lives inside a scaled phone — divide
      // through so it tracks the cursor instead of lagging behind it.
      const ps = phoneScale();
      if(mode === 'swipe'){
        moved = true;
        card.style.transform = 'translateX('+(dx/ps)+'px)';
        bg.style.opacity = Math.min(Math.abs(dx)/90, 1);
        bg.style.background = dx > 0 ? 'var(--success)' : 'var(--neutral)';
      } else if(mode === 'drag'){
        card.style.transform = 'translate('+(dx/ps)+'px,'+(dy/ps)+'px) scale(1.03)';
        paintCol(colUnder(e.clientX, e.clientY));
      }
    });
    const end = e => {
      if(pid === null){ mode = null; return; }
      clearTimeout(timer);
      try{ card.releasePointerCapture(pid); }catch(_){}
      pid = null;
      const t = DB.tasks.find(x => x.id == wrap.dataset.id);

      if(mode === 'swipe' && t){
        if(dx > 80){
          card.style.transition = 'transform .2s ease-out';
          card.style.transform = 'translateX(120%)';
          setTimeout(() => { t.col = 'done'; t.overdue = false; render(); snack('Task completed.','green','check'); }, 170);
          mode = null; return;
        }
        if(dx < -80){
          card.style.transition = 'transform .2s ease-out';
          card.style.transform = 'translateX(-120%)';
          setTimeout(() => { DB.tasks = DB.tasks.filter(x => x.id != t.id); render(); snack("Marked as didn't happen."); }, 170);
          mode = null; return;
        }
      } else if(mode === 'drag' && t && e){
        const col = colUnder(e.clientX, e.clientY);
        if(col && col.dataset.col !== t.col){
          t.col = col.dataset.col;
          paintCol(null);
          render();
          snack('Moved to '+COLS.find(c => c.k === t.col).l+'.','green','check');
          mode = null; return;
        }
      }
      settle();
      mode = null;
    };
    card.addEventListener('pointerup', end);
    card.addEventListener('pointercancel', end);
    // A gesture must never also register as a tap.
    card.addEventListener('click', e => {
      if(moved){ e.stopPropagation(); e.preventDefault(); moved = false; }
    });
  });
}

/* ============================================================================
   13. Quick Add sheet — Tasks + Events in one
   ========================================================================== */
let qa = {mode:'Task', title:'', prio:'Normal', desc:'', remind:true,
          type:'Custom', due:'', date:'', start:'', end:'', allDay:false, err:''};
const TASK_TYPES = ['Custom','Follow Up','Document Upload','Compliance','Review','Deal Action'];
const EVENT_TYPES = ['Manual','Deal','Lease','Compliance','Prospecting'];

ACTS.quickAdd = mode => {
  qa = {mode:mode||'Task', title:'', prio:'Normal', desc:'', remind:true, type:mode==='Event'?'Manual':'Custom',
        due:'', date:fmtISO(TODAY), start:'', end:'', allDay:false, err:''};
  openQuickAdd();
};
function readQa(){
  const g = id => { const el = document.getElementById(id); return el ? el.value : ''; };
  if(document.getElementById('qaTitle')) qa.title = g('qaTitle');
  if(document.getElementById('qaDesc')) qa.desc = g('qaDesc');
  if(document.getElementById('qaStart')) qa.start = g('qaStart');
  if(document.getElementById('qaEnd')) qa.end = g('qaEnd');
  if(document.getElementById('qaDate')) qa.date = g('qaDate');
  if(document.getElementById('qaDue')) qa.due = g('qaDue');
}
/* Conflicts WARN but never BLOCK — the app trusts the agent's judgement. */
function conflicts(){
  if(qa.mode!=='Event' || !qa.date || !qa.start || qa.allDay) return [];
  const off = dayOffset(new Date(qa.date+'T00:00:00'));
  const s = qa.start, e = qa.end || qa.start;
  return eventsOn(off).filter(ev => ev.t < e && ev.end > s);
}
function openQuickAdd(){
  sheet({
    title:'Quick Add', height:'86%',
    body: () => {
      const isEvent = qa.mode==='Event';
      const cf = conflicts();
      return segmented([{k:'Task',l:'Task'},{k:'Event',l:'Event'}], qa.mode, 'qaMode')
        + '<div style="height:18px"></div>'
        + field(isEvent?'Event title':'Task title',
            '<input class="inp" id="qaTitle" placeholder="'+(isEvent?'e.g. Viewing at 12 Beach Road':'e.g. Send mandate to Sarah')+'" value="'+esc(qa.title)+'" autofocus>')
        + '<div class="field"><label>Priority</label><div class="row" style="gap:7px;flex-wrap:wrap">'
          + Object.keys(PRIO).map(p =>
            '<button class="tchip'+(qa.prio===p?' on':'')+'" data-act="qaPrio:'+p+'"'
            + (qa.prio===p?' style="background:color-mix(in srgb,'+PRIO[p]+' 16%,transparent);border-color:'+PRIO[p]+';color:'+PRIO[p]+'"':'')+'>'
            + '<span style="width:7px;height:7px;border-radius:99px;background:'+PRIO[p]+'"></span>'+p+'</button>').join('')
          + '</div></div>'
        + (isEvent
          ? field('Date', '<input class="inp" id="qaDate" type="date" value="'+esc(qa.date)+'">')
            + '<div class="row" style="gap:10px;align-items:flex-start">'
              + '<span class="grow">'+field('Start time','<input class="inp" id="qaStart" type="time" value="'+esc(qa.start)+'" data-act="noop">')+'</span>'
              + '<span class="grow">'+field('End time','<input class="inp" id="qaEnd" type="time" value="'+esc(qa.end)+'">',
                  {err: (qa.start && qa.end && qa.end <= qa.start) ? 'End time must be after start time' : ''})+'</span>'
            + '</div>'
            + field('Type', pickerBtn('qaType', qa.type, 'Select type'))
            + '<div class="card" style="padding:12px 14px;margin-bottom:14px">'+switchRow('All day', null, qa.allDay, 'qaAllDay')+'</div>'
            + (cf.length ? conflictBanner(cf) : '')
          : field('Type', pickerBtn('qaType', qa.type, 'Select type'))
            + field('Due Date', '<input class="inp" id="qaDue" type="date" min="'+fmtISO(TODAY)+'" value="'+esc(qa.due)+'">'))
        + field('Description (optional)', '<textarea class="inp" id="qaDesc" rows="3" placeholder="Add any detail…">'+esc(qa.desc)+'</textarea>')
        + '<div class="card" style="padding:12px 14px">'+switchRow('Remind me','Push notification before it\'s due', qa.remind, 'qaRemind')+'</div>'
        + '<div style="height:8px"></div>';
    },
    foot: () => '<button class="btn" data-act="qaSave">'+(qa.mode==='Event'?'Add Event':'Add Task')+'</button>'
  });
}
function conflictBanner(cf){
  return '<div style="border-radius:var(--r-card);padding:12px 14px;margin-bottom:14px;background:rgba(245,158,11,.12);border-left:3px solid var(--warning)" data-tour="conflict">'
    + '<div class="row" style="gap:7px;margin-bottom:8px;color:var(--warning)">'+icon('alert-triangle',16)
      + '<span style="font-size:13px;font-weight:700">Overlaps '+cf.length+' event'+(cf.length>1?'s':'')+'</span></div>'
    + cf.slice(0,3).map(e=>'<div style="font-size:12px;color:var(--text-secondary);margin-bottom:3px">'
        + esc(e.title)+' · '+e.t+'–'+e.end+'</div>').join('')
    + (cf.length>3 ? '<div style="font-size:12px;color:var(--text-tertiary)">+ '+(cf.length-3)+' more</div>' : '')
    + '</div>';
}
ACTS.qaMode = m => { readQa(); qa.mode = m; qa.type = m==='Event'?'Manual':'Custom'; openQuickAdd(); };
ACTS.qaPrio = p => { readQa(); qa.prio = p; openQuickAdd(); };
ACTS.qaAllDay = () => { readQa(); qa.allDay = !qa.allDay; openQuickAdd(); };
ACTS.qaRemind = () => { readQa(); qa.remind = !qa.remind; openQuickAdd(); };
ACTS.qaType = () => {
  readQa();
  const list = qa.mode==='Event' ? EVENT_TYPES : TASK_TYPES;
  sheet({title:'Select type', body:()=> '<div class="stack" style="padding-bottom:10px">'
    + list.map(t=>'<button class="rowbtn" data-act="qaSetType:'+t+'">'
        + '<span class="t-body grow">'+t+'</span>'
        + (qa.type===t?'<span style="color:var(--accent)">'+icon('check',18)+'</span>':'')+'</button>').join('')+'</div>'});
};
ACTS.qaSetType = t => { qa.type = t; openQuickAdd(); };
ACTS.qaSave = () => {
  readQa();
  if(qa.mode==='Event'){
    if(!qa.title || !qa.date || (!qa.start && !qa.allDay)){
      snack('Please add a task title, a date and a start time.','amber','alert-triangle'); return;
    }
    if(qa.start && qa.end && qa.end <= qa.start) return;
    const off = dayOffset(new Date(qa.date+'T00:00:00'));
    DB.events.push({id:uid(), t:qa.allDay?'All day':qa.start, end:qa.end||qa.start,
      title:qa.title, loc:'', colour:'#3B82F6', pillar:null, cls:qa.type, att:0, day:off, desc:qa.desc});
    closeSheet();
    cal.focus = addDays(TODAY, off);
    snack('Event added.','green','check');
  } else {
    if(!qa.title){ snack('Please add a task title, a date and a start time.','amber','alert-triangle'); return; }
    DB.tasks.unshift({id:uid(), title:qa.title, col:'todo', prio:qa.prio, type:qa.type,
      due:qa.due ? fmtDate(new Date(qa.due+'T00:00:00')).replace(' 2026','') : 'No date',
      overdue:false, pillar:null, desc:qa.desc});
    closeSheet();
    snack('Task added.','green','check');
  }
};
/* ============================================================================
   14. SCREEN — Properties list
   ========================================================================== */
let plist = {q:'', scope:'mine', filters:{suburbs:[], min:'', max:'', listing:[], status:[]}};
const addr = p => (p.num?p.num+' ':'') + p.street + ', ' + p.suburb;
const activeFilterCount = () => {
  const f = plist.filters;
  return f.suburbs.length + f.listing.length + f.status.length + (f.min?1:0) + (f.max?1:0);
};
function filteredProps(){
  const f = plist.filters, q = plist.q.trim().toLowerCase();
  return DB.properties.filter(p => {
    if(plist.scope==='mine' && !p.mine) return false;
    if(q && !(addr(p)+' '+p.title).toLowerCase().includes(q)) return false;
    if(f.suburbs.length && !f.suburbs.includes(p.suburb)) return false;
    if(f.listing.length && !f.listing.includes(p.listing)) return false;
    if(f.status.length && !f.status.includes(p.status)) return false;
    if(f.min && p.price < +f.min) return false;
    if(f.max && p.price > +f.max) return false;
    return true;
  });
}
SCREENS.properties = {
  nav:false,
  appbar: () => subAppBar('Properties',
    '<button class="iconbtn" data-act="propFilters" data-tour="filterBtn">'+icon('filter',20)
    + (activeFilterCount() ? '<span class="dot-badge" style="width:15px;height:15px;font-size:9px;display:grid;place-items:center;color:#fff;font-weight:700;top:3px;right:2px">'+activeFilterCount()+'</span>' : '')
    + '</button>'),
  render(){
    const list = filteredProps();
    const total = DB.properties.filter(p=>plist.scope==='mine'?p.mine:true).length;
    const filtered = activeFilterCount()>0 || plist.q;
    return '<div class="pad">'
      + '<span class="inp-wrap" style="margin-bottom:12px;display:flex">'
        + '<span class="pre">'+icon('search',18)+'</span>'
        + '<input class="inp" id="propSearch" placeholder="Search by address" value="'+esc(plist.q)+'">'
      + '</span>'
      + '<div class="row" style="gap:8px;margin-bottom:14px">'
        + '<div class="seg" style="flex:1">'
          + '<button class="'+(plist.scope==='mine'?'on':'')+'" data-act="propScope:mine">My Properties</button>'
          + '<button class="'+(plist.scope==='all'?'on':'')+'" data-act="propScope:all">All Properties</button>'
        + '</div>'
        + '<button class="iconbtn" data-act="agentFilter" style="background:var(--fill-chip)">'+icon('users',19)+'</button>'
      + '</div>'
      + (filtered && list.length
          ? '<div class="row" style="margin-bottom:12px"><span class="t-sub grow">'+list.length+' of '+total+' match</span>'
            + '<button class="linkbtn" data-act="clearFilters">Clear filters</button></div>' : '')
      + (list.length
          ? '<div class="stack">'+list.map(propCard).join('')+'</div>'
          : (filtered
              ? empty('search','No matches','Try a different search or clear the filters.','Clear all','clearFilters')
              : empty('building-skyscraper','No properties yet','Tap + to add your first property.')))
      + '<div style="height:24px"></div></div>';
  },
  fab: () => '<button class="fab" data-act="newProperty" data-tour="propFab">'+icon('plus',24)+'</button>',
  after(){
    const s = document.getElementById('propSearch');
    if(s) s.addEventListener('input', e => { plist.q = e.target.value; renderKeepFocus('propSearch'); });
  }
};
/* Re-render but restore focus + caret in a search field (300ms debounce in the real app). */
function renderKeepFocus(id){
  const el = document.getElementById(id);
  const pos = el ? el.selectionStart : 0;
  render();
  const el2 = document.getElementById(id);
  if(el2){ el2.focus(); try{ el2.setSelectionRange(pos,pos); }catch(e){} }
}
function propCard(p){
  return '<button class="rowbtn" data-act="openProp:'+p.id+'" style="align-items:flex-start;gap:12px;padding:12px">'
    + thumb(p.g)
    + '<span class="grow" style="padding-top:2px">'
      + (!p.mine ? '<span class="chip accent" style="font-size:9px;margin-bottom:5px">Co-listing</span><br>' : '')
      + '<span class="h-row clamp2" style="display:block;margin-bottom:6px">'+esc(addr(p))+'</span>'
      + '<span class="row" style="gap:11px;color:var(--text-tertiary)">'
        + '<span class="row" style="gap:3px">'+icon('bed',14)+'<span style="font-size:12px;font-weight:600">'+p.beds+'</span></span>'
        + '<span class="row" style="gap:3px">'+icon('bath',14)+'<span style="font-size:12px;font-weight:600">'+p.baths+'</span></span>'
        + '<span class="row" style="gap:3px">'+icon('car',14)+'<span style="font-size:12px;font-weight:600">'+p.garages+'</span></span>'
      + '</span>'
      + '<span class="money" style="display:block;font-size:13px;margin-top:7px">'+money(p.price)+(p.listing==='For Rental'?' pm':'')+'</span>'
    + '</span>'
    + '<span>'+statusChip(p.status, statusColour(p.status), true)+'</span>'
    + '</button>';
}
ACTS.propScope = s => { plist.scope=s; render(); };
ACTS.agentFilter = () => sheet({title:'Filter by agent', body:()=>
  '<div class="stack" style="padding-bottom:10px">'
  + ['Andre Roets (me)','Lindi Khumalo','Dawie Pretorius','Everyone'].map(a =>
    '<button class="rowbtn" data-act="closeSheet"><span class="avatar circle" style="width:32px;height:32px;font-size:11px">'
    + a.split(' ').map(w=>w[0]).slice(0,2).join('')+'</span><span class="t-body grow">'+esc(a)+'</span></button>').join('')+'</div>'});
ACTS.clearFilters = () => { plist.filters={suburbs:[],min:'',max:'',listing:[],status:[]}; plist.q=''; closeSheet(); render(); };
ACTS.propFilters = () => {
  const F = plist.filters;
  const suburbs = [...new Set(DB.properties.map(p=>p.suburb))];
  const group = (label, items, key) =>
    '<div class="field"><label>'+label+'</label><div class="row" style="gap:7px;flex-wrap:wrap">'
    + items.map(i=>'<button class="tchip'+(F[key].includes(i)?' on':'')+'" data-act="fToggle:'+key+'|'+i+'">'+esc(i)+'</button>').join('')
    + '</div></div>';
  sheet({
    title:'Filters', height:'72%',
    headAction:'<button class="linkbtn" data-act="clearFilters">Clear</button>',
    body: () => group('Suburb', suburbs, 'suburbs')
      + '<div class="field"><label>Price</label><div class="row" style="gap:10px">'
        + '<input class="inp" id="fMin" placeholder="Min" value="'+esc(F.min)+'" inputmode="numeric">'
        + '<input class="inp" id="fMax" placeholder="Max" value="'+esc(F.max)+'" inputmode="numeric"></div></div>'
      + group('Listing Type', ['For Sale','For Rental'], 'listing')
      + group('Status', ['active','draft'], 'status'),
    foot: () => '<button class="btn" data-act="applyFilters">Apply</button>'
  });
};
ACTS.fToggle = a => {
  const [key, val] = a.split('|');
  const arr = plist.filters[key];
  const i = arr.indexOf(val);
  if(i>=0) arr.splice(i,1); else arr.push(val);
  ACTS.propFilters();
};
ACTS.applyFilters = () => {
  const min = document.getElementById('fMin'), max = document.getElementById('fMax');
  plist.filters.min = min ? min.value.replace(/\D/g,'') : '';
  plist.filters.max = max ? max.value.replace(/\D/g,'') : '';
  closeSheet(); render();
};
/* THE RULE: a draft opens the EDITOR; a live listing opens the OVERVIEW. */
ACTS.openProp = id => {
  const p = DB.properties.find(x=>x.id==id);
  if(p.status==='draft'){
    startWizard(p);
    snack('Draft → opens the editor, not the overview.','','pencil');
  } else {
    go('overview', {id:p.id});
  }
};

/* ============================================================================
   15. ★ PROPERTY UPLOAD — the 4-step wizard
   ========================================================================== */
const PROPERTY_TYPES = ['House','Apartment','Townhouse','Vacant Land','Farm','Commercial'];
const PROPERTY_STATUS = ['Draft','Active','Under Offer','Sold','Withdrawn'];
const CATEGORIES = ['Residential','Commercial','Industrial','Agricultural'];
const MANDATES = ['Sole','Open','Joint','Exclusive'];

let W = null;   // the in-flight wizard
function startWizard(existing, presetContact){
  W = {
    step:1, created:false, propertyId:null, focusField:null, contact:presetContact||null,
    num:'', street:'', complex:'', unit:'', province:'', city:'', suburb:'', district:'', region:'',
    listing:'For Sale', title:'', type:'', status:'Draft', price:'', category:'', mandate:'',
    excerpt:'', desc:'',
    rentAmount:'', deposit:'', leaseStart:'', leaseEnd:'',
    spaces:[],                 // {k, count, features:{all:[], per:{}}}
    features:{},               // category -> [feature]
    photos:[],                 // {tag, g, name} — analysed server-side on upload
    g: Math.floor(Math.random()*8),
  };
  if(existing){
    W.propertyId = existing.id; W.created = true;
    W.num = existing.num; W.street = existing.street;
    W.province = existing.province; W.city = existing.city; W.suburb = existing.suburb;
    W.listing = existing.listing; W.title = existing.title; W.type = existing.type;
    W.status = 'Draft'; W.price = String(existing.price); W.mandate = existing.mandate;
    W.desc = existing.desc; W.g = existing.g;
  }
  go('wizard');
}
ACTS.newProperty = () => startWizard(null);

function readW(){
  const g = id => { const el = document.getElementById(id); return el ? el.value : null; };
  ['num','street','complex','unit','district','region','title','excerpt','desc',
   'rentAmount','deposit'].forEach(k => { const v = g('w_'+k); if(v!==null) W[k]=v; });
  const price = g('w_price'); if(price!==null) W.price = price.replace(/\D/g,'');   // strip non-digits
}

SCREENS.wizard = {
  nav:false,
  appbar: () => '<header class="appbar">'
    + '<button class="iconbtn" data-act="wPrev" title="Previous step">'+icon('arrow-left',21)+'</button>'
    + '<span class="title">'+(W.created && W.propertyId ? 'Edit Property' : 'New Property')+'</span>'
    + '<button class="iconbtn" data-act="wClose" title="Close">'+icon('x',21)+'</button></header>',
  render(){
    const seg = [1,2,3,4].map(i =>
      '<span style="flex:1;height:4px;border-radius:99px;background:'+(i<=W.step?'var(--accent)':'var(--fill-chip)')
      + (i<=W.step?';box-shadow:0 0 10px var(--accent-glow)':'')+'"></span>').join('');
    const body = [wStep1, wStep2, wStep3, wStep4][W.step-1]();
    return '<div style="padding:0 16px 8px" data-tour="wizardProgress"><div class="row" style="gap:5px">'+seg+'</div>'
      + '<div class="eyebrow mute" style="margin-top:8px">STEP '+W.step+' OF 4 · '
        + ['ADDRESS','DETAILS','SPACES & FEATURES','GALLERY'][W.step-1]+'</div></div>'
      + '<div class="pad tabin" style="padding-top:14px">'+body+'<div style="height:28px"></div></div>';
  },
  after(){
    if(W.focusField){
      const el = document.getElementById('w_'+W.focusField) || document.querySelector('[data-fieldkey="'+W.focusField+'"]');
      if(el){
        el.scrollIntoView({behavior:'smooth', block:'center'});
        el.classList.add('hl');
        setTimeout(()=>el.classList.remove('hl'), 3200);
      }
      W.focusField = null;
    }
  }
};
ACTS.wClose = () => { if(S.side==='agent') go('properties'); };
ACTS.wPrev = () => { readW(); if(W.step>1){ W.step--; render(true); } else go('properties'); };

/* ---- STEP 1 — Address (with the Province → City → Suburb cascade) -------- */
function wStep1(){
  return '<div class="h-card" style="margin-bottom:16px">Address</div>'
    + '<div class="row" style="gap:10px;align-items:flex-start">'
      + '<span style="flex:0 0 34%">'+field('Street Number', textInput('w_num','12', W.num))+'</span>'
      + '<span class="grow">'+field('Street Name', textInput('w_street','Beach Road', W.street))+'</span>'
    + '</div>'
    + field('Complex Name (optional)', textInput('w_complex','', W.complex))
    + field('Unit Number (optional)', textInput('w_unit','', W.unit))
    + '<div data-fieldkey="province">'+field('Province', pickerBtn('pickProvince', W.province, 'Select Province'), {req:true})+'</div>'
    + '<div data-fieldkey="city">'+field('City', pickerBtn('pickCity', W.city, 'Select City', !W.province),
        {req:true, hint: !W.province ? 'Select a province first' : ''})+'</div>'
    + '<div data-fieldkey="suburb">'+field('Suburb', pickerBtn('pickSuburb', W.suburb, 'Select Suburb', !W.city),
        {req:true, hint: !W.city ? 'Select a city first' : ''})+'</div>'
    + field('District (optional)', textInput('w_district','', W.district))
    + field('Region (optional)', textInput('w_region','', W.region))
    + '<div style="height:8px"></div>'
    + '<button class="btn" data-act="wNext1">Next</button>';
}
ACTS.wNext1 = () => { readW(); W.step = 2; render(true); };

/* A searchable picker sheet — autofocused search, "No matches" empty state.
   Only the results list is re-rendered as you type, so the input keeps focus. */
let picker = {items:[], act:'', q:''};
function pickerList(){
  const q = picker.q.trim().toLowerCase();
  const list = picker.items.filter(i => i.toLowerCase().includes(q));
  return list.length
    ? '<div class="stack" style="padding-bottom:10px">'+list.map(i =>
        '<button class="rowbtn" data-act="'+picker.act+':'+i+'"><span class="t-body grow">'+esc(i)+'</span>'
        + icon('chevron-right',17)+'</button>').join('')+'</div>'
    : '<div class="t-sub" style="text-align:center;padding:28px 0">No matches</div>';
}
function selectSheet(title, items, act){
  picker = {items, act, q:''};
  sheet({title, height:'70%', body: () =>
    '<span class="inp-wrap" style="display:flex;margin-bottom:12px">'
    + '<span class="pre">'+icon('search',18)+'</span>'
    + '<input class="inp" id="pickerSearch" placeholder="Search…" value="'+esc(picker.q)+'">'
    + '</span>'
    + '<div id="pickerList">'+pickerList()+'</div>'});
  const inp = document.getElementById('pickerSearch');
  if(inp) inp.focus();
}
ACTS.pickProvince = () => selectSheet('Select Province', Object.keys(SA), 'setProvince');
ACTS.pickCity = () => { if(W.province) selectSheet('Select City', Object.keys(SA[W.province]), 'setCity'); };
ACTS.pickSuburb = () => { if(W.city) selectSheet('Select Suburb', SA[W.province][W.city], 'setSuburb'); };
/* Changing a parent CLEARS all its children. */
ACTS.setProvince = v => { readW(); W.province=v; W.city=''; W.suburb=''; closeSheet(); };
ACTS.setCity = v => { readW(); W.city=v; W.suburb=''; closeSheet(); };
ACTS.setSuburb = v => { readW(); W.suburb=v; closeSheet(); };

/* ---- STEP 2 — Details --------------------------------------------------- */
function wStep2(){
  const isRental = W.listing==='For Rental';
  return '<div class="h-card" style="margin-bottom:16px">Details</div>'
    + '<div class="field" data-fieldkey="listing"><label>Listing Type<span class="req">*</span></label>'
      + '<div class="row" style="gap:8px">'
      + ['For Sale','For Rental'].map(l =>
        '<button class="tchip'+(W.listing===l?' on':'')+'" data-act="wListing:'+l+'" style="flex:1;justify-content:center;padding:12px">'+l+'</button>').join('')
      + '</div></div>'
    + '<div data-fieldkey="title">'+field('Title', textInput('w_title','e.g. Stunning 4 Bed House in Uvongo', W.title), {req:true})+'</div>'
    + '<div data-fieldkey="type">'+field('Property Type', pickerBtn('wPick:type', W.type, 'Select property type'), {req:true})+'</div>'
    + '<div data-fieldkey="status">'+field('Property Status', pickerBtn('wPick:status', W.status, 'Select status'), {req:true})+'</div>'
    + '<div data-fieldkey="price">'+field('Price (R)', textInput('w_price','e.g. 2500000', W.price, 'inputmode="numeric"'), {req:true})+'</div>'
    + field('Category', pickerBtn('wPick:category', W.category, 'Select category'))
    + field('Mandate Type', pickerBtn('wPick:mandate', W.mandate, 'Select mandate'))
    + field('Excerpt (max 500 chars)',
        '<textarea class="inp" id="w_excerpt" rows="2" maxlength="500" placeholder="A short teaser for the portals…">'+esc(W.excerpt)+'</textarea>'
        + '<div class="counter" id="excerptCounter">'+W.excerpt.length+' / 500</div>')
    + field('Description', '<textarea class="inp" id="w_desc" rows="4" placeholder="The full listing description…">'+esc(W.desc)+'</textarea>')
    + (isRental
      ? '<div class="card" style="margin-bottom:16px">'
        + '<div class="h-row" style="margin-bottom:14px">Rental Details</div>'
        + field('Rental Amount (R / month)', textInput('w_rentAmount','12500', W.rentAmount,'inputmode="numeric"'))
        + field('Deposit Amount (R)', textInput('w_deposit','25000', W.deposit,'inputmode="numeric"'))
        + field('Lease Start Date', pickerBtn('wDate:leaseStart', W.leaseStart, 'YYYY-MM-DD'))
        + '<div style="margin-bottom:0">'+field('Lease End Date', pickerBtn('wDate:leaseEnd', W.leaseEnd, 'YYYY-MM-DD'))+'</div>'
        + '</div>' : '')
    + '<div style="height:4px"></div>'
    /* ⚠️ The property is CREATED on this button the first time — steps 3 and 4 need a server ID. */
    + '<button class="btn" data-act="wNext2" data-tour="createBtn">'
      + (W.created ? 'Next: Spaces' : 'Create &amp; Continue')+'</button>';
}
ACTS.wListing = l => {
  readW();
  if(W.listing==='For Rental' && l==='For Sale'){ W.rentAmount=''; W.deposit=''; W.leaseStart=''; W.leaseEnd=''; }
  W.listing = l; render();
};
ACTS.wPick = key => {
  readW();
  const opts = {type:PROPERTY_TYPES, status:PROPERTY_STATUS, category:CATEGORIES, mandate:MANDATES}[key];
  const titles = {type:'Property Type', status:'Property Status', category:'Category', mandate:'Mandate Type'};
  sheet({title:titles[key], body:()=> '<div class="stack" style="padding-bottom:10px">'
    + opts.map(o=>'<button class="rowbtn" data-act="wSet:'+key+'|'+o+'"><span class="t-body grow">'+esc(o)+'</span>'
      + (W[key]===o?'<span style="color:var(--accent)">'+icon('check',18)+'</span>':'')+'</button>').join('')+'</div>'});
};
/* Anything that re-renders the wizard must first read what's in the inputs,
   or the re-render rebuilds them from stale state and eats what you typed. */
ACTS.wSet = a => { readW(); const [k,v] = a.split('|'); W[k]=v; closeSheet(); };
ACTS.wDate = k => {
  readW();                       // capture typed text before the re-render blows it away
  const d = addDays(TODAY, k==='leaseStart'?14:379);
  W[k] = fmtISO(d); closeSheet(); render();
  snack('Date picker → '+W[k]);
};

/* The "Missing Required Fields" dialog — one of the few real centred dialogs. */
ACTS.wNext2 = () => {
  readW();
  const missing = [];
  if(!W.province || !W.city || !W.suburb) missing.push({l:'Province, City & Suburb', step:1, f:'province'});
  if(!W.title) missing.push({l:'Title', step:2, f:'title'});
  if(!W.type) missing.push({l:'Property Type', step:2, f:'type'});
  if(!W.price) missing.push({l:'Price', step:2, f:'price'});
  if(missing.length){
    W.missing = missing;
    dialog({
      title:'Missing Required Fields', icon:'alert-circle', iconColour:'var(--destructive)',
      body:'<div style="margin-bottom:10px">Please fill in the following before creating the property:</div>'
        + '<ul style="padding-left:16px">'+missing.map(m=>'<li style="font-size:13px;margin-bottom:4px;color:var(--text-primary)">'+esc(m.l)+'</li>').join('')+'</ul>',
      cancel:'Close', confirm:'Take me there', confirmAct:'takeMeThere'
    });
    return;
  }
  if(!W.created){
    W.created = true;
    W.propertyId = 900 + Math.floor(Math.random()*90);
    snack('Property created (#'+W.propertyId+'). Steps 3 & 4 can now attach to it.','green','check');
  }
  W.step = 3; render(true);
};
/* "Take me there" jumps to the right step AND scrolls to the first missing field. */
ACTS.takeMeThere = () => {
  const m = W.missing[0];
  closeDialog();
  W.step = m.step;
  W.focusField = m.f;
  render(true);
};
/* ---- STEP 3 — Spaces & Features ---------------------------------------- */
function wStep3(){
  const used = W.spaces.map(s=>s.k);
  const avail = SPACE_TYPES.filter(s=>!used.includes(s.k));
  return '<div class="h-card" style="margin-bottom:14px">Spaces &amp; Features</div>'
    + (avail.length
      ? '<div class="field"><label>Add a space</label>'+pickerBtn('addSpace','', 'Choose a space type…')+'</div>'
      : '')
    + (W.spaces.length
      ? '<div class="stack" style="margin-bottom:18px">'+W.spaces.map(spaceCard).join('')+'</div>'
      : '<div class="card" style="margin-bottom:18px"><div class="t-sub" style="text-align:center;padding:8px 0">No spaces added yet.</div></div>')
    + '<div class="h-row" style="margin:20px 0 12px">Property Features</div>'
    + Object.keys(PROP_FEATURES).map(featureAccordion).join('')
    + '<div style="height:16px"></div>'
    + '<button class="btn" data-act="wNext3">Next: Gallery</button>';
}
function spaceCard(s){
  const st = SPACE_TYPES.find(t=>t.k===s.k);
  const n = (s.features.all||[]).length;
  return '<div class="card row" style="padding:12px 14px;gap:11px">'
    + '<button class="row grow" data-act="openFeatures:'+s.k+'" style="gap:11px;text-align:left;min-width:0">'
      + ibox(st.icon,'var(--accent)',40,20)
      + '<span class="grow" style="min-width:0">'
        + '<span class="t-body" style="display:block">'+esc(s.k)+'</span>'
        + '<span class="t-sub">'+n+' feature'+(n===1?'':'s')+'</span></span>'
    + '</button>'
    + '<span class="row" style="gap:2px;flex:none">'
      + '<button class="iconbtn" data-act="spaceStep:'+s.k+'|-1" style="width:30px;height:30px">'+icon('minus',16)+'</button>'
      + '<span style="min-width:30px;text-align:center;font-size:14px;font-weight:700">'+s.count+'</span>'
      + '<button class="iconbtn" data-act="spaceStep:'+s.k+'|1" style="width:30px;height:30px">'+icon('plus',16)+'</button>'
      + '<button class="iconbtn" data-act="removeSpace:'+s.k+'" style="width:30px;height:30px;color:var(--text-tertiary)">'+icon('x',16)+'</button>'
    + '</span></div>';
}
ACTS.addSpace = () => {
  const used = W.spaces.map(s=>s.k);
  const avail = SPACE_TYPES.filter(s=>!used.includes(s.k));
  sheet({title:'Add a space', height:'62%', body:()=>
    '<div class="stack" style="padding-bottom:10px">'+avail.map(s =>
      '<button class="rowbtn" data-act="doAddSpace:'+s.k+'">'+ibox(s.icon,'var(--accent)',40,20)
      + '<span class="t-body grow">'+esc(s.k)+'</span>'+icon('chevron-right',17)+'</button>').join('')+'</div>'});
};
ACTS.doAddSpace = k => {
  W.spaces.push({k, count:1, features:{all:[], per:{}}});
  closeSheet();
  ACTS.openFeatures(k);          // adding a space immediately opens the feature picker
};
ACTS.spaceStep = a => {
  const [k, dir] = a.split('|');
  const s = W.spaces.find(x=>x.k===k);
  const st = SPACE_TYPES.find(t=>t.k===k);
  s.count = Math.round((s.count + st.step * (+dir)) * 2) / 2;   // bathrooms step by 0.5
  if(s.count <= 0){ W.spaces = W.spaces.filter(x=>x.k!==k); }    // 0 removes the space
  render();
};
ACTS.removeSpace = k => { W.spaces = W.spaces.filter(x=>x.k!==k); render(); };

/* Feature picker sheet — "All Units" / "Per Unit" tabs appear when count >= 2. */
let fpTab = 'all', fpUnit = 1;
ACTS.openFeatures = k => {
  fpTab = 'all'; fpUnit = 1;
  const build = () => {
    const s = W.spaces.find(x=>x.k===k);
    if(!s) return '';
    const multi = s.count >= 2;
    const groups = SPACE_FEATURES[k] || {};
    const selected = fpTab==='all' ? (s.features.all||[]) : (s.features.per[fpUnit]||[]);
    return (multi
      ? segmented([{k:'all',l:'All Units'},{k:'per',l:'Per Unit'}], fpTab, 'fpTab')
        + (fpTab==='per'
          ? '<div class="row" style="gap:7px;margin-top:12px;overflow-x:auto;padding-bottom:4px">'
            + Array.from({length:Math.ceil(s.count)}, (_,i)=>i+1).map(u =>
              '<button class="tchip'+(fpUnit===u?' on':'')+'" data-act="fpUnit:'+k+'|'+u+'">'+esc(k)+' '+u+'</button>').join('')
            + '</div>' : '')
      : '')
      + '<div style="height:14px"></div>'
      + Object.keys(groups).map(cat =>
        '<div style="margin-bottom:16px"><div class="eyebrow mute" style="margin-bottom:9px">'+esc(cat.toUpperCase())+'</div>'
        + '<div class="row" style="gap:7px;flex-wrap:wrap">'
        + groups[cat].map(f =>
          '<button class="tchip'+(selected.includes(f)?' on':'')+'" data-act="fpToggle:'+k+'|'+f+'">'
          + (selected.includes(f)?icon('check',13):'')+esc(f)+'</button>').join('')
        + '</div></div>').join('');
  };
  sheet({title:k, height:'70%', body:build, foot:()=>'<button class="btn" data-act="closeSheet">Done</button>'});
};
ACTS.fpTab = t => { fpTab = t; render(); };
ACTS.fpUnit = a => { const [k,u] = a.split('|'); fpUnit = +u; render(); };
ACTS.fpToggle = a => {
  const [k, f] = a.split('|');
  const s = W.spaces.find(x=>x.k===k);
  const target = fpTab==='all' ? (s.features.all = s.features.all||[])
                               : (s.features.per[fpUnit] = s.features.per[fpUnit]||[]);
  const i = target.indexOf(f);
  if(i>=0) target.splice(i,1); else target.push(f);
  render();
};
/* Accordion open/closed state — kept off the wizard object, because the same
   helper is used on Compliance, where there is no wizard. */
let openAcc = null;
function featureAccordion(cat){
  const sel = W.features[cat] || [];
  const open = openAcc === cat;
  return '<div class="card" style="margin-bottom:10px;padding:0;overflow:hidden">'
    + '<button class="row" data-act="acc:'+cat+'" style="width:100%;padding:14px 16px;text-align:left">'
      + '<span class="t-body grow">'+esc(cat)+'</span>'
      + '<span class="chip'+(sel.length?' accent':'')+'">'+sel.length+' selected</span>'
      + '<span style="margin-left:8px;color:var(--text-tertiary)">'+icon(open?'chevron-up':'chevron-down',17)+'</span>'
    + '</button>'
    + (open ? '<div style="padding:0 16px 16px"><div class="row" style="gap:7px;flex-wrap:wrap">'
        + PROP_FEATURES[cat].map(f =>
          '<button class="tchip'+(sel.includes(f)?' on':'')+'" data-act="pfToggle:'+cat+'|'+f+'">'
          + (sel.includes(f)?icon('check',13):'')+esc(f)+'</button>').join('')
        + '</div></div>' : '')
    + '</div>';
}
ACTS.acc = cat => { openAcc = openAcc===cat ? null : cat; render(); };
ACTS.pfToggle = a => {
  const [cat, f] = a.split('|');
  const arr = W.features[cat] = W.features[cat] || [];
  const i = arr.indexOf(f);
  if(i>=0) arr.splice(i,1); else arr.push(f);
  render();
};
ACTS.wNext3 = () => { W.step = 4; render(true); };

/* Confidence pips — 4 dots, filled in proportion to the score. Used by the web
   preview in the explanation panel. */
function pips(c){
  const n = Math.round(c*4);
  return '<span class="row" style="gap:2px">'+[1,2,3,4].map(i =>
    '<span style="width:4px;height:4px;border-radius:99px;background:'+(i<=n?'currentColor':'color-mix(in srgb,currentColor 25%,transparent)')+'"></span>').join('')+'</span>';
}

/* ============================================================================
   AI PHOTO ANALYSIS — where it actually happens.
   There is NO AI panel in the mobile app. Photos you upload from the gallery go
   straight to the server, which queues each one for analysis automatically — the
   agent does not start it, wait for it, or confirm anything on the phone.
   The suggested features surface in the WEB app, on the property, later.
   All the phone does is tell you the analysis is under way.
   ========================================================================== */
function aiNotice(){
  const n = W.photos.length;
  if(!n) return '';
  return '<div class="card" style="border-left:2px solid #8B5CF6;margin-bottom:16px" data-tour="aiNotice">'
    + '<div class="row" style="gap:8px;margin-bottom:6px">'
      + aiBadge()
      + '<span style="font-size:14px;font-weight:700" class="grow">Photos are being analysed</span>'
    + '</div>'
    + '<div class="t-sub" style="font-size:12px;line-height:1.6">'
      + n + ' photo' + (n===1?'':'s') + ' queued automatically on upload. '
      + 'The suggested features will be waiting for you <b style="color:var(--text-primary)">on the web app</b>, '
      + 'on this property — nothing to do here.'
    + '</div></div>';
}

/* ---- STEP 4 — Gallery --------------------------------------------------- */
/* Photo TAGS are derived from the spaces created in Step 3. */
function photoTags(){ return W.spaces.map(s=>s.k); }
function wStep4(){
  const tags = photoTags();
  const groups = {};
  W.photos.forEach(p => (groups[p.tag||'Unsorted'] = groups[p.tag||'Unsorted']||[]).push(p));
  const keys = [...new Set([...tags, ...Object.keys(groups)])];
  return '<div class="row" style="margin-bottom:14px"><span class="h-card grow">Gallery</span>'
      + '<button class="linkbtn" data-act="openUpload" data-tour="uploadBtn">'+icon('upload',15)+'Upload</button></div>'
    + aiNotice()
    + (!W.photos.length && !tags.length
      ? '<div class="card"><div class="t-sub" style="text-align:center;padding:14px 8px;line-height:1.6">'
        + 'No photos yet. Add spaces first to unlock tags, or tap Upload to add untagged photos.</div></div>'
      : keys.map(tag => {
          const list = groups[tag] || [];
          return '<div style="margin-bottom:20px">'
            + '<div class="row" style="margin-bottom:9px">'
              + '<span class="h-row grow">'+esc(tag)+' · '+list.length+'</span>'
              + '<button class="chip accent" data-act="openUpload:'+tag+'">'+icon('plus',12)+'Add Photo</button></div>'
            + (list.length
              ? '<div class="row" style="gap:8px;overflow-x:auto;padding-bottom:4px">'
                + list.map(p => '<div class="thumb g'+(p.g%8)+'" style="width:120px;height:90px;flex:none">'+icon('photo',22)+'</div>').join('')
                + '</div>'
              : '<div class="t-sub" style="font-size:12px">No photos in this group yet.</div>')
            + '</div>';
        }).join(''))
    + '<div style="height:12px"></div>'
    + '<button class="btn" data-act="wSave">'+icon('device-floppy',18)+'Save Property</button>';
}
/* The upload sheet — an 85% draggable sheet with tag chips, three sources and a queue. */
let up = {tag:null, queue:[], uploading:false, done:0, failed:[], retrying:false};
ACTS.openUpload = tag => {
  const tags = photoTags();
  up = {tag: tag || (tags[0]||null), queue:[], uploading:false, done:0, failed:[]};
  openUploadSheet();
};
function openUploadSheet(){
  sheet({
    title:'Upload Photos', height:'85%',
    body: () => {
      const tags = photoTags();
      const counts = {};
      W.photos.forEach(p => counts[p.tag] = (counts[p.tag]||0)+1);
      return '<div class="eyebrow mute" style="margin-bottom:9px">TAG THIS PHOTO</div>'
        + (tags.length
          ? '<div class="row" style="gap:7px;flex-wrap:wrap;margin-bottom:16px">'
            + tags.map(t => '<button class="tchip'+(up.tag===t?' on':'')+'" data-act="upTag:'+t+'">'
                + esc(t)+' · '+(counts[t]||0)+'</button>').join('')
            + '<button class="tchip'+(up.tag===null?' on':'')+'" data-act="upTag:">No tag</button>'
            + '</div>'
          : '<div class="t-sub" style="margin-bottom:16px">This property has no spaces yet — photos will upload to Unsorted.</div>')
        + '<div class="row" style="gap:8px;margin-bottom:18px">'
          + [['Multi Capture','camera'],['Native','photo'],['Gallery','photo-plus']].map(([l,i]) =>
            '<button class="btn2 sm" data-act="upPick:'+l+'" style="flex:1;flex-direction:column;height:64px;gap:5px;padding:0">'
            + icon(i,19)+'<span style="font-size:11px;font-weight:600">'+l+'</span></button>').join('')
        + '</div>'
        + (up.queue.length
          ? '<div class="eyebrow mute" style="margin-bottom:9px">'
              + (up.uploading ? 'UPLOADING '+(up.done+1)+' OF '+up.queue.length+'…' : up.queue.length+' SELECTED')+'</div>'
            + (up.uploading
              ? '<div class="progress" style="margin-bottom:14px;height:5px"><i style="width:'+Math.round(up.done/up.queue.length*100)+'%"></i></div>'
              : '')
            + '<div class="row" style="gap:8px;flex-wrap:wrap;margin-bottom:14px">'
            + up.queue.map((p,i) => '<div style="position:relative">'
                + '<div class="thumb g'+(p.g%8)+'" style="width:90px;height:90px;'
                  + (up.failed.includes(i)?'outline:2px solid var(--danger);outline-offset:-2px':'')+'">'+icon('photo',20)+'</div>'
                + (up.uploading ? '' : '<button data-act="upRemove:'+i+'" style="position:absolute;top:-6px;right:-6px;width:22px;height:22px;border-radius:99px;background:var(--danger);color:#fff;display:grid;place-items:center;box-shadow:0 4px 10px rgba(0,0,0,.3)">'+icon('x',12)+'</button>')
                + '</div>').join('')
            + '</div>'
            + (up.failed.length
              ? '<button class="card" data-act="upRetry" style="width:100%;text-align:left;border-left:2px solid var(--danger);margin-bottom:8px">'
                + '<div class="row" style="gap:8px;color:var(--danger);margin-bottom:5px">'+icon('alert-circle',16)
                + '<span style="font-size:13px;font-weight:700">Failed ('+up.failed.length+') — tap to retry</span></div>'
                + '<div class="t-sub" style="font-size:12px">IMG_28'+(40+up.failed[0])+'.jpg · upload interrupted</div>'
                + '<span class="chip accent" style="margin-top:9px">'+icon('refresh',12)+'Retry</span></button>'
              : '')
          : '<div class="t-sub" style="text-align:center;padding:20px 0">Pick a source above to add photos.</div>');
    },
    foot: () => '<button class="btn" data-act="upSend"'+((!up.queue.length||up.uploading)?' disabled':'')+'>'
      + (up.uploading ? 'Uploading…' : 'Upload '+up.queue.length+' photo(s)')+'</button>'
  });
}
ACTS.upTag = t => { up.tag = t || null; openUploadSheet(); };
ACTS.upPick = src => {
  const n = src==='Multi Capture' ? 3 : src==='Native' ? 1 : 5;
  for(let i=0;i<n;i++) up.queue.push({g: Math.floor(Math.random()*8), name:'IMG_28'+(40+up.queue.length)+'.jpg'});
  openUploadSheet();
};
ACTS.upRemove = i => { up.queue.splice(+i,1); openUploadSheet(); };
ACTS.upSend = () => {
  up.uploading = true; up.done = 0; up.failed = [];
  openUploadSheet();
  const step = () => {
    up.done++;
    if(up.done >= up.queue.length){
      // One file "fails" the first time — the learner retries it and it succeeds.
      if(!up.retried && up.queue.length > 2){
        up.failed = [up.queue.length-1];
        up.uploading = false;
        openUploadSheet();
        return;
      }
      finishUpload();
      return;
    }
    openUploadSheet();
    setTimeout(step, 380);
  };
  setTimeout(step, 420);
};
ACTS.upRetry = () => {
  up.retried = true; up.failed = []; up.uploading = true; up.done = up.queue.length-1;
  openUploadSheet();
  setTimeout(finishUpload, 700);
};
function finishUpload(){
  const n = up.queue.length;
  up.queue.forEach(p => W.photos.push({tag:up.tag, g:p.g, name:p.name}));
  up = {tag:null, queue:[], uploading:false, done:0, failed:[]};
  closeSheet();
  render();
  // Reaching the server IS the trigger. The agent starts nothing and waits for
  // nothing — the analysis runs server-side and the results land on the web.
  snack(n+' photo(s) uploaded','green','check');
}
ACTS.wSave = () => {
  const p = {
    id: W.propertyId || uid(), num:W.num, street:W.street||'New listing', suburb:W.suburb||'—',
    city:W.city||'—', province:W.province||'—', title:W.title, price:+W.price||0,
    status:(W.status||'Draft').toLowerCase()==='draft'?'draft':'active', type:W.type||'House',
    listing:W.listing, beds:(W.spaces.find(s=>s.k==='Bedroom')||{count:0}).count,
    baths:(W.spaces.find(s=>s.k==='Bathroom')||{count:0}).count,
    garages:(W.spaces.find(s=>s.k==='Garage')||{count:0}).count,
    floor:0, erf:0, photos:W.photos.length, photosNeeded:12, g:W.g, mandate:W.mandate||'Open', mine:true,
    listed:'—', expires:'—', loaded:'just now', modified:'just now', days:0, live:false,
    desc:W.desc, compliance:{authority:false, fica:false, photos:W.photos.length>=12, details:!!(W.title&&W.price&&W.type)},
    sellers: W.contact ? [{name:W.contact, fica:'pending'}] : [], portals:[]
  };
  const i = DB.properties.findIndex(x=>x.id===p.id);
  if(i>=0) DB.properties[i] = {...DB.properties[i], ...p}; else DB.properties.unshift(p);
  go('overview', {id:p.id});
  snack('Property saved.','green','check');
};
/* ============================================================================
   16. SCREEN — Property Overview
   ========================================================================== */
let descOpen = false;
const curProp = () => DB.properties.find(p=>p.id==S.params.id) || DB.properties[0];

SCREENS.overview = {
  nav:false,
  appbar: () => subAppBar('Overview',
    '<button class="iconbtn" data-act="editProp">'+icon('pencil',20)+'</button>'),
  render(){
    const p = curProp();
    const c = p.compliance;
    const gates = [
      {k:'authority', l:'Authority to market', ok:c.authority,
       detail: c.authority ? 'Mandate signed 3 Jul 2026' : 'No signed mandate on file',
       act:'Send mandate for signature', actId:'gate:authority'},
      {k:'fica', l:'Seller FICA', ok:c.fica,
       detail: c.fica ? 'All sellers verified' : 'Piet Grobler is not FICA verified',
       act:'Start seller FICA', actId:'gate:fica'},
      {k:'photos', l:'Photos', ok:p.photos >= p.photosNeeded,
       detail: 'Portals require '+p.photosNeeded+' photos', chip: p.photos+'/'+p.photosNeeded+' photos',
       act:null, actId:'gate:photos'},
      {k:'details', l:'Listing details', ok:c.details,
       detail: c.details ? 'All required fields complete' : 'Title, price or property type missing',
       act:'Resolve', actId:'gate:details'},
    ];
    const blocking = gates.filter(g=>!g.ok);
    const ready = blocking.length===0;
    const badge = p.live ? {l:'LIVE', c:'var(--success)', i:'circle-check'}
                : ready   ? {l:'READY', c:'var(--info)', i:'clipboard-check'}
                          : {l:'BLOCKED', c:'var(--p-property)', i:'alert-triangle'};
    const shortDesc = p.desc.length > 220 && !descOpen ? p.desc.slice(0,220)+'…' : p.desc;

    return hero(p)
      /* 2 — At a glance */
      + '<div class="pad"><div class="card" style="margin-bottom:12px">'
        + '<div class="t-sub" style="line-height:1.7">'
        + [p.beds+' Beds', p.baths+' Baths', p.garages+' Garages',
           (p.floor?p.floor+' m² floor':null), (p.erf?p.erf+' m² erf':null),
           p.photos+' Photos', p.mandate+' mandate'].filter(Boolean).join(' · ')
        + '</div></div>'

      /* 3 — Compliance: the most important card in the app */
      + '<div class="card" style="margin-bottom:12px" data-tour="compliance">'
        + '<div class="row" style="margin-bottom:14px">'
          + '<span class="h-card grow">Compliance</span>'
          + statusChip(badge.l, badge.c)
        + '</div>'
        + gates.map(g =>
          '<div class="row" style="align-items:flex-start;gap:10px;padding:9px 0;border-top:1px solid var(--border)">'
          + '<span style="color:'+(g.ok?'var(--success)':'var(--p-property)')+';flex:none;margin-top:1px">'
            + icon(g.ok?'circle-check':'alert-circle',18)+'</span>'
          + '<span class="grow"><span class="t-body" style="display:block">'+esc(g.l)+'</span>'
            + '<span class="t-sub" style="font-size:12px">'+esc(g.detail)+'</span></span>'
          + (g.chip ? '<span class="chip'+(g.ok?'':' ')+'" style="'+(g.ok?'':'background:rgba(249,115,22,.14);color:var(--p-property)')+'">'+esc(g.chip)+'</span>'
              : (!g.ok && g.act ? '<button class="chip accent" data-act="'+g.actId+'">'+esc(g.act)+'</button>' : ''))
          + '</div>').join('')
        + (blocking.length
          ? '<div style="margin-top:14px;border-radius:var(--r-small);padding:12px 14px;background:rgba(249,115,22,.12);border-left:3px solid var(--p-property)">'
            + '<div class="row" style="gap:7px;color:var(--p-property);margin-bottom:7px">'+icon('alert-triangle',15)
            + '<span style="font-size:12px;font-weight:700;letter-spacing:.4px">BLOCKING GO-LIVE</span></div>'
            + '<ul style="padding-left:16px;margin:0">'
            + blocking.map(g=>'<li style="font-size:12.5px;color:var(--text-secondary);margin-bottom:3px">'+esc(g.detail)+'</li>').join('')
            + '</ul></div>'
          : '')
        + (p.sellers.length
          ? '<div class="divider"></div><div class="eyebrow mute" style="margin-bottom:9px">SELLERS</div>'
            + p.sellers.map(s => '<div class="row" style="padding:6px 0;gap:10px">'
              + '<span class="avatar circle" style="width:30px;height:30px;font-size:11px">'+s.name.split(' ').map(w=>w[0]).slice(0,2).join('')+'</span>'
              + '<span class="t-body grow">'+esc(s.name)+'</span>'
              + statusChip(s.fica==='approved'?'FICA approved':'FICA pending',
                  s.fica==='approved'?'var(--success)':'var(--warning)', true)+'</div>').join('')
          : '')
        + '<div class="divider"></div>'
        + '<div class="eyebrow mute" style="margin-bottom:9px">NEXT ACTIONS</div>'
        + '<div class="t-sub" style="margin-bottom:14px">'
          + (ready ? 'Everything is green — send it to market.'
                   : blocking.map(g=>g.act||('Add '+(p.photosNeeded-p.photos)+' more photos')).join(' · '))
        + '</div>'
        + (p.live
          ? '<div class="row" style="gap:8px;padding:12px 14px;border-radius:var(--r-small);background:rgba(34,197,94,.12);color:var(--success)">'
            + icon('circle-check',17)+'<span style="font-size:13px;font-weight:700">Live · Sent to market on 3 Jul 2026</span></div>'
          : '<button class="btn" data-act="sendToMarket"'+(ready?'':' disabled')+' data-tour="sendMarket">'
            + icon('send',18)+'Send Authority to Market</button>'
            + (ready?'':'<div class="hint" style="text-align:center;margin-top:9px">Resolve the items above to enable sending to market.</div>'))
      + '</div>'

      /* 4 — Rental inspections (rentals only) */
      + (p.listing==='For Rental'
        ? '<button class="rowbtn" style="margin-bottom:12px">'+ibox('clipboard-check','var(--info)',40,20)
          + '<span class="grow"><span class="t-body" style="display:block">Rental Inspections</span>'
          + '<span class="t-sub">In, out &amp; custom inspection galleries</span></span>'+icon('chevron-right',17)+'</button>'
        : '')

      /* 5 — Contacts */
      + sectionCard('Contacts', p.sellers.length
          ? p.sellers.map(s => '<div class="row" style="padding:8px 0;gap:10px">'
              + '<span class="avatar circle" style="width:32px;height:32px;font-size:11px">'+s.name.split(' ').map(w=>w[0]).slice(0,2).join('')+'</span>'
              + '<span class="grow"><span class="t-body" style="display:block">'+esc(s.name)+'</span>'
              + '<span class="t-sub">Seller</span></span>'
              + '<button class="iconbtn" data-act="unlink:'+esc(s.name)+'">'+icon('unlink',17)+'</button></div>').join('')
          : '<div class="t-sub">No contacts linked yet.</div>')

      /* 6 — Description */
      + (p.desc ? sectionCard('Description',
          '<div class="t-sub" style="line-height:1.65">'+esc(shortDesc)+'</div>'
          + (p.desc.length>220 ? '<button class="linkbtn" style="margin-top:8px" data-act="toggleDesc">'
              + (descOpen?'Show less':'Read more')+'</button>' : '')) : '')

      /* 7 — Live preview */
      + '<button class="btn2" data-act="preview" style="margin-bottom:12px">'+icon('external-link',17)+'Open Live Preview</button>'

      /* 8 — Syndication */
      + sectionCard('Where this listing is published', p.portals.length
          ? p.portals.map(pt => '<div class="row" style="padding:9px 0;gap:10px;border-top:1px solid var(--border)">'
              + ibox(pt.live?'world':'lock', pt.live?'var(--success)':'var(--neutral)', 32, 16)
              + '<span class="grow"><span class="t-body" style="display:block">'+esc(pt.n)+'</span>'
              + '<span class="t-sub" style="font-size:11.5px">'+(pt.ref?'Ref '+esc(pt.ref):'Not published')+'</span></span>'
              + (pt.live ? statusChip('Live','var(--success)',true)
                         : '<span class="chip" style="opacity:.6">Not published</span>')+'</div>').join('')
          : '<div class="t-sub"><b style="color:var(--text-primary)">Not yet published to any portal</b><br>'
            + "This listing isn't live anywhere yet — open Syndication on the desktop to publish.</div>")

      /* 9 — Listing agents */
      + sectionCard('Listing Agent(s)',
          '<div class="row" style="padding:6px 0;gap:10px">'
          + '<span class="avatar circle">AR</span>'
          + '<span class="grow"><span class="t-body" style="display:block">Andre Roets</span>'
          + '<span class="t-sub">Lead agent</span></span>'
          + '<button class="iconbtn" data-act="call">'+icon('phone',17)+'</button>'
          + '<button class="iconbtn" data-act="email">'+icon('mail',17)+'</button></div>'
          + (!p.mine ? '<div class="row" style="padding:6px 0;gap:10px;border-top:1px solid var(--border)">'
              + '<span class="avatar circle">LK</span>'
              + '<span class="grow"><span class="t-body" style="display:block">Lindi Khumalo</span>'
              + '<span class="t-sub">Co-agent</span></span></div>' : ''))

      /* 10, 11 — Owner + Virtual tour */
      + sectionCard('Owner', '<div class="t-sub">'+(p.sellers[0]?esc(p.sellers[0].name):'—')+'</div>')
      + sectionCard('Virtual Tour', '<div class="t-sub">No virtual tour linked.</div>')

      /* 12 — Key dates (missing values render as "—", never null) */
      + sectionCard('Key dates',
          '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">'
          + [['Listed',p.listed],['Expires',p.expires],['Loaded',p.loaded],['Modified',p.modified]].map(([l,v]) =>
            '<div><div class="eyebrow mute" style="margin-bottom:3px">'+l.toUpperCase()+'</div>'
            + '<div class="t-body">'+esc(v||'—')+'</div></div>').join('')
          + '</div>')
      + '<div style="height:24px"></div></div>';
  }
};
function hero(p){
  return '<div style="position:relative;height:220px;flex:none" class="g'+(p.g%8)+'">'
    + '<div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,.35),rgba(0,0,0,.72))"></div>'
    + '<div style="position:absolute;inset:0;display:grid;place-items:center;color:rgba(255,255,255,.18)">'+icon('building-skyscraper',56)+'</div>'
    + '<div style="position:absolute;top:12px;left:16px">'+statusChip(p.status, statusColour(p.status), true)+'</div>'
    + '<div style="position:absolute;top:12px;right:16px"><span class="chip" style="background:rgba(0,0,0,.45);color:#fff">'+p.days+' days on market</span></div>'
    + '<div style="position:absolute;bottom:14px;left:16px;right:16px">'
      + '<div style="font-size:18px;font-weight:700;color:#fff;letter-spacing:-.3px;margin-bottom:2px" class="clamp2">'+esc(p.title||addr(p))+'</div>'
      + '<div style="font-size:12.5px;color:rgba(255,255,255,.75);margin-bottom:8px">'+esc(p.suburb)+', '+esc(p.city)+'</div>'
      + '<div class="money" style="font-size:22px">'+money(p.price)+(p.listing==='For Rental'?' <span style="font-size:13px">pm</span>':'')+'</div>'
    + '</div></div>';
}
function sectionCard(title, inner){
  return '<div class="card" style="margin-bottom:12px">'
    + '<div class="h-row" style="margin-bottom:11px">'+esc(title)+'</div>'+inner+'</div>';
}
ACTS.toggleDesc = () => { descOpen = !descOpen; render(); };
ACTS.editProp = () => { const p = curProp(); startWizard(p); };
ACTS.preview = () => snack('Opens the public listing page in a browser.','','external-link');
ACTS.call = () => snack('Dialling 082 555 0101…','','phone');
ACTS.email = () => snack('Opening your mail app…','','mail');
ACTS.unlink = name => dialog({title:'Unlink contact', icon:'unlink',
  body:'Remove '+esc(name)+' from this property? This only removes the link — the contact is kept.',
  cancel:'Cancel', confirm:'Unlink', destructive:true, confirmAct:'doUnlink'});
ACTS.doUnlink = () => { closeDialog(); snack('Contact unlinked.'); };
ACTS.gate = k => {
  const p = curProp();
  if(k==='authority'){ p.compliance.authority = true; render(); snack('Mandate sent for signature.','green','send'); }
  if(k==='fica'){ p.compliance.fica = true; p.sellers.forEach(s=>s.fica='approved'); render(); snack('Seller FICA started.','green','shield-check'); }
  if(k==='photos'){ p.photos = p.photosNeeded; render(); snack(p.photosNeeded+' photos on file.','green','photo'); }
  if(k==='details'){ p.compliance.details = true; render(); snack('Listing details resolved.','green','check'); }
};
ACTS.sendToMarket = () => {
  const p = curProp();
  p.live = true; p.status = 'active';
  p.portals = [{n:'Website',live:true,ref:'REF-'+p.id},{n:'Agency Premium',live:true,ref:'REF-P-'+p.id},
               {n:'Private Property',live:false,ref:null},{n:'Property24',live:false,ref:null}];
  render(); snack('Property sent to market.','green','send');
};
/* ============================================================================
   17. SCREEN — Contacts
   ========================================================================== */
let clist = {q:'', scope:'mine'};
const CONTACT_TYPES = ['— None —','Buyer','Seller','Tenant','Landlord','Investor','Referral'];
const initials = c => (c.first[0]+(c.last[0]||'')).toUpperCase();
const fullName = c => c.first+' '+c.last;

SCREENS.contacts = {
  nav:false,
  appbar: () => subAppBar('Contacts'),
  render(){
    const q = clist.q.trim().toLowerCase();
    const list = DB.contacts.filter(c => !q || (fullName(c)+' '+c.phone+' '+c.email).toLowerCase().includes(q));
    return '<div class="pad">'
      + '<span class="inp-wrap" style="display:flex;margin-bottom:12px">'
        + '<span class="pre">'+icon('search',18)+'</span>'
        + '<input class="inp" id="contactSearch" placeholder="Search contacts…" value="'+esc(clist.q)+'">'
      + '</span>'
      + '<div class="seg" style="margin-bottom:14px">'
        + '<button class="'+(clist.scope==='mine'?'on':'')+'" data-act="cScope:mine">My Contacts</button>'
        + '<button class="'+(clist.scope==='all'?'on':'')+'" data-act="cScope:all">All Contacts</button>'
      + '</div>'
      + (list.length
        ? '<div class="stack">'+list.map(c =>
            '<button class="rowbtn" data-act="openContact:'+c.id+'">'
            + '<span class="avatar circle s44">'+initials(c)+'</span>'
            + '<span class="grow"><span class="h-row" style="display:block">'+esc(fullName(c))+'</span>'
            + '<span class="t-sub">'+esc(c.phone || 'No phone')+'</span></span>'
            + '<span class="row" style="gap:6px">'
              + (c.type ? '<span class="chip accent">'+esc(c.type)+'</span>' : '')
              + (c.wa > 0 ? '<span class="chip" style="background:rgba(34,197,94,.14);color:var(--success)">'
                  + icon('brand-whatsapp',12)+c.wa+'</span>' : '')
            + '</span></button>').join('')+'</div>'
        : (q ? empty('search','No matches','No contacts match "'+clist.q+'".')
             : empty('users','No contacts yet','Tap + to add your first contact.')))
      + '<div style="height:24px"></div></div>';
  },
  fab: () => '<button class="fab" data-act="newContact" data-tour="contactFab">'+icon('plus',24)+'</button>',
  after(){
    const s = document.getElementById('contactSearch');
    if(s) s.addEventListener('input', e => { clist.q = e.target.value; renderKeepFocus('contactSearch'); });
  }
};
ACTS.cScope = s => { clist.scope=s; render(); };

/* ---- New Contact: a SINGLE-PAGE form (no wizard) ------------------------ */
let NC = null;
ACTS.newContact = (role) => {
  NC = {first:'', last:'', phone:'', email:'', idnum:'', type:'', notes:'', errs:{}, role:role||null};
  go('newContact');
};
SCREENS.newContact = {
  nav:false,
  appbar: () => subAppBar('New Contact'),
  render(){
    const e = NC.errs;
    return '<div class="pad">'
      + (NC.role ? '<div class="card accent" style="margin-bottom:16px"><div class="row" style="gap:9px">'
          + ibox('link','var(--accent)',32,16)
          + '<span class="t-sub">This contact will be linked to the new listing as the <b style="color:var(--text-primary)">'+esc(NC.role)+'</b>.</span></div></div>' : '')
      + field('First Name', textInput('nc_first','John', NC.first), {req:true, err:e.first})
      + field('Last Name', textInput('nc_last','Meyer', NC.last), {req:true, err:e.last})
      + field('Phone', textInput('nc_phone','082 555 0134', NC.phone, 'inputmode="tel"'), {req:true, err:e.phone})
      + field('Email', textInput('nc_email','john.meyer@gmail.com', NC.email, 'type="email"'))
      + field('ID Number', textInput('nc_idnum','8203155012083', NC.idnum))
      + field('Contact Type', pickerBtn('ncType', NC.type, '— None —'))
      + field('Notes', '<textarea class="inp" id="nc_notes" rows="3" placeholder="Anything worth remembering…">'+esc(NC.notes)+'</textarea>')
      + '<div style="height:6px"></div>'
      + '<button class="btn" data-act="createContact" data-tour="createContact">Create Contact</button>'
      + '<div style="height:24px"></div></div>';
  }
};
function readNC(){
  ['first','last','phone','email','idnum','notes'].forEach(k => {
    const el = document.getElementById('nc_'+k); if(el) NC[k] = el.value;
  });
}
ACTS.ncType = () => {
  readNC();
  sheet({title:'Contact Type', body:()=> '<div class="stack" style="padding-bottom:10px">'
    + CONTACT_TYPES.map(t=>'<button class="rowbtn" data-act="ncSetType:'+t+'"><span class="t-body grow">'+esc(t)+'</span>'
      + ((NC.type===t)||(!NC.type&&t==='— None —')?'<span style="color:var(--accent)">'+icon('check',18)+'</span>':'')+'</button>').join('')+'</div>'});
};
ACTS.ncSetType = t => { NC.type = t==='— None —' ? '' : t; closeSheet(); };
ACTS.createContact = () => {
  readNC();
  NC.errs = {};
  if(!NC.first) NC.errs.first = 'First name is required.';
  if(!NC.last) NC.errs.last = 'Last name is required.';
  if(!NC.phone) NC.errs.phone = 'Phone is required.';
  if(Object.keys(NC.errs).length){ render(); return; }

  // Duplicate detection: phone or ID already on file.
  const dupe = DB.contacts.find(c =>
    (c.phone && c.phone.replace(/\D/g,'') === NC.phone.replace(/\D/g,'')) ||
    (c.idnum && NC.idnum && c.idnum === NC.idnum));
  if(dupe){
    dialog({title:'This contact already exists', icon:'alert-circle', iconColour:'var(--warning)',
      body:'A contact with this phone or ID is already on file.',
      cancel:'Close', confirm:'Open contact', confirmAct:'openDupe:'+dupe.id});
    return;
  }
  const c = {id:uid(), first:NC.first, last:NC.last, phone:NC.phone, email:NC.email,
             idnum:NC.idnum, type:NC.type, wa:0, waLast:null, notes:NC.notes,
             consent:{marketing:false, whatsapp:false, data:false}};
  DB.contacts.unshift(c);
  const role = NC.role;
  // After a successful create the app IMMEDIATELY opens the new contact's detail screen.
  go('contact', {id:c.id});
  snack('Contact created.','green','check');
  if(role) setTimeout(()=>{ startWizard(null, fullName(c)); snack('Wizard opened with '+fullName(c)+' pre-linked as '+role+'.'); }, 900);
};
ACTS.openDupe = id => { closeDialog(); go('contact', {id:+id}); };

/* ---- Contact detail ----------------------------------------------------- */
const curContact = () => DB.contacts.find(c=>c.id==S.params.id) || DB.contacts[0];
SCREENS.contact = {
  nav:false,
  appbar: () => subAppBar(fullName(curContact()),
    '<button class="iconbtn" data-act="noop">'+icon('pencil',20)+'</button>'),
  render(){
    const c = curContact();
    const ms = DB.matches.filter(m=>m.contactId===c.id);
    const props = DB.properties.filter(p => p.sellers.some(s=>s.name===fullName(c)));
    const row = (i,l,v) => '<div class="row" style="padding:8px 0;gap:10px">'
      + '<span style="color:var(--text-tertiary)">'+icon(i,17)+'</span>'
      + '<span class="t-sub grow" style="color:var(--text-primary);font-weight:600">'+esc(v||'—')+'</span></div>';
    return '<div class="pad">'
      + '<div class="card" style="margin-bottom:12px">'
        + '<div class="row" style="gap:12px;margin-bottom:12px">'
          + '<span class="avatar circle s44">'+initials(c)+'</span>'
          + '<span class="grow"><span class="h-card" style="display:block">'+esc(fullName(c))+'</span>'
          + (c.type?'<span class="chip accent" style="margin-top:5px">'+esc(c.type)+'</span>':'')+'</span>'
        + '</div>'
        + row('phone','Phone',c.phone) + row('mail','Email',c.email) + row('id','ID Number',c.idnum)
        + (c.wa > 0
          ? '<div class="row" style="gap:8px;margin-top:8px">'
            + '<span class="chip" style="background:rgba(34,197,94,.14);color:var(--success)">'+icon('brand-whatsapp',12)+'WhatsApp · '+c.wa+'</span>'
            + '<span class="t-sub" style="font-size:11.5px">last '+esc(c.waLast)+'</span></div>'
          : '')
      + '</div>'
      + '<button class="btn green" data-act="whatsapp:'+c.id+'" data-tour="waBtn" style="margin-bottom:10px">'
        + icon('brand-whatsapp',19)+'WhatsApp</button>'
      + '<div class="row" style="gap:10px;margin-bottom:12px">'
        + '<button class="btn2 sm" data-act="addMatch:'+c.id+'">'+icon('plus',16)+'Match</button>'
        + '<button class="btn2 sm" data-act="addListing:'+c.id+'">'+icon('plus',16)+'Listing</button>'
      + '</div>'
      + '<button class="rowbtn" data-act="go:compliance" style="margin-bottom:12px">'
        + ibox('shield-check','var(--info)',40,20)
        + '<span class="grow"><span class="t-body" style="display:block">Compliance</span>'
        + '<span class="t-sub">Consent · Documents · FICA</span></span>'+icon('chevron-right',17)+'</button>'
      + '<div class="sec-head"><span class="h-sec">Matches</span></div>'
      + (ms.length
        ? '<div class="stack">'+ms.map(m =>
            '<button class="rowbtn" data-act="openMatch:'+m.id+'" style="align-items:flex-start">'
            + '<span class="grow"><span class="row" style="gap:7px;margin-bottom:5px">'
              + statusChip(m.status, statusColour(m.status), true)+'</span>'
            + '<span class="t-sub" style="font-size:12px">'+esc(m.listing)+' · '+esc(m.suburbs.join(', '))
              + ' · '+money(m.min)+' – '+money(m.max)+'</span></span>'
            + icon('chevron-right',17)+'</button>').join('')+'</div>'
        : empty('heart-handshake','No matches yet','Tap + Match to capture buyer or tenant criteria.'))
      + '<div class="sec-head"><span class="h-sec">Linked Properties</span></div>'
      + (props.length
        ? '<div class="stack">'+props.map(propCard).join('')+'</div>'
        : empty('building-skyscraper','No linked listings','Tap + Listing to create a property tied to this contact.'))
      + '<div style="height:24px"></div></div>';
  }
};
ACTS.openContact = id => go('contact', {id:+id});
/* Every WhatsApp tap is logged server-side and increments the counter. */
ACTS.whatsapp = id => {
  const c = DB.contacts.find(x=>x.id==id);
  if(!c.phone){ snack('No phone on contact — add a phone number first.','red','alert-circle'); return; }
  c.wa++; c.waLast = '13 Jul 2026';
  render();
  snack('Opening… Logged WhatsApp send. whatsapp_count = '+c.wa,'green','brand-whatsapp');
};
/* "+ Listing" first asks for the contact's ROLE, then launches the wizard pre-linked. */
ACTS.addListing = id => {
  const c = DB.contacts.find(x=>x.id==id);
  sheet({title:'Pick contact role', body:()=>
    '<div class="stack" style="padding-bottom:10px">'
    + ['Seller','Landlord','Buyer','Tenant'].map(r =>
      '<button class="rowbtn" data-act="roleThenWizard:'+id+'|'+r+'">'
      + ibox(r==='Seller'||r==='Landlord'?'building-skyscraper':'users','var(--accent)',36,18)
      + '<span class="t-body grow">'+r+'</span>'+icon('chevron-right',17)+'</button>').join('')+'</div>'});
};
ACTS.roleThenWizard = a => {
  const [id, role] = a.split('|');
  const c = DB.contacts.find(x=>x.id==id);
  closeSheet();
  startWizard(null, fullName(c));
  snack('New property — '+fullName(c)+' pre-linked as '+role+'.','','link');
};

/* ---- Contact Compliance: Consent | Documents | FICA --------------------- */
let compTab = 'consent';
const CONSENTS = [
  {k:'marketing', l:'Marketing communication'},
  {k:'whatsapp', l:'WhatsApp contact'},
  {k:'data', l:'Data processing (POPIA)'},
];
let consentHistory = [{l:'Marketing communication', a:'Given', how:'Electronic', d:'2 Jul 2026'}];

SCREENS.compliance = {
  nav:false,
  appbar: () => subAppBar('Compliance'),
  render(){
    return '<div class="pad">'
      + segmented([{k:'consent',l:'Consent'},{k:'docs',l:'Documents'},{k:'fica',l:'FICA'}], compTab, 'compTab')
      + '<div style="height:16px"></div>'
      + (compTab==='consent' ? consentTab() : compTab==='docs' ? docsTab() : ficaTab())
      + '<div style="height:24px"></div></div>';
  },
  fab: () => compTab==='docs' ? '<button class="fab" data-act="uploadDoc" style="width:auto;padding:0 18px;gap:7px;display:flex;align-items:center">'
    + icon('upload',20)+'<span style="font-size:14px;font-weight:700">Upload</span></button>' : ''
};
ACTS.compTab = t => { compTab=t; render(); };
function consentTab(){
  const c = curContact();
  return '<div class="card" style="margin-bottom:12px">'
    + CONSENTS.map(x => {
        const on = c.consent[x.k];
        return '<div style="border-top:1px solid var(--border);padding:4px 0">'
          + switchRow(x.l, on ? 'Given 2 Jul 2026 · Electronic' : 'Not given', on, 'consentToggle:'+x.k)+'</div>';
      }).join('')
    + '</div>'
    + '<button class="card row" data-act="acc:history" style="width:100%;text-align:left;margin-bottom:12px">'
      + '<span class="h-row grow">History</span>'
      + icon(openAcc==='history'?'chevron-up':'chevron-down',17)+'</button>'
    + (consentHistory.length
      ? '<div class="stack">'+consentHistory.map(h =>
        '<div class="card row" style="padding:12px 14px;gap:10px">'
        + ibox(h.a==='Given'?'circle-check':'circle-x', h.a==='Given'?'var(--success)':'var(--danger)',32,16)
        + '<span class="grow"><span class="t-body" style="display:block">'+esc(h.l)+' — '+esc(h.a)+'</span>'
        + '<span class="t-sub" style="font-size:11.5px">'+esc(h.how)+' · '+esc(h.d)+'</span></span></div>').join('')+'</div>'
      : empty('note','No history','Consent changes will be logged here.'));
}
ACTS.consentToggle = k => {
  const c = curContact();
  const label = CONSENTS.find(x=>x.k===k).l;
  if(c.consent[k]){
    dialog({title:'Revoke "'+label+'"?', icon:'circle-x',
      body:'<div style="margin-bottom:12px">This is logged against the contact.</div>'
        + '<textarea class="inp" id="revokeReason" rows="3" placeholder="Reason (optional)"></textarea>',
      cancel:'Cancel', confirm:'Revoke', destructive:true, confirmAct:'doRevoke:'+k});
  } else {
    let how = 'Electronic';
    sheet({title:'How was consent given?', body:()=>
      '<div class="stack" style="padding-bottom:10px">'
      + ['Electronic','Verbal','Written','Signed document'].map(o =>
        '<button class="rowbtn" data-act="consentHow:'+k+'|'+o+'"><span class="t-body grow">'+o+'</span>'
        + icon('circle',18)+'</button>').join('')+'</div>',
      foot:()=>'<button class="btn" data-act="consentHow:'+k+'|Electronic">Confirm consent</button>'});
  }
};
ACTS.consentHow = a => {
  const [k, how] = a.split('|');
  const c = curContact();
  c.consent[k] = true;
  consentHistory.unshift({l:CONSENTS.find(x=>x.k===k).l, a:'Given', how, d:'13 Jul 2026'});
  closeSheet(); snack('Consent recorded ('+how+').','green','shield-check');
};
ACTS.doRevoke = k => {
  const c = curContact();
  c.consent[k] = false;
  consentHistory.unshift({l:CONSENTS.find(x=>x.k===k).l, a:'Revoked', how:'Agent', d:'13 Jul 2026'});
  closeDialog(); snack('Consent revoked.','red','circle-x');
};
function docsTab(){
  if(!DB.documents.length) return empty('file-text','No documents','Tap Upload to add the first document.');
  const groups = {};
  DB.documents.forEach(d => (groups[d.prop||'Unlinked'] = groups[d.prop||'Unlinked']||[]).push(d));
  return Object.keys(groups).map(g =>
    '<div style="margin-bottom:18px"><div class="eyebrow mute" style="margin-bottom:9px">'+esc(g.toUpperCase())+'</div>'
    + '<div class="stack">'+groups[g].map(d =>
      '<div class="card" style="padding:13px 14px">'
      + '<div class="row" style="gap:10px;margin-bottom:9px">'
        + ibox('file-text','var(--info)',36,18)
        + '<span class="grow" style="min-width:0"><span class="t-body trunc" style="display:block">'+esc(d.name)+'</span>'
        + '<span class="t-sub" style="font-size:11.5px">'+esc(d.size)+' · by '+esc(d.by)+' · '+esc(d.date)+'</span></span>'
        + '<span class="chip accent">'+esc(d.type)+'</span></div>'
      + '<div class="row" style="gap:7px">'
        + '<button class="chip" data-act="docDownload">'+icon('download',12)+'Download</button>'
        + '<button class="chip" data-act="docRetag">'+icon('link',12)+'Re-tag</button>'
        + '<button class="chip" data-act="docDelete:'+d.id+'" style="background:rgba(239,68,68,.14);color:var(--danger)">'+icon('trash',12)+'Delete</button>'
      + '</div></div>').join('')+'</div></div>').join('');
}
ACTS.docDownload = () => snack('Downloading…','','download');
ACTS.docRetag = () => snack('Re-tag sheet opens here.','','link');
ACTS.docDelete = id => dialog({title:'Delete document?', icon:'trash',
  body:'"'+esc(DB.documents.find(d=>d.id==id).name)+'" will be permanently removed.',
  cancel:'Cancel', confirm:'Delete', destructive:true, confirmAct:'doDocDelete:'+id});
ACTS.doDocDelete = id => { DB.documents = DB.documents.filter(d=>d.id!=id); closeDialog(); snack('Document deleted.','red','trash'); };
ACTS.uploadDoc = () => sheet({title:'Add a document', body:()=>
  '<div class="stack" style="padding-bottom:10px">'
  + '<button class="rowbtn" data-act="docMeta">'+ibox('photo','var(--accent)',36,18)+'<span class="t-body grow">Choose from library</span></button>'
  + '<button class="rowbtn" data-act="docMeta">'+ibox('camera','var(--accent)',36,18)+'<span class="t-body grow">Take a photo</span></button>'
  + '<div class="hint" style="text-align:center;margin-top:6px">Maximum file size 20 MB.</div></div>'});
ACTS.docMeta = () => sheet({title:'Upload Mandate_Scan.pdf', body:()=>
  field('Document type', pickerBtn('noop','Mandate','Select type'))
  + field('Link to property', pickerBtn('noop','12 Beach Road, Uvongo','Select property')),
  foot:()=>'<button class="btn" data-act="docUploaded">Upload</button>'});
ACTS.docUploaded = () => {
  DB.documents.unshift({id:uid(), name:'Mandate_Scan.pdf', type:'Mandate',
    prop:'12 Beach Road, Uvongo', size:'820 KB', by:'Andre Roets', date:'13 Jul 2026'});
  closeSheet(); snack('Document uploaded.','green','check');
};
function ficaTab(){
  const c = curContact();
  const complete = c.consent.data && c.wa >= 0 && c.idnum;
  const badge = complete ? {l:'FICA complete', c:'var(--success)', i:'circle-check'}
                         : {l:'FICA outstanding', c:'var(--danger)', i:'alert-circle'};
  return '<div class="card" style="margin-bottom:12px;border-left:2px solid '+badge.c+'">'
    + '<div class="row" style="gap:10px">'+ibox(badge.i, badge.c, 40, 20)
    + '<span class="grow"><span class="h-row" style="display:block;color:'+badge.c+'">'+badge.l+'</span>'
    + '<span class="t-sub">'+(complete?'Verified 2 Jul 2026 · expires 2 Jul 2027':'No valid FICA submission on file.')+'</span></span></div></div>'
    + (complete
      ? '<div class="eyebrow mute" style="margin:18px 0 9px">SUBMISSIONS</div>'
        + '<div class="card">'
        + [['Risk','Low'],['Verified by','Compliance desk'],['Verified','2 Jul 2026'],['Expires','2 Jul 2027'],['PDF','available']]
          .map(([k,v]) => '<div class="row" style="padding:7px 0;border-top:1px solid var(--border)">'
            + '<span class="t-sub grow">'+k+'</span><span class="t-body">'+v+'</span></div>').join('')
        + '</div>'
        + '<div class="eyebrow mute" style="margin:18px 0 9px">LEGACY DOCUMENTS</div>'
        + '<div class="card"><div class="t-sub">2 documents migrated from the old system.</div></div>'
      : empty('shield-check','No submissions','No FICA submissions on file for this contact.'))
    + '<div class="hint" style="margin-top:14px;text-align:center;line-height:1.6">FICA is read-only in the mobile app.<br>Submissions are captured by the compliance desk.</div>';
}
/* ============================================================================
   18. SCREEN — Core Matches (a DETERMINISTIC scoring engine, not AI)
   ========================================================================== */
const REACTIONS = {
  interested:{l:'Interested', c:'var(--success)', i:'thumb-up'},
  saved:{l:'Saved', c:'var(--warning)', i:'bookmark'},
  no:{l:'Not for me', c:'var(--danger)', i:'thumb-down'},
};
const tierOf = s => s>=80 ? {l:'Strong matches', c:'var(--success)'}
                  : s>=65 ? {l:'Good matches', c:'var(--info)'}
                          : {l:'Fair matches', c:'var(--warning)'};
SCREENS.matches = {
  nav:false,
  appbar: () => subAppBar('Core Matches'),
  render(){
    return '<div class="pad">'
      + (DB.matches.length
        ? '<div class="stack">'+DB.matches.map(m =>
            '<button class="rowbtn" data-act="openMatch:'+m.id+'" style="align-items:flex-start;flex-direction:column;gap:8px">'
            + '<span class="row" style="width:100%;gap:8px">'
              + ibox('heart-handshake','var(--accent)',36,18)
              + '<span class="grow" style="text-align:left"><span class="h-row" style="display:block">'+esc(m.contact)+'</span>'
              + '<span class="t-sub">'+esc(m.name)+'</span></span>'
              + statusChip(m.status, statusColour(m.status), true)+'</span>'
            + '<span class="t-sub" style="font-size:12px">'+esc(m.listing)+' · '+esc(m.suburbs.join(', '))
              + ' · '+money(m.min)+' – '+money(m.max)+'</span>'
            + '<span class="row" style="gap:6px">'
              + '<span class="chip accent">'+m.results.length+' results</span>'
              + Object.keys(REACTIONS).map(r => {
                  const n = m.results.filter(x=>x.reaction===r).length;
                  return n ? '<span class="chip" style="background:color-mix(in srgb,'+REACTIONS[r].c+' 14%,transparent);color:'
                    + REACTIONS[r].c+'">'+icon(REACTIONS[r].i,11)+n+'</span>' : '';
                }).join('')
            + '</span></button>').join('')+'</div>'
        : empty('heart-handshake','No matches yet','Capture a buyer or tenant requirement to start scoring properties.'))
      + '<div style="height:24px"></div></div>';
  },
  fab: () => '<button class="fab" data-act="newMatch">'+icon('plus',24)+'</button>'
};
const curMatch = () => DB.matches.find(m=>m.id==S.params.id) || DB.matches[0];
ACTS.openMatch = id => go('match', {id:+id});
ACTS.addMatch = () => ACTS.newMatch();

SCREENS.match = {
  nav:false,
  appbar: () => subAppBar('Match', '<button class="iconbtn" data-act="noop">'+icon('pencil',20)+'</button>'),
  render(){
    const m = curMatch();
    const results = [...m.results].sort((a,b)=>b.score-a.score);
    const tiers = {};
    results.forEach(r => { const t = tierOf(r.score).l; (tiers[t] = tiers[t]||[]).push(r); });
    return '<div class="pad">'
      + '<div class="card" style="margin-bottom:12px">'
        + '<div class="row" style="gap:10px;margin-bottom:12px">'
          + '<span class="avatar circle s44">'+m.contact.split(' ').map(w=>w[0]).join('')+'</span>'
          + '<span class="grow"><span class="h-card" style="display:block">'+esc(m.contact)+'</span>'
          + '<span class="t-sub">'+esc(m.name)+'</span></span>'
          + statusChip(m.status, statusColour(m.status), true)+'</div>'
        + '<div class="t-sub" style="line-height:1.7">'+esc(m.listing)+' · '+esc(m.type)+'<br>'
          + esc(m.suburbs.join(', '))+'<br>'
          + '<span class="money">'+money(m.min)+' – '+money(m.max)+'</span><br>'
          + 'Min '+m.beds+' beds · '+m.baths+' baths</div>'
        + (m.features.length ? '<div class="row" style="gap:6px;margin-top:10px;flex-wrap:wrap">'
            + m.features.map(f=>'<span class="chip accent">'+esc(f)+'</span>').join('')+'</div>' : '')
      + '</div>'
      + '<div class="card" style="margin-bottom:12px;border-left:2px solid var(--info)">'
        + '<div class="row" style="gap:8px;margin-bottom:6px">'+icon('info-circle',16)
        + '<span class="t-body">Deterministic scoring</span></div>'
        + '<div class="t-sub" style="font-size:12px">Properties are scored against these criteria by a rules engine — '
        + 'price fit, location, beds, baths and must-have features. No AI, no guessing.</div></div>'
      + '<button class="btn green" data-act="waShare:'+m.id+'" data-tour="waShare" style="margin-bottom:16px">'
        + icon('brand-whatsapp',19)+'Send via WhatsApp</button>'
      + '<button class="btn2" data-act="clientPage" style="margin-bottom:16px">'+icon('external-link',17)+'Client Page</button>'
      + (results.length
        ? Object.keys(tiers).map(t => {
            const tier = tierOf(tiers[t][0].score);
            return '<div style="margin-bottom:16px">'
              + '<div class="row" style="gap:7px;margin-bottom:10px">'
                + '<span style="width:7px;height:7px;border-radius:99px;background:'+tier.c+'"></span>'
                + '<span class="h-row" style="color:'+tier.c+'">'+t+'</span>'
                + '<span class="chip">'+tiers[t].length+'</span></div>'
              + '<div class="stack">'+tiers[t].map(r => {
                  const p = DB.properties.find(x=>x.id===r.pid);
                  const rc = r.reaction ? REACTIONS[r.reaction] : null;
                  return '<button class="rowbtn" data-act="openProp:'+p.id+'" style="align-items:flex-start;gap:11px;padding:12px">'
                    + thumb(p.g, 's76')
                    + '<span class="grow" style="text-align:left">'
                      + '<span class="h-row clamp2" style="display:block;margin-bottom:5px">'+esc(addr(p))+'</span>'
                      + '<span class="money" style="display:block;font-size:13px;margin-bottom:7px">'+money(p.price)+'</span>'
                      + (rc ? '<span class="status dense" style="background:color-mix(in srgb,'+rc.c+' 14%,transparent);color:'+rc.c+'">'
                          + icon(rc.i,12)+rc.l+'</span>' : '<span class="t-sub" style="font-size:11.5px">No reaction yet</span>')
                    + '</span>'
                    + '<span class="status" style="background:color-mix(in srgb,'+tier.c+' 14%,transparent);color:'+tier.c+'">'+r.score+'%</span>'
                    + '</button>';
                }).join('')+'</div></div>';
          }).join('')
        : '<div class="card"><div class="t-sub" style="text-align:center;padding:14px 8px;line-height:1.6">'
          + 'Nothing scored 50% or higher for this match. Adjust the filters to widen the search.</div></div>')
      + '<div style="height:24px"></div></div>';
  }
};
ACTS.clientPage = () => snack('Opens the shareable client web page for this match.','','external-link');

/* The WhatsApp share — the richest comms feature in the app. */
let waMsg = '';
function templateFor(m){
  const props = [...m.results].sort((a,b)=>b.score-a.score).slice(0,3)
    .map(r => DB.properties.find(p=>p.id===r.pid));
  return 'Hi '+m.contact.split(' ')[0]+', I found '+props.length+' properties that fit what you\'re looking for:\n\n'
    + props.map((p,i) => (i+1)+'. '+addr(p)+'\n   '+money(p.price)+' · '+p.beds+' bed · '+p.baths+' bath').join('\n\n')
    + '\n\nLet me know which ones you\'d like to see.\n— Andre, Demo Agency';
}
ACTS.waShare = id => {
  const m = DB.matches.find(x=>x.id==id);
  const c = DB.contacts.find(x=>x.id===m.contactId);
  waMsg = templateFor(m);
  sheet({
    title:'Send to '+m.contact, height:'82%',
    body: () => (c.phone
        ? '<div class="row" style="gap:8px;margin-bottom:16px">'+icon('phone',16)
          + '<span class="t-body">'+esc(c.phone)+'</span></div>'
        : '<div style="border-radius:var(--r-small);padding:12px 14px;margin-bottom:16px;background:rgba(239,68,68,.12);border-left:3px solid var(--danger)">'
          + '<div class="row" style="gap:7px;color:var(--danger);margin-bottom:4px">'+icon('alert-circle',15)
          + '<span style="font-size:13px;font-weight:700">No phone on contact</span></div>'
          + '<div class="t-sub" style="font-size:12px">Add a phone number to this contact first.</div></div>')
      + '<div class="row" style="margin-bottom:8px"><span class="eyebrow mute grow">MESSAGE</span>'
        + '<button class="linkbtn" data-act="waReset:'+id+'">Reset to template</button></div>'
      + '<textarea class="inp" id="waMsg" rows="12" style="line-height:1.6;font-weight:500">'+esc(waMsg)+'</textarea>'
      + '<div class="hint">Server-rendered from your matched properties. Edit freely before sending.</div>',
    foot: () => '<button class="btn green" data-act="waSend:'+id+'"'+(c.phone?'':' disabled')+'>'
      + icon('brand-whatsapp',18)+'Send via WhatsApp</button>'
  });
};
ACTS.waReset = id => { waMsg = templateFor(DB.matches.find(x=>x.id==id)); ACTS.waShare(id); };
/* Sending LOGS it server-side first, then opens WhatsApp. */
ACTS.waSend = id => {
  const m = DB.matches.find(x=>x.id==id);
  const c = DB.contacts.find(x=>x.id===m.contactId);
  c.wa++; c.waLast = '13 Jul 2026';
  closeSheet();
  snack('Logged WhatsApp send. whatsapp_count = '+c.wa,'green','brand-whatsapp');
};
/* New Match form */
let NM = null;
ACTS.newMatch = () => {
  NM = {listing:'For Sale', name:'', category:'', type:'', min:'', max:'', beds:'', baths:'', suburbs:[], notes:''};
  go('newMatch');
};
SCREENS.newMatch = {
  nav:false,
  appbar: () => subAppBar('New Match'),
  render(){
    const allSuburbs = [...new Set(DB.properties.map(p=>p.suburb))];
    return '<div class="pad">'
      + '<div class="field"><label>Listing Type<span class="req">*</span></label><div class="row" style="gap:8px">'
      + ['For Sale','For Rental'].map(l => '<button class="tchip'+(NM.listing===l?' on':'')+'" data-act="nmListing:'+l+'" style="flex:1;justify-content:center;padding:12px">'+l+'</button>').join('')
      + '</div></div>'
      + field('Name', textInput('nm_name','e.g. Sea view family home', NM.name))
      + field('Category', pickerBtn('noop', NM.category, 'Select category'))
      + field('Property Type', pickerBtn('noop', NM.type, 'Select property type'))
      + '<div class="row" style="gap:10px;align-items:flex-start">'
        + '<span class="grow">'+field('Price Min', textInput('nm_min','1200000','', 'inputmode="numeric"'))+'</span>'
        + '<span class="grow">'+field('Price Max', textInput('nm_max','2500000','', 'inputmode="numeric"'))+'</span></div>'
      + '<div class="row" style="gap:10px;align-items:flex-start">'
        + '<span class="grow">'+field('Beds Min', textInput('nm_beds','3','','inputmode="numeric"'))+'</span>'
        + '<span class="grow">'+field('Baths Min', textInput('nm_baths','2','','inputmode="numeric"'))+'</span></div>'
      + '<div class="field"><label>Suburbs</label><div class="row" style="gap:7px;flex-wrap:wrap">'
      + allSuburbs.map(s => '<button class="tchip'+(NM.suburbs.includes(s)?' on':'')+'" data-act="nmSuburb:'+s+'">'
          + (NM.suburbs.includes(s)?icon('check',13):'')+esc(s)+'</button>').join('')
      + '</div><div class="hint">Multi-select — pick as many as you like.</div></div>'
      + field('Notes', '<textarea class="inp" rows="3" placeholder="Anything the buyer told you…"></textarea>')
      + '<button class="btn" data-act="saveMatch">Create Match</button>'
      + '<div style="height:24px"></div></div>';
  }
};
ACTS.nmListing = l => { NM.listing = l; render(); };
ACTS.nmSuburb = s => {
  const i = NM.suburbs.indexOf(s);
  if(i>=0) NM.suburbs.splice(i,1); else NM.suburbs.push(s);
  render();
};
ACTS.saveMatch = () => {
  const g = id => { const el = document.getElementById(id); return el ? el.value : ''; };
  const m = {id:uid(), contactId:1, contact:'John Meyer', name:g('nm_name')||'Untitled match',
    listing:NM.listing, status:'active', suburbs:NM.suburbs.length?NM.suburbs:['Uvongo'],
    min:+g('nm_min')||1000000, max:+g('nm_max')||3000000, beds:+g('nm_beds')||2, baths:+g('nm_baths')||1,
    type:'House', features:[], notes:'',
    results:[{pid:1, score:92, reaction:null},{pid:5, score:76, reaction:null},{pid:2, score:61, reaction:null}]};
  DB.matches.unshift(m);
  go('match', {id:m.id});
  snack('Match created — properties scored.','green','check');
};

/* ============================================================================
   19. SCREEN — Portal Leads
   ========================================================================== */
const SRC = {P24:{c:'#E11D2A', l:'P24'}, PP:{c:'#2563EB', l:'PP'}};
let leadDay = 1;
SCREENS.leads = {
  nav:false,
  appbar: () => {
    const un = DB.leads.filter(l=>l.unread).length;
    return '<header class="appbar">'
      + '<button class="iconbtn" data-act="back">'+icon('arrow-left',21)+'</button>'
      + '<span class="title">Portal Leads</span>'
      + (un ? '<span class="chip accent">'+un+' unread</span>' : '')+'</header>';
  },
  render(){
    // Sunday-anchored week strip. You cannot navigate past the current week.
    const sunday = addDays(TODAY, -TODAY.getDay());
    const days = [0,1,2,3,4,5,6].map(i => {
      const d = addDays(sunday, i);
      const off = dayOffset(d);
      const items = DB.leads.filter(l => l.day===i);
      return {i, d, off, n:items.length, unread:items.some(l=>l.unread), future: off > 0};
    });
    const list = DB.leads.filter(l => l.day===leadDay);
    return '<div class="pad">'
      + '<div class="row" style="gap:5px;margin-bottom:18px">'
      + days.map(x =>
        '<button data-act="leadDay:'+x.i+'"'+(x.future?' disabled':'')+' class="press" style="flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;padding:9px 0;border-radius:12px;position:relative;'
        + (x.i===leadDay ? 'background:var(--accent);color:#fff;box-shadow:0 8px 18px -8px var(--accent-glow);' : 'background:var(--fill-subtle);')
        + (x.future?'opacity:.35;':'')+'">'
        + '<span style="font-size:10px;font-weight:700;opacity:.75">'+DAYS[x.d.getDay()][0]+'</span>'
        + '<span style="font-size:13px;font-weight:700">'+x.d.getDate()+'</span>'
        + '<span style="font-size:10px;font-weight:600;opacity:.8">'+(x.n||'—')+'</span>'
        + (x.unread ? '<span style="position:absolute;top:5px;right:6px;width:6px;height:6px;border-radius:99px;background:'
            +(x.i===leadDay?'#fff':'var(--money)')+'"></span>' : '')
        + '</button>').join('')
      + '</div>'
      + (list.length
        ? '<div class="stack">'+list.map(l =>
            '<button class="rowbtn" data-act="openLead:'+l.id+'" style="align-items:flex-start;gap:11px">'
            + '<span class="ibox s40" style="background:color-mix(in srgb,'+SRC[l.src].c+' 14%,transparent);color:'+SRC[l.src].c+';font-size:11px;font-weight:800">'+SRC[l.src].l+'</span>'
            + '<span class="grow" style="text-align:left">'
              + '<span class="row" style="gap:6px;margin-bottom:3px">'
                + '<span class="h-row">'+esc(l.name)+'</span>'
                + (l.unread?'<span style="width:7px;height:7px;border-radius:99px;background:var(--accent)"></span>':'')+'</span>'
              + '<span class="t-sub" style="font-size:12px;display:block;margin-bottom:5px">'+esc(l.prop)+'</span>'
              + '<span class="t-sub clamp2" style="font-size:11.5px;color:var(--text-tertiary)">'+esc(l.msg)+'</span>'
            + '</span>'+icon('chevron-right',17)+'</button>').join('')+'</div>'
        : '<div class="card"><div class="t-sub" style="text-align:center;padding:14px 0">No leads on this day.</div></div>')
      + '<div class="card" style="margin-top:16px;border-left:2px solid var(--money)">'
        + '<div class="row" style="gap:8px;margin-bottom:6px;color:var(--money)">'+icon('target-arrow',16)
        + '<span class="t-body" style="color:var(--text-primary)">Speed is everything</span></div>'
        + '<div class="t-sub" style="font-size:12px">These are public enquiries from Property24 and Private Property, '
        + 'landing straight in your pocket. The first agent to call usually wins the mandate.</div></div>'
      + '<div style="height:24px"></div></div>';
  }
};
ACTS.leadDay = d => { leadDay = +d; render(); };
ACTS.openLead = id => {
  const l = DB.leads.find(x=>x.id==id);
  l.unread = false;
  sheet({title:l.name, height:'auto',
    lead:'<span class="ibox s32" style="background:color-mix(in srgb,'+SRC[l.src].c+' 14%,transparent);color:'+SRC[l.src].c+';font-size:10px;font-weight:800">'+SRC[l.src].l+'</span>',
    body:()=> '<div class="t-sub" style="margin-bottom:14px">'+esc(l.prop)+'</div>'
      + '<div class="card" style="margin-bottom:14px"><div class="t-sub" style="line-height:1.65;color:var(--text-primary)">"'+esc(l.msg)+'"</div></div>'
      + '<button class="rowbtn" data-act="call" style="margin-bottom:9px">'+icon('phone',18)
        + '<span class="t-body grow">'+esc(l.phone)+'</span>'
        + (l.wa?'<span class="chip" style="background:rgba(34,197,94,.14);color:var(--success)">'+icon('brand-whatsapp',11)+'WhatsApp</span>':'')+'</button>'
      + '<button class="rowbtn" data-act="email" style="margin-bottom:10px">'+icon('mail',18)
        + '<span class="t-body grow trunc">'+esc(l.email)+'</span></button>',
    foot:()=> '<button class="btn" data-act="leadToContact:'+l.id+'">'+icon('plus',18)+'Add as contact</button>'});
  render();
};
ACTS.leadToContact = id => {
  const l = DB.leads.find(x=>x.id==id);
  const [first, ...rest] = l.name.split(' ');
  closeSheet();
  NC = {first, last:rest.join(' '), phone:l.phone, email:l.email, idnum:'', type:'Buyer', notes:'From '+l.src+' lead: '+l.msg, errs:{}, role:null};
  go('newContact');
};

/* ============================================================================
   20. SCREEN — Notifications (grouped by pillar, fixed order)
   ========================================================================== */
const GROUP_ORDER = [['property','Properties'],['contact','Contacts'],['deal','Deals'],['agent','My activity'],[null,'Other']];
const SEV_RANK = {overdue:0, warning:1, info:2};
SCREENS.notifications = {
  nav:false,
  appbar: () => subAppBar('Notifications',
    '<button class="linkbtn" data-act="markAllRead" style="margin-right:6px">Mark all read</button>'
    + '<button class="iconbtn" data-act="go:settings">'+icon('settings',20)+'</button>'),
  render(){
    if(!DB.notifs.length) return '<div class="pad">'
      + empty('circle-check',"You're all caught up",'Nothing new to look at right now.')+'</div>';
    return '<div class="pad">'
      + GROUP_ORDER.map(([key, label]) => {
          const items = DB.notifs.filter(n => n.pillar === key)
            .sort((a,b) => SEV_RANK[a.sev]-SEV_RANK[b.sev]);
          if(!items.length) return '';
          return '<div style="margin-bottom:20px">'
            + '<div class="eyebrow mute" style="margin-bottom:10px">'+label.toUpperCase()+'</div>'
            + '<div class="stack">'+items.map(n =>
              '<button class="rowbtn" data-act="readNotif:'+n.id+'" style="align-items:flex-start;gap:11px;position:relative;'
              + (n.unread?'border:1px solid color-mix(in srgb,'+SEV[n.sev]+' 35%,transparent);':'')+'">'
              + '<span style="width:3px;height:40px;border-radius:99px;background:'+SEV[n.sev]+';box-shadow:0 0 10px '+SEV[n.sev]+';flex:none"></span>'
              + '<span class="grow" style="text-align:left">'
                + '<span style="font-size:13.5px;font-weight:'+(n.unread?'700':'600')+';display:block;margin-bottom:3px;line-height:1.35">'+esc(n.title)+'</span>'
                + '<span class="t-sub clamp2" style="font-size:12px">'+esc(n.body)+'</span>'
                + '<span class="row" style="gap:6px;margin-top:6px">'+pillarChip(n.pillar)
                  + '<span style="font-size:11px;color:var(--text-tertiary)">'+esc(n.time)+'</span></span>'
              + '</span>'
              + (n.unread ? '<span style="width:8px;height:8px;border-radius:99px;background:'+SEV[n.sev]+';flex:none;margin-top:4px"></span>' : '')
              + '</button>').join('')+'</div></div>';
        }).join('')
      + '<div class="card" style="border-left:2px solid var(--accent)">'
        + '<div class="t-sub" style="font-size:12px;line-height:1.6">'
        + '<b style="color:var(--text-primary)">No "9+" badge. No pop-up on launch.</b><br>'
        + 'The old version interrupted agents the moment they opened the app, and they hated it. '
        + 'Actionable things become rows you can act on; pure noise gets no badge at all.</div></div>'
      + '<div style="height:24px"></div></div>';
  }
};
ACTS.readNotif = id => { const n = DB.notifs.find(x=>x.id==id); n.unread = false; render(); };

/* ============================================================================
   21. SCREENS — Me, Settings, QR
   ========================================================================== */
SCREENS.me = {
  appbar: () => mainAppBar(unreadCount()>0),
  render(){
    const u = S.side==='agent' ? ME : {name:CLIENT.name, email:CLIENT.email, initials:CLIENT.initials, role:'Client'};
    return '<div class="pad" style="text-align:center;padding-top:12px">'
      + '<div style="display:flex;justify-content:center;margin-bottom:16px"><span class="avatar s96">'+u.initials+'</span></div>'
      + '<div style="font-size:24px;font-weight:800;letter-spacing:-.5px">'+esc(u.name)+'</div>'
      + '<div class="t-sub" style="margin:4px 0 10px">'+esc(u.email)+'</div>'
      + '<span class="chip accent">'+esc(u.role)+'</span>'
      + '<div class="card" style="margin-top:20px;text-align:left">'
        + '<div class="eyebrow mute" style="margin-bottom:12px">ACCOUNT</div>'
        + [['Name',u.name],['Email',u.email],['Role',u.role]].map(([k,v]) =>
          '<div class="row" style="padding:9px 0;border-top:1px solid var(--border)">'
          + '<span class="t-sub grow">'+k+'</span><span class="t-body trunc" style="max-width:190px">'+esc(v)+'</span></div>').join('')
      + '</div>'
      + (S.side==='agent'
        ? '<button class="rowbtn" data-act="go:qr" style="margin-top:12px">'+ibox('qrcode','var(--accent)',36,18)
          + '<span class="t-body grow" style="text-align:left">My QR Code</span>'+icon('chevron-right',17)+'</button>'
        : '')
      + '<button class="rowbtn" data-act="go:settings" style="margin-top:10px">'+ibox('settings','var(--accent)',36,18)
        + '<span class="t-body grow" style="text-align:left">Settings</span>'+icon('chevron-right',17)+'</button>'
      + '<button class="btn2 red" data-act="signout" style="margin-top:16px">'+icon('logout',18)+'Sign out</button>'
      + '<div style="height:24px"></div></div>';
  }
};
let settings = {quiet:false, reminder:'15 min', biometric:true};
SCREENS.settings = {
  nav:false,
  appbar: () => subAppBar('Settings'),
  render(){
    const sec = (t, inner) => '<div class="eyebrow mute" style="margin:18px 0 9px">'+t+'</div>'
      + '<div class="card">'+inner+'</div>';
    return '<div class="pad">'
      + sec('APPEARANCE', switchRow('Dark mode','Switch the whole app to the dark palette', S.theme==='dark','theme'))
      + sec('NOTIFICATIONS',
          switchRow('Quiet hours','Mute between 20:00 and 07:00', settings.quiet, 'toggleQuiet')
          + '<div style="border-top:1px solid var(--border);margin-top:4px;padding-top:4px"></div>'
          + '<button class="row" data-act="reminderPick" style="width:100%;padding:12px 0;text-align:left">'
          + '<span class="grow"><span class="t-body" style="display:block">Event reminder</span>'
          + '<span class="t-sub">How long before an event you get pinged</span></span>'
          + '<span class="chip accent">'+settings.reminder+'</span></button>')
      + sec('SECURITY', switchRow('Biometric sign-in','Use Face ID or a fingerprint', settings.biometric, 'toggleBio'))
      + sec('ABOUT', '<div class="row" style="padding:6px 0"><span class="t-sub grow">Version</span>'
          + '<span class="t-body">1.0.0</span></div>')
      + '<div style="height:24px"></div></div>';
  }
};
ACTS.toggleQuiet = () => { settings.quiet = !settings.quiet; render(); };
ACTS.toggleBio = () => { settings.biometric = !settings.biometric; render(); };
ACTS.reminderPick = () => sheet({title:'Event reminder', body:()=>
  '<div class="stack" style="padding-bottom:10px">'
  + ['5 min','15 min','30 min','1 hour','1 day'].map(r =>
    '<button class="rowbtn" data-act="setReminder:'+r+'"><span class="t-body grow">'+r+' before</span>'
    + (settings.reminder===r?'<span style="color:var(--accent)">'+icon('check',18)+'</span>':'')+'</button>').join('')+'</div>'});
ACTS.setReminder = r => { settings.reminder = r; closeSheet(); };

/* My QR Code — the growth loop of the entire product. */
function qrSvg(size){
  // A plausible QR grid, drawn deterministically (no external image).
  const N = 25, cell = size/N;
  let rects = '';
  const finder = (ox,oy) => {
    let s = '';
    for(let y=0;y<7;y++) for(let x=0;x<7;x++){
      const edge = x===0||x===6||y===0||y===6;
      const core = x>=2&&x<=4&&y>=2&&y<=4;
      if(edge||core) s += '<rect x="'+((ox+x)*cell)+'" y="'+((oy+y)*cell)+'" width="'+cell+'" height="'+cell+'" fill="#0B1426"/>';
    }
    return s;
  };
  let seed = 7;
  const rnd = () => (seed = (seed*1103515245 + 12345) & 0x7fffffff) / 0x7fffffff;
  for(let y=0;y<N;y++) for(let x=0;x<N;x++){
    const inFinder = (x<8&&y<8)||(x>N-9&&y<8)||(x<8&&y>N-9);
    if(inFinder) continue;
    if(rnd() > 0.52) rects += '<rect x="'+(x*cell)+'" y="'+(y*cell)+'" width="'+cell+'" height="'+cell+'" fill="#0B1426"/>';
  }
  return '<svg width="'+size+'" height="'+size+'" viewBox="0 0 '+size+' '+size+'">'
    + '<rect width="'+size+'" height="'+size+'" fill="#fff"/>'
    + rects + finder(0,0) + finder(N-7,0) + finder(0,N-7)
    + '<rect x="'+(size/2-22)+'" y="'+(size/2-22)+'" width="44" height="44" rx="12" fill="#fff"/>'
    + '<rect x="'+(size/2-17)+'" y="'+(size/2-17)+'" width="34" height="34" rx="10" fill="var(--accent)"/>'
    + '<text x="'+(size/2)+'" y="'+(size/2+7)+'" font-size="20" font-weight="800" fill="#fff" text-anchor="middle" font-family="sans-serif">C</text>'
    + '</svg>';
}
SCREENS.qr = {
  nav:false,
  appbar: () => subAppBar('My QR Code'),
  render(){
    return '<div class="pad" style="text-align:center">'
      + '<div style="display:flex;justify-content:center;margin:8px 0 20px">'
        + '<div style="background:#fff;padding:18px;border-radius:var(--r-large);box-shadow:0 20px 50px -16px var(--accent-glow)">'
        + qrSvg(244)+'</div></div>'
      + '<div class="h-card" style="margin-bottom:8px">Your Client QR</div>'
      + '<div class="t-sub" style="max-width:290px;margin:0 auto 18px;line-height:1.6">'
        + 'Hand this to prospects. When they scan it in the CoreX app, they sign up directly as your client.</div>'
      + '<div class="card" style="margin-bottom:16px"><div class="t-body">'+ME.name+'</div>'
        + '<div class="t-sub">'+ME.agency+'</div></div>'
      + '<div class="row" style="gap:10px">'
        + '<button class="btn2 sm" data-act="qrShare">'+icon('share',17)+'Share</button>'
        + '<button class="btn2 sm" data-act="qrSave">'+icon('download',17)+'Save Image</button></div>'
      + '<div style="height:24px"></div></div>';
  }
};
ACTS.qrShare = () => snack('Share sheet opens — WhatsApp, email, AirDrop…','','share');
ACTS.qrSave = () => snack('QR saved to your photos.','green','download');
/* ============================================================================
   22. SCREEN — Ellie (push-to-talk). Voice-to-ACTION, not a chatbot.
   ========================================================================== */
let ellie = {state:'idle', transcript:null, lastEventId:null};
const ELLIE_STATUS = {idle:'Hold the mic and tell Ellie what to do', recording:'Listening…', sending:'Thinking…'};
const ELLIE_CAPTION = {idle:'Hold to talk', recording:'Release to send', sending:'Sending to Ellie…'};

SCREENS.ellie = {
  appbar: () => '<header class="appbar">'
    + '<button class="iconbtn" data-act="drawer">'+icon('menu-2',22)+'</button>'
    + '<span class="grow"></span></header>',
  render(){
    const rec = ellie.state==='recording';
    return '<div class="pad" style="flex:1;display:flex;flex-direction:column">'
      + '<div class="row" style="gap:9px;margin-bottom:6px">'
        + '<span class="h-screen">Ellie</span>'+aiBadge()+'</div>'
      + '<div style="font-size:15px;color:var(--text-secondary);margin-bottom:6px">'+ELLIE_STATUS[ellie.state]+'</div>'
      + '<div style="font-size:12px;font-style:italic;color:var(--text-tertiary);line-height:1.5">'
        + 'Try: "Book a viewing with John at 12 Beach Road tomorrow at 11am"</div>'
      + '<div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:18px;min-height:280px">'
        + '<button id="mic" style="width:140px;height:140px;border-radius:99px;display:grid;place-items:center;color:#fff;'
          + 'background:'+(rec ? 'linear-gradient(160deg,#E53935,#B71C1C)' : 'linear-gradient(160deg,var(--accent-lite),var(--accent-dark))')+';'
          + 'box-shadow:0 20px 50px -14px '+(rec?'rgba(229,57,53,.5)':'var(--accent-glow)')+';'
          + (rec?'animation:pulse .9s ease-in-out infinite;':'')+'touch-action:none;user-select:none" data-tour="mic">'
          + icon(ellie.state==='sending'?'robot':'microphone', 52, 1.8)
        + '</button>'
        + '<style>@keyframes pulse{0%,100%{transform:scale(1);box-shadow:0 20px 50px -14px rgba(229,57,53,.5)}50%{transform:scale(1.1);box-shadow:0 20px 70px -10px rgba(229,57,53,.75)}}</style>'
        + '<div style="font-size:13px;font-weight:600;color:var(--text-secondary)">'+ELLIE_CAPTION[ellie.state]+'</div>'
      + '</div>'
      + (ellie.transcript
        ? '<div class="card accent" style="margin-bottom:8px">'
          + '<div class="eyebrow" style="margin-bottom:7px">LAST TRANSCRIPT</div>'
          + '<div style="font-size:14px;font-style:italic;line-height:1.55">"'+esc(ellie.transcript)+'"</div></div>'
        : '')
      + '<div style="height:8px"></div></div>';
  },
  after(){
    const mic = document.getElementById('mic');
    if(!mic) return;
    let t0 = 0;
    const start = e => {
      e.preventDefault();
      if(ellie.state!=='idle') return;
      t0 = performance.now();
      ellie.state = 'recording';
      render();
    };
    const stop = e => {
      if(ellie.state!=='recording') return;
      const held = performance.now() - t0;
      // Released too quickly (< 0.4s) → coach, don't send.
      if(held < 400){
        ellie.state = 'idle';
        render();
        snack('Hold to talk — press and hold the mic','amber','microphone');
        return;
      }
      ellie.state = 'sending';
      render();
      setTimeout(() => {
        ellie.state = 'idle';
        // A very short hold (< 1s) simulates "nothing intelligible".
        if(held < 1000){
          ellie.transcript = null;
          render();
          snack('Didn\'t catch that — hold the mic and speak after the tone','amber','microphone');
          ellieSheet(null);
          return;
        }
        ellie.transcript = 'Book a viewing with John at 12 Beach Road tomorrow at 11am';
        render();
        ellieSheet(ellie.transcript);
      }, 1100);
    };
    mic.addEventListener('pointerdown', start);
    mic.addEventListener('pointerup', stop);
    mic.addEventListener('pointerleave', stop);
    mic.addEventListener('pointercancel', stop);
  }
};
function ellieSheet(transcript){
  if(!transcript){
    sheet({title:'Ellie', lead:aiBadge(), height:'auto',
      body:()=> '<div class="card" style="margin-bottom:8px"><div class="t-body">I didn\'t catch that.</div>'
        + '<div class="t-sub" style="margin-top:5px">Hold the mic, wait for the tone, then speak.</div></div>',
      foot:()=>'<button class="btn" data-act="closeSheet">Try again</button>'});
    return;
  }
  // She transcribes, extracts an intent, and CREATES a calendar event — undoable in one tap.
  const ev = {id:uid(), t:'11:00', end:'12:00', title:'Viewing with John Meyer',
              loc:'12 Beach Road, Uvongo', colour:'#F97316', pillar:'property',
              cls:'Viewing', att:2, day:1, desc:'Created by Ellie from a voice command.'};
  DB.events.push(ev);
  ellie.lastEventId = ev.id;
  sheet({
    title:'Ellie', lead:aiBadge(), height:'auto',
    body: () => '<div style="font-size:14px;font-style:italic;color:var(--text-secondary);margin-bottom:14px">"'+esc(transcript)+'"</div>'
      + '<div class="card" style="border-left:2px solid var(--success)"><div class="row" style="gap:11px">'
        + ibox('calendar-event','var(--success)',40,20)
        + '<span class="grow"><span class="eyebrow" style="display:block;margin-bottom:3px;color:var(--success)">SCHEDULED</span>'
        + '<span class="t-body">Viewing with John Meyer — Tue 14 Jul 11:00</span></span></div></div>',
    foot: () => '<div class="row" style="gap:10px">'
      + '<button class="btn2 sm" data-act="ellieUndo">'+icon('arrow-back-up',17)+'Undo</button>'
      + '<button class="btn sm" data-act="ellieOpen">'+icon('calendar',17)+'Open event</button></div>'
  });
}
ACTS.ellieUndo = () => {
  DB.events = DB.events.filter(e => e.id !== ellie.lastEventId);
  ellie.lastEventId = null;
  closeSheet(); snack('Undone — the event was removed.','','arrow-back-up');
};
ACTS.ellieOpen = () => {
  closeSheet();
  cal.view = 'D';
  cal.focus = addDays(TODAY, 1);
  tab('calendar');
  setTimeout(()=>snack('Ellie created this event. It was real all along.','green','sparkles'), 400);
};

/* ============================================================================
   23. THE CLIENT PORTAL — a smaller, softer app behind the same login
   ========================================================================== */
const CLIENT_PROPS = () => DB.matches[0].results.map(r => ({...r, p:DB.properties.find(x=>x.id===r.pid)}));

SCREENS.chome = {
  appbar: () => '<header class="appbar">'
    + '<button class="iconbtn" data-act="drawer">'+icon('menu-2',22)+'</button>'
    + '<span style="font-size:18px;font-weight:800;letter-spacing:-.3px">CoreX <span style="background:linear-gradient(135deg,var(--accent-lite),var(--accent-dark));-webkit-background-clip:text;background-clip:text;color:transparent">OS</span></span>'
    + '<span class="grow"></span>'
    + '<button class="avatar press" data-act="tab:cme">'+CLIENT.initials+'</button></header>',
  render(){
    const a = CLIENT.agent;
    const items = CLIENT_PROPS();
    return '<div class="pad">'
      + '<div class="t-sub" style="font-size:12px;font-weight:600;color:var(--text-tertiary)">'+esc(a.agency)+'</div>'
      + '<div class="h-greet" style="margin:2px 0 18px">'+greeting()+', '+CLIENT.first+'.</div>'

      /* Your agent — an accent card */
      + '<div class="card accent" style="margin-bottom:16px">'
        + '<div class="eyebrow" style="margin-bottom:10px">YOUR AGENT</div>'
        + '<div class="row" style="gap:12px;margin-bottom:14px">'
          + '<span class="avatar circle s44">'+a.initials+'</span>'
          + '<span class="grow"><span class="h-row" style="display:block">'+esc(a.name)+'</span>'
          + '<span class="t-sub">'+esc(a.agency)+'</span></span></div>'
        + '<div class="row" style="gap:9px">'
          + '<button class="btn2 sm" data-act="call">'+icon('phone',16)+'Call</button>'
          + '<button class="btn2 sm" data-act="clientWa">'+icon('brand-whatsapp',16)+'WhatsApp</button></div>'
      + '</div>'

      /* Properties matched to you — with reactions that flow back to the agent */
      + '<div class="sec-head" style="margin-top:4px"><span class="h-sec">Matched to you</span>'
        + '<span class="chip accent">'+items.length+'</span></div>'
      + '<div class="stack">'+items.map(r => clientPropCard(r)).join('')+'</div>'

      /* My listing's stats (sellers only) */
      + (CLIENT.seller ? '<div class="sec-head"><span class="h-sec">My Listings</span>'
          + '<button class="linkbtn" data-act="go:cListings">All '+icon('arrow-right',15)+'</button></div>'
          + listingStatCard() : '')

      /* Review your agent */
      + '<div class="sec-head"><span class="h-sec">Your agent</span></div>'
      + reviewCard()
      + '<div style="height:24px"></div></div>';
  }
};
function clientPropCard(r){
  const p = r.p;
  const rc = r.reaction ? REACTIONS[r.reaction] : null;
  return '<div class="card" style="padding:12px">'
    + '<div class="row" style="gap:12px;align-items:flex-start;margin-bottom:12px">'
      + thumb(p.g)
      + '<span class="grow" style="min-width:0">'
        + '<span class="h-row clamp2" style="display:block;margin-bottom:5px">'+esc(addr(p))+'</span>'
        + '<span class="money" style="display:block;font-size:14px;margin-bottom:6px">'+money(p.price)+'</span>'
        + '<span class="row" style="gap:10px;color:var(--text-tertiary)">'
          + '<span class="row" style="gap:3px">'+icon('bed',13)+'<span style="font-size:11.5px;font-weight:600">'+p.beds+'</span></span>'
          + '<span class="row" style="gap:3px">'+icon('bath',13)+'<span style="font-size:11.5px;font-weight:600">'+p.baths+'</span></span>'
        + '</span></span>'
    + '</div>'
    + '<div class="row" style="gap:7px">'
      + Object.keys(REACTIONS).map(k => {
          const x = REACTIONS[k];
          const on = r.reaction===k;
          return '<button class="tchip" data-act="react:'+r.pid+'|'+k+'" style="flex:1;justify-content:center;'
            + (on ? 'background:color-mix(in srgb,'+x.c+' 16%,transparent);border-color:'+x.c+';color:'+x.c+';' : '')+'">'
            + icon(x.i,14)+'<span style="font-size:11px">'+x.l+'</span></button>';
        }).join('')
    + '</div></div>';
}
/* Reactions flow straight back to the agent's Core Matches screen. */
ACTS.react = a => {
  const [pid, k] = a.split('|');
  const r = DB.matches[0].results.find(x=>x.pid==pid);
  r.reaction = r.reaction===k ? null : k;
  render();
  if(r.reaction) snack('Your agent can see this in Core Matches.','green', REACTIONS[k].i);
};
ACTS.clientWa = () => snack('Opening WhatsApp with Andre…','green','brand-whatsapp');
function listingStatCard(){
  const p = DB.properties[1];
  const kpi = (l, v, delta, isMoney) =>
    '<div class="card" style="padding:12px 14px;flex:1">'
    + '<div class="eyebrow mute" style="margin-bottom:5px">'+l+'</div>'
    + '<div style="font-size:20px;font-weight:700;letter-spacing:-.3px'+(isMoney?';color:var(--money);text-shadow:0 0 10px var(--money-glow)':'')+'">'+v+'</div>'
    + (delta ? '<div style="font-size:11px;font-weight:600;color:var(--success-delta);margin-top:3px">▲ '+delta+'</div>' : '')
    + '</div>';
  return '<div class="card" style="margin-bottom:10px;padding:12px">'
    + '<div class="row" style="gap:12px;margin-bottom:14px">'
      + thumb(p.g)
      + '<span class="grow"><span class="h-row clamp2" style="display:block;margin-bottom:4px">'+esc(addr(p))+'</span>'
      + statusChip('Live','var(--success)',true)+'</span></div>'
    + '<div class="row" style="gap:8px">'
      + kpi('VIEWS','1 284','18% this week')
      + kpi('ENQUIRIES','9','3 new')
      + kpi('ASKING', 'R 1.85m', null, true)
    + '</div></div>';
}
function reviewCard(){
  if(CLIENT.reviewed){
    return '<div class="card"><div class="row" style="gap:10px">'
      + ibox('circle-check','var(--success)',40,20)
      + '<span class="grow"><span class="t-body" style="display:block">Thanks for the review</span>'
      + '<span class="t-sub">You rated Andre '+CLIENT.rating+'/5.</span></span></div></div>';
  }
  return '<div class="card">'
    + '<div class="t-body" style="margin-bottom:4px">How is Andre doing?</div>'
    + '<div class="t-sub" style="margin-bottom:12px">Your review helps the agency, and it helps other buyers.</div>'
    + '<div class="row" style="gap:7px;justify-content:center">'
    + [1,2,3,4,5].map(i => '<button data-act="rate:'+i+'" class="press" style="color:'
        + (i<=CLIENT.rating?'var(--money)':'var(--text-muted)')+'">'+icon('star',30)+'</button>').join('')
    + '</div></div>';
}
ACTS.rate = n => {
  CLIENT.rating = +n; CLIENT.reviewed = true;
  render(); snack('Review sent. Thank you!','green','star');
};
SCREENS.cListings = {
  nav:false,
  appbar: () => subAppBar('My Listings'),
  render(){
    return '<div class="pad">'
      + '<div class="eyebrow mute" style="margin-bottom:10px">MY LISTINGS</div>'
      + listingStatCard()
      + '<div class="card" style="border-left:2px solid var(--accent);margin-top:6px">'
        + '<div class="t-sub" style="font-size:12px;line-height:1.6">You only ever see <b style="color:var(--text-primary)">your own</b> listing\'s '
        + 'numbers here — never another seller\'s, and never the agency\'s internal pipeline.</div></div>'
      + '<div style="height:24px"></div></div>';
  }
};
SCREENS.cme = {
  appbar: () => '<header class="appbar">'
    + '<button class="iconbtn" data-act="drawer">'+icon('menu-2',22)+'</button>'
    + '<span class="title">Profile</span></header>',
  render(){
    const c = CLIENT;
    return '<div class="pad" style="text-align:center;padding-top:8px">'
      + '<div style="display:flex;justify-content:center;margin-bottom:14px"><span class="avatar s96">'+c.initials+'</span></div>'
      + '<div style="font-size:24px;font-weight:800;letter-spacing:-.5px">'+esc(c.name)+'</div>'
      + '<div class="t-sub" style="margin:4px 0 10px">'+esc(c.email)+'</div>'
      + '<span class="chip accent">Client</span>'

      /* POPIA privacy consent — the client manages their own */
      + '<div class="card" style="margin-top:20px;text-align:left">'
        + '<div class="row" style="gap:8px;margin-bottom:6px">'+icon('shield-check',17)
        + '<span class="h-row grow">Privacy &amp; consent</span></div>'
        + '<div class="t-sub" style="font-size:12px;margin-bottom:6px">POPIA is South Africa\'s privacy law. '
        + 'You control what your agent may do with your details, and you can withdraw at any time.</div>'
        + CONSENTS.map(x => '<div style="border-top:1px solid var(--border)">'
            + switchRow(x.l, c.consent[x.k] ? 'Given · Electronic' : 'Not given', c.consent[x.k], 'cConsent:'+x.k)+'</div>').join('')
      + '</div>'
      + '<button class="rowbtn" data-act="go:settings" style="margin-top:12px">'+ibox('settings','var(--accent)',36,18)
        + '<span class="t-body grow" style="text-align:left">Settings</span>'+icon('chevron-right',17)+'</button>'
      + '<button class="btn2 red" data-act="signout" style="margin-top:14px">'+icon('logout',18)+'Sign out</button>'
      + '<div style="height:24px"></div></div>';
  }
};
ACTS.cConsent = k => {
  CLIENT.consent[k] = !CLIENT.consent[k];
  render();
  snack(CLIENT.consent[k] ? 'Consent given — logged.' : 'Consent withdrawn — logged.',
        CLIENT.consent[k]?'green':'', 'shield-check');
};
/* ============================================================================
   24. THE TOUR — chapters, steps, and the explanation panel
   ========================================================================== */
/* A mock of the DESKTOP panel, drawn in the explanation column — because this
   screen genuinely does not exist on the phone, and showing it inside the phone
   would be a lie. Static on purpose: it is a picture of another app. */
function webAiPreview(){
  return '<div class="webmock">'
    + '<div class="webmock-bar">'
      + '<span class="dot" style="background:#ff5f57"></span>'
      + '<span class="dot" style="background:#febc2e"></span>'
      + '<span class="dot" style="background:#28c840"></span>'
      + '<span class="webmock-url">CoreX OS · web · 12 Beach Road, Uvongo</span>'
    + '</div>'
    + '<div class="webmock-body">'
      + '<div class="row" style="gap:8px;margin-bottom:4px">'
        + aiBadge()
        + '<span style="font-size:13px;font-weight:700">AI suggestions from your photos</span></div>'
      + '<div style="font-size:11.5px;color:var(--text-tertiary);margin-bottom:12px">Analysed 6 photos · click a chip to inspect or untick</div>'
      + '<div class="row" style="gap:6px;flex-wrap:wrap;margin-bottom:12px">'
      + AI_SUGGESTIONS.map(s => {
          const on = s.c >= 0.5;                     // >= 50% arrives pre-ticked
          return '<span class="tchip'+(on?' on':'')+'" style="padding:5px 8px;gap:5px;cursor:default">'
            + icon(on?'circle-check':'circle',13)
            + '<span style="font-size:11.5px;font-weight:600">'+esc(s.f)+'</span>'
            + '<span class="ai" style="padding:1px 4px;font-size:9px">'+icon('robot',9)+'AI</span>'
            + pips(s.c)
            + '<span style="opacity:.7;display:flex">'+icon('eye',13)+'</span>'
            + '</span>';
        }).join('')
      + '</div>'
      + '<div class="eyebrow mute" style="margin-bottom:7px">SPACES (ADVISORY)</div>'
      + '<div class="row" style="gap:6px;flex-wrap:wrap;margin-bottom:12px">'
        + '<span class="chip">Bedroom · 4</span><span class="chip">Pool · 1</span>'
      + '</div>'
      + '<span class="btn sm" style="width:auto;padding:0 14px;height:34px;font-size:12.5px;display:inline-flex">'
        + icon('sparkles',15)+'Apply AI features</span>'
    + '</div></div>';
}

const T = {                                          // little markup helpers
  callout:(ttl, body, kind) => '<div class="callout '+(kind||'')+'"><div class="ttl">'
    + icon(kind==='warn'?'alert-triangle':kind==='purple'?'robot':kind==='gold'?'coin':'info-circle',13)
    + esc(ttl)+'</div><p>'+body+'</p></div>',
  try:(body) => '<div class="tryit">'+icon('hand-grab',18)
    + '<span><div class="k">Try it</div><p>'+body+'</p></span></div>',
};

const TOUR = [
{ title:'Welcome',
  steps:[
    { t:'This is CoreX OS',
      run:()=>{ S.screen='splash'; S.stack=[]; render(true); },
      html: '<p>You are looking at a <strong>fully working replica</strong> of the CoreX OS mobile app. Every button in that phone is live. Nothing talks to a server — the data is fake and lives in your browser — but the screens, the copy and the behaviour are the real thing.</p>'
        + '<p>CoreX OS is a <strong>real-estate operating system</strong> used by estate agencies in South Africa. The app is white-labelled, so the name, the logo and the colours are whatever your agency supplies.</p>'
        + T.callout('The whole product in one sentence', 'Agents open CoreX to answer one question — <strong>"What do I do now?"</strong> So the app is action-first. Everything you see is something you can complete, reschedule, skip or open.')
        + '<p>Work through the chapters in order, or search for the feature you need. Everything in the phone is yours to press.</p>' },

    { t:'The four pillars',
      run:()=>{ S.screen='splash'; render(true); },
      html: '<p>Everything in CoreX connects to four things:</p>'
        + '<div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:14px">'
          + pillarChip('property')+pillarChip('contact')+pillarChip('deal')+pillarChip('agent')+'</div>'
        + '<p>Every task, event and notification must link to <strong>at least one pillar</strong>. An item that links to nothing is considered a bug — an "orphan".</p>'
        + '<p>You will see those tiny square chips everywhere. They are 9px, uppercase, and deliberately <em>not</em> pill-shaped, so they never get confused with a status.</p>'
        + T.callout('An honest rule', 'If an item genuinely has no pillar, the chip <strong>renders nothing at all</strong>. It never fabricates a destination just to look complete.') },

    { t:'Two apps, one login',
      html: '<p>There are two completely separate experiences behind a single login, and the backend decides which one you get:</p>'
        + '<ul><li><strong>The Agent app</strong> — listings, contacts, matches, calendar, tasks, AI. Five tabs.</li>'
        + '<li><strong>The Client portal</strong> — for the agent\'s buyers, sellers and tenants. A much smaller, softer app. Two tabs.</li></ul>'
        + '<p>Use the <strong>Agent / Client</strong> switch in the top bar at any time to flip sides. The status bar in the phone always tells you which one you are looking at.</p>'
        + T.try('The tour covers both. We will spend most of it on the agent side, then hand the phone to a client near the end.') },
  ]},

{ title:'Signing in',
  steps:[
    { t:'The login screen',
      run:()=>{ S.screen='login'; S.stack=[]; loginState = freshLogin(); render(true); },
      html: '<p>Email, password, and two ways in. The fields arrive <strong>pre-filled with a working agent login</strong>, so you can just press <strong>Continue to your workspace</strong> and you are in.</p>'
        + '<p>Notice the shape of this screen — it is the design system in miniature:</p>'
        + '<ul><li>The wordmark, with <strong>"OS" in an accent gradient</strong>.</li>'
        + '<li>The eyebrow: <code>YOUR REAL ESTATE OS</code> — 11px, weight 700, letter-spacing 2.4. Tiny, wide, uppercase. You will see this element on almost every screen.</li>'
        + '<li>Inputs with the label <strong>above</strong>, never beside. No border at rest; a 1.5px accent border on focus.</li>'
        + '<li>The primary button: 56px tall, a vertical gradient, and a soft accent glow underneath it. That glow is the signature look.</li></ul>' },

    { t:'The login cascade',
      run:()=>{ S.screen='login'; render(true); },
      html: '<p>This genuinely confuses people, so here it is in full. When you press <strong>Continue</strong>, the app tries, in order:</p>'
        + '<ul><li><strong>1. Log you in as an agent.</strong> Works? Straight to Agent Home.</li>'
        + '<li><strong>2. Wrong password (a 401)?</strong> It <em>stops there</em> and says "Incorrect password." It does not keep guessing.</li>'
        + '<li><strong>3. Otherwise, look the email up as a client.</strong> Not found, never activated, or active — three different outcomes.</li></ul>'
        + T.try('<strong>Replace the pre-filled email</strong> in the phone and watch the cascade play out:<br>'
          + '<code>andre@…</code> → agent. <code>john@…</code> → client.<br>'
          + '<code>new@…</code> → the "not activated yet" path, with the OTP screen.<br>'
          + 'Anything else → "We couldn\'t find that email."') },

    { t:'Two shortcuts',
      run:()=>{ S.screen='login'; render(true); },
      html: '<p>For the rest of the tour you do not need to type anything. The two buttons at the bottom of the login screen drop you straight into either side.</p>'
        + T.try('Tap <strong>Sign in as Agent</strong>, then hit Next up here to keep going.')
        + '<p>The <strong>Scan agent QR</strong> button is the other way in — a prospect scans their agent\'s QR code and signs up as that agent\'s client. We come back to that at the end; it is the growth engine of the whole product.</p>' },
  ]},

{ title:'Home',
  steps:[
    { t:'The launcher',
      run:()=>{ if(S.side!=='agent') { S.side='agent'; syncSide(); } enterApp('agent'); },
      html: '<p>This is the cockpit. Agency name, a greeting that knows the time of day, and then three things that answer <em>"what do I do now?"</em></p>'
        + '<p>Look at what is <strong>not</strong> here: no floating <strong>+</strong> button. That is deliberate. Home is for orienting, not creating.</p>'
        + T.callout('Depth without elevation', 'Material elevation is zero everywhere in this app. Every bit of depth you can see is faked with a vertical gradient, a 1px top highlight line, and coloured shadows. That is why it looks expensive.') },

    { t:'The Meet Ellie card',
      hl:'[data-tour="ellieCard"]',
      run:()=>{ enterApp('agent'); },
      html: '<p>An <strong>accent card</strong> — a 2px accent left border plus a leftward halo. That treatment is reserved for the few cards the app wants you to look at first.</p>'
        + '<p>The italic line is the day\'s quote. It rotates daily from a bank of 100 lines.</p>'
        + T.callout('A small honesty', 'Those quotes are <strong>hand-written by a human</strong>, not generated. They carry no AI badge, because nothing about them is AI. The badge is a trust signal, and it is only spent where it is earned.')
        + T.try('Tap the card. It takes you straight to <strong>Ellie</strong>, the voice assistant — which gets a chapter of its own shortly.') },

    { t:'Next appointment',
      hl:'[data-tour="nextAppt"]',
      run:()=>{ enterApp('agent'); S.nextApptState='event'; render(); },
      html: '<p>The single most-read card in the app. It has three states, and you can flip between all three right now:</p>'
        + '<div class="row" style="gap:8px;margin:12px 0;display:flex">'
          + '<button class="btn2 sm" data-tourbtn="nextState:loading">Loading</button>'
          + '<button class="btn2 sm" data-tourbtn="nextState:clear">Clear</button>'
          + '<button class="btn2 sm" data-tourbtn="nextState:event">Event</button></div>'
        + '<p>In the event state you get a 64px icon box tinted to the event\'s own colour, the eyebrow, the title, the address, and <strong>two chips</strong> — the clock time, and a <em>relative</em> one: "Now", "in 45 min", "in 2h 15m".</p>'
        + '<p>That relative chip is the whole point. Nobody wants to do arithmetic before a viewing.</p>' },

    { t:'The Workspace grid',
      hl:'[data-tour="workspace"]',
      run:()=>{ enterApp('agent'); },
      html: '<p>Four square module tiles: <strong>Properties</strong>, <strong>Contacts</strong>, <strong>Core Matches</strong>, <strong>Portal Leads</strong>.</p>'
        + '<p>Each is a 40×40 rounded icon box holding an accent icon — the recurring motif of the entire design system. You will see that same icon box at 32px in notification rows and at 64px on hero cards.</p>'
        + T.callout('The gold dot', 'See the small gold dot on <strong>Portal Leads</strong>? That means unread items. Gold, because these are enquiries from the public portals, and answering them first is how you win the mandate. It is the only place on Home that shouts.', 'gold')
        + T.try('Tap into any tile. Everything works. The bottom nav will get you back.') },
  ]},

{ title:'Today & Calendar',
  steps:[
    { t:'Today',
      run:()=>{ enterApp('agent'); tab('today'); },
      html: '<p>Two blocks: <strong>Today\'s Schedule</strong> and <strong>Unread Notifications</strong>. That is the whole screen.</p>'
        + '<p>Each event row has a 4px coloured bar, a fixed time column, the title, and — if it has them — a location and an attendee count. The colour is the event\'s own, not a status.</p>'
        + T.try('Tap any event. It opens a <strong>bottom sheet</strong> with the full detail and two actions: <em>Dismiss</em> and <em>Complete</em>.')
        + T.callout('No centred dialogs', 'Except for destructive confirmations, this app has <strong>no centred modals at all</strong>. Everything slides up from the bottom: rounded top corners, a small grab handle, a title, and a pinned action button. Learn to recognise the pattern — it is used everywhere.') },

    { t:'The notification severities',
      run:()=>{ enterApp('agent'); tab('today'); },
      html: '<p>Three severities, three colours, and they never mean anything else:</p>'
        + '<ul><li><span style="color:var(--danger);font-weight:700">Overdue</span> — red. Something has already slipped.</li>'
        + '<li><span style="color:var(--warning);font-weight:700">Warning</span> — amber. Something is about to slip.</li>'
        + '<li><span style="color:var(--info);font-weight:700">Info</span> — blue. Something happened; nothing is on fire.</li></ul>'
        + '<p>Timestamps are always relative — "just now", "5m ago", "3h ago", "2d ago". The real app polls every 60 seconds and refreshes whenever you come back to it.</p>' },

    { t:'The Calendar',
      hl:'[data-tour="calViews"]',
      run:()=>{ enterApp('agent'); cal={view:'D',focus:new Date(TODAY),selected:null}; tab('calendar'); },
      html: '<p>Four views behind that little <strong>M · W · D · A</strong> toggle, and the app opens on <strong>Day</strong> — because day is what an agent actually needs at 08:00.</p>'
        + '<ul><li><strong>M</strong>onth — a 7×6 grid, today is a filled accent circle, up to three event dots per day.</li>'
        + '<li><strong>W</strong>eek — a Monday-anchored strip of day pills.</li>'
        + '<li><strong>D</strong>ay — arrows either side of the date.</li>'
        + '<li><strong>A</strong>genda — everything ahead, grouped by date, with today prefixed <em>"Today · "</em>.</li></ul>'
        + T.try('Cycle through all four. Then tap the mail icon in the header — the <strong>amber dot</strong> means someone has invited you to something and you have not replied.') },
  ]},

{ title:'Tasks',
  steps:[
    { t:'A kanban you can actually drag',
      hl:'[data-tour="taskFab"]',
      run:()=>{ enterApp('agent'); tasksTab='active'; go('tasks'); },
      html: '<p>Three stacked columns — <strong>To Do</strong>, <strong>In Progress</strong>, <strong>Done</strong> — with a colour dot and a count each.</p>'
        + '<p>The header subtitle reads <code>4 open · 1 overdue</code>, and <strong>the whole line turns red</strong> the moment anything is overdue. Overdue cards themselves take a red border.</p>'
        + T.try('<strong>Drag a card</strong> from To Do into In Progress with your mouse. Drop it. An empty column says "Nothing here." — while you are dragging over it, it changes to <em>"Drop to move here."</em>') },

    { t:'Swipe to act',
      run:()=>{ enterApp('agent'); tasksTab='active'; go('tasks'); },
      html: '<p>Dragging is not the fast path. <strong>Swiping is.</strong></p>'
        + '<ul><li><strong>Swipe right</strong> → Complete. The background goes green.</li>'
        + '<li><strong>Swipe left</strong> → "Didn\'t happen". The background goes grey.</li></ul>'
        + T.try('Press and hold on a task card and <strong>drag it sideways</strong>. Go past about a third of the card and let go. Works with a mouse here, exactly as it does with a thumb on the phone.')
        + '<p>Long-pressing a card also opens a <em>Move "…"</em> sheet — the same action, reachable without any gesture at all. Every gesture in this app has a tappable equivalent.</p>' },

    { t:'Quick Add — one sheet, two things',
      hl:'[data-tour="taskFab"]',
      run:()=>{ enterApp('agent'); tasksTab='active'; go('tasks'); },
      html: '<p>The <strong>+</strong> button opens <strong>Quick Add</strong>, which makes either a <strong>Task</strong> or an <strong>Event</strong> from one sheet. Priority pills, a description, and a "Remind me" switch that defaults to on.</p>'
        + T.try('Tap <strong>+</strong>, switch to the <strong>Event</strong> tab, set the date to <strong>13 July 2026</strong> and the start time to <strong>09:00</strong>. Watch what appears.') },

    { t:'The conflict banner',
      run:()=>{ enterApp('agent'); tasksTab='active'; go('tasks');
                qa={mode:'Event', title:'Coffee with a seller', prio:'Normal', desc:'', remind:true, type:'Manual',
                    due:'', date:fmtISO(TODAY), start:'09:00', end:'10:00', allDay:false, err:''};
                setTimeout(openQuickAdd, 250); },
      hl:'[data-tour="conflict"]',
      html: '<p>There it is: <strong>"Overlaps 2 events"</strong>, in amber, listing what it clashes with.</p>'
        + T.callout('It warns. It never blocks.', 'The Add Event button stays enabled. You can double-book yourself, and sometimes you should — a 15-minute overlap between a call and a viewing is often fine, and the app does not know your day better than you do. <strong>It informs, then trusts your judgement.</strong>')
        + '<p>That principle runs right through CoreX. The app is opinionated about what it shows you, and humble about what it lets you do.</p>' },
  ]},

{ title:'Properties',
  steps:[
    { t:'The list',
      run:()=>{ enterApp('agent'); plist={q:'',scope:'mine',filters:{suburbs:[],min:'',max:'',listing:[],status:[]}}; go('properties'); },
      html: '<p>A 76px thumbnail, the address in bold, a stat row (bed · bath · garage), the price in <span class="money">gold</span>, and a status chip on the right: <strong>active</strong> is green, <strong>draft</strong> is amber.</p>'
        + T.callout('Money is gold, and only money.', 'Gold — <code>#E8B86D</code> — with a soft glow, is reserved for monetary values. Prices, commission, market-value KPIs. Nothing else is ever gold. It is a <em>semantic</em> colour, which is why it survives even when the agency re-brands the app.', 'gold')
        + '<p>A property belonging to another agent carries a <strong>"Co-listing"</strong> badge.</p>' },

    { t:'The rule that surprises everyone',
      run:()=>{ enterApp('agent'); go('properties'); },
      html: T.callout('Draft opens the editor. Live opens the overview.', 'Tap a <strong>draft</strong> property and you land in the <strong>editing wizard</strong>. Tap a <strong>live</strong> one and you land on the <strong>Overview</strong>. Same list, same gesture, two destinations — because a draft is unfinished work, and unfinished work wants finishing.', 'warn')
        + T.try('Tap <strong>21 Ramsgate Heights</strong> (draft, amber) — you land in the editor. Come back and tap <strong>8 Marine Drive</strong> (active, green) — you land on the overview.') },

    { t:'Search and filters',
      hl:'[data-tour="filterBtn"]',
      run:()=>{ enterApp('agent'); go('properties'); },
      html: '<p>The filter icon carries a <strong>badge with the number of active filters</strong>, so you can never be filtering without knowing it. That sounds obvious. Plenty of apps get it wrong.</p>'
        + '<p>With filters on, a line appears above the list: <code>2 of 5 match</code> and a <strong>Clear filters</strong> button.</p>'
        + T.try('Open the filter sheet, pick a suburb, hit <strong>Apply</strong>. Note it is a bottom sheet, with a <em>Clear</em> in the header and a full-width <em>Apply</em> pinned to the bottom — the standard shape.') },
  ]},

{ title:'★ Uploading a property',
  steps:[
    { t:'The 4-step wizard',
      hl:'[data-tour="wizardProgress"]',
      run:()=>{ enterApp('agent'); startWizard(null); },
      html: '<p>This is the heart of the app, and where new agents get stuck most often. Four steps, a progress bar that fills in accent, and <strong>no swiping between them</strong> — you move only with the buttons.</p>'
        + '<ol style="padding-left:18px"><li style="font-size:14px;color:var(--text-secondary);margin-bottom:6px"><strong style="color:var(--text-primary)">Address</strong> — where is it</li>'
        + '<li style="font-size:14px;color:var(--text-secondary);margin-bottom:6px"><strong style="color:var(--text-primary)">Details</strong> — what is it, what does it cost</li>'
        + '<li style="font-size:14px;color:var(--text-secondary);margin-bottom:6px"><strong style="color:var(--text-primary)">Spaces &amp; Features</strong> — what is inside it</li>'
        + '<li style="font-size:14px;color:var(--text-secondary)"><strong style="color:var(--text-primary)">Gallery</strong> — what does it look like</li></ol>' },

    { t:'The cascade: Province → City → Suburb',
      run:()=>{ enterApp('agent'); startWizard(null); },
      html: '<p>Three pickers that depend on each other. <strong>City is disabled until you choose a Province</strong> ("Select a province first"). <strong>Suburb is disabled until you choose a City.</strong></p>'
        + '<p>Each one opens a <strong>searchable bottom sheet</strong> with the search box already focused, because these lists are long.</p>'
        + T.callout('Changing a parent clears its children', 'Pick KwaZulu-Natal → Margate → Uvongo, then change the province to Western Cape. City and Suburb are <strong>wiped</strong>. They have to be — Uvongo is not in the Western Cape. Do not fight it; just re-pick.', 'warn')
        + T.try('Go: <strong>KwaZulu-Natal → Margate → Uvongo</strong>. Then change the province and watch the other two empty out.') },

    { t:'⚠️ When the property is actually created',
      hl:'[data-tour="createBtn"]',
      run:()=>{ enterApp('agent');
                startWizard(null);
                Object.assign(W,{step:2, num:'12', street:'Beach Road', province:'KwaZulu-Natal', city:'Margate', suburb:'Uvongo'});
                render(true); },
      html: T.callout('This is the single most important thing on this page', 'Steps 3 and 4 need a <strong>real property ID on the server</strong> — you cannot attach photos to something that does not exist yet. So <strong>the property is created the moment you press the button at the end of Step 2</strong>.', 'warn')
        + '<p>That is why the button says <strong>"Create &amp; Continue"</strong> the first time, and <strong>"Next: Spaces"</strong> every time after. It is not a typo, and it is not two different buttons.</p>'
        + '<p>The practical consequence: <strong>once you pass Step 2, the listing exists</strong>. If you abandon the wizard there, you will find a draft waiting for you in the list. That is a feature — your work is saved — but it does catch people out.</p>' },

    { t:'"Missing Required Fields" — and "Take me there"',
      run:()=>{ enterApp('agent'); startWizard(null);
                Object.assign(W,{step:2, num:'12', street:'Beach Road', province:'KwaZulu-Natal', city:'Margate', suburb:'Uvongo'});
                render(true);
                setTimeout(()=>ACTS.wNext2(), 350); },
      html: '<p>Press <strong>Create &amp; Continue</strong> with gaps and you get one of the app\'s few genuine centred dialogs, listing exactly what is missing.</p>'
        + '<p>Then the good bit: <strong>"Take me there"</strong> jumps to the correct step <em>and</em> scrolls to the first missing field, and pulses it.</p>'
        + T.try('Hit <strong>Take me there</strong> in the phone. Watch the field highlight. Then fill in a Title, a Property Type and a Price, and press Create &amp; Continue for real.') },

    { t:'Step 3 — Spaces',
      run:()=>{ enterApp('agent'); startWizard(null);
                Object.assign(W,{step:3, created:true, propertyId:912, num:'12', street:'Beach Road',
                  province:'KwaZulu-Natal', city:'Margate', suburb:'Uvongo', title:'Stunning 4 Bed House in Uvongo',
                  type:'House', price:'2500000',
                  spaces:[{k:'Bedroom',count:4,features:{all:['Built-in cupboards','En-suite'],per:{}}},
                          {k:'Bathroom',count:2.5,features:{all:['Shower'],per:{}}}]});
                render(true); },
      html: '<p>Add a space and it appears as a card with steppers. Tap the card body and the <strong>feature picker</strong> opens immediately.</p>'
        + T.callout('Bathrooms step by 0.5', '2.5 bathrooms is a real thing in property — two full bathrooms and a guest loo. Everything else steps by 1. Set any count to <strong>0</strong> and the space is removed.', 'gold')
        + '<p>When a space count reaches <strong>2 or more</strong>, the feature picker grows two tabs — <strong>All Units</strong> and <strong>Per Unit</strong> — so "Bedroom 1" can have an en-suite while "Bedroom 3" has a ceiling fan.</p>'
        + T.try('Tap the <strong>Bedroom</strong> card (count 4) and switch to <strong>Per Unit</strong>.') },

    { t:'Step 4 — the Gallery, and where tags come from',
      hl:'[data-tour="uploadBtn"]',
      run:()=>{ enterApp('agent'); startWizard(null);
                Object.assign(W,{step:4, created:true, propertyId:912, title:'Stunning 4 Bed House in Uvongo',
                  suburb:'Uvongo', city:'Margate', province:'KwaZulu-Natal', type:'House', price:'2500000',
                  spaces:[{k:'Bedroom',count:4,features:{all:[],per:{}}},{k:'Bathroom',count:2.5,features:{all:[],per:{}}},
                          {k:'Pool',count:1,features:{all:[],per:{}}}]});
                render(true); },
      html: T.callout('Photo tags come from your spaces', 'Add a <strong>Bedroom</strong> in Step 3 and a "Bedroom" photo tag appears in Step 4. Add a <strong>Pool</strong> and you get a Pool tag. If you added no spaces, everything lands in <em>Unsorted</em> — which is why the empty state says <em>"Add spaces first to unlock tags."</em>')
        + T.try('Tap <strong>Upload</strong>. Pick a tag, choose <strong>Gallery</strong> as the source (it adds 5 photos), and hit <strong>Upload 5 photo(s)</strong>.<br><br>One file will <strong>fail</strong> — that is on purpose. Tap the red banner to <strong>retry</strong> it, and watch it succeed. Uploads fail in the real world, usually in a car with one bar of signal, and you should know what that looks like before it happens to you.') },
  ]},

{ title:'AI features',
  steps:[
    { t:'AI photo analysis is automatic',
      hl:'[data-tour="aiNotice"]',
      run:()=>{ enterApp('agent'); startWizard(null);
                Object.assign(W,{step:4, created:true, propertyId:912, title:'Stunning 4 Bed House in Uvongo',
                  suburb:'Uvongo', city:'Margate', province:'KwaZulu-Natal', type:'House', price:'2500000',
                  spaces:[{k:'Bedroom',count:4,features:{all:[],per:{}}},{k:'Pool',count:1,features:{all:[],per:{}}}],
                  photos:Array.from({length:6},(_,i)=>({tag:i<4?'Bedroom':'Pool',g:i,name:'IMG_284'+i+'.jpg'}))});
                render(true); },
      html: T.callout('There is nothing to press', 'The moment your photos reach the server — from the <strong>Gallery</strong>, the camera, anywhere — <strong>every one of them is queued for analysis automatically</strong>. You do not start it. You do not wait for it. You do not confirm anything on the phone.', 'purple')
        + '<p>All the app does is tell you it is happening. That is the whole of the AI photo story on mobile: upload, and carry on with your day.</p>'
        + '<p>You can close the wizard, drive to your next viewing, and the analysis keeps running without you.</p>' },

    { t:'The suggestions appear on the web',
      run:()=>{ enterApp('agent'); startWizard(null);
                Object.assign(W,{step:4, created:true, propertyId:912, title:'Stunning 4 Bed House in Uvongo',
                  suburb:'Uvongo', city:'Margate', province:'KwaZulu-Natal', type:'House', price:'2500000',
                  spaces:[{k:'Bedroom',count:4,features:{all:[],per:{}}},{k:'Pool',count:1,features:{all:[],per:{}}}],
                  photos:Array.from({length:6},(_,i)=>({tag:i<4?'Bedroom':'Pool',g:i,name:'IMG_284'+i+'.jpg'}))});
                render(true); },
      html: '<p>Later, when you <strong>open that property in the web app</strong>, the results are waiting for you. This panel does not exist anywhere on the phone — it is <strong>desktop only</strong>, and that is exactly where the reviewing gets done, on a big screen with the photos in front of you.</p>'
        + '<p>Here is what the web shows for the six photos you just uploaded:</p>'
        + webAiPreview()
        + '<p>Each chip carries the feature name, a purple <strong>AI badge</strong>, and <strong>confidence pips</strong>. Anything scoring <strong>50% or higher arrives pre-ticked</strong>. <em>Air conditioning</em> at one pip arrives unticked, because the machine is not confident and is not pretending to be. Clicking the eye shows the actual source photos it was detected in.</p>' },

    { t:'The trust model',
      run:()=>{},
      html: T.callout('The AI never silently changes your listing', 'It <strong>proposes</strong>. You <strong>confirm</strong> — on the web, where you can see what it saw. Nothing is written to the listing until a human accepts it, and the room counts you typed on the phone are <strong>never overwritten</strong>; the AI\'s own count is advisory only.', 'purple')
        + '<p>This is why the purple badge exists at all: so you always know what a machine touched.</p>'
        + T.callout('Purple is not a brand colour', 'The AI badge stays purple no matter which agency you are. Change the agency theme in the top bar and watch — the buttons, icons and chips all re-brand, but the <strong>AI badge does not</strong>, and neither does the money gold. Those two are <em>system signals</em>, not brand decoration. If purple could become your agency\'s colour, "this was AI-generated" would stop meaning anything.', 'purple') },

    { t:'Ellie — hold to talk',
      hl:'[data-tour="mic"]',
      run:()=>{ enterApp('agent'); ellie={state:'idle',transcript:null,lastEventId:null}; tab('ellie'); },
      html: '<p>A 140px mic button. Accent gradient at rest; a <strong>red gradient with a pulsing glow</strong> while recording.</p>'
        + T.try('<strong>Press and hold</strong> the mic for a second or two, then let go. Watch it go red and pulse, then "Thinking…", then a result sheet slides up.<br><br>Tap <strong>"Open event"</strong> and you will land on the Calendar — <strong>with the event actually there</strong>. It was real all along.') },

    { t:'What Ellie is, and is not',
      run:()=>{ enterApp('agent'); tab('ellie'); },
      html: '<p>Ellie is a <strong>voice-to-action</strong> assistant. You speak; she transcribes; she extracts an intent; she creates a calendar event. And you can <strong>undo it in one tap</strong>.</p>'
        + T.callout('She is not a chatbot', 'There is no conversation thread. You cannot ask her how the market is doing. Setting that expectation up front prevents a great deal of disappointment — and honestly, "book that viewing while I am walking to my car" is worth more than a chat window.', 'purple')
        + '<p>The failure paths are real, so try them:</p>'
        + '<ul><li><strong>Tap and release instantly</strong> → <em>"Hold to talk — press and hold the mic"</em>.</li>'
        + '<li><strong>Hold for well under a second</strong> → she cannot make it out: <em>"I didn\'t catch that."</em></li></ul>'
        + '<p>Both are worth doing once, on purpose, here — so that the first time it happens on a client\'s driveway you already know what you are looking at.</p>' },
  ]},

{ title:'Going live',
  steps:[
    { t:'The compliance card',
      hl:'[data-tour="compliance"]',
      run:()=>{ enterApp('agent'); DB.properties[0].compliance={authority:false,fica:false,photos:false,details:false};
                DB.properties[0].live=false; DB.properties[0].photos=8;
                go('overview',{id:1}); },
      html: '<p>This is the card new agents get stuck on more than any other, and the question is always the same: <strong>"why can\'t I publish my listing?"</strong></p>'
        + '<p>The answer is <em>always</em> one of four gates, and they are always in this order:</p>'
        + '<ul><li><strong>Authority to market</strong> — is there a signed mandate?</li>'
        + '<li><strong>Seller FICA</strong> — is the seller verified? (South African law.)</li>'
        + '<li><strong>Photos</strong> — the portals demand 12. You have 8.</li>'
        + '<li><strong>Listing details</strong> — title, price, property type.</li></ul>'
        + '<p>The badge at the top reads <strong>BLOCKED</strong> (orange), <strong>READY</strong> (blue) or <strong>LIVE</strong> (green). Below the gates, an orange panel spells out exactly what is blocking go-live.</p>' },

    { t:'Resolve them and ship it',
      hl:'[data-tour="sendMarket"]',
      run:()=>{ enterApp('agent'); go('overview',{id:1}); },
      html: '<p>The <strong>"Send Authority to Market"</strong> button at the bottom is <strong>disabled</strong>, and it tells you why: <em>"Resolve the items above to enable sending to market."</em></p>'
        + '<p>No mystery, no silent failure, no support ticket.</p>'
        + T.try('Tap each gate\'s little action chip in turn — <strong>Send mandate for signature</strong>, <strong>Start seller FICA</strong>, the photos chip, <strong>Resolve</strong>. Watch each icon flip from orange to green, the badge climb from BLOCKED to READY, and the button come alive.<br><br>Then press it. You get a green banner: <strong>"Live · Sent to market on 3 Jul 2026"</strong>, and the portal cards below light up.') },

    { t:'Where the listing is published',
      run:()=>{ enterApp('agent'); go('overview',{id:2}); },
      html: '<p>Scroll to <strong>"Where this listing is published"</strong>. A card per portal — Website, Agency Premium, Private Property, Property24 — each either a green <strong>Live</strong> pill with a reference (<code>Ref P24-123456</code>) or a greyed <strong>Not published</strong> with a lock.</p>'
        + '<p>Below that: the compliance sellers list with FICA badges, the linked contacts, the description with <em>Read more</em>, the listing agents with tappable phone and email, and <strong>Key dates</strong> in a 2×2 grid.</p>'
        + T.callout('Never null', 'A missing date renders as <strong>"—"</strong>. You will never see the word <code>null</code> anywhere in this app. It sounds trivial. It is the difference between software that feels finished and software that does not.') },
  ]},

{ title:'Contacts & Matches',
  steps:[
    { t:'New Contact is one page, not a wizard',
      hl:'[data-tour="createContact"]',
      run:()=>{ enterApp('agent'); ACTS.newContact(); },
      html: '<p>Compare this to the property wizard. <strong>No steps. No progress bar.</strong> Just a form and a button.</p>'
        + T.callout('Why the difference?', 'A contact is <strong>one small record</strong> — it can be created in a single request. A property needs a server ID <em>before</em> it can hold spaces and photos, which is what forces it into steps. The shape of each screen follows the shape of the data underneath it.')
        + T.try('Fill in <strong>John / Meyer</strong> and phone <code>082 555 0134</code> — a number already on file — then press <strong>Create Contact</strong>.') },

    { t:'Duplicate detection',
      run:()=>{ enterApp('agent');
                NC={first:'John', last:'Meyer', phone:'082 555 0134', email:'', idnum:'', type:'Buyer', notes:'', errs:{}, role:null};
                go('newContact');
                setTimeout(()=>ACTS.createContact(), 400); },
      html: '<p><strong>"This contact already exists"</strong> — matched on phone <em>or</em> ID number. Not on name, because two people really can both be called John Meyer.</p>'
        + '<p><strong>"Open contact"</strong> takes you straight to the existing record rather than making you go and find it.</p>'
        + '<p>And when a create <em>does</em> succeed, the app <strong>immediately opens the new contact\'s detail screen</strong>. That surprises people who expect to bounce back to the list.</p>' },

    { t:'What contacts do NOT have',
      run:()=>{ enterApp('agent'); go('contact',{id:1}); },
      html: T.callout('Stop looking for these — they are not there', 'There is <strong>no CSV / bulk import</strong>, <strong>no free-text tags</strong>, and <strong>no "source" field</strong> on contacts in the mobile app. Agents hunt for them for twenty minutes and then log a support ticket.', 'warn')
        + '<p>What you get instead is the <strong>Contact Type</strong> dropdown (Buyer, Seller, Tenant, Landlord, Investor, Referral — the agency defines the list) and, separately, a <strong>Role per linked property</strong>. The same person can be the seller of one house and a buyer of another, and that is exactly why the role lives on the <em>link</em>, not on the contact.</p>' },

    { t:'WhatsApp, and why it counts',
      hl:'[data-tour="waBtn"]',
      run:()=>{ enterApp('agent'); go('contact',{id:1}); },
      html: '<p>See the green <strong>"WhatsApp · 7"</strong> pill with <em>"last 2 Jul 2026"</em>?</p>'
        + T.callout('Every WhatsApp tap is logged', 'Tapping WhatsApp <strong>logs the send server-side and increments that counter</strong> before it opens WhatsApp. That is how the agency knows outreach is actually happening — not by asking agents whether they followed up.')
        + T.try('Tap the green <strong>WhatsApp</strong> button and watch the counter go to 8.')
        + '<p>Below it, <strong>+ Match</strong> and <strong>+ Listing</strong>. Tapping <strong>+ Listing</strong> first asks for the contact\'s <em>role</em> — Seller, Landlord, Buyer, Tenant — and <em>then</em> launches the property wizard with that contact already linked. Try it; it is a lovely little flow.</p>' },

    { t:'Core Matches — not AI',
      run:()=>{ enterApp('agent'); go('match',{id:1}); },
      html: '<p>A <strong>Core Match</strong> is a saved buyer or tenant requirement — type, price band, beds, baths, suburbs, must-have features — that the system scores every property against.</p>'
        + T.callout('This is a rules engine, not AI', 'The name misleads people constantly. There is no model here. It is <strong>deterministic scoring</strong>: price fit, location, beds, baths, features. Same inputs, same score, every single time. That is a feature — you can explain a number like that to a client.')
        + '<p>Results come in three tiers: <strong>Strong</strong> ≥ 80 (green), <strong>Good</strong> 65–79 (blue), <strong>Fair</strong> 50–64 (amber). Nothing below 50 is shown at all — <em>"Adjust the filters to widen the search."</em></p>' },

    { t:'The WhatsApp share',
      hl:'[data-tour="waShare"]',
      run:()=>{ enterApp('agent'); go('match',{id:1}); },
      html: '<p>The richest comms feature in the app, and worth understanding properly.</p>'
        + T.try('Tap <strong>"Send via WhatsApp"</strong>. You get a pre-written, server-rendered message listing the matched properties — <strong>fully editable</strong>, with a "Reset to template" link if you mangle it.')
        + '<p>Press send and the app <strong>logs it first, then opens WhatsApp</strong>. You will see the snackbar: <em>"Logged WhatsApp send. whatsapp_count = 8"</em>.</p>'
        + '<p>If the contact has no phone (try Piet Grobler), you get a red <strong>"No phone on contact"</strong> and the send button is disabled. It fails early and says why.</p>'
        + '<p>Look at the reaction tallies on each result too — those green, amber and red pills are <strong>the client\'s own reactions</strong>, flowing straight back from the client portal. Which is exactly where we are going next.</p>' },
  ]},

{ title:'Leads, alerts & QR',
  steps:[
    { t:'Portal Leads',
      run:()=>{ enterApp('agent'); leadDay=1; go('leads'); },
      html: '<p>These are <strong>public enquiries from Property24 and Private Property</strong>, landing straight in the agent\'s pocket. <strong>P24</strong> is red; <strong>PP</strong> is blue.</p>'
        + '<p>A Sunday-anchored week strip with per-day totals and unread dots. You cannot navigate past the current week — there are no leads from the future.</p>'
        + '<p>Tap a lead: the full enquiry, a tappable phone (with a <strong>WhatsApp</strong> tag when the number supports it), an email, and <strong>Add as contact</strong> — which pre-fills the New Contact form for you.</p>'
        + T.callout('Why this earns a gold dot on Home', 'Speed of response is everything. The first agent to call usually wins the mandate. This is the one place in the app that is allowed to shout.', 'gold') },

    { t:'Notifications',
      run:()=>{ enterApp('agent'); go('notifications'); },
      html: '<p>Grouped <strong>by pillar</strong>, always in this order: <strong>Properties · Contacts · Deals · My activity · Other</strong>. Inside each group, sorted overdue → warning → info, then newest first.</p>'
        + '<p>Unread rows are bold, take a coloured border and an 8px dot. Every row has a glowing severity bar down its left edge.</p>'
        + T.callout('No "9+" badge. No pop-up on launch.', 'The old version interrupted agents the moment they opened the app, and they hated it. Now: actionable things become <strong>rows you can act on</strong>, and pure noise gets <strong>no badge at all</strong>. The absence of a number is a design decision, not an oversight.') },

    { t:'The QR code — the growth loop',
      run:()=>{ enterApp('agent'); go('qr'); },
      html: '<p>Every agent has a personal QR code. A prospect scans it in the CoreX app and <strong>signs up directly as that agent\'s client</strong> — no forms, no admin, no "I\'ll add you on Monday."</p>'
        + '<p><em>"Hand this to prospects. When they scan it in the CoreX app, they sign up directly as your client."</em></p>'
        + '<p>That is the growth engine of the entire product. Every show day, every window card, every business card is a doorway into the client portal — which is the last thing we need to show you.</p>' },
  ]},

{ title:'The Client portal',
  steps:[
    { t:'A different app entirely',
      run:()=>{ S.side='client'; syncSide(); enterApp('client'); },
      html: '<p>Same login, same design system, <strong>completely different app</strong>. Two tabs instead of five. No pipeline, no compliance, no tasks, no kanban.</p>'
        + '<p>Your client is <strong>John Meyer</strong>, a buyer who is also selling his own flat. The status bar in the phone now reads <strong>CLIENT</strong>.</p>'
        + '<p>Top of the screen: <strong>YOUR AGENT</strong> — an accent card with Andre\'s name, a call button and a WhatsApp button. That is the whole point of the portal. The client always knows who their human is.</p>' },

    { t:'Reactions flow back to the agent',
      run:()=>{ S.side='client'; syncSide(); enterApp('client'); },
      html: '<p>Under <strong>"Matched to you"</strong> are the properties the scoring engine picked, and under each one, three buttons:</p>'
        + '<div style="display:flex;gap:6px;margin-bottom:14px;flex-wrap:wrap">'
          + '<span class="chip" style="background:rgba(34,197,94,.14);color:var(--success)">'+icon('thumb-up',12)+'Interested</span>'
          + '<span class="chip" style="background:rgba(245,158,11,.14);color:var(--warning)">'+icon('bookmark',12)+'Saved</span>'
          + '<span class="chip" style="background:rgba(239,68,68,.14);color:var(--danger)">'+icon('thumb-down',12)+'Not for me</span></div>'
        + T.try('Tap <strong>Interested</strong> on a property. Now switch back to the <strong>Agent app</strong> in the top bar and open <strong>Core Matches → John Meyer</strong>. Your reaction is <strong>right there</strong> on the agent\'s screen.')
        + '<p>That loop — the agent sends, the client reacts, the agent sees it without asking — is why the portal exists at all.</p>' },

    { t:'Their listing, their privacy',
      run:()=>{ S.side='client'; syncSide(); enterApp('client'); tab('cme'); },
      html: '<p>A selling client also sees <strong>My Listings</strong>: views, enquiries, and their asking price in gold. Their own numbers, and <strong>only</strong> their own — never another seller\'s, never the agency\'s pipeline.</p>'
        + '<p>And on the Profile tab: <strong>Privacy &amp; consent</strong>, which they control themselves.</p>'
        + T.callout('POPIA, briefly', 'POPIA is South Africa\'s privacy law — the local equivalent of GDPR. It is why consent and FICA exist in this app at all. Every toggle here is logged with a timestamp and a method (electronic, verbal, written, signed). It is a <strong>legal requirement</strong>, not bureaucracy for its own sake, and if a client withdraws consent, the agency must be able to prove when and how.')
        + '<p>The client can also review their agent — those gold stars go straight to the agency.</p>' },
  ]},

{ title:'White-label & wrap-up',
  steps:[
    { t:'One app, many agencies',
      run:()=>{ S.side='agent'; syncSide(); enterApp('agent'); },
      html: '<p>CoreX is <strong>white-labelled</strong>. Each agency supplies its own colours from the API, and the app re-brands itself at runtime.</p>'
        + T.try('Click through the four <strong>Agency</strong> swatches in the top bar — Sky, Emerald, Crimson, Violet — and watch the entire phone re-theme. Buttons, icons, chips, focus rings, the bottom nav, the glow under every button.<br><br>This is <strong>exactly</strong> what happens when a new agency is onboarded. Nobody rebuilds anything.')
        + T.callout('Two things never change', 'The <strong>money gold</strong> and the <strong>purple AI badge</strong> stay put no matter what. Money is a semantic role — "this is currency" — and the AI badge is a system signal — "a machine made this." If an agency could brand either of them, both would stop carrying meaning.', 'gold') },

    { t:'Both themes are real themes',
      run:()=>{ setTheme(S.theme==='dark'?'light':'dark'); },
      html: '<p>Neither theme is an afterthought. Each is a genuine palette, with its own surfaces, borders and shadows — not one palette with the brightness turned down.</p>'
        + '<p>Note that in light mode the page background (<code>#E4EAF3</code>) is deliberately <strong>cooler and darker than the cards</strong>. That is what makes white cards pop instead of dissolving into the background. Cards also gain a hairline border that they do not need in the dark. In dark mode the money gold glows; in light it deepens, because a glow on white is just a smear.</p>'
        + T.callout('Your choice is remembered', 'This page opens in <strong>light</strong>. Flip the sun/moon in the top bar and it stays flipped — next time you come back, and on the rest of the site too. The two share one setting.')
        + T.try('Toggle the sun/moon a few times. Everything — phone, page, panel — re-themes together.') },

    { t:'That is CoreX OS',
      run:()=>{ S.side='agent'; syncSide(); enterApp('agent'); },
      html: '<p>You have now seen every screen an agent touches: the launcher, today, the calendar, the kanban, the property wizard, compliance, contacts, matches, leads, notifications, Ellie — and the client\'s side of the glass.</p>'
        + '<p>If you remember five things, make them these:</p>'
        + '<ul><li>Everything hangs off <strong>four pillars</strong>. No orphans.</li>'
        + '<li>The property is <strong>created at the end of Step 2</strong>. That is why the button changes.</li>'
        + '<li>If you cannot publish, it is <strong>one of four compliance gates</strong>. Always.</li>'
        + '<li>The AI <strong>proposes</strong>; you <strong>confirm</strong>. Purple means a machine touched it.</li>'
        + '<li>The app <strong>warns but does not block</strong>. It respects your judgement.</li></ul>'
        + T.callout('Keep playing', 'Nothing here can break, and nothing is saved. Hit <strong>Restart tour</strong> in the top bar to reset the data and go again — or just close this panel out of your mind and use the phone like an agent would.')
        + '<p class="t-sub" style="font-size:12px;margin-top:16px">Credits: icons hand-drawn in the <strong>Tabler</strong> style (2px stroke, round caps). The real app sets body copy in <strong>Inter</strong> and headings in <strong>Plus Jakarta Sans</strong>; webfonts cannot be loaded here, so this page degrades to your system stack — the tight negative letter-spacing on headings is preserved, because that is most of the character.</p>' },
  ]},
];

/* ---- Tour engine -------------------------------------------------------- */
function totalSteps(){ return TOUR.reduce((n,c)=>n+c.steps.length, 0); }
function stepIndex(){
  let n = 0;
  for(let i=0;i<S.chapter;i++) n += TOUR[i].steps.length;
  return n + S.step;
}
function applyStep(){
  const ch = TOUR[S.chapter];
  const st = ch.steps[S.step];

  document.getElementById('chapEyebrow').textContent =
    'CHAPTER '+(S.chapter+1)+' OF '+TOUR.length+' · '+ch.title.toUpperCase();
  document.getElementById('stepTitle').textContent = st.t;
  document.getElementById('stepBody').innerHTML = st.html;
  document.getElementById('stepBody').scrollTop = 0;
  document.getElementById('progressBar').style.width = Math.round((stepIndex()+1)/totalSteps()*100)+'%';

  document.getElementById('stepDots').innerHTML = ch.steps.map((_,i) =>
    '<i class="'+(i===S.step?'on':(i<S.step?'done':''))+'"></i>').join('');
  document.getElementById('prevBtn').disabled = (S.chapter===0 && S.step===0);
  document.getElementById('nextBtn').textContent =
    (S.chapter===TOUR.length-1 && S.step===ch.steps.length-1) ? 'Done' : 'Next';
  document.getElementById('chapterSel').value = S.chapter;

  if(st.run) st.run();

  // Highlight ring inside the phone
  document.querySelectorAll('.hl').forEach(e=>e.classList.remove('hl'));
  if(st.hl){
    requestAnimationFrame(()=>setTimeout(()=>{
      const el = document.querySelector('#app '+st.hl);
      if(el){
        el.classList.add('hl');
        el.scrollIntoView({behavior:'smooth', block:'center'});
      }
    }, 120));
  }
}
function nextStep(){
  const ch = TOUR[S.chapter];
  if(S.step < ch.steps.length-1) S.step++;
  else if(S.chapter < TOUR.length-1){ S.chapter++; S.step = 0; }
  else return;
  applyStep();
}
function prevStep(){
  if(S.step > 0) S.step--;
  else if(S.chapter > 0){ S.chapter--; S.step = TOUR[S.chapter].steps.length-1; }
  else return;
  applyStep();
}
/* Buttons embedded in the explanation panel can drive the phone. */
document.getElementById('stepBody').addEventListener('click', e => {
  const b = e.target.closest('[data-tourbtn]');
  if(!b) return;
  const [name, arg] = b.getAttribute('data-tourbtn').split(':');
  if(ACTS[name]) ACTS[name](arg);
});

/* ============================================================================
   25. FEATURE SEARCH — find any feature and jump straight to the step that
   teaches it. The index is built from the tour itself, so it can never drift
   out of sync with the content.
   ========================================================================== */
const stripTags = h => h.replace(/<[^>]*>/g, ' ').replace(/&[a-z]+;/gi, ' ').replace(/\s+/g, ' ').trim();

/* A few things learners search for by a name the copy doesn't literally use. */
const SYNONYMS = {
  'kanban':'tasks board columns drag',
  'photo':'photos gallery image upload picture',
  'fica':'compliance verification seller identity',
  'popia':'consent privacy',
  'whatsapp':'message share send client',
  'qr':'code scan sign up prospect growth',
  'ellie':'ai voice assistant microphone speak talk',
  'mandate':'authority to market go live publish',
  'publish':'go live market portal syndication property24 private property',
  'dark':'theme light mode appearance',
  'brand':'white label agency colour theme accent',
  'wizard':'upload property steps create new listing',
  'duplicate':'contact already exists phone id',
  'lead':'portal leads property24 private property enquiry',
  'match':'core matches scoring buyer criteria',
  'password':'login sign in cascade email otp activate',
  'notification':'alerts badge unread severity',
  'swipe':'gesture complete didn\'t happen task',
  'conflict':'overlap double book clash event',
};
const SEARCH_INDEX = [];
TOUR.forEach((ch, ci) => ch.steps.forEach((st, si) => {
  const body = stripTags(st.html);
  SEARCH_INDEX.push({
    ci, si,
    title: st.t,
    chapter: ch.title,
    body,
    hay: (st.t + ' ' + ch.title + ' ' + body).toLowerCase(),
  });
}));

const reEsc = t => t.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
/* Match at a word boundary, not anywhere in the string. A naive substring match
   makes "fica" hit "notiFICAtion", which buries the actual FICA content. */
const wordRe = t => new RegExp('\\b' + reEsc(t), 'i');

function searchTour(qRaw){
  const q = qRaw.trim().toLowerCase();
  if(!q) return [];
  // Expand the query with synonyms so "kanban" finds the Tasks board.
  let expanded = q;
  Object.keys(SYNONYMS).forEach(k => { if(wordRe(k).test(q)) expanded += ' ' + SYNONYMS[k]; });
  const terms = [...new Set(expanded.split(/\s+/).filter(Boolean))];
  const typed = q.split(/\s+/).filter(Boolean);

  return SEARCH_INDEX.map(e => {
    let score = 0;
    terms.forEach(t => {
      const re = wordRe(t);
      const weight = typed.includes(t) ? 3 : 1;       // what they actually typed matters most
      if(re.test(e.title)) score += 12 * weight;
      if(re.test(e.chapter)) score += 6 * weight;
      if(re.test(e.body)) score += 3 * weight;
    });
    if(wordRe(q).test(e.hay)) score += 10;            // whole-phrase bonus
    return {e, score};
  })
  .filter(r => r.score > 0)
  .sort((a,b) => b.score - a.score)
  .slice(0, 8);
}
/* Pull a snippet around the first hit so the result explains itself. */
function snippet(body, q){
  const first = q.trim().split(/\s+/)[0];
  const m = body.match(wordRe(first));
  if(!m) return esc(body.slice(0, 100));
  const i = m.index;
  const start = Math.max(0, i - 32);
  const raw = (start ? '…' : '') + body.slice(start, start + 110);
  return esc(raw).replace(wordRe(first), s => '<span class="sr-hit">' + s + '</span>');
}

const sIn = document.getElementById('searchInput');
const sBox = document.getElementById('searchResults');
let sHits = [], sSel = 0;

function renderSearch(){
  const q = sIn.value;
  sHits = searchTour(q);
  sSel = 0;
  if(!q.trim()){ closeSearch(); return; }
  sBox.innerHTML = sHits.length
    ? sHits.map((r, i) =>
        '<button class="sr-item'+(i===0?' sel':'')+'" role="option" data-hit="'+i+'">'
        + '<span class="sr-top"><span class="sr-title">'+esc(r.e.title)+'</span>'
        + '<span class="sr-chap">'+esc(r.e.chapter)+'</span></span>'
        + '<span class="sr-snip">'+snippet(r.e.body, q)+'</span></button>').join('')
    : '<div class="sr-empty">No feature matches &ldquo;'+esc(q)+'&rdquo;.<br>Try <b>photos</b>, <b>FICA</b>, <b>Ellie</b>, <b>swipe</b> or <b>publish</b>.</div>';
  sBox.hidden = false;
  sIn.setAttribute('aria-expanded','true');
}
function closeSearch(){
  sBox.hidden = true;
  sIn.setAttribute('aria-expanded','false');
}
function gotoHit(i){
  const r = sHits[i];
  if(!r) return;
  S.chapter = r.e.ci; S.step = r.e.si;
  applyStep();
  closeSearch();
  sIn.blur();
}
sIn.addEventListener('input', renderSearch);
sIn.addEventListener('focus', () => { if(sIn.value.trim()) renderSearch(); });
sIn.addEventListener('keydown', e => {
  if(e.key === 'Escape'){ closeSearch(); sIn.blur(); return; }
  if(sBox.hidden || !sHits.length) return;
  if(e.key === 'ArrowDown' || e.key === 'ArrowUp'){
    e.preventDefault();
    sSel = (sSel + (e.key === 'ArrowDown' ? 1 : -1) + sHits.length) % sHits.length;
    sBox.querySelectorAll('.sr-item').forEach((el, i) => el.classList.toggle('sel', i === sSel));
    const el = sBox.querySelectorAll('.sr-item')[sSel];
    if(el) el.scrollIntoView({block:'nearest'});
  }
  if(e.key === 'Enter'){ e.preventDefault(); gotoHit(sSel); }
});
sBox.addEventListener('click', e => {
  const b = e.target.closest('[data-hit]');
  if(b) gotoHit(+b.dataset.hit);
});
document.addEventListener('click', e => {
  if(!document.getElementById('search').contains(e.target)) closeSearch();
});

/* ============================================================================
   26. PAGE CHROME — theme, agency, AI flag, side switch, restart
   ========================================================================== */
/* persist=false on boot: only a deliberate toggle should be remembered, so we
   never confuse "the default" with "the visitor chose light". */
function setTheme(t, persist){
  S.theme = t;
  document.documentElement.setAttribute('data-theme', t);
  document.getElementById('themeBtn').innerHTML = icon(t==='dark'?'sun':'moon',19);
  if(persist !== false){
    try {
      // Remembered for next time, and shared with the marketing site.
      localStorage.setItem('corex-theme', t);
    } catch (e) { /* storage unavailable (private mode) — the page still works */ }
  }
  render();
}
function setAgency(i){
  const a = AGENCIES[i];
  S.agency = i;
  const r = document.documentElement.style;
  r.setProperty('--accent', a.accent);
  r.setProperty('--accent-lite', a.lite);
  r.setProperty('--accent-dark', a.dark);
  const rgb = h => { const n = parseInt(h.slice(1),16); return [(n>>16)&255,(n>>8)&255,n&255].join(','); };
  r.setProperty('--accent-soft', 'rgba('+rgb(a.accent)+',0.15)');
  r.setProperty('--accent-glow', 'rgba('+rgb(a.accent)+',0.25)');
  r.setProperty('--accent-border', 'rgba('+rgb(a.accent)+',0.40)');
  document.querySelectorAll('#swatches .swatch').forEach((s,j) =>
    s.setAttribute('aria-pressed', j===i ? 'true' : 'false'));
  render();
}
function syncSide(){
  document.getElementById('sideAgent').setAttribute('aria-pressed', S.side==='agent' ? 'true':'false');
  document.getElementById('sideClient').setAttribute('aria-pressed', S.side==='client' ? 'true':'false');
}
document.getElementById('themeBtn').addEventListener('click', ()=>setTheme(S.theme==='dark'?'light':'dark'));
document.getElementById('sideAgent').addEventListener('click', ()=>{
  if(S.side==='agent') return;
  S.side='agent'; syncSide(); enterApp('agent');
});
document.getElementById('sideClient').addEventListener('click', ()=>{
  if(S.side==='client') return;
  S.side='client'; syncSide(); enterApp('client');
});
document.getElementById('restartBtn').addEventListener('click', ()=>{
  seed();
  S.chapter=0; S.step=0; S.side='agent'; S.nextApptState='event';
  loginState = freshLogin();
  cal={view:'D',focus:new Date(TODAY),selected:null};
  tasksTab='active'; plist={q:'',scope:'mine',filters:{suburbs:[],min:'',max:'',listing:[],status:[]}};
  clist={q:'',scope:'mine'}; compTab='consent'; leadDay=1; descOpen=false;
  ellie={state:'idle',transcript:null,lastEventId:null};
  CLIENT.reviewed=false; CLIENT.rating=0;
  syncSide();
  S.stack=[]; S.screen='splash'; S.tab='home';
  render(true);
  applyStep();
});
const chapSel = document.getElementById('chapterSel');
chapSel.innerHTML = TOUR.map((c,i)=>'<option value="'+i+'">'+(i+1)+'. '+esc(c.title)+'</option>').join('');
chapSel.addEventListener('change', e => { S.chapter = +e.target.value; S.step = 0; applyStep(); });
document.getElementById('nextBtn').addEventListener('click', nextStep);
document.getElementById('prevBtn').addEventListener('click', prevStep);
document.addEventListener('keydown', e => {
  const el = e.target;
  if(el && typeof el.matches === 'function' && el.matches('input,textarea,select')) return;
  if(e.key === '/'){ e.preventDefault(); sIn.focus(); sIn.select(); return; }
  if(e.key==='ArrowRight') nextStep();
  if(e.key==='ArrowLeft') prevStep();
});

/* ---- Boot --------------------------------------------------------------- */
document.getElementById('restartBtn').innerHTML = icon('refresh',19);
document.getElementById('searchIco').innerHTML = icon('search',17);

/* ---- Fit the phone to the viewport -------------------------------------
   The phone is 868px tall. On a laptop that plus the top bar overflows the
   screen, and the learner has to scroll to reach the bottom nav — which is
   exactly the part they need to click. So we scale it to the height that's
   actually left over, and the page never scrolls on desktop. */
const PHONE_H = 868;
const GAP = 14;                                    // .stage gap between phone and caption

function fitPhone(){
  const root = document.documentElement;
  const topbar = document.querySelector('.topbar');

  const cap = document.getElementById('phoneCaption');

  // The top bar wraps to two rows on a narrow window, so its height has to be
  // measured, never assumed — guessing it is what pushes the bottom nav off screen.
  root.style.setProperty('--tb', topbar.offsetHeight + 'px');

  // Below 1081px the layout stacks and the page scrolls normally.
  if(window.innerWidth <= 1080){
    root.style.setProperty('--ps', '1');
    cap.style.display = '';
    return;
  }

  // Measure the layout box, not the stage: the stage is sized BY the phone, so
  // asking it how much room the phone has always answers "enough".
  requestAnimationFrame(() => {
    const layout = document.querySelector('.layout');
    const cs = getComputedStyle(layout);
    const room = layout.clientHeight - parseFloat(cs.paddingTop) - parseFloat(cs.paddingBottom);

    cap.style.display = '';
    let ps = (room - GAP - cap.offsetHeight) / PHONE_H;

    // On a short screen the footnote costs ~60px, a real chunk of phone. The
    // phone is what people came to use, so the caption gives way first.
    if(ps < 0.78){
      cap.style.display = 'none';
      ps = room / PHONE_H;
    }
    root.style.setProperty('--ps', Math.max(0.5, Math.min(1, ps)).toFixed(4));
  });
}
const phoneScale = () =>
  parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--ps')) || 1;

fitPhone();
window.addEventListener('resize', fitPhone);
// A wrapping top bar changes the height available to the phone.
if(window.ResizeObserver){
  new ResizeObserver(() => fitPhone()).observe(document.querySelector('.topbar'));
}
document.getElementById('swatches').innerHTML = AGENCIES.map((a,i) =>
  '<button class="swatch" style="background:'+a.accent+'" title="'+a.name+'" aria-pressed="'+(i===0)+'"'
  + ' data-agency="'+i+'" aria-label="'+a.name+' theme"></button>').join('');
document.getElementById('swatches').addEventListener('click', e => {
  const b = e.target.closest('[data-agency]');
  if(b) setAgency(+b.dataset.agency);
});
document.getElementById('sbClock').textContent =
  String(NOW_H).padStart(2,'0')+':'+String(NOW_M).padStart(2,'0');

setTheme(S.theme, false);   // whatever the pre-paint script chose; light by default
setAgency(0);
syncSide();
render(true);
applyStep();
</script>
</body>
</html>
@endverbatim
