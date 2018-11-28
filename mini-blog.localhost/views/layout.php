<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php if (isset($title)): echo $this->escape($title) . ' - ';
        endif; ?>Mini Blog</title>

    <link rel="stylesheet" type="text/css" media="screen" href="/css/style.css" />
</head>
<body>
    <div id="header">
        <h1><a href="<?php echo $base_url; ?>/">Twittter</a></h1>
    </div>

    <div id="nav">
        <p>
            <?php if ($session->isAuthenticated()): ?>
                <a href="<?php echo $base_url; ?>/">ホーム</a>
                <a href="<?php echo $base_url; ?>/account">アカウント</a>
                  <a href="<?php echo $base_url; ?>/status/getall">全投稿一覧</a>　<!--追加-->
                  <a href="<?php echo $base_url; ?>/status/reaction/reply">通知</a> <!--追加-->
                  <a href="<?php echo $base_url; ?>/status/search">検索</a> <!--追加-->
            <?php else: ?>
                <a href="<?php echo $base_url; ?>/account/signin">ログイン</a>
                <a href="<?php echo $base_url; ?>/account/signup">アカウント登録</a>

            <?php endif; ?>
        </p>
    </div>

    <div id="main">
        <?php echo $_content; ?>
    </div>
</body>
</html>