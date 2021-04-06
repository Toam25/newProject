<?php 
namespace App\Twig;

use App\Entity\Boutique;
use App\Repository\MenuRepository;
use App\Service\CategoryOptionService;
use App\Service\TypeOptionMenuService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;

class SettingOptionTwig extends AbstractExtension{
       
    private $twig;
    private $typeOptionMenuService;
    private $categoryOptionService;
    private $menuRepository;

    public function __construct(  Environment $twig,MenuRepository $menuRepository, TypeOptionMenuService $typeOptionMenuService,CategoryOptionService $categoryOptionService )
    {
        
        $this->twig = $twig;
        $this->typeOptionMenuService=$typeOptionMenuService;
        $this->categoryOptionService=$categoryOptionService;
        $this->menuRepository = $menuRepository;;

    }
    public function getFunctions()
    {
        return [
             new TwigFunction('settingOption',[$this, 'getSetting'],['is_safe'=>['html']])
        ];
    }

    public function getSetting(Boutique $boutique){
         
        return $this->twig->render('partials/settingOption.html.twig',[
            'options'=>$this->typeOptionMenuService->getTypeOptionMenu($boutique->getType()),
            'categoryBlogs'=>$this->typeOptionMenuService->getCategoryBlog(),
            'listCategories'=>$this->categoryOptionService->getListPerCategory($this->menuRepository->findBy(['boutique'=>$boutique]))
        ]);
    }
}