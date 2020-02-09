<?php

namespace App\Http\Requests\Orders;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('order'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_name' => 'max:255',
            'client_phone' => 'max:255',
            'client_address' => 'max:255'
        ];
    }
}
