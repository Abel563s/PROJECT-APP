import './bootstrap';
import { createIcons, icons } from 'lucide';
import ApexCharts from 'apexcharts';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const lucideCreateIcons = (options = {}) => createIcons({ icons, ...options });

window.lucide = globalThis.lucide = { createIcons: lucideCreateIcons, icons };
window.ApexCharts = globalThis.ApexCharts = ApexCharts;
window.Chart = globalThis.Chart = Chart;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => lucideCreateIcons());
} else {
    lucideCreateIcons();
}
