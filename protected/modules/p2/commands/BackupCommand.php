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
 * Command, creates database dump
 *
 * Usage:
 * <pre>./yiic backup</pre>
 * <b>NOTE! MySQL only yet, uses exec() and mysqldump.</b>
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: BackupCommand.php 511 2010-03-24 00:41:52Z schmunk $
 * @package p2.cli.commands
 * @since 2.0
 */
class BackupCommand extends CConsoleCommand {
    private $_rootPath;

    public function getHelp() {
        return <<<EOD
        USAGE
  yiic backup <config-file>

DESCRIPTION
        This command generates an sql dump in data/backup

PARAMETERS
        * app-path: required, the directory where the new application will be created.
   If the directory does not exist, it will be created. After the application
   is created, please make sure the directory can be accessed by Web users.

EOD;
    }

    /**
     * Execute the action.
     * @param array command line parameters specific for this command
     */
    public function run($args) {
        echo "\nWelcome to p2 backup\n";
        echo "========================\n";

        // IMPORT CONFIGURATION
        if(!isset($args[0]))
            $args[0]='config/local.php';
        $entryScript=isset($args[0]) ? $args[0] : 'config/local.php';
        if(($entryScript=realpath($args[0]))===false || !is_file($entryScript))
            $this->usageError("{$args[0]} does not exist or is not an entry script file.");
        ob_start();
        $config=require($entryScript);
        ob_end_clean();


        // PRE-CHECKS
        if (isset($config['components']['db']['connectionString'])) {
            $connectionString = $config['components']['db']['connectionString'];
        } else {
            die("\nERROR - Please specifiy a database component in config/local.php or config/main.php!\n");
        }

        $backupDir = Yii::app()->basePath.DS.$config['params']['protectedDataPath'].DS.'backup';
        if (!is_dir($backupDir)) {
            mkdir($backupDir);
        }

        echo "Opening database connection '$connectionString' ...\n";
        try {
            $db = new PDO(
                $config['components']['db']['connectionString'],
                $config['components']['db']['username'],
                $config['components']['db']['password']);
        } catch (PDOException $e) {
            die('ERROR - Database connection failed: ' . $e->getMessage()."\n");
        }

        echo "Create database backup in '".$backupDir."' ? [Yes|No] ";
        if(!strncasecmp(trim(fgets(STDIN)),'y',1)) {
            $dbType = substr($config['components']['db']['connectionString'],0,6);
            if ($dbType == "mysql:") {
                $keyValuePairs = explode(";",substr($config['components']['db']['connectionString'],6));
                foreach($keyValuePairs AS $pair) {
                    $pairSplit = explode("=",$pair);
                    $cmdParams[$pairSplit[0]] = $pairSplit[1];
                }
            } else {
                throw new Exception('Only MySQL backup currently supported.');
            }

            $backupFile = $backupDir.DS.date("Ymd-His") . '.sql.gz';
	    $opt = "--skip-lock-tables";//" --opt";
            $command = "mysqldump ".$opt." -h".$cmdParams['host']." -u".$config['components']['db']['username']." -p".$config['components']['db']['password']." ".$cmdParams['dbname']." | gzip > $backupFile";
            system($command);
            echo "Created backup file ".$backupFile."\n";
            echo "\nHint: Use 'scp' at your local computer to retrieve this file:\n";
            echo "scp USER@HOST:".$backupFile." .\n";


            echo "\nBackup complete.\n";
        } else {
            echo "... skipped\n";
        }
    }

}
