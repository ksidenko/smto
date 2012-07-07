<div class="p2PdfPreview">
    <?php
    $this->widget('p2.extensions.flash.EJqueryFlash',
        array(
            'name' => 'preview',
            'text' => 'Loading PDF preview ...',
            'htmlOptions' => array(
                'width' => $width,
                'height' => $height,
                'allowFullScreen'=>true,
                'src'=> Yii::app()->assetManager->publish(dirname(__FILE__).DS.'..'.DS."support")."/main.swf",
                'flashvars'=>array(
                    'allowZoom' => $allowZoom,
                    'allowTogglePageLayout' => $allowTogglePageLayout,
                    'allowFullScreen' => $allowFullScreen,
                    'zoomFactor' => $zoomFactor,
                    'themeColor' => $themeColor,
                    'renderSwfUrl' => $swfUrl,
                    // FIXME
                    'piiAssetPath' => Yii::app()->assetManager->publish(dirname(__FILE__).DS.'..'.DS."support")."/",
                )
            )
        )
    );
   ?>
</div>