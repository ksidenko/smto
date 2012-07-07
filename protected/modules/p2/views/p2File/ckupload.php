<?php $this->layout = 'application.modules.p2.views.layouts.simple'; ?>
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>

<h2><?php echo Yii::t('P2Module.p2','Upload') ?></h2>

<div class="actionBar">
    <?php echo CHtml::link(
        'Return',
        P2Helper::return_url(),
        array('class' => P2Helper::juiButtonCssClass())
    ); ?>
</div>
<?php echo P2Helper::clearfloat() ?>

<div class="fckupload">
       <?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>false,
)); ?>
</div>

<script type="text/javascript">
$(window).resize(function(){
    $('.fckupload').height($(window).height()-100);
});
$('.fckupload').height($(window).height()-100);
$('.fckupload INPUT[type=submit]').click(function(){
    <?php echo P2Helper::configure('jquery:loadingStart','.fckupload'); ?>
});
</script>
