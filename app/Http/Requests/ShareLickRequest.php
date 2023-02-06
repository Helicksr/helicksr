<?php

namespace App\Http\Requests;

use App\Models\Lick;
use Illuminate\Foundation\Http\FormRequest;

class ShareLickRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('share', $this->route('lick'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'share_target_users' => ['array'],
            'share_target_teams' => ['array'],
        ];
    }
}
