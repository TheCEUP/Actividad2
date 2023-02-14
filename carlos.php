<?php

session_start();

class Usuario {
  public $nombre;
  public $apellido;
  public $dni;
  public $matematicas;
  public $fisica;
  public $programacion;

  public function __construct($nombre, $apellido, $dni, $matematicas, $fisica, $programacion) {
    $this->nombre = $nombre;
    $this->apellido = $apellido;
    $this->dni = $dni;
    $this->matematicas = $matematicas;
    $this->fisica = $fisica;
    $this->programacion = $programacion;
  }
}

if (!isset($_SESSION['usuarios'])) {
  $_SESSION['usuarios'] = [];
}

$usuarios = $_SESSION['usuarios'];

function agregarUsuario($nombre, $apellido, $dni, $matematicas, $fisica, $programacion, &$usuarios) {
  $usuarios[] = new Usuario($nombre, $apellido, $dni, $matematicas, $fisica, $programacion);
}

function mostrarUsuarios($usuarios) {
  echo "<table>";
  echo "<tr>";
  echo "<th>Nombre</th>";
  echo "<th>Apellido</th>";
  echo "<th>DNI</th>";
  echo "<th>Matemáticas</th>";
  echo "<th>Física</th>";
  echo "<th>Programación</th>";
  echo "</tr>";
  foreach ($usuarios as $usuario) {
    echo "<tr>";
    echo "<td>" . $usuario->nombre . "</td>";
    echo "<td>" . $usuario->apellido . "</td>";
    echo "<td>" . $usuario->dni . "</td>";
    echo "<td>" . $usuario->matematicas . "</td>";
    echo "<td>" . $usuario->fisica . "</td>";
    echo "<td>" . $usuario->programacion . "</td>";
    echo "</tr>";
  }
  echo "</table>";
}

if (isset($_POST['submit'])) {
  $nombre = $_POST['nombre'];
  $apellido = $_POST['apellido'];
  $dni = $_POST['dni'];
  $matematicas = $_POST['matematicas'];
  $fisica = $_POST['fisica'];
  $programacion = $_POST['programacion'];

  agregarUsuario($nombre, $apellido, $dni, $matematicas, $fisica, $programacion, $usuarios);
  $_SESSION['usuarios'] = $usuarios;

  unset($_POST['nombre']);
  unset($_POST['apellido']);
  unset($_POST['dni']);
  unset($_POST['matematicas']);
  unset($_POST['fisica']);
  unset($_POST['programacion']);

  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}

$matematicasMax = 0;
$fisicaMax = 0;
$programacionMax = 0;
$numMenosDe10 = 0;
$numMasDe10 = 0;
$numTodasMasDe10 = 0;
$numDosMasDe10 = 0;
$numUnaMasDe10 = 0;
$gpaSuma = 0;

foreach ($usuarios as $usuario) {
  $matematicas = $usuario->matematicas;
  $fisica = $usuario->fisica;
  $programacion = $usuario->programacion;
  $gpa = ($matematicas + $fisica + $programacion) / 3;

  $gpaSuma += $gpa;

  if ($matematicas > $matematicasMax) {
      $matematicasMax = $matematicas;
  }
  if ($fisica > $fisicaMax) {
      $fisicaMax = $fisica;
  }
  if ($programacion > $programacionMax) {
      $programacionMax = $programacion;
  }

  if ($gpa < 10) {
      $numMenosDe10++;
  } else {
      $numMasDe10++;
  }

  if ($matematicas >= 10 && $fisica >= 10 && $programacion >= 10) {
      $numMasDe10++;
  }

  if ($matematicas >= 10 && $fisica >= 10 || $matematicas >= 10 && $programacion >= 10 || $fisica >= 10 && $programacion >= 10) {
      $numDosMasDe10++;
  }

  if ($matematicas >= 10 || $fisica >= 10 || $programacion >= 10) {
      $numUnaMasDe10++;
  }
}

$gpaPromedio = 0;

if (count($usuarios) > 0) {
  $gpaPromedio = $gpaSuma / count($usuarios);
}

echo "Nota promedio de todos los estudiantes: " . $gpaPromedio . "<br>";
echo "Estudiantes aprobados: " . $numMenosDe10 . "<br>";
echo "Estudiantes reprobados: " . $numMasDe10 . "<br>";
echo "Estudiantes con todas las materias aprobadas: " . $numMasDe10 . "<br>";
echo "Estudiantes con 2 aprobadas: " . $numDosMasDe10 . "<br>";
echo "Estudiantes con 1 aprobada: " . $numUnaMasDe10 . "<br>";
echo "Nota maxima en Matematicas: " . $matematicasMax . "<br>";
echo "Nota maxima en Fisica: " . $fisicaMax . "<br>";
echo "Nota maxima en Programacion: " . $programacionMax . "<br><br><br>";

?>

<form method="post">
  <label for="nombre">Name:</label>
  <input type="text" min="3" max="14" name="nombre" id="nombre" required>
  <br>
  <label for="apellido">Last Name:</label>
  <input type="text" min="3" max="14" name="apellido" id="apellido" required>
  <br>
  <label for="dni">DNI:</label>
  <input type="number" name="dni" id="dni" min="1" max="99999999" required>
  <br>
  <label for="matematicas">matematicas:</label>
  <input type="number" name="matematicas" min="0" max="20" id="matematicas" required>
  <br>
  <label for="fisica">fisica:</label>
  <input type="number" name="fisica" min="0" max="20" id="fisica" required>
  <br>
  <label for="programacion">programacion:</label>
  <input type="number" name="programacion" min="0" max="20" id="programacion" required>
  <br>
  <input type="submit" name="submit" value="Submit">
</form>
<br>
<?php

  mostrarUsuarios($usuarios);

?>