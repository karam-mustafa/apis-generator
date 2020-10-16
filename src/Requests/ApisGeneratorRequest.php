<?php


namespace KMLaravel\ApiGenerator\Requests;


use Illuminate\Console\GeneratorCommand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use KMLaravel\ApiGenerator\Facade\KMGeneratorCommandFacade;

class ApisGeneratorRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public
    function authorize()
    {
        return config('apis_generator.request_auth');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public
    function rules()

    {

        return [
            "title" => "required|string|".Rule::notIn($this->getReservedNames()),
            "basic_options" => "required",
            "advanced_options" => "sometimes",
            "column" => "required|array",
            "column.*.type" => "required|".Rule::notIn($this->getReservedNames()),
        ];
    }
    function getReservedNames()
    {
       return KMGeneratorCommandFacade::getReservedNames();
    }
}
