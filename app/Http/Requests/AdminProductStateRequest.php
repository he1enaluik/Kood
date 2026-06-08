<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminProductStateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        return [
            'overrides' => ['required', 'array'],
            'overrides.*.slug' => ['required', 'string', 'max:120', 'regex:/^[a-z0-9\-]+$/'],
            'overrides.*.name' => ['required', 'string', 'max:200'],
            'overrides.*.price' => ['required', 'numeric', 'min:0', 'max:9999'],
            'overrides.*.category' => ['required', 'string', 'in:mesi,kunlad,kinke,hooaeg'],
            'overrides.*.origin_filter' => ['required', 'string', 'in:poltsamaa,jogevamaa,laane'],
            'overrides.*.origin' => ['required', 'string', 'max:120'],
            'overrides.*.short_desc' => ['required', 'string', 'max:500'],
            'overrides.*.image' => ['nullable', 'string', 'max:500'],
            'overrides.*.badge' => ['nullable', 'string', 'max:40'],
            'deletedSlugs' => ['present', 'array'],
            'deletedSlugs.*' => ['string', 'max:120', 'regex:/^[a-z0-9\-]+$/'],
        ];
    }
}
