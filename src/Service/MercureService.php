<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MercureService extends AbstractController
{

    public function mercurePost(string $topic, array $data = [])
    {

        define("JWT", $this->getParameter('jwt_token'));

        $postData = http_build_query([
            'topic' => $topic,
            'data' => json_encode($data)
        ]);

        return file_get_contents(
            "http://localhost:3000/.well-known/mercure",
            false,
            stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' =>
                    "Content-Type: application/x-www-form-urlencoded\r\n" .
                        "Authorization: Bearer " . JWT,
                    "content" => $postData
                    // 'header' =>
                    // "Content-type : application/x-www-form-urlencoded\r\n" .
                    //     "Authorization: Bearer " . JWT,
                    // 'content' => $postData,
                ]
            ])
        );
    }
}
