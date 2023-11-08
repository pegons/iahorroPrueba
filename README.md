<h1> Descripcion del proyecto </h1>

Se trata de una refactorización a codigo DDD de un proyecto que contiene un CRUD de leads.

Todo esta implementado en una primera instancia en el controlador, por lo que ya es una pista que no sigue arquitectura hexagonal ni buenas practicas (Hay proyectos que no hay porque usar DDD obligatoriamente, pero en este caso la prueba era el refactor)

Lo primero que se ve son las rutas tipicas de CRUD, asi que la primera idea (a mi me gusta mas así) es crear un controlador por cada metodo que vayamos a usar. Segun el principio de Responsabilidad unica una clase tiene que tener
una unica funcion, y en este caso tambien me gusta aplicarselo a los controladores.

Una vez creado el controlador, lo siguiente es usar la inversion de dependencias, es decir inyeccion de interfaces en lugar de clase. Asi el dia de mañana si se quiere cambiar solo hay que cambiar la clase y que implemente de ella.
Ademas, laravel es capaz de resolver en tiempo de ejecucion la clase que define la interfaz, por lo que podiamos tener varios applicacion service dependiendo de por ejemplo el tipo de cliente y resolverse en tiempo de ejecucion, para diferentes implementaciones de un Use Case.

El flujo que sigue es Controlador -> Application Service -> Repository  (Respetando Arquitectura Hexagonal Infrastructura -> Application -> Domain) (Repositorio es interfaz y pertenece a Dominio, otra cosa es la implementacion que hagamos luego que es de infraestructura)

Al separarlo en application service y domain, podemos testear unitariamente cada clase. Esto seria parte de los test unitarios que he añadido usando mock para simular los repositorios.

Aparte he agregado test E2E para testear los endpoint de la api, esto unido a una config de docker correcta que permita crear una bbdd provisional en los pipelines de un repositorio, nos puede servir (Si las migraciones estan bien hechas desde el principio) para tener test de cobertura muy alta (algo mas lentos porque en cada test se resetea la BBDD, pero muy precisos) (Es bueno tener alguno pero no tantos, hay que seguir la piramide de test dando prio a los unitarios que son los mas rapidos)

He creado dos repositorios uno de ellos de eloquent (Aunque suelo trabajar con query builder sin modelos, pero por si la prueba requeria modelos de eloquent los he agregado, pero suele verse mas como un anti pattern)
Y otro de Api ya que no tenia claro quien era el encargado de calcular el score de un Lead, y se lo he dejado a una API EXTERNA (Simulada, la implementacion es un aleatorio como se puede ver xD)

La api y las rutas he intentado que sean REST FULL y los nombres de application acompañarlos de POST, PUT, DELETE, GET ...

Para el manejo de errores, he añadido exceptions personalizadas, en caso de ser una api utilizada por un FRONT-END, siempre esta bien definir un codigo de error en algun fichero para que devuelva el codigo de error, y que sea FRONT quien lo pinta a partir del listado de codigos (EJ : 100001 => CLIENTE NO ENCONTRADO ), pero para la prueba he añadido excepciones basicas.

No deberia haber codigo repetido, ya que cada funcion es reutilizable, y como cada application service tiene una funcion muy definida, deberia ser abierto a extension y cerrado a modificacion, por lo que si hubiera que hacer algo mas podria encargarse la API GATEWAY de llamar a diferentes controladores, o el mismo controlador.

Iba a añadir eventos de dominio, por si se trabaja con Arquitectura basada a eventos, pero no me da tiempo. De todas formas en mi github tengo un ejemplo donde creo un consumer y un encolador. En este caso un ejemplo podria ser
una vez se crea un Lead crear un evento de dominio de creacin de leads, que se encole por ejemplo en rabbitmq y que si hay algun consumer de email por ejemplo, pues diga, me has creado un lead, voy a lanzar un email de bienvenida(por ejemplo, hay muchos mas usos)

He incluido por ultimo una notacion swagger de open api para poder generar la docu directamente, ya que tenemos DTOs y podemos usarlos para definir las peticiones, y en el propio controlador las diferentes respuestas.



