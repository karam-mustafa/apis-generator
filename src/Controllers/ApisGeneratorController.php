<?php

namespace KMLaravel\ApiGenerator\Controllers;

use App\Http\Controllers\Controller;
use KMLaravel\ApiGenerator\Services\GeneratorService;
use KMLaravel\ApiGenerator\Requests\ApisGeneratorRequest;

class apisGeneratorController extends Controller
{
    protected $generatorFactory;

    public function __construct()
    {
        $this->generatorFactory = new GeneratorService();
    }

    public function create(ApisGeneratorRequest $request)
    {
        $this->generatorFactory->initialRequest($request)->generateApi();
        session()->flash('alert_type' , 'success');
        session()->flash('success' , 'create new api successfully');
        return redirect()->route('apisGenerator.index');
    }
}
