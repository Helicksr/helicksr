<?php

namespace App\Http\Requests;

use App\Models\Lick;
use Illuminate\Foundation\Http\FormRequest;

class ShareLickRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('share', $this->route('lick'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'share_target_users' => ['array'],
            'share_target_teams' => ['array'],
        ];
    }
}
