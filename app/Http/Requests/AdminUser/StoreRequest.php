<?php

namespace App\Http\Requests\AdminUser;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'nama_lengkap'  => 'required|max:255', 
            'email'         => '', 
            'username'      => 'required|max:255', 
            'role_id'       => 'nullable|exists:roles,id', 
            'alamat'        => 'nullable',
            'password'      => 'required|confirmed|min:8', 
    
        ];
    }

    /**
     * Attributes
     */
    public function attributes()
    {
        return [
            'name'                  => 'Nama', 
            'email'                 => 'Email', 
            'username'              => 'Username', 
            'role_id'               => 'Role', 
            'password'              => 'Password',
            'password_confirmation' => 'Konfirmasi Password',  
        ];
    }
}
