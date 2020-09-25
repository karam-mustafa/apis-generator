<?php

namespace KMLaravel\ApiGenerator\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use KMLaravel\ApiGenerator\Helpers\GeneratorFactory;

class apisGeneratorController extends Controller
{
    protected $generatorFactory;

    public function __construct()
    {
        $this->generatorFactory = new GeneratorFactory(request());
    }

    public function create()
    {
        $this->generatorFactory
            ->checkValidate()
            ->setBuildOption()
            ->setCheckIfBaseControllerExists()
            ->generateApi();
        session()->flash('alert_type' , 'success');
        session()->flash('success' , 'create new api successfully');
        return redirect()->route('apisGenerator.index');
    }
}
