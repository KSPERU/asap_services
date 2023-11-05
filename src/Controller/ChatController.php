<?php

namespace App\Controller;

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
        return $this->render('chat/index.html.twig', [
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
        // $entityManager->flush();
        $hub->publish(new Update(
            [
                sprintf("/chat/conversacion/%s", $conversacion->getId()),
            ],
            $this->renderView('chat/turbo/all_users_chat_success.stream.html.twig', [
                'participantes' => $recipient,
            ]),
        ));

        // $conversaciones = $conversacionRepository->findConversationsByUser($this->getUser()->getId());

        // $hub->publish(new Update(
        //     [
        //         sprintf("/chat/conversacion"),
        //     ],
        //     $this->renderView('chat/turbo/conversacion_success.stream.html.twig', [
        //         'conversacion' => $conversaciones
        //     ]),
        // ));

        // $ultimoMensaje = $conversacion->getUltimoMensajeId()->getMensaje();
        // $conversacionId = $conversacion->getId();
        // $update = new Update(
        //     sprintf('https://example.com/conversacion/%s', $conversacionId),
        //     json_encode(['status' => 'mensaje recuperado', 'uMensaje' => $ultimoMensaje, 'conversationId' => $conversacionId,
        //     'conversacion' => $conversacion])
        // );
        // $hub->publish($update);

        //Mandar nuevamente el repositorio de conversacion para que se actualice todo
        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

            return $this->render('chat/turbo/chat_success.stream.html.twig');
        }
        return $this->redirectToRoute('app_chat');
    }

    #[Route('/chat/conversacion', name: 'app_chat_conversacion')]
    public function conversacion(ConversacionRepository $conversacionRepository, Request $request, HubInterface $hub): Response
    {
        $conversaciones = $conversacionRepository->findConversationsByUser($this->getUser()->getId());

        // $update = new Update(
        //     sprintf("/chat/conversacion"),
        //     $this->renderView('chat/conversacion.html.twig', [
        //         'conversacion' => $conversaciones,
        //     ]),
        // );
        // if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
        //     // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
        //     $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
        //     return $this->render('chat/conversacion_success.stream.html.twig', ['conversacion' => $conversaciones]);
        // }
        // $hub->publish($update);

        return $this->render('chat/conversacion.html.twig', [
            'conversacion' => $conversaciones,  
        ]);
    }
    // #[Route('turbo/chat/conversacion/{id}', name: 'app_chat_conversacion_turbo', methods: ['POST'])]
    // public function publish(HubInterface $hub, ConversacionRepository $conversacionRepository): Response
    // {
    //     $conversaciones = $conversacionRepository->findConversationsByUser($this->getUser()->getId());

    //     $hub->publish(new Update(
    //         [
    //             sprintf("/chat/conversacion"),
    //         ],
    //         $this->renderView('chat/turbo/conversacion_success.stream.html.twig', [
    //             'conversacion' => $conversaciones
    //         ]),
    //     ));
    //     return $this->redirectToRoute('app_chat_conversacion');
    // }

    // #[Route('chat/publish', name: 'publish')]
    // public function publish(HubInterface $hub, ConversacionRepository $conversacionRepository): Response
    // {
    //     $conversaciones = $conversacionRepository->findConversationsByUser($this->getUser()->getId());
    //     $update = new Update(
    //         'https://example.com/books/1',
    //         json_encode(['status' => 'mensaje recuperado',
    //         'conversacion' => $conversaciones])
    //     );

    //     $hub->publish($update);
        
    //     return $this->json('Published!');
    // }
}
