<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BoutiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil/{id}", name="profil")
     */
    public function index(User $user, BoutiqueRepository $boutiqueRepository)
    {   
        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'boutique' => $boutiqueRepository->findOneBoutiqueByUserPerRole('ROLE_SUPER_ADMIN'),
        ]);
    }
}
