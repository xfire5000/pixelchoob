<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'qty' => 'required|numeric|min:1',
            'dimensions.w' => 'required|numeric|min:1',
            'dimensions.h' => 'required|numeric|min:1',
            // chamfers validation
            'chamfer.l1' => [
                'required_if:pvc.l1,false',
                'required_if:groove.l,false',
                'required_if:gazor_hinge.l,false',
            ],
            'chamfer.l2' => [
                'required_if:pvc.l2,false',
                'required_if:groove.l,false',
                'required_if:gazor_hinge.l,false',
            ],
            'chamfer.w1' => [
                'required_if:pvc.w1,false',
                'required_if:groove.w,false',
                'required_if:gazor_hinge.w,false',
            ],
            'chamfer.w2' => [
                'required_if:pvc.w2,false',
                'required_if:groove.w,false',
                'required_if:gazor_hinge.w,false',
            ],
            // pvc validations
            'pvc.l1' => [
                'required_if:chamfer.l1,false',
                'required_if:groove.l,false',
                'required_if:gazor_hinge.l,false',
            ],
            'pvc.l2' => [
                'required_if:chamfer.l2,false',
                'required_if:groove.l,false',
                'required_if:gazor_hinge.l,false',
            ],
            'pvc.w1' => [
                'required_if:chamfer.w1,false',
                'required_if:groove.w,false',
                'required_if:gazor_hinge.w,false',
            ],
            'pvc.w2' => [
                'required_if:chamfer.w2,false',
                'required_if:groove.w,false',
                'required_if:gazor_hinge.w,false',
            ],
            // gazor_hinge validation
            'gazor_hinge.l' => [
                'required_if:chamfer.l1,false',
                'required_if:chamfer.l2,false',
                'required_if:pvc.l1,false',
                'required_if:pvc.l2,false',
                'required_if:groove.l,false',
            ],
            'gazor_hinge.w' => [
                'required_if:chamfer.w1,false',
                'required_if:chamfer.w2,false',
                'required_if:pvc.w1,false',
                'required_if:pvc.w2,false',
                'required_if:groove.w,false',
            ],
            // groove validation
            'groove.l' => [
                'required_if:chamfer.l1,false',
                'required_if:chamfer.l2,false',
                'required_if:pvc.l1,false',
                'required_if:pvc.l2,false',
                'required_if:gazor_hinge.l,false',
            ],
            'groove.w' => [
                'required_if:chamfer.w1,false',
                'required_if:chamfer.w2,false',
                'required_if:pvc.w1,false',
                'required_if:pvc.w2,false',
                'required_if:gazor_hinge.w,false',
            ],
        ];
    }
}
