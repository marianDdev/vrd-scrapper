<?php

namespace App\Factories;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class HttpBrowserFactory
{
   public static function createHttpBrowser(): HttpBrowser
   {
       return new HttpBrowser(HttpClient::create(config('http_client')));
   }
}
