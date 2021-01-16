<?php

namespace App\Service;

use App\Data\Search;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;

class SearchService
{

    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }
    public function getResultSearch(Request $request)
    {

        $data = new Search();
        $q = $request->get('q');
        $category = $request->get('category');
        $min_price = $request->get('min_price');
        $max_price = $request->get('max_price');
        $boutique_id = $request->get('shop_id');
        $marque = $request->get('marque');
        $type = $request->get('type');

        if (!empty($q)) {
            $data->q = $q;
        }
        if (!empty($category)) {
            $data->category = $category;
        }
        if (!empty($min_price)) {
            $data->minPrice = $min_price;
        }
        if (!empty($max_price)) {
            $data->maxPrice = $max_price;
        }
        if (!empty($boutique_id)) {
            $data->boutique_id = $boutique_id;
        }
        if (!empty($marque)) {
            $data->marque = $marque;
        }
        if (!empty($type)) {
            $data->type = $type;
        }

        return $this->articleRepository->getArticleWithVoteBy($data);
    }
}
