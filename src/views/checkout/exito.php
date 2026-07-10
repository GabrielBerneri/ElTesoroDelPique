<div class="resultado-pago contenedor">
    <div class="resultado-icono resultado-ok">✓</div>
    <h1 class="resultado-titulo">¡Pago aprobado!</h1>
    <p class="resultado-texto">
        Tu pedido fue confirmado. Te vamos a escribir por WhatsApp o email para coordinar el envío.
    </p>
    <?php if (!empty($referencia)): ?>
    <p class="resultado-referencia">Referencia: <strong><?= htmlspecialchars($referencia) ?></strong></p>
    <?php endif; ?>
    <div class="resultado-acciones">
        <a href="/productos" class="btn btn-primario">Seguir comprando</a>
        <a href="/" class="btn-link">Volver al inicio</a>
    </div>
</div>
