<?php $this->layout = 'application.modules.p2.views.layouts.simple'; ?>
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>

<h2><?php echo Yii::t('P2Module.p2','Files') ?></h2>

<div class="actionBar">
    <?php echo CHtml::link(
    Yii::t('P2Module.p2','Upload'),
    array(
    '/p2/p2File/ckupload',
    'return_url' => P2Helper::uri(),
    ),
        array('class' => P2Helper::juiButtonCssClass())
    ); ?>
    <?php echo P2Helper::clearfloat(); ?>

<p class="hint"><?php echo Yii::t('P2Module.p2','__CKBROWSE_INFO__') ?></p>

<h3><?php echo Yii::t('P2Module.p2','Format')?></h3>
<p>
<?php
if($type == 'image') {
    echo CHtml::dropDownList('preset', '', $presets);
} else {
    echo CHtml::hiddenField('preset', 'original');
}
?>
</p>
<h3><?php echo Yii::t('P2Module.p2','Search')?></h3>
<p>
<?php 
$this->widget('application.modules.p2.components.P2AutoComplete',
    array(
    'name' => 'autoComplete',
    'searchModel' => 'P2File',
    'mode' => P2AutoComplete::MODE_SELECT)
); 
echo CHtml::button(Yii::t('P2Module.p2','Select'), array('onclick' => 'handleSelection($("#autoComplete").val(),$("#preset").val())'));
?>
</p>
</div>


<?php echo P2Helper::clearfloat(); ?>

<h3><?php echo Yii::t('P2Module.p2','List')?></h3>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<div class="fckbrowse">
    <?php foreach($models as $n=>$model): ?>

    <div class="imageSelector"
         onclick="handleSelection(<?php echo $model->id ?>, $('#preset').val())">

        <div class="imageContainer">
            <img
                src="<?php echo CController::createUrl(
                    '/p2/p2File/image',
                    array('id'=>$model->id, 'preset'=> 'fckbrowse'),
                    "&amp;") ?>" />
        </div>
        <div class="info">
                <?php echo $model->id; ?>
            <br/>
                <?php echo CHtml::encode($model->name); ?>
            <br/>
                <?php echo CHtml::encode($model->fileType); ?>
        </div>
    </div>

    <?php endforeach; ?>
</div>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>



<script type="text/javascript">
    $(window).resize(function(){
        //$('.fckbrowse').height($(window).height()-100);
    });
    //$('.fckbrowse').height($(window).height()-100);

    /**
     * create image URL and return it
     */
    function handleSelection(id,preset) {
        //alert(id+'-'+preset);
        if($('#preset').val() == '') {
            alert('Please choose an image preset.');
            return false;
        }
        url = '<?php echo CController::createUrl('/p2/p2File/image',array('id'=>'__ID__', 'preset'=> '__PRESET__')) ?>';
        url = url.replace('__PRESET__', preset);
        url = url.replace('__ID__', id);
        //url = url.replace('<?php echo Yii::app()->baseUrl.DS ?>', ''); // FIXME? - CKEditor 3.1 - baseHref support
        //alert (url);
        window.opener.CKEDITOR.tools.callFunction(<?php echo $_REQUEST['CKEditorFuncNum'] ?>,url);
        window.close();
    }

</script>
