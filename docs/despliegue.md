# Despliegue en la Nube

Para que tu documentación sea accesible desde cualquier lugar (y no solo localmente), tienes dos opciones principales gratuitas y profesionales.

## Opción 1: Vercel (Recomendada y Gratis)

Vercel es ideal porque detecta automáticamente proyectos de MkDocs. El **Plan Hobby** es 100% gratuito para proyectos personales y educativos.

### Pasos:
1. Sube tu proyecto a un repositorio de **GitHub**, **GitLab** o **Bitbucket**.
2. Entra en [Vercel.com](https://vercel.com) y crea una cuenta (puedes usar tu cuenta de GitHub).
3. Haz clic en **"Add New"** > **"Project"**.
4. Importa tu repositorio.
5. Vercel detectará el archivo `mkdocs.yml`. En la configuración del build:
    - **Build Command**: `mkdocs build`
    - **Output Directory**: `site`
6. ¡Listo! Te dará una URL tipo `tu-proyecto.vercel.app` de forma gratuita para siempre.

---

## Opción 2: GitHub Pages

Si usas GitHub, puedes usar sus "Pages" de forma gratuita.

### Pasos:
1. En tu terminal local, instala `gh-deploy`:
   ```bash
   pip install mkdocs-material
   ```
2. Ejecuta el comando mágico:
   ```bash
   mkdocs gh-deploy
   ```
3. Esto creará una rama llamada `gh-pages` y subirá todo el sitio estático automáticamente.
4. Ve a la configuración de tu repo en GitHub > **Pages** y asegúrate de que esté apuntando a la rama `gh-pages`.

---

## Preparación de Archivos
He creado un archivo llamado `requirements.txt` en la raíz de tu proyecto. Este archivo le dice a la nube (Vercel o GitHub) qué herramientas necesita instalar para que el sitio funcione:
- `mkdocs-material`
- `pymdown-extensions`
