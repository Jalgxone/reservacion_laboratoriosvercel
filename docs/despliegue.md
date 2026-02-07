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
