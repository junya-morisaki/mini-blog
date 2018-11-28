<?php

/**
 * StatusController.
 *
 * @author Katsuhiro Ogawa <fivestar@nequal.jp>
 */
class StatusController extends Controller
{
    protected $auth_actions = array('index', 'post','rt','fav','reply','reaction','all_reply');

    public function indexAction()
    {
        $user = $this->session->get('user');
        $statuses = $this->db_manager->get('Status')
            ->fetchAllPersonalArchivesByUserId($user['id']);

        $list=$this->getFav_Rt_List($user['id']);

        return $this->render(array(
            'statuses' => $statuses,
            'user'     => $user,
            'body'     => '',
            '_token'=> $this->generateCsrfToken('status/post'),
            'list'     =>$list

        ));
    }

    public function postAction()
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('status/post', $token)) {
            return $this->redirect('/');
        }

        $body = $this->request->getPost('body');

        $errors = array();

        if (!strlen($body)) {
            $errors[] = 'ひとことを入力してください';
        } else if (mb_strlen($body) > 200) {
            $errors[] = 'ひとことは200 文字以内で入力してください';
        }

        if (count($errors) === 0) {
            $user = $this->session->get('user');
            $this->db_manager->get('Status')->insert($user['id'], $body);

            return $this->redirect('/');
        }

        $user = $this->session->get('user');
        $statuses = $this->db_manager->get('Status')
            ->fetchAllPersonalArchivesByUserId($user['id']);
            $list=$this->getFav_Rt_List($user['id']);

        return $this->render(array(
            'errors'   => $errors,
            'body'     => $body,
            'statuses' => $statuses,
            '_token'   => $this->generateCsrfToken('status/post'),
            'list'     => $list
        ), 'index');
    }

    public function userAction($params)
    {
        $user = $this->db_manager->get('User')
            ->fetchByUserName($params['user_name']);
        if (!$user) {
            $this->forward404();
        }

        $statuses = $this->db_manager->get('Status')
            ->fetchAllByUserId($user['id']);

        $followsum=$this->db_manager->get('Following')
            ->fetchFollowSumByUserId($user['id']);

        $followersum=$this->db_manager->get('Following')
            ->fetchFollowerSumByUserId($user['id']);


        $following = null;
        if ($this->session->isAuthenticated()) {
            $my = $this->session->get('user');
            if ($my['id'] !== $user['id']) {
                $following = $this->db_manager->get('Following')
                    ->isFollowing($my['id'], $user['id']);
            }
        }

        $list=$this->getFav_Rt_List($user['id']);

        return $this->render(array(
            'user'      => $user,
            'statuses'  => $statuses,
            'following' => $following,
            '_token'    => $this->generateCsrfToken('account/follow'),
            'list'      => $list,
            'followsum' => $followsum,
            'followersum'=>$followersum
        ));
    }

    public function showAction($params)
    {
        $user = $this->session->get('user');
        $status = $this->db_manager->get('Status')
            ->fetchByIdAndUserName($params['id'], $params['user_name']);

        $rtinfo = $this->db_manager->get('Rt')
                ->fetchRtUserByPostId($params['id']);

        $favinfo = $this->db_manager->get('Fav')
                        ->fetchFavUserByPostId($params['id']);

        $list=$this->getFav_Rt_List($user['id']);

        if (!$status) {
            $this->forward404();
        }

        return $this->render(array(
          'status'  => $status,
          'user'    => $user,
          'rtinfo'  => $rtinfo,
          'favinfo' =>$favinfo,
          'type'    =>$params['type'],
          'list'    =>$list
        ));
    }

    public function signinAction()
    {
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/account');
        }

        return $this->render(array(
            'user_name' => '',
            'password'  => '',
            '_token'    => $this->generateCsrfToken('account/signin'),
        ));
    }

//以降追加

    public function getallAction(){
      $statuses = $this->db_manager->get('Status')->fetchAllStatus();
        $user = $this->session->get('user');
        $list=$this->getFav_Rt_List($user['id']);

          if (!$statuses) {
              $this->forward404();
          }

          return $this->render(array(
            'statuses' => $statuses,
            'user'     => $user,
            'list'     => $list
         ));
    }



    public function rtAction($params){
      if (!$this->request->isPost()) {
          $this->forward404();
      }

      $id=(int)$this->request->getPost('postid');

        $user = $this->session->get('user');
        $user_id=(int)$user['id'];
        if($params['bool']==='true'){
          $bool=true;
        }elseif($params['bool']==='false'){
          $bool =false;
        }else{
          $this->forward404();
        }


      $this->db_manager->get('Rt')->rt_update($id,$user_id,$bool);
      return $this->redirect('/');
    }


      public function favAction($params){
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $id=(int)$this->request->getPost('postid');
          $user = $this->session->get('user');
          $user_id=(int)$user['id'];
          if($params['bool']==='true'){
            $bool=true;
          }elseif($params['bool']==='false'){
            $bool =false;
          }else{
            $this->forward404();
          }


        $this->db_manager->get('Fav')->fav_update($id,$user_id,$bool);
        return $this->redirect('/');
      }

      public function showfavAction($params){
        $user_id=(int)$params['user_id'];
          $user = $this->session->get('user');
          $list=$this->getFav_Rt_List($user['id']);

        $statuses=$this->db_manager->get('Fav')->getfav($user_id);//getfav名前なおす
          return $this->render(array(
            'statuses' => $statuses,
            'user'    =>$user,
            'list'    =>$list
          ));
      }

      public function searchAction(){
        $user = $this->session->get('user');
        $key=$this->request->getGet('key');
        $list=$this->getFav_Rt_List($user['id']);
        if(isset($key)){
        $statuses=$this->db_manager->get('Status')->fetchSearchByKey($key);
        return $this->render(array(
          'statuses' => $statuses,
          'key'      => $key,
          'user'     => $user,
          'list'     => $list
        ));
      }else{
        return $this->render();
      }

      }

      public function replyAction(){//postのバリデーション追加　もしくは合併
        if (!$this->request->isPost()) {
            $this->forward404();
        }



        $postid=(int)$this->request->getPost('postid');
        $body=$this->request->getPost('body');
        $user = $this->session->get('user');

        $errors = array();

        if (!strlen($body)) {
            $errors[] = 'ひとことを入力してください';
        } else if (mb_strlen($body) > 200) {
            $errors[] = 'ひとことは200 文字以内で入力してください';
        }



        if(count($errors) === 0){
          $this->db_manager->get('Status')
          ->insert($user['id'], $body,true,$postid);

            return $this->redirect('/');
        }
          return $this->render(array(
              'errors'   => $errors,
              'body'     => $body,

          ));


      }

      public function reactionAction($params){
          $user = $this->session->get('user');
          $statuses=$this->db_manager->get('Status')
          ->fetchReactionByUserId($user['id']);
          $list=$this->getFav_Rt_List($user['id']);

          return $this->render(array(
            'statuses' => $statuses,
            'user'     => $user,
            'reaction' => $params['reaction'],
            'list'     => $list
          ));
      }

      public function all_replyAction($params){
        $user = $this->session->get('user');
        $post_id=(int)$params['post_id'];
        $statuses=$this->db_manager->get('Status')
        ->fetchReplyByPostId($post_id);
        $list=$this->getFav_Rt_List($user['id']);

        return $this->render(array(
          'statuses' => $statuses,
          'user'     => $user,
          'list'     => $list
        ));
      }

      private function getFav_Rt_List($user_id){

        $rt=$this->db_manager->get('Rt')
        ->fetchRtPostByUserId($user_id);

        $fav=$this->db_manager->get('Fav')
        ->fetchfavPostByUserId($user_id);

        $list=array();
        $list['rt']=$rt;
        $list['fav']=$fav;

        return $list;
      }

      public function show_fAction($params){

      if($params['value']==='follow'){
        $infos=$this->db_manager->get('Following')
            ->fetchFollowByUserName($params['user_id']);

      }elseif($params['value']==='follower'){
        $infos=$this->db_manager->get('Following')
            ->fetchFollowerByUserName($params['user_id']);

      }else{
        $this->forward404();
      }

      return $this->render(array(
        'infos' => $infos,
        'value'=> $params['value']
      ));
      }

}
