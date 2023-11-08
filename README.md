<h1> Puesta en marcha </h1>
- Inicializamos el proyecto con:

        composer install (Requiere php 8.1)

- Configuracion de la BBDD en el .env

- Ejecutamos las migraciones y los seeders si se necesita:

        php artisan migrate:fresh --seed

- Ahora para no tener que usar postman ni ningun cliente de peticiones, he creado todos los test E2E (Carpeta test/E2E)

  Dentro de estos test, estan las llamadas y las comprobaciones de datos que el endpoint funciono correctamente.

  Podemos lanzarlo uno por uno con

          vendor/bin/phpunit --filter "Nombre del test"

  Lanzar la suite de test que he creado (Unitarios o E2E) (Faltaria por añadir para mas consistencia los de integracion que se lanzarian sobre los repositorios con sus correspondientes comprobaciones)

          vendor/bin/phpunit --testsuite E2E

  O todos en general con:

          vendor/bin/phpunit



<h1>Descripción del proyecto</h1>
Se trata de una refactorización a código DDD de un proyecto que contiene un CRUD de leads.

Todo está implementado en una primera instancia en el controlador y usa directamente modelos Eloquent, por lo que ya es una pista de que no sigue arquitectura hexagonal ni buenas prácticas (hay proyectos que no hay por qué usar DDD obligatoriamente, pero en este caso la prueba era el refactor).

Lo primero que se ve son las rutas típicas de CRUD, así que la primera idea (a mí me gusta más así) es crear un controlador por cada método que vayamos a usar. Según el principio de Responsabilidad Única, una clase tiene que tener una única función, y en este caso también me gusta aplicárselo a los controladores.

Una vez creado el controlador, lo siguiente es usar la inversión de dependencias, es decir, inyección de interfaces en lugar de clases. Así, el día de mañana, si se quiere cambiar, solo hay que cambiar la clase y que implemente de ella. Además, Laravel es capaz de resolver en tiempo de ejecución la clase que define la interfaz, por lo que podríamos tener varios application services dependiendo de, por ejemplo, el tipo de cliente y resolverse en tiempo de ejecución para diferentes implementaciones de un Use Case.

El flujo que sigue es Controlador -> Application Service -> Repository (Respetando Arquitectura Hexagonal: Infraestructura -> Aplicación -> Dominio). El Repositorio es interfaz y pertenece al Dominio; otra cosa es la implementación que hagamos luego, que es de infraestructura.

Al separarlo en application service y dominio, podemos testear unitariamente cada clase. Esto sería parte de los tests unitarios que he añadido, usando mocks para simular los repositorios.

Aparte, he agregado tests E2E para testear los endpoints de la API. Esto, unido a una configuración de Docker correcta que permita crear una base de datos provisional en los pipelines de un repositorio, nos puede servir (si las migraciones están bien hechas desde el principio) para tener tests de cobertura muy alta (algo más lentos porque en cada test se resetea la base de datos, pero muy precisos). Es bueno tener alguno, pero no tantos; hay que seguir la pirámide de tests, dando prioridad a los unitarios que son los más rápidos.

He creado dos repositorios: uno de ellos de Eloquent (aunque suelo trabajar con query builder sin modelos, pero por si la prueba requería modelos de Eloquent, los he agregado, aunque suele verse más como un antipatrón), y otro de API, ya que no tenía claro quién era el encargado de calcular el score de un Lead, y se lo he dejado a una API EXTERNA (simulada; la implementación es un aleatorio, como se puede ver xD).

La API y las rutas he intentado que sean RESTful, y los nombres de application services, acompañarlos de POST, PUT, DELETE, GET...

Para el manejo de errores, he añadido excepciones personalizadas. En caso de ser una API utilizada por un front-end, siempre está bien definir un código de error en algún fichero para que devuelva el código de error, y que sea el front-end quien lo pinte a partir del listado de códigos (ej.: 100001 => CLIENTE NO ENCONTRADO), pero para la prueba he añadido excepciones básicas.

No debería haber código repetido, ya que cada función es reutilizable, y como cada application service tiene una función muy definida, debería ser abierto a extensión y cerrado a modificación. Por lo que, si hubiera que hacer algo más, podría encargarse la API Gateway de llamar a diferentes controladores, o el mismo controlador.

Iba a añadir eventos de dominio, por si se trabaja con arquitectura basada en eventos, pero no me da tiempo. De todas formas, en mi GitHub tengo un ejemplo donde creo un consumidor y un encolador. En este caso, un ejemplo podría ser: una vez se crea un Lead, crear un evento de dominio de creación de leads, que se encole por ejemplo en RabbitMQ, y que si hay algún consumidor de email, por ejemplo, pues diga: "Me has creado un lead, voy a lanzar un email de bienvenida" (por ejemplo, hay muchos más usos).

He incluido, por último, una notación Swagger de OpenAPI para poder generar la documentación directamente, ya que tenemos DTOs y podemos usarlos para definir las peticiones, y en el propio controlador, las diferentes respuestas.

Hay algunos detalles que, dependiendo de quién implemente, puede tomar un camino u otro. Hay una parte del código donde, en lugar de usar un repositorio directamente para, por ejemplo, obtener el score, intento darle utilidad al dominio de Lead, para que sea el mismo quien tiene la potestad de generarse su Score. Entonces, dejamos dominios menos anémicos (solo getters y setters y constructores). Por lo que, al pasarle el repositorio (realmente la interfaz), él puede encargarse de generarse su propio score, por ejemplo.

También, hablando de dominio, he creado varios Value Objects básicos como Phone o Email, en lugar de usar strings directamente, ya que pueden ser útiles, por ejemplo, para autovalidarse o añadir funcionalidad a futuro sobre ese objeto un poco más compacto que un atributo primitivo.




