<?php

namespace App\Controller;

use App\Repository\BoutiqueRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/user", name="api.user.")
 */
class UserApiController extends AbstractController
{
    /**
     * @Route("/", name="user",methods={"GET"})
     */

    public function getAllUser(UserRepository $userRepository, BoutiqueRepository $boutiqueRepository, Request $request)
    {

        $users = $userRepository->findAllUser($request->query->get('q'));
        $data = [];

        for ($i = 0; $i < sizeof($users); $i++) {
            if (sizeof($users[$i]->getBoutiques()) > 0) {

                array_push($data, [
                    "name" => $users[$i]->getBoutiques()[0]->getName(),
                    "id" => $users[$i]->getId(),
                    "image" => $users[$i]->getBoutiques()[0]->getLogo(),
                ]);
            }
        }
        return new JsonResponse($data);
    }
}
