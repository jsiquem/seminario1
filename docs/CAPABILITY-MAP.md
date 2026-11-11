# Mapa de capacidades: Case & Tech Guatemala

## Objetivo
Construir una tienda local de comercio electrónico para vender cases, fundas y pantallas de celulares de distintas marcas comerciales en Guatemala, usando la base existente de WordPress, WooCommerce, el tema `case-tech` y el plugin `case-tech-custom`.

## Capacidades

| ID | Responsabilidad | Depende de |
|---|---|---|
| catalogo | Marcas (iPhone/Apple, Samsung, Huawei y Xiaomi), categorías, productos, variaciones, precios e inventario | — |
| experiencia-tienda | Inicio, navegación, búsqueda, filtros, fichas y carrito | catalogo |
| checkout-local | Configuración de Guatemala, moneda GTQ, transferencia bancaria y zona general con envío de Q25.00 | catalogo |
| identidad-visual | Sistema visual, contenido comercial, confianza y responsive | — |
| administracion | Gestión de productos, pedidos, clientes y contenido desde WordPress | catalogo, checkout-local |
| calidad-seguridad | Pruebas, accesibilidad, rendimiento, copias y secretos | todas |

## Orden de construcción

1. `identidad-visual`
2. `catalogo`
3. `experiencia-tienda`
4. `checkout-local`
5. `administracion`
6. `calidad-seguridad`

## Decisión de arquitectura

Se usará un enfoque híbrido:

- **WordPress/WooCommerce:** productos, categorías, precios, inventario, pedidos, clientes, contenido editable y configuración comercial.
- **Código propio:** tema `case-tech` para la interfaz y plugin `case-tech-custom` para funcionalidades, campos, shortcodes, integraciones y reglas específicas.
- **No se sustituirá MariaDB:** es la base compatible con WordPress/WooCommerce en este entorno.

Esta separación permite que la persona administradora pueda operar la tienda desde WordPress sin editar código, mientras el código mantiene el diseño y la lógica reutilizable.
