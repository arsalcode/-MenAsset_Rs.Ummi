import './bootstrap';
import Chart from 'chart.js/auto';
import { createIcons, icons } from 'lucide';
import { Html5Qrcode } from 'html5-qrcode';

window.Chart = Chart;
window.createIcons = createIcons;
window.lucideIcons = icons;
window.Html5Qrcode = Html5Qrcode;

export function renderLucide() {
    try {
        createIcons({
            icons,
            attrs: {
                'stroke-width': 2,
            }
        });
    } catch (err) {
        console.error('[Lucide error]', err);
    }
}

window.renderLucide = renderLucide;

document.addEventListener('DOMContentLoaded', () => {
    renderLucide();

    // Auto-render any dynamically injected unrendered icon tags via MutationObserver
    if (document.body) {
        const observer = new MutationObserver(() => {
            if (document.querySelector('i[data-lucide], span[data-lucide]')) {
                renderLucide();
            }
        });
        observer.observe(document.body, { childList: true, subtree: true });
    }
});

document.addEventListener('livewire:navigated', renderLucide);

if (window.Livewire) {
    window.Livewire.hook('morph.updated', () => {
        queueMicrotask(renderLucide);
    });
    window.Livewire.hook('commit', ({ succeed }) => {
        succeed(() => {
            queueMicrotask(renderLucide);
        });
    });
} else {
    document.addEventListener('livewire:init', () => {
        window.Livewire.hook('morph.updated', () => {
            queueMicrotask(renderLucide);
        });
        window.Livewire.hook('commit', ({ succeed }) => {
            succeed(() => {
                queueMicrotask(renderLucide);
            });
        });
    });
}


