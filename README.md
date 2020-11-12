# CrudGenerator Backend

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codegaf/crudgenerator.svg?style=flat-square)](https://packagist.org/packages/codegaf/crudgenerator)
[![Total Downloads](https://img.shields.io/packagist/dt/codegaf/crudgenerator.svg?style=flat-square)](https://packagist.org/packages/codegaf/crudgenerator)

Generador de crud backend para 10codesoftware.

## Instalación

Puedes instalar el paquete via composer:

```bash
composer require codegaf/crudgenerator dev-master
```

## Guía de uso

El comando crud generator de la parte del backend genera las siguientes clases:

- Migration.
- Model.
- Controller.
- Service.
- Repository.
- DataTable.
- Request.

La clase migration irá directamente en database/migrations. La clase model irá almacenada en la carpeta app/models. Las demás clases irán en carpetas concretas siguiendo la convención. Ejemplo: el controlador de un crud generator de users irá en UserController\UserController.php.


El crud generator necesita conocer unos datos previos para su funcionamiento correcto. Estos datos lo especificaremos en una plantilla config que tendrá su ubicación en la carpeta config/models. El nombre del archivo seguirá la convención singular camelCase. Ejemplos: user, userContact, car… Por ejemplo, imaginemos que los usuarios tienen contactos, relación 1:N, un usuario tiene muchos contactos y cada contacto pertenece a un usuario.

Importante: Como cualquier archivo config en Laravel, cada vez que modifiquemos el archivo config relacionado con el crud generator tendremos que llamar al comando:

``` php
php artisan config:cache
```

Si ejecutamos el crud generator y no hemos llamado al comando de Laravel que reconstruye la caché, los cambios no surtirán el efecto deseado.


Índice migration.

1.Columns

Los índices del array columns contemplan los nombres de las columnas tal y como estarán en base de datos. Las opciones contempladas por cada columna serán:

- type -> String. Obligatorio. El tipo de columna en base de datos. Tal y como especificamos en el migration, es decir, acepta todos los tipos que acepta Laravel. $table->string, $table->text, $table->integer…

- nullable -> Booleano. Opcional. Si especificamos esta opción y la establecemos a true, la columna en base de datos será nullable. Si no especificamos esta opción o la establecemos a false la columna no será nullable.

- unique -> Booleano. Opcional. Si especificamos esta opción y la establecemos a true, la columna en base de datos será única. Si no especificamos esta opción o la establecemos a false la columna no será única.

- default -> String|Integer|Boolean… Opcional. Si especificamos esta opción y establecemos un valor, se añadirá un valor por defecto a la columna en base de datos.

2.Foreigns

Los índices del array foreigns recogen las columnas de tipo foreign key. Este índice no es obligatorio añadirlo. Solo será necesario para las tablas con relaciones. Las opciones de cada array foreign serán:

- references -> String. Obligatorio. Es la columna relación en la otra tabla de la foreign key.

- table -> String. Obligatorio. Es el nombre de la tabla con la que la foreign key se relaciona.

- onDelete -> String. Obligatorio. Es la operación que se llevará a cabo con las relaciones si la fila es borrada. El índice es obligatorio especificarlo pero si no se requiere ninguna acción podrá dejarse vacío.




``` php
'migration' => [
   'columns' => [
       'name' => [
           'type' => 'string',
           'nullable' => false,
           'default' => 'string|integer|boolean'
       ],

       'surname' => [
           'type' => 'string',
           'nullable' => false,
           'unique' => false,
       ],

       'email' => [
           'type' => 'string',
       ],

       

'phone' => [
           'type' => 'string',
           'nullable' => true,
       ],

       'born_date' => [
           'type' => 'date',
       ],

       
       'user_id' => [
           'type' => 'unsignedBigInteger',
       ]
       
   ],

   
   'foreigns' => [
       'user_id' => [
           'references' => 'id',
           'table' => 'users',
           'onDelete' => 'cascade'
       ]
   ]
],
```


3.Model

El índice model nos permitirá especificar los fillable y las relaciones de la siguiente manera:

- fillable -> String. Obligatorio. En este índice especificaremos las columnas que se guardarán en base de datos. Ejemplo:

``` php
‘model’ => [
    ‘fillable’ => ['name', 'surname', 'email', 'phone', 'born_date', 'user_id'],
],
```

- relations -> Array. Opcional. En este índice podemos especificar las relaciones con otras entidades. Los índices del array relations serán los nombres de las relaciones que contempla Laravel. Como por ejemplo belongsTo, hasMany, hasOne, belongsToMany... 

Este array puede tener las siguientes opciones:

- functionName -> String. Obligatorio. Es el nombre de la función. Ejemplo: public function users…

- modelClass -> String. Obligatorio. Es la clase destino de la relación. Ejemplo: User::class.

- foreign -> String. Obligatorio. Es la columna foreign. Ejemplo: user_id

``` php
‘model’ => [
    ‘fillable’ => ['name', 'surname', 'email', 'phone', 'born_date', 'user_id'],
    ‘relations’ => [
	    0 => [
                ‘relation’ => ‘belongsTo’,
                ‘functionName’ => ‘user’,
                ‘modelClass’ => ‘User::class’,
                ‘foreign’ => ‘user_id’
        ],
        1 => ...
    ],
],
```




Para las relaciones belongsToMany tendremos las siguientes opciones disponibles:

``` php
‘model’ => [
    ‘fillable’ => ['name', 'surname', 'email', 'phone', 'born_date', 'user_id'],
    ‘relations’ => [
	    0 => [
                ‘relation’ => ‘belongsToMany’,
                ‘functionName’ => ‘user’,
                ‘modelClass’ => ‘User::class’,
                ’table’ => ’users_contacts’,
                ‘foreignKey’ => ‘user_id’,
                ‘relationKey’ => ‘friend_id’,
                ‘pivot’ => ‘friendship_date, active’
        ],
        1 => ...
    ],
],
```



4.DataTables

La plantilla trae una novedad respecto a nuestra forma de emplear datatables hasta ahora. Esta vez se instancia y se prepara en el backend. Para que el crud generator pueda realizar esta tarea debemos añadir la siguiente estructura al config.

Los índices del array dataTables son los nombres de las columnas en base de datos o los alias que le hayamos dado a los campos en la consulta. Estos arrays tienen a su vez otro índice que permite especificar el label de la columna. No es necesario especificar la columna acción. El crud generator ya la tiene en cuenta automáticamente. Si no se necesita puede eliminarse una vez generada la clase DataTable.

- label -> String. Obligatorio. En formato lang pasaremos el nombre de la columna tal y como se verá en la vista.

``` php
'dataTables' => [
   'name' => [
       'label' => 'global.name'
   ],
   'surname' => [
       'label' => 'global.surname'
   ],
   'email' => [
       'label' => 'global.label'
   ],
   'phone' => [
       'label' => 'global.phone'
   ],
   'born_date' => [
       'label' => 'global.born_date'
   
   ],
   'created_at' => [
       'label' => 'global.created_at'
   ]
],
```

Una vez configurado correctamente el archivo en config/models podemos proceder a ejecutar los comandos que se indican a continuación:

Para que los cambios del archivo config surtan efectos tenemos que limpiar la caché del config de Laravel:

``` php
php artisan config:cache
```

Para ejecutar el comando completo añadiremos la opción --all. El nombre del modelo debe
especificarse en PascalCase (Car, UserContact) 

``` php
php artisan crud:generator Model --all
```

Si no necesitamos ejecutar todos los ficheros obviaremos la opción --all. La terminal nos irá
preguntando qué archivos queremos generar.

``` php
php artisan crud:generator Model
```

Comando destroy crud

Si estás en una fase muy temprana del crud y has cometido algún error en la generación puedes utilizar un comando que destruye el crud entero salvo el migrations en la carpeta database. La razón por la que el archivo migration no se elimina es que lleva una parte dinámica que hace referencia al timestamp del momento de su creación.

El comando se ejecuta de la siguiente manera:

``` php
php artisan destroy:crud Model
```

- model -> Obligatorio. model hace referencia al modelo del crud en formato mayúscula, singular y camel case.

El sistema pedirá confirmación antes de realizar el proceso. Se recuerda que esta acción destruye el crud entero, por lo que hay que sopesar, si se tenía avanzado el crud, si merece la pena o no ejecutar el comando.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

### Security

If you discover any security related issues, please email isaaccamrod@gmail.com instead of using the issue tracker.

## Credits

- [Isaac Campos](https://github.com/10codesoftware)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
