<?php

/**
 * UserRepository.
 *
 * @author Katsuhiro Ogawa <fivestar@nequal.jp>
 */
class UserRepository extends DbRepository
{
    public function insert($user_name,$name, $password)//変更
    {
        $password = $this->hashPassword($password);
        $now = new DateTime();

        $sql = "
            INSERT INTO user(user_name, password, created_at,name)
                VALUES(:user_name, :password, :created_at,:name)
        ";//変更

        $stmt = $this->execute($sql, array(
            ':user_name'  => $user_name,
            ':password'   => $password,
            ':created_at' => $now->format('Y-m-d H:i:s'),
            ':name'        => $name,//追加
        ));
    }

    public function hashPassword($password)
    {
        return sha1($password . 'SecretKey');
    }

    public function fetchByUserName($user_name)
    {
        $sql = "SELECT * FROM user WHERE user_name = :user_name";

        return $this->fetch($sql, array(':user_name' => $user_name));
    }

    public function isUniqueUserName($user_name)
    {
        $sql = "SELECT COUNT(id) as count FROM user WHERE user_name = :user_name";

        $row = $this->fetch($sql, array(':user_name' => $user_name));
        if ($row['count'] === '0') {
            return true;
        }

        return false;
    }

    public function fetchAllFollowingsByUserId($user_id)
    {
        $sql = "
            SELECT u.*
                FROM user u
                    LEFT JOIN following f ON f.following_id = u.id
                WHERE f.user_id = :user_id
        ";

        return $this->fetchAll($sql, array(':user_id' => $user_id));
    }

    public function update($name,$intro,$id){//プロフィールの更新
      $sql="
      update user set name=:name  where id=:id
      ";

      $stmt = $this->execute($sql, array(
          ':name'  => $name,
          ':id'    => $id,
      ));

      $sql="
      update user set intro=:intro  where id=:id
      ";

      $stmt = $this->execute($sql, array(
          ':intro'  => $intro,
          ':id'    => $id,
      ));


    }



}
