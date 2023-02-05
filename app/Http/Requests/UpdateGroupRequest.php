<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @property string $name
 * @property Group $group
 */
class UpdateGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->group->owner_id == auth()->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:1|max:25',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => "Invalid body."
            ], 401)
        );
    }

    public function failedAuthorization()
    {
        throw new HttpResponseException(
            response()->json([
                'message' => "Unauthorized."
            ], 401)
        );
    }
}
