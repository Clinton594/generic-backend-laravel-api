<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $hidden = ['id'];
    protected $table = "company";

    public static function get(bool $force_clear = true): mixed
    {
        if ($force_clear) {
            Cache::clear('company');
        }
        return Cache::remember('company', config('data.cacheTime'), function () {
            $row = object([]);
            if (Schema::hasTable('company')) {
                $row =  self::find(1);
                if ($row) {
                    $row->makeHidden(['created_by', 'updated_at', 'created_at']);
                    $row->link = env("APP_LINK");
                    $row->url = env("APP_URL");
                    $images = array_map(fn ($img) => asset($img->src), isJson($row->logo_ref) ?? []);
                    $row->logo = reset($images);
                    $row->favicon = end($images);
                    $row->images = $images;
                    $row->social_media = isJson($row->social_media) ?? [];
                    unset($row->logo_ref);
                }
            }
            return $row;
        });
    }
}
