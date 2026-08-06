import './bootstrap';
import { createIcons, icons } from 'lucide';
import ApexCharts from 'apexcharts';

const lucideCreateIcons = (options = {}) => createIcons({ icons, ...options });

window.lucide = globalThis.lucide = { createIcons: lucideCreateIcons, icons };
window.ApexCharts = globalThis.ApexCharts = ApexCharts;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => lucideCreateIcons());
} else {
    lucideCreateIcons();
}
