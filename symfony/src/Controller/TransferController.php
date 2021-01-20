<?php

namespace App\Controller;

use App\Service\MoneyTransferService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Exception\TransactionExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TransferController extends AbstractController
{
    /** @var MoneyTransferService */
    private $moneyTransferService;
    /** @var SerializerInterface */
    private $serializer;

    /**
     * @param MoneyTransferService $moneyTransferService
     * @param SerializerInterface $serializer
     */
    public function __construct(MoneyTransferService $moneyTransferService, SerializerInterface $serializer)
    {
        $this->moneyTransferService = $moneyTransferService;
        $this->serializer = $serializer;
    }


    /**
     * @Route("/transfer", name="transfer")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $json = $request->get('json');
        $errors = [];
        if (null === $json) {
            $errors [] = 'request should contain json field';
        } else {
            $transferInput = json_decode($json, true);
            if (!is_array($transferInput)) {
                $errors[] = 'Wrong input json';
            } else {
                if (!array_key_exists('sourceWallet', $transferInput)) {
                    $errors[] = 'sourceWallet field should be provided';
                }
                if (!array_key_exists('destinationWallet', $transferInput)) {
                    $errors[] = 'destinationWallet field should be provided';
                }
                if (!array_key_exists('amount', $transferInput)) {
                    $errors[] = 'amount field should be provided';
                }
            }
        }

        if (!empty($errors)) {
            return new JsonResponse(['errors' => $errors], 400);
        }

        try {
            $moneyTransfer = $this->moneyTransferService->transferBetweenWallets(
                $transferInput['sourceWallet'],
                $transferInput['destinationWallet'],
                $transferInput['amount']
            );

            return new JsonResponse($this->serializer->serialize($moneyTransfer, 'json'), 200, [], true);
        } catch (TransactionExceptionInterface $exception) {
            return new JsonResponse(['errors' => [$exception->getMessage()]], 422);
        } catch (\Throwable $exception) {
            return new JsonResponse(['error' => [$exception->getMessage()]], 500);
        }
    }
}
