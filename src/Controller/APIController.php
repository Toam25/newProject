<?php

namespace App\Controller;

use App\Entity\Header;
use App\Repository\BoutiqueRepository;
use App\Repository\EsArticleRepository;
use App\Repository\HeaderRepository;
use App\Service\InsertFileServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api")
 */
class APIController extends AbstractController
{
    /**
     * @Route("/es_article/get/{category}", name="get_es_article")
     */
    public function getEsArticle(string $category, EsArticleRepository $esArticleRepository)
    {
        $es_article = $esArticleRepository->findBy(['category' => $category]);
        $data = [];
        foreach ($es_article as $key => $value) {
            $data[$key]['type'] = $value->getType();
            $data[$key]['category'] = $value->getCategory();
            $data[$key]['image'] = $value->getImage();
        }
        return new Response(json_encode($data));
    }
    /**
     * @Route("/header_image/edit", name="edit_images_header")
     */

    public function editImagesHeaderAdmin(Request $request, HeaderRepository $headerRepository, BoutiqueRepository $boutiqueRepository, InsertFileServices $insertFileServices)
    {


        $images = $request->files->get('header_image');

        if ($this->getUser()) {
            $file = $insertFileServices->insertFile($images, ['jpeg', 'jpg', 'gif', 'png']);

            if ($file != false) {
                $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
                $header = $headerRepository->findOneBy(['boutique' => $boutique]);

                if ($header) {
                    $header->setName($file);
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                } else {
                    $header = new Header();
                    $header->setName($file);
                    $header->setBoutique($boutique);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($header);
                    $em->flush();
                }
                return new JsonResponse(['images' => $file]);
            } else {
                return new JsonResponse(['message' => "form fichier invalide"], Response::HTTP_NOT_ACCEPTABLE);
            }
        }

        return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
    }
    /**
     * @Route("/header_image/delete/{id}", name="header_delete", methods={"DELETE"})
     */
    public function header_delete($id,Header $header): Response
    {
        if ($this->getUser() and $header->getId()==$id) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($header);
            $entityManager->flush();
            return new JsonResponse(['status'=>'success'],Response::HTTP_OK);

        }
        else{
            return new JsonResponse(['status'=>'error'],Response::HTTP_NOT_EXTENDED);
        }

       
    }
}
