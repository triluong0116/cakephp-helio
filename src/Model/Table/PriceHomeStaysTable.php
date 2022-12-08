<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PriceHomeStays Model
 *
 * @property \App\Model\Table\HomeStaysTable|\Cake\ORM\Association\BelongsTo $HomeStays
 *
 * @method \App\Model\Entity\PriceHomeStay get($primaryKey, $options = [])
 * @method \App\Model\Entity\PriceHomeStay newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PriceHomeStay[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PriceHomeStay|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PriceHomeStay|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PriceHomeStay patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PriceHomeStay[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PriceHomeStay findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PriceHomeStaysTable extends Table
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

        $this->setTable('price_home_stays');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('HomeStays', [
            'foreignKey' => 'home_stay_id',
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

        $validator
            ->integer('type')
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->integer('price')
            ->requirePresence('price', 'create')
            ->notEmpty('price');

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
        $rules->add($rules->existsIn(['home_stay_id'], 'HomeStays'));

        return $rules;
    }
}
