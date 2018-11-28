<?php

/**
 * MiniBlogApplication.
 *
 * @author Katsuhiro Ogawa <fivestar@nequal.jp>
 */
class MiniBlogApplication extends Application
{
    protected $login_action = array('account', 'signin');

    public function getRootDir()
    {
        return dirname(__FILE__);
    }

    protected function registerRoutes()
    {
        return array(
            '/'
                => array('controller' => 'status', 'action' => 'index'),
            '/status/post'
                => array('controller' => 'status', 'action' => 'post'),
            '/status/getall'
                => array('controller' => 'status', 'action' => 'getall'),//追加
            '/status/rt/:bool'
                => array('controller' => 'status', 'action' => 'rt'),//追加　
            '/status/fav/:bool'
                => array('controller' => 'status', 'action' => 'fav'),//追加
            'status/showfav/:user_id'
                => array('controller' => 'status', 'action' =>'showfav'),//追加
            '/status/reaction/:reaction'
                => array('controller' => 'status', 'action' => 'reaction'),//追加
            '/status/search'
                => array('controller' => 'status', 'action' => 'search'),//追加
              '/status/reply'
                => array('controller' => 'status', 'action' => 'reply'),//追加
              '/status/all_reply/:post_id'
                => array('controller' => 'status', 'action' => 'all_reply'),//追加
            '/user/:user_name'
                => array('controller' => 'status', 'action' => 'user'),
            '/user/:user_id/:value'
                => array('controller' => 'status', 'action' => 'show_f'),
            '/user/:user_name/status/:id/:type'
                => array('controller' => 'status', 'action' => 'show'),
            '/account'
                => array('controller' => 'account', 'action' => 'index'),
            '/account/:action'
                => array('controller' => 'account'),
            '/follow/:value'
                => array('controller' => 'account', 'action' => 'follow'),

        );
    }

    protected function configure()
    {
        $this->db_manager->connect('master', array(
            'dsn'      => 'mysql:dbname=mini_blog;host=localhost;charset=utf8',
            'user'     => 'root',
            'password' => '12345',
        ));
    }
}
