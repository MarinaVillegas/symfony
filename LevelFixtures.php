<?php

namespace App\DataFixtures;
use App\Entity\Level;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LevelFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $levels = [
    [
        'title' => 'Despertar Confuso',
        'description' => 'Despiertas con un leve mareo y el silencio de tu habitación parece más profundo que de costumbre. La luz de la mañana entra suavemente por la ventana, dibujando sombras extrañas sobre el suelo. Al girar la cabeza, notas un móvil sobre la mesita de noche. Está cargado, como si alguien lo hubiera dejado esperando que lo descubrieras. La curiosidad y un ligero escalofrío recorren tu espalda pero cuando lo miras descubres que está bloqueado y se muestra un pequeño mensaje que parece una pista:',
        'riddle' => 'Rosalía, tu hermoso nombre me recuerda aquel regalo que una vez te hice y rechazaste...',
        'solution' => 'rosa',
        'hint' => 'Piensa en el nombre, en él se oculta el regalo de una flor que le hizo',
        'order' => 1
    ],
    [
        'title' => 'La carpeta oculta',
        'description' => 'Mientras examinas el móvil con cuidado, te das cuenta de que contiene fotografías tuyas corriendo y en las notas aparecen los horarios en los que sueles correr y algo que llama tu atención, parece ser una nota dirigida a ti: "Querida Rosalía: Sabía que el primer acertijo sería fácil de resolver, mi intención no era hacértelo difícil pues quería que supieras que cometiste un grave error al rechazar mi rosa. Cada acertijo será más difícil pero confío en que el destino te ayudará si mereces salir de aquí, para cuando salgas, si es que logras, yo ya estaré muy lejos. Para abrir la carpeta oculta debes resolver el siguiente acertijo"',
        'riddle' => 'Sigue cada indicio con cuidado, no te apresures. Observar detalles te permitirá avanzar. La salida está más cerca de lo que imaginas.',
        'solution' => 'sol',
        'hint' => 'La palabra que se forma con las primeras letras',
        'order' => 2
    ],
    [
        'title' => 'El móvil misterioso',
        'description' => 'Después de abrir la carpeta oculta, descubres que puedes acceder a los ajustes de la red pero te pide una contraseña, el nombre de la red es "minombreeslaclave". Debes buscar el nombre de tu vecino y hay solamente una habitación donde estabas que esta vacía y una biblioteca en frente.',
        'riddle' => '¿Dónde quieres buscar primero?',
        'solution' => 'biblioteca',
        'hint' => 'La habitación está vacía y sólo queda un lugar por buscar...',
        'order' => 3
    ],
    [
        'title' => 'Búsqueda del nombre',
        'description' => 'Al entrar en la biblioteca, notas que todo está ordenado alfabéticamente: los anaqueles siguen su ritmo perfecto... excepto una sola fila. Allí los lomos de los 5 libros parecen haber sido colocados a propósito: no guardan orden. El resto de los libros están llenos de polvo pero estos están limpios, te parece que es una pista así que examinas los títulos con cuidado...',
        'riddle' => 'Oblomov (Goncharov) Siddhartha (Hermann Hesse) Cien años de soledad (Gabriel García Márquez) A Clockwork Orange (Anthony Burgess) Rayuela (Julio Cortázar)',
        'solution' => 'oscar',
        'hint' => 'En los títulos se esconde el nombre',
        'order' => 4
    ],
    [
        'title' => 'Búsqueda del nombre',
        'description' => 'Tras introducir la contraseña del protagonista, decides llamar a emergencias, pero la app no tiene acceso al micrófono y nadie puede escucharte. La operadora intenta rastrear tu llamada mientras tu móvil vibra: es un mensaje de él, amenazando con tu mascota y obligándote a colgar. Desesperada, le suplicas que no le haga daño y él te recuerda que solo quedan dos mensajes, permitiéndote hacer una pregunta. Preguntas cómo salir de allí y él responde con una frase que parece un acertijo: “Los niños creen que saltando un dibujo en el suelo se puede llegar al cielo. Pero dime… ¿me agradecerías por tenerte encerrada aunque yo dijera amarte?” El mensaje te hace pensar de inmediato en un juego, y esa asociación te lleva corriendo a la biblioteca, donde recuerdas la fila mal colocada y el nombre oculto formado por los títulos: Rayuela.',
        'riddle' => 'Buscas el libro de Cortázar con desesperación y, al abrirlo, encuentras la página 302 marcada con la frase “Mirá que agradecerle al que lo tiene enjaulado”, y en ese instante comprendes que la única palabra que debes enviar como respuesta es:',
        'solution' => 'gracias',
        'hint' => 'El te tiene encerrada y cree que deberías agradecerle porque te "ama" al igual que tú amas a tu mascota y la tienes "encerrada"',
        'order' => 5
    ],
];

        foreach ($levels as $l) {
            $level = new Level();
            $level->setTitle($l['title']);
            $level->setDescription($l['description']);
            $level->setRiddle($l['riddle']);
            $level->setSolution($l['solution']);
            $level->setHint($l['hint']);
            $level->setOrderNumber($l['order']);
            $manager->persist($level);
        }

        $manager->flush();
    }
}

