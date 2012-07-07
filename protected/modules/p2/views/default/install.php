<?php $this->layout = "plain"; ?>

<h1>phundament 2</h1>

<h2>Module Installation</h2>
<p><?php echo CHtml::link("Run checks again",array()); ?></p>

<h2>Status</h2>
<?php
if ($this->module->isInstalled() === true) {
    echo "Phundament module is installed correctly.<br/><br/>";
    echo "<h2>Links</h2>";
    echo CHtml::link('Home Page',Yii::app()->homeUrl)."<br/>";
    echo CHtml::link('Module: pundament 2 ',$this->createUrl('/p2'));
} else {
    echo "Phundament module is not installed correctly. More information, see below ...<br/><br/>";
    echo "<h2>Install Command</h2>";
    echo "<pre>cd ".Yii::app()->basePath."</pre>";
    echo "<pre>./yiic install config/main.php<config-file></pre>";
    echo "<p>or on Windows</p>";
    echo "<pre>yiic.bat install config/main.php<config-file></pre>";
}

if($this->module->getInstallationErrors()){ 
    echo "<h2>Errors</h2>";
    echo "<ul>";
    foreach($this->module->getInstallationErrors() AS $key => $error) {
        echo "<li>[".$key."] ".$error['message']."";
        echo "<ul>";
        foreach($error['hints'] AS $hint) {
            echo "<li>".$hint."</li>";
        }        
        echo "</ul></li>";
    }
    echo "</ul>";
}

if ($this->module->getInstallationWarnings()) {    
    echo "<h2>Warnings</h2>";
    echo "<ul>";
    foreach($this->module->getInstallationWarnings() AS $key => $warning) {
        echo "<li>[".$key."] ".$warning['message']."";
        echo "<ul>";
        foreach($warning['hints'] AS $hint) {
            echo "<li>".$hint."</li>";
        }        
        echo "</ul></li>";
    }
    echo "</ul>";
}
?>
