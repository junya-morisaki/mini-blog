<table>
    <tbody>
        <tr>
            <th>ユーザID</th>
            <td>
                <input type="text" name="user_name" value="<?php echo $this->escape($user_name); ?>" />
            </td>
        </tr>

        <!--追加-->
        <?php if(isset($name)){
          $msg=<<<EOD
        <tr>
            <th>名前</th>
            <td>
                <input type="text" name="name" value="{$this->escape($name)}" />
            </td>
        </tr>
EOD;
print $msg;}
        ?>

        <tr>
            <th>パスワード</th>
            <td>
                <input type="password" name="password" value="<?php echo $this->escape($password); ?>" />
            </td>
        </tr>

    </tbody>
</table>
