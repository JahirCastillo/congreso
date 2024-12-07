erDiagram
    AUTORES {
        int aut_id PK
        string aut_nombre
        string aut_email
        int aut_id_ponencia FK
    }

    CONVOCATORIAS_CONGRESOS {
        int id_convocatoria PK
        string nombre
        date fecha_inicio
        date fecha_fin
        text descripcion
        string ubicacion
        string estatus
        date fecha_inicio_recepcion_documentos
        date fecha_limite_documentos
    }

    PONENCIAS {
        int po_id_ponencia PK
        int po_id_tematica FK
        int po_id_subtematica FK
        int po_id_convocatoria FK
        int po_id_ponente FK
        string po_titulo
        text po_resumen
        string po_palabrasclave
        datetime po_hora_inicio
        datetime po_hora_fin
        string po_estatus
        int po_revisiones
        datetime po_fecha_registro
        text po_motivorechazo
        datetime po_fechamovimiento
        int po_id_revisor FK
    }

    PONENTES {
        int id_ponente PK
        string nombre
        string email
        string contrasena
        string institucion
        string pais
    }


    ROLES {
        int rol_id PK
        string rol_clave
        string rol_nombre
        string rol_descripcion
        int rol_usuarioagrego
        date rol_fechaagrego
        int rol_usuariomodifico
        date rol_fechamodifico
    }

    SUBTEMATICAS {
        int id_subtematica PK
        int id_tematica FK
        string nombre
        text descripcion
    }

    TEMATICAS {
        int id_tematica PK
        string nombre
        text descripcion
        int convocatoria_congreso FK
    }

    USUARIOS {
        int usu_id PK
        string usu_login
        string usu_password
        string usu_nombre
    }

    %% Relaciones

    AUTORES ||--o| PONENCIAS: "Escribe"
    PONENCIAS ||--o| TEMATICAS: "Pertenecen a"
    PONENCIAS ||--o| SUBTEMATICAS: "Tiene"
    PONENCIAS ||--o| PONENTES: "Presenta"
    PONENCIAS ||--o| USUARIOS: "Revisa"
    PONENCIAS ||--o| CONVOCATORIAS_CONGRESOS: "Se incluye en"
    PONENTES ||--o| ROLES: "Tiene rol"
    TEMATICAS ||--o| CONVOCATORIAS_CONGRESOS: "Está asociada a"
    SUBTEMATICAS ||--o| TEMATICAS : "Tiene"
