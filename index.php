<?php
error_reporting(0);
include_once $_SERVER["DOCUMENT_ROOT"] . '/include/S3.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/include/DataCache.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/include/getConfig.php';
$s3 = new S3($config['s3']['access'], $config['s3']['secret'], false, $config['s3']['region']);
$cache = new JG_Cache('cache'); //Make sure it exists and is writeable

if ($_GET['cache'] == 'clear') {
    $cache->clear('s3_upload_files');
}

$bucket_contents = $cache->get('s3_upload_files');
if (!$bucket_contents) {
    $bucket_contents = $s3->getBucket($config['s3']['bucket']);
    foreach($bucket_contents as $content){
        if(!strstr($content['name'], '_gsdata_')){
            $filtered_contents[] = $content;
        }
    }
    $cache->set("s3_upload_files", $filtered_contents);
}else{
    $filtered_contents = $bucket_contents;
}

$random_keys = array_rand($filtered_contents, 20);
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?=$config['site']['title']?> - <?=$config['site']['slogan']?></title>
        <meta name="keywords" content="<?=$config['site']['keywords']?>" />
        <meta name="description" content="<?=$config['site']['description']?>" />
        <meta name=viewport content="width=device-width, initial-scale=1">
        <style>
            .container{
                -webkit-column-width:303px;
                -moz-column-width:303px;
                -o-colum-width:303px;
                -webkit-column-gap:1px;
                -moz-column-gap:1px;
                -o-column-gap:1px;
            }
            .container div{
                display:inline-block;
                width:300px;
                position:relative;
                margin:10px 2px;
            }
            .container div img{
                border-radius: 10px;
            }
        </style>
    </head>
    <body style="color: #FFF; text-align:center; position: relative; margin: 0;">
        <div style="background: #000 url(<?=$config['site']['background']?>) fixed top; background-size: 100% auto; position: fixed; z-index: -999; width: 100%; height: 100%;"></div>
        <h1><?=$config['site']['title']?></h1>
        <h4><?=$config['site']['slogan']?></h4>
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-1417632407426788"
             data-ad-slot="3280187989"
             data-ad-format="auto"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        <div class="container">
            <?php
            $i = 0;
            foreach ($random_keys as $key) {
                $i++;
                $fname = $filtered_contents[$key]['name'];
                $furl = "http://".$config['s3']['bucket'].".s3.amazonaws.com/" . $fname;

                if (strpos($fname, "/$") || (!stristr($fname, '.jpg') && !stristr($fname, '.png') && !stristr($fname, '.jpeg'))) {
                	continue;
                }

                //echo "<div><a href='$furl' target='_blank'><img src='" . $furl . "' style=\"width: 300px;\" /></a></div>";

                 $images_name = 'imgs/' . md5($furl) . '_300.jpg';
                 if (file_exists($images_name)) {
                 echo "<div><a href='/view.php?img=" . urlencode($furl) . "'><img src='/$images_name' style=\"width: 300px;\" /></a></div>";
                 } else {
                 echo "<div><a href='/view.php?img=" . urlencode($furl) . "'><img src='/imgAgent.php?w=300&img=" . urlencode($furl) . "' style=\"width: 300px;\" /></a></div>";
                 }
            }
            ?>
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
            <a href="/?<?= rand(10000, 99999) ?>" title="Get more naked girls!"><img src="/include/refresh.png" /></a>
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