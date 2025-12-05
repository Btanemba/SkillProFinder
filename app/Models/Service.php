<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use CrudTrait;

    protected $fillable = [
        'person_id',
        'name',
        'skill_type',
        'years_of_experience',
        'price',
        'description',
        'skill_level',
        'certificate',
        'sample_pictures',
    ];

    protected $casts = [
        'sample_pictures' => 'array',
        'price' => 'decimal:2',
    ];

    public function setCertificateAttribute($value)
    {
        $attribute_name = "certificate";
        $disk = "public";
        $destination_path = "certificates";

        // If it's already a string (existing file), don't process it
        if (is_string($value)) {
            $this->attributes[$attribute_name] = $value;
            return;
        }

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setSamplePicturesAttribute($value)
    {
        $attribute_name = "sample_pictures";
        $disk = "public";
        $destination_path = "sample_pictures";

        // If it's already an array of strings (existing files), don't process it
        if (is_array($value) && !empty($value) && is_string($value[0] ?? null)) {
            $this->attributes[$attribute_name] = json_encode($value);
            return;
        }

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
