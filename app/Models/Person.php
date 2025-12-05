<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Person extends Model
{
    use CrudTrait;

    protected $fillable = [
        'user_id',
        'profile_picture',
        'country',
        'city',
        'home_address',
        'house_number',
        'postbox',
        'postal_code',
        'phone',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Handle profile picture upload
     */
    public function setProfilePictureAttribute($value)
    {
        $attribute_name = "profile_picture";
        $disk = "public";
        $destination_path = "profile_pictures";

        // if the image was erased
        if ($value == null) {
            // delete the image from disk
            if (isset($this->{$attribute_name})) {
                Storage::disk($disk)->delete($this->{$attribute_name});
            }
            // set null in the database column
            $this->attributes[$attribute_name] = null;
            return;
        }

        // if a base64 was sent, store it in the db
        if (is_string($value) && str_starts_with($value, 'data:image'))
        {
            // 0. Make the image
            $image = \Image::make($value)->encode('jpg', 90);
            // 1. Generate a filename.
            $filename = time() . '_' . uniqid() . '.jpg';
            // 2. Store the image on disk.
            Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());
            // 3. Save the path to the database
            $this->attributes[$attribute_name] = $destination_path.'/'.$filename;
        }
        // if it's a file upload
        elseif (is_object($value) && is_a($value, 'Illuminate\Http\UploadedFile'))
        {
            // Delete the previous file if it exists
            if (isset($this->{$attribute_name})) {
                Storage::disk($disk)->delete($this->{$attribute_name});
            }

            // Generate a unique filename
            $filename = time() . '_' . uniqid() . '.' . $value->getClientOriginalExtension();
            
            // Store the file
            $value->storeAs($destination_path, $filename, $disk);
            
            // Save the path to the database
            $this->attributes[$attribute_name] = $destination_path . '/' . $filename;
        }
        // if it's a string (filename already saved)
        elseif (is_string($value)) {
            $this->attributes[$attribute_name] = $value;
        }
    }
}
