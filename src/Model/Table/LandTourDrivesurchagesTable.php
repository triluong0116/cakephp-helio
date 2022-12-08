<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LandTourDrivesurchages Model
 *
 * @property \App\Model\Table\LandToursTable|\Cake\ORM\Association\BelongsTo $LandTours
 *
 * @method \App\Model\Entity\LandTourDrivesurchage get($primaryKey, $options = [])
 * @method \App\Model\Entity\LandTourDrivesurchage newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LandTourDrivesurchage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LandTourDrivesurchage|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LandTourDrivesurchage|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LandTourDrivesurchage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LandTourDrivesurchage[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LandTourDrivesurchage findOrCreate($search, callable $callback = null, $options = [])
 */
class LandTourDrivesurchagesTable extends Table
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

        $this->setTable('land_tour_drivesurchages');
        $this->setDisplayField('name');
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
//            ->scalar('name')
//            ->maxLength('name', 16777215)
//            ->requirePresence('name', 'create')
//            ->notEmpty('name');
//
//        $validator
//            ->integer('price_adult')
//            ->requirePresence('price_adult', 'create')
//            ->notEmpty('price_adult');
//
//        $validator
//            ->integer('price_crowd')
//            ->requirePresence('price_crowd', 'create')
//            ->notEmpty('price_crowd');

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
