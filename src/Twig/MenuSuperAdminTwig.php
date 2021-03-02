<?php 
namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Repository\BoutiqueRepository;
use App\Service\TypeOptionMenuService;
use Twig\Environment;

class MenuSuperAdminTwig extends AbstractExtension{
       
    private $twig;
    private $typeOptionMenuService;

    public function __construct( Environment $twig, TypeOptionMenuService $typeOptionMenuService)
    {
        $this->twig = $twig;
        $this->typeOptionMenuService=$typeOptionMenuService;
    }
    public function getFunctions()
    {
        return [
             new TwigFunction('menu_super_admin',[$this, 'getMenuSuperAdmin'],['is_safe'=>['html']])
        ];
    }

    public function getMenuSuperAdmin(string $boutiqueType,$role){
         
        return $this->twig->render('partials/menuSuperAdmin.html.twig',[
            'role'=>($role=="SUPER_ADMIN") ? 'es_article_list' :'article_list',
            'menus'=>$this->typeOptionMenuService->getTypeOptionMenu(strtolower($boutiqueType))

        ]);
    }
    
}