<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Review Entity
 *
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string $slug
 * @property string $caption
 * @property string $content
 * @property string $thumbnail
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Category $category
 */
class Review extends Entity
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
