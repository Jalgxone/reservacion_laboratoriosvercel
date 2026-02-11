# Modernización y Arquitectura

El sistema ha sido transformado para ofrecer una experiencia de usuario superior, pasando de una lógica procedimental a una arquitectura moderna basada en estándares de la industria.

## Arquitectura de Referencia: MVC
El proyecto se fundamenta en el patrón **Model-View-Controller (MVC)**, lo que permite una separación clara de responsabilidades:

- **Modelos (`app/Models`)**: Encapsulan la lógica de acceso a datos y reglas de negocio complejas.
- **Vistas (`app/Views`)**: Utilizan plantillas PHP modulares que garantizan una interfaz consistente y profesional.
- **Controladores (`app/Controllers`)**: Actúan como puentes, procesando las entradas del usuario y coordinando la respuesta adecuada.

## Mejoras de Reactividad (AJAX & Fetch)
Para mejorar la fluidez, se han sustituido las recargas de página tradicionales por interacciones asíncronas en puntos críticos:

### 1. Gestión de Registros en Caliente
El borrado de elementos (Usuarios, Laboratorios, Equipos) utiliza **Fetch API** para comunicarse con el servidor. El DOM se actualiza progresivamente, eliminando la fila del registro únicamente tras la confirmación exitosa del backend.

### 2. Sistema de Notificaciones Inteligente
Se ha integrado un motor de alertas centralizado que captura los mensajes de sesión y los renderiza como bloques estáticos elegantes, mejorando drásticamente el feedback que recibe el usuario ante errores de validación.

## Motor de Validación Extensible
El sistema cuenta con un componente `Validator` dedicado que procesa reglas complejas (regex, unicidad en BD, formatos regionales) de manera declarativa, asegurando que solo los datos limpios y coherentes sean procesados por la aplicación.


