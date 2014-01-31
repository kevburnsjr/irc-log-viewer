<?php require('header.html.php'); ?>

    <div style="padding: 12px 20px 18px;">
        <h1><?=$title?></h1>
    </div>
    <div class="dates">
    <?php
        $maxsize = 0;
        $files = array_reverse(array_slice(scandir($logdir),2));
        foreach ($files as $file) {
            $maxsize = max($maxsize, filesize($logdir."/".$file));
        }
        foreach ($files as $file) {
            //var_dump($file);
            if (is_valid_log_filename($file, $logprefix)) {
                $w = number_format(filesize($logdir."/".$file)/$maxsize*100,1);
                $filedate = log_filename_to_date($file, $logprefix);
                ?>
        <a href="<?=($baseurl.$filedate)?>.html"><?=$filedate?></a>
        <div class='bar'><div style='width:<?=$w?>%'>&nbsp;</div></div>
                <?php
            }
        }
    ?>
    </div>

<?php require('footer.html.php'); ?>
