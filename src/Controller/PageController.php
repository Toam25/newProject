<?php

namespace App\Controller;

use App\Entity\Page;
use App\Form\PageType;
use App\Repository\BoutiqueRepository;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{

    /**
     * @Route("/admin/add/formation", name="add_formation")
     */
    public function add_formation(BoutiqueRepository $boutiqueRepository)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );
        $pageForm = $this->createForm(PageType::class, new Page);
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);

        return $this->render('admin/index.html.twig', [
            'pages' => 'add_page',
            'boutique' => $boutique,
            'formForm' => $pageForm->createView()

        ]);
    }
    /**
     * @Route("/admin/edit/formation/{id}", name="edit_formation")
     */
    public function edit_formation(int $id, BoutiqueRepository $boutiqueRepository, PageRepository $pageRepository)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        $page = $pageRepository->findOneBy(['boutique' => $boutique, 'id' => $id]);
        $pageForm = $this->createForm(PageType::class, $page);
        return $this->render('admin/index.html.twig', [
            'pages' => 'edit_page',
            'boutique' => $boutique,
            'formForm' => $pageForm->createView()
        ]);
    }
    /**
     * @Route("/admin/list/formation", name="list_formation", methods={"GET"})
     */
    public function list_formation(BoutiqueRepository $boutiqueRepository, PageRepository $pageRepository)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        $pages = $pageRepository->findBy(['boutique' => $boutique]);

        return $this->render('admin/index.html.twig', [
            'pages' => 'list_page',
            'boutique' => $boutique,
            'pagesListes' => $pages
        ]);
    }
}
