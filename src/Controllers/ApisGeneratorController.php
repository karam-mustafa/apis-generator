<?php

namespace KMLaravel\ApiGenerator\Controllers;

use App\Http\Controllers\Controller;
use KMLaravel\ApiGenerator\Services\GeneratorService;
use KMLaravel\ApiGenerator\Requests\ApisGeneratorRequest;

class ApisGeneratorController extends Controller
{
    protected $generatorService;

    public function __construct()
    {
        $this->generatorService = new GeneratorService();
    }

    public function create(ApisGeneratorRequest $request)
    {
        $this->generatorService->initialRequest($request)->generateApi();
        return redirect()->route('apisGenerator.index')->with('session_success' , 'create new api successfully');
    }
}
