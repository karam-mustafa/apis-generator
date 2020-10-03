<?php


namespace KMLaravel\ApiGenerator\Requests;


use Illuminate\Foundation\Http\FormRequest;

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
            "title" => "required|string",
            "basic_options" => "required",
            "advanced_options" => "sometimes",
            "column" => "required",
            "column.*.type" => "required",
        ];
    }
}
