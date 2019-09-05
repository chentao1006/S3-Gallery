<?php
include_once $_SERVER['DOCUMENT_ROOT']. '/include/getConfig.php';
if (!isset($_GET['img'])) {
    error_reporting(0);
    include_once $_SERVER["DOCUMENT_ROOT"] . '/include/S3.php';
    include_once $_SERVER["DOCUMENT_ROOT"] . '/include/DataCache.php';

    $s3 = new S3($config['s3']['access'], $config['s3']['secret']);

    $cache = new JG_Cache('cache'); //Make sure it exists and is writeable

    $bucket_contents = $cache->get('s3_upload_files');
    if (!$bucket_contents) {
        $bucket_contents = $s3->getBucket($config['s3']['bucket']);
        //print_r($bucket_contents);
        $cache->set("s3_upload_files", $bucket_contents);
    }

    $random_keys = array_rand($bucket_contents, 1);
    $img = "http://".$config['s3']['bucket'].".s3.amazonaws.com/" . $random_keys;
}else{
    $img = str_replace(' ', '%20', urldecode($_GET['img']));

}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?=$config['site']['title']?> - <?=$config['site']['slogan']?></title>
        <meta name="keywords" content="<?=$config['site']['keywords']?>" />
        <meta name="description" content="<?=$config['site']['description']?>" />
        <meta name=viewport content="width=device-width, initial-scale=1">
        <style>
            .view_img{
                max-width: 100%;
            }
        </style>
    </head>
    <body style="color: #FFF; text-align:center; position: relative; margin: 0;">
        <div style="background: #000 url(<?=$config['site']['background']?>) fixed top; background-size: 100% auto; position: fixed; z-index: -999; width: 100%; height: 100%;"></div>
        <h1><?=$config['site']['title']?></h1>
        <h4><?=$config['site']['slogan']?></h4>
        <div style="text-align: center;">
            <a href="/?<?= rand(10000, 99999) ?>"><img src="<?= $img ?>" class="view_img" /></a>
        </div>
        <!-- AddThis Button BEGIN -->
        <div class="addthis_toolbox addthis_default_style" style="max-width: 500px; margin: auto;">
            <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
            <a class="addthis_button_tweet"></a>
            <a class="addthis_button_pinterest_pinit" pi:pinit:layout="horizontal" pi:pinit:url="http://www.addthis.com/features/pinterest" pi:pinit:media="http://www.addthis.com/cms-content/images/features/pinterest-lg.png"></a>
            <a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
            <a class="addthis_counter addthis_pill_style"></a>
        </div>
        <script type="text/javascript">var addthis_config = {"data_track_addressbar": true};</script>
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4f9f655636b50769" async="true"></script>
        <!-- AddThis Button END -->
        <div style="text-align: center;">
            <a href="/?<?= rand(10000, 99999) ?>"><img src="/include/refresh.png" /></a>
        </div>
        <p>&copy; <?=date('Y')?> <?=$config['site']['domain']?> <?=$config['site']['title']?> - <?=$config['site']['slogan']?></p>
        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-47774493-1', '<?=$config['site']['domain']?>');
            ga('send', 'pageview');

        </script>
    </body>
</html>