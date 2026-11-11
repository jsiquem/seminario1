# Plan de trabajo profesional — Case-Tech

## Objetivo

Convertir Case-Tech en una tienda local de WooCommerce preparada para vender de forma clara, confiable y administrable en Guatemala. El plan prioriza primero la compatibilidad del catálogo y el flujo de compra; después se trabajarán conversión, operación y calidad.

## Forma de trabajo

Cada prioridad se implementará como una capacidad completa: código, configuración de WordPress, prueba manual, revisión responsive y registro en `docs/PROJECT-MEMORY.md`. No se avanzará a la siguiente prioridad hasta validar el flujo principal de la anterior.

## Prioridad 1 — Catálogo y compatibilidad

**Estado: Implementada; validación renderizada pendiente.**

El comprador debe poder encontrar productos por marca, modelo compatible y disponibilidad sin recibir resultados irrelevantes.

### Entregables

- [x] Categorías reales por marca: Apple, Samsung, Huawei y Xiaomi.
- [x] Atributo WooCommerce `Modelo compatible` asignado a cada producto.
- [x] Filtro por marca.
- [x] Filtro por modelo compatible.
- [x] Filtro por disponibilidad.
- [x] Selector visible dentro del catálogo.
- [x] Enlaces de la portada conectados a categorías reales.
- [x] Aviso de compatibilidad en la página de catálogo.
- [x] Formulario de filtros dirigido al archivo real de la tienda, no a la portada.
- [ ] Prueba: `Inicio → Samsung → Galaxy A15` debe mostrar solo el modelo correspondiente.

### Validación requerida

1. Activar `Case-Tech Custom` desde Plugins si todavía no está activo.
2. Ejecutar el importador demo para actualizar categorías, modelos e imágenes.
3. Guardar nuevamente los enlaces permanentes con la opción **Nombre de la entrada**.
4. Probar cada marca y modelo.
5. Confirmar que el filtro de disponibilidad excluye productos agotados.
6. Revisar los filtros en móvil y escritorio.

## Prioridad 2 — Fichas de producto orientadas a ventas

**Estado: Implementada; validación renderizada pendiente.**

Se añadieron al plugin propio los bloques de compatibilidad, disponibilidad, envío, pago, advertencia de modelo y señales de confianza. Falta validar el resultado renderizado en una ficha real de WooCommerce.

- [x] Compatibilidad visible cerca del precio.
- [x] Stock y SKU claros.
- [x] Características resumidas.
- [ ] Galería de imágenes.
- [x] Advertencia de modelo exacto.
- [x] Envío Q25.00 y pago por transferencia visibles.
- [ ] Productos relacionados.
- [x] CTA de compra destacado.

## Prioridad 3 — Confianza y contenido comercial

**Estado: En implementación; falta ejecutar el importador y revisar el renderizado.**

- [x] Contacto.
- [x] Preguntas frecuentes.
- [x] Políticas de cambios, devoluciones y privacidad.
- [x] Envíos a toda Guatemala.
- [x] Tiempo de preparación y entrega.
- [x] Datos demo claramente identificados.
- [x] Importador idempotente de páginas institucionales preparado.
- [x] Enlaces de las páginas añadidos al pie del sitio.
- [ ] Ejecutar `create-store-pages.php` dentro de `wpcli`.
- [ ] Revisar Contacto, FAQ, Envíos y Políticas en móvil y escritorio.

## Prioridad 4 — Carrito y checkout profesional

**Estado: En implementación; configuración automatizada preparada.**

- [x] País Guatemala y moneda GTQ.
- [x] Envío plano Q25.00.
- [x] Transferencia bancaria directa demo.
- [x] Aviso de checkout con envío, pago y datos demo.
- [x] Corrección de zona Guatemala con ubicación `GT` y método Flat Rate activo.
- [x] Departamentos de Guatemala registrados como estados seleccionables.
- [x] Países restringidos a Guatemala; no se muestra el listado mundial.
- Resumen claro de pedido.
- Validaciones y errores en español.
- Confirmación con número de pedido.
- Correos de prueba revisados en Mailpit.
- [x] Confirmar que checkout muestre `Envío nacional — Q25.00` al seleccionar Guatemala.
- [x] Confirmar que los 22 departamentos acepten la tarifa de Q25.00.

## Prioridad 5 — Conversión y recompra

**Estado: Pendiente.**

- Productos destacados.
- Recomendaciones relacionadas.
- Mensajes de stock.
- Carrito persistente.
- CTA de continuar comprando.
- Preparación futura para WhatsApp y recuperación de carrito.

## Prioridad 6 — Calidad, accesibilidad y responsive

**Estado: Pendiente.**

- Viewports de 375, 768, 1280 y 1920 px.
- Navegación completa con teclado.
- Focus visible.
- Contraste.
- Textos alternativos.
- Estados de carga, vacío y error.
- Revisión de logs PHP/WordPress.
- Revisión de rendimiento.

## Prioridad 7 — Preparación para producción

**Estado: Pendiente.**

- Sustituir datos ficticios.
- Dominio y HTTPS.
- SMTP real.
- Copias de seguridad.
- Pagos reales.
- Términos legales.
- Prueba final de compra.
- Checklist de seguridad.
