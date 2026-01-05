<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookingRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BookingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BookingCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
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
        CRUD::setModel(\App\Models\Booking::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/booking');
        CRUD::setEntityNameStrings('booking', 'bookings');
        
            $this->crud->with([
        'service.person.user',
        'client',
    ]);
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id')->label('ID');
        CRUD::column('client_id')->label('Client')->type('select')->entity('client')->attribute('first_name')->model('App\Models\User');
        CRUD::column('service_id')->label('Service')->type('select')->entity('service')->attribute('name')->model('App\Models\Service');
        
        // Show provider name
        CRUD::addColumn([
            'name' => 'provider_name',
            'label' => 'Provider',
            'type' => 'custom_html',
            'value' => function($entry) {
                $provider = $entry->service->person->user ?? null;
                if ($provider) {
                    return $provider->first_name . ' ' . $provider->last_name;
                }
                return 'N/A';
            }
        ]);
        
        CRUD::column('booking_date')->label('Date')->type('date');
        CRUD::column('booking_time')->label('Time');
        CRUD::column('status')->label('Status')->type('select_from_array')->options([
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ]);
        CRUD::column('service_price')->label('Service Price')->type('number')->prefix('£');
        CRUD::column('platform_fee')->label('Platform Fee')->type('number')->prefix('£');
        CRUD::column('total_price')->label('Total Price')->type('number')->prefix('£');
        CRUD::column('created_at')->label('Booked At')->type('datetime');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(BookingRequest::class);
        
        CRUD::field('client_id')->label('Client')->type('select')->entity('client')->attribute('first_name')->model('App\Models\User');
        CRUD::field('service_id')->label('Service')->type('select')->entity('service')->attribute('name')->model('App\Models\Service');
        CRUD::field('booking_date')->label('Booking Date')->type('date');
        CRUD::field('booking_time')->label('Booking Time')->type('time');
        CRUD::field('message')->label('Message')->type('textarea');
        CRUD::field('status')->label('Status')->type('select_from_array')->options([
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ])->default('pending');
        CRUD::field('service_price')->label('Service Price')->type('number')->prefix('£');
        CRUD::field('platform_fee')->label('Platform Fee')->type('number')->prefix('£')->default(5.00);
        CRUD::field('total_price')->label('Total Price')->type('number')->prefix('£');
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
