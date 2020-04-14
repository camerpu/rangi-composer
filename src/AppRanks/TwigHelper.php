<?php

namespace AppRanks;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use AppRanks\Ranks;


class TwigHelper
{
    /**
     * Creating Twig Instantion
     * @return Twig
     */
    public static function getTwig() : Environment
    {
        $loader = new FilesystemLoader('../views/');
        $twig = new Environment($loader, [
            'cache' => '../page_cache/',
            'debug' => true,
        ]);;
        $twig->addExtension(new DebugExtension());

        $filter = new TwigFilter('forumlink', function ($string) {
            return Ranks::forumLink($string);
        });

        $twig->addFilter($filter);
        return $twig;
    }
}