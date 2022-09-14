<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

class UserGetCollectionController extends AbstractController
{
    public function __invoke(Request $request)
    {
        $page = $request->get('page') ?? 1;
        $search = $request->get('search') ?? '';

        return $this->userRepository->findAllBySearch($search, $page);
    }
}
