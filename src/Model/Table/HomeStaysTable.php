<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HomeStays Model
 *
 * @property \App\Model\Table\LocationsTable|\Cake\ORM\Association\BelongsTo $Locations
 * @property \App\Model\Table\PriceHomeStaysTable|\Cake\ORM\Association\HasMany $PriceHomeStays
 * @property \App\Model\Table\CategoriesTable|\Cake\ORM\Association\BelongsToMany $Categories
 *
 * @method \App\Model\Entity\HomeStay get($primaryKey, $options = [])
 * @method \App\Model\Entity\HomeStay newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\HomeStay[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HomeStay|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HomeStay|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HomeStay patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\HomeStay[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\HomeStay findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HomeStaysTable extends Table
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

        $this->setTable('home_stays');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Sluggable', [
            'field' => 'name'
        ]);

        $this->addBehavior('CounterCache', [
            'Locations' => [
                'homestay_count' => function ($event, $entity, $table, $original) {
                    $total_vote = $table->find()
                        ->select([
                            'count' => $table->find()->func()->count('HomeStays.id')
                        ])
                        ->where(['location_id' => $entity->location_id])->first();
                    return $total_vote->count;
                }
            ]
        ]);
//        $this->addBehavior('Muffin/Trash.Trash', [
//            'events' => ['Model.beforeFind']
//        ]);
        $this->belongsTo('Locations', [
            'foreignKey' => 'location_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('PriceHomeStays', [
            'foreignKey' => 'home_stay_id'
        ]);
        $this->belongsToMany('Categories', [
            'foreignKey' => 'home_stay_id',
            'targetForeignKey' => 'category_id',
            'joinTable' => 'home_stays_categories'
        ]);
        $this->hasMany('Favourites', [
            'className' => 'Favourites',
            'foreignKey' => 'object_id',
            'bindingKey' => 'id',
            'conditions' => ['object_type' => HOMESTAY],
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
//            ->scalar('address')
//            ->maxLength('address', 255)
//            ->requirePresence('address', 'create')
//            ->notEmpty('address');
//
//        $validator
//            ->scalar('description')
//            ->requirePresence('description', 'create')
//            ->notEmpty('description');
//
//        $validator
//            ->numeric('rating')
//            ->requirePresence('rating', 'create')
//            ->notEmpty('rating');
//
//        $validator
//            ->email('email')
//            ->requirePresence('email', 'create')
//            ->notEmpty('email');
//
//        $validator
//            ->scalar('fb_content')
//            ->requirePresence('fb_content', 'create')
//            ->notEmpty('fb_content');
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
//            ->scalar('hotline')
//            ->maxLength('hotline', 255)
//            ->requirePresence('hotline', 'create')
//            ->notEmpty('hotline');
//
//        $validator
//            ->scalar('term')
//            ->requirePresence('term', 'create')
//            ->notEmpty('term');
//
//        $validator
//            ->integer('homestay_type')
//            ->requirePresence('homestay_type', 'create')
//            ->notEmpty('homestay_type');
//
//        $validator
//            ->integer('room_type')
//            ->requirePresence('room_type', 'create')
//            ->notEmpty('room_type');
//
//        $validator
//            ->integer('num_bed_room')
//            ->requirePresence('num_bed_room', 'create')
//            ->notEmpty('num_bed_room');
//
//        $validator
//            ->integer('num_guest')
//            ->requirePresence('num_guest', 'create')
//            ->notEmpty('num_guest');
//
//        $validator
//            ->integer('num_bed')
//            ->requirePresence('num_bed', 'create')
//            ->notEmpty('num_bed');
//
//        $validator
//            ->integer('num_bath_room')
//            ->requirePresence('num_bath_room', 'create')
//            ->notEmpty('num_bath_room');
//
//        $validator
//            ->integer('price_agency')
//            ->requirePresence('price_agency', 'create')
//            ->notEmpty('price_agency');
//
//        $validator
//            ->integer('price_customer')
//            ->requirePresence('price_customer', 'create')
//            ->notEmpty('price_customer');

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
//        $rules->add($rules->isUnique(['email']));
//        $rules->add($rules->existsIn(['location_id'], 'Locations'));

        return $rules;
    }
}
