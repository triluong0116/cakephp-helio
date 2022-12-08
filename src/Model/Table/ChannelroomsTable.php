<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Channelrooms Model
 *
 * @property \App\Model\Table\HotelsTable|\Cake\ORM\Association\BelongsTo $Hotels
 *
 * @method \App\Model\Entity\Channelroom get($primaryKey, $options = [])
 * @method \App\Model\Entity\Channelroom newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Channelroom[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Channelroom|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Channelroom|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Channelroom patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Channelroom[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Channelroom findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ChannelroomsTable extends Table
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

        $this->setTable('channelrooms');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Hotels', [
            'foreignKey' => 'hotel_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Channelrateplanes', [
            'foreignKey' => 'channel_room_id',
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
//            ->scalar('hotel_link_code')
//            ->maxLength('hotel_link_code', 255)
//            ->requirePresence('hotel_link_code', 'create')
//            ->notEmpty('hotel_link_code');
//
//        $validator
//            ->scalar('room_code')
//            ->maxLength('room_code', 255)
//            ->requirePresence('room_code', 'create')
//            ->notEmpty('room_code');
//
//        $validator
//            ->scalar('name')
//            ->allowEmpty('name');
//
//        $validator
//            ->scalar('description')
//            ->allowEmpty('description');
//
//        $validator
//            ->scalar('area')
//            ->allowEmpty('area');
//
//        $validator
//            ->scalar('thumbnail')
//            ->allowEmpty('thumbnail');
//
//        $validator
//            ->scalar('media')
//            ->allowEmpty('media');
//
//        $validator
//            ->scalar('view_type')
//            ->maxLength('view_type', 255)
//            ->allowEmpty('view_type');

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
