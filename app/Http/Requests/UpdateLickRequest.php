<?php

namespace App\Http\Requests;

use App\Models\Lick;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLickRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('lick'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'audio' => ['nullable', 'mimes:mp3,m4a,aac,oga,wav,wma', 'max:10240'],
        ];
    }
}
