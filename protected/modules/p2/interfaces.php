<?php
/**
 * Interfaces
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @link http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2010 diemeisterei GmbH
 * @license http://www.phundament.com/license/
 */

/**
 * Interfaces for p2
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: interfaces.php 512 2010-03-24 00:42:38Z schmunk $
 * @package p2.cellmanager
 * @since 2.0
 */

interface ICellManagerWidget {

    public function getCreateView();
    public function getCreateData();

    public function getUpdateView();
    public function getUpdateData();

    public function getHasData();
    public function getAdminParams();
    public function getHeadline();
    
}
?>
