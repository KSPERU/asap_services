<?php

namespace App\Twig\Components;

use App\Entity\Persona;
use App\Form\ClienteType;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsLiveComponent('cliente_form', template: 'components/cliente_form.html.twig')]
class ClienteFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use LiveCollectionTrait;

    #[LiveProp(fieldName: 'formData')]
    public ?Persona $persona;

    public function __construct(){
        
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            ClienteType::class,
            $this->persona
        );
    }
    
}
