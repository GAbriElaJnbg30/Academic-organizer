// -------------------------------- Menu de las Vistas del Usuario General y Usuario Administrador ------------------------------------

function toggleMenu() {
    var sideMenu = document.getElementById("sideMenu");
    var mainContent = document.getElementById("mainContent");
    var footer = document.getElementById("footer");

    sideMenu.classList.toggle("active");
    mainContent.classList.toggle("shifted");
    footer.classList.toggle("shifted");
}

document.addEventListener("DOMContentLoaded", function() {
    const menuBar = document.querySelector(".menu-bar");
    const sideMenu = document.getElementById("sideMenu");

    function updateSideMenuPosition() {
        const menuBarRect = menuBar.getBoundingClientRect();
        const screenWidth = window.innerWidth;

        // Si el ancho de la ventana es mayor a 768px (pantallas grandes)
        if (screenWidth > 768) {
            if (menuBarRect.top <= 0) {
                sideMenu.style.position = 'fixed';
                sideMenu.style.top = '0';
            } else {
                sideMenu.style.position = 'absolute';
                sideMenu.style.top = '85px';
            }
        } else {
            // Para pantallas pequeñas, el menú debe ser absoluto, no fijo
            sideMenu.style.position = 'absolute';
            sideMenu.style.top = '85px'; // No cambiar la posición cuando se redimensiona
        }
    }

    updateSideMenuPosition();
    window.addEventListener("scroll", updateSideMenuPosition);
    window.addEventListener("resize", updateSideMenuPosition);
});
