<?php

class OperatorCommand extends CConsoleCommand {

    public function actionImport($filepath = '') {

        $path = trim($filepath);
        if (empty($path)) {
            $path = Param::getParamValue('operator_data_path');
        }

        $import = new OperatorImport($path);

        $result = $import->run();
        if ($import->hasErrors()) {
            $errors = $import->getErrors();
            foreach($errors as $i => $error) {
                echo "$i) " . $error . PHP_EOL;
            }
        }

        Yii::app()->cache->flush();
    }
}
