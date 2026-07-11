# Webhook de MercadoPago

El webhook confirma los pagos **del lado del servidor**, en vez de depender de que el
cliente vuelva a la página de éxito. Es más seguro y actualiza las ventas automáticamente.

---

## Cómo funciona

```
Cliente paga en MercadoPago
        ↓
MercadoPago envía una notificación (POST) a /webhook/mercadopago
        ↓
Nuestro servidor recibe el ID del pago
        ↓
Re-consultamos el pago REAL en la API de MercadoPago (con nuestro access token)
        ↓
Según el estado del pago actualizamos la venta:
   approved  → pagado
   rejected  → cancelado
   otros     → pendiente
```

> **Seguridad**: nunca confiamos en el contenido de la notificación. Siempre volvemos a
> consultar el pago real usando el ID, con nuestro `MP_ACCESS_TOKEN`. Así, aunque alguien
> intente falsificar un webhook, no puede marcar una venta como pagada.

---

## Configuración en el panel de MercadoPago (una sola vez)

Además de que el código ya envía la `notification_url` en cada compra, conviene registrarla
en el panel para que MercadoPago la use siempre:

1. Entrá a **mercadopago.com.ar → Tus integraciones → tu aplicación**
2. Andá a la sección **Webhooks** (o "Notificaciones")
3. En **URL de producción** poné:
   ```
   https://darksalmon-quail-593672.hostingersite.com/webhook/mercadopago
   ```
4. En **Eventos**, tildá **Pagos** (`payment`)
5. Guardá

---

## Cómo probarlo

- Desde el panel de Webhooks de MercadoPago hay un botón para **enviar una notificación de prueba**.
- O hacé una compra real de prueba: al aprobarse, la venta debería pasar sola a **pagado**
  en la sección **Ventas** del admin, con el ID de pago de MercadoPago guardado.

Si algo falla, los errores quedan registrados en el log de PHP del servidor (Hostinger →
Avanzado → Registros de errores).
