import './bootstrap';
import { createIcons, icons } from 'lucide';

const lucideCreateIcons = (options = {}) => createIcons({ icons, ...options });

window.lucide = globalThis.lucide = { createIcons: lucideCreateIcons, icons };

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => lucideCreateIcons());
} else {
    lucideCreateIcons();
}
