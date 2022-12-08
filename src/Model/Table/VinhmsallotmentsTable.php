<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Vinhmsallotments Model
 *
 * @method \App\Model\Entity\Vinhmsallotment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Vinhmsallotment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Vinhmsallotment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Vinhmsallotment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Vinhmsallotment|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Vinhmsallotment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Vinhmsallotment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Vinhmsallotment findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VinhmsallotmentsTable extends Table
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

        $this->setTable('vinhmsallotments');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

//        $validator
//            ->scalar('code')
//            ->maxLength('code', 255)
//            ->requirePresence('code', 'create')
//            ->notEmpty('code');
//
//        $validator
//            ->scalar('name')
//            ->maxLength('name', 255)
//            ->requirePresence('name', 'create')
//            ->notEmpty('name');
//
//        $validator
//            ->integer('sale_revenue')
//            ->requirePresence('sale_revenue', 'create')
//            ->notEmpty('sale_revenue');
//
//        $validator
//            ->integer('revenue')
//            ->requirePresence('revenue', 'create')
//            ->notEmpty('revenue');

        return $validator;
    }
}
