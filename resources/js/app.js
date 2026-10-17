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

function applyTheme(theme, persist = true) {
    const isDark = theme === 'dark';
    const root = document.documentElement;
    root.dataset.theme = isDark ? 'dark' : 'light';
    root.classList.toggle('dark', isDark);

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        const nextTheme = isDark ? 'light' : 'dark';
        const label = `Switch to ${nextTheme} mode (currently ${isDark ? 'dark' : 'light'} mode)`;
        button.setAttribute('aria-pressed', String(isDark));
        button.setAttribute('aria-label', label);
        button.title = `Switch to ${nextTheme} mode`;
        button.querySelector('[data-theme-label]')?.replaceChildren(`Switch to ${nextTheme} mode`);
        button.querySelectorAll('[data-theme-icon]').forEach((icon) => {
            const isVisibleIcon = icon.dataset.themeIcon === (isDark ? 'moon' : 'sun');
            icon.classList.toggle('hidden', !isVisibleIcon);
        });
    });

    if (persist) {
        try {
            window.localStorage.setItem('projects-app-theme', isDark ? 'dark' : 'light');
        } catch (error) {
            console.error('Unable to save the theme preference.', error);
        }
    }

    window.dispatchEvent(new CustomEvent('app:theme-change', {
        detail: { theme: isDark ? 'dark' : 'light' },
    }));
}

function initializeThemeControls() {
    const initialTheme = document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light';
    applyTheme(initialTheme, false);

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const nextTheme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
            applyTheme(nextTheme);
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeThemeControls, { once: true });
} else {
    initializeThemeControls();
}
