<?php
/**
 * Affiche les erreurs en twig
 * $session = $this->get('session');
 * $session->set('filter', array(
 *   'accounts' => 'value',
 * ));
 * 
 * 
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(dirname(__FILE__)));
$dotenv->load();

require_once dirname(dirname(__FILE__)) . '/Config/config.php';
use App\Core\Application;


$app = new Application();

require_once dirname(dirname(__FILE__)) . '/Routes/routes.php';

$app->run();
