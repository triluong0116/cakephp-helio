<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Vinrooms Model
 *
 * @property \App\Model\Table\HotelsTable|\Cake\ORM\Association\BelongsTo $Hotels
 *
 * @method \App\Model\Entity\Vinroom get($primaryKey, $options = [])
 * @method \App\Model\Entity\Vinroom newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Vinroom[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Vinroom|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Vinroom|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Vinroom patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Vinroom[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Vinroom findOrCreate($search, callable $callback = null, $options = [])
 */
class VinroomsTable extends Table
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

        $this->setTable('vinrooms');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Hotels', [
            'foreignKey' => 'hotel_id',
            'joinType' => 'INNER'
        ]);
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
//            ->scalar('vin_code')
//            ->maxLength('vin_code', 255)
//            ->requirePresence('vin_code', 'create')
//            ->notEmpty('vin_code');
//
//        $validator
//            ->integer('trippal_price')
//            ->requirePresence('trippal_price', 'create')
//            ->notEmpty('trippal_price');
//
//        $validator
//            ->integer('customer_price')
//            ->requirePresence('customer_price', 'create')
//            ->notEmpty('customer_price');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['hotel_id'], 'Hotels'));

        return $rules;
    }
}
