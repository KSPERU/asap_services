<?php

namespace App\DataFixtures;

use App\Entity\Metodocobro;
use App\Entity\Servicio;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $servicioData = [
            ['Electricidad', 'https://homesolution.net/blog/wp-content/uploads/2019/01/Fotolia_83248213_Subscription_Monthly_M.jpg'],
            ['Pintura', 'https://pinturashipopotamo.es/wp-content/uploads/2020/01/como-elegir-un-buen-pintor.jpg'],
            ['Gasfitería', 'https://perufacility.com/wp-content/uploads/2022/05/Servicios-Gasfiteria.png'],
            ['Carpintería', 'https://ferreco.com/modules/hiblog/views/img/upload/original/972d35fab6e09157adc989e67bdbb251.jpg'],
            ['Limpieza', 'https://www.servinet.cat/wp-content/uploads/2018/04/fotopostdesinfeccio-865x540.jpg'],
            ['Jardinería', 'https://cursos.com/wp-content/uploads/2020/12/funciones-de-un-jardinero.jpg'],
            ['Reparación', 'https://sp-ao.shortpixel.ai/client/to_webp,q_glossy,ret_img,w_520,h_380/https://serviciotecnicoparalima.com/wp-content/uploads/2020/12/mantenimiento-de-cocinas.png'],
            ['Cuidado Personal', 'https://www.marronyblanco.com/wp-content/uploads/2023/07/Milar-Comelsa-lanza-un-canal-de-YouTube-dedicado-al-cuidado-personal.jpg'],
        ];

        foreach($servicioData as $data){
            $servicio = new Servicio();
            $servicio->setSvNombre($data[0]);
            $servicio->setSvimagen($data[1]);
            $manager->persist($servicio);
        }

        $servicioBanco = [
            ['BCP'],
            ['BBVA'],
            ['Interbank']
        ];

        foreach ($servicioBanco as $banco) {
            $mc = new Metodocobro();
            $mc->setMcDescripcion($banco[0]);
            $manager->persist($mc);
        }
        
        $manager->flush();
    }
}
