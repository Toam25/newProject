<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Notification;
use App\Entity\Notify;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use App\Service\NotificationService;
use App\Service\TypeOptionMenuService;
use App\Service\UtilsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{   
    private $typeOptionMenuService;
    private $utilsService;
    public function __construct(UtilsService $utilsService, TypeOptionMenuService $typeOptionMenuService )
    {
        $this->typeOptionMenuService=$typeOptionMenuService;
        $this->utilsService=$utilsService;
    }
      
    /**
     * @Route("/addBlog", name="addBlog")
     */
    public function addBlog(BoutiqueRepository $boutiqueRepository)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );

        $blogForm = $this->createForm(BlogType::class,new Blog);
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        return $this->render('admin/index.html.twig', [
            'pages' => 'blogAdd',
            'boutique' => $boutique,
            'blogForm'=>$blogForm->createView()
        ]);
    }

    /**
     * @Route("/edit/blog/{id}", name="editBlog")
     */
    public function editBlog(BlogRepository $blogRepository,$id, BoutiqueRepository $boutiqueRepository,Request $request)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        
        if(in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles())){
            $blog= $blogRepository->findOneBy(['id'=>$id ]);
        }
        else{
            $blog= $blogRepository->findOneBy(['boutique'=>$boutique,'id'=>$id ]);
        }
        $blogForm = $this->createForm(BlogType::class,$blog);
        
        if($blogForm->isSubmitted() and $request->isXmlHttpRequest()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();
            return new JsonResponse(['status'=>"success"]);
        }
    
        return $this->render('admin/index.html.twig', [
            'pages' => 'blogEdit',
            'boutique' => $boutique,
            'id_blog'=>$blog->getId(),
            'blog'=>$blog,
            'blogForm'=>$blogForm->createView()
        ]);
    }
    /**
     * @Route("admin/list/Blog", name="listBlog")
     */
    public function listBlog(BoutiqueRepository $boutiqueRepository,BlogRepository $blogRepository)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );
    
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        return $this->render('admin/index.html.twig', [
            'pages' => 'bloglist',
            'boutique' => $boutique,
            'blogs'=>$blogRepository->findAllBlogByBoutique($boutique)
            //'blogForm'=>$blogForm->createView()
        ]);
    }
    /**
     * @Route("superadmin/list/Blog", name="validateBlog")
     */
    public function listValidateBlog(BoutiqueRepository $boutiqueRepository,BlogRepository $blogRepository)
    {

        
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        return $this->render('admin/index.html.twig', [
            'pages' => 'blogValidatelist',
            'boutique' => $boutique,
            'blogs'=>$blogRepository->findBy(['validate'=>false])
            
        ]);
    }
    /**
     * @Route("/superadmin/validate/blog/{id}", name="superadminvalidateBlog", methods="POST")
     */
    public function validateBlog(Blog $blog, UserRepository $userRepository)
    {     
        $users= $userRepository->findAll();
         if($blog->getValidate()==false){

            $notification = new Notification(); 
            $notification->setSubject('APPROUVED');
            $notification->addToUser($blog->getBoutique()->getUser());
            $notification->setFromUser($this->getUser()->getId());
            $notification->setCreatedAt(new \DateTime());
            $notification->setDescription($blog->getId());
            $blog->setValidate(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($notification);
            $em->flush();
            
            return  new JsonResponse(['status'=>'success']);
         }

         else {
            return  new JsonResponse(['status'=>'error', 'message'=>"Blog déjà approuvé"], 401 );
         }
            
    }
    /**
     * @Route("/preview/blog/{id}", name="previewBlog")
     */
    public function previewBlog(BoutiqueRepository $boutiqueRepository,Blog $blog)
    {
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        return $this->render('admin/index.html.twig', [
            'pages' => 'blogpreview',
            'boutique' => $boutique,
            'blog'=>$blog
        ]);
    }
}
