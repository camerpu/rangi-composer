<?php
require '../vendor/autoload.php';
use AppRanks\Ranks;
use AppRanks\TwigHelper;

$server = null;
if(!isSet($_GET['id']))
{
    $id = 1;
    $server = new Ranks($id);
}
else
{
    try
    {
        $server = new Ranks($_GET['id']);
    }
    catch(Exception $e)
    {
        echo '<div class="container"><div class="alert alert-dark" role="alert" style="margin-top: 50px;">Problem: ',  $e->getMessage(), "</div>";
        exit();
    }
}

$allServers = Ranks::listServers();
$top = isset($_POST['nick']) ? $server->findByPlayer($_POST['nick']) : $server->getTOP(20);
$twig = TwigHelper::getTwig();
$twig->addGlobal('session', $_SESSION);
$twig->addGlobal('get', $_GET);
$twig->addGlobal('post', $_POST);

echo $twig->render('index.html.twig', ['allServers'=>$allServers, 'server'=>$server, 'top'=>$top, 'servername'=>$server->getName()]);