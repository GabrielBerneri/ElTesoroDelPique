// JavaScript principal del sitio
// Por ahora solo preparamos la estructura base

document.addEventListener('DOMContentLoaded', () => {
    // Marcar el link activo en el nav según la URL actual
    const navLinks = document.querySelectorAll('.nav a');
    const rutaActual = window.location.pathname;

    navLinks.forEach(link => {
        link.classList.remove('activo');
        if (link.getAttribute('href') === rutaActual) {
            link.classList.add('activo');
        }
    });
});
