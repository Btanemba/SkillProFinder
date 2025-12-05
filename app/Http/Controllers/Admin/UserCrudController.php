<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use League\ISO3166\ISO3166;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
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
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
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

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequest::class);
        
      
         CRUD::field('first_name')->label('First Name')->type('text')->tab('General Info')->wrapper(['class' => 'form-group col-md-6']);
        CRUD::field('last_name')->label('Last Name')->type('text')->tab('General Info')->wrapper(['class' => 'form-group col-md-6']);
       
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
            'wrapper' => [
                'class' => 'form-group col-md-6',
            ],
        ]);
        
        // User Information - Right Side
       
        CRUD::field('email')->label('Email')->type('email')->tab('General Info')->attributes(['readonly' => 'readonly', 'style' => 'background-color: #e9ecef;'])->wrapper(['class' => 'form-group col-md-12']);
        
        // CRUD::field('password')->label('Password')->type('password')->tab('General Info');
          // Field for Photo - Left Side (3 columns)
    
        // Person Information
        CRUD::field('person.country')
            ->label('Country Of Residence')
            ->tab('Address Info')
            ->type('select_from_array')
            ->options(collect([
                'GB' => 'United Kingdom',
                'separator' => '──────────',
            ])->merge(
                collect((new \League\ISO3166\ISO3166())->all())
                    ->pluck('name', 'alpha2')
                    ->sort()
            )->toArray())
            ->wrapper(['class' => 'form-group col-md-6'])
            ->attributes(['data-separator-key' => 'separator', 'id' => 'country-field']);
        
        CRUD::field('person.city')
            ->label('City of Residence')
            ->tab('Address Info')
            ->type('select_from_array')
            ->options([])
            ->wrapper(['class' => 'form-group col-md-6'])
            ->attributes(['id' => 'city-field']);
        
        CRUD::field('person.home_address')->label('Home Address')->type('text')->tab('Address Info')->wrapper(['class' => 'form-group col-md-6']);
        CRUD::field('person.house_number')->label('House Number')->type('text')->tab('Address Info')->wrapper(['class' => 'form-group col-md-6']);
        CRUD::field('person.postal_code')->label('Postal Code')->type('text')->tab('Address Info')->wrapper(['class' => 'form-group col-md-6']);
        CRUD::field('person.postbox')->label('Post Box')->type('text')->tab('Address Info')->wrapper(['class' => 'form-group col-md-6']);
        CRUD::field('person.phone')->label('Phone')->type('text')->tab('Address Info')->wrapper(['class' => 'form-group col-md-6']);

        // Add city loader script
        $savedCity = null;
        if ($this->crud->getCurrentOperation() == 'update') {
            $entry = $this->crud->getCurrentEntry();
            if ($entry && $entry->person) {
                $savedCity = $entry->person->city;
            }
        }
        
        CRUD::field('city_loader_script')->type('custom_html')->value('
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
                            cityField.empty().append("<option value=\'\'>Loading cities...</option>");
                            
                            $.ajax({
                                url: "/admin/user/cities/" + countryCode,
                                method: "GET",
                                dataType: "json",
                                success: function(cities) {
                                    cityField.empty();
                                    cityField.append("<option value=\'\'>Select a city</option>");
                                    
                                    $.each(cities, function(key, value) {
                                        var option = $("<option></option>")
                                            .attr("value", key)
                                            .text(value);
                                        if (selectedCity && key === selectedCity) {
                                            option.prop("selected", true);
                                        }
                                        cityField.append(option);
                                    });
                                    
                                    cityField.prop("disabled", false);
                                    
                                    if (selectedCity) {
                                        setTimeout(function() {
                                            cityField.val(selectedCity);
                                        }, 100);
                                    }
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
                    
                    // Initial load
                    var initialCountry = countryField.val();
                    if (initialCountry && initialCountry !== "" && initialCountry !== "separator") {
                        loadCities(initialCountry, savedCity);
                    } else {
                        cityWrapper.hide();
                    }
                }, 1000);
            }
            initCityLoader();
            </script>
        ')->tab("Address Info");

        // Add photo preview JavaScript
        CRUD::field('photo_preview_script')->type('custom_html')->value('
            <script>
            function initPhotoPreview() {
                if (typeof $ === "undefined") {
                    setTimeout(initPhotoPreview, 100);
                    return;
                }
                
                setTimeout(function() {
                    var fileInput = $("input[name=\'person[profile_picture]\']");
                    var preview = $("#photo-preview");
                    
                    console.log("Photo preview initialized");
                    console.log("File input found:", fileInput.length);
                    console.log("Preview div found:", preview.length);
                    
                    if (fileInput.length && preview.length) {
                        fileInput.on("change", function(e) {
                            console.log("File input changed");
                            var file = e.target.files[0];
                            if (file) {
                                console.log("File selected:", file.name);
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    console.log("File loaded, updating preview");
                                    preview.html(\'<img src="\' + e.target.result + \'" style="max-width: 200px; max-height: 200px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">\');
                                }
                                reader.readAsDataURL(file);
                            }
                        });
                    } else {
                        console.log("Retrying photo preview initialization...");
                        setTimeout(initPhotoPreview, 500);
                    }
                }, 1000);
            }
            initPhotoPreview();
            </script>
        ')->tab('General Info');
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

    /**
     * Get cities for a specific country
     */
    public function getCities($countryCode)
    {
        $cities = $this->getCitiesByCountry($countryCode);
        return response()->json($cities);
    }

    /**
     * Return cities based on country code using external API
     */
    private function getCitiesByCountry($countryCode)
    {
        switch ($countryCode) {
            case 'GB':
                return $this->getUKCities();
            default:
                return [];
        }
    }

    /**
     * Get all UK cities from JSON file
     */
    private function getUKCities()
    {
        $jsonPath = storage_path('app/uk-cities.json');
        
        if (file_exists($jsonPath)) {
            $cities = json_decode(file_get_contents($jsonPath), true);
            $cityOptions = [];
            foreach ($cities as $city) {
                $cityOptions[$city] = $city;
            }
            return $cityOptions;
        }
        
        // Fallback to static list if file not found
        return $this->getStaticUKCities();
    }

    /**
     * Static UK cities as fallback
     */
    private function getStaticUKCities()
    {
        // Load from JSON file
        $jsonPath = storage_path('app/uk-cities.json');
        
        if (file_exists($jsonPath)) {
            $jsonContent = file_get_contents($jsonPath);
            $cities = json_decode($jsonContent, true);
            
            if (is_array($cities) && count($cities) > 0) {
                $cityOptions = [];
                foreach ($cities as $city) {
                    $cityOptions[$city] = $city;
                }
                return $cityOptions;
            }
        }
        
        // If JSON loading fails, return empty array
        return [];
    }
}
