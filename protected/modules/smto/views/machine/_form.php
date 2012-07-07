<div class="form">


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'machine-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля отмеченные <span class="required">*</span> обязательны для заполнения.</p>
    
	<?php //echo $form->errorSummary($model, ''); ?>
    <?php echo $form->hiddenField($model, 'id'); ?>

    <?php if ($is_create && !$this->isTemplate) { ?>
    <div class="row">
        <?php echo $form->labelEx($model,'template_id'); ?>
        <?php echo $form->dropDownList($model, 'template_id', array(-1 => '') + CHtml::listData(Machine::getList(), 'id', 'name')) ?>
        <?php echo $form->error($model,'template_id'); ?>
        <?php echo CHtml::submitButton('Применить', array('name' => 'apply_template')); ?>
    </div>
    <?php } ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>512)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>30,'maxlength'=>512)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'ip'); ?>
		<?php echo $form->textField($model,'ip',array('size'=>16,'maxlength'=>16)); ?>
		<?php echo $form->error($model,'ip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mac'); ?>
		<?php echo $form->textField($model,'mac',array('size'=>16,'maxlength'=>16)); ?>
		<?php echo $form->error($model,'mac'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'work_type'); ?>
		<?php echo $form->dropDownList($model,'work_type',Machine::$work_type_list, array()); ?>
		<?php echo $form->error($model,'work_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_idle_run'); ?>
		<?php echo $form->textField($model,'time_idle_run',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'time_idle_run'); ?>
	</div>


    <h2>F - кнопки</h2>
    <table style="border: 1px solid black; width: 500px;"  rules="all" >
        <tr>
            <td width="50">
                <?php echo $form->label(Fkey::model(),  'number'); ?>
            </td>

            <td width="50">
                <?php echo $form->label(Fkey::model(), 'code'); ?>
            </td>

            <td width="50">
                <?php echo $form->label(Fkey::model(), 'color'); ?>
            </td>

            <td width="100%">
                <?php echo $form->label(Fkey::model(), 'machine_event'); ?>
            </td>

            <td width="40%">
                <?php echo $form->label(Fkey::model(), 'type'); ?>
            </td>

            <td width="40%">
                <?php echo $form->label(Fkey::model(), 'descr'); ?>
            </td>

            <td width="30">
                <?php echo $form->label(Fkey::model(), 'status'); ?>
            </td>
        </tr>
        <?php foreach($model->fkey as $i => $fkey) { ?>
        <?php echo $this->renderPartial('../fkey/_subform', array('form' => $form, 'model'=>$fkey, 'index' => $i)); ?>
        <?php } ?>
    </table>

    <table>
        <tr><td>
            <h2>Датчики</h2>
            <table style="border: 1px solid black; width: 200px;"  rules="all" >
                <tr>
                    <td width="50">
                        <?php echo $form->label(Detector::model(), 'number'); ?>
                    </td>

                    <td width="100%%">
                        <?php echo $form->label(Detector::model(), 'type'); ?>
                    </td>

                    <td width="30">
                        <?php echo $form->label(Detector::model(), 'status'); ?>
                    </td>
                </tr>

            <?php foreach($model->detector as $i => $detector) { ?>
            <?php   echo $this->renderPartial('../detector/_subform', array('form' => $form, 'model'=>$detector, 'index' => $i)); ?>
            <?php } ?>
            </table>

        </td><td>
            
            <h2>Амплитуда</h2>
            <table style="border: 1px solid black; width: 200px;"  rules="all" >
                <tr>
                    <td width="50">
                        <?php echo $form->label(Amplitude::model(), 'number'); ?>
                    </td>

                    <td width="100%%">
                        <?php echo $form->label(Amplitude::model(), 'type'); ?>
                    </td>

                    <td width="50">
                        <?php echo $form->label(Amplitude::model(), 'value'); ?>
                    </td>
                </tr>
            <?php foreach($model->amplitude as $i => $amplitude) { ?>
            <?php   echo $this->renderPartial('../amplitude/_subform', array('form' => $form, 'model'=>$amplitude, 'index' => $i)); ?>
            <?php } ?>
            </table>
    
        </td>
    </tr>
    </table>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->getIsNewRecord() ? 'Добавить' : 'Сохранить', array('name' => 'submit')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->