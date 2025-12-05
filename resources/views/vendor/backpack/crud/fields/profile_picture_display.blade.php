@php
    $photoUrl = null;
    
    if (isset($entry) && $entry->person && $entry->person->profile_picture) {
        $photoUrl = asset('storage/' . $entry->person->profile_picture);
    }
@endphp

<div id="photo-preview" style="margin-top: 10px;">
    @if($photoUrl)
        <img src="{{ $photoUrl }}" style="max-width: 200px; max-height: 200px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    @endif
</div>
