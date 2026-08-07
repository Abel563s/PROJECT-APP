import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

window.Chart = globalThis.Chart = Chart;
window.dispatchEvent(new Event('chartjs:ready'));
