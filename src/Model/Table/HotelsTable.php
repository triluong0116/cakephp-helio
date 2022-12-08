<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Hotels Model
 *
 * @property \App\Model\Table\LocationsTable|\Cake\ORM\Association\BelongsTo $Locations
 * @property \App\Model\Table\RoomsTable|\Cake\ORM\Association\HasMany $Rooms
 * @property \App\Model\Table\PriceHotelsTable|\Cake\ORM\Association\HasMany $PriceHotels
 * @property \App\Model\Table\CategoriesTable|\Cake\ORM\Association\BelongsToMany $Categories
 *
 * @method \App\Model\Entity\Hotel get($primaryKey, $options = [])
 * @method \App\Model\Entity\Hotel newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Hotel[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Hotel|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Hotel|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Hotel patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Hotel[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Hotel findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HotelsTable extends Table
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

        $this->setTable('hotels');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('CounterCache', [
            'Locations' => ['hotel_count']
        ]);

        $this->addBehavior('Timestamp');
        $this->addBehavior('Sluggable', [
            'field' => 'name'
        ]);
//        $this->addBehavior('Muffin/Trash.Trash', [
//            'events' => ['Model.beforeFind']
//        ]);
        $this->belongsTo('Locations', [
            'foreignKey' => 'location_id',
            'joinType' => 'LEFT'
        ]);
        $this->hasMany('Rooms', [
            'foreignKey' => 'hotel_id',
            'saveStrategy' => 'replace'
        ]);
        $this->hasMany('Channelrooms', [
            'foreignKey' => 'hotel_id',
            'saveStrategy' => 'replace'
        ]);
        $this->hasMany('PriceHotels', [
            'foreignKey' => 'hotel_id'
        ]);
        $this->belongsToMany('Categories', [
            'foreignKey' => 'hotel_id',
            'targetForeignKey' => 'category_id',
            'joinTable' => 'hotels_categories'
        ]);
        $this->belongsToMany('Combos', [
            'through' => 'CombosHotelsRelations',
        ]);

        $this->hasMany('Favourites', [
            'className' => 'Favourites',
            'foreignKey' => 'object_id',
            'bindingKey' => 'id',
            'conditions' => ['object_type' => HOTEL],
            'propertyName' => 'favourites',
            'joinType' => 'LEFT'
        ]);

//        $this->belongsToMany('Surcharges', [
//            'through' => 'HotelsSurchargesRelations',
//        ]);

        $this->hasMany('HotelSurcharges', [
            'foreignKey' => 'hotel_id',
            'saveStrategy' => 'replace'
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
//            ->scalar('description')
//            ->requirePresence('description', 'create')
//            ->notEmpty('description');
//
//        $validator
//            ->scalar('thumbnail')
//            ->maxLength('thumbnail', 255)
//            ->requirePresence('thumbnail', 'create')
//            ->notEmpty('thumbnail');
//
//        $validator
//            ->scalar('album')
//            ->requirePresence('album', 'create')
//            ->notEmpty('album');
//
//        $validator
//            ->numeric('rating')
//            ->requirePresence('rating', 'create')
//            ->notEmpty('rating');
//
//        $validator
//            ->scalar('map')
//            ->requirePresence('map', 'create')
//            ->notEmpty('map');
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
        $rules->add($rules->existsIn(['location_id'], 'Locations'));

        return $rules;
    }
}
