<?php namespace App\Repositories;

use App\Models\Config;
use DB;

class ConfigRepository {


    public static function save($type, $name, $value)
    {
        $config = Config::where('type', '=', $type)
                        ->where('name', '=', $name)->first();

        if(is_null($config))
        {
            $config = new Config();
        }

        $config->type = $type;
        $config->name = $name;
        $config->value = $value;

        return $config->save();
    }

    public static function delete($id)
    {
        Config::find($id)->delete();
    }

    /**
     * Get all transactions
     *
     * @param $type
     * @return array []
     */
    public static function getByType($type)
    {
        $items = Config::where('type', '=', $type)->lists('name', 'value');

        return $items;
    }
}