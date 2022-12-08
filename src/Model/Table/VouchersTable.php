<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Vouchers Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\DeparturesTable|\Cake\ORM\Association\BelongsTo $Departures
 * @property \App\Model\Table\DestinationsTable|\Cake\ORM\Association\BelongsTo $Destinations
 * @property \App\Model\Table\HotelsTable|\Cake\ORM\Association\BelongsTo $Hotels
 *
 * @method \App\Model\Entity\Voucher get($primaryKey, $options = [])
 * @method \App\Model\Entity\Voucher newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Voucher[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Voucher|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Voucher|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Voucher patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Voucher[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Voucher findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VouchersTable extends Table
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

        $this->setTable('vouchers');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Sluggable', [
            'field' => 'name'
        ]);
        $this->addBehavior('CounterCache', [
            'Destinations' => [
                'voucher_count' => function ($event, $entity, $table, $original) {                    
                    $total_vote = $table->find()
                                    ->select([
                                        'count' => $table->find()->func()->count('Vouchers.id')
                                    ])
                                    ->where(['destination_id' => $entity->destination_id])->first();                    
                    return $total_vote->count;
                }
            ]
        ]);
//        $this->addBehavior('Muffin/Trash.Trash', [
//            'events' => ['Model.beforeFind']
//        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Departures', [
            'className' => 'Locations',
            'foreignKey' => 'departure_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Destinations', [
            'className' => 'Locations',
            'foreignKey' => 'destination_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Hotels', [
            'className' => 'Hotels',
            'foreignKey' => 'hotel_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Favourites', [
            'className' => 'Favourites',
            'foreignKey' => 'object_id',
            'bindingKey' => 'id',
            'conditions' => ['object_type' => VOUCHER],
            'propertyName' => 'favourites',
            'joinType' => 'LEFT'
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
//            ->scalar('caption')
//            ->requirePresence('caption', 'create')
//            ->notEmpty('caption');
//
//        $validator
//            ->scalar('description')
//            ->requirePresence('description', 'create')
//            ->notEmpty('description');
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
//            ->date('start_date')
//            ->requirePresence('start_date', 'create')
//            ->notEmpty('start_date');
//
//        $validator
//            ->date('end_date')
//            ->requirePresence('end_date', 'create')
//            ->notEmpty('end_date');
//
//        $validator
//            ->integer('status')
//            ->requirePresence('status', 'create')
//            ->notEmpty('status');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['departure_id'], 'Departures'));
        $rules->add($rules->existsIn(['destination_id'], 'Destinations'));

        return $rules;
    }
}
