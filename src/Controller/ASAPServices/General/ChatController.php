<?php

namespace App\Controller\ASAPServices\General;

use App\Entity\Chat;
use App\Entity\Conversacion;
use App\Repository\ChatRepository;
use App\Repository\ConversacionRepository;
use App\Repository\ParticipanteRepository;
use App\Repository\UsuarioRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\TurboBundle;

class ChatController extends AbstractController
{
    #[Route('/chat/conversacion/{id}', name: 'app_chat')]
    public function __invoke(Conversacion $conversacion, ParticipanteRepository $participanteRepository, ConversacionRepository $conversacionRepository): Response
    {
        $recipient = $participanteRepository->findMensajesByConversacionIdAndUserId(
            $conversacion->getId(),
            $this->getUser()->getId()
        );
        // $conversacion = $conversacionRepository->find($id);

        $fotoUser = $this->getUser()->getIdPersona()->getPFoto();
        if(empty($fotoUser)){
            $this->addFlash('error', "Para acceder a las conversaciones necesitas una foto");
            $this->addFlash('redirect', true);
        }
        
        // Verifica si el usuario actual es un participante válido en la conversación
        $participantes = $conversacion->getParticipantes();
        $usuarioEsParticipante = false;

        foreach ($participantes as $participante) {
            if ($participante->getUsuarioId() === $this->getUser()) {
                $usuarioEsParticipante = true;
                break;
            }
        }
        if (!$usuarioEsParticipante) {
            throw new \Exception("No estas incluido en la conversación");
        }
        return $this->render('asap_services/general/chat/index.html.twig', [
            // 'form' => $form,
            // 'messages' => $chatRepository->findBy([], ['fecha_creacion' => 'DESC'], 20),
            'participantes' => $recipient,
            'conversacion' => $conversacion
         ]);
    }

    #[Route('turbo/chat/{id}', name: 'app_chat_turbo', methods: ['POST'])]
    public function chat(Conversacion $conversacion, ConversacionRepository $conversacionRepository, Request $request,ParticipanteRepository $participanteRepository, HubInterface $hub, EntityManagerInterface $entityManager): Response
    {
        // $conversaciones = $conversacionRepository->findByUsuario($this->getUser());
        // $conversacion = $conversacionRepository->find($id);
        $recipient = $participanteRepository->findMensajesByConversacionIdAndUserId(
            $conversacion->getId(),
            $this->getUser()->getId()
        );
        $message = $request->request->get('chat-name');
        $chat = new Chat();
        $chat->setUsuarioId($this->getUser());
        // $chat->setConversacionId($conversacionRepository->find($conversacion->getId()));
        $chat->setMensaje($message);

        $conversacion->addChat($chat); 
        $conversacion->setUltimoMensajeId($chat);
        $entityManager->persist($chat);
        $entityManager->flush();
        $hub->publish(new Update(
            [
                sprintf("/chat/conversacion/%s", $conversacion->getId()),
            ],
            $this->renderView('asap_services/general/chat/turbo/all_users_chat_success.stream.html.twig', [
                'participantes' => $recipient,
            ]),
        ));
        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

            return $this->render('asap_services/general/chat/turbo/chat_success.stream.html.twig');
        }
        return $this->redirectToRoute('app_chat');
    }

    #[Route('/chat/conversacion', name: 'app_chat_conversacion')]
    public function conversacion(ConversacionRepository $conversacionRepository, ParticipanteRepository $participanteRepository, Request $request, HubInterface $hub): Response
    {
        $conversaciones = $conversacionRepository->findConversationsByUser($this->getUser()->getId());

        $fotoUser = $this->getUser()->getIdPersona()->getPFoto();
        if(empty($fotoUser)){
            $this->addFlash('error', "Para acceder a las conversaciones necesitas una foto");
            $this->addFlash('redirect', true);
        }
        return $this->render('asap_services/general/chat/conversacion.html.twig', [
            'conversacion' => $conversaciones,  
        ]);
    }
}
