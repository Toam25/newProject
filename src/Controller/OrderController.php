<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Blog;
use App\Entity\Boutique;
use App\Entity\EsArticle;
use App\Entity\Header;
use App\Entity\Images;
use App\Entity\Menu;
use App\Entity\Notification;
use App\Entity\Page;
use App\Entity\ProfilJob;
use App\Entity\User;
use App\Entity\UserCondition;
use App\Entity\UserVote;
use App\Entity\Video;
use App\Entity\Vote;
use App\Entity\Votes;
use App\Repository\ArticleRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\CartRepository;
use App\Repository\EsArticleRepository;
use App\Repository\HeaderRepository;
use App\Repository\ImagesRepository;
use App\Repository\MenuRepository;
use App\Repository\NotificationRepository;
use App\Repository\PageRepository;
use App\Repository\ProfilJobRepository;
use App\Repository\ReferenceRepository;
use App\Repository\SliderRepository;
use App\Repository\SocialNetworkRepository;
use App\Repository\UserConditionRepository;
use App\Repository\UserRepository;
use App\Repository\UserVoteRepository;
use App\Repository\VoteRepository;
use App\Service\ArticlePerShopService;
use App\Service\CategoryOptionService;
use App\Service\InsertFileServices;
use App\Service\NotificationService;
use App\Service\TypeOptionMenuService;
use App\Service\UtilsService;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use ProxyManager\Factory\RemoteObject\Adapter\JsonRpc;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Response as BrowserKitResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin", name="")
 */
class OrderController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/order" , name ="order")
     */
    public function order(BoutiqueRepository $boutiqueRepository, CartRepository $cartRepository)
    {
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        $orders = $cartRepository->findBy(['boutique' => $boutique, 'type' => 'order'], ['id' => 'DESC']);

        return $this->render('admin/index.html.twig', [
            'pages' => 'order',
            'boutique' => $boutique,
            'orders' => $orders
        ]);
    }
    /**
     * @Route("/order/{id}-{slug}" , name ="detail")
     */
    public function detail(BoutiqueRepository $boutiqueRepository, CartRepository $cartRepository, $id)
    {
        $boutique = $boutiqueRepository->findOneBy(['user' => $this->getUser()]);
        $order = $cartRepository->findOneByIdAndBoutiqueAndType($id, $boutique, 'order');

        // $order = $cartRepository->findOneBy(['id' => $id, 'boutique' => $boutique, 'type' => 'order'], ['id' => 'DESC']);

        return $this->render('admin/index.html.twig', [
            'pages' => 'view_order',
            'boutique' => $boutique,
            'order' => $order
        ]);
    }
}
