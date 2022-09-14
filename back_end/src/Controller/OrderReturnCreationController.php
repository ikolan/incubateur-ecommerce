<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DataTransformer\OrderReturnInputDataTransformer;
use App\Entity\Order;
use App\Entity\OrderReturn;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class OrderReturnCreationController extends AbstractController
{
    public function __invoke(Order $data, Request $request, ValidatorInterface $validator)
    {
        if (null !== $data->getOrderReturn()) {
            throw new ConflictHttpException('Already existing order return request.');
        }

        $orderReturn = (new OrderReturnInputDataTransformer($validator))
            ->transform(json_decode($request->getContent()), OrderReturn::class);

        $orderReturn->setOrderR($data);
        $this->em->persist($orderReturn);
        $this->em->flush();

        return $data;
    }
}
