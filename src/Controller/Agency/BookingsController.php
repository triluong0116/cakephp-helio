<?php
namespace App\Controller\Agency;

use App\Controller\AppController;

/**
 * Bookings Controller
 *
 * @property \App\Model\Table\BookingsTable $Bookings
 *
 * @method \App\Model\Entity\Booking[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BookingsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Combos']
        ];
        $bookings = $this->paginate($this->Bookings);

        if($this->request->is('get') && $this->request->getQuery('search')){
            $data = trim($this->request->getQuery('search'));
            $list_object_bookings = $this->Bookings->find()->where([
                'OR' => [
                    'Bookings.full_name LIKE' => '%'.$data.'%',
                ]
            ]);
         
            $number = $list_object_bookings->count();
            $bookings = $this->paginate($list_object_bookings);
            $this->set(compact('bookings', 'number', 'data'));
            $this->render('search');
        } else $this->set(compact('bookings'));
    }

    /**
     * View method
     *
     * @param string|null $id Booking id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $booking = $this->Bookings->get($id, [
            'contain' => ['Users', 'Combos']
        ]);

        $this->set('booking', $booking);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $booking = $this->Bookings->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['user_id'] = $this->Auth->user('id');
            $booking = $this->Bookings->patchEntity($booking, $data);
            if ($this->Bookings->save($booking)) {
                $this->Flash->success(__('The booking has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The booking could not be saved. Please, try again.'));
        }
        $users = $this->Bookings->Users->find('list', ['limit' => 200]);
        $combos = $this->Bookings->Combos->find('list', ['limit' => 200]);
        $this->set(compact('booking', 'users', 'combos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Booking id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $booking = $this->Bookings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $booking = $this->Bookings->patchEntity($booking, $data);
            if ($this->Bookings->save($booking)) {
                $this->Flash->success(__('The booking has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The booking could not be saved. Please, try again.'));
        }
        $users = $this->Bookings->Users->find('list', ['limit' => 200]);
        $combos = $this->Bookings->Combos->find('list', ['limit' => 200]);
        $this->set(compact('booking', 'users', 'combos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Booking id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $booking = $this->Bookings->get($id);
        if ($this->Bookings->delete($booking)) {
            $this->Flash->success(__('The booking has been deleted.'));
        } else {
            $this->Flash->error(__('The booking could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
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
