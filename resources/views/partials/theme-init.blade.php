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

        const updateThemeControls = (activeTheme) => {
            const isDark = activeTheme === 'dark';
            const nextTheme = isDark ? 'light' : 'dark';

            document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
                button.setAttribute('aria-pressed', String(isDark));
                button.setAttribute('aria-label', `Switch to ${nextTheme} mode (currently ${activeTheme} mode)`);
                button.title = `Switch to ${nextTheme} mode`;
                button.querySelector('[data-theme-label]')?.replaceChildren(`Switch to ${nextTheme} mode`);
                button.querySelectorAll('[data-theme-icon]').forEach((icon) => {
                    icon.classList.toggle('hidden', icon.dataset.themeIcon !== (isDark ? 'moon' : 'sun'));
                });
            });
        };

        updateThemeControls(theme);
        document.addEventListener('DOMContentLoaded', () => {
            updateThemeControls(root.dataset.theme);
        }, { once: true });
        document.addEventListener('click', (event) => {
            const button = event.target instanceof Element
                ? event.target.closest('[data-theme-toggle]')
                : null;

            if (!button) return;

            const nextTheme = root.dataset.theme === 'dark' ? 'light' : 'dark';
            root.dataset.theme = nextTheme;
            root.classList.toggle('dark', nextTheme === 'dark');
            updateThemeControls(nextTheme);

            try {
                window.localStorage.setItem(preferenceKey, nextTheme);
            } catch (error) {
                console.error('Unable to save the theme preference.', error);
            }

            window.dispatchEvent(new CustomEvent('app:theme-change', {
                detail: { theme: nextTheme },
            }));
        });
    })();
</script>
