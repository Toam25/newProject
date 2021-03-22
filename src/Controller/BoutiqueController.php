<?php

namespace App\Controller;

use App\Data\Search;
use App\Entity\Article;
use App\Entity\Boutique;
use App\Entity\Votes;
use App\Form\SearchType;
use App\Repository\ArticleRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\CartRepository;
use App\Repository\HeaderRepository;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use App\Repository\VotesRepository;
use App\Service\Cart\CartService;
use App\Service\SearchService;
use App\Service\TypeOptionMenuService;
use App\Service\UtilsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

class BoutiqueController extends AbstractController
{

    private $typeOptionMenuService;
  
    public function __construct(UtilsService $utilsService, TypeOptionMenuService $typeOptionMenuService )
    {
        $this->typeOptionMenuService=$typeOptionMenuService;
    }
    /**
     * @Route("/", name="index")
     */
    public function index(VoteRepository $voteRepository, HeaderRepository $headerRepository, BoutiqueRepository $boutiqueRepository, CartRepository $cartRepository, ArticleRepository $articleRepository)
    {

        $boutique = $boutiqueRepository->findOneBoutiqueByUserPerRole('ROLE_SUPER_ADMIN');
        $allMenu= $this->typeOptionMenuService->getTypeOptionMenu();

        $vote = $voteRepository->findAllWithUserVote();
         
        $lastboutique = $boutiqueRepository->findAll();
        $header = $headerRepository->findOneBy(['boutique' => $boutique]);
        $header_image = ($header) ? $header->getName() : "images_default/default_image.jpg";
        $articles = $articleRepository->getArticleWithVote();
        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BoutiqueController',
            'boutique' => $boutique,
            'articles' => $articles,
            'header_image' => $header_image,
            'votes' => $vote,
            'allMenus'=>$allMenu,
            'lastboutique' => $lastboutique[1]
        ]);
    }
    /**
     * @Route("/shop/{type}/{id}", name="shop", defaults = {"id"=null})
     */
    public function boutique($type = "", $id, Request $request, BoutiqueRepository $boutiqueRepository, SearchService $searchService, ArticleRepository $articleRepository)
    {


        $listShops = $this->getlistShop($boutiqueRepository->findBy(['type' => $type]), $type);

        $pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';

        if ($id == -1) {
            $boutique = $boutiqueRepository->findOneBy(['type' => $type]);
        } else {

            if ($pageWasRefreshed) {
                $shopId = $id;
            } else {
                $shopId = $id; //$this->lastShopVisited($listShops['allShopId']);
            }

            $boutique = $boutiqueRepository->findOneByWithHeaderReference($type,intval($shopId));
        }

        if ($request->get('shop_id')) {
            $article = $searchService->getResultSearch($request);
        } else {
            $article = $articleRepository->findAllArticleByBoutique($boutique);
        }



        return $this->render('boutique/boutique.html.twig', [
            'controller_name' => 'BoutiqueController',
            'boutique' => $boutique,
            'articles' => $article,
            'newArticles' => $articleRepository->findAllArticleSliderByBoutique($boutique),
            'listShop' => $listShops['listShops'],
            'type' => $type,
            'filtreCategory' => $this->getCategoryPerArticle($article),
            'menu'=>$type

        ]);
    }

    /**
     * @Route("/detail/{id}-{slug}", name="detail")
     */
    public function detail(int $id, Article $article, BoutiqueRepository $boutiqueRepository,UtilsService $utilsService, VotesRepository $votesRepository)
    {

        $votes = $votesRepository->findBy(['article' => $article]);
        
        $getNumberVote = $this->getNumberTotalVote($votes);
        
        $listOption=$this->typeOptionMenuService->getOption($utilsService->getSlug($article->getCategory()));

        return $this->render('boutique/detail.html.twig', [
            'controller_name' => 'BoutiqueController',
            'boutique' => $article->getBoutique(),
            'article' => $article,
            'votes' => $votes,
             'category'=>$listOption,
            'valuevote' => $getNumberVote["votes"],
            'users' => $getNumberVote["user"],
        ]);
    }

    /**
     * @Route("/vote/{id}/add" , name="addVote")
     */

    public function addVote(Article $article, Request $request)
    {

        if ($this->getUser() != null) {


            $comment = $request->request->get('comment');
            $value = $request->request->get('vote');

            $vote = new Votes();
            $vote->setValue($value)
                ->setComment($comment)
                ->setUser($this->getUser())
                ->setVotearticle($article);
            $article->addVote($vote);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($vote);
            $manager->flush();
            $array = ['status' => 'ok', 'msg' => 'Enregistrer avec success'];
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
    public function list(Request $request, SearchService $search)
    {

        if ($request->isXmlHttpRequest()) {


            return $this->render(
                'boutique/dependancies/_list.html.twig',
                [
                    'articles' => $search->getResultSearch($request),
                ]
            );
        }
        return $this->render(
            'boutique/list.html.twig',
            [
                'articles' => $search->getResultSearch($request),
            ]
        );
    }
        /**
     * @Route("/condition/{id}", name="condition")
     */
    public function condition(Boutique $boutique, BoutiqueRepository $boutiqueRepository)
    {
        $lastboutique = $boutiqueRepository->findAll();
        return $this->render("boutique/condition.html.twig",[
            'boutique'=>$boutique,
            'lastboutique' => $lastboutique[1]
        ]);

        
    }
    /**
     * @return array 
     */
    private function getNumberTotalVote(array $votes)
    {
        $nbr = 0;
        $five = 0;
        $four = 0;
        $tree = 0;
        $two = 0;
        $one = 0;
        $array = [];
        $user = [
            'isComment' => false
        ];
        for ($i = 0; $i < sizeof($votes); $i++) {
            if ($votes[$i]->getValue() == 5) {
                $five++;
            }
            if ($votes[$i]->getValue() == 4) {
                $four++;
            }
            if ($votes[$i]->getValue() == 3) {
                $tree++;
            }
            if ($votes[$i]->getValue() == 2) {
                $two++;
            }
            if ($votes[$i]->getValue() == 1) {
                $one++;
            }
            if ($votes[$i]->getUser() == $this->getUser()) {
                $user = [
                    'isComment' => true,
                    'comment' => $votes[$i]->getComment(),
                    'value' => $votes[$i]->getValue(),
                    'id'=>$votes[$i]->getId(),
                    'owner'=>$votes[$i]->getUser()->getId()
                ];
            }
            $nbr += $votes[$i]->getValue();
        }
        return [
            'user' => $user,
            'votes' => [
                'five' => $five,
                'four' => $four,
                'tree' => $tree,
                'two' => $two,
                'one' => $one,
                'total' => $nbr,
            ]
        ];
    }

    private function getlistShop(array $shops, $types)
    {
        $newlistShop = [];
        $myListShops = "";
        $myListShop = "";
        $allShopId = [];
        
        $first = (isset($_COOKIE[$types]) and is_numeric($_COOKIE[$types])) ? intval($_COOKIE[$types]) : 0;

        foreach ($shops as $key => $shop) {

            array_push($allShopId, $shop->getId());
            $newlistShop[$key] = '<a class="text-center" href="/shop/' . $shop->getType() . '/' . $shop->getId() . '" id="' . $key . '"> 
        <img class="logo_image_boutique_header" src="/images/' . $shop->getLogo() . '" alt="' . $shop->getName() . '">
         <br> 
         <p class="nom_boutique" style="display: block;"> ' . $shop->getName() . '</p></a>';
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
        $newlistShop['allShopId'] = $allShopId;
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
        $size = count($myarray);

        if ($size > 0) {
            for ($i = 0; $i < $size; $i++) {

                $newarray[$i] = $myarray[$begin++];

                if ($begin >= $size) {
                    $begin = 0;
                }
            }
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
}
