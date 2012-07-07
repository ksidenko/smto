<div class="P2BlogWidget">

    <h2><?php echo $this->model->title ?></h2>

    <div class="list">
        <div class="actionBar">
            <?php if (Yii::app()->user->checkAccess('editor')) {
                echo CHtml::link(
                Yii::t('P2Module.p2','Create new post'),
                array('/p2/p2Html/create', 'P2Info'=>array('type'=>$this->model->type) ,'return_url'=>P2Helper::uri()),
                array('class' => P2Helper::juiButtonCssClass())
                );
            }?>
        </div>

        <?php echo P2Helper::clearfloat() ?>
        <div class="pager">
            <?php if($this->model->displayPager) $this->widget('CLinkPager',array('pages'=>$pages)); ?>
        </div>
        <?php echo P2Helper::clearfloat() ?>

        <?php foreach($models AS $key => $model): ?>

            <?php if (Yii::app()->user->checkAccess('editor')) {
                echo '<div class="actionBar">';
                echo CHtml::link(
                Yii::t('P2Module.p2','Edit post'),
                array('/p2/p2Html/update','id'=>$model->id, 'return_url'=>P2Helper::uri()),
                array('class' => P2Helper::juiButtonCssClass())
                );
                echo '</div>';
                echo P2Helper::clearfloat();
            }?>
        <div class="posting">

            <div class="header">

                <h3><?php
                        echo CHtml::link(
                        $this->extractHeadline($model),
                        $this->createDetailUrl($model)) ?></h3>
                    <?php

                    if (!isset($models[($key-1)]) || $models[($key-1)]->P2Info->begin != $model->P2Info->begin) {

                        echo "<p>".CHtml::link(
                        Yii::app()->dateFormatter->formatDateTime($model->P2Info->begin,'long',null),
                        '#post'.$model->id,
                        array('name'=>'post'.$model->id)
                        )."</p>";

                    } else {
                        echo "<a name='post".$model->id."'></a>";
                    }?>
            </div>

            <div class="content">
                    <?php echo $this->extractShortContent($model); ?>
                    <?php echo P2Helper::clearfloat() ?>
            </div>

            <div class="footer">
                <div class="permalink">
                        <?php
                        echo CHtml::link(
                        Yii::t('P2Module.p2','Permalink'),
                        $this->createDetailUrl($model)) ?>
                </div>
                <div class="keywords">
                        <?php echo Yii::t('P2Module.p2','Keywords')?>:
                    <strong>
                            <?php echo $model->P2Info->keywords ?>
                    </strong>
                </div>
            </div>

        </div>
        <?php endforeach; ?>

        <div class="pager">
            <?php if($this->model->displayPager) $this->widget('CLinkPager',array('pages'=>$pages)); ?>
        </div>

    </div>

</div>