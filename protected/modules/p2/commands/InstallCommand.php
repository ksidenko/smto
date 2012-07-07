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
 * Command, installs p2
 *
 * Usage:
 * <pre>./yiic install [config-file]</pre>
 * [config-file] defaults to: 'config/local.php'
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: InstallCommand.php 535 2010-03-29 00:31:55Z schmunk $
 * @package p2.cli.commands
 * @since 2.0
 */
Yii::import('application.modules.p2.components.*');

class InstallCommand extends CConsoleCommand {
    private $_rootPath;

    public function getHelp() {
        return <<<EOD
USAGE
        yiic install <config-file>

DESCRIPTION
        This command generates an P2 Web Application at the specified location.

PARAMETERS
        * config file: required, the path to the main configuration file, 
        usually config/local.php or config/main.php
EOD;
    }

    /**
     * Execute the action.
     * @param array command line parameters specific for this command
     */
    public function run($args) {
        echo "\nWelcome to p2 installer\n";
        echo "========================\n";
        
        // IMPORT CONFIGURATION
        if(!isset($args[0])) {        
            $args[0]='config/local.php';            
        if (!is_file('config/local.php')) {
            // LOCAL CONFIGURATION
            if (is_file('config/local-sample.php')) {
                echo "\nConfiguration\n";
                echo "-------------\n";
                $configPath = realpath('config/');
                    copy(
                    $configPath.DIRECTORY_SEPARATOR.'local-sample.php',
                $configPath.DIRECTORY_SEPARATOR.'local.php'
                );
                echo "\nCreated config/local.php\n\nPlease run installer again.\n\n";
            } else {
                $this->usageError("\nUnable to create config/local.php, please specify your config file, i.e.:\n./yiic install config/main.php \n\n");
            }
        }

        }
        $entryScript=isset($args[0]) ? $args[0] : 'config/local.php';
        if(($entryScript=realpath($args[0]))===false || !is_file($entryScript))
            $this->usageError("{$args[0]} does not exist or is not an entry script file.");
	ob_start();
        $config=require($entryScript);
        ob_end_clean();


        // DIRECTORIES
        $appPath = realpath(dirname(__FILE__)."/../../..");
        // FIXME add args[1] = appPath for some servers
        echo "Application path set to: ".$appPath."\n";


        // PRE-CHECKS
        $errors = null;
        if (isset($config['components']['db']['connectionString'])) {
            if(!strstr($config['components']['db']['connectionString'],"mysql")) {
                $errors[] = "ERROR - Only MySQL installation currently supported!\n";
            }
            $connectionString = $config['components']['db']['connectionString'];
        } else {
            $errors[] = "ERROR - Please specifiy a database component in config! Note: Only MySQL currently supported.\n";
        }

        if ($config['params']['adminEmail']) {
        //
        } else {
            $errors[] = "ERROR - Please specifiy parameter adminEmail in config!\n";
        }
        if ($config['params']['languages']) {
        //
        } else {
            $errors[] = "ERROR - Please specifiy parameter languages in config!\n";
        }

        if ($errors) {
            echo "\n";
            foreach ($errors AS $error) echo $error;
            die();
        }

        // DATABASE
        echo "Opening database connection '$connectionString' ...\n";
        try {
            $db = new PDO(
                $config['components']['db']['connectionString'],
                $config['components']['db']['username'],
                $config['components']['db']['password']);
            if(isset($config['components']['db']['charset']))
                $db->exec('SET NAMES '.$config['components']['db']['charset']);
        } catch (PDOException $e) {
            die('ERROR - Database connection failed: ' . $e->getMessage()."\n");
        }

        // DIRECTORIES
        # Yii::app()->assetManager->basePath; # does not exist in CConsoleApplication -- FIXME
        if(isset($config['params']['p2.install.mode']) && $config['params']['p2.install.mode'] === 'application') $checkDirs[] = $appPath.DIRECTORY_SEPARATOR.'www/assets';
        $checkDirs[] = Yii::app()->runtimePath;
        $checkDirs[] = $appPath.DIRECTORY_SEPARATOR.$config['params']['protectedDataPath'];
        $checkDirs[] = $appPath.DIRECTORY_SEPARATOR.$config['params']['protectedRuntimePath'];
        $checkDirs[] = $appPath.DIRECTORY_SEPARATOR.$config['params']['publicDataPath'];
        $checkDirs[] = $appPath.DIRECTORY_SEPARATOR.$config['params']['publicRuntimePath'];
        echo "\nDirectories\n";
        echo "-----------\n";
        echo "Create a p2 environment under '$appPath'? [Yes|No] ";
        if(!strncasecmp(trim(fgets(STDIN)),'y',1)) {
            $sourceDir=realpath(dirname(__FILE__).'/../');
            if($sourceDir===false)
                die('ERROR - Unable to locate the source directory.');

            foreach($checkDirs AS $dir) {
                @mkdir($dir,0777);
                @chmod($dir,0777);
                echo "\nCreated directory {$dir}";
            }
            echo "\nDirecorties and permissions created successfully under {$appPath}.\n";
        } else {
            echo "... skipped\n";
        }

        // DATABASE
        echo "\nDatabase\n";
        echo "--------\n";
        echo "==============================================\n";
        echo "WARNING! THESE ACTIONS MAY ERASE EXISTING DATA\n";
        echo "==============================================\n";
        echo "Create p2 schema in database? [Yes|No] ";
        if(!strncasecmp(trim(fgets(STDIN)),'y',1)) {
            $p2SchemaSql = file_get_contents($appPath.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'p2'.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'schema.sql');
            $this->executeMultipleSqlStatements($db, $p2SchemaSql);
        } else {
            echo "... skipped\n";
        }

        echo "Import p2 data into database? [Yes|No] ";
        if(!strncasecmp(trim(fgets(STDIN)),'y',1)) {
            echo "Creating admin user ...\n";
            echo "Please choose a password: ";
            $pass = trim(fgets(STDIN));
            echo "E-Mail address: ";
            $email = trim(fgets(STDIN));
            $replace = array('__ADMIN_PASSWORD__' => P2Helper::hash($pass), '__ADMIN_EMAIL__' => $email);
            $p2DataSql = file_get_contents($appPath.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'p2'.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'data.sql');
            $p2DataSql = strtr($p2DataSql, $replace);
            $this->executeMultipleSqlStatements($db, $p2DataSql);
        } else {
            echo "... skipped\n";
        }

        $appSchemaFile = $appPath.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'schema.sql';
        if (is_file($appSchemaFile)) {
            echo "Create project schema in database? [Yes|No] ";
            if(!strncasecmp(trim(fgets(STDIN)),'y',1)) {
                $appSchemaSql = file_get_contents($appSchemaFile);
                $this->executeMultipleSqlStatements($db, $appSchemaSql);
            } else {
                echo "... skipped\n";
            }
        }

        $appDataFile = $appPath.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'data.sql';
        if (is_file($appDataFile)) {
            echo "Import project data into database? [Yes|No] ";
            if(!strncasecmp(trim(fgets(STDIN)),'y',1)) {
                $appDataSql = file_get_contents($appDataFile);
                $this->executeMultipleSqlStatements($db, $appDataSql);
            } else {
                echo "... skipped\n";
            }
        }




        echo "\nPlease visit the module installation check page at /p2/default/install in your browser.\n";
        echo "Thank you for choosing phundament.\n";
    }

    /**
     * Some servers require statement splitting
     * @param <type> $pdo
     * @param <type> $sql
     * @return <type>
     */
    private function executeMultipleSqlStatements($pdo, $sql) {
        foreach(explode(';
', $sql) AS $part) {
            if (!trim($part)) {
                continue;
            }
            $pdo->exec($part);
            $error = $pdo->errorInfo();
            if($error[0] != 0) {
                echo $part;
                print_r($error);
            } else {
                echo '.';
            }
        }
        echo "\n";
    }

}
