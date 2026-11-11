# Plan de trabajo profesional — Case-Tech

> Plan único vigente. `tasks/plan.md` queda como histórico. Actualizado 2026-11-11.

## Objetivo

Convertir Case-Tech en una tienda local de WooCommerce preparada para vender de forma clara, confiable y administrable en Guatemala. Primero se levanta el entorno en la PC de trabajo sin Docker; después se validan las prioridades ya implementadas, se cierran los pendientes y se trabajan conversión, calidad y producción.

## Forma de trabajo

Cada prioridad se implementa como una capacidad completa: código, configuración de WordPress, prueba manual, revisión responsive y registro en `docs/PROJECT-MEMORY.md`. No se avanza a la siguiente prioridad hasta validar el flujo principal de la anterior. Si una dependencia no está disponible, la prioridad queda "implementada, pendiente de validación", nunca "terminada".

## Entornos de ejecución

| Entorno | Uso | Base de datos | Correo |
|---|---|---|---|
| **WordPress Playground** (`npx @wp-playground/cli`) en la PC sin Docker | Desarrollo diario y validación visual de tema y plugin | SQLite, desechable; se reconstruye con los importadores | No disponible |
| **Docker Compose** (PC con Docker Desktop o GitHub Codespaces) | Validación fiel: MariaDB, pedidos y correos en Mailpit | MariaDB | Mailpit |

**Regla:** lo visual y funcional se valida en Playground. Lo que dependa de correos, pedidos persistentes o MariaDB se cierra en Docker o Codespaces. El código no debe depender del entorno: nada de rutas `/var/www/html` fijas ni SQL específico de MariaDB.

**Ubicación del proyecto en esta PC:** `C:\Users\CONTABILIDAD\Documents\ecomerce\seminario1`. La copia en `Pictures` es de solo lectura para el usuario y no se usa.

---

## Prioridad 0 — Entorno local sin Docker

**Estado: Completada el 2026-11-11.** `npm run dev` levanta la tienda con 12 productos, 4 páginas institucionales y el tema y el plugin activos, sin errores PHP.

Levantar el proyecto en esta PC (Windows 10, 8 GB de RAM, sin Docker ni PHP, con Node 24) con WordPress Playground. El tema y el plugin se montan desde el repositorio para que cada cambio hecho en VS Code se vea al recargar el navegador.

### Entregables

- [x] `playground/blueprint.json`: instala WooCommerce y lo pone en español; activa `case-tech` y `case-tech-custom`; ejecuta `configure-store.php`, `create-store-pages.php` e `import-demo-products.php`; fija los enlaces permanentes en "Nombre de la entrada".
- [x] `package.json` con un script `npm run dev` que lanza Playground con los montajes.
- [x] Sección "Windows sin Docker (Playground)" en `docs/INSTALL.md` y enlace desde `README.md`.
- [x] Ignorar en Git los archivos que genere Playground.

### Validación

1. `npm run dev` abre WordPress en el navegador con sesión de administrador.
2. Productos lista 12 productos con SKU, precio en GTQ, stock, categoría de marca, atributo `Modelo compatible` e imagen.
3. Páginas lista Contacto, Preguntas frecuentes, Envíos y pagos y Políticas de Case-Tech.
4. Al editar `style.css` y recargar, el cambio se ve sin reiniciar.
5. No aparecen errores fatales de PHP al activar el tema y el plugin.

### Riesgos

- SQLite en lugar de MariaDB. El riesgo es bajo porque el código no usa `$wpdb` directo, pero el flujo debe validarse en Docker antes de producción.
- Los datos de Playground no son permanentes. Ningún dato definitivo se carga ahí.
- La RAM libre es baja (unos 700 MB): conviene cerrar aplicaciones pesadas mientras corre.

## Prioridad 0.5 — Limpieza técnica

**Estado: En curso.**

- [x] Eliminar la copia duplicada `tasks/import-demo-products.php`, que ya difiere de la del plugin desde la línea 65. La versión válida es `plugins/case-tech-custom/import-demo-products.php`. Corregir su comentario de uso, que todavía apunta a `/var/www/html/tasks/`.
- [x] Unificar la búsqueda de la página Shop. Hoy hay dos funciones casi iguales: `case_tech_shop_page_id()` en el tema y `case_tech_custom_shop_page_id()` en el plugin. La lógica debe quedar en el plugin y el tema debe reutilizarla.
- [x] Encontrar la causa real del 404 en `/shop/` (enlaces permanentes o reglas de reescritura) y volver a URLs amigables, retirando el parche `?page_id=` si ya no hace falta. **Resultado:** en Playground `/shop/` responde 200, así que el 404 era de configuración del entorno Docker. La causa del regreso a Inicio era que un formulario GET descarta la query string de su `action`; ahora se usa el permalink real y la query se reenvía como campos ocultos.
- [ ] Limpiar páginas sobrantes: "Página de ejemplo", "Refund and Returns Policy" (borrador) y "Política de privacidad" (borrador, duplicada con "Políticas de Case-Tech"). Asignar la página de privacidad correcta en Ajustes → Privacidad. Si se hace con código, añadirlo de forma idempotente a `create-store-pages.php`.
- [ ] Mover a la cola de la tienda los avisos de depuración que muestre WooCommerce (avisos de funciones obsoletas, avisos del tema).

---

## Prioridad 1 — Catálogo y compatibilidad

**Estado: Validada en Playground el 2026-11-11, salvo el filtro por tipo de producto.**

El comprador debe poder encontrar productos por marca, modelo compatible y disponibilidad sin ver resultados que no le sirven.

### Entregables

- [x] Categorías reales por marca: Apple, Samsung, Huawei y Xiaomi.
- [x] Atributo WooCommerce `Modelo compatible` asignado a cada producto.
- [x] Filtro por marca.
- [x] Filtro por modelo compatible, que depende de la marca elegida.
- [x] Filtro por disponibilidad.
- [x] Selector visible dentro del catálogo.
- [x] Enlaces de la portada conectados a categorías reales.
- [x] Aviso de compatibilidad en la página de catálogo.
- [x] Formulario de filtros dirigido al archivo real de la tienda, no a la portada.
- [ ] Filtro por tipo de producto (case, funda o mica), exigido en `SPEC.md` y no implementado.
- [x] Mensaje de "sin resultados" con un botón para limpiar los filtros.
- [x] Prueba: `Inicio → Samsung → Galaxy A15` debe mostrar solo el modelo correspondiente.

### Validación

1. Probar cada marca con cada uno de sus modelos.
2. Seleccionar Apple: solo deben aparecer iPhone 11, 13 y 15.
3. Marcar un producto como agotado y confirmar que el filtro de disponibilidad lo excluye.
4. Revisar los filtros en 375 px y en 1280 px.

## Prioridad 2 — Fichas de producto orientadas a ventas

**Estado: Implementada; validación renderizada pendiente.**

- [x] Compatibilidad visible cerca del precio.
- [x] Stock y SKU claros.
- [x] Características resumidas.
- [x] Advertencia de modelo exacto.
- [x] Envío Q25.00 y pago por transferencia visibles.
- [x] CTA de compra destacado.
- [ ] Galería de imágenes: activar el soporte `wc-product-gallery-zoom/lightbox/slider` en el tema y cargar al menos 2 imágenes por producto demo.
- [ ] Productos relacionados de la misma marca o modelo, filtrados por compatibilidad. **Hallazgo:** hoy la ficha del Galaxy A15 recomienda S24, iPhone 15, POCO X6 e iPhone 13, porque todos comparten la categoría padre `Cases`.
- [ ] Productos variables: convertir al menos un producto demo en variable (por ejemplo, por color) para cumplir el criterio de éxito de `SPEC.md` ("agregar una variación al carrito").

## Prioridad 3 — Confianza y contenido comercial

**Estado: Implementada; falta ejecutar el importador y revisar el renderizado (se hace en Playground).**

- [x] Contacto.
- [x] Preguntas frecuentes.
- [x] Políticas de cambios, devoluciones y privacidad.
- [x] Envíos a toda Guatemala.
- [x] Tiempo de preparación y entrega.
- [x] Datos demo claramente identificados.
- [x] Importador idempotente de páginas institucionales.
- [x] Enlaces de las páginas en el pie del sitio.
- [x] Ejecutar `create-store-pages.php` (lo hace el blueprint de la prioridad 0).
- [ ] Revisar Contacto, FAQ, Envíos y Políticas en móvil y escritorio.
- [ ] Escribir las instrucciones de la transferencia de prueba. Es una pregunta abierta de `SPEC.md`: se definen y se ponen en la descripción de BACS dentro de `configure-store.php`.

## Prioridad 4 — Carrito y checkout profesional

**Estado: Configuración validada; falta el flujo completo del pedido.**

- [x] País Guatemala y moneda GTQ.
- [x] Envío plano Q25.00.
- [x] Transferencia bancaria directa demo.
- [x] Aviso en el checkout sobre envío, pago y datos demo.
- [x] Zona Guatemala con ubicación `GT` y método Flat Rate activo.
- [x] Departamentos de Guatemala como estados seleccionables.
- [x] Países restringidos a Guatemala.
- [x] El checkout muestra `Envío nacional — Q25.00`.
- [x] Los 22 departamentos aceptan la tarifa de Q25.00.
- [ ] Resumen de pedido claro en carrito y checkout.
- [ ] Validaciones y errores en español (comprobar que WooCommerce está en `es_ES`).
- [ ] Teléfono obligatorio con formato guatemalteco (8 dígitos) y campos sobrantes ocultos (código postal, empresa).
- [ ] Página de confirmación con número de pedido e instrucciones de transferencia: se prueba en Playground.
- [ ] Correos de pedido revisados en Mailpit: **solo en Docker o Codespaces**.

## Prioridad 5 — Conversión y recompra

**Estado: Pendiente.**

- [ ] Productos destacados en la portada, tomados de WooCommerce (producto destacado) y no fijos en el código.
- [ ] Recomendaciones relacionadas y ventas cruzadas en el carrito.
- [ ] Mensajes de stock ("Quedan 3", "Agotado").
- [ ] Carrito persistente para usuarios con sesión (nativo de WooCommerce: solo verificar).
- [ ] CTA de "continuar comprando" en el carrito y tras agregar un producto.
- [ ] Botón de WhatsApp con un número demo configurable desde el administrador, sin integración real.

## Prioridad 6 — Calidad, accesibilidad y responsive

**Estado: Pendiente.** La revisión visual puede automatizarse con capturas en Playwright sobre Playground.

- [ ] Capturas en 375, 768, 1280 y 1920 px de Inicio, Tienda, Ficha, Carrito, Checkout y una página institucional.
- [ ] Navegación completa con teclado y foco visible.
- [ ] Contraste AA: verificar el naranja `#f97316` sobre blanco en texto pequeño (probablemente no cumple; usar `#c2410c` para texto).
- [ ] Textos alternativos en logo, productos y tarjetas.
- [ ] Estados de carga, vacío y error.
- [ ] Sin desplazamiento horizontal en móvil.
- [ ] Revisión de `debug.log` sin avisos del tema ni del plugin.
- [ ] Rendimiento: imágenes demo a 1920×1920 px, que pesan demasiado para una tarjeta; generar tamaños de WooCommerce y comprobar que las tarjetas usan miniaturas.

## Prioridad 7 — Preparación para producción

**Estado: Pendiente. No se publica nada sin aprobación explícita.**

- [ ] Validación completa en Docker o Codespaces con MariaDB y Mailpit (pedido de principio a fin).
- [ ] Sustituir los datos ficticios (BAM, WhatsApp, productos).
- [ ] Elegir hosting compatible con PHP 8.3 y MariaDB, con dominio y HTTPS.
- [ ] SMTP real.
- [ ] Copias de seguridad y prueba de restauración.
- [ ] Pagos reales (por ejemplo, Recurrente) solo después de pruebas en modo sandbox.
- [ ] Términos legales, privacidad, cambios y devoluciones revisados.
- [ ] Usuarios administradores seguros y desactivación de `WP_DEBUG`.
- [ ] Prueba final de compra y checklist de seguridad.

---

## Orden de ejecución sugerido

| Paso | Prioridad | Dónde |
|---|---|---|
| 1 | P0: entorno con Playground | Esta PC |
| 2 | P0.5: limpieza técnica | Esta PC |
| 3 | Validar P1 a P3 en el navegador y corregir lo que falle | Esta PC |
| 4 | Cerrar P1 (tipo de producto, sin resultados) y P2 (galería, relacionados, producto variable) | Esta PC |
| 5 | Cerrar P4 (campos, confirmación) | Esta PC; correos en Docker o Codespaces |
| 6 | P5: conversión | Esta PC |
| 7 | P6: calidad y accesibilidad | Esta PC |
| 8 | P7: producción | Docker o Codespaces y hosting |
