<?php

namespace App\Http\Requests\Orders;

use App\Order;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Order::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_name' => 'required|min:3|max:255',
            'client_phone' => 'required|max:255',
            'client_address' => 'required|max:255'
        ];
    }
}
