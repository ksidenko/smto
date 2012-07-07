<h2>View P2User <?php echo $model->id; ?></h2>

<div class="actionBar">
    <?php $this->widget('application.modules.p2.components.P2CrudActionBar', array('id'=>$_REQUEST['id'])) ?>
    <?php
    $this->widget('application.modules.p2.components.P2AutoComplete',
        array(
        'name' => 'autoComplete',
        'searchModel' => 'P2User',
        'mode' => P2AutoComplete::MODE_UPDATE,
        'class' => 'ui-widget',)
    );
    ?>
</div>
<?php echo P2Helper::clearfloat() ?>


<table class="dataGrid">
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('status')); ?>
        </th>
        <td><?php echo CHtml::encode($model->getStatusName()); ?>
            <?php if($model->status==P2User::STATUS_VERIFIED): ?>
                <?php echo CHtml::linkButton('Activate',array(
                'submit'=>'',
                'params'=>array('command'=>'activate','id'=>$model->id),
                'return_url' => P2Helper::return_url())); ?>
            <?php endif; ?>

            <?php if($model->getProfile()): ?>
                <?php echo CHtml::link(
                'Review Profile',
                array(
                '/'.P2Helper::lcfirst($this->module->params['p2.user.profileModel']).'/show',
                'id' => $model->getProfile()->id),
                array('target' => '_blank')
                ); ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('name')); ?>
        </th>
        <td><?php echo CHtml::encode($model->name); ?>
        </td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('firstName')); ?>
        </th>
        <td><?php echo CHtml::encode($model->firstName); ?>
        </td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('lastName')); ?>
        </th>
        <td><?php echo CHtml::encode($model->lastName); ?>
        </td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('eMail')); ?>
        </th>
        <td><?php echo CHtml::mailto($model->eMail); ?>
        </td>
    </tr>
    <tr>
        <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('password')); ?> (md5)
        </th>
        <td><?php echo CHtml::encode($model->password); ?>
        </td>
    </tr>
</table>
