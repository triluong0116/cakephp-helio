<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VouchersRooms Model
 *
 * @property \App\Model\Table\VouchersTable|\Cake\ORM\Association\BelongsTo $Vouchers
 * @property \App\Model\Table\RoomsTable|\Cake\ORM\Association\BelongsTo $Rooms
 *
 * @method \App\Model\Entity\VouchersRoom get($primaryKey, $options = [])
 * @method \App\Model\Entity\VouchersRoom newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VouchersRoom[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VouchersRoom|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VouchersRoom|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VouchersRoom patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VouchersRoom[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VouchersRoom findOrCreate($search, callable $callback = null, $options = [])
 */
class VouchersRoomsTable extends Table
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

        $this->setTable('vouchers_rooms');

        $this->belongsTo('Vouchers', [
            'foreignKey' => 'voucher_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Rooms', [
            'foreignKey' => 'room_id',
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
            ->requirePresence('id', 'create')
            ->notEmpty('id');

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
        $rules->add($rules->existsIn(['voucher_id'], 'Vouchers'));
        $rules->add($rules->existsIn(['room_id'], 'Rooms'));

        return $rules;
    }
}
