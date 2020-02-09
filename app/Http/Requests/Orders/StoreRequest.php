<?php

namespace App\Http\Requests\Orders;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_name' => 'required|max:255',
            'client_phone' => 'required|max:255',
            'client_address' => 'required|max:255'
        ];
    }
}
