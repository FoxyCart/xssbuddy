<?php
/**
 * DokuWiki Security Headers plugin
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Fred Alger <fred.alger@foxycart.com>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once DOKU_PLUGIN.'action.php';

class action_plugin_xssbuddy extends DokuWiki_Action_Plugin {
    protected $is_editing = false;

    public function register(Doku_Event_Handler &$controller) {
       $controller->register_hook('ACTION_HEADERS_SEND', 'BEFORE', $this, 'handle_headers_send');
       $controller->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE', $this, 'handle_act_preprocess');
    }

    public function handle_headers_send(Doku_Event &$event, $param) {
        if ($_SERVER['REMOTE_USER'] && $this->is_editing) {
            $event->data[] = "X-XSS-Protection: 0";
        }
    }

    public function handle_act_preprocess(Doku_Event &$event, $data) {
        if (@$event->data['draft'] || $event->data['preview']) {
            header('X-Doku-Action: editing');
            $this->is_editing = true;
        }
    }
}