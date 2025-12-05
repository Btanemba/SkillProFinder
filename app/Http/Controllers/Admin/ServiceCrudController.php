<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ServiceRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ServiceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ServiceCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Service::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/service');
        CRUD::setEntityNameStrings('service', 'services');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // Filter services by logged-in provider
        if (backpack_user()->user_type === 'provider') {
            $person = \App\Models\Person::where('user_id', backpack_user()->id)->first();
            if ($person) {
                CRUD::addClause('where', 'person_id', $person->id);
            }
        }
    
        // Add thumbnail column for sample pictures
        CRUD::addColumn([
            'name' => 'thumbnail',
            'label' => 'Image',
            'type' => 'custom_html',
            'value' => function($entry) {
                if (empty($entry->sample_pictures)) {
                    return '<div style="width: 80px; height: 60px; background: #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;"><i class="la la-image" style="font-size: 24px; color: #94a3b8;"></i></div>';
                }
                
                $images = $entry->sample_pictures;
                $count = count($images);
                
                if ($count === 1) {
                    $url = asset('storage/' . $images[0]);
                    return '<img src="'.$url.'" style="width: 80px; height: 60px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" />';
                }
                
                $id = 'gallery_' . $entry->id;
                $imagesUrls = array_map(function($img) { return asset('storage/' . $img); }, $images);
                $firstUrl = $imagesUrls[0];
                
                $html = '<div id="'.$id.'" data-current="0" style="position: relative; width: 80px; height: 60px;">';
                $html .= '<img id="'.$id.'_img" src="'.htmlspecialchars($firstUrl).'" style="width: 80px; height: 60px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: block;" />';
                
                // Hidden data
                foreach ($imagesUrls as $index => $url) {
                    $html .= '<input type="hidden" id="'.$id.'_url_'.$index.'" value="'.htmlspecialchars($url).'" />';
                }
                
                // Left Arrow
                $html .= '<button type="button" onclick="(function(){
                    var container = document.getElementById(\''.$id.'\');
                    var current = parseInt(container.getAttribute(\'data-current\'));
                    var total = '.$count.';
                    current = (current - 1 + total) % total;
                    container.setAttribute(\'data-current\', current);
                    document.getElementById(\''.$id.'_img\').src = document.getElementById(\''.$id.'_url_\' + current).value;
                    document.getElementById(\''.$id.'_current\').textContent = (current + 1);
                    event.stopPropagation();
                })()" style="position: absolute; left: 2px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.6); color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0; font-size: 12px; line-height: 1; z-index: 10;">‹</button>';
                
                // Right Arrow
                $html .= '<button type="button" onclick="(function(){
                    var container = document.getElementById(\''.$id.'\');
                    var current = parseInt(container.getAttribute(\'data-current\'));
                    var total = '.$count.';
                    current = (current + 1) % total;
                    container.setAttribute(\'data-current\', current);
                    document.getElementById(\''.$id.'_img\').src = document.getElementById(\''.$id.'_url_\' + current).value;
                    document.getElementById(\''.$id.'_current\').textContent = (current + 1);
                    event.stopPropagation();
                })()" style="position: absolute; right: 2px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.6); color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0; font-size: 12px; line-height: 1; z-index: 10;">›</button>';
                
                // Counter Badge
                $html .= '<div style="position: absolute; bottom: 2px; right: 2px; background: rgba(0,0,0,0.7); color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; pointer-events: none; z-index: 10;"><span id="'.$id.'_current">1</span>/'.$count.'</div>';
                $html .= '</div>';
                
                return $html;
            },
            'searchLogic' => false,
            'orderable' => false,
        ]);
        
        CRUD::column('name')
            ->label('Skill')
            ->type('text')
            ->wrapper([
                'element' => 'div',
                'class' => 'font-weight-bold'
            ]);
            
        CRUD::column('skill_type')
            ->label('Type')
            ->type('text');
            
        CRUD::addColumn([
            'name' => 'experience_badge',
            'label' => 'Experience',
            'type' => 'custom_html',
            'value' => function($entry) {
                $years = $entry->years_of_experience;
                $color = $years >= 5 ? '#10b981' : ($years >= 2 ? '#3b82f6' : '#6366f1');
                return '<span style="display: inline-block; padding: 4px 12px; background: '.$color.'; color: white; border-radius: 16px; font-size: 13px; font-weight: 600;">'.$years.' '.($years == 1 ? 'year' : 'years').'</span>';
            },
            'searchLogic' => false,
        ]);
        
        CRUD::addColumn([
            'name' => 'skill_level_badge',
            'label' => 'Level',
            'type' => 'custom_html',
            'value' => function($entry) {
                $colors = [
                    'beginner' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                    'intermediate' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                    'advanced' => ['bg' => '#dcfce7', 'text' => '#166534'],
                    'expert' => ['bg' => '#fce7f3', 'text' => '#9f1239']
                ];
                $level = $entry->skill_level;
                $color = $colors[$level] ?? $colors['beginner'];
                return '<span style="display: inline-block; padding: 4px 12px; background: '.$color['bg'].'; color: '.$color['text'].'; border-radius: 16px; font-size: 13px; font-weight: 600; text-transform: capitalize;">'.$level.'</span>';
            },
            'searchLogic' => false,
        ]);
        
        CRUD::column('price')
            ->type('number')
            ->prefix('$')
            ->wrapper([
                'element' => 'span',
                'class' => 'font-weight-bold',
                'style' => 'color: #059669; font-size: 16px;'
            ]);
        
        CRUD::column('created_at')
            ->label('Added')
            ->type('date')
            ->format('M d, Y');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ServiceRequest::class);

        // Auto-fill person_id from logged-in user
        $user = backpack_user();
        $person = \App\Models\Person::where('user_id', $user->id)->first();
        
        CRUD::field('person_id')
            ->type('hidden')
            ->value($person ? $person->id : null);
        
        $serviceNames = \App\Models\Selection::where('table', 'services')
            ->where('field', 'skills')
            ->pluck('code', 'name')
            ->toArray();
        
        CRUD::field('name')
            ->label('Skill Name')
            ->type('select_from_array')
            ->allows_null(true)
            ->wrapper(['class' => 'form-group col-md-6'])
            ->options($serviceNames);
        
        CRUD::field('skill_type')
            ->label('Skill Type')
            ->type('text')
            ->wrapper(['class' => 'form-group col-md-6']);
        
        CRUD::field('years_of_experience')
            ->label('Years of Experience')
            ->type('number')
               ->wrapper(['class' => 'form-group col-md-6'])
            ->attributes(['min' => 0]);

               CRUD::field('description')
            ->label('Description')
            ->type('textarea');

         CRUD::field('skill_level')
            ->label('Skill Level')
            ->type('select_from_array')
            ->options([
                'beginner' => 'Beginner',
                'intermediate' => 'Intermediate',
                'advanced' => 'Advanced',
                'expert' => 'Expert'
            ])
            ->wrapper(['class' => 'form-group col-md-6']);
        
        CRUD::field('price')
            ->label('Price')
            ->type('number')
            ->attributes(['step' => '0.01', 'min' => 0])
             ->wrapper(['class' => 'form-group col-md-6'])
            ->prefix('$');       
        
        CRUD::field('certificate')
            ->label('Certificate')
            ->type('upload')
            ->upload(true);
        
        CRUD::field('sample_pictures')
            ->label('Sample Pictures')
            ->type('upload_multiple')
            ->upload(true);
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
     * Define what happens when the Show operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-show
     * @return void
     */
    protected function setupShowOperation()
    {
        CRUD::column('name')->label('Skill Name');
        CRUD::column('skill_type')->label('Skill Type');
        CRUD::column('years_of_experience')->label('Years of Experience');
        CRUD::column('price')->type('number')->prefix('$');
        CRUD::column('description')->type('textarea');
        CRUD::column('skill_level')->label('Skill Level');
        CRUD::column('certificate')->type('image')->disk('public');
        
        // Custom display for multiple images
        CRUD::addColumn([
            'name' => 'sample_pictures',
            'label' => 'Sample Pictures',
            'type' => 'custom_html',
            'value' => function($entry) {
                if (empty($entry->sample_pictures)) {
                    return '-';
                }
                $html = '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
                foreach ($entry->sample_pictures as $image) {
                    $url = asset('storage/' . $image);
                    $html .= '<a href="'.$url.'" target="_blank"><img src="'.$url.'" style="height: 150px; width: 150px; object-fit: cover; border-radius: 8px;" /></a>';
                }
                $html .= '</div>';
                return $html;
            }
        ]);
        
        CRUD::column('created_at')->label('Created');
        CRUD::column('updated_at')->label('Updated');
    }
}
