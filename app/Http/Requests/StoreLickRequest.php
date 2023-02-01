<?php

namespace App\Http\Requests;

use App\Models\Lick;
use Illuminate\Foundation\Http\FormRequest;

class StoreLickRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Lick::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required'],
            'tempo' => ['required'],
            'audio' => ['required', 'nullable', 'mimes:mp3,m4a,aac,oga,wav,wma', 'max:10240'],
            'transcription' => ['required'],
        ];
    }
}
