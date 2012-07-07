<div class="p2BlogWidget">

    <h2><?php echo $this->model->title ?></h2>

    <div class="detail">
        <div class="actionBar">
            <?php if (Yii::app()->user->checkAccess('editor')) {
                echo CHtml::link(
                Yii::t('P2Module.p2','Create new post'),
                array('/p2/p2Html/create','return_url'=>P2Helper::uri()),
                array('class' => P2Helper::juiButtonCssClass())
                );
            }?>
        </div>
        <?php echo P2Helper::clearfloat() ?>

        <div class="Posting">

            <div class="header">

                <?php echo $this->activeLinkList($model,'^'); ?>

                <?php echo Yii::app()->dateFormatter->formatDateTime($model->P2Info->begin,'long',null); ?>
                <small>
                    <?php echo $model->name ?>
                </small>
            </div>

            <div class="content">

                <?php echo $model->html ?>
                <?php echo P2Helper::clearfloat() ?>
            <div class="link">
                <?php echo $this->activeLinkList($model, $this->model->listUrlText) ?>
            </div>
            </div>


            <div class="footer">
                <?php echo Yii::t('P2Module.p2','Permalink')?>:
                <b><?php echo CHtml::link(
                    $this->createDetailUrl($model, true),
                    $this->createDetailUrl($model)
                    ) ?></b>
                <br/>
                <?php echo Yii::t('P2Module.p2','Keywords')?>:
                <b><?php echo $model->P2Info->keywords ?></b>
                <br/>
                <?php echo Yii::t('P2Module.p2','Last updated')?>:
                <?php echo Yii::app()->dateFormatter->formatDateTime($model->P2Info->modifiedAt,'long','short'); ?>
                <?php echo Yii::t('P2Module.p2','by')?>
                <?php echo $model->P2Info->Creator->name ?>
            </div>

            <?php if (Yii::app()->user->checkAccess('editor')) {
                echo CHtml::link(
                Yii::t('P2Module.p2','Edit'),
                array('/p2/p2Html/update','id'=>$model->id, 'return_url'=>P2Helper::uri()),
                array('class' => P2Helper::juiButtonCssClass())
                );
                echo P2Helper::clearfloat();
            }?>

        </div>
    </div>
</div>