<div id="<?php echo $this->uniqid ?>" class="p2GoogleFeedWidget">
    <div class="header">
        <h2><a class="title link"></a></h2>
        <?php if ($this->model->displayAuthor): ?><p class="author"></p><?php endif; ?>
        <?php if ($this->model->displayDescription): ?><p class="description"></p><?php endif; ?>
        <hr/>
    </div>
    <div class="entries">
    </div>
    <div class="entryTemplate">
        <?php if ($this->model->displayEntryDate): ?><div class="date"></div><? endif; ?>
        <h3 class="<?php echo ($this->model->displayFullContent)?'full':'' ?>"><a class="title link"></a></h3>
        <?php if ($this->model->displayShortContent): ?><p class="shortContent"></p><? endif; ?>
        <?php if ($this->model->displayFullContent): ?><p class="fullContent"></p><? endif; ?>
        <div class="categories"></div>
        <hr/>
    </div>
</div>

