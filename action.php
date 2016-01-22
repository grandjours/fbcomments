<?php
/**
 * Facebook Script Action Plugin
 *
 * @author     Greatedays@gmail.com
 */

if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once DOKU_PLUGIN.'action.php';

class action_plugin_fbcomments extends DokuWiki_Action_Plugin {

    // Plugin Info
    function getInfo(){
        return array(
                      'author' => 'Greatdays',
                      'email'  => 'greatedays@gmail.com',
                      'date'   => '2011-04-03',
                      'name'   => 'Facebook Script',
                      'desc'   => 'Add Facebook script',
                      'url'    => 'http://juice.linuxstudy.pe.kr/wiki/facebook_comments_for_dokuwiki',
                    );
    }

    function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this,'_addHeader');
    }
    function _addHeader(&$event, $param){
        $event->data['meta'][] = array(
                                    'property'    => 'fb:app_id',
                                    'content' => $this->getConf('FB_AppID'),
                                    );

        $event->data['meta'][] = array(
                                     'property'    => 'fb:admins',
                                     'content' => $this->getConf('FB_UserID'),
                                    );

//        $event->data['div'][] = array(
//                                      'id' => 'fb-root',
//                                    );

//        $event->data['script'][] = array(
//                                          "type" => "text/javascript",
//                                            'src'=> 'http://connect.facebook.net/'. $this->getLang('fb_langs')
//                                                 .'/all.js#appId='. $this->getConf('FB_AppID') 
//                                                 .'&amp;xfbml=1',
//                                    );
    }

}