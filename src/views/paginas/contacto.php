<section class="pagina-header">
    <div class="contenedor">
        <h1 class="pagina-titulo">Contacto</h1>
        <p class="pagina-subtitulo">Escribinos y te respondemos a la brevedad</p>
    </div>
</section>

<section class="pagina-contacto">
    <div class="contenedor">
        <div class="contacto-tarjetas">
            <a href="https://wa.me/5491158438800" target="_blank" rel="noopener" class="contacto-tarjeta">
                <span class="contacto-icono">💬</span>
                <span class="contacto-titulo">WhatsApp</span>
                <span class="contacto-dato">11 5843-8800</span>
            </a>
            <a href="https://www.instagram.com/eltesorodelpique/" target="_blank" rel="noopener" class="contacto-tarjeta">
                <span class="contacto-icono">📷</span>
                <span class="contacto-titulo">Instagram</span>
                <span class="contacto-dato">@eltesorodelpique</span>
            </a>
        </div>

        <!-- SEGUIR MI PEDIDO -->
        <div class="contacto-seguimiento">
            <h2 class="contacto-seguimiento-titulo">📦 Seguí tu pedido</h2>
            <p class="contacto-seguimiento-texto">
                Ingresá el número de orden y el email de la compra para ver en qué estado está.
            </p>
            <form method="POST" action="/seguimiento" class="contacto-seguimiento-form">
                <input type="text" name="referencia" required
                       placeholder="Número de orden (ej: ORD-1234567890-123)">
                <input type="email" name="email" required
                       placeholder="Email de la compra">
                <button type="submit" class="btn btn-primario">Consultar</button>
            </form>
        </div>
    </div>
</section>
