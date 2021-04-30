<?php

namespace App\Controller;

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

    public function getAllUser(UserRepository $userRepository, Request $request)
    {

        $users = $userRepository->findAllUser($request->query->get('q'));
        $data = [];

        for ($i = 0; $i < sizeof($users); $i++) {
            if (sizeof($users[$i]->getBoutiques()) > 0) {
                $name = $users[$i]->getBoutiques()[0]->getName();
                $image = $users[$i]->getBoutiques()[0]->getLogo();
            } else {
                $name = $users[$i]->getName() . " " . $users[$i]->getFirstname();
                $image = $users[$i]->getAvatar();
            }

            array_push($data, [
                "name" => $name,
                "id" => $users[$i]->getId(),
                "image" => $image
            ]);
        }
        return new JsonResponse($data);
    }
}
