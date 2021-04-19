<?php

namespace App\Controller;

use App\Entity\ProfilJob;
use App\Entity\User;
use App\Form\CvType;
use App\Repository\BoutiqueRepository;
use App\Repository\ProfilJobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil/{id}-{slug}", name="profil")
     */
    public function index(User $user, BoutiqueRepository $boutiqueRepository, ProfilJobRepository $profilJobRepository)
    {
        $profilJob = $profilJobRepository->findOneBy(['user' => $user]);
        $lastboutique = $boutiqueRepository->findAll();
        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'boutique' => $boutiqueRepository->findOneBoutiqueByUserPerRole('ROLE_SUPER_ADMIN'),
            'lastboutique' => $lastboutique[1],
            "profilJob" => $profilJob
        ]);
    }

    /**
     * @Route("/profil/cv", name="profilViewCv", methods="GET")
     */

    public function viewPdg(ProfilJobRepository $profilJobRepository, BoutiqueRepository $boutiqueRepository)
    {
        $profilJob = $profilJobRepository->findOneBy(['user' => $this->getUser()]);
        $lastboutique = $boutiqueRepository->findAll();
        return $this->render('profil/cv.html.twig', [
            'user' => $this->getUser(),
            'boutique' => $boutiqueRepository->findOneBoutiqueByUserPerRole('ROLE_SUPER_ADMIN'),
            'lastboutique' => $lastboutique[1],
            "profilJob" => $profilJob
        ]);
    }
}
