<?php

namespace Xbigdaddyx\Companion\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasProfilePhoto
{
    /**
     * Update the user's profile photo.
     */
    public function updateProfilePhoto(UploadedFile $photo, $storagePath = 'profile-photos'): void
    {
        tap($this->avatar_url, function ($previous) use ($photo, $storagePath) {
            $this->forceFill([
                'avatar_url' => $photo->storePublicly(
                    $storagePath,
                    ['disk' => $this->profilePhotoDisk()]
                ),
            ])->save();

            if ($previous) {
                Storage::disk($this->profilePhotoDisk())->delete($previous);
            }
        });
    }

    /**
     * Delete the user's profile photo.
     */
    public function deleteProfilePhoto(): void
    {
        if (!Features::managesProfilePhotos() || $this->avatar_url === null) {
            return;
        }

        Storage::disk($this->profilePhotoDisk())->delete($this->avatar_url);

        $this->forceFill([
            'avatar_url' => null,
        ])->save();
    }

    /**
     * Get the URL to the user's profile photo.
     */
    // public function avatarUrl(): Attribute
    // {
    //     return Attribute::get(function () {
    //         return $this->avatar_url
    //             ? Storage::disk($this->profilePhotoDisk())->url($this->avatar_url)
    //             : $this->defaultProfilePhotoUrl();
    //     });
    // }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     */
    protected function defaultAvatarUrl(): string
    {
        $name = trim(collect(explode(' ', $this->name))->map(static function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return sprintf('https://ui-avatars.com/api/?name=%s&color=FFFFFF&background=111827', urlencode($name));
    }

    /**
     * Get the disk that profile photos should be stored on.
     */
    protected function profilePhotoDisk(): string
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : config('filament-companies.profile_photo_disk', 'public');
    }
}
