<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Image;
use App\Entity\Product;
use App\Entity\Role;
use App\Entity\Status;
use App\Entity\User;
use App\Repository\AddressRepository;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use App\Repository\RoleRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Base class for controllers. It will get all needed repositories during construction.
 */
abstract class AbstractController extends SymfonyAbstractController
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var AddressRepository */
    protected $addressRepository;

    /** @var UserRepository */
    protected $userRepository;

    /** @var RoleRepository */
    protected $roleRepository;

    /** @var ProductRepository */
    protected $productRepository;

    /** @var ImageRepository */
    protected $imageRepository;

    /** @var StatusRepository */
    protected $statusRepository;

    /** @var SerializerInterface */
    protected $serializer;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->addressRepository = $em->getRepository(Address::class);
        $this->userRepository = $em->getRepository(User::class);
        $this->roleRepository = $em->getRepository(Role::class);
        $this->productRepository = $em->getRepository(Product::class);
        $this->imageRepository = $em->getRepository(Image::class);
        $this->statusRepository = $em->getRepository(Status::class);
        $this->serializer = $serializer;
    }
}
