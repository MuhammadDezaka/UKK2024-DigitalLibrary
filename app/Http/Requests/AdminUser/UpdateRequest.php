<?php

namespace App\Http\Requests\AdminUser;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'        => 'required|exists:user,id', 
            'nama_lengkap'      => 'required|max:255', 
            'username'  => 'required|unique:user,username,' . $this->id, 
            'role_id'       => 'nullable|exists:roles,id', 
            'email'     => 'email|max:255',
        ];
    }

    /**
     * Attributes
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'id'        => 'ID', 
            'name'      => 'Nama', 
            'username'  => 'Username', 
            'email'     => 'Email',
            'role_id'   => 'Role', 
        ];
    }
}
