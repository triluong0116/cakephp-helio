<?php
use Migrations\AbstractMigration;

class AddDeletedToHomeStays extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('home_stays');
        $table->addColumn('deleted', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->update();
    }
}
