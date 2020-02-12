<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileProject extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'path'
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public static function store(UploadedFile $uploadedFile)
    {
        if($uploadedFile instanceof UploadedFile) {
            $attributes['name'] = $uploadedFile->getClientOriginalName();
            $hash = md5_file($uploadedFile->path());
            $filename = $hash . '.' . $uploadedFile->extension();
            $dir = substr($hash, 0, 3);

            $path = $uploadedFile->storeAs('project_files/' . $dir, $filename);

            return self::firstOrCreate(['path' => $path], $attributes);
        }
        throw new \TypeError('Argument must be instance of Illuminate\Http\UploadedFile');
    }

    public function delete() {
        Storage::delete($this->path);
        return parent::delete();
    }
}
