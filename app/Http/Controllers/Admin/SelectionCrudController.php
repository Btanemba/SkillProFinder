<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SelectionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SelectionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SelectionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Selection::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/selection');
        CRUD::setEntityNameStrings('selection', 'selections');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('table');
        CRUD::column('field');
        CRUD::column('code');
        CRUD::column('name');
        CRUD::column('created_by')->type('select')->entity('creator')->model('App\\Models\\User')->attribute('name');
       
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(SelectionRequest::class);
        
        CRUD::field('table')->type('text')->label('Table')->wrapper(['class' => 'form-group col-md-6']);
        CRUD::field('field')->type('text')->label('Field')->wrapper(['class' => 'form-group col-md-6']);
        CRUD::field('code')->type('text')->label('Code')->wrapper(['class' => 'form-group col-md-6']);
        CRUD::field('name')->type('text')->label('Name')->wrapper(['class' => 'form-group col-md-6']);
        // CRUD::field('created_by')->type('select')->entity('creator')->model('App\\Models\\User')->attribute('name')->label('Created By');
        // CRUD::field('updated_by')->type('select')->entity('updater')->model('App\\Models\\User')->attribute('name')->label('Updated By');
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
