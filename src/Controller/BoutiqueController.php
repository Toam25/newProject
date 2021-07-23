<?php

namespace App\Controller;

use App\Data\Search;
use App\Entity\Article;
use App\Entity\Blog;
use App\Entity\Boutique;
use App\Entity\Page;
use App\Entity\Video;
use App\Entity\Votes;
use App\Form\BlogType;
use App\Form\PageType;
use App\Form\SearchType;
use App\Repository\ArticleRepository;
use App\Repository\BlogRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\CartRepository;
use App\Repository\HeaderRepository;
use App\Repository\PageRepository;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use App\Repository\VoteRepository;
use App\Repository\VotesRepository;
use App\Service\Cart\CartService;
use App\Service\SearchService;
use App\Service\TypeOptionMenuService;
use App\Service\UtilsService;
use App\Service\VotesService;
use ProxyManager\Factory\RemoteObject\Adapter\JsonRpc;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Validator\Constraints\Length;

class BoutiqueController extends AbstractController
{

    private $typeOptionMenuService;
    private $utilsService;

    public function __construct(UtilsService $utilsService, TypeOptionMenuService $typeOptionMenuService)
    {
        $this->typeOptionMenuService = $typeOptionMenuService;
        $this->utilsService = $utilsService;
    }
    /**
     * @Route("/", name="index")
     */
    public function index(VoteRepository $voteRepository, BlogRepository $blogRepository, HeaderRepository $headerRepository, BoutiqueRepository $boutiqueRepository, CartRepository $cartRepository, ArticleRepository $articleRepository)
    {

        $boutique = $boutiqueRepository->findOneBoutiqueByUserPerRole('ROLE_SUPER_ADMIN');
        $allMenu = $this->typeOptionMenuService->getTypeOptionMenu();

        $vote = $voteRepository->findAllWithUserVote();

        $header = $headerRepository->findOneBy(['boutique' => $boutique]);
        $header_image = ($header) ? $header->getName() : "images_default/default_image.jpg";
        $articles = $articleRepository->getArticleWithVote();
        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BoutiqueController',
            'boutique' => $boutique,
            'articles' => $articles,
            'header_image' => $header_image,
            'votes' => $vote,
            'allMenus' => $allMenu,
            'blogs' => $blogRepository->findByBlogValidateInHomePage()
        ]);
    }
    /**
     * @Route("/shop/{type}/{id}-{slug}", name="shop", defaults = {"id"=null,"slug": "slug"})
     */

    public function boutique($type = "", $id, Request $request, BlogRepository $blogRepository, BoutiqueRepository $boutiqueRepository, SearchService $searchService, ArticleRepository $articleRepository)
    {

        $pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
        $blog = "";
        $matches = [];
        $first = intval($request->cookies->get($type));
        $boutiques = [];
        $slideShops = [];

        if ($type != "") {

            $boutiques = $boutiqueRepository->findBy(['type' => $type]);
            $id = ($id != null and is_numeric($id)) ? intval($id) : $first;
            $boutique = $boutiqueRepository->findOneByWithHeaderReference($type, intval($id));


            if ($boutique == null) {
                $boutique = $boutiqueRepository->findOneByWithHeaderReference($type, intval($boutiques[0]->getId()));
            }

            if (!$pageWasRefreshed) {
                $boutique->setNbrOfVisitor($boutique->getNbrOfVisitor() + 1);
                $em = $this->getDoctrine()->getManager();
                $em->flush($boutique);
            }
            $isHomeShop = false;
            $listShops = $this->getlistShop($boutiques, $type);
        } else {
            $boutique = $boutiqueRepository->findOneBoutiqueByUserPerRole("ROLE_SUPER_ADMIN");
            $listShops = $this->getlistShop($boutiqueRepository->findAllBoutiqueWithOutUserRoleSuperAdmin("ROLE_ADMIN"), $type);
            $isHomeShop = true;
            $type = "g_marchande";
            $boutiques = $boutiqueRepository->findAllBoutiqueWithOutUserRoleSuperAdmin("ROLE_ADMIN");
            $slideShops = $boutiques;
        }



        if ($request->get('shop_id')) {
            $article = $searchService->getResultSearch($request);
        } else {

            $article = $articleRepository->findAllArticleByBoutique($boutique);
            $blogs = $blogRepository->findByValidate($boutique);
        }

        if ($boutique) {
            preg_match('%(http[s]?:\/\/|www\/)([a-zA-Z0-9-_\.\/\?=&]+)%i', $boutique->getExternalLink(), $matches);
        }

        $shopLink = "";

        if ($boutique->getDetailOffer() != "Gratuit") {
            $shopLink = sizeof($matches) > 2 ? $matches[2] : "";
        }

        return $this->render('boutique/boutique.html.twig', [
            'controller_name' => 'BoutiqueController',
            'boutique' => $boutique,
            'articles' => $article,
            'newArticles' => $articleRepository->findAllArticleSliderByBoutique($boutique),
            'listShop' => $listShops['listShops'],
            'type' => $type,
            'filtreCategory' => $this->getCategoryPerArticle($article),
            'menu' => $type,
            'isHomeShop' => $isHomeShop,
            'blogs' => $blogs,
            'shops' => $boutiques,
            'slideShop' => $slideShops,
            'shopLink' => $shopLink,
            'rangeEchantillon' => $this->uniqueRandomNumbersWithinRange(0, sizeof($boutiques) - 1, 4)

        ]);
    }

    /**
     * @Route("/show/blog/{id}-{slug}", name="showBlog")
     */
    public function showBlog($id, BlogRepository $blogRepository, VotesService $votesService, VotesRepository $votesRepository)
    {

        // $allArticle = $articleRepository->findBy(['boutique'=>$boutiqueRepository->findOneBy(['user'=>$this->getUser()])] );

        $blog = $blogRepository->findOneBy(['id' => $id, "validate" => true]);
        if ($blog) {


            $votes = $votesRepository->findBy(['blog' => $blog]);
            $astuce = $blogRepository->findBy(['boutique' => $blog->getBoutique(), "category" => "Astuces", "validate" => true], ["id" => "DESC"]);
            $view = $blogRepository->findBy(['boutique' => $blog->getBoutique(), "validate" => true], ["view" => "DESC"]);
            $share = $blogRepository->findBy(['boutique' => $blog->getBoutique(), "validate" => true], ["shareNbr" => "DESC"], 5);
            $getNumberVote = $votesService->getNumberTotalVote($votes);




            return $this->render('boutique/detailblog.html.twig', [
                'blog' => $blog,
                'views' => $view,
                'shares' => $share,
                'boutique' => $blog->getBoutique(),
                'astuces' => $astuce,
                'votes' => $votes,
                'users' => $getNumberVote["user"],
                'valuevote' => $getNumberVote["votes"],
                'sondages' => $this->persisteSondageBlog($view),
                'page' => 'blog'
            ]);
        }
        return new Response("Page temporairement indisponible<a href='/ '> Retour</a>", Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/detail/{id}-{slug}", name="detail")
     */
    public function detail(int $id, Article $article, VotesService $votesService, BoutiqueRepository $boutiqueRepository, UtilsService $utilsService, VotesRepository $votesRepository)
    {

        $votes = $votesRepository->findBy(['article' => $article]);

        $getNumberVote = $votesService->getNumberTotalVote($votes);

        $listOption = $this->typeOptionMenuService->getOption($utilsService->getSlug($article->getCategory()));

        return $this->render('boutique/detail.html.twig', [
            'controller_name' => 'BoutiqueController',
            'boutique' => $article->getBoutique(),
            'article' => $article,
            'votes' => $votes,
            'category' => $listOption,
            'valuevote' => $getNumberVote["votes"],
            'users' => $getNumberVote["user"],
        ]);
    }

    /**
     * @Route("/galery_marchande/shop", name="shopsGaleryMarchande", methods={"GET"})
     */
    public function shopsGaleryMarchande(BoutiqueRepository $boutiqueRepository, SearchService $search, Request $request)
    {
        $boutiques = $search->getResultSearchForShops($request);

        return $this->render('boutique/dependancies//list_shop_galery_marchande.html.twig', [
            'shops' => $boutiques
        ]);
    }


    /**
     * @Route("/infographie", name="infographie", methods={"GET"})
     */
    public function infogratphie()
    {

        return $this->render('infographie/index.html.twig', [
            'infographie' => "infographie"
        ]);
    }

    /**
     * @Route("/galery_marchande/shop/nbr", name="shopsGaleryMarchandeNbr", methods={"GET"})
     */
    public function shopsGaleryMarchandeNbr(BoutiqueRepository $boutiqueRepository, SearchService $search, Request $request)
    {
        $boutiques = $search->getResultSearchForShops($request);
        $nbr = sizeof($boutiques) != "" ? sizeof($boutiques)  : 0;
        return new JsonResponse(['nbr' => $nbr], Response::HTTP_OK);
    }

    /**
     * @Route("/myoffer/", name="my_offer", methods={"GET","POST"})
     */
    public function myOffer(BoutiqueRepository $boutiqueRepository, Request $request, MailerInterface $mailer): response
    {

        if ($request->isXmlHttpRequest()) {
            $name = $request->request->get('name');
            $mail = $request->request->get('email');
            $subject = $request->request->get('subject');
            $description = $request->request->get('message', '');
            $email = (new TemplatedEmail())
                ->from($mail)
                ->to('contact@toutenone.com')
                ->subject($subject)
                ->htmlTemplate('email/offer.html.twig')
                ->context([
                    'name' => $name,
                    'mail' => $mail,
                    'description' => $description
                ]);
            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                return new JsonResponse("Erreur de connexion au serveur", Response::HTTP_UNAUTHORIZED);
            }
            return new JsonResponse(['status' => 'Ok']);
        }
        return $this->render('boutique/offer.html.twig', [
            'boutique' => $boutique = $boutiqueRepository->findOneBoutiqueByUserPerRole('ROLE_SUPER_ADMIN')
        ]);
    }

    /////////////////////

    /**
     * @Route("/list/video/{id}-{slug}", name="list_video_shop", methods={"GET"})
     */
    public function showVideoShop(Boutique $boutique, VideoRepository $videosRepository): response
    {
        $matches = [];
        $shopLink = "";
        if ($boutique) {
            preg_match('%(http[s]?:\/\/|www\/)([a-zA-Z0-9-_\.\/\?=&]+)%i', $boutique->getExternalLink(), $matches);
        }

        if ($boutique->getDetailOffer() != "Gratuit") {
            $shopLink = sizeof($matches) > 2 ? $matches[2] : "";
        }
        return $this->render('boutique/page.html.twig', [
            'boutique' => $boutique,
            'videos' => $videosRepository->findBy(['boutique' => $boutique]),
            'shopLink' => $shopLink
        ]);
    }
    /**
     * @Route("/list/formations/{id}-{slug}", name="list_formation_shop", methods={"GET"})
     */
    public function listFormation(Boutique $boutique, PageRepository $pageRepository): response
    {

        $matches = [];
        $shopLink = "";

        if ($boutique) {
            preg_match('%(http[s]?:\/\/|www\/)([a-zA-Z0-9-_\.\/\?=&]+)%i', $boutique->getExternalLink(), $matches);
        }

        if ($boutique->getDetailOffer() != "Gratuit") {
            $shopLink = sizeof($matches) > 2 ? $matches[2] : "";
        }
        return $this->render('boutique/page.html.twig', [
            'boutique' => $boutique,
            'formations' => $pageRepository->findPageBy($boutique),
            'shopLink' => $shopLink,
        ]);
    }
    /**
     * @Route("/list/contrib/{id}-{slug}", name="list_contrib_shop", methods={"GET"})
     */
    public function listContrib(Boutique $boutique, BlogRepository $blogRepository): response
    {

        $matches = [];
        $form = $this->createForm(PageType::class, new Page);
        if ($boutique) {
            preg_match('%(http[s]?:\/\/|www\/)([a-zA-Z0-9-_\.\/\?=&]+)%i', $boutique->getExternalLink(), $matches);
        }
        return $this->render('boutique/page.html.twig', [
            'boutique' => $boutique,
            'page' => 'contrib',
            'shopLink' => sizeof($matches) > 2 ? $matches[2] : "",
            'formForm' => $form->createView(),
            'blogs' => $blogRepository->findAll()
        ]);
    }

    /**
     * @Route("/view/formation/{id}-{slug}", name="view_formation_shop", methods={"GET"})
     */
    public function viewFormation(Page $page): response
    {
        $matches = [];
        $boutique = $page->getBoutique();
        $shopLink = "";
        if ($boutique) {
            preg_match('%(http[s]?:\/\/|www\/)([a-zA-Z0-9-_\.\/\?=&]+)%i', $boutique->getExternalLink(), $matches);
        }

        if ($boutique->getDetailOffer() != "Gratuit") {
            $shopLink = sizeof($matches) > 2 ? $matches[2] : "";
        }
        if ($page) {
            $page->setView($page->getView() + 1);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->render('boutique/page_view.html.twig', [
                'boutique' => $boutique,
                'page' => $page,
                'shopLink' => $shopLink
            ]);
        } else {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
    }
    /**
     * @Route("/list/other/{id}-{slug}", name="list_other_shop", methods={"GET"})
     */
    public function viewOther(PageRepository $pageRepository, $id): response
    {
        return new JsonResponse(['status' => "OK"]);
        // $page = $pageRepository->findOneBy(['id' => $id]);
        // // if ($page == null) {
        // //     return new JsonResponse(['status' => 'ko']);
        // // }
        // $matches = [];
        // $boutique = $page->getBoutique();
        // $shopLink = "";
        // if ($boutique) {
        //     preg_match('%(http[s]?:\/\/|www\/)([a-zA-Z0-9-_\.\/\?=&]+)%i', $boutique->getExternalLink(), $matches);
        // }
        // if ($boutique->getDetailOffer() != "Gratuit") {
        //     $shopLink = sizeof($matches) > 2 ? $matches[2] : "";
        // }
        // if ($page) {
        //     $page->setView($page->getView() + 1);
        //     $em = $this->getDoctrine()->getManager();
        //     $em->flush();
        //     return $this->render('boutique/page_view.html.twig', [
        //         'boutique' => $boutique,
        //         'page' => $page,
        //         'shopLink' => $shopLink
        //     ]);
        // } else {
        //     return new Response('', Response::HTTP_NOT_FOUND);
        // }
    }

    //////////////////////////////
    /**
     * @Route("/vote/{id}/add" , name="addVote")
     */

    public function addVote(Article $article, Request $request, VotesRepository $votesRepository)
    {

        if ($this->getUser() != null) {

            $comment = $request->request->get('comment');
            $value = $request->request->get('vote');
            $manager = $this->getDoctrine()->getManager();

            $vote = $votesRepository->findOneBy(['user' => $this->getUser(), 'article' => $article]);
            if ($vote) {
                $vote->setValue($value)
                    ->setComment($comment)
                    ->setUser($this->getUser())
                    ->setVotearticle($article);
                $article->addVote($vote);
                $manager->flush();
            } else {
                $vote = new Votes();
                $vote->setValue($value)
                    ->setComment($comment)
                    ->setUser($this->getUser())
                    ->setVotearticle($article);
                $article->addVote($vote);
                $manager->persist($vote);
                $manager->flush();
            }

            $array = ['status' => 'ok', 'msg' => 'Enregistrer avec success', 'id' => $vote->getId()];
            $code = 200;
        } else {
            $array = ['status' => 'ko', 'msg' => 'Unautorized'];
            $code = 402;
        }
        return  new Response(json_encode($array), $code, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/vote/blog/{id}/add" , name="addVoteBlog")
     */

    public function addVoteBlog(Blog $blog, Request $request, VotesRepository $votesRepository)
    {

        if ($this->getUser() != null) {

            $comment = $request->request->get('comment');
            $value = $request->request->get('vote');
            $manager = $this->getDoctrine()->getManager();

            $vote = $votesRepository->findOneBy(['user' => $this->getUser(), 'blog' => $blog]);
            if ($vote) {
                $vote->setValue($value)
                    ->setComment($comment)
                    ->setUser($this->getUser());
                $blog->addVote($vote);
                $manager->flush();
            } else {
                $vote = new Votes();
                $vote->setValue($value)
                    ->setComment($comment)
                    ->setUser($this->getUser());
                $blog->addVote($vote);
                $manager->persist($vote);
                $manager->flush();
            }

            $array = ['status' => 'ok', 'msg' => 'Enregistrer avec success', 'id' => $vote->getId()];
            $code = 200;
        } else {
            $array = ['status' => 'ko', 'msg' => 'Unautorized'];
            $code = 402;
        }
        return  new Response(json_encode($array), $code, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/product/list", name="list_product")
     */
    public function list(Request $request, BoutiqueRepository $boutiqueRepository, SearchService $search)
    {

        $q = $request->query->get('q') ?? "";

        switch ($request->query->get('searchtype')) {
            case 'blog': {


                    if ($request->isXmlHttpRequest()) {

                        return $this->render(
                            'boutique/dependancies/_list.html.twig',
                            []
                        );
                    }

                    return $this->render(
                        'boutique/list.html.twig',
                        [
                            'blogs' => $search->getResultSearchForBlog($request),
                            'boutique' => $boutiqueRepository->findOneBoutiqueByUserPerRole('ROLE_SUPER_ADMIN'),
                            'type' => 'blog',
                            'search' => $q,
                        ]
                    );
                }
                break;
            case 'shop': {


                    if ($request->isXmlHttpRequest()) {

                        return $this->render(
                            'boutique/dependancies/_list.html.twig',
                            []
                        );
                    }

                    return $this->render(
                        'boutique/list.html.twig',
                        [
                            'shops' => $search->getResultSearchForShops($request),
                            'boutique' => $boutiqueRepository->findOneBoutiqueByUserPerRole('ROLE_SUPER_ADMIN'),
                            'type' => 'shop',
                            'search' => $q,
                        ]
                    );
                }
                break;

            default: {

                    $article = $search->getResultSearch($request);
                    if ($request->isXmlHttpRequest()) {

                        return $this->render(
                            'boutique/dependancies/_list.html.twig',
                            [
                                'articles' => $article,

                            ]
                        );
                    }

                    return $this->render('boutique/list.html.twig', [
                        'articles' => $article,
                        'boutique' => $boutiqueRepository->findOneBoutiqueByUserPerRole('ROLE_SUPER_ADMIN'),
                        'filtreCategory' => $this->getCategoryPerArticle($article),
                        'type' => 'article',
                        'search' => $q,

                    ]);
                }
                break;
        }
    }

    /**
     * @Route("/blog/list", name="_blog_list")
     */
    public function _blogList(Request $request, SearchService $search)
    {


        if ($request->isXmlHttpRequest()) {

            return $this->render(
                'boutique/dependancies/_listblogs.html.twig',
                [
                    'blogs' => $search->getResultSearchForBlog($request),
                ]
            );
        }
        return new JsonResponse(['status' => "error"], Response::HTTP_UNAUTHORIZED);
        /* return $this->render(
            'boutique/list.html.twig',
            [
                'articles' => $search->getResultSearch($request),
            ]
        );*/
    }
    /**
     * @Route("/condition/{id}", name="condition")
     */
    public function condition(Boutique $boutique, BoutiqueRepository $boutiqueRepository)
    {
        $lastboutique = $boutiqueRepository->findAll();
        return $this->render("boutique/condition.html.twig", [
            'boutique' => $boutique,
            'lastboutique' => $lastboutique[1]
        ]);
    }

    /**
     * @Route("/forgetPassword", name="forgetPassword", methods={"GET","POST"})
     */
    public function forgetPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer, TokenGeneratorInterface $tokenGeneratorInterface): Response
    {

        if ($request->isXmlHttpRequest()) {
            $mail = $request->request->get('recupmessage');
            $user = $userRepository->findOneBy(['email' => $mail]);

            if ($user !== null) {
                $token = $tokenGeneratorInterface->generateToken();
                $user->setResetToken($token);

                $email = (new TemplatedEmail())
                    ->from('no-reply@toutenone.com')
                    ->to($mail)
                    ->subject('Recuperation mot de passe')
                    ->htmlTemplate('email/send_mail_confirmation.html.twig')
                    ->context([
                        'user' => $user
                    ]);

                try {

                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    return new JsonResponse(["message" => "Erreur de connexion au serveur"], Response::HTTP_UNAUTHORIZED);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $this->json(["message" => "Un email de reinitialisation de mot de passe a eté envoyé à " . $user->getEmail()], Response::HTTP_CREATED);
            } else {
                return $this->json(['status' => 'ko', 'message' => 'Utilisateur introuvable'], Response::HTTP_UNAUTHORIZED);
            }
        }
        return  $this->json(['status' => 'ko', 'message' => 'Utilisateur introuvable'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @Route("/resetpassword/{token}", name="app_reset_password", methods={"GET","POST"})
     */
    public function resetPassWord(UserRepository $userRepository, UserPasswordEncoderInterface $passdecoder, $token = "", Request $request)
    {
        $user = $userRepository->findOneBy(['resetToken' => $token]);
        // dd($request->request->get('new_password'));
        if ($user !== null and $token != "") {
            if ($request->isXmlHttpRequest()) {

                $user->setResetToken("");
                $user->setPassword($passdecoder->encodePassword($user, $request->request->get('new_password')));
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->json(['status' => 'ok', 'message' => 'Mot de passe changé avec success']);
            }
        } else {
            return $this->render('email/get_new_password.html.twig', [
                'error' => 'error',
            ]);
        }

        return $this->render('email/get_new_password.html.twig', [
            'token' => $token,
        ]);
    }
    private function getlistShop(array $shops, $types)
    {
        $newlistShop = [];
        $myListShops = "";
        $myListShop = "";
        $allShopId = [];

        $first = (isset($_COOKIE[$types]) and is_numeric($_COOKIE[$types])) ? intval($_COOKIE[$types]) : 0;

        foreach ($shops as $key => $shop) {

            if ($key == 0) {
                $firstShop = $shop->getId();
            }
            array_push($allShopId, $shop->getId());
            $shopSli = '<a class="text-center" href="/shop/' . $shop->getType() . '/' . $shop->getId() . '-' . $this->utilsService->getSlug($shop->getName()) . '"> 
            <div class="_container_image_shop">
                 <img class="logo_image_boutique_header" src="/images/' . $shop->getLogo() . '" alt="' . $shop->getName() . '">';
            if ($shop->isActiveNow()) {
                $shopSli .= '<div class="onLigne"></div>';
            }
            $shopSli .= '<div class="' . $shop->getDetailOffer() . '"></div></div>
         <br> 
         <p class="nom_boutique" style="display: block;"> ' . $shop->getName() . '</p></a>';

            $newlistShop[$key] = $shopSli;
        }

        if (isset($newlistShop) and sizeof($newlistShop) > 0) {
            $results = $this->changePlace($newlistShop, $first);
            for ($i = 0; $i < count($results); $i++) {
                $myListShop .= $results[$i];
            }
            $newlistShop['listShops'] = $myListShop;
        } else {
            $newlistShop['listShops'] = " <p>Pas de résultat :(  </p>";
        }
        $newlistShop['allShopId'] = $this->changePlace($allShopId, $first);

        return $newlistShop;
    }

    private function getCategoryPerArticle(array $articles)
    {
        $list = [];
        foreach ($articles as $article) {

            $sous_category_existe = false;
            foreach ($list as $key => $listcategory) {
                if (array_key_exists($article->getSousCategory(), $listcategory)) {
                    $sous_category_existe = true;
                    $sous_category = $article->getSousCategory();
                    $key_sous_category = $key;
                }
            }
            if ($sous_category_existe) {
                $type =  $article->getType() ?? 'pas de type';
                if (!in_array($type, $list[$key_sous_category][$sous_category])) {
                    array_push(
                        $list[$key_sous_category][$sous_category],
                        $type

                    );
                }
            } else {
                array_push($list, [
                    $article->getSousCategory() => [
                        $article->getType()
                    ]
                ]);
            }
        }
        return $this->htlmCategory($list);
    }
    private function htlmCategory(array $list)
    {

        $html = "<div>";
        $html .= "<label for='search' class=' '>Recherche</label>";
        $html .= "<input id='search' type='text' placeholder='Recherche' name='q' class='form-control' />";
        $html .= "<label for='min_price ' class=' '>Prix</label>";
        $html .= "<div class='containt_price '>";
        $html .= "<input type='number' placeholder='min' name='min_price' class='form-control' />";
        $html .= "<input type='number' placeholder='max' name='max_price' class='form-control'/>
       </div>";

        $html .= "</div>";
        $html .= "<ul>";
        for ($i = 0, $j = 0; $i < sizeof($list); $i++) {
            foreach ($list[$i] as $keys => $values) {
                $html .= "<label for='category" . $j . "' class=' form-check-label '>" . ucfirst(mb_strtolower($keys, 'UTF-8')) . "</label>";
                $html .= "<ul class=''>";
                foreach ($values as $key => $value) {
                    $j++;
                    $html .= "<li class=''>";
                    $html .= "<input id='category" . $j . "' class='' type='checkbox' name='type[]' value='" . $value . "'><label for='category" . $j . "'class='form-check-label'>" . ucfirst(mb_strtolower($value, 'UTF-8')) . "</label>";
                    $html .= "<li>";
                }
                $j++;
                $html .= "</ul>";
            }
        }
        $html .= "</ul>";
        return $html;
    }

    function changePlace($myarray, $begin)
    {
        $newarray = [];
        $size = sizeof($myarray);

        if ($size > 1) {
            for ($i = 0; $i < $size; $i++) {

                $newarray[$i] = $myarray[$begin++];

                if ($begin >= $size) {
                    $begin = 0;
                }
            }
        } else {
            $newarray = $myarray;
        }

        return $newarray;
    }

    private function lastShopVisited(array $listeboutique)
    {
        if (sizeof($listeboutique) > 0) {
            $last_shop_visited = $listeboutique[0];
            for ($i = 0; $i < sizeof($listeboutique); $i++) {
                if (isset($_COOKIE['visited_shop'])) {
                    $my_array_shop = unserialize($_COOKIE['visited_shop']);
                    if (!in_array($listeboutique[$i], $my_array_shop)) {

                        array_push($my_array_shop, $listeboutique[$i]);
                        setcookie('visited_shop', serialize($my_array_shop));
                        $last_shop_visited = $listeboutique[$i];
                        break;
                    }
                    if ($i == sizeof($listeboutique) - 1) {
                        setcookie('visited_shop', serialize([]));
                        $my_array_shop = [];
                        array_push($my_array_shop, $listeboutique[0]);
                        setcookie('visited_shop', serialize($my_array_shop));
                        $last_shop_visited = $listeboutique[0];
                    }
                } else {
                    $my_array_shop = [];
                    array_push($my_array_shop, $listeboutique[$i]);
                    setcookie('visited_shop', serialize($my_array_shop));
                    $last_shop_visited = $listeboutique[$i];

                    break;
                }
            }
        } else {
            return "";
        }

        return $last_shop_visited;
    }

    public function persisteSondageBlog(array $blogs, $nbr = 3)
    {
        $newBlogs = [];
        $returnBlogs = [];
        $nbrTotalBlog = 0;

        for ($i = 0; $i < sizeof($blogs); $i++) {
            $total = $this->getNumberTotalVote($blogs[$i])['total'];
            $nbrTotalBlog += $total;
            array_push($newBlogs, [
                'title' => $blogs[$i]->getTitle(),
                'id' => $blogs[$i]->getId(),
                'slug' => $blogs[$i]->getLink(),
                'total' => $total
            ]);
        }
        $total = [];
        foreach ($newBlogs as $key => $value) {
            $total[$key] = $value['total'];
        }
        array_multisort($total, SORT_DESC, $newBlogs);

        foreach ($newBlogs as $key => $value) {

            if ($key == $nbr) {
                break;
            }
            array_push($returnBlogs, $value);
        }
        return [
            'blogs' => $returnBlogs,
            'total' => $nbrTotalBlog
        ];
    }

    public function getNumberTotalVote(Blog $blog)
    {
        $votes = $blog->getVotes();
        $nbrTotalVote = 0;
        for ($i = 0; $i < sizeof($votes); $i++) {
            $nbrTotalVote += $votes[$i]->getValue();
        }
        $newNbrTotalVote = [
            'total' => $nbrTotalVote,
        ];
        return $newNbrTotalVote;
    }

    function uniqueRandomNumbersWithinRange($min, $max, $quantity)
    {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }
}
