<?php namespace App\Http\Controllers;

use App\Repositories\ConfigRepository;
use Input;

class ApiController extends Controller {

    public function getListOfSourcesThatMatch()
    {
        $term = Input::get('term');

        $matches = [];
        $sources = ConfigRepository::getByType('source');
        foreach($sources as $name => $value)
        {
            if(strpos(strtolower($name), strtolower($term)) !== false)
            {
                $matches[] = $value;
            }
        }

        return response()->json($matches);
    }
}