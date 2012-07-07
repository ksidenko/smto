<?php $this->layout = "application.views.layouts.main" ?>
<h1>Feeds</h1>
<?php foreach($feeds as $feed): ?>
    <?php echo CHtml::link($feed['name'],$feed['url']); ?><br/>
<?php endforeach; ?>

<!--
        <link rel="alternate"
              type="application/atom+xml"
              title="Feed"
              href="<?php #echo $this->createAbsoluteUrl('/feed/atom') ?>" />
-->