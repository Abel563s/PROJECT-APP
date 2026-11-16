import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

window.Chart = globalThis.Chart = Chart;

function syncChartTheme(theme = document.documentElement.dataset.theme) {
    const isDark = theme === 'dark';
    const textColor = isDark ? '#CBD5E1' : '#475569';
    const mutedColor = isDark ? '#94A3B8' : '#64748B';
    const borderColor = isDark ? '#334155' : '#E2E8F0';

    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = borderColor;
    Chart.defaults.plugins.legend.labels.color = textColor;
    Chart.defaults.plugins.tooltip.backgroundColor = isDark ? '#1E293B' : 'rgba(255, 255, 255, 0.95)';
    Chart.defaults.plugins.tooltip.titleColor = isDark ? '#F1F5F9' : '#0F172A';
    Chart.defaults.plugins.tooltip.bodyColor = isDark ? '#CBD5E1' : '#475569';
    Chart.defaults.plugins.tooltip.borderColor = borderColor;

    Object.values(Chart.instances).forEach((chart) => {
        chart.options.color = textColor;
        chart.options.plugins.legend = {
            ...chart.options.plugins.legend,
            labels: {
                ...chart.options.plugins.legend?.labels,
                color: textColor,
            },
        };
        chart.options.plugins.tooltip = {
            ...chart.options.plugins.tooltip,
            backgroundColor: isDark ? '#1E293B' : 'rgba(255, 255, 255, 0.95)',
            titleColor: isDark ? '#F1F5F9' : '#0F172A',
            bodyColor: isDark ? '#CBD5E1' : '#475569',
            borderColor,
        };

        Object.values(chart.options.scales || {}).forEach((scale) => {
            scale.ticks = { ...scale.ticks, color: mutedColor };
            scale.grid = { ...scale.grid, color: isDark ? '#334155' : '#E2E8F0' };
        });

        chart.update('none');
    });
}

window.addEventListener('app:theme-change', (event) => {
    syncChartTheme(event.detail?.theme);
});
syncChartTheme();

function summarizeChartSeries(items, formatter) {
    const maxVisibleChartItems = 12;
    const visibleItems = items.slice(0, maxVisibleChartItems);
    const remainingItems = items.slice(maxVisibleChartItems);
    const summarized = visibleItems.map((item, index) => ({
        ...item,
        label: formatter(item, index)
    }));

    if (remainingItems.length > 0) {
        summarized.push({
            label: `Others (${remainingItems.length})`,
            isSummary: true,
            values: remainingItems,
        });
    }

    return summarized;
}

function shortCodeLabel(code, fallback, index) {
    const value = String(code || fallback || `P${index + 1}`).trim();
    return value.length > 14 ? `${value.slice(0, 12)}…` : value;
}

function roundToNice(value, roundUp = false) {
    if (!Number.isFinite(value) || value <= 0) return 0;
    const exp = Math.floor(Math.log10(value));
    const base = Math.pow(10, exp);
    const normalized = value / base;
    let nice;
    if (roundUp) {
        if (normalized <= 1) nice = 1;
        else if (normalized <= 2) nice = 2;
        else if (normalized <= 2.5) nice = 2.5;
        else if (normalized <= 5) nice = 5;
        else nice = 10;
    } else {
        if (normalized < 1.5) nice = 1;
        else if (normalized < 2.5) nice = 2;
        else if (normalized < 3.5) nice = 3;
        else if (normalized < 5.5) nice = 5;
        else nice = 10;
    }
    return nice * base;
}

function buildLineChart(ctx, labels, values, color, label, target, ySuffix = '') {
    if (!ctx || values.length === 0) return;

    const dataMin = Math.min(...values);
    const dataMax = Math.max(...values);
    const padding = (dataMax - dataMin) * 0.15;
    const rawMin = dataMin - padding;
    const rawMax = dataMax + padding;
    const yMin = target === 100 ? 0 : Math.max(0, roundToNice(rawMin, false));
    const yMax = target === 100 ? Math.max(100, roundToNice(rawMax, true)) : roundToNice(rawMax, true);
    const finalMax = Math.max(yMax, yMin + (target === 100 ? 20 : 1.0));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label,
                data: values,
                borderColor: color,
                backgroundColor: (context) => {
                    const chart = context.chart;
                    const { ctx: gradientCtx, chartArea } = chart;
                    if (!chartArea) return `${color}1A`;
                    const gradient = gradientCtx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                    gradient.addColorStop(0, `${color}00`);
                    gradient.addColorStop(1, `${color}26`);
                    return gradient;
                },
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 8,
                pointBackgroundColor: color,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                    titleColor: '#0f172a',
                    bodyColor: '#475569',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    cornerRadius: 12,
                    padding: 16,
                    boxPadding: 4,
                    usePointStyle: true,
                    callbacks: {
                        title: function(context) {
                            const idx = context[0].dataIndex;
                            return 'Date: ' + (labels[idx] || 'N/A');
                        },
                        label: function(context) {
                            const value = context.raw;
                            return context.dataset.label + ': ' + value.toFixed(2) + ySuffix;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: {
                        font: { weight: 'bold', size: 10 },
                        color: '#64748b',
                        maxRotation: 45,
                        minRotation: 0
                    }
                },
                y: {
                    min: yMin,
                    max: finalMax,
                    grid: { color: '#f1f5f9', drawBorder: false },
                    ticks: {
                        font: { weight: 'bold', size: 9 },
                        color: '#94a3b8',
                        callback: function(value) {
                            return value + ySuffix;
                        }
                    }
                }
            },
            animation: { duration: 1500, easing: 'easeInOutQuart' }
        },
        plugins: [{
            id: 'targetLine',
            beforeDraw: (chart) => {
                const { ctx, chartArea: { left, right }, scales: { y } } = chart;
                const yPos = y.getPixelForValue(target);
                ctx.save();
                ctx.strokeStyle = '#94a3b8';
                ctx.lineWidth = 2;
                ctx.setLineDash([8, 4]);
                ctx.beginPath();
                ctx.moveTo(left, yPos);
                ctx.lineTo(right, yPos);
                ctx.stroke();
                ctx.restore();
            }
        }]
    });
}

function initializeAnalyticsCharts(data = {}) {
    const chartConstructor = window.Chart || globalThis.Chart;
    if (!chartConstructor) {
        console.error('Chart.js failed to load for analytics page');
        return;
    }

    const ChartInstance = chartConstructor;

    const evmRows = Array.isArray(data.evmData) ? data.evmData : [];
    if (document.getElementById('evmChart') && evmRows.length > 0) {
        const evmSeries = evmRows.map((item, index) => ({
            ...item,
            label: shortCodeLabel(item.project_code, item.project_name, index),
        }));
        const labels = evmSeries.map(item => item.label);
        const pvData = evmSeries.map(item => (Number(item.pv) || 0));
        const evData = evmSeries.map(item => (Number(item.ev) || 0));
        const acData = evmSeries.map(item => (Number(item.ac) || 0));
        const cpData = evmSeries.map(item => (Number(item.cost_planned) || 0));

        const formatCurrency = (value) => {
            if (value >= 1_000_000_000) return 'ETB ' + (value / 1_000_000_000).toFixed(1) + 'B';
            if (value >= 1_000_000) return 'ETB ' + (value / 1_000_000).toFixed(1) + 'M';
            if (value >= 1_000) return 'ETB ' + (value / 1_000).toFixed(0) + 'K';
            return 'ETB ' + value.toFixed(0);
        };

        new ChartInstance(document.getElementById('evmChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Revenue Planned (PV)',
                        data: pvData,
                        backgroundColor: '#2563eb',
                        borderColor: '#2563eb',
                        borderWidth: 1,
                        borderRadius: 8,
                    },
                    {
                        label: 'Revenue Actual (EV)',
                        data: evData,
                        backgroundColor: '#10b981',
                        borderColor: '#10b981',
                        borderWidth: 1,
                        borderRadius: 8,
                    },
                    {
                        label: 'Cost Actual (AC)',
                        data: acData,
                        backgroundColor: '#f59e0b',
                        borderColor: '#f59e0b',
                        borderWidth: 1,
                        borderRadius: 8,
                    },
                    {
                        label: 'Cost Planned (CP)',
                        data: cpData,
                        backgroundColor: '#f43f5e',
                        borderColor: '#f43f5e',
                        borderWidth: 1,
                        borderRadius: 8,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#64748b' } },
                    y: { beginAtZero: true, ticks: { color: '#64748b' } }
                }
            }
        });
    }

    const sCurveData = Array.isArray(data.sCurveData) ? data.sCurveData : [];
    const allDates = [];
    const spiValues = [];
    const cpiValues = [];

    if (sCurveData.length > 0) {
        sCurveData.forEach(update => {
            if (update.date !== null && update.date !== undefined) {
                allDates.push(update.date);
                spiValues.push(Number.isFinite(update.spi) ? update.spi : 0);
                cpiValues.push(Number.isFinite(update.cpi) ? update.cpi : 0);
            }
        });
    }

    const cpiChartCtx = document.getElementById('cpiChart');
    if (cpiChartCtx && cpiValues.length > 0) {
        buildLineChart(cpiChartCtx, allDates, cpiValues, '#2563eb', 'CPI', 1.0);
    }

    const spiChartCtx = document.getElementById('spiChart');
    if (spiChartCtx && spiValues.length > 0) {
        buildLineChart(spiChartCtx, allDates, spiValues, '#10b981', 'SPI', 100, '%');
    }

    const sectorCtx = document.getElementById('sectorDistributionChart');
    const sectorData = data.sectorValues && typeof data.sectorValues === 'object' ? Object.values(data.sectorValues) : [];
    const sectorLabels = data.sectorValues && typeof data.sectorValues === 'object' ? Object.keys(data.sectorValues) : [];
    if (sectorCtx && sectorData.length > 0) {
        const sectorColors = ['#00ADC5', '#6366f1', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6'];
        new ChartInstance(sectorCtx, {
            type: 'doughnut',
            data: {
                labels: sectorLabels,
                datasets: [{
                    data: sectorData,
                    backgroundColor: sectorColors.slice(0, sectorLabels.length),
                    borderColor: '#ffffff',
                    borderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%'
            }
        });
    }

    const scheduleCtx = document.getElementById('schedulePerformanceChart');
    const scheduleData = Array.isArray(data.schedulePerformanceData) ? data.schedulePerformanceData : [];
    if (scheduleCtx && scheduleData.length > 0) {
        const scheduleSeries = scheduleData.map((item, index) => ({
            ...item,
            label: shortCodeLabel(item.project_code, item.project_name, index),
        }));
        const projectLabels = scheduleSeries.map(item => item.label);
        const plannedValues = scheduleSeries.map(item => (parseFloat(item.planned) || 0));
        const actualValues = scheduleSeries.map(item => (parseFloat(item.actual) || 0));

        new ChartInstance(scheduleCtx, {
            type: 'bar',
            data: {
                labels: projectLabels,
                datasets: [
                    { label: 'Planned Progress', data: plannedValues, backgroundColor: '#3b82f6', borderColor: '#2563eb', borderWidth: 1, borderRadius: 8 },
                    { label: 'Actual Progress', data: actualValues, backgroundColor: '#10b981', borderColor: '#059669', borderWidth: 1, borderRadius: 8 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, max: 100, ticks: { color: '#64748b' } }
                }
            }
        });
    }

    const typeCtx = document.getElementById('typePerformanceChart');
    const typeData = data.projectTypes && typeof data.projectTypes === 'object' ? data.projectTypes : {};
    const typeLabels = Object.keys(typeData);
    const typeValues = Object.values(typeData);
    if (typeCtx && typeLabels.length > 0) {
        const typeColors = ['#00ADC5', '#6366f1', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6'];
        new ChartInstance(typeCtx, {
            type: 'polarArea',
            data: {
                labels: typeLabels,
                datasets: [{
                    label: 'Projects',
                    data: typeValues,
                    backgroundColor: typeLabels.map((_, i) => typeColors[i % typeColors.length] + '26'),
                    borderColor: typeLabels.map((_, i) => typeColors[i % typeColors.length]),
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: { beginAtZero: true, max: Math.max(...typeValues, 5) }
                }
            }
        });
    }
}

window.initializeAnalyticsCharts = initializeAnalyticsCharts;
window.dispatchEvent(new Event('chartjs:ready'));
