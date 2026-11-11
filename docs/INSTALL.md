# Instalación y ejecución de Case-Tech

## Opciones disponibles

Case-Tech puede ejecutarse de tres formas:

1. **Windows con Docker Desktop:** opción recomendada para trabajar localmente.
2. **Linux con Docker Engine y Compose:** opción equivalente para una máquina virtual o servidor de desarrollo.
3. **GitHub Codespaces:** opción útil para editar y ejecutar el proyecto desde el navegador o VS Code, usando la configuración de `.devcontainer`.

En todos los casos, WordPress y WooCommerce se ejecutan dentro de Docker. No es necesario instalar PHP, MariaDB ni Apache directamente en el sistema operativo.

### ¿Hace falta Docker Desktop en otra PC?

No siempre. Si la otra PC usará GitHub Codespaces desde el navegador o VS Code conectado al Codespace, no hace falta instalar Docker Desktop allí: Docker se ejecutará dentro del entorno de desarrollo de GitHub. Si se quiere ejecutar WordPress directamente en Windows, sí hace falta Docker Desktop; en Linux basta con Docker Engine y Docker Compose.

La tabla rápida es:

| Forma de trabajo | Docker Desktop local |
|---|---|
| GitHub Codespaces desde navegador | No |
| VS Code conectado a Codespaces | No |
| Windows ejecutando WordPress localmente | Sí |
| Linux ejecutando WordPress localmente | No; requiere Docker Engine |
| Solo editar archivos de GitHub | No |

Codespaces puede consumir horas y almacenamiento según el plan de GitHub. Si Docker-in-Docker no inicia por una política o límite del plan, se puede usar Codespaces para editar y Docker Desktop local para ejecutar.

## Requisitos generales

Se necesita Git, Docker con Compose y conexión a Internet. Se recomiendan al menos 4 GB de RAM libres para WordPress, MariaDB y Mailpit. Para Windows se recomienda Docker Desktop con el motor WSL 2 activo.

## Windows con Docker Desktop

Instala Docker Desktop y verifica que esté abierto. Desde PowerShell, dentro de la carpeta del repositorio, ejecuta:

```powershell
Copy-Item .env.example .env
notepad .env
```

Cambia las dos contraseñas de `.env` por valores distintos. No subas `.env` a GitHub.

Después inicia los servicios:

```powershell
docker compose up -d --build
docker compose ps
```

Abre WordPress en `http://localhost:8080` y Mailpit en `http://localhost:8025`.

## Windows sin Docker (WordPress Playground)

Para PCs donde no se puede instalar Docker. Solo requiere Node.js 20 o superior e Internet. No necesita PHP, MariaDB ni permisos de administrador.

```powershell
npm run dev
```

Se abre `http://127.0.0.1:9400` con sesión de administrador iniciada. El blueprint `playground/blueprint.json` instala WooCommerce en español, activa el tema `case-tech` y el plugin `case-tech-custom`, y ejecuta `configure-store.php`, `create-store-pages.php` e `import-demo-products.php`.

- El tema y el plugin se montan desde el repositorio: edita en VS Code y recarga el navegador.
- La base de datos es SQLite y se reinicia en cada arranque; el blueprint reconstruye la tienda demo. No cargues datos definitivos aquí.
- No hay Mailpit: los correos y la validación con MariaDB se hacen en Docker o en Codespaces.
- Para detener el servidor: `Ctrl + C` en la terminal.

## Linux

Instala Docker Engine, el plugin Docker Compose y Git. Luego ejecuta:

```bash
cp .env.example .env
nano .env
docker compose up -d --build
docker compose ps
```

Abre `http://localhost:8080` y `http://localhost:8025`.

## Instalación inicial de WordPress y WooCommerce

La primera vez, abre `http://localhost:8080` y completa el instalador local de WordPress. Después, desde la carpeta del repositorio, activa el plugin propio y ejecuta los importadores:

```bash
docker compose run --rm wpcli wp plugin install woocommerce --activate
docker compose run --rm wpcli wp plugin activate case-tech-custom
docker compose run --rm wpcli wp eval-file /var/www/html/wp-content/plugins/case-tech-custom/import-demo-products.php
docker compose run --rm wpcli wp eval-file /var/www/html/wp-content/plugins/case-tech-custom/create-store-pages.php
docker compose run --rm wpcli wp eval-file /var/www/html/wp-content/plugins/case-tech-custom/configure-store.php
```

Luego, en WordPress, guarda **Ajustes → Enlaces permanentes → Nombre de la entrada**.

## GitHub Codespaces

El repositorio incluye `.devcontainer/devcontainer.json`. Al crear un Codespace, GitHub construirá un contenedor de desarrollo con Docker-in-Docker, extensiones de Docker, PHP y YAML, y reenviará los puertos 8080 y 8025. Codespaces usa un dev container configurado en la carpeta `.devcontainer` y permite acceder a las aplicaciones mediante puertos reenviados.

Pasos:

1. Sube el repositorio a GitHub sin `.env`.
2. En GitHub, abre **Code → Codespaces → Create codespace on main**.
3. Cuando cargue VS Code, abre la terminal.
4. Crea el archivo local de variables:

```bash
cp .env.example .env
nano .env
```

5. Inicia el proyecto:

```bash
docker compose up -d --build
docker compose ps
```

6. Abre la pestaña **Ports** y utiliza el enlace privado del puerto `8080`. Mailpit estará en el puerto `8025`.

La primera creación del Codespace puede tardar más porque instala Docker dentro del contenedor de desarrollo. Si Docker-in-Docker no inicia en un plan o política concreta de GitHub, usa Codespaces para editar y Docker Desktop para ejecutar localmente.

## Comandos operativos

```bash
docker compose ps
docker compose logs -f wordpress
docker compose stop
docker compose up -d
docker compose down
```

No ejecutar `docker compose down -v` salvo que se quiera eliminar la base de datos y el estado local de WordPress.

## Variables y secretos

`.env.example` es una plantilla pública y sí debe versionarse. `.env` contiene las contraseñas locales y está excluido por `.gitignore`. Nunca subas credenciales reales, exportaciones SQL, `wp-content/uploads` ni copias de seguridad.

## Solución rápida de problemas

Si `docker --version` funciona pero `docker run hello-world` falla, abre Docker Desktop y espera a que el motor indique que está activo. Si aparece un 404 para `/shop/`, guarda los enlaces permanentes en WordPress y confirma que WooCommerce tenga una página Shop asignada. Si el catálogo no aparece, ejecuta nuevamente el importador demo con `wpcli`.
