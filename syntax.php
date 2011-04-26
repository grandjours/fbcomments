<?php
/**
 * Plugin Facebook comments on Dokuwiki
 *
 * Syntax: <TEST> - will be replaced with "Hello World!"
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Christopher Smith <chris@jalakai.co.uk>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_INC.'inc/auth.php');

class syntax_plugin_fbcomments extends DokuWiki_Syntax_Plugin {

   /**
    * Plugin Info
    */
    function getInfo(){
        return array(
                      'author' => 'Greatdays',
                      'email'  => 'greatedays@gmail.com',
                      'date'   => '2011-04-03',
                      'name'   => 'Facebook comments box',
                      'desc'   => 'Add Facebook comments box',
                      'url'    => 'http://juice.linuxstudy.pe.kr/wiki/facebook_comments_for_dokuwiki',
                    );
    }
    
  /*
   * Get the type of syntax this plugin defines.
   */
   function getType(){
      return 'container';
   }
       
   function getPType(){
      return 'block';
   }

   function getSort(){
      return FXIME;
   }
   
   function connectTo($mode) {
//      $this->Lexer->addSpecialPattern('\{\{fbc[^}]*\}\}',$mode,'plugin_fbcomments');
      $this->Lexer->addSpecialPattern('\{\{fbc>[^}]*\}\}',$mode,'plugin_fbcomments');      
   }
   
   function handle($match, $state, $pos, &$handler){
      if (isset($_REQUEST['comment'])) return false;
      
      $match= substr($match, 6, -2);
      $data = array();
      
      $params = explode('|',$match);
      foreach($params as $param){
        
        $splitparam = explode('=',$param);
        if($splitparam[0] == num) 
          $splitparam[0]= 'num_posts';
          
        $splitparam[0] = FB_. $splitparam[0];
        $data[$splitparam[0]] = $splitparam[1];
      }
      
        return $data;
   }
   
   function render($mode, &$renderer, $data){
     if($mode == 'xhtml'){
       $renderer->doc .= $this->_commentsBox($data);
       
       return true;
     } 
       return false;
   }
   
   protected function _commentsBox($data){
     global $ID;
     global $conf;
     $this ->data= $data;
     
     if($data['FB_like'] == 'y' or $data['FB_like'] == 'Y'){
        $fblike =  '<!-- Fcaebook Like Button -->'
                  .'<fb:like href="'. wl($ID, '', true) .'"'
                  .'show_faces="true" width="'. $this->_fbsetting('FB_width', $conf) .'" action="like" font="">'
                  .'</fb:like>'
                  .'<!-- Facebook Like Button end -->';
     }
        else $fblike = '<!-- Fcaebook Like Button --> '
                      .'<!-- Facebook Like Button end -->';
        
     if(!empty($conf['plugin']['fbcomments']['FB_AppID'])){
     
          $box=  '<!-- Facebook Script -->'
                .'<div id="fb-root"> </div>'
                .'<script src="http://connect.facebook.net/'. $this->getLang('fb_langs')
                .'/all.js#appId='. $this->getConf('FB_AppID') .'&amp;xfbml=1">'
                .'</script>'
                . $fblike
                .'<!-- Facbebook Comments Box start -->'
                .'<fb:comments href="'. wl($ID, '', true) 
                .'" num_posts="'. $this->_fbsetting('FB_num_posts', $conf)
                .'" width="'. $this->_fbsetting('FB_width', $conf)
                .'"></fb:comments>'
                .'<!-- Facebook Comments Box end -->';
      } 
        else $box= 'Empty Facebook App ID';

    return $box;
   }
   
   protected function _fbsetting($name, $fbconf) {
      include dirname(__FILE__).'/conf/default.php';
      
      if(empty($this->data[$name])){
        if(!empty($fbconf['plugin']['fbcomments'][$name])) 
          return $fbconf['plugin']['fbcomments'][$name];
            else return $conf[$name];
      } 
        return hsc($this->data[$name]);
   }
   
}
?>