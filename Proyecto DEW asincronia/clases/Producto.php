<?php
/* Archivo Producto.php
   Clase abstracta que define la estructura comun de un producto  𐙚𝜗
   Solo se agregan comentarios sin modificar la logica del codigo  */

abstract class Producto
{
    /* Propiedad id identificador unico del producto nullable entero  */
    protected ?int $id = null;
    /* Propiedad nombre cadena que representa el nombre del producto  */
    protected string $nombre;
    /* Propiedad precio valor numerico decimal del producto  */
    protected float $precio;
    /* Propiedad tipo cadena que categoriza el producto  */
    protected string $tipo;
    /* Propiedad imagen ruta o nombre del recurso de imagen  */
    protected string $imagen;

    /* Constructor que inicializa nombre precio tipo e imagen
       El parametro tipo es opcional y por defecto cadena vacia
       El parametro imagen es opcional y por defecto cadena vacia  */
    public function __construct(string $nombre, float $precio, string $tipo = '', string $imagen = '')
    {
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->tipo = $tipo;
        $this->imagen = $imagen;
    }

    /* Metodos getters para acceder a propiedades del objeto
       Cada metodo devuelve el valor de la propiedad correspondiente  */
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function getPrecio(): float
    {
        return $this->precio;
    }
    public function getTipo(): string
    {
        return $this->tipo;
    }
    public function getImagen(): string
    {
        return $this->imagen;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    /* Metodos setters para actualizar propiedades del objeto
       Se usan para ajustar valores antes de persistir o procesar  */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }
    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }
    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
    }
    public function setImagen(string $imagen): void
    {
        $this->imagen = $imagen;
    }

    /* Metodo abstracto calcularPrecio que debe implementar cada subclase
       Recibe la cantidad y devuelve el precio calculado segun la logica concreta  */
    abstract public function calcularPrecio(int $cantidad): float;

    /* Metodo protegido y estatico para obtener la conexion a la base de datos
       Implementa un patron simple para reutilizar la misma conexion en toda la ejecucion  */
    protected static function getConnection()
    {
        static $conn = null;

        if ($conn === null) {
            /* Requiere el archivo de configuracion que define la variable global de conexion  */
            require_once __DIR__ . '/../config/db.php';
            $conn = $GLOBALS['conn'];
        }

        return $conn;
    }

    /* Metodo para verificar si ya existe un producto con el mismo nombre y tipo
       La comprobacion tiene en cuenta si se trata de una edicion para excluir el id actual  */
    public function existeMismoNombreTipo(): bool
    {
        $conn = self::getConnection();

        if ($this->id !== null) {
            /* Caso edicion se excluye el registro actual en la consulta  */
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM productos WHERE nombre = ? AND tipo = ? AND id != ?");
            $stmt->bind_param("ssi", $this->nombre, $this->tipo, $this->id);
        } else {
            /* Caso creacion se busca cualquier registro con el mismo nombre y tipo  */
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM productos WHERE nombre = ? AND tipo = ?");
            $stmt->bind_param("ss", $this->nombre, $this->tipo);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        /* Retorna verdadero si existe al menos un registro coincidente  */
        return $row['count'] > 0;
    }

    /* Metodos para persistencia CRUD con validaciones basicas
       El metodo save inserta o actualiza segun la existencia de id  */
    public function save(): bool
    {
        /* Validacion que evita duplicados por nombre y tipo  */
        if ($this->existeMismoNombreTipo()) {
            throw new Exception("Ya existe un producto con el nombre '{$this->nombre}' del tipo '{$this->tipo}'");
        }

        $conn = self::getConnection();

        if ($this->id !== null) {
            /* Actualizacion de un producto existente  */
            $stmt = $conn->prepare("UPDATE productos SET nombre=?, precio=?, tipo=?, imagen=? WHERE id=?");
            $stmt->bind_param("sdssi", $this->nombre, $this->precio, $this->tipo, $this->imagen, $this->id);
            return $stmt->execute();
        } else {
            /* Insercion de un nuevo producto  */
            $stmt = $conn->prepare("INSERT INTO productos (nombre, precio, tipo, imagen) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdss", $this->nombre, $this->precio, $this->tipo, $this->imagen);
            $result = $stmt->execute();

            if ($result) {
                /* Asigna el id generado por la base de datos al objeto  */
                $this->id = $stmt->insert_id;
            }

            return $result;
        }
    }

    /* Metodo para eliminar el producto de la base de datos segun su id
       Retorna falso si el objeto no tiene id asignado  */
    public function delete(): bool
    {
        if ($this->id === null) {
            return false;
        }

        $conn = self::getConnection();
        $stmt = $conn->prepare("DELETE FROM productos WHERE id=?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }

    /* Metodo estatico para buscar un producto por su id
       Devuelve una instancia de Producto o null si no se encuentra  */
    public static function findById(int $id): ?Producto
    {
        $conn = self::getConnection();
        $stmt = $conn->prepare("SELECT * FROM productos WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return self::crearDesdeArray($row);
        }

        return null;
    }

    /* Metodo estatico para obtener todos los productos registrados
       Devuelve un arreglo de instancias de Producto o arreglo vacio si no hay resultados  */
    public static function getAll(): array
    {
        $conn = self::getConnection();
        $result = $conn->query("SELECT * FROM productos");

        if (!$result) {
            return [];
        }

        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = self::crearDesdeArray($row);
        }

        return $productos;
    }

    /* Metodo estatico para verificar existencia de un par nombre y tipo
       Permite excluir un id especifico para comprobaciones en edicion  */
    public static function nombreTipoExiste(string $nombre, string $tipo, ?int $excluirId = null): bool
    {
        $conn = self::getConnection();

        if ($excluirId !== null) {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM productos WHERE nombre = ? AND tipo = ? AND id != ?");
            $stmt->bind_param("ssi", $nombre, $tipo, $excluirId);
        } else {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM productos WHERE nombre = ? AND tipo = ?");
            $stmt->bind_param("ss", $nombre, $tipo);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0;
    }

    /* Metodo PUBLICO para crear un objeto Producto a partir de un arreglo de datos
       Valida la presencia de los campos minimos nombre y precio  */
    public static function crearDesdeArray(array $data): Producto
    {
        /* Validacion basica de campos requeridos  */
        if (!isset($data['nombre']) || !isset($data['precio'])) {
            throw new InvalidArgumentException('Los datos del producto deben incluir nombre y precio');
        }

        $tipo = $data['tipo'] ?? '';
        $nombre = $data['nombre'];
        $precio = floatval($data['precio']);
        $imagen = $data['imagen'] ?? '';
        $id = $data['id'] ?? null;

        /* Requiere las clases concretas que extienden Producto  */
        require_once __DIR__ . '/PaqueteLlaves.php';
        require_once __DIR__ . '/PaqueteBolso.php';
        require_once __DIR__ . '/PaqueteMochila.php';
        require_once __DIR__ . '/Llavero.php';

        /* Selecciona la clase concreta segun el tipo y crea la instancia correspondiente  */
        switch ($tipo) {
            case 'llaves':
                $producto = new PaqueteLlaves($nombre, $precio);
                break;
            case 'bolso':
                $producto = new PaqueteBolso($nombre, $precio);
                break;
            case 'mochila':
                $producto = new PaqueteMochila($nombre, $precio);
                break;
            default:
                $producto = new Llavero($nombre, $precio, $tipo);
        }

        /* Restaura propiedades adicionales obtenidas del arreglo  */
        $producto->setId($id);
        $producto->setTipo($tipo);
        $producto->setImagen($imagen);

        return $producto;
    }
}
?>