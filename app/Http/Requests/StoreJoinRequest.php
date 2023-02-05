<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreJoinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $group = Group::find($this->group);
        if ($group == null) {
            return false;
        }
        return $group->users()->get()->contains($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => "Invalid body.",
            ], 401)
        );
    }

    public function failedAuthorization()
    {
        throw new HttpResponseException(
            response()->json([
                'message' => "Unauthorized.",
            ], 401)
        );
    }
}
