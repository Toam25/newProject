<?php 
  namespace App\Service;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UtilsService extends AbstractController{
       
   
    public static function getSlug($text)
    {    
      $find    = array("à", "á", "â", "ã", "ä", "å", "ò", "ó", "ô", "õ", "ö", "ø", "è", "é", "ê", "ë", "ç", "ì", "í", "î", "ï", "ù", "ú", "û", "ü", "ÿ", "ñ");  
      $replace = array("a", "a", "a", "a", "a", "a", "o", "o", "o", "o", "o", "o", "e", "e", "e", "e", "c", "i", "i", "i", "i", "u", "u", "u", "u", "u", "n");  
      $text    = str_replace($find, $replace, $text);
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

  }