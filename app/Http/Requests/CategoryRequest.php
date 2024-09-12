<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // access to the param, this refer to the request
        $id = $this->route("category");

        // automatic validate this array, so we don't need to validate it in the controller once pass CategoryRequest
        return Category::rules($id); // $id for exclude the current record from the uniqueness check.
    }
}
