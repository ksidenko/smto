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
 * Command, displays help information
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: HelpCommand.php 511 2010-03-24 00:41:52Z schmunk $
 * @package p2.cli.commands
 * @since 2.0
 */
class HelpCommand extends CConsoleCommand
{
	/**
	 * Execute the action.
	 * @param array command line parameters specific for this command
	 */
	public function run($args)
	{
		$runner=$this->getCommandRunner();
		$commands=$runner->commands;
		if(isset($args[0]))
			$name=strtolower($args[0]);
		if(!isset($args[0]) || !isset($commands[$name]))
		{
			echo <<<EOD
At the prompt, you may enter a PHP statement or one of the following commands:

EOD;
			$commandNames=array_keys($commands);
			sort($commandNames);
			echo ' - '.implode("\n - ",$commandNames);
			echo "\nType 'help <command-name>' for details about a command.\n";
		}
		else
			echo $runner->createCommand($name)->getHelp();
	}

	/**
	 * Provides the command description.
	 * @return string the command description.
	 */
	public function getHelp()
	{
		return <<<EOD
USAGE
  help [command-name]

DESCRIPTION
  Display the help information for the specified command.
  If the command name is not given, all commands will be listed.

PARAMETERS
 * command-name: optional, the name of the command to show help information.

EOD;
	}
}