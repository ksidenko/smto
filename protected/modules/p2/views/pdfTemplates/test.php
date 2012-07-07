<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<lithron>

	<font font-family="junicode"
		file-normal="<?php echo Yii::app()->basePath.DS."data".DS."fonts".DS."gw-fonts-ttf-1.0".DS."CasUni".DS."CaslonRoman" ?>"
        embedding-normal="true"
        file-bold="<?php echo Yii::app()->basePath.DS."data".DS."fonts".DS."gw-fonts-ttf-1.0".DS."CasUni".DS."CaslonBold" ?>"
		embedding-bold="true"
		file-italic="<?php echo Yii::app()->basePath.DS."data".DS."fonts".DS."gw-fonts-ttf-1.0".DS."CasUni".DS."CaslonBold" ?>"
		embedding-italic="true"
		file-oblique="<?php echo Yii::app()->basePath.DS."data".DS."fonts".DS."gw-fonts-ttf-1.0".DS."CasUni".DS."CaslonItalic" ?>"
		embedding-oblique="true"
	/>

    <style>
        * {
            font-family: junicode;
        }

        page.A4 {
            height: 297mm;
            width: 210mm;
            font-size: 1.5em;
            line-height: 1.6em;
        }
        .wrapper {
            padding: 1cm;
        }
        img {
            display: block-image;
            width: 6cm;
            margin-top: 1em;
            margin-bottom: 1em;
        }
        sink {
            position: absolute;
            Xbackground-color: cmyk(0,20,0,0);
        }
        .separator {
            height: 1pt;
            width: 100%;
            margin-top: 1em;
            margin-bottom: 2em;
            background-color: cmyk(0,0,0,50);
        }
        .Xentry {
            border-stroke-mode: single;
            border-style: solid;
            border-width-bottom: 0.2cm;
            border-stroke-width: 0.2cm;
            border-stroke-color: cmyk(100,0,0,0);
            border-stroke-pattern: "50 10";
            border-stroke-pattern: "20 20";

        }
        a {
            color: cmyk(100,5,5,20);
        }
    </style>

    <file name="<?php echo "test.pdf" ?>">

        <well well-id="mywell">
            <?php
            $cond = new CDbCriteria;
            $cond->offset = "0";
            $cond->limit = "99";
            $cond->order = "id DESC";
            $models = P2Html::model()->findAll($cond);

            function mediaFile($matches) {
                $model = P2File::model()->findByPk($matches[2]);
                if($model == null) {
                    return $matches[1].$matches[2].'" ';
                }
                return "src=\"".Yii::app()->basePath.DS.$model->filePath."\" "; # trailing slash debug
            } ?>

            <?php
            foreach($models AS $model):
                // extract id
                $html = preg_replace_callback('/(src="[\/a-z_A-Z?=]*)(\d+)([\/a-z_A-Z?=])*"/',"mediaFile",$model->html);
                #$html = $model->html;            
            ?>

            <div class="entry">
                <a class="editLink" href="<?php echo $this->createAbsoluteUrl('/p2/p2Html/update',array('id'=>$model->id)); ?>">
                    Edit <?php echo "#".$model->id." ".$model->name ?>
                </a>
                <?php echo $html ?>
            </div>
            <div class="separator"></div>
            <cbr/>
            <?php endforeach; ?>
        </well>

        <repeater well-id="mywell">
            <page class="A4">
                <div class="wrapper">
                    <sink well-id="mywell" left="1cm" top="1cm" width="9cm" height="27cm" />
                    <sink well-id="mywell" left="11cm" top="1cm" width="9cm" height="27cm" />
                </div>
            </page>
        </repeater>

    </file>




</lithron>