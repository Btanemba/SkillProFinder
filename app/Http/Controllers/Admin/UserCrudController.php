<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use League\ISO3166\ISO3166;

class UserCrudController extends CrudController
{
    /*
    |--------------------------------------------------------------------------
    | CRUD Operations
    |--------------------------------------------------------------------------
    */

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /*
    |--------------------------------------------------------------------------
    | SETUP
    |--------------------------------------------------------------------------
    */

    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    /*
    |--------------------------------------------------------------------------
    | LIST
    |--------------------------------------------------------------------------
    */

    protected function setupListOperation()
    {
        CRUD::column('first_name')->label('First Name');
        CRUD::column('last_name')->label('Last Name');
        CRUD::column('email')->label('Email');
        CRUD::column('person.country')->label('Country');
        CRUD::column('person.city')->label('City');
        CRUD::column('person.phone')->label('Phone');
        CRUD::column('created_at')->label('Registered At')->type('datetime');
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequest::class);

        /*
        |------------------------
        | General Info
        |------------------------
        */

        CRUD::field('first_name')
            ->label('First Name')
            ->type('text')
            ->tab('General Info')
            ->wrapper(['class' => 'form-group col-md-6']);

        CRUD::field('last_name')
            ->label('Last Name')
            ->type('text')
            ->tab('General Info')
            ->wrapper(['class' => 'form-group col-md-6']);

        CRUD::addField([
            'name'  => 'person.profile_picture',
            'label' => 'Display Photo',
            'type'  => 'upload',
            'tab'   => 'General Info',
            'upload' => true,
            'disk'  => 'public',
            'wrapper' => ['class' => 'form-group col-md-6'],
        ]);

        CRUD::addField([
            'name' => 'person.profile_picture_display',
            'type' => 'view',
            'view' => 'vendor.backpack.crud.fields.profile_picture_display',
            'label' => ' ',
            'tab' => 'General Info',
            'wrapper' => ['class' => 'form-group col-md-6'],
        ]);

        CRUD::field('email')
            ->label('Email')
            ->type('email')
            ->tab('General Info')
            ->attributes(['readonly' => 'readonly', 'style' => 'background-color: #e9ecef;'])
            ->wrapper(['class' => 'form-group col-md-12']);

        /*
        |------------------------
        | Address Info
        |------------------------
        */
        
        CRUD::field('person.country')
            ->label('Country Of Residence')
            ->tab('Address Info')
            ->type('select_from_array')
            ->options(
                collect(['GB' => 'United Kingdom', 'separator' => '──────────'])
                ->merge(
                    collect((new ISO3166())->all())
                        ->pluck('name', 'alpha2')
                        ->sort()
                )
                ->toArray()
            )
            ->wrapper(['class' => 'form-group col-md-6'])
            ->attributes(['data-separator-key' => 'separator', 'id' => 'country-field']);

        CRUD::field('person.city')
            ->label('City of Residence')
            ->tab('Address Info')
            ->type('select_from_array')
            ->options([])
            ->wrapper(['class' => 'form-group col-md-6'])
            ->attributes(['id' => 'city-field']);

        CRUD::field('person.home_address')
            ->label('Home Address')
            ->type('text')
            ->tab('Address Info')
            ->wrapper(['class' => 'form-group col-md-6']);

        CRUD::field('person.house_number')
            ->label('House Number')
            ->type('text')
            ->tab('Address Info')
            ->wrapper(['class' => 'form-group col-md-6']);

        CRUD::field('person.postal_code')
            ->label('Postal Code')
            ->type('text')
            ->tab('Address Info')
            ->wrapper(['class' => 'form-group col-md-6']);

        CRUD::field('person.postbox')
            ->label('Post Box')
            ->type('text')
            ->tab('Address Info')
            ->wrapper(['class' => 'form-group col-md-6']);

        CRUD::field('person.phone')
            ->label('Phone')
            ->type('text')
            ->tab('Address Info')
            ->wrapper(['class' => 'form-group col-md-6']);

        /*
        |------------------------
        | City loader script
        |------------------------
        */

        $savedCity = null;
        if ($this->crud->getCurrentOperation() === 'update') {
            $entry = $this->crud->getCurrentEntry();
            if ($entry && $entry->person) {
                $savedCity = $entry->person->city;
            }
        }

        CRUD::field('city_loader_script')
            ->type('custom_html')
            ->value('
                <script>
                function initCityLoader() {
                    if (typeof $ === "undefined") {
                        setTimeout(initCityLoader, 100);
                        return;
                    }
                    
                    setTimeout(function() {
                        var countryField = $("select[name=\'person[country]\']");
                        var cityField = $("select[name=\'person[city]\']");
                        var cityWrapper = cityField.closest(".form-group");
                        
                        var savedCity = ' . json_encode($savedCity) . ';
                        
                        function loadCities(countryCode, selectedCity) {
                            if (countryCode === "GB") {
                                cityWrapper.show();
                                cityField.prop("disabled", true);
                                cityField.empty().append("<option>Loading...</option>");
                                
                                $.get("/admin/user/cities/" + countryCode, function(cities) {
                                    cityField.empty().append("<option value=\'\'>Select a city</option>");
                                    $.each(cities, function(key, value) {
                                        cityField.append(
                                            $("<option>").val(key).text(value)
                                        );
                                    });
                                    cityField.prop("disabled", false);

                                    if (selectedCity) {
                                        cityField.val(selectedCity);
                                    }
                                });
                            } else {
                                cityWrapper.hide();
                                cityField.val("");
                            }
                        }
                        
                        countryField.on("change", function() {
                            var selectedCountry = $(this).val();
                            if (selectedCountry && selectedCountry !== "separator") {
                                loadCities(selectedCountry);
                            } else {
                                cityWrapper.hide();
                            }
                        });
                        
                        var initialCountry = countryField.val();
                        if (initialCountry && initialCountry !== "separator") {
                            loadCities(initialCountry, savedCity);
                        } else {
                            cityWrapper.hide();
                        }
                    }, 1000);
                }
                initCityLoader();
                </script>
            ')
            ->tab("Address Info");

        /*
        |------------------------
        | Photo preview script
        |------------------------
        */

        CRUD::field('photo_preview_script')
            ->type('custom_html')
            ->value('
                <script>
                function initPhotoPreview() {
                    if (typeof $ === "undefined") {
                        setTimeout(initPhotoPreview, 100);
                        return;
                    }
                    
                    setTimeout(function() {
                        var fileInput = $("input[name=\'person[profile_picture]\']");
                        var preview = $("#photo-preview");
                        
                        if (fileInput.length && preview.length) {
                            fileInput.on("change", function(e) {
                                var file = e.target.files[0];
                                if (file) {
                                    var reader = new FileReader();
                                    reader.onload = function(e) {
                                        preview.html(
                                            `<img src="${e.target.result}" 
                                                  style="max-width:200px; max-height:200px; border-radius:8px;">`
                                        );
                                    }
                                    reader.readAsDataURL(file);
                                }
                            });
                        } else {
                            setTimeout(initPhotoPreview, 500);
                        }
                    }, 1000);
                }
                initPhotoPreview();
                </script>
            ')
            ->tab('General Info');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX: Get Cities
    |--------------------------------------------------------------------------
    */

    public function getCities($countryCode)
    {
        return response()->json($this->getCitiesByCountry($countryCode));
    }

    private function getCitiesByCountry($countryCode)
    {
        return $countryCode === 'GB'
            ? $this->getUKCities()
            : [];
    }

    private function getUKCities()
    {
        $jsonPath = storage_path('app/uk-cities.json');

        if (file_exists($jsonPath)) {
            $cities = json_decode(file_get_contents($jsonPath), true);
            return collect($cities)->mapWithKeys(fn($c) => [$c => $c])->toArray();
        }

        return $this->getStaticUKCities();
    }

    private function getStaticUKCities()
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | OVERRIDE STORE / UPDATE (NESTED PERSON FIELDS)
    |--------------------------------------------------------------------------
    */

    public function store()
    {
        $request = $this->crud->validateRequest();
        $data = $request->all();

        $userModel = $this->crud->getModel();
        $userFillable = (new $userModel)->getFillable();
        $data = collect($data)->only($userFillable)->toArray();

        $personData = $request->input('person', []);
        $personFillable = (new \App\Models\Person)->getFillable();
        $personData = collect($personData)->only($personFillable)->toArray();
        // Preserve file upload for profile_picture
        if ($request->hasFile('person.profile_picture')) {
            $personData['profile_picture'] = $request->file('person.profile_picture');
        }

        $user = $userModel::create($data);

        if (!empty($personData)) {
            $user->person()->create($personData);
        }

        return $this->crud->performSaveAction($user->id);
    }

    public function update($id)
    {
        $request = $this->crud->validateRequest();
        $data = $request->all();

        $user = $this->crud->getModel()::findOrFail($id);
        $userFillable = $user->getFillable();
        $data = collect($data)->only($userFillable)->toArray();

        $personData = $request->input('person', []);
        $personFillable = (new \App\Models\Person)->getFillable();
        $personData = collect($personData)->only($personFillable)->toArray();
        // Preserve file upload for profile_picture
        if ($request->hasFile('person.profile_picture')) {
            $personData['profile_picture'] = $request->file('person.profile_picture');
        }

        $user->update($data);

        if (!empty($personData)) {
            $user->person
                ? $user->person->update($personData)
                : $user->person()->create($personData);
        }

        return $this->crud->performSaveAction($id);
    }
}
