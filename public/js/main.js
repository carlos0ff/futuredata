document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) {
        window.lucide.createIcons();
    }

    const sidebar          = document.getElementById('sidebar');
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const osMenuTrigger    = document.querySelector('[data-collapse-trigger="os-menu"]');
    const osMenu           = document.getElementById('os-menu');

    /* ─────────────────────────────────────────
       OVERLAY MOBILE – criado dinamicamente
    ───────────────────────────────────────── */
    const overlay = document.createElement('div');
    overlay.id = 'sidebar-overlay';
    overlay.className = [
        'fixed inset-0 z-40 bg-black/50 backdrop-blur-sm',
        'opacity-0 pointer-events-none transition-opacity duration-300',
        'lg:hidden'
    ].join(' ');
    document.body.appendChild(overlay);

    const openSidebar = () => {
        sidebar?.classList.remove('hidden');
        sidebar?.classList.add('flex');
        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100');
        document.body.style.overflow = 'hidden';
    };

    const closeSidebar = () => {
        sidebar?.classList.add('hidden');
        sidebar?.classList.remove('flex');
        overlay.classList.add('opacity-0', 'pointer-events-none');
        overlay.classList.remove('opacity-100');
        document.body.style.overflow = '';
    };

    mobileMenuButton?.addEventListener('click', () => {
        const isOpen = !sidebar?.classList.contains('hidden');
        isOpen ? closeSidebar() : openSidebar();
    });

    overlay.addEventListener('click', closeSidebar);

    // Fechar sidebar mobile ao redimensionar para desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            overlay.classList.add('opacity-0', 'pointer-events-none');
            overlay.classList.remove('opacity-100');
            document.body.style.overflow = '';
        }
    });

    /* ─────────────────────────────────────────
       COLLAPSE – Ordens de Serviço
    ───────────────────────────────────────── */
    const syncOsMenuState = (isOpen) => {
        if (!osMenu || !osMenuTrigger) return;
        const chevron = osMenuTrigger.querySelector('svg:last-child');

        if (isOpen) {
            osMenu.classList.remove('hidden');
            chevron?.classList.add('rotate-180');
            osMenuTrigger.classList.add('bg-slate-800/50', 'text-white');
            osMenuTrigger.classList.remove('text-slate-200');
        } else {
            osMenu.classList.add('hidden');
            chevron?.classList.remove('rotate-180');
            osMenuTrigger.classList.remove('bg-slate-800/50', 'text-white');
            osMenuTrigger.classList.add('text-slate-200');
        }
    };

    // Restaura estado salvo (padrão: aberto se estiver numa página de OS)
    const savedState  = localStorage.getItem('osMenuOpen');
    const isOsMenuOpen = savedState !== null ? savedState === 'true' : true;
    syncOsMenuState(isOsMenuOpen);

    osMenuTrigger?.addEventListener('click', (e) => {
        e.preventDefault();
        const currentlyOpen = !osMenu.classList.contains('hidden');
        const newState = !currentlyOpen;
        localStorage.setItem('osMenuOpen', newState);
        syncOsMenuState(newState);
    });

    /* ─────────────────────────────────────────
       TABS
    ───────────────────────────────────────── */
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels  = document.querySelectorAll('[data-tab-panel]');

    const activateTab = (targetTab) => {
        tabButtons.forEach((btn) => {
            const active = btn.dataset.tab === targetTab;
            btn.classList.toggle('border-blue-600',   active);
            btn.classList.toggle('text-blue-600',     active);
            btn.classList.toggle('font-semibold',     active);
            btn.classList.toggle('border-transparent', !active);
            btn.classList.toggle('text-slate-500',    !active);
            btn.classList.toggle('font-medium',       !active);
        });

        tabPanels.forEach((panel) => {
            panel.classList.toggle('hidden', panel.dataset.tabPanel !== targetTab);
        });

        sessionStorage.setItem('activeTab', targetTab);
    };

    tabButtons.forEach((btn) => {
        btn.addEventListener('click', () => activateTab(btn.dataset.tab));
    });

    // Restaura última aba ativa (padrão: orcamento)
    const savedTab = sessionStorage.getItem('activeTab') ?? 'orcamento';
    activateTab(savedTab);
});
