<?php

namespace App\Http\Requests;

use App\Rules\MusicXML;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLickRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('lick'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $lick = $this->route('lick');

        // validating that lick stays with either audio, transcription or both
        $wouldRemoveAudio = !$this->hasFile('audio') &&
            $this->has('audio') &&
            $this->input('audio') === null;
        $wouldRemoveTranscription = $this->has('transcription') &&
            $this->input('transcription') === null;

        $wouldAddAudio = $this->hasFile('audio') &&
            $this->file('audio') !== null;
        $wouldAddTranscription = $this->has('transcription') &&
            $this->input('transcription') !== null;

        $wouldEndUpWithAudio = $wouldAddAudio ||
            (!!$lick->audio_file_path && !$wouldRemoveAudio);
        $wouldEndUpWithTranscription = $wouldAddTranscription ||
            (!!$lick->transcription && !$wouldRemoveTranscription);

        $wouldEndUpWithoutAudio =  $wouldRemoveAudio ||
            (!$lick->audio_file_path && !$wouldAddAudio);
        $wouldEndUpWithoutTranscription = $wouldRemoveTranscription ||
            (!$lick->transcription && !$wouldAddTranscription);

        return [
            // 'title' => ['required'],
            'tempo' => ['numeric', 'gt:0'],
            'audio' => [
                Rule::requiredIf(
                    $wouldEndUpWithoutTranscription &&
                    !$wouldEndUpWithAudio
                ),
                'nullable',
                'mimes:mp3,mp4,m4a,aac,oga,wav,wma,webm',
                'max:512000',
            ],
            'transcription' => [
                Rule::requiredIf(
                    $wouldEndUpWithoutAudio &&
                    !$wouldEndUpWithTranscription
                ),
                'nullable',
                // new MusicXML(),
            ],
        ];
    }
}
