<?php 
namespace App\Data;


class Search {
    
    /**
     * @var null|integer
     */
    public $minPrice;
    /**
     * @var null|integer
     */
    public  $maxPrice;

    /**
     * @var array
     */
    public $category;
    /**
     * @var string
     */
    public $q;
    /**
     * @var bool
     */
    public $promo=false;
    /**
     * @var integer
     */
    public $boutique_id;
    /**
     * @var string
     */
    public $marque;

}


