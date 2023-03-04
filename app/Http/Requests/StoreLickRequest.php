<?php

namespace App\Http\Requests;

use App\Rules\MusicXML;
use Illuminate\Foundation\Http\FormRequest;

class StoreLickRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Lick::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'tempo' => ['required', 'numeric', 'gt:0'],
            'audio' => [
                'required_if:transcription,null',
                'nullable',
                'mimes:mp3,mp4,m4a,aac,oga,wav,wma,webm',
                'max:512000',
                // TODO: add max size validation to prevent running into mysql errors
            ],
            'transcription' => [
                'required_if:audio,null',
                'nullable',
                // new MusicXML(),
            ],
        ];
    }
}
