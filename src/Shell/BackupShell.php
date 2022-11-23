<?php
declare(strict_types=1);


namespace App\Shell;

use Xel\Cake\Shell\XelShell;
use Xel\Util\BackupDatabase;

class BackupShell extends XelShell
{

    private BackupDatabase $backupDatabase;

    //override startup to disable welcome message
    public function startup(): void
    {
    }

    public function main(...$args)
    {
        $this->out('Need a subcommand: Databases');
    }

    /**
     * @Inject
     * @param BackupDatabase $backupDatabase
     */
    public function set(BackupDatabase $backupDatabase)
    {
        $this->backupDatabase = $backupDatabase;
    }

    public function Databases()
    {
        $this->out("Starting task backup_database");
        $this->backupDatabase->start();
        $this->out("Backup_database task finished");
    }
}
