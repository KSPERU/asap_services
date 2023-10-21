<?php

namespace App\Twig\Components;

use App\Entity\Persona;
use App\Form\ProveedorType;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsLiveComponent('proveedor_form', template: 'components/proveedor_form.html.twig')]
class ProveedorFormComponent extends AbstractController
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
            ProveedorType::class,
            $this->persona
        );
    }
    
}
