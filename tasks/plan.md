# Plan de trabajo propuesto (HISTÓRICO)

> **Reemplazado por [`docs/PLAN-PROFESIONAL.md`](../docs/PLAN-PROFESIONAL.md) el 2026-11-11.** Se conserva solo como referencia; no actualizar.

> Este plan queda sujeto a la revisión de `docs/SPEC.md`. No se debe avanzar a cambios importantes de código hasta aprobar la especificación y resolver las preguntas abiertas prioritarias.

## Fase 0 — Confirmación del producto

- [x] Confirmar nombre comercial: Case-Tech.
- [ ] Diseñar y aprobar el logotipo con un case que integre la palabra “tech”.
- [x] Confirmar marcas iniciales: iPhone/Apple, Samsung, Huawei y Xiaomi.
- [x] Definir un catálogo demo de tres modelos por marca.
- [x] Confirmar cobertura nacional mediante zona general de Guatemala.
- [x] Confirmar tarifa plana de envío: Q25.00.
- [x] Confirmar transferencia bancaria como método inicial.
- [x] Confirmar que las pantallas serán micas/repuestos sin instalación.
- [x] Definir una dirección visual provisional editable a partir de la referencia de Figma.

**Salida:** brief comercial y catálogo demo definidos; queda pendiente la aprobación del logo final.

## Fase 1 — Auditoría técnica y visual

- [x] Revisar la estructura actual del tema `case-tech`.
- [x] Revisar el plugin `case-tech-custom`.
- [x] Identificar qué ya funciona y qué está vacío.
- [x] Definir tokens visuales, componentes y breakpoints.
- [x] Corregir el error de sintaxis detectado en `functions.php`.

**Salida:** diagnóstico y sistema visual aprobado.

## Fase 2 — Páginas y experiencia comercial

- [x] Construir el tema propio `case-tech` y su primera portada comercial.
- [ ] Activar el tema y validar la página de inicio en el navegador.
- [x] Crear navegación, encabezado, pie, CTA, categorías visuales y marcas.
- [x] Preparar páginas de contacto, preguntas frecuentes, envíos y políticas básicas.
- [ ] Conectar las páginas de WooCommerce: tienda, carrito, checkout y mi cuenta.
- [ ] Validar responsive, teclado, foco, contraste y estados vacíos.

**Salida:** sitio profesional navegable, con identidad y estructura comercial.

## Fase 3 — Base de WordPress/WooCommerce

- [ ] Completar instalación local de WordPress.
- [ ] Instalar y activar WooCommerce.
- [ ] Configurar Guatemala, GTQ, impuestos de prueba y enlaces permanentes.
- [ ] Configurar transferencia bancaria y Mailpit.
- [ ] Ejecutar `create-store-pages.php` y `configure-store.php` desde `wpcli`.
- [ ] Crear categorías, marcas y atributos.

**Salida:** administración lista para cargar catálogo.

## Fase 4 — Catálogo de prueba

- [ ] Crear 12 productos demo: tres modelos para cada marca inicial.
- [ ] Cargar imágenes genéricas reemplazables para los productos demo.
- [ ] Definir productos simples y variables.
- [ ] Configurar inventario, imágenes, precios y SKU.
- [ ] Verificar búsqueda, filtros y variaciones.

**Salida:** catálogo mínimo navegable.

## Fase 5 — Experiencia de catálogo y compra

- [ ] Diseñar y construir la página de inicio.
- [ ] Construir encabezado, navegación, búsqueda y menú móvil.
- [ ] Diseñar categorías, archivo de productos y ficha de producto.
- [ ] Mejorar carrito, checkout y estados vacíos/error.
- [ ] Añadir contacto, preguntas frecuentes y políticas básicas.

**Salida:** flujo completo de compra de prueba.

## Fase 6 — Calidad y preparación

- [ ] Probar móvil, tablet y escritorio.
- [ ] Probar teclado, foco, contraste y textos alternativos.
- [ ] Revisar rendimiento y errores PHP/WordPress.
- [ ] Documentar operación local y backup de prueba.
- [ ] Actualizar `docs/PROJECT-MEMORY.md`.

**Salida:** versión local demostrable y documentada.

## Fase 7 — Preparación futura de producción

- [ ] Elegir dominio y hosting compatible.
- [ ] Definir copias de seguridad y recuperación.
- [ ] Configurar SMTP real y pagos reales solo cuando corresponda.
- [ ] Revisar privacidad, términos, cambios y devoluciones.
- [ ] Ejecutar checklist de seguridad antes de publicar.

**Salida:** checklist de publicación, sin publicar automáticamente.
