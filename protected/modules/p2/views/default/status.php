
<div class="span-8">
    <h2>Controllers</h2>
    <p>
    <?php foreach(P2Helper::getControllers() AS $controller): ?>
            <?php echo CHtml::link($controller, array("/".$controller)).""; ?> -
            <?php foreach(P2Helper::getActions($controller) AS $key => $action): ?>
                <?php echo CHtml::link("/".$key,$action).", "; ?>
            <?php endforeach; ?>
    <br/>
    <?php endforeach; ?>
    </p>

    <h2>Modules</h2>
    <?php foreach(P2Helper::getModules() AS $module): ?>
    <h3> <?php echo CHtml::link($module,array('/'.$module)); ?></h3>
        <?php foreach(P2Helper::getControllers($module) AS $module_controller): ?>
            <?php echo CHtml::link($module_controller, Yii::app()->baseUrl."/".$module_controller)." - "; ?>
            <?php foreach(P2Helper::getActions($module_controller) AS $key => $action): ?>
                <?php echo CHtml::link($key, $action).","; ?>
            <?php endforeach; ?>
    <br/>
        <?php endforeach; ?>
    <br/>
    <?php endforeach; ?>

</div>

<div class="span-8">
    <h2>User</h2>
    <h3>Name</h3>
    <p><?php echo Yii::app()->user->name ?></p>
    <h3>Auth Items</h3>
    <p><?php foreach(Yii::app()->authManager->getAuthItems() AS $key => $value) echo $key.", " ?></p>

    <h2>Themes</h2>
    <?php foreach(Yii::app()->themeManager->getThemeNames() AS $theme): ?>
        <?php echo $theme ?>
    <br/>
    <?php endforeach; ?>
</div>

<div class="span-8 last">
    <h2>Application</h2>
    <h3>Version</h3>
    <p>Yii::getVersion(): <?php echo Yii::getVersion(); ?></p>
    <p>P2Helper::MODULE_VERSION: <?php echo P2Helper::MODULE_VERSION ?></p>

    <h3>Language</h3>
    <p>Yii::app()->language: <?php echo Yii::app()->language; ?></p>

    <h3>Base Path</h3>
    <p>Yii::app()->basePath: <?php echo Yii::app()->basePath; ?></p>
    <h3>Base Url</h3>
    <p>Yii::app()->baseUrl: <?php echo Yii::app()->baseUrl; ?></p>

</div>

<div class="span-24 last">
    <h2>Configuration</h2>
    <?php if(isset($GLOBALS['config'])) {
        $config = include($GLOBALS['config']);
        echo nl2br(CVarDumper::dumpAsString($config,10,true));
    } else {
        echo "Could not find config file.";
    } ?>

</div>