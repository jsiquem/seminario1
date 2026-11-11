# CASE & TECH — entorno local de WooCommerce

Para instalar el proyecto en otra PC o ejecutarlo en GitHub Codespaces, consulta [docs/INSTALL.md](docs/INSTALL.md).

## Requisitos
- Docker Engine + Docker Compose v2 (o Docker Desktop)
- Git, 4-8 GB de RAM disponible (recomendado)
- Internet para obtener imágenes de Docker y descargar plugins

## Arranque
```bash
cp .env.example .env
# Editar .env, sustituir ambas contraseñas por valores aleatorios distintos.
docker compose up -d --build
```
Abre http://localhost:8080, elige español, y completa la instalación web de WordPress (el usuario administrador debe ser único y su clave segura).

## WooCommerce por WP-CLI
Una vez completada la instalación web de WordPress:
```bash
docker compose run --rm wpcli wp plugin install woocommerce --activate
```
También puedes instalar WooCommerce desde **Plugins > Añadir plugin**.

## Configuración inicial de tienda
- WooCommerce > Ajustes > General: país Guatemala y moneda GTQ.
- WooCommerce > Ajustes > Pagos: activar **Transferencia bancaria directa (BACS)** como opción manual.
- WooCommerce > Ajustes > Envío: crear zonas Guatemala y tarifas de prueba.
- Añadir productos variables y gestionar inventario por variación.
- Permalinks: seleccionar "Nombre de la entrada".
- Recurrente: **no conectar claves reales de producción** hasta tener entorno seguro y pruebas adecuadas.

## Emails de prueba
Mailpit está accesible en http://localhost:8025, pero WordPress no enviará automáticamente a Mailpit sin configurar SMTP.
Instala en WordPress un plugin SMTP de tu elección y configura:
- SMTP host: `mailpit`
- Port: `1025`
- Sin autenticación y sin cifrado, **solo para el entorno local**.
- Remitente: `tienda@case-tech.test`

## Operación
```bash
docker compose ps
docker compose logs -f wordpress
docker compose stop
docker compose up -d
```
`docker compose down` elimina contenedores y redes, pero conserva volúmenes. **NO ejecutes `docker compose down -v`** salvo que quieras borrar datos de la tienda y base de datos.

## Consideraciones
- Solo se expone WordPress y Mailpit en la interfaz de loopback (`127.0.0.1`).
- El servicio MariaDB no expone puertos al host.
- Este entorno es de desarrollo y NO es configuración apta para producción.
- Para usar la API de WooCommerce en el futuro, crea claves de solo lectura cuando sea posible; no uses claves de administrador del sitio en el bot.
- Los webhooks externos de Meta/Recurrente no llegarán a `localhost`: requiere túnel HTTPS temporal o staging público separado, sin exponer el servicio de administración.
- Versiona el tema/plugin propios; no guardes secretos, cargas de clientes ni backups en Git.
- Las versiones de Docker se pueden fijar por digest después de validar el conjunto de versiones.
