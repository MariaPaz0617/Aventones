<!DOCTYPE html>
<html>
<head>
    <title>Cuenta activada</title>
      @vite('resources/css/activar_cuenta.css')
</head>
<body>
    <h1>{{ $mensaje }}</h1>
    <a href="{{ url('/login') }}">Ir al login</a>
</body>
</html>