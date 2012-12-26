<?php

class SysCommand extends CConsoleCommand {

    public function actionClearCache() {
        $paths = array();
        $paths []= Yii::getPathOfAlias('application') . '/../assets/*';
        $paths []= Yii::getPathOfAlias('application') . '/runtime/cache/*';
        $paths []= Yii::getPathOfAlias('application') . '/runtime/state.bin';

        foreach($paths as $path) {
            echo $path;
            exec("rm -rf $path");
            echo '...done' . PHP_EOL;
        }

    }
}