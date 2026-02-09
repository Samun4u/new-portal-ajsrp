<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileManager extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'file_type',
        'file_role',
        'storage_type',
        'original_name',
        'file_name',
        'user_id',
        'path',
        'extension',
        'size',
        'external_link',
    ];

    public function upload($to, $file, $name = NULL, $id = NULL, $fileRole = NULL)
    {
        try {
            // Validate file
            if (!$file || !$file->isValid()) {
                Log::error('FileManager upload: Invalid file provided');
                return NULL;
            }

            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $size = $file->getSize();

            if ($name == '') {
                $file_name = rand(000, 999) . time() . '.' . $extension;
            } else {
                $file_name = $name . '-' . time() . '.' . $extension;
            }
            $file_name = str_replace(' ', '_', $file_name);

            $storageDriver = config('app.STORAGE_DRIVER', 'public');
            $uploadPath = 'uploads/' . $to . '/' . $file_name;

            $disk = Storage::disk($storageDriver);

            // Ensure directory exists (only for local/public disks)
            if (in_array($storageDriver, ['local', 'public'])) {
                $directory = dirname($uploadPath);
                try {
                    if (!$disk->exists($directory)) {
                        $disk->makeDirectory($directory, 0755, true);
                    }
                } catch (\Exception $dirException) {
                    Log::warning('FileManager upload: Could not create directory, but continuing: ' . $dirException->getMessage());
                }
            }

            // Get file contents
            $filePath = $file->getRealPath();
            if ($filePath === false) {
                // Fallback: read from uploaded file stream
                $fileContents = $file->get();
            } else {
                $fileContents = file_get_contents($filePath);
            }

            if ($fileContents === false || $fileContents === null) {
                Log::error('FileManager upload: Failed to read file contents');
                return NULL;
            }

            // Upload file
            $uploaded = $disk->put($uploadPath, $fileContents);

            if (!$uploaded) {
                Log::error('FileManager upload: Storage::put() returned false');
                return NULL;
            }

            $fileManager = (is_null($id)) ? new self() : self::find($id);
            $fileManager = is_null($fileManager) ? new self() : $fileManager;
            $fileManager->file_type = $file->getMimeType();

            // Task 28: Set file role based on category or provided role
            if ($fileRole) {
                $fileManager->file_role = $fileRole;
            } else {
                $fileManager->file_role = $this->mapCategoryToRole($to);
            }

            $fileManager->storage_type = config('filesystems.default');
            $fileManager->original_name = $originalName;
            $fileManager->file_name = $file_name;
            $fileManager->user_id = auth()->id();
            $fileManager->path = 'uploads/' . $to . '/' . $file_name;
            $fileManager->extension = $extension;
            $fileManager->size = $size;
            $fileManager->save();
            if (config('app.STORAGE_DRIVER') == 'public') {
                if (!env('IS_SYMLINK_SUPPORT', true)) {
                    $this->copyFolder(storage_path('app/public'), public_path() . "/storage/");
                }
            }
            return $fileManager;

        } catch (\Exception $e) {
            Log::error('FileManager upload error: ' . $e->getMessage());
            Log::error('FileManager upload error trace: ' . $e->getTraceAsString());
            Log::error('FileManager upload details - to: ' . $to . ', original_name: ' . ($file->getClientOriginalName() ?? 'N/A'));
            return NULL;
        }
    }
    function copyFolder($source, $destination)
    {
        if (is_dir($source)) {
            if (!is_dir($destination)) {
                mkdir($destination, 0755, true); // Create the destination directory if it doesn't exist
            }

            $dir = opendir($source);

            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    $src = $source . '/' . $file;
                    $dest = $destination . '/' . $file;

                    if (is_dir($src)) {
                        // If it's a directory, recursively call the function
                        $this->copyFolder($src, $dest);
                    } else {
                        // If it's a file, use copy() to copy it
                        copy($src, $dest);
                    }
                }
            }

            closedir($dir);
        } else {
            copy($source, $destination);
        }
    }

    //    public function removeFile($id)
//    {
//        self::find($id)->delete();
//    }

    public function removeFile()
    {
        if (Storage::disk(config('app.STORAGE_DRIVER'))->exists($this->path)) {
            Storage::disk(config('app.STORAGE_DRIVER'))->delete($this->path);
            return 100;
        }
        return 200;
    }

    /**
     * Map upload category to file role
     * Task 28: File role management
     */
    private function mapCategoryToRole($category)
    {
        $roleMap = [
            'Proof' => 'proof_version',
            'Galley' => 'galley_version',
            'Certificate' => 'certificate',
            'Revision' => 'revision',
            'Order' => 'final_manuscript', // Original submission
            'Service' => 'final_manuscript', // Original submission
        ];

        return $roleMap[$category] ?? 'other';
    }

    /**
     * Scope to filter files by role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('file_role', $role);
    }




}
