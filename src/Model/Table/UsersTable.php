<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\RolesTable|\Cake\ORM\Association\BelongsTo $Roles
 * @property \App\Model\Table\HotelsTable|\Cake\ORM\Association\BelongsTo $Hotels
 * @property \App\Model\Table\BookingsTable|\Cake\ORM\Association\HasMany $Bookings
 * @property \App\Model\Table\CommentsTable|\Cake\ORM\Association\HasMany $Comments
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Hotels', [
            'foreignKey' => 'hotel_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Bookings', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Comments', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Fanpages', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('UserTransactions', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('UserSessions', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsToMany('Bookings', [
            'through' => 'BookingsUsersRelations',
        ]);
        $this->belongsTo('ParentUers', [
            'className' => 'Users',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildUsers', [
            'className' => 'Users',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Clients', [
            'className' => 'Clients',
            'foreignKey' => 'user_id'
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
//            ->scalar('username')
//            ->maxLength('username', 10, 'Tối đa 10 ký tự')
//            ->requirePresence('username', 'create')
//            ->notEmpty('username');
//
//        $validator
//            ->scalar('password')
//            ->maxLength('password', 255)
//            ->requirePresence('password', 'create')
//            ->notEmpty('password');
//
//        $validator
//            ->scalar('screen_name')
//            ->maxLength('screen_name', 128)
//            ->requirePresence('screen_name', 'create')
//            ->notEmpty('screen_name');
//
//        $validator
//            ->email('email')
//            ->requirePresence('email', 'create')
//            ->notEmpty('email');
//
//        $validator
//            ->scalar('email_access_code')
//            ->maxLength('email_access_code', 128)
//            ->requirePresence('email_access_code', 'create')
//            ->notEmpty('email_access_code');

//        $validator
//            ->integer('is_active')
//            ->requirePresence('is_active', 'create')
//            ->notEmpty('is_active');

        return $validator;
    }

    public function validationLogin(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('username')
//                ->maxLength('username', 'Tên đăng nhập')
            ->requirePresence('username', 'create')
            ->notEmpty('username', 'Vui lòng nhập tên truy cập.');
//
        $validator
            ->scalar('password')
            ->requirePresence('password', 'create')
            ->notEmpty('password', 'Vui lòng nhập mật khẩu.');
//
//        $validator
//            ->scalar('screen_name')
//            ->maxLength('screen_name', 128)
//            ->requirePresence('screen_name', 'create')
//            ->notEmpty('screen_name');
//
//        $validator
//            ->email('email')
//            ->requirePresence('email', 'create')
//            ->notEmpty('email');
//
//        $validator
//            ->scalar('email_access_code')
//            ->maxLength('email_access_code', 128)
//            ->requirePresence('email_access_code', 'create')
//            ->notEmpty('email_access_code');

//        $validator
//            ->integer('is_active')
//            ->requirePresence('is_active', 'create')
//            ->notEmpty('is_active');

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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['role_id'], 'Roles'));
//        $rules->add($rules->existsIn(['hotel_id'],'Hotels'));

        return $rules;
    }
}
