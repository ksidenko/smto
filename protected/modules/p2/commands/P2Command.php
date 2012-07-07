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
 * Command, special p2 shell
 *
 * Usage:
 * <pre>./yiic p2</pre>
 *
 * Available commands:
 * <ul>
 * <li>{@link P2CrudCommand} generates p2 CRUDs
 * </ul>
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2Command.php 516 2010-03-24 01:00:00Z schmunk $
 * @package p2.cli.commands
 * @since 2.0
 */
Yii::import('system.cli.commands.*');

class P2Command extends ShellCommand
{
    protected function runShell()
	{
		// disable E_NOTICE so that the shell is more friendly
		error_reporting(E_ALL ^ E_NOTICE);

                #set_include_path(Yii::app()->getBasePath().":".get_include_path());
                
		$_runner_=new CConsoleCommandRunner;
		$_runner_->addCommands(dirname(__FILE__).'/p2');
		$_runner_->addCommands(Yii::getPathOfAlias('application.commands.shell'));
		$_commands_=$_runner_->commands;

		while(($_line_=$this->readline("\n>> "))!==false)
		{
			$_line_=trim($_line_);
			try
			{
				$_args_=preg_split('/[\s,]+/',rtrim($_line_,';'),-1,PREG_SPLIT_NO_EMPTY);
				if(isset($_args_[0]) && isset($_commands_[$_args_[0]]))
				{
					$_command_=$_runner_->createCommand($_args_[0]);
					array_shift($_args_);
					$_command_->run($_args_);
				}
				else {
                                    echo eval($_line_.';');
                                }

			}
			catch(Exception $e)
			{
				if($e instanceof ShellException)
					echo $e->getMessage();
				else
					echo $e;
			}
		}
	}
}