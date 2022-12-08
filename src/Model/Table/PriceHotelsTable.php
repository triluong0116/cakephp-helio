<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PriceHotels Model
 *
 * @property \App\Model\Table\HotelsTable|\Cake\ORM\Association\BelongsTo $Hotels
 *
 * @method \App\Model\Entity\PriceHotel get($primaryKey, $options = [])
 * @method \App\Model\Entity\PriceHotel newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PriceHotel[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PriceHotel|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PriceHotel|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PriceHotel patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PriceHotel[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PriceHotel findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PriceHotelsTable extends Table
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

        $this->setTable('price_hotels');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

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

        $validator
            ->date('start_date')
            ->requirePresence('start_date', 'create')
            ->notEmpty('start_date');

        $validator
            ->date('end_date')
            ->requirePresence('end_date', 'create')
            ->notEmpty('end_date');

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
        $rules->add($rules->existsIn(['hotel_id'], 'Hotels'));

        return $rules;
    }
}
