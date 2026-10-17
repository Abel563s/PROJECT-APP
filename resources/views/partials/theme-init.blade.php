<script>
    (() => {
        const preferenceKey = 'projects-app-theme';
        let theme = 'light';

        try {
            const savedTheme = window.localStorage.getItem(preferenceKey);
            if (savedTheme === 'dark' || savedTheme === 'light') {
                theme = savedTheme;
            }
        } catch (error) {
            console.error('Unable to read the saved theme preference.', error);
        }

        const root = document.documentElement;
        root.dataset.theme = theme;
        root.classList.toggle('dark', theme === 'dark');
    })();
</script>
