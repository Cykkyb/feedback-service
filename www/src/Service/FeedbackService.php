<?php

namespace App\Service;

use App\Entity\FeedbackRequest;
use App\Model\FeedbackRequestDTO;
use App\Repository\FeedbackRequestRepository;
use App\Repository\UserRepository;
use http\Env\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FeedbackService
{
    private FeedbackRequestRepository $feedbackRepository;

    private ValidatorInterface $validator;

    private UserRepository $userRepository;

    public function __construct(
        FeedbackRequestRepository $feedbackRepository,
        UserRepository $userRepository,
        ValidatorInterface $validator,
    )
    {
        $this->feedbackRepository = $feedbackRepository;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function create(FeedbackRequestDTO $feedbackRequestDTO): FeedbackRequest
    {
        $user = $this->userRepository->find($feedbackRequestDTO->getUserId());

        if ($user === null) {
            throw new UserNotFoundException( 'User with id ' . $feedbackRequestDTO->getUserId() . ' not found');
        }

        $feedbackRequest = new FeedbackRequest();
        $feedbackRequest->setTopic($feedbackRequestDTO->getTopic());
        $feedbackRequest->setUserId($user->getId());
        $feedbackRequest->setMessage($feedbackRequestDTO->getMessage());
        $feedbackRequest->setStatus(FeedbackRequest::STATUS_NEW);

        $errors = $this->validator->validate($feedbackRequest);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            throw new BadRequestHttpException(implode(', ', $errorMessages));
        }

        $this->feedbackRepository->create($feedbackRequest);

        return $feedbackRequest;
    }

    public function getById(int $request_id): ?FeedbackRequest
    {
        return $this->feedbackRepository->find($request_id);
    }

    public function getByUser(int $user_id): array
    {
        return $this->feedbackRepository->findBy(['user_id' => $user_id]);
    }

    public function getRequestStatus(int $request_id): ?string
    {
        $request = $this->getById($request_id);

        if (empty($request)){
            return null;
        }

        return $request->getStatus();
    }

   public function getAll(): ?array
   {
       return $this->feedbackRepository->findAll();
   }

   public function update(FeedbackRequest $feedbackRequest): void
   {
       $this->feedbackRepository->update();
   }

}