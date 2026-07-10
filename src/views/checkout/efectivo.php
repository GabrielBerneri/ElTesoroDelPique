<div class="resultado-pago contenedor">
    <div class="resultado-icono resultado-ok">💵</div>
    <h1 class="resultado-titulo">Pago en efectivo</h1>
    <p class="resultado-texto">
        Para coordinar la entrega y el pago en efectivo, envianos un mensaje por WhatsApp.
        Ya te dejamos el mensaje listo con los datos de tu pedido.
    </p>

    <p class="resultado-referencia">
        Pedido: <strong><?= htmlspecialchars($referencia) ?></strong> —
        Total: <strong>$<?= number_format($total, 0, ',', '.') ?></strong>
    </p>

    <div class="resultado-acciones">
        <a href="<?= htmlspecialchars($whatsappUrl) ?>" target="_blank" rel="noopener" class="btn-whatsapp">
            💬 Coordinar por WhatsApp
        </a>
        <a href="/productos" class="btn-link">Seguir comprando</a>
    </div>
</div>
