<?php $this->beginWidget(
    'application.modules.p2.extensions.mbmenu.EP2Mbmenu',
    array(
    'cssSelector'=>'.p2Menu',
    'menuSelector'=>'.p2MenuContainer',
    'cssFile'=>Yii::app()->assetManager->publish(P2Helper::findModule()->basePath.DS.'assets'.DS.'p2Menu'.DS.'p2Menu.css'),
    'iconPath'=>(Yii::app()->theme)?Yii::app()->theme->baseUrl."/icons":"")
); ?>

<div id="p2Menu" class="p2Menu">
    <!-- start horizontal menu -->
    <table class="rootVoices" cellspacing='0' cellpadding='0' border='0'><tr>
            <td class="rootVoice {menu: 'menuP2'}" ><b><?php echo Yii::t('P2Module.p2','π'); ?></b></td>
            <td class="rootVoice {menu: 'menuContent'}" ><?php echo Yii::t('P2Module.p2','Data'); ?></td>
            <td class="rootVoice {menu: 'menuApplication'}"><?php echo Yii::t('P2Module.p2','Application'); ?></td>
            <td class="rootVoice {menu: 'menuModules'}"><?php echo Yii::t('P2Module.p2','Modules'); ?></td>
            <?php if (Yii::app()->user->checkAccess('admin')): ?>
            <td class="rootVoice {menu: 'menuInfo'}" ><?php echo Yii::t('P2Module.p2','Info'); ?></td>
            <?php endif; ?>
            <td width="100%"></td>
            <td class="rootVoice" ><?php echo Yii::app()->user->name ?></td>
        </tr>
    </table>

    <!-- end horizontal menu -->

    <!-- menues -->
    <div id="menuP2" class="mbmenu">
        <?php echo CHtml::link(
        P2Helper::juiIcon('home').Yii::t('P2Module.p2','Administration'),
        array('/p2/default/index'),
        array('class'=>""));
        ?>
        <?php echo CHtml::link(
        P2Helper::juiIcon('home').Yii::t('P2Module.p2','Home Page'),
        array('/site/index'),
        array('class'=>""));
        ?>
        <a rel="separator"> </a>
        <?php echo CHtml::link(
        P2Helper::juiIcon('locked').Yii::t('P2Module.p2','Logout').' '.Yii::app()->user->name,
        array('/site/logout'),
        array('class'=>""));
        ?>
    </div>

    <div id="menuModules" class="mbmenu">
<?php foreach(Yii::app()->getModules() AS $id => $module): ?>
        <?php echo CHtml::link($id, array("/".$id)); ?>
        <a rel="separator"> </a> <!-- menuvoice separator-->        
<?php endforeach; ?>
    </div>

    <div id="menuApplication" class="mbmenu">
        <?php echo CHtml::link(
        P2Helper::juiIcon('person').Yii::t('P2Module.p2','Users'),
        null,
        array(
        'class'=>"{disabled: ".CPropertyValue::ensureString(!Yii::app()->user->checkAccess('admin'))."}",
        'menu'=>'menuUsersSub'));
        ?>
        <?php echo CHtml::link(
        P2Helper::juiIcon('info').Yii::t('P2Module.p2','Status'),
        array('/p2/default/status'),
        array('class'=>"{disabled: ".CPropertyValue::ensureString(!Yii::app()->user->checkAccess('admin'))."}")
        );
        ?>
        <a rel="separator"> </a> <!-- menuvoice separator-->
        <?php echo CHtml::link(
        P2Helper::juiIcon('clipboard').Yii::t('P2Module.p2','Log'),
        array('/p2/p2Log/list'),
        array('class'=>"{disabled: ".CPropertyValue::ensureString(!Yii::app()->user->checkAccess('admin'))."}")
            );
        ?>
    </div>

    <div id="menuUsersSub" class="mbmenu">
        <?php echo CHtml::link(
        P2Helper::juiIcon('gear').Yii::t('P2Module.p2','Manage Users'),
        array('/p2/p2User/admin'),
        array('class'=>""));
        ?>
        <?php echo CHtml::link(
        P2Helper::juiIcon('plus').Yii::t('P2Module.p2','Create User'),
        array('/p2/p2User/create'),
        array('class'=>""));
        ?>

        <a rel="separator"> </a>

        <?php echo CHtml::link(
        Yii::t('P2Module.p2','Assign Roles'),
        array('/srbac/authitem/assign'),
        array('class'=>""));
        ?>
        <?php echo CHtml::link(
        Yii::t('P2Module.p2','Manage Roles'),
        array('/srbac/authitem/manage'),
        array('class'=>""));
        ?>
        <?php echo CHtml::link(
        Yii::t('P2Module.p2','View Roles'),
        array('/srbac/authitem/assignments'),
        array('class'=>""));
        ?>
    </div>


    <div id="menuContent" class="mbmenu">
        <?php echo CHtml::link(
        P2Helper::juiIcon('document').Yii::t('P2Module.p2','HTML'),
        array('/p2/p2Html/admin'),
        array('class'=>""));
        ?>
        <?php echo CHtml::link(
        P2Helper::juiIcon('image').Yii::t('P2Module.p2','Files'),
        array('/p2/p2File/admin'),
        array('class'=>"", 'menu'=>'subMenuFiles'));
        ?>
        <?php echo CHtml::link(
        P2Helper::juiIcon('note').Yii::t('P2Module.p2','Pages'),
        array('/p2/p2Page/sitemap'),
        array('class'=>"", 'menu'=> 'subMenuPages'));
        ?>
        <?php echo CHtml::link(
        P2Helper::juiIcon('folder-collapsed').Yii::t('P2Module.p2','Cells'),
        array('/p2/p2Cell/admin'),
        array('class'=>""));
        ?>
    </div>
    <div id="subMenuFiles" class="menu"  style="display:none"> <!-- display: none = ugly render hotfix -->
        <?php echo CHtml::link(Yii::t('P2Module.p2','Upload File'), array('/p2/p2File/create')) ?>
        <?php echo CHtml::link(Yii::t('P2Module.p2','Import Files'), array('/p2/p2File/import')) ?>
    </div>
    <div id="subMenuPages" class="menu"  style="display:none"> <!-- display: none = ugly render hotfix -->
        <?php if(isset($_GET[P2Page::PAGE_ID_KEY])) echo CHtml::link(Yii::t('P2Module.p2','Edit Page'), array('/p2/p2Page/update','id'=>$_GET[P2Page::PAGE_ID_KEY], 'return_url' => P2Helper::uri())) ?>
        <?php echo CHtml::link(Yii::t('P2Module.p2','Copy Pages'), array('/p2/p2Page/copy', 'return_url' => P2Helper::uri())) ?>
        <?php echo CHtml::link(Yii::t('P2Module.p2','Hide Admin Controls'), '?'.P2CellManager::GET_PARAM_ADMIN_CONTROLS.'=0') ?>
    </div>
    <div id="menuInfo" class="menu"  style="display:none"> <!-- display: none = ugly render hotfix -->
        <a href="http://www.phundament.com" target="_blank"><?php echo Yii::t('P2Module.p2','Go to phundament.com') ?></a>
        <a href="http://code.google.com/p/phundament/" target="_blank"><?php echo Yii::t('P2Module.p2','Developer') ?></a>
        <a href="http://code.google.com/p/phundament/issues/list" target="_blank"><?php echo Yii::t('P2Module.p2','Report a Bug') ?></a>
    </div>
    <!-- end menues -->
</div>

<?php $this->endWidget(); ?>