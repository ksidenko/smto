<?php
/**
 * Class File
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @link http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2010 diemeisterei GmbH
 * @license http://www.phundament.com/license/
 */

/**
 * Action, searches models
 *
 * Model and columns to search and select are read from $_GET parameters, which can be
 * specified in {@link CAutoComplete} extraParams.
 *
 * While 'model' is a string, 'scope', 'select' and 'search' have to be arrays.
 *
 * see {@link P2AutoComplete}
 *
 * @todo Security!
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2AjaxSearchAction.php 511 2010-03-24 00:41:52Z schmunk $
 * @package p2.actions
 * @since 2.0
 */
class P2AjaxSearchAction extends CAction {

    function run() {
        $class = Yii::import($_GET['model']);
        $model = new $class;
        $criteria = new CDbCriteria();

        // prepare search condition
        $condition = "";
        foreach (explode(' ',$_GET['q']) AS $i => $part) {
            if (strstr($part,":")) {
                $pair  = explode(":",$part);
                $condition = "(".$pair[0]." = :t".$i.") AND ";
                $params[':t'.$i] = $pair[1];
            } else {
                $condition .= "(";
                foreach($_GET['search'] AS $search) {
                    $condition .= $search." LIKE :q".$i." OR ";
                    $params[':q'.$i] = "%".$part."%";
                }
                $condition .= "0 = 1 ";
                $condition .= ") AND ";
            }

        }
        $condition .= "1 = 1";
        $criteria->condition = $condition;
        $criteria->params = $params;
        $criteria->select = $_GET['select'];

        // apply scopes
        if(isset($_GET['scope'])) {
            foreach($_GET['scope'] AS $scope) {
                $model = $model->$scope();
            }

        }

        // with these relations
        if(isset($_GET['with'])) {
            foreach($_GET['with'] AS $withModel) {
                $model = $model->with($withModel);
            }
        }
        $models = $model->findAll($criteria);

        $return = "";
        foreach($models AS $model) {
            foreach($model->attributes AS $value) {
                // clean output
                $value = strip_tags($value);
                $value = str_replace("\n", "", $value);
                $value = str_replace("\r", "", $value);
                $value = str_replace("\t", " ", $value);
                $value = str_replace("\s+", " ", $value);
                $return .= $value."|";
            }
            $return .= "\n";
        }

        echo $return;
        return;
    }
}
?>
