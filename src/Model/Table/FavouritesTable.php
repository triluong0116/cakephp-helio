<?php
namespace App\Model\Table;

use App\Model\Entity\Combo;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Favourites Model
 *
 * @property \App\Model\Table\ObjectsTable|\Cake\ORM\Association\BelongsTo $Objects
 *
 * @method \App\Model\Entity\Favourite get($primaryKey, $options = [])
 * @method \App\Model\Entity\Favourite newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Favourite[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Favourite|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Favourite|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Favourite patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Favourite[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Favourite findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FavouritesTable extends Table
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

        $this->setTable('favourites');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Combos', [
            'className' => 'Combos',
            'foreignKey' => 'object_id',
            'bindingKey' => 'id',
            'conditions' => ['object_type' => COMBO],
            'propertyName' => 'combo'
        ]);

        $this->belongsTo('Vouchers', [
            'className' => 'Vouchers',
            'foreignKey' => 'object_id',
            'bindingKey' => 'id',
            'conditions' => ['object_type' => VOUCHER],
            'propertyName' => 'voucher'
        ]);

        $this->belongsTo('LandTours', [
            'className' => 'LandTours',
            'foreignKey' => 'object_id',
            'bindingKey' => 'id',
            'conditions' => ['object_type' => LANDTOUR],
            'propertyName' => 'land_tour'
        ]);

        $this->belongsTo('Hotels', [
            'className' => 'Hotels',
            'foreignKey' => 'object_id',
            'bindingKey' => 'id',
            'conditions' => ['object_type' => HOTEL],
            'propertyName' => 'hotel'
        ]);

        $this->belongsTo('HotelSearchs', [
            'className' => 'HotelSearchs',
            'foreignKey' => 'object_id',
            'bindingKey' => 'id',
            'conditions' => ['object_type' => HOTEL],
            'propertyName' => 'hotel_search'
        ]);


        $this->belongsTo('HomeStays', [
            'className' => 'HomeStays',
            'foreignKey' => 'object_id',
            'bindingKey' => 'id',
            'conditions' => ['object_type' => HOMESTAY],
            'propertyName' => 'home_stay'
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
//            ->scalar('clientId')
//            ->maxLength('clientId', 255)
//            ->requirePresence('clientId', 'create')
//            ->notEmpty('clientId');

        $validator
            ->integer('object_type')
            ->requirePresence('object_type', 'create')
            ->notEmpty('object_type');

        $validator
            ->integer('object_id')
            ->requirePresence('object_id', 'create')
            ->notEmpty('object_id');

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
        return $rules;
    }
}
