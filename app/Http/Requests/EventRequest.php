<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string',
                    'description' => 'required|string',
                    'date' => 'required|date',
                    'time' => 'required|date_format:H:i',
                    'location' => 'required|string',
                    'available_seats' => 'required|integer',
                    'price' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
                ];
    
            case 'PATCH': 
            case 'PUT': 
                return [
                    'name' => 'sometimes|string',
                    'description' => 'sometimes|string',
                    'date' => 'sometimes|date',
                    'time' => 'sometimes|nullable',
                    'location' => 'sometimes|string',
                    'available_seats' => 'sometimes|integer',
                    'price' => ['sometimes', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
                ];
    
            default:
                return [];
        }
    }
    

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'description.required' => 'The description field is required.',
            'date.required' => 'The date field is required.',
            'time.required' => 'The time field is required.',
            'time.date_format' => 'The time must be in the format HH:MM.',
            'location.required' => 'The location field is required.',
            'available_seats.required' => 'The available seats field is required.',
            'available_seats.integer' => 'The available seats must be an integer.',
            'price.required' => 'The price field is required.',
            'price.numeric' => 'The price must be a number.',
            'price.regex' => 'The price must be a valid decimal with up to 2 decimal places.',

        ];
    }
}
