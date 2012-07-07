<div class="yiiForm form">
    <p>
        Fields with <span class="required">*</span> are required.
    </p>

    <?php echo CHtml::beginForm(
    #$this->createUrl('/p2/p2File/create',array('return_url' => urlencode(P2Helper::return_url()))),
    #$this->createUrl(($update)?'':'/p2/p2File/create',array('return_url' => (isset($_REQUEST['return_url'])?$_REQUEST['return_url']:''))),
    null,
    'POST',
    array(
    'enctype' => 'multipart/form-data',

    )
    ); ?>

    <?php echo CHtml::hiddenField('return_url', base64_encode(P2Helper::return_url())); ?>

    <?php echo CHtml::errorSummary($model); ?>

    <div class="span-12">
        <div class="row">
            <?php echo CHtml::label('Image','fckbrowse'); ?>
            <div>
                <?php if($model->id) echo P2File::image($model->id,'fckbrowse',array('style'=>'max-width:150px'))?>
            </div>
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model,'name'); ?>
            <?php echo CHtml::activeTextField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
            <br/>
            <?php $this->widget('P2NameNormalizer', array('selector' => '#P2File_name')); ?>
        </div>
        <div class="row">
            <?php echo CHtml::label('Upload File','fileUpload'); ?>
            <div>

                <?php echo CHtml::fileField('fileUpload',null,array(
                'style' => 'width: 100%',
                'onchange'=>'$("#P2File_name").val($("#fileUpload").val());')
                ); ?>
            </div>
            <p class="hint">Maximum size: <?php echo max(ini_get('upload_max_filesize'),ini_get('post_max_size')) ?></p>
        </div>
        <div class="row buttons">
            <?php echo CHtml::submitButton($update ? 'Save' : 'Create'); ?>
        </div>
    </div>

    <div class="span-12 last">
        <?php $this->widget('P2InfoInputWidget', array('owner'=>$model)); ?>
    </div>

    <?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->