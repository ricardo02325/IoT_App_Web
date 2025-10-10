document.addEventListener('DOMContentLoaded', function() {

    // --- MANEJO DEL MENÚ LATERAL ---
    const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

    // Se comprueba que se encontraron elementos antes de continuar
    if (allSideMenu.length > 0) {
        allSideMenu.forEach(item => {
            const li = item.parentElement;

            item.addEventListener('click', function () {
                allSideMenu.forEach(i => {
                    i.parentElement.classList.remove('active');
                })
                li.classList.add('active');
            });
        });
    }

    // --- MANEJO DE LA BARRA DE NAVEGACIÓN (TOGGLE SIDEBAR) ---
    const menuBar = document.querySelector('#content nav .bx.bx-menu');
    const sidebar = document.getElementById('sidebar');

    // Se comprueba que AMBOS elementos existen
    if (menuBar && sidebar) {
        menuBar.addEventListener('click', function () {
            sidebar.classList.toggle('hide');
        });
    }

    // --- MANEJO DEL FORMULARIO DE BÚSQUEDA (ESTA PARTE CAUSABA EL ERROR) ---
    const searchButton = document.querySelector('#content nav form .form-input button');
    const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
    const searchForm = document.querySelector('#content nav form');

    // SOLUCIÓN: Si el botón, el ícono y el formulario existen, entonces agrega el listener.
    // Si no existen (como en la página de 'salones'), simplemente ignora este bloque de código.
    if (searchButton && searchButtonIcon && searchForm) {
        searchButton.addEventListener('click', function (e) {
            if (window.innerWidth < 576) {
                e.preventDefault();
                searchForm.classList.toggle('show');
                if (searchForm.classList.contains('show')) {
                    searchButtonIcon.classList.replace('bx-search', 'bx-x');
                } else {
                    searchButtonIcon.classList.replace('bx-x', 'bx-search');
                }
            }
        });
    }

    // Si tienes más código en este archivo, sigue el mismo patrón de verificación
    // if (elemento) { ... }
});