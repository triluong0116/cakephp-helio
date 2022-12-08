<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RequestVoucher Entity
 *
 * @property int $id
 * @property string $title
 * @property string $time
 * @property string $price
 * @property string $full_name
 * @property string $phone
 * @property string $email
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class RequestVoucher extends Entity
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
        'title' => true,
        'time' => true,
        'price' => true,
        'full_name' => true,
        'phone' => true,
        'email' => true,
        'created' => true,
        'modified' => true
    ];
}
