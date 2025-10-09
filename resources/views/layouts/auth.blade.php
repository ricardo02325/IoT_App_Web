{{-- resources/views/layouts/auth.blade.php --}}

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">
        <main>
            {{-- Aquí es donde se insertará el contenido de tu login o registro --}}
            @yield('content')
        </main>
    </div>

    {{-- SCRIPT PARA LA FUNCIONALIDAD DE MOSTRAR/OCULTAR CONTRASEÑA --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Selecciona todos los iconos de ojo
            const togglePasswordIcons = document.querySelectorAll('.toggle-password');

            togglePasswordIcons.forEach(icon => {
                icon.addEventListener('click', function () {
                    // Encuentra el campo de contraseña que está justo antes del icono
                    const passwordField = this.previousElementSibling;

                    // Cambia el tipo del input: de 'password' a 'text' o viceversa
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);

                    // Cambia el icono: de 'bx-show' a 'bx-hide' o viceversa
                    this.classList.toggle('bx-show');
                    this.classList.toggle('bx-hide');
                });
            });
        });
    </script>

</body>
</html>