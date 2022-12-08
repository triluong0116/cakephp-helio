<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LandTourSurcharges Model
 *
 * @property \App\Model\Table\LandToursTable|\Cake\ORM\Association\BelongsTo $LandTours
 *
 * @method \App\Model\Entity\LandTourSurcharge get($primaryKey, $options = [])
 * @method \App\Model\Entity\LandTourSurcharge newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LandTourSurcharge[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LandTourSurcharge|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LandTourSurcharge|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LandTourSurcharge patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LandTourSurcharge[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LandTourSurcharge findOrCreate($search, callable $callback = null, $options = [])
 */
class LandTourSurchargesTable extends Table
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

        $this->setTable('land_tour_surcharges');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('LandTours', [
            'foreignKey' => 'land_tour_id',
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
//            ->requirePresence('surcharge_type', 'create')
//            ->notEmpty('surcharge_type');
//
//        $validator
//            ->integer('price')
//            ->requirePresence('price', 'create')
//            ->notEmpty('price');

        $validator
            ->scalar('options')
            ->allowEmpty('options');

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
        $rules->add($rules->existsIn(['land_tour_id'], 'LandTours'));

        return $rules;
    }
}
