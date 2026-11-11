// Renders por modelo de micas y fundas (Case-Tech).
// Uso: pasar este archivo a la herramienta browser_run_code de Playwright (parámetro filename).
// Guarda PNG de 1200 px en .playwright-mcp/render-<tipo>-<modelo>.png; luego se convierten a JPG
// como themes/case-tech/demo-<tipo>-<modelo>.jpg. Para un modelo nuevo, añádelo a MODELS y JOBS.
async (page) => {

// Medidas reales aproximadas (mm) y tipo de recorte de pantalla de cada modelo.
const MODELS = {
  'iphone-15':     { name: 'iPhone 15',     w: 71.6, h: 147.6, r: 0.17, bezel: 0.035, chin: 0.035, cut: 'island', home: true },
  'iphone-13':     { name: 'iPhone 13',     w: 71.5, h: 146.7, r: 0.17, bezel: 0.035, chin: 0.035, cut: 'notch',  home: true },
  'galaxy-a15':    { name: 'Galaxy A15',    w: 76.8, h: 160.1, r: 0.11, bezel: 0.03,  chin: 0.075, cut: 'drop' },
  'galaxy-a55':    { name: 'Galaxy A55',    w: 77.4, h: 161.1, r: 0.12, bezel: 0.03,  chin: 0.045, cut: 'hole' },
  'nova-11i':      { name: 'nova 11i',      w: 75.8, h: 164.5, r: 0.12, bezel: 0.028, chin: 0.05,  cut: 'hole-left' },
  'p30-lite':      { name: 'P30 lite',      w: 72.7, h: 152.9, r: 0.11, bezel: 0.03,  chin: 0.075, cut: 'drop' },
  'poco-x6':       { name: 'POCO X6',       w: 74.3, h: 161.2, r: 0.12, bezel: 0.026, chin: 0.04,  cut: 'hole' },
  'redmi-note-13': { name: 'Redmi Note 13', w: 75.6, h: 162.2, r: 0.12, bezel: 0.028, chin: 0.045, cut: 'hole' },
};

function phoneFront(m, x, y, H, id) {
  const W = H * m.w / m.h, R = W * m.r, b = W * m.bezel, chin = W * m.chin;
  const sx = x + b, sy = y + b, sw = W - 2 * b, sh = H - b - chin, sr = Math.max(R - b, 6);
  let cut = '';
  const cx = sx + sw / 2;
  if (m.cut === 'island') cut = `<rect x="${cx - sw * 0.16}" y="${sy + sw * 0.03}" width="${sw * 0.32}" height="${sw * 0.095}" rx="${sw * 0.0475}" fill="#050505"/>`;
  if (m.cut === 'notch') { const nw = sw * 0.42, nh = sw * 0.085, k = nh * 0.6; cut = `<path d="M${cx - nw / 2 - k} ${sy} q${k} 0 ${k} ${k * 0.8} v${nh - k * 1.4} q0 ${k * 0.6} ${k * 0.8} ${k * 0.6} h${nw - 1.6 * k} q${k * 0.8} 0 ${k * 0.8} ${-k * 0.6} v${-(nh - k * 1.4)} q0 ${-k * 0.8} ${k} ${-k * 0.8} z" fill="#050505"/>`; }
  if (m.cut === 'drop') { const dw = sw * 0.12, dh = sw * 0.065; cut = `<path d="M${cx - dw} ${sy} q${dw * 0.45} 0 ${dw * 0.55} ${dh * 0.5} a${dw * 0.45} ${dh * 0.55} 0 0 0 ${dw * 0.9} 0 q${dw * 0.1} ${-dh * 0.5} ${dw * 0.55} ${-dh * 0.5} z" fill="#050505"/>`; }
  if (m.cut === 'hole') cut = `<circle cx="${cx}" cy="${sy + sw * 0.055}" r="${sw * 0.03}" fill="#050505"/>`;
  if (m.cut === 'hole-left') cut = `<circle cx="${sx + sw * 0.085}" cy="${sy + sw * 0.065}" r="${sw * 0.032}" fill="#050505"/>`;
  const home = m.home ? `<rect x="${cx - sw * 0.17}" y="${sy + sh - sw * 0.035}" width="${sw * 0.34}" height="${sw * 0.012}" rx="${sw * 0.006}" fill="rgba(255,255,255,.75)"/>` : '';
  return `
  <defs>
    <linearGradient id="wall${id}" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="#1d2a3a"/><stop offset=".55" stop-color="#3b3f52"/><stop offset="1" stop-color="#c25a1c"/>
    </linearGradient>
    <clipPath id="screen${id}"><rect x="${sx}" y="${sy}" width="${sw}" height="${sh}" rx="${sr}"/></clipPath>
  </defs>
  <rect x="${x - 3}" y="${y - 3}" width="${W + 6}" height="${H + 6}" rx="${R + 3}" fill="#8d9096"/>
  <rect x="${x}" y="${y}" width="${W}" height="${H}" rx="${R}" fill="#0b0c0e"/>
  <rect x="${sx}" y="${sy}" width="${sw}" height="${sh}" rx="${sr}" fill="url(#wall${id})"/>
  <circle cx="${sx + sw * 0.78}" cy="${sy + sh * 0.3}" r="${sw * 0.55}" fill="rgba(255,140,60,.18)" clip-path="url(#screen${id})"/>
  <text x="${cx}" y="${sy + sh * 0.2}" text-anchor="middle" font-family="Segoe UI, Arial" font-weight="300" font-size="${sw * 0.2}" fill="rgba(255,255,255,.92)">10:24</text>
  ${cut}${home}`;
}

function frame(inner, caption) {
  return `<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="1200" viewBox="0 0 1200 1200">
  <defs>
    <radialGradient id="bg" cx=".5" cy=".42" r=".75"><stop offset="0" stop-color="#fffdfa"/><stop offset="1" stop-color="#efe9df"/></radialGradient>
    <filter id="shadow" x="-30%" y="-30%" width="160%" height="160%"><feDropShadow dx="0" dy="28" stdDeviation="30" flood-color="#17191c" flood-opacity=".28"/></filter>
    <filter id="leather"><feTurbulence type="fractalNoise" baseFrequency=".9" numOctaves="2" seed="4"/><feColorMatrix values="0 0 0 0 .1  0 0 0 0 .1  0 0 0 0 .11  0 0 0 .22 0"/><feComposite in2="SourceGraphic" operator="in"/></filter>
  </defs>
  <rect width="1200" height="1200" fill="url(#bg)"/>
  ${inner}
  <text x="600" y="1150" text-anchor="middle" font-family="Segoe UI, Arial" font-size="26" letter-spacing="3" fill="#62666a">${caption.toUpperCase()}</text>
</svg>`;
}

function micaSvg(key) {
  const m = MODELS[key], H = 820, W = H * m.w / m.h, x = 590 - W, y = 170;
  const R = W * m.r, b = W * 0.018;
  // Vidrio desplazado y girado sobre el teléfono, con marco negro, brillo diagonal y pestaña de guía.
  const gx = 640, gy = y + 10;
  const glass = `
  <g transform="rotate(5 ${gx + W / 2} ${gy + H / 2})" filter="url(#shadow)">
    <defs>
      <linearGradient id="sheen" x1="0" y1="0" x2="1" y2="1">
        <stop offset="0" stop-color="#fff" stop-opacity=".55"/><stop offset=".28" stop-color="#fff" stop-opacity=".08"/>
        <stop offset=".46" stop-color="#fff" stop-opacity=".38"/><stop offset=".52" stop-color="#fff" stop-opacity=".05"/><stop offset="1" stop-color="#cfe3f0" stop-opacity=".22"/>
      </linearGradient>
    </defs>
    <rect x="${gx}" y="${gy}" width="${W}" height="${H}" rx="${R}" fill="rgba(210,228,240,.18)" stroke="rgba(255,255,255,.9)" stroke-width="3"/>
    <rect x="${gx + b}" y="${gy + b}" width="${W - 2 * b}" height="${H - 2 * b}" rx="${R - b}" fill="none" stroke="#0b0c0e" stroke-width="${W * 0.035}"/>
    <rect x="${gx}" y="${gy}" width="${W}" height="${H}" rx="${R}" fill="url(#sheen)"/>
    <rect x="${gx + W * 0.36}" y="${gy + H - 4}" width="${W * 0.28}" height="${W * 0.11}" rx="10" fill="#ff6a00" opacity=".9"/>
  </g>`;
  return frame(`<g filter="url(#shadow)">${phoneFront(m, x, y, H, 'm')}</g>${glass}`, `Mica ${m.name} · vidrio templado 9H · imagen ilustrativa`);
}

function fundaSvg(key) {
  const m = MODELS[key], H = 780, W = H * m.w / m.h, pad = 34, flapW = W + 2 * pad, y = 180;
  const total = flapW * 2 + 16, x0 = 600 - total / 2 - 30, x1 = x0 + flapW + 16;
  const lr = W * 0.12 + pad * 0.6;
  const leather = (x, w) => `<rect x="${x}" y="${y - pad}" width="${w}" height="${H + 2 * pad}" rx="${lr}" fill="#1f2124"/>
    <rect x="${x}" y="${y - pad}" width="${w}" height="${H + 2 * pad}" rx="${lr}" fill="#1f2124" filter="url(#leather)"/>
    <rect x="${x + 14}" y="${y - pad + 14}" width="${w - 28}" height="${H + 2 * pad - 28}" rx="${lr - 12}" fill="none" stroke="#8a8f96" stroke-width="2.4" stroke-dasharray="9 7"/>`;
  let slots = '';
  for (let i = 0; i < 3; i++) {
    const sy = y + 60 + i * 74;
    slots += `<path d="M${x0 + 46} ${sy} h${flapW - 92} v118 h${-(flapW - 92)} z" fill="#2a2d31" stroke="#141517" stroke-width="2"/>
      <line x1="${x0 + 52}" y1="${sy + 8}" x2="${x0 + flapW - 52}" y2="${sy + 8}" stroke="#8a8f96" stroke-width="2" stroke-dasharray="7 6"/>`;
  }
  const pocket = `<rect x="${x0 + 46}" y="${y + H - 290}" width="${flapW - 92}" height="250" rx="16" fill="#2a2d31" stroke="#141517" stroke-width="2"/>
    <line x1="${x0 + 58}" y1="${y + H - 280}" x2="${x0 + flapW - 58}" y2="${y + H - 280}" stroke="#8a8f96" stroke-width="2" stroke-dasharray="7 6"/>`;
  const spine = `<rect x="${x0 + flapW - 6}" y="${y - pad + 6}" width="28" height="${H + 2 * pad - 12}" rx="10" fill="#151618"/>`;
  const shell = `<rect x="${x1 + pad - 12}" y="${y - 12}" width="${W + 24}" height="${H + 24}" rx="${W * m.r + 12}" fill="#121315"/>`;
  const strap = `<rect x="${x1 + flapW - 30}" y="${y + H / 2 - 70}" width="120" height="140" rx="30" fill="#1f2124"/>
    <rect x="${x1 + flapW - 30}" y="${y + H / 2 - 70}" width="120" height="140" rx="30" fill="#1f2124" filter="url(#leather)"/>
    <rect x="${x1 + flapW - 18}" y="${y + H / 2 - 58}" width="96" height="116" rx="22" fill="none" stroke="#8a8f96" stroke-width="2.2" stroke-dasharray="8 6"/>`;
  const inner = `<g filter="url(#shadow)">${leather(x0, flapW)}${leather(x1, flapW)}${spine}${strap}</g>
    ${slots}${pocket}${shell}${phoneFront(m, x1 + pad, y, H, 'f')}`;
  return frame(inner, `Funda tipo cartera ${m.name} · imagen ilustrativa`);
}

const JOBS = [
  ['mica', 'iphone-15'], ['mica', 'galaxy-a15'], ['mica', 'nova-11i'], ['mica', 'poco-x6'],
  ['funda', 'iphone-13'], ['funda', 'galaxy-a55'], ['funda', 'p30-lite'], ['funda', 'redmi-note-13'],
];
  await page.setViewportSize({ width: 1200, height: 1200 });
  const out = [];
  for (const [type, key] of JOBS) {
    const svg = type === 'mica' ? micaSvg(key) : fundaSvg(key);
    await page.setContent('<html><body style="margin:0">' + svg + '</body></html>');
    await page.waitForTimeout(150);
    const file = `.playwright-mcp/render-${type}-${key}.png`;
    await page.screenshot({ path: file, clip: { x: 0, y: 0, width: 1200, height: 1200 } });
    out.push(file);
  }
  return out;
}
