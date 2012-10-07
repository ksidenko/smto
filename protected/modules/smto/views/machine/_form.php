<div class="form">


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'machine-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form'),
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
		<?php echo $form->labelEx($model,'port'); ?>
		<?php echo $form->textField($model,'port',array('size'=>5,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'port'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'local_port'); ?>
        <?php echo $form->textField($model,'local_port',array('size'=>5,'maxlength'=>7)); ?>
        <?php echo $form->error($model,'local_port'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'pwd'); ?>
        <?php echo $form->textField($model,'pwd',array('size'=>16,'maxlength'=>20)); ?>
        <?php echo $form->error($model,'pwd'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'mac'); ?>
        <?php echo $form->textField($model,'mac',array('size'=>16,'maxlength'=>16)); ?>
        <?php echo $form->error($model,'mac'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'s_values'); ?>
        <?php echo $form->textField($model,'s_values',array('size'=>16,'maxlength'=>20)); ?>
        <?php echo $form->error($model,'s_values'); ?>
        <p class="hint" >Кол-во проверок, после которой станок переводиться в следующее состояние (выключен->включен->холостой ход->работа)</p>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'reasons_timeout_table'); ?>
        <?php echo $form->textField($model,'reasons_timeout_table',array('size'=>50,'maxlength'=>100)); ?>
        <?php echo $form->error($model,'reasons_timeout_table'); ?>
        <p class="hint" >
            Таблица таймаутов в секундах (0...65535) для причин простоя (до 16-ти).
            После истечения таймаута причина сбрасывается в состояние "неизвестная причина",
            что требует от оператора станка повторного ввода причины
            0 - означает "бесконечный таймаут"
        </p>

    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'data_fix_period'); ?>
        <?php echo $form->textField($model,'data_fix_period',array('size'=>10)); ?>
        <?php echo $form->error($model,'data_fix_period'); ?>
        <p class="hint" >
            Период фиксации данных в секундах (2...10), но рекомендуется не меньше 3
        </p>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'peak_average_period'); ?>
        <?php echo $form->textField($model,'peak_average_period',array('size'=>10)); ?>
        <?php echo $form->error($model,'peak_average_period'); ?>
        <p class="hint" >
            Интервал интегрирования для вычисления квазипиковых ("максимальных")
            значений в сотнях миллисекунд (1...50)
            Рекомендуется, чтобы период фиксации был кратен интервалу интегрирования
            (если не кратен - то обязательно период фиксации должен быть больше)
        </p>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'main_detector_digit'); ?>
        <?php echo $form->dropDownList($model,'main_detector_digit',range(0, 3, 1), array('size'=>1)); ?>
        <?php echo $form->error($model,'main_detector_digit'); ?>
        <p class="hint" ></p>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'main_detector_analog'); ?>
        <?php echo $form->dropDownList($model,'main_detector_analog',range(0, 3, 1), array('size'=>1)); ?>
        <?php echo $form->error($model,'main_detector_analog'); ?>
        <p class="hint" ></p>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'groups'); ?>
        <?php
            $dataSelect = array();
            foreach($model->groups as $group) {
                $dataSelect[$group->id] = $group->name;
            }
            $data = CHtml::listData(MachineGroup::model()->findAll(array('order' => 'name')), 'id', 'name');

            //print_r($dataSelect);print_r($data);die;

            echo CHtml::dropDownList('Machine[groups][]', $dataSelect, $data, array('multiple'=>true, 'size' => 3));
        ?>
        <?php echo $form->error($model,'groups'); ?>
        <p class="hint" ></p>
    </div>

    <?php if (isset($machineConfigData) && is_array($machineConfigData)) {echo $this->renderPartial('machine_config', array('form' => $form, 'machineConfigData' => $machineConfigData, )); } ?>

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
<!--        <tr><td>-->
<!--            <h2>Датчики</h2>-->
<!--            <table style="border: 1px solid black; width: 200px;"  rules="all" >-->
<!--                <tr>-->
<!--                    <td width="50">-->
<!--                        --><?php //echo $form->label(Detector::model(), 'number'); ?>
<!--                    </td>-->
<!---->
<!--                    <td width="100%%">-->
<!--                        --><?php //echo $form->label(Detector::model(), 'type'); ?>
<!--                    </td>-->
<!---->
<!--                    <td width="30">-->
<!--                        --><?php //echo $form->label(Detector::model(), 'status'); ?>
<!--                    </td>-->
<!--                </tr>-->
<!---->
<!--            --><?php ////foreach($model->detector as $i => $detector) { ?>
<!--            --><?php //// echo $this->renderPartial('../detector/_subform', array('form' => $form, 'model'=>$detector, 'index' => $i)); ?>
<!--            --><?php ////} ?>
<!--            </table>-->
<!---->
<!--        </td><td>-->
<!--            -->
<!--            <h2>Амплитуда</h2>-->
<!--            <table style="border: 1px solid black; width: 200px;"  rules="all" >-->
<!--                <tr>-->
<!--                    <td width="50">-->
<!--                        --><?php //echo $form->label(Amplitude::model(), 'number'); ?>
<!--                    </td>-->
<!---->
<!--                    <td width="100%%">-->
<!--                        --><?php //echo $form->label(Amplitude::model(), 'type'); ?>
<!--                    </td>-->
<!---->
<!--                    <td width="50">-->
<!--                        --><?php //echo $form->label(Amplitude::model(), 'value'); ?>
<!--                    </td>-->
<!--                </tr>-->
<!--            --><?php ////foreach($model->amplitude as $i => $amplitude) { ?>
<!--            --><?php ////  echo $this->renderPartial('../amplitude/_subform', array('form' => $form, 'model'=>$amplitude, 'index' => $i)); ?>
<!--            --><?php ////} ?>
<!--            </table>-->
<!--    -->
<!--        </td>-->
<!--    </tr>-->
    <tr>
        <td>

        </td>
        <td>
        </td>
    </tr>
    </table>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->getIsNewRecord() ? 'Добавить' : 'Сохранить', array('name' => 'submit')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->