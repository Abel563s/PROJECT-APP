import './bootstrap';
import { createIcons, icons } from 'lucide';
import ApexCharts from 'apexcharts';
import './analytics-chart-loader';

const iconAliases = {
    Blueprint: 'PanelsTopLeft',
    ShieldLock: 'ShieldCheck',
};
const appIcons = { ...icons };

Object.entries(iconAliases).forEach(([alias, source]) => {
    appIcons[alias] = icons[source];
});

const lucideCreateIcons = (options = {}) => createIcons({ icons: appIcons, ...options });

window.lucide = globalThis.lucide = { createIcons: lucideCreateIcons, icons: appIcons };
window.ApexCharts = globalThis.ApexCharts = ApexCharts;

const renderAddedIcons = (mutations) => {
    mutations.forEach((mutation) => {
        mutation.addedNodes.forEach((node) => {
            if (!(node instanceof Element) || node.tagName.toLowerCase() === 'svg') return;

            if (node.matches('[data-lucide]')) {
                const stagingRoot = document.createElement('span');
                stagingRoot.append(node.cloneNode(false));
                lucideCreateIcons({ root: stagingRoot });
                const renderedIcon = stagingRoot.firstElementChild;
                if (renderedIcon?.tagName.toLowerCase() === 'svg') node.replaceWith(renderedIcon);
                return;
            }

            if (node.querySelector('[data-lucide]')) {
                lucideCreateIcons({ root: node });
            }
        });
    });
};

const iconObserver = new MutationObserver(renderAddedIcons);
iconObserver.observe(document.documentElement, { childList: true, subtree: true });

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => lucideCreateIcons());
} else {
    lucideCreateIcons();
}
