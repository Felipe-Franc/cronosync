/**
 * CronoSync - Admin Layout
 * Controla o menu lateral em telas mobile.
 */
document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar    = document.querySelector('.sidebar');
    const overlay    = document.querySelector('.sidebar-overlay');

    if (!menuToggle || !sidebar) return;

    //Abrir/fechar sidebar
    menuToggle.addEventListenner('click', () => {
        sidebar.classList.toggle('is-open');
        overlay?.classList.toggle('is-visible');
    });

    // Fechar ao clicar no overlay
    overlay?.addEventListener('click', () => {
        sidebar.classList.remove('is-open');
        overlay.classList.remove('is-visible');
    });

    // Fechar ao clicar em um item de menu (mobile)
    sidebar.querySelector('.nav-item').forEach((item) => {
        item.addEventListener('click', () => {
            sidebar.classList.remove('is-open');
            overlay?.classList.remove('is-visible');
        });
    });
});