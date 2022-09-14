<?php

namespace App\IdGenerator;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Symfony\Component\Uid\Uuid;

/**
 * Id generator for UUIDs.
 */
class UuidGenerator extends AbstractIdGenerator
{
    public function generateId(EntityManagerInterface $em, $entity): string
    {
        $uuid = Uuid::v4();

        if (null !== $em->getRepository(null !== $entity ? get_class($entity) : self::class)->find($uuid)) {
            $uuid = $this->generateId($em, $entity);
        }

        return $uuid;
    }
}
