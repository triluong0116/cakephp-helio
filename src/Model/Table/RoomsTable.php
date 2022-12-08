<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Rooms Model
 *
 * @property \App\Model\Table\HotelsTable|\Cake\ORM\Association\BelongsTo $Hotels
 * @property \App\Model\Table\PriceRoomsTable|\Cake\ORM\Association\HasMany $PriceRooms
 * @property \App\Model\Table\CombosTable|\Cake\ORM\Association\BelongsToMany $Combos
 * @property \App\Model\Table\CategoriesTable|\Cake\ORM\Association\BelongsToMany $Categories
 *
 * @method \App\Model\Entity\Room get($primaryKey, $options = [])
 * @method \App\Model\Entity\Room newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Room[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Room|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Room|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Room patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Room[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Room findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RoomsTable extends Table
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

        $this->setTable('rooms');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Sluggable', [
            'field' => 'name'
        ]);

        $this->belongsTo('Hotels', [
            'foreignKey' => 'hotel_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('PriceRooms', [
            'foreignKey' => 'room_id',
            'saveStrategy' => 'replace'
        ]);
        $this->belongsToMany('Combos', [
            'foreignKey' => 'room_id',
            'targetForeignKey' => 'combo_id',
            'joinTable' => 'combos_rooms'
        ]);
        $this->belongsToMany('Categories', [
            'foreignKey' => 'room_id',
            'targetForeignKey' => 'category_id',
            'joinTable' => 'rooms_categories'
        ]);
        $this->hasMany('RoomPrices', [
            'foreignKey' => 'room_id',
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
//            ->numeric('area')
//            ->requirePresence('area', 'create')
//            ->notEmpty('area');
//
//        $validator
//            ->integer('num_bed')
//            ->requirePresence('num_bed', 'create')
//            ->notEmpty('num_bed');
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
