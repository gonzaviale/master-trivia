<?php


class AdministradorModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function cantidadDeJugadores()
    {
        $sql=("SELECT COUNT(u.id) AS cantidadJugadores FROM usuario u  JOIN login l ON l.id_usuario = u.id  JOIN rol r ON r.id = l.id_rol  WHERE r.rol = 'Jugador'");
        return $this->database->uniqueQuery($sql, "cantidadJugadores");
    }

    public function porcentajeJugador()
    {
        $sql=("SELECT
                u.id AS usuario_id,
                u.nombre_completo,
                j.respuestas_correctas,
                j.total_respuestas,
                TRUNCATE(((j.respuestas_correctas / j.total_respuestas) * 100), 2) AS porcentaje_correctas
                FROM
                Usuario u
                JOIN
                Jugador j ON u.id = j.usuario_id;");
        return $this->database->query($sql);
    }


    public function cantidadDeJugadoresNuevos($range=null)
    {
        $condicion = $this->filtrarFecha($range);

        $sql=("SELECT COUNT(u.id) AS cantidadJugadoresNuevos FROM usuario u  JOIN login l ON l.id_usuario = u.id  JOIN rol r ON r.id = l.id_rol  WHERE r.rol = 'Jugador' ");
        if ($condicion) {
            $sql .= " AND $condicion";
        }

        return $this->database->uniqueQuery($sql, "cantidadJugadoresNuevos");
    }

    public function cantidadDePartidas($range=null)
    {
        $condicion = $this->filtrarFechaPartida($range);
        $sql=("SELECT COUNT(partida.id) as cantidadPartidas FROM partida ");
        if ($condicion) {
            $sql .= " WHERE $condicion";
        }
        return $this->database->uniqueQuery($sql, "cantidadPartidas");

    }
    public function cantidadDeUsuariosPorSexo($range=null)
    {
        $condicion = $this->filtrarFecha($range);


        $sql = "SELECT COUNT(u.id) as cantidadUsuariosPorSexo, u.sexo 
            FROM usuario u 
            JOIN login l ON l.id_usuario = u.id 
            JOIN rol r ON r.id = l.id_rol  
            WHERE r.rol = 'Jugador'";


        if ($condicion) {
            $sql .= " AND $condicion";
        }


        $sql .= " GROUP BY u.sexo";

        return $this->database->query($sql);
    }

    public function cantidadDeUsuariosPorGrupoDeEdad($range = null)
    {
        $condicion = $this->filtrarFecha($range);

        $sql = "SELECT 
        COUNT(u.id) as cantidadUsuariosPorGrupoDeEdad,
        CASE 
            WHEN YEAR(CURDATE()) - u.ano_nacimiento < 18 THEN 'Menores'
            WHEN YEAR(CURDATE()) - u.ano_nacimiento BETWEEN 18 AND 65 THEN 'Mediana Edad'
            ELSE 'Jubilados'
        END as grupoEdad 
    FROM usuario u 
    JOIN login l ON l.id_usuario = u.id 
    JOIN rol r ON r.id = l.id_rol  
    WHERE r.rol = 'Jugador'";

        if ($condicion) {
            $sql .= " AND $condicion";
        }

        $sql .= " GROUP BY grupoEdad";

        return $this->database->query($sql);
        }
    public function cantidadDePreguntas($range=null)
    {
        $condicion = $this->filtrarFecha($range);
        $sql=("SELECT COUNT(pregunta.id) as cantidadPreguntas FROM pregunta  ");
        if ($condicion) {
            $sql .= " WHERE $condicion";
        }
        return $this->database->uniqueQuery($sql, "cantidadPreguntas");

    }

    public function cantidadUsuariosPorPaises($range=null)
    {
        $condicion = $this->filtrarFecha($range);


        $sql = "SELECT COUNT(u.id) as cantidadUsuariosPorPais, u.pais 
            FROM usuario u 
            JOIN login l ON l.id_usuario = u.id 
            JOIN rol r ON r.id = l.id_rol  
            WHERE r.rol = 'Jugador'";


        if ($condicion) {
            $sql .= " AND $condicion";
        }


        $sql .= " GROUP BY u.pais";

        return $this->database->query($sql);
    }
    public function filtrarFechaPartida($range)
    {
        switch($range){
            case 'dia':
                return "DATE(fecha_hora) = CURDATE()";
            default:
                return "fecha_hora>= DATE_SUB(CURDATE(),INTERVAL 1 MONTH)";
            case 'anio':
                return "fecha_hora>= DATE_SUB(CURDATE(),INTERVAL 1 YEAR)";

        }
    }

    public function filtrarFecha($range)
    {
        switch($range){
        case 'dia':
            return "fecha_creacion = CURDATE()";
            default:
              return "fecha_creacion>= DATE_SUB(CURDATE(),INTERVAL 1 MONTH)";
            case 'anio':
                return "fecha_creacion>= DATE_SUB(CURDATE(),INTERVAL 1 YEAR)";

        }
    }
}