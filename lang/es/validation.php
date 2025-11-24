<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Líneas de Lenguaje de Validación (Genéricas)
    |--------------------------------------------------------------------------
    */

    'confirmed' => 'La confirmación de :attribute no coincide.',
    'email' => 'El campo :attribute debe ser una dirección de correo válida.',
    'required' => 'El campo :attribute es obligatorio.',
    'unique' => 'El :attribute ya ha sido registrado.',
    'min' => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'regex' => 'El formato del campo :attribute no es válido.',

    /*
    |--------------------------------------------------------------------------
    | Mensajes Personalizados (Aquí está la magia para tu contraseña)
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'password' => [
            'regex' => 'La contraseña es insegura. Debe tener mayúsculas, minúsculas, números y un símbolo (@$!%*#?&).',
            'min' => 'La contraseña debe tener al menos :min caracteres.',
            'confirmed' => 'La confirmación de la contraseña no coincide.',
        ],
        'email' => [
            'required' => 'El correo es obligatorio.',
            'email' => 'Debes ingresar un correo válido.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Nombres de Atributos
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'email' => 'correo electrónico',
        'password' => 'contraseña',
    ],

];