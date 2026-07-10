<div class="resultado-pago contenedor">
    <div class="resultado-icono resultado-pendiente">🏦</div>
    <h1 class="resultado-titulo">Pagá por transferencia</h1>
    <p class="resultado-texto">
        Transferí el total del pedido a la siguiente cuenta y enviá el comprobante por WhatsApp
        para confirmar tu compra.
    </p>

    <div class="datos-transferencia">
        <div class="dato-transferencia">
            <span class="dato-label">Alias</span>
            <span class="dato-valor"><?= htmlspecialchars($alias) ?></span>
        </div>
        <div class="dato-transferencia">
            <span class="dato-label">Titular</span>
            <span class="dato-valor"><?= htmlspecialchars($titular) ?></span>
        </div>
        <div class="dato-transferencia">
            <span class="dato-label">Total a transferir</span>
            <span class="dato-valor dato-destacado">$<?= number_format($total, 0, ',', '.') ?></span>
        </div>
        <div class="dato-transferencia">
            <span class="dato-label">Referencia del pedido</span>
            <span class="dato-valor"><?= htmlspecialchars($referencia) ?></span>
        </div>
    </div>

    <div class="resultado-acciones">
        <a href="<?= htmlspecialchars($whatsappUrl) ?>" target="_blank" rel="noopener" class="btn-whatsapp">
            💬 Enviar comprobante por WhatsApp
        </a>
        <a href="/productos" class="btn-link">Seguir comprando</a>
    </div>
</div>
