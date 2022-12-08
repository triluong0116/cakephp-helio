<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HotelSurcharges Model
 *
 * @property \App\Model\Table\HotelsTable|\Cake\ORM\Association\BelongsTo $Hotels
 *
 * @method \App\Model\Entity\HotelSurcharge get($primaryKey, $options = [])
 * @method \App\Model\Entity\HotelSurcharge newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\HotelSurcharge[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HotelSurcharge|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HotelSurcharge|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HotelSurcharge patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\HotelSurcharge[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\HotelSurcharge findOrCreate($search, callable $callback = null, $options = [])
 */
class HotelSurchargesTable extends Table
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

        $this->setTable('hotel_surcharges');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Hotels', [
            'foreignKey' => 'hotel_id',
            'joinType' => 'INNER'
        ]);
        $this->addBehavior('Sluggable', [
            'field' => 'other_name',
            'slug' => 'other_slug'
        ]);

        $this->belongsTo('Surcharges', [
            'foreignKey' => 'surcharge_type',
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
//
//        $validator
//            ->scalar('options')
//            ->allowEmpty('options');

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
