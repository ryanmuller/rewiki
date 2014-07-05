<?php
/**
 * DokuWiki Plugin rewiki (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Ryan Muller <cognitionmachine@gmail.com>
 */

if (!defined('DOKU_INC')) die();

require_once DOKU_PLUGIN.'action.php';

class action_plugin_rewiki extends DokuWiki_Action_Plugin {
  public function register(Doku_Event_Handler &$controller) {
    $controller->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE', $this, 'handle_action_act_preprocess');
  }

  public function handle_action_act_preprocess(Doku_Event &$event, $param) {
    $WIKI_PATH = '/Users/ryanmuller/Sites/wiki';

    $tmp  = time();
    $page = $_GET["id"];
    $url  = $_GET["url"];
    $body = $_GET["body"];

    if (empty($page) || empty($url)) return;

    $host     = parse_url($url, PHP_URL_HOST);
    $tmppath  = '/tmp/dw-'.$tmp.'.tmp';
    $pagepath = $WIKI_PATH.'/data/pages/'.$page.'.txt';
    $msg      = 'Added text from rewiki';

    if (file_exists($pagepath))
      $bodytext = file_get_contents($pagepath)."\n\n";
    else
      $bodytext = "";

    $bodytext = $bodytext."[[".$url.'|'.$host.']]';

    if (!empty($body))
      $bodytext = $bodytext.":\n\n<blockquote><html>".$body.'</html></blockquote>';

    file_put_contents($tmppath, $bodytext);
    exec($WIKI_PATH."/bin/dwpage.php -m '".$msg."' commit ".$tmppath.' '.$page);
  }
}
