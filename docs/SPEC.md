# Especificación: tienda Case & Tech Guatemala

## Estado
Borrador para revisión y aprobación. No iniciar la implementación de funcionalidades nuevas hasta validar esta especificación y sus preguntas abiertas.

## Objetivo
Crear una tienda web local, responsive y preparada para WooCommerce que permita vender cases, fundas y pantallas para celulares de distintas marcas comerciales en Guatemala. La tienda debe transmitir confianza, facilitar la búsqueda por marca/modelo y permitir administrar el catálogo y pedidos desde WordPress.

## Definición comercial confirmada

- **Nombre comercial:** Case-Tech.
- **Oferta:** cases y fundas para celulares, además de micas/pantallas protectoras como repuesto. No se ofrecerá instalación dentro del alcance inicial.
- **Marcas iniciales:** iPhone/Apple, Samsung, Huawei y Xiaomi. La lista podrá crecer después según el catálogo real.
- **Cobertura:** toda Guatemala, usando inicialmente una zona general de envío.
- **Envío:** tarifa plana de Q25.00.
- **Pago inicial:** transferencia bancaria directa.

La marca debe proyectar una tienda tecnológica accesible y confiable, con énfasis en compatibilidad por marca y modelo. El logotipo deberá representar un case de teléfono que integre visualmente la palabra “tech”; se mantendrá como una pieza gráfica propia y no se copiarán elementos protegidos de terceros.

## Referencia de experiencia y estilo

Se usará como inspiración de estructura y experiencia el archivo público [Ecommerce Website Design de iCoderz Solutions](https://www.figma.com/es-la/comunidad/file/1184498378829556800/ecommerce-website-design). La referencia orienta una navegación clara, fichas de producto detalladas, compra sencilla, señales de confianza, seguimiento del pedido y recomendaciones. Case-Tech tendrá un sistema visual propio, adaptado a productos móviles, precios en quetzales y compradores de Guatemala.

## Catálogo de demostración

Para las primeras pruebas se cargarán tres modelos por marca. Son datos demo y podrán sustituirse sin cambiar la estructura del sitio:

- **iPhone/Apple:** iPhone 11, iPhone 13 y iPhone 15.
- **Samsung:** Galaxy A15, Galaxy A55 y Galaxy S24.
- **Huawei:** Y9a, nova 11i y P30 lite.
- **Xiaomi:** Redmi 13C, Redmi Note 13 y POCO X6.

Cada producto tendrá categoría, marca, modelo compatible, tipo de producto, precio demo, SKU demo, inventario demo e imagen reemplazable.

## Datos ficticios para pruebas

La configuración local usará una cuenta bancaria ficticia de BAM, por ejemplo `BAM — cuenta de prueba 0000-000000-0`, y un contacto de WhatsApp claramente marcado como demo: `+502 0000-0000`. Estos datos no representan cuentas reales y nunca deben usarse en producción.

## Usuarios

- **Comprador:** visita desde móvil o computadora, busca por marca y modelo, consulta precio/disponibilidad, añade productos al carrito y completa un pedido.
- **Administrador:** gestiona catálogo, inventario, pedidos, contenido y configuración comercial desde WordPress.

## Alcance inicial

- Página de inicio comercial.
- Catálogo con categorías y marcas.
- Búsqueda y filtros por marca, modelo, tipo de producto y disponibilidad.
- Producto simple y producto variable cuando aplique.
- Carrito y checkout de WooCommerce.
- Moneda GTQ y configuración para Guatemala.
- Transferencia bancaria directa como método manual inicial.
- Zonas y tarifas de envío de prueba.
- Página de contacto, preguntas frecuentes y políticas básicas.
- Zona general de envío para Guatemala con tarifa de Q25.00.
- Recomendaciones o productos relacionados cuando WooCommerce lo permita.
- Diseño responsive, accesible y orientado primero a móvil.
- Mailpit para pruebas de correo local.

## Fuera del alcance inicial

- Pagos reales o claves de producción.
- Integración real con Meta, WhatsApp, Recurrente u otros proveedores.
- Publicación en internet.
- Aplicación móvil nativa.
- Sustitución de MariaDB por PostgreSQL.
- Automatizaciones avanzadas de inventario externo.

## Arquitectura y tecnología

- WordPress ejecutado mediante Docker Compose.
- WooCommerce como motor de catálogo, carrito, checkout y pedidos.
- MariaDB como base de datos compatible con WordPress.
- Tema propio: `wordpress/wp-content/themes/case-tech`.
- Plugin propio: `wordpress/wp-content/plugins/case-tech-custom`.
- Código editable desde VS Code.
- Datos comerciales editables desde el panel de WordPress.

## Comandos del entorno

```powershell
# Desde la carpeta case-tech-local
docker compose up -d --build
docker compose ps
docker compose logs -f wordpress
docker compose stop
docker compose down
```

No ejecutar `docker compose down -v` salvo que se quiera borrar la base de datos y los volúmenes de WordPress.

## Estructura relevante

```text
compose.yaml
.env
wordpress/
  wp-content/
    themes/case-tech/       # presentación y plantillas
    plugins/case-tech-custom/ # lógica y extensiones propias
docs/
  CAPABILITY-MAP.md
  SPEC.md
  PROJECT-MEMORY.md
tasks/
  plan.md
```

## Principios de implementación

1. Mantener contenido y datos de negocio fuera del código cuando deban ser editables por administración.
2. Usar el tema para presentación y el plugin para lógica que debe sobrevivir a un cambio de tema.
3. Evitar editar archivos del núcleo de WordPress, WooCommerce o imágenes base de Docker.
4. No guardar contraseñas, claves de API, datos reales de clientes ni backups en Git.
5. Diseñar primero para móvil y validar teclado, contraste, etiquetas y mensajes de error.
6. Usar GTQ y lenguaje comercial comprensible para Guatemala.
7. No conectar servicios de producción hasta tener pruebas, copias y configuración segura.

## Estrategia de pruebas

- Verificación manual del flujo: inicio → categoría → producto → carrito → checkout.
- Pruebas responsive en móvil, tablet y escritorio.
- Pruebas de búsqueda, filtros, variaciones, inventario y precio.
- Comprobación de que el pedido de prueba se registra correctamente.
- Revisión de correos en Mailpit.
- Revisión de logs de WordPress y Docker ante errores.
- Revisión básica de accesibilidad: teclado, foco visible, textos alternativos y contraste.

## Criterios de éxito inicial

- WordPress, WooCommerce, tema y plugin propio cargan sin errores fatales.
- La página de inicio comunica qué se vende y cómo comprar.
- El comprador puede encontrar un producto por marca y modelo.
- El comprador puede agregar una variación al carrito y crear un pedido de prueba.
- El administrador puede modificar un producto sin editar código.
- La interfaz funciona correctamente en móvil y escritorio.
- No se usan credenciales reales ni integraciones de producción.

## Límites

### Siempre hacer

- Registrar decisiones y cambios en `docs/PROJECT-MEMORY.md`.
- Probar cada capacidad antes de avanzar a la siguiente.
- Mantener separación entre tema, plugin y configuración de WordPress.
- Mostrar explícitamente errores, alertas y faltantes.

### Preguntar antes

- Cambiar la arquitectura o sustituir WooCommerce.
- Añadir dependencias externas o servicios de pago.
- Cambiar el esquema de datos o eliminar volúmenes.
- Publicar el sitio o conectar claves reales.
- Cambiar la identidad visual aprobada.

### Nunca hacer

- Cometer secretos o credenciales.
- Ejecutar `docker compose down -v` sin autorización explícita.
- Editar el núcleo de WordPress/WooCommerce para resolver una necesidad del proyecto.
- Usar datos reales de clientes en el entorno local.

## Preguntas abiertas

1. ¿Cuál será el primer listado de modelos compatibles por cada marca?
2. ¿Cuál será el número de cuenta y el texto de instrucciones para la transferencia de prueba?
3. ¿Se desea integrar WhatsApp en una fase posterior?
4. ¿Qué colores y tono de comunicación debe tener la marca además del concepto del logotipo?
