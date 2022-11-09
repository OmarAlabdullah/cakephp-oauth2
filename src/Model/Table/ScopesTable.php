<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class ScopesTable  extends Table
{
    /**
     * @param array $config Config
     * @return void
     */
    public function initialize(array $config): void
    {
        $this->setTable('scopes');
        $this->setPrimaryKey('identifier');

        parent::initialize($config);
    }

}
