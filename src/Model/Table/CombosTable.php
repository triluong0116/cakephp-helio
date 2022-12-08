<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Combos Model
 *
 * @property \App\Model\Table\DeparturesTable|\Cake\ORM\Association\BelongsTo $Departures
 * @property \App\Model\Table\DestinationsTable|\Cake\ORM\Association\BelongsTo $Destinations
 * @property \App\Model\Table\BookingsTable|\Cake\ORM\Association\HasMany $Bookings
 * @property \App\Model\Table\RoomsTable|\Cake\ORM\Association\BelongsToMany $Rooms
 *
 * @method \App\Model\Entity\Combo get($primaryKey, $options = [])
 * @method \App\Model\Entity\Combo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Combo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Combo|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Combo|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Combo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Combo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Combo findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CombosTable extends Table
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

        $this->setTable('combos');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Sluggable', [
            'field' => 'name'
        ]);
        $this->addBehavior('CounterCache', [
            'Destinations' => [
                'combo_count' => function ($event, $entity, $table, $original) {                    
                    $total_vote = $table->find()
                                    ->select([
                                        'count' => $table->find()->func()->count('Combos.id')
                                    ])
                                    ->where(['destination_id' => $entity->destination_id])->first();                    
                    return $total_vote->count;
                }
            ]
        ]);

        $this->belongsTo('Departures', [
            'className' => 'Locations',
            'foreignKey' => 'departure_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Destinations', [
            'className' => 'Locations',
            'foreignKey' => 'destination_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Bookings', [
            'foreignKey' => 'combo_id'
        ]);
        $this->belongsToMany('Rooms', [
            'foreignKey' => 'combo_id',
            'targetForeignKey' => 'room_id',
            'joinTable' => 'combos_rooms'
        ]);
        $this->belongsToMany('Hotels', [
            'through' => 'CombosHotelsRelations',
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
//            ->maxLength('name', 255)
//            ->requirePresence('name', 'create')
//            ->notEmpty('name');
//
//        $validator
//            ->scalar('slug')
//            ->maxLength('slug', 255)
//            ->requirePresence('slug', 'create')
//            ->notEmpty('slug');
//
//        $validator
//            ->integer('price')
//            ->requirePresence('price', 'create')
//            ->notEmpty('price');
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
//
//        $validator
//            ->numeric('promote')
//            ->requirePresence('promote', 'create')
//            ->notEmpty('promote');
//
//        $validator
//            ->integer('days')
//            ->requirePresence('days', 'create')
//            ->notEmpty('days');
//
//        $validator
//            ->numeric('rating')
//            ->requirePresence('rating', 'create')
//            ->notEmpty('rating');
//
//        $validator
//            ->scalar('thumbnail')
//            ->maxLength('thumbnail', 255)
//            ->requirePresence('thumbnail', 'create')
//            ->notEmpty('thumbnail');
//
//        $validator
//            ->scalar('media')
//            ->requirePresence('media', 'create')
//            ->notEmpty('media');
//
//        $validator
//            ->date('date_start')
//            ->requirePresence('date_start', 'create')
//            ->notEmpty('date_start');
//
//        $validator
//            ->date('date_end')
//            ->requirePresence('date_end', 'create')
//            ->notEmpty('date_end');

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
        $rules->add($rules->existsIn(['departure_id'], 'Departures'));
        $rules->add($rules->existsIn(['destination_id'], 'Destinations'));

        return $rules;
    }
}
