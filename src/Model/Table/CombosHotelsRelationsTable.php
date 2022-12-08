<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class CombosHotelsRelationsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('combos_hotels');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Hotels');
        $this->belongsTo('Combos');
    }

}
