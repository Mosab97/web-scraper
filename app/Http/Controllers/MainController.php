<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Sunra\PhpSimple\HtmlDomParser;

class MainController extends Controller
{
    public function scrape(Request $request){

        //Get url param for scraping
        $url = $request->get('url');

        //Init Guzzle
        $client = new Client();

        //Get request
        $response = $client->request(
            'GET',
            $url
        );

        $response_status_code = $response->getStatusCode();
        $html = $response->getBody()->getContents();

        if($response_status_code==200){
            $dom = HtmlDomParser::str_get_html( $html );

            $song_items = $dom->find('div[class="chart-list-item"]');

            $count = 1;
            foreach ($song_items as $song_item){
                if($count==1){
                    $song_title = trim($song_item->find('span[class="chart-list-item__title-text"]',0)->text());
                    $song_artist = trim($song_item->find('div[class="chart-list-item__artist"]',0)->text());

                    $song_lyrics_parent = $song_item->find('div[class="chart-list-item__lyrics"]',0)->find('a',0);
                    $song_lyrics_href = $song_lyrics_parent->attr['href'];

                    //Store in database
                }
                $count++;
            }
        }
    }
}
