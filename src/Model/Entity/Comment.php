<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Comment Entity
 *
 * @property int $id
 * @property int $parent_id
 * @property int $user_id
 * @property int $object_type
 * @property int $object_id
 * @property string $content
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ParentComment $parent_comment
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Object $object
 * @property \App\Model\Entity\ChildComment[] $child_comments
 */
class Comment extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
