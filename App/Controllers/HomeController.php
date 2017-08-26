<?php
namespace App\Controllers;

use Cac\Controller\Action;
use GuzzleHttp\Client;

class HomeController extends Action
{

    private $credentials = null;
    private $client      = null;
    private $token       = 'https://api.twitter.com/oauth2/token';
    private $tweets      = 'https://api.twitter.com/1.1/search/tweets.json?q=%23';

    public function __construct()
    {
        $this->client       = new Client();

        $config = config('twitter.credentials');

        $this->credentials  = base64_encode("{$config['key']}:{$config['secret']}");
    }
    public function index()
    {
        echo $this->render('home.index');
    }

    public function hashtag(){
        $hashtag = $_POST['hashtag'];
        $this->search($hashtag);
    }


    public function search($hastag)
    {
        $r1 = $this->client->post($this->token,
            [
                'query' => ['grant_type' => 'client_credentials'],

                'headers' =>
                    [
                        'Authorization' => "Basic {$this->credentials}",
                        'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
                    ]
            ]);

        //obj token
        $token = json_decode($r1->getBody());


        $r1 = $this->client->get($this->tweets.$hastag,
            [
                'headers' =>
                    [
                        'Authorization' => "Bearer {$token->access_token}",
                    ]
            ]);

        //obj Tweets

        dd(json_decode($r1->getBody()));
    }
}
