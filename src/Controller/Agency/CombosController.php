<?php
namespace App\Controller\Agency;

use App\Controller\AppController;

/**
 * Combos Controller
 *
 * @property \App\Model\Table\CombosTable $Combos
 *
 * @method \App\Model\Entity\Combo[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CombosController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Departures', 'Destinations']
        ];
        $combos = $this->paginate($this->Combos);

        $this->set(compact('combos'));
    }

    /**
     * View method
     *
     * @param string|null $id Combo id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $combo = $this->Combos->get($id, [
            'contain' => ['Departures', 'Destinations', 'Rooms', 'Bookings']
        ]);

        $this->set('combo', $combo);
    }
    
    public function isAuthorized($user) {
        // All registered users can add articles
        // Admin can access every action
        if (isset($user['role_id']) && ($user['role_id'] === 1 || $user['role_id'] === 3)) {
            return true;
        }

        $this->redirect(array('controller' => 'users', 'action' => 'login', 'prefix' => 'agency'));
        return parent::isAuthorized($user);
    }
}
