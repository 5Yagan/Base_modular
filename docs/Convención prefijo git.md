### Tabla de Convención de Nombres

| **Prefijo**   | **Descripción**                                                                 | **Ejemplo en Laravel (Mejora de roles dinámicos)**                                   | **Cuándo usarlo en tu caso**                                                                 |
|---------------|--------------------------------------------------------------------------------|------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------|
| `ENHANCE`     | Mejora de una funcionalidad existente, haciéndola más flexible o robusta.       | `ENHANCE(roles): implementar asignación dinámica de permisos en RoleController`      | Para mejoras evolutivas que amplían o mejoran el sistema de roles (ej. más flexibilidad).    |
| `FEAT`        | Nueva funcionalidad añadida al sistema.                                        | `FEAT(roles): añadir soporte para permisos basados en grupos dinámicos`             | Para características nuevas dentro de la mejora, como un nuevo tipo de permiso o módulo.     |
| `REFACTOR`    | Reestructuración del código sin cambiar su comportamiento externo.             | `REFACTOR(roles): reorganizar lógica de permisos en RoleService para dinámicos`      | Para reorganizaciones internas que preparan el sistema para la mejora evolutiva.            |
| `BUG`         | Corrección de un error específico identificado.                               | `BUG(roles): solucionar error al asignar permisos dinámicos en la API (#123)`       | Para errores encontrados durante la implementación de la mejora.                            |
| `TEST`        | Añadir o modificar pruebas unitarias o de integración.                         | `TEST(roles): añadir pruebas para validación de permisos dinámicos`                 | Para pruebas que validen la nueva funcionalidad dinámica.                                   |
| `DOCS`        | Cambios en la documentación.                                                  | `DOCS: actualizar documentación de la API para permisos dinámicos`                  | Para documentar los cambios evolutivos en el sistema.                                       |
| `CONFIG`      | Cambios en archivos de configuración.                                         | `CONFIG(roles): añadir configuración para permisos dinámicos en config/permissions`  | Para ajustes en configuraciones relacionadas con la mejora (ej. `.env` o `config`).         |
| `BREAK`       | Cambios que rompen compatibilidad con versiones anteriores.                   | `BREAK(roles): modificar estructura de la tabla permissions para soporte dinámico`   | Si la reestructuración cambia la API o la base de datos de forma incompatible.              |

### Uso en tu proyecto Laravel (Mejora evolutiva del sistema de roles y permisos)

Dado que describes la reestructuración como una **mejora evolutiva**, el prefijo `ENHANCE` será el más usado, ya que refleja que estás mejorando la funcionalidad existente (de roles fijos a dinámicos) sin necesariamente introducir algo completamente nuevo. Sin embargo, si partes de la reestructuración añaden características nuevas (como un nuevo panel de administración para gestionar roles dinámicos), usarías `FEAT`.

#### Ejemplo de commits para la mejora evolutiva:
1. **Configuración inicial**:
   - `CONFIG(roles): añadir variables de entorno para permisos dinámicos`
     - Ejemplo: Agregar `DYNAMIC_ROLES_ENABLED=true` en `.env`.

2. **Reestructuración de la lógica**:
   - `REFACTOR(roles): mover lógica de permisos fijos a un servicio dinámico`
     - Ejemplo: Reorganizar el código en `app/Services/RoleService.php` para soportar dinámicas.

3. **Mejora evolutiva principal**:
   - `ENHANCE(roles): implementar soporte para asignación dinámica de permisos`
     - Ejemplo: Modificar `RoleController` para permitir asignar permisos basados en reglas dinámicas.

4. **Nueva funcionalidad (si aplica)**:
   - `FEAT(roles): añadir interfaz de administración para gestionar permisos dinámicos`
     - Ejemplo: Crear un nuevo endpoint en la API o vistas Blade para gestionar roles.

5. **Pruebas**:
   - `TEST(roles): añadir pruebas de integración para asignación dinámica de permisos`
     - Ejemplo: Crear pruebas en `tests/Feature/RoleTest.php` con PHPUnit o Pest.

6. **Corrección de errores**:
   - `BUG(roles): corregir error en validación de permisos dinámicos en middleware`
     - Ejemplo: Solucionar un problema donde el middleware rechazaba permisos válidos.

7. **Documentación**:
   - `DOCS: actualizar README con instrucciones para configurar roles dinámicos`
     - Ejemplo: Explicar cómo usar el nuevo sistema en la documentación.

8. **Cambios que rompen compatibilidad (si aplica)**:
   - `BREAK(roles): actualizar estructura de la tabla permissions para dinámicos`
     - Ejemplo: Cambiar la estructura de la base de datos, requiriendo una migración.