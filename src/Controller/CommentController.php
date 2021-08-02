<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Comments;
use App\Entity\Votes;
use App\Form\CategoryType;
use App\Repository\BoutiqueRepository;
use App\Repository\CategoryRepository;
use App\Repository\VotesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

/**
 * @Route("/comment", name = "comment.")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("", name="new", methods={"POST"})
     */
    public function index(BoutiqueRepository $boutiqueRepository, EntityManagerInterface $em, Request $request, CategoryRepository $categoryRepository): Response
    {

        $rating = $request->request->get('rating');
        $_comment = $request->request->get('comment');
        $boutique_id = $request->request->get('boutique_id');
        $boutique = $boutiqueRepository->findOneBy(['id' => $boutique_id]);

        $votes = new Votes();
        $votes->setValue($rating);
        $votes->setBoutique($boutique);
        $votes->setUser($this->getUser());
        $votes->setComment($_comment);
        $em->persist($votes);


        $comment = new Comments();
        $comment->setContent($_comment);
        $comment->setUser($this->getUser());
        $comment->setParentId(0);
        $em->persist($comment);



        $em->flush();
        return new  JsonResponse(['status' => "mety ve "], Response::HTTP_OK);



        // var_dump($boutique);
        // var_dump($comment);

        // die;
        // $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        // $listCategories = $this->category($categoryRepository->findAll());
        // $category = new Category();
        // $form = $this->createForm(CategoryType::class, $category);
        // $form->handleRequest($request);

        // if ($form->isSubmitted()) {
        //     $category->setUser($this->getUser());
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $entityManager->persist($category);
        //     $entityManager->flush();
        //     $array = [
        //         'id' => $category->getId(),
        //         'parentId' => $category->getParentId(),
        //         'name' => $category->getName(),
        //         'type' => $category->getType(),
        //         'user' => $category->getUser(),
        //         'status' => "success"

        //     ];
        // }

        // return $this->render('admin/index.html.twig', [
        //     'categories' => $listCategories,
        //     'pages' => "category",
        //     'boutique' => $boutique,
        //     'form' => $form->createView()
        // ]);
    }

    /**
     * @Route("/remove/{id}", name="remove_vote", methods= {"POST"})
     */

    public function remove($id, VotesRepository $votesRepository)
    {
        $vote = $votesRepository->findOneBy(['id' => $id]);
        if ($vote) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($vote);
            $em->flush();
            return new JsonResponse(['status' => "ok"], Response::HTTP_OK);
        }
        return new JsonResponse(['status' => "ko"], Response::HTTP_UNAUTHORIZED);
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
     * @Route("/{id}", name="category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index');
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
