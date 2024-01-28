<?php

namespace App\Controller;

use App\Entity\FeedbackRequest;
use App\Model\FeedbackRequestDTO;
use App\Model\UpdateFeedbackRequestDTO;
use App\Service\FeedbackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FeedbackController extends AbstractController
{
    private FeedbackService $feedbackService;

    private SerializerInterface $serializer;

    private ValidatorInterface $validator;


    public function __construct(FeedbackService $feedbackService, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->feedbackService = $feedbackService;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('/api/v1/requests', name: 'app_request_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $feedbackRequest = $this->serializer->deserialize($request->getContent(), FeedbackRequestDTO::class, 'json');
        $feedbackRequest->setUserId($this->getUser()->getId());

        $errors = $this->validator->validate($feedbackRequest);

        if (count($errors) > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return $this->json($errorMessages, Response::HTTP_BAD_REQUEST);
        }

        return $this->json(
            $this->feedbackService->create($feedbackRequest),
            Response::HTTP_CREATED
        );
    }

    #[Route('/api/v1/requests/{request_id}', name: 'app_request_get', methods: ['GET'])]
    public function get(Request $request, int $request_id): Response
    {
        $requestData = $this->feedbackService->getById($request_id);

        if (!$requestData){
            return $this->json(['message' => 'Request not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['request' => $requestData], Response::HTTP_OK);
    }

    #[Route('/api/v1/requests/user', name: 'app_request_by_user', methods: ['GET'])]
    public function getRequestsByUser(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $requests = $this->feedbackService->getByUser($user->getId());

        if (!$requests) {
            return $this->json(['message' => 'User has no requests'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($requests, Response::HTTP_OK);
    }

    #[Route('/api/v1/requests/status/{request_id}', name: 'app_request_status', methods: ['GET'])]
    public function getRequestsStatus(Request $request, int $request_id): Response
    {
        $request_status = $this->feedbackService->getRequestStatus($request_id);

        if (!$request_status) {
            return $this->json(['message' => 'Request not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['status' => $request_status], Response::HTTP_OK);
    }

    #[Route('/api/v1/manager/requests', name: 'app_request_all', methods: ['GET'])]
    public function getAll(Request $request): Response
    {
        return $this->json($this->feedbackService->getAll(), Response::HTTP_OK);
    }

    #[Route('/api/v1/manager/requests/{request_id}', name: 'app_request_update', methods: ['PUT'])]
    public function update(Request $request, int $request_id): Response
    {

        $requestData = $this->feedbackService->getById($request_id);

        if (!$requestData) {
            return $this->json(['message' => 'Request not found'], Response::HTTP_NOT_FOUND);
        }

        $updateData = $this->serializer->deserialize($request->getContent(), UpdateFeedbackRequestDTO::class, 'json');

        if (!$requestData->getComment() && !$updateData->getComment()) {
            return $this->json(['message' => 'Need comment'], Response::HTTP_BAD_REQUEST);
        }

        $validStatuses = [
            FeedbackRequest::STATUS_NEW,
            FeedbackRequest::STATUS_PROCESSING,
            FeedbackRequest::STATUS_RESOLVED,
        ];

        if (!in_array($updateData->getStatus(), $validStatuses, true)) {
            return $this->json(['message' => 'Wrong status'], Response::HTTP_BAD_REQUEST);
        }

        $requestData->setStatus($updateData->getStatus());
        $requestData->setComment($updateData->getComment() ?: $requestData->getComment());

        $this->feedbackService->update($requestData);

        return $this->json(['message' => 'Request updated'], Response::HTTP_OK);
    }
}
