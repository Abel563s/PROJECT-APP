<script>
    function toggleFilterPanel(button, panelId) {
        const panel = document.getElementById(panelId);
        if (!panel) return;
        const isExpanded = button.getAttribute('aria-expanded') === 'true';
        button.setAttribute('aria-expanded', String(!isExpanded));
        panel.classList.toggle('hidden', isExpanded);
        button.querySelector('.filter-panel-chevron')?.classList.toggle('rotate-180', !isExpanded);
    }
</script>
