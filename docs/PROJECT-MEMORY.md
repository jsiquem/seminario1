# Memoria del proyecto Case & Tech

## Propósito
Registrar decisiones, avances, problemas, alertas y cambios relevantes para que el proyecto pueda retomarse sin perder contexto.

## Estado actual

- **Fecha de inicio de planificación:** 2026-11-10.
- **Entorno:** Windows + Docker Desktop + WSL 2.
- **Proyecto:** `case-tech-local`.
- **Stack:** WordPress, WooCommerce, MariaDB, Mailpit y Docker Compose.
- **Tipo de entrega:** tienda local para venta de cases, fundas y pantallas en Guatemala.
- **Estado:** entorno y WordPress/WooCommerce operativos según la administración mostrada; instalación ya completada; identidad visual en desarrollo; 12 imágenes demo listas para carga; catálogo y tema propio pendientes de integración.

## Decisiones confirmadas

| Fecha | Decisión | Motivo |
|---|---|---|
| 2026-11-10 | Ejecutar el entorno con Docker Desktop sobre WSL 2 | Es la opción disponible y compatible en Windows |
| 2026-11-10 | Mantener MariaDB | WordPress/WooCommerce dependen de compatibilidad MySQL/MariaDB |
| 2026-11-10 | Construir la tienda desde cero sobre la base existente | El proyecto no contiene una base de datos restaurada con el sitio original |
| 2026-11-10 | Usar arquitectura híbrida | WordPress administra datos; tema y plugin contienen código propio |
| 2026-11-10 | Nombre comercial Case-Tech | Identidad definida para la tienda |
| 2026-11-10 | Vender cases, fundas y micas/pantallas protectoras sin instalación | Delimita la oferta inicial de e-commerce |
| 2026-11-10 | Marcas iniciales: iPhone/Apple, Samsung, Huawei y Xiaomi | Enfoque en marcas comerciales indicadas para Guatemala |
| 2026-11-10 | Zona general para toda Guatemala con envío de Q25.00 | Configuración logística inicial simple |
| 2026-11-10 | Transferencia bancaria directa | Método de pago inicial confirmado |
| 2026-11-10 | Inspiración de experiencia: Ecommerce Website Design de Figma | Referencia de navegación y estructura, no copia visual |

## Alertas y faltantes

- [ ] Aprobar el logotipo de Case-Tech (ya existe un concepto generado en `wordpress/wp-content/themes/case-tech/case-tech-logo-concept.png`).
- [x] Definir tres modelos demo por marca para pruebas.
- [ ] Escribir instrucciones de transferencia de prueba.
- [ ] Definir paleta, tipografías y tono visual.
- [x] Crear los 12 recursos visuales demo de productos; falta cargarlos como medios/productos en WooCommerce.
- [x] Configurar/instalar la base de WooCommerce; falta validar moneda, BACS, envío y SMTP de Mailpit.
- [x] Revisar el estado local: el tema y plugin propios no contienen código implementado en esta copia, aunque WordPress/WooCommerce sí están operativos en los volúmenes activos.
- [x] Crear primero la primera versión de las páginas y la experiencia comercial de Case-Tech antes de continuar con la carga profunda del catálogo.

## Registro de cambios

### 2026-11-10

- Se confirmó que el proyecto base es una instalación técnica, no un backup completo de una tienda ya creada.
- Se eligió construir la tienda desde cero.
- Se decidió usar WordPress/WooCommerce para la administración y código propio para interfaz y funcionalidades específicas.
- Se creó el mapa de capacidades, la especificación inicial y este archivo de memoria.
- Se preparó una skill reutilizable del proyecto: `case-tech-woocommerce`.
- Se confirmó el nombre Case-Tech, las marcas iniciales, la cobertura nacional, el envío plano de Q25.00 y la transferencia bancaria.
- Se registró como referencia visual el archivo público de Figma indicado por la persona responsable del proyecto.
- Se definieron 12 productos demo: tres modelos para iPhone/Apple, Samsung, Huawei y Xiaomi.
- Se usarán BAM `0000-000000-0` y WhatsApp `+502 0000-0000` únicamente como datos ficticios de pruebas locales.

### 2026-11-10 — Retoma de implementación visual

- **Cambio:** se confirmó mediante la pantalla de administración que WordPress 7.1.4, WooCommerce y las páginas base Cart, Checkout, My account y Shop ya están instaladas y operativas.
- **Motivo:** corregir el diagnóstico inicial que solo pudo comprobar que el ejecutable `docker` no está disponible en el PATH de esta sesión.
- **Archivos o pantallas afectadas:** administración de WordPress; `wordpress/wp-content/themes/case-tech/`.
- **Prueba realizada:** se verificó la existencia de 12 imágenes `demo-*-case.jpg`, todas generadas a 1920×1920 px, además del concepto de logotipo.
- **Resultado:** activos visuales completos para los 12 modelos demo; no se reinstalará Docker ni WordPress.
- **Alertas/faltantes:** falta ejecutar el importador dentro del contenedor `wpcli`; después deben validarse la carga de medios/productos, la implementación del tema/plugin propio y la configuración final de checkout.
- **Siguiente paso:** ejecutar `wp eval-file /var/www/html/wp-content/plugins/case-tech-custom/import-demo-products.php` desde el servicio `wpcli` y validar los 12 productos en WooCommerce.

### 2026-11-10 — Importador de catálogo demo

- **Cambio:** se creó un importador idempotente en `tasks/import-demo-products.php` y se copió a `wordpress/wp-content/plugins/case-tech-custom/import-demo-products.php`, ruta visible para el servicio `wpcli`.
- **Motivo:** cargar los 12 productos demo con categorías por marca, SKU, precio GTQ, inventario de 10 unidades, metadatos de marca/modelo e imágenes generadas sin mover ni sobrescribir los archivos fuente.
- **Prueba realizada:** se verificó la presencia de los dos archivos del importador y se corrigió la carga de imágenes para usar copias temporales.
- **Resultado:** integración preparada para ejecución segura y repetible; todavía no se ejecutó porque la sesión actual no expone el ejecutable `docker` ni comparte la sesión autenticada del navegador de escritorio.
- **Alertas/faltantes:** ejecutar el comando WP-CLI en el entorno Docker activo y revisar el resultado en Productos.
- **Siguiente paso:** ejecutar el importador y comprobar nombres, SKU, precios, stock, categorías e imágenes desde el panel.

### 2026-11-10 — Descripciones comerciales del catálogo

- **Cambio:** se agregaron descripciones largas y resumidas en español para los 12 productos demo dentro del importador.
- **Motivo:** mejorar la confianza y realismo de la tienda explicando compatibilidad, protección diaria, acabado, acceso a controles y nota de verificación del modelo.
- **Archivos afectados:** `tasks/import-demo-products.php` y `wordpress/wp-content/plugins/case-tech-custom/import-demo-products.php`.
- **Resultado:** al ejecutar nuevamente el importador se actualizarán las descripciones existentes sin duplicar productos ni imágenes.
- **Siguiente paso:** ejecutar el importador desde `wpcli` y revisar una ficha de producto desde la tienda.

### 2026-11-10 — Cambio de orden: experiencia antes del catálogo

- **Cambio:** se decidió priorizar la creación de una página profesional y las páginas base de la tienda antes de continuar con la integración detallada de productos.
- **Motivo:** evitar presentar un catálogo aislado sin identidad, navegación, propuesta de valor ni contexto comercial.
- **Dirección visual provisional:** base grafito/crema, acento naranja del logotipo, composición editorial tecnológica, lenguaje claro para Guatemala y diseño mobile-first.
- **Alcance inmediato:** inicio comercial, navegación, categorías, marcas, beneficios de compra, envío, CTA hacia la tienda y estructura preparada para WooCommerce.
- **Resultado esperado:** el visitante entenderá qué vende Case-Tech, para quién es y cómo comenzar a comprar antes de entrar al catálogo.
- **Siguiente paso:** implementar el tema `case-tech`, activar la página de inicio y después crear/conectar las páginas Tienda, Carrito, Checkout, Mi cuenta, Contacto y FAQ.

### 2026-11-10 — Primera versión de página profesional

- **Cambio:** se construyó la primera versión del tema propio `case-tech` con `style.css`, `functions.php`, cabecera, pie, portada, páginas internas, archivo y plantilla WooCommerce.
- **Dirección visual:** estética editorial tecnológica con base grafito, crema y naranja; tipografía display serif de alto contraste; composición mobile-first; secciones de marcas, beneficios, selección demo y CTA.
- **Archivos afectados:** `wordpress/wp-content/themes/case-tech/`.
- **Resultado:** la instalación ya tiene una portada comercial lista para activar en WordPress y conectada a los enlaces de tienda/cart cuando WooCommerce los tenga asignados.
- **Pendientes:** activar el tema desde Apariencia, asignar la página de inicio, crear páginas Contacto/FAQ/Políticas, revisar el catálogo y validar responsive en el navegador.
- **Siguiente paso:** activar el tema y comprobar la portada en `http://localhost:8080/` antes de seguir con productos.

### 2026-11-10 — Segunda pasada de diseño y responsive

- **Cambio:** se aplicó una segunda pasada de diseño al tema existente siguiendo un enfoque de rediseño preserve.
- **Mejoras:** menú móvil funcional con `aria-expanded`, cierre con Escape, estados de foco visibles, animación de entrada respetuosa de `prefers-reduced-motion`, hover refinado en botones/tarjetas, mejor navegación responsive y breakpoint adicional para pantallas pequeñas.
- **Archivos afectados:** `style.css`, `functions.php`, `header.php` y `assets/js/menu.js` dentro de `wordpress/wp-content/themes/case-tech/`.
- **Contratos preservados:** productos, rutas, slugs, logo, enlaces principales y compatibilidad con WooCommerce.
- **Siguiente paso:** activar el tema en WordPress y revisar la portada en móvil y escritorio.

## Plantilla para próximas entradas

```markdown
### YYYY-MM-DD — Título del cambio

- **Cambio:**
- **Motivo:**
- **Archivos o pantallas afectadas:**
- **Prueba realizada:**
- **Resultado:**
- **Alertas/faltantes:**
- **Siguiente paso:**
```


### 2026-11-10 — Primera pasada aprobada de UX y WooCommerce

- **Cambio:** se añadió una función reutilizable de búsqueda de productos y se integró en la cabecera y en el hero de la portada.
- **Motivo:** priorizar la búsqueda por marca/modelo definida en la especificación y hacer visible el acceso al catálogo desde móvil y escritorio.
- **Archivos afectados:** `wordpress/wp-content/themes/case-tech/functions.php`, `header.php`, `front-page.php` y `style.css`.
- **Mejoras visuales:** controles de búsqueda con el sistema grafito/crema/naranja; estilos base para formularios, botones, avisos y tarjetas de productos WooCommerce.
- **Contratos preservados:** arquitectura WordPress/WooCommerce, rutas existentes, logo conceptual, catálogo editable desde administración y responsive mobile-first.
- **Prueba realizada:** inspección de archivos modificados y comprobación de que las funciones, la búsqueda y las reglas CSS están presentes. PHP y Docker no están disponibles en el PATH de esta sesión, por lo que falta la validación renderizada.
- **Resultado:** primera pasada de interfaz aplicada al tema propio; no se modificó el núcleo de WordPress/WooCommerce ni se ejecutó ninguna operación destructiva sobre volúmenes.
- **Alertas/faltantes:** validar el sitio activo en `http://localhost:8080`, revisar el formulario de búsqueda en navegador, conectar filtros reales por marca/modelo y completar páginas de Contacto, FAQ y políticas.
- **Siguiente paso:** activar/revisar el tema en el entorno Docker y ejecutar la revisión responsive en 375, 768, 1280 y 1920 px.


### 2026-11-11 — Corrección del filtrado por marca

- **Cambio:** las tarjetas Apple, Samsung, Huawei y Xiaomi de la portada dejaron de apuntar a la tienda general y ahora usan la URL de su categoría hija real dentro de `product_cat`.
- **Motivo:** seleccionar Samsung mostraba todo el catálogo porque las tarjetas compartían `$shop_url`; la categoría de marca ya existía en el importador, pero no estaba conectada a la interfaz.
- **Archivos afectados:** `wordpress/wp-content/themes/case-tech/functions.php` y `wordpress/wp-content/themes/case-tech/front-page.php`.
- **Resultado esperado:** Samsung muestra únicamente sus tres modelos demo; el mismo patrón aplica a las otras marcas.
- **Prueba realizada:** se confirmó que las tarjetas usan `brand_urls`, que la función busca el término real de `product_cat` y que ya no existe un enlace `brand-tile` hacia `$shop_url`.
- **Validación pendiente:** probar las URLs de categoría dentro de WordPress/WooCommerce activo y confirmar que los enlaces permanentes estén configurados como "Nombre de la entrada".
- **Siguientes mejoras comerciales:** filtros por modelo y disponibilidad, ficha de producto orientada a compatibilidad, señales de confianza, carrito persistente y checkout local con envío Q25.00 y transferencia bancaria demo.


### 2026-11-11 — Inicio del plan profesional: prioridad 1

- **Cambio:** se creó `docs/PLAN-PROFESIONAL.md` con siete prioridades ordenadas para convertir Case-Tech en una tienda profesional de ventas.
- **Prioridad activa:** catálogo y compatibilidad.
- **Implementación:** se creó el plugin principal `case-tech-custom.php` con filtros por marca, modelo compatible y disponibilidad; se añadió un panel de filtros a la plantilla WooCommerce y estilos responsive.
- **Corrección de datos:** el importador ahora crea términos del atributo `pa_modelo-compatible` y los asigna a cada producto demo.
- **Acción requerida:** activar el plugin propio y ejecutar nuevamente el importador demo dentro de `wpcli` para aplicar los atributos a los productos existentes.
- **Validación pendiente:** comprobar el flujo `Inicio → Samsung → Galaxy A15` y confirmar que solo devuelve productos compatibles.
- **Siguiente paso:** terminar la validación de prioridad 1 antes de comenzar las fichas de producto orientadas a ventas.


### 2026-11-11 — Inicio de prioridad 2: ficha de producto orientada a ventas

- **Cambio:** se añadieron bloques comerciales a las fichas individuales de WooCommerce mediante el plugin propio.
- **Motivo:** mostrar compatibilidad, disponibilidad, envío, forma de pago y advertencia del modelo antes de agregar al carrito.
- **Archivos afectados:** `wordpress/wp-content/plugins/case-tech-custom/case-tech-custom.php` y `wordpress/wp-content/themes/case-tech/style.css`; el estado se actualizó en `docs/PLAN-PROFESIONAL.md`.
- **Resultado esperado:** cada producto debe mostrar el modelo compatible, estado de stock, envío nacional demo de Q25.00, transferencia bancaria y una señal de confianza.
- **Prueba realizada:** inspección estática de hooks, funciones, clases CSS y archivos modificados. El entorno Docker/browser continúa pendiente para comprobar una ficha real.
- **Alertas/faltantes:** validar que el plugin esté activo, que el importador haya asignado `pa_modelo-compatible` y que los datos demo se distingan antes de presentar el sitio fuera del entorno académico.
- **Siguiente paso:** abrir un producto real, revisar la ficha en móvil/escritorio y corregir cualquier problema visual antes de avanzar a confianza y contenido comercial.


### 2026-11-11 — Inicio de prioridad 3: confianza y contenido comercial

- **Cambio:** se preparó un importador idempotente para crear Contacto, Preguntas frecuentes, Envíos y pagos, y Políticas como páginas administrables de WordPress.
- **Motivo:** una tienda profesional necesita resolver dudas, explicar cobertura y separar claramente los datos demo de la operación real.
- **Archivos afectados:** `wordpress/wp-content/plugins/case-tech-custom/create-store-pages.php`, `functions.php`, `footer.php` y `style.css`; el plan de trabajo fue actualizado con casillas de seguimiento.
- **Resultado esperado:** las páginas quedarán enlazadas desde el footer y podrán editarse desde WordPress sin modificar código.
- **Prueba realizada:** verificación estática del importador, helper de URLs, enlaces del footer y estilos institucionales. Docker continúa sin estar visible en esta sesión.
- **Acción pendiente:** activar el plugin, ejecutar `create-store-pages.php` dentro de `wpcli` y revisar las páginas en móvil y escritorio.
- **Siguiente paso:** cerrar la validación de prioridad 3 y luego comenzar la prioridad 4: carrito y checkout profesional.


### 2026-11-11 — Inicio de prioridad 4: carrito y checkout profesional

- **Cambio:** se creó un configurador idempotente para Guatemala, GTQ, transferencia bancaria directa demo y zona de envío nacional con tarifa plana de Q25.00.
- **Motivo:** centralizar la configuración comercial local y evitar ajustes manuales inconsistentes entre sesiones.
- **Archivos afectados:** `wordpress/wp-content/plugins/case-tech-custom/configure-store.php`, `case-tech-custom.php`, `style.css` y `docs/PLAN-PROFESIONAL.md`.
- **Mejora adicional:** se añadió un aviso visible en checkout para explicar envío, transferencia y naturaleza ficticia de los datos bancarios.
- **Prueba realizada:** inspección estática del script, hooks y estilos. Docker continúa sin estar disponible en el PATH de esta sesión, por lo que la configuración todavía no se ha ejecutado en WordPress.
- **Acción pendiente:** activar el plugin y ejecutar `configure-store.php`; después probar carrito, checkout, pedido creado y correo en Mailpit.
- **Siguiente paso:** cerrar las validaciones de prioridades 3 y 4 antes de avanzar a conversión y recompra.


### 2026-11-11 — Corrección de envío no disponible para Guatemala

- **Problema observado:** el checkout mostraba “Lo siento, no enviamos pedidos al país seleccionado” aunque el país fuera Guatemala.
- **Causa probable:** la zona no tenía guardada explícitamente la ubicación `GT` o el método Flat Rate estaba deshabilitado/sin instancia activa.
- **Corrección:** `configure-store.php` ahora verifica la zona Guatemala, añade la ubicación de país `GT` si falta y guarda la instancia Flat Rate con estado activo, título “Envío nacional” y costo `25`.
- **Validación pendiente:** volver a ejecutar el configurador y confirmar en checkout que aparezca `Envío nacional — Q25.00`.
- **Siguiente paso:** probar un pedido completo con transferencia bancaria demo después de confirmar la tarifa.


### 2026-11-11 — Departamentos de Guatemala en checkout

- **Decisión:** mantener `Guatemala` como país, porque WooCommerce necesita el país para calcular zonas y pedidos, y usar los 22 departamentos como estados/regiones seleccionables.
- **Cambio:** se añadió el filtro `woocommerce_states` con Alta Verapaz, Baja Verapaz, Chimaltenango, Chiquimula, El Progreso, Escuintla, Guatemala, Huehuetenango, Izabal, Jalapa, Jutiapa, Petén, Quetzaltenango, Quiché, Retalhuleu, Sacatepéquez, San Marcos, Santa Rosa, Sololá, Suchitepéquez, Totonicapán y Zacapa.
- **Configuración:** `configure-store.php` añade ubicaciones estatales `GT:<departamento>` a la zona Guatemala y mantiene el método Flat Rate activo a Q25.00.
- **Validación pendiente:** ejecutar nuevamente el configurador, refrescar checkout y confirmar una tarifa para varios departamentos.
- **Nota:** no se deben sustituir países por departamentos; la estructura correcta es país Guatemala y departamento como segundo nivel.


### 2026-11-11 — Restricción del selector de países

- **Problema:** el checkout mostraba o permitía desplegar países que no forman parte de la cobertura de Case-Tech.
- **Decisión:** conservar el campo país por compatibilidad con WooCommerce, pero limitarlo a una única opción: Guatemala (`GT`).
- **Cambio:** se añadieron filtros `woocommerce_countries_allowed_countries` y `woocommerce_countries_shipping_countries`, además de las opciones correctas `woocommerce_allowed_countries`, `woocommerce_specific_allowed_countries`, `woocommerce_ship_to_countries` y `woocommerce_specific_ship_to_countries`.
- **Resultado esperado:** el checkout mostrará Guatemala sin lista mundial de países; el departamento seguirá mostrando los 22 departamentos configurados.
- **Validación pendiente:** ejecutar el configurador, recargar el checkout y abrir los selectores de país y departamento.


### 2026-11-11 — Confirmación de checkout local

- **Confirmación:** se validó la corrección del checkout para trabajar únicamente con Guatemala y sus departamentos, sin mostrar el listado mundial de países.
- **Resultado:** el envío nacional de Q25.00 queda registrado como validado para el flujo local.
- **Plan:** se marcaron como completadas las comprobaciones de país, departamentos y tarifa dentro de la prioridad 4.
- **Pendientes restantes:** completar un pedido de prueba, confirmar el estado del pedido y revisar los correos en Mailpit; también ejecutar y validar las páginas institucionales de la prioridad 3 si aún no se han creado en WordPress.


### 2026-11-11 — Corrección de filtros que regresaban a la portada

- **Problema:** al aplicar marca, modelo o disponibilidad desde el catálogo, la navegación terminaba en la pantalla principal.
- **Causa probable:** el formulario estaba usando la URL contextual de la consulta actual como destino.
- **Corrección:** el formulario y el enlace `Limpiar` ahora usan `case_tech_shop_url()` como destino estable del catálogo y conservan los parámetros GET de filtros.
- **Validación pendiente:** probar `Tienda → Samsung → Galaxy A15 → Aplicar filtros` y confirmar que la URL permanezca en la tienda y solo muestre productos compatibles.


### 2026-11-11 — Auditoría de continuidad

- **Cambio:** se revisó el plan profesional contra el tema, el plugin, los importadores y los activos presentes en el proyecto.
- **Resultado:** la base visual, los filtros, las fichas de producto, `create-store-pages.php` y `configure-store.php` están preparados; el logo y las 12 imágenes demo existen en el tema.
- **Corrección realizada:** se añadió el punto y coma faltante en `case_tech_shop_url()` dentro de `functions.php`.
- **Validación pendiente:** ejecutar los importadores dentro de `wpcli` y comprobar el renderizado real en navegador.
- **Siguiente paso:** ejecutar `create-store-pages.php` y `configure-store.php`; después probar catálogo, ficha, carrito, checkout y Mailpit.


### 2026-11-11 — Corrección del filtro que redirigía a Inicio

- **Problema:** al pulsar “Aplicar filtros”, el formulario regresaba a la portada en lugar de permanecer en el catálogo.
- **Causa probable:** `wc_get_page_permalink('shop')` estaba devolviendo la URL raíz cuando la página de tienda no estaba asignada o sus enlaces permanentes aún no estaban actualizados.
- **Corrección:** `case_tech_shop_url()` ahora valida el ID real de la página Tienda, usa el archivo de productos como respaldo, busca una página `/shop/` y solo al final usa esa ruta fija.
- **Validación pendiente:** guardar Enlaces permanentes, probar marca/modelo/disponibilidad y confirmar que la URL permanezca en la tienda.


### 2026-11-11 — Segunda corrección del destino del filtro

- **Evidencia:** la captura del panel confirma que existe y está publicada la página `Shop — Página de la tienda`.
- **Problema:** la corrección basada solo en `case_tech_shop_url()` no resolvió el redireccionamiento a Inicio.
- **Nueva corrección:** se añadió `case_tech_catalog_form_url()` para enviar el formulario a la categoría actual cuando existe o a la página Shop explícita, eliminando únicamente los parámetros anteriores del filtro.
- **Archivos:** `wordpress/wp-content/themes/case-tech/functions.php` y `wordpress/wp-content/plugins/case-tech-custom/case-tech-custom.php`.
- **Validación:** recargar el sitio sin caché y probar `Shop → filtros → Aplicar filtros`; la URL ya no debería ser la raíz.


### 2026-11-11 — Corrección del 404 en el filtro

- **Problema observado:** después de la corrección anterior, aplicar el filtro dejó de llevar a Inicio, pero terminó en `Not Found` al solicitar `/shop/`.
- **Alerta:** la ruta amigable `/shop/` no está siendo resuelta por Apache/WordPress en el entorno actual.
- **Corrección:** el formulario de filtros ahora usa la página Shop mediante `?page_id=ID`, evitando depender temporalmente de los enlaces permanentes o de la ruta `/shop/`.
- **Validación pendiente:** recargar con `Ctrl + F5` y probar el filtro. Si funciona, después se pueden reparar los enlaces permanentes para recuperar URLs amigables.


### 2026-11-11 — Corrección de consulta y dependencia marca-modelo

- **Problema 1:** la URL observada perdió `page_id` y quedó como `/?case_brand=apple&case_model=iphone-11`, por lo que WordPress cargó la portada.
- **Causa:** la resolución del ID de Shop no cubría todas las formas en que WooCommerce guarda la página.
- **Corrección:** se creó `case_tech_shop_page_id()` con fallback por opción de WooCommerce, API de WooCommerce, slug `shop` y título `Shop`; el formulario usa ese ID directamente.
- **Problema 2:** el selector permitía combinar Apple con modelos Samsung.
- **Corrección:** el selector de `Modelo compatible` ahora consulta únicamente los términos asociados a productos de la marca elegida. La consulta de resultados mantiene la relación `AND` entre marca y modelo.
- **Validación pendiente:** recargar el catálogo, seleccionar Apple y confirmar que solo aparezcan iPhone 11, 13 y 15; después seleccionar Samsung y confirmar que aparezcan Galaxy A15, A55 y S24.


### 2026-11-11 — Plugin autocontenido para filtros

- **Hallazgo:** el formulario del plugin dependía de funciones del tema (`case_tech_catalog_form_url()` y `case_tech_shop_url()`), lo que podía generar una URL raíz o una ruta `/shop/` inválida si el tema activo no coincidía o WooCommerce no resolvía el enlace.
- **Corrección:** `case-tech-custom.php` ahora resuelve internamente el ID de Shop y construye `?page_id=ID`; también reconoce esa página en `pre_get_posts`.
- **Mejora de UX:** el selector de modelos incluye asociación por marca y JavaScript para ocultar modelos incompatibles inmediatamente al cambiar Apple, Samsung, Huawei o Xiaomi.
- **Validación pendiente:** reiniciar el contenedor de WordPress, recargar sin caché y probar Apple/iPhone 11 y Samsung/Galaxy A15.


### 2026-11-11 — Instalación reproducible y GitHub Codespaces

- **Cambio:** se creó `docs/INSTALL.md` con instrucciones para Windows, Linux y GitHub Codespaces.
- **Cambio:** se creó `.devcontainer/devcontainer.json` con Docker-in-Docker, extensiones de Docker/PHP/YAML y reenvío de puertos 8080 y 8025.
- **Decisión:** mantener `.env.example` versionado y exigir que cada entorno cree su propio `.env` local.
- **Validación:** el JSON de Codespaces fue parseado correctamente y la guía quedó enlazada desde `README.md`.


### 2026-11-11 — Nueva PC sin Docker y plan unificado

- **Cambio:** se reescribió `docs/PLAN-PROFESIONAL.md` como plan único, con Prioridad 0 (entorno con WordPress Playground) y Prioridad 0.5 (limpieza técnica). `tasks/plan.md` quedó marcado como histórico.
- **Motivo:** la PC actual (Windows 10, 8 GB de RAM, unos 700 MB libres) no puede usar Docker y no tiene PHP; sí tiene Node 24. Playground ejecuta WordPress con PHP en WebAssembly y SQLite sin instalar nada.
- **Hallazgo:** `C:\Users\CONTABILIDAD\Pictures` es de solo lectura para el usuario (solo los administradores escriben). El proyecto se copió a `C:\Users\CONTABILIDAD\Documents\ecomerce\seminario1`, que es la ubicación de trabajo en esta PC.
- **Hallazgos técnicos:** importador duplicado y distinto en `tasks/`; dos funciones duplicadas para la página Shop (tema y plugin); páginas sobrantes en borrador; falta el filtro por tipo de producto y un producto variable exigidos en `SPEC.md`.
- **Alertas/faltantes:** la base de datos de la tienda original está en el volumen Docker de la otra PC; en esta PC se reconstruye con los importadores idempotentes.
- **Siguiente paso:** crear `playground/blueprint.json` y `npm run dev`, levantar la tienda y validar las prioridades 1 a 3 en el navegador.


### 2026-11-11 — Prioridad 0: entorno Playground operativo

- **Cambio:** se crearon `playground/blueprint.json` y `package.json` (`npm run dev`), se documentó la sección "Windows sin Docker" en `INSTALL.md` y se añadió `node_modules/` a `.gitignore`.
- **Prueba realizada:** al arrancar se instaló WooCommerce en español, se activaron el tema y el plugin y corrieron los 3 importadores. La API pública `wc/store/v1/products` devuelve 12 productos con SKU, precio GTQ, categoría de marca, atributo `Modelo compatible`, imagen y stock. Hay 4 páginas institucionales. Inicio, Shop, ficha, carrito y Contacto responden 200 sin errores PHP visibles.
- **Hallazgo y corrección (filtros):** el formulario usaba `action="/?page_id=5"` con `method="get"`. Los navegadores descartan esa query string, así que la URL quedaba en `/?case_brand=…` y cargaba la portada. Ahora la acción es el permalink de Shop y cualquier query se reenvía como campos ocultos (`case_tech_custom_form_hidden_args()`).
- **Corrección:** los filtros también se muestran cuando no hay resultados (`woocommerce_no_products_found`).
- **Corrección:** `absint( wc_get_page_id() )` convertía -1 en 1 y apuntaba a la entrada con ID 1. Se cambió a `max( 0, … )`. Se eliminó `get_page_by_title()`, que está obsoleta.
- **Limpieza:** el tema delega en `case_tech_custom_shop_page_id()` del plugin; se eliminó la función sin uso `case_tech_catalog_form_url()` del tema y la copia duplicada `tasks/import-demo-products.php`.
- **Validación de filtros:** Samsung + Galaxy A15 devuelve 1 producto; Apple devuelve iPhone 11, 13 y 15; Apple + Galaxy A15 devuelve 0 con los filtros visibles.
- **Alertas/faltantes:** los productos relacionados mezclan marcas (P2). Falta borrar a mano las páginas sobrantes en la instalación Docker. En Docker hay que volver a guardar Ajustes → Enlaces permanentes para que `/shop/` funcione.
- **Siguiente paso:** revisión visual con capturas (P1 a P3) y productos relacionados por marca.
