<?php require 'header.html.php'; ?>

    <div style="padding: 12px 20px 18px;">
        <h1><?=$title?></h1>
    </div>
    <div class="dates">
    <?php
        $files = $logManager->getLogFileList();
        $maxsize = $logManager->getMaxSize();
        foreach ($files as $file) {
            $w = number_format($file->getFileSize()/$maxsize*100, 1);
            $filedate = $logManager->getDateFromLogFileName($file->getFileName());
    ?>
        <a href="<?=($baseurl.$filedate)?>.html"><?=$filedate?></a>
        <div class='bar'><div style='width:<?=$w?>%'>&nbsp;</div></div>
    <?php
        }
    ?>
    </div>

<?php require 'footer.html.php'; ?>
