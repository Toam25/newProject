<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\BoutiqueRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("gestion/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/{type}", name="category", methods={"GET","POST"})
     */
    public function index(string $type, BoutiqueRepository $boutiqueRepository, Request $request, CategoryRepository $categoryRepository): Response
    {
        if ($type == "product" || $type == "blog" || $type == "video"  || $type == "formation") {

            $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
            $listCategories = $this->category($categoryRepository->findBy(['boutique' => $boutique, 'type' => $type]));
            // dump($request->request->get("type"));
            // dd($request);
            $category = new Category();
            $form = $this->createForm(CategoryType::class, $category);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($request->request->get("type") == "add") {
                    $category->setUser($this->getUser());
                    $category->setBoutique($boutique);
                    $category->setType($type);
                } else {

                    $parentId = $category->getParentId();
                    $name = $category->getName();
                    $category = $categoryRepository->findOneBy(['id' => $parentId]);
                    $category->setName($name);
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($category);
                $entityManager->flush();
                return  $this->render('admin/dependencies/categoryList.html.twig', [
                    'categories' => $this->category($categoryRepository->findBy(['boutique' => $boutique, 'type' => $type]))
                ]);
            }
            return $this->render('admin/index.html.twig', [
                'categories' => $listCategories,
                'pages' => "category",
                'boutique' => $boutique,
                'form' => $form->createView()
            ]);
        } else {
            $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $category->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            $array = [
                'id' => $category->getId(),
                'parentId' => $category->getParentId(),
                'name' => $category->getName(),
                'type' => $category->getType(),
                'user' => $category->getUser(),

            ];
            return new  JsonResponse(['status' => "success", ['response'] => $array], Response::HTTP_OK);
        }
        return new  JsonResponse(['status' => "error"], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{type}/{id}", name="category_delete", methods={"POST"})
     */
    public function delete(Request $request, string $type, int $id, Category $category, BoutiqueRepository $boutiqueRepository, CategoryRepository $categoryRepository): Response
    {
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        return  $this->render('admin/dependencies/categoryList.html.twig', [
            'categories' => $this->category($categoryRepository->findBy(['boutique' => $boutique, 'type' => $type]))
        ]);
    }
    private function category(array $listCategories)
    {
        $list = [];
        foreach ($listCategories as $category) {
            $list[$category->getId()] = $category;
        }
        foreach ($listCategories as $key => $category) {
            if ($category->getParentId() != 0) {
                $list[$category->getParentId()]->children[] = $category;
                unset($listCategories[$key]);
            }
        }
        return $listCategories;
    }
}
