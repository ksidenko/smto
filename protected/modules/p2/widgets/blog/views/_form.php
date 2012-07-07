<h3>Blog Widget</h3>

<div class="form classProps">

    <?php echo CHtml::beginForm() ?>
    <?php echo CHtml::hiddenField('modelClass', get_class($model)) ?>


    <div class="row">
        <?php echo CHtml::activeLabel($model, 'type') ?>
        <?php echo CHtml::activeDropDownList($model, 'type', Yii::app()->params['p2.info.types']['P2Html']) ?>
    <p class="hint">
        <?php echo Yii::t('P2Module.p2','HTML-type to select.') ?>
    </p>
    </div>

    <div class="row">
        <?php echo CHtml::activeLabel($model, 'pageSize') ?>
        <?php echo CHtml::activeTextField($model, 'pageSize') ?>
    </div>
    <p class="hint">
        <?php echo Yii::t('P2Module.p2','Blog items in overview mode.') ?>
    </p>

    <div class="row">
        <?php echo CHtml::activeLabel($model, 'showFullEntries') ?>
        <?php echo CHtml::activeCheckBox($model, 'showFullEntries') ?>
    </div>
    <p class="hint">
        <?php echo Yii::t('P2Module.p2','Wheter to show complete entries in list mode.') ?>
    </p>

    <div class="row">
        <?php echo CHtml::activeLabel($model, 'moreMarker') ?>
        <?php echo CHtml::activeTextField($model, 'moreMarker') ?>
    </div>
    <p class="hint">
        <?php echo Yii::t('P2Module.p2','String, replaced by detail link posting will be cut off at this string in overview mode.') ?>
    </p>

        <div class="row">
        <?php echo CHtml::activeLabel($model, 'detailUrlText') ?>
        <?php echo CHtml::activeTextField($model, 'detailUrlText') ?>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabel($model, 'detailUrl') ?>
        <?php echo CHtml::activeTextField($model, 'detailUrl') ?>
    </div>
    <p class="hint">
        <?php echo Yii::t('P2Module.p2','A JSON string {route:"/site/blog"} or URL.') ?>
    </p>

    <div class="row">
        <?php echo CHtml::activeLabel($model, 'listUrlText') ?>
        <?php echo CHtml::activeTextField($model, 'listUrlText') ?>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabel($model, 'listUrl') ?>
        <?php echo CHtml::activeTextField($model, 'listUrl') ?>
    </div>
    <p class="hint">
        <?php echo Yii::t('P2Module.p2','A JSON string {route:"/site/blog"} or URL.') ?>
    </p>

    <div class="row">
        <?php echo CHtml::activeLabel($model, 'headlineTag') ?>
        <?php echo CHtml::activeTextField($model, 'headlineTag') ?>
    </div>

    <div class="row">
        <?php echo CHtml::activeLabel($model, 'displayPager') ?>
        <?php echo CHtml::activeCheckBox($model, 'displayPager') ?>
    </div>

    <div class="row">
        <?php echo CHtml::activeLabel($model, 'ongoing') ?>
        <?php echo CHtml::activeCheckBox($model, 'ongoing') ?>
    </div>
    <p class="hint">
        <?php echo Yii::t('P2Module.p2','Show only entries which are currently within begin and end') ?>
    </p>

    <div class="row">
        <?php echo CHtml::activeLabel($model, 'localized') ?>
        <?php echo CHtml::activeCheckBox($model, 'localized') ?>
    </div>
    <p class="hint">
        <?php echo Yii::t('P2Module.p2','Wheter to show all entries or only localized ones.') ?>
    </p>

    <?php echo CHtml::endForm() ?>

</div>
