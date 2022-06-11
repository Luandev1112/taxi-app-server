<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'ال :attribute يجب أن تكون مقبولة.',
    'active_url' => 'ال :attribute ليس عنوان URL صالحًا.',
    'after' => 'ال :attribute يجب أن يكون تاريخ بعد :date.',
    'after_or_equal' => 'ال :attribute يجب أن يكون تاريخ بعد أو يساوي :date.',
    'alpha' => 'ال :attribute قد تحتوي فقط على رسائل.',
    'alpha_dash' => 'ال :attribute قد يحتوي فقط على أحرف وأرقام وشرطات وشرطات سفلية.',
    'alpha_num' => 'ال :attribute قد يحتوي فقط على أحرف وأرقام.',
    'array' => 'ال :attribute يجب أن يكون مجموعة.',
    'before' => 'ال :attribute يجب أن يكون تاريخ من قبل :date.',
    'before_or_equal' => 'ال :attribute يجب أن يكون تاريخ قبل أو يساوي :date.',
    'between' => [
        'numeric' => 'ال :attribute يجب ان يكون بين :min و :max.',
        'file' => 'ال :attribute يجب ان يكون بين :min و :max كيلو بايت.',
        'string' => 'ال :attribute يجب ان يكون بين :min و :max الشخصيات.',
        'array' => 'ال :attribute يجب أن يكون بين :min و :max العناصر.',
    ],
    'boolean' => 'ال :attribute field must be true or false.',
    'confirmed' => 'ال :attribute confirmation does not match.',
    'date' => 'ال :attribute is not a valid date.',
    'date_equals' => 'ال :attribute must be a date equal to :date.',
    'date_format' => 'ال :attribute does not match the format :format.',
    'different' => 'ال :attribute و :oالr must be different.',
    'digits' => 'ال :attribute must be :digits digits.',
    'digits_between' => 'ال :attribute يجب ان يكون بين :min و :max digits.',
    'dimensions' => 'ال :attribute has invalid image dimensions.',
    'distinct' => 'ال :attribute field has a duplicate value.',
    'email' => 'ال :attribute must be a valid email address.',
    'exists' => 'ال selected :attribute is invalid.',
    'file' => 'ال :attribute must be a file.',
    'filled' => 'ال :attribute field must have a value.',
    'gt' => [
        'numeric' => 'ال :attribute must be greater than :value.',
        'file' => 'ال :attribute must be greater than :value kilobytes.',
        'string' => 'ال :attribute must be greater than :value characters.',
        'array' => 'ال :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'ال :attribute must be greater than or equal :value.',
        'file' => 'ال :attribute must be greater than or equal :value kilobytes.',
        'string' => 'ال :attribute must be greater than or equal :value characters.',
        'array' => 'ال :attribute must have :value items or more.',
    ],
    'image' => 'ال :attribute must be an image.',
    'in' => 'ال selected :attribute is invalid.',
    'in_array' => 'ال :attribute field does not exist in :other.',
    'integer' => 'ال :attribute must be an integer.',
    'ip' => 'ال :attribute must be a valid IP address.',
    'ipv4' => 'ال :attribute must be a valid IPv4 address.',
    'ipv6' => 'ال :attribute must be a valid IPv6 address.',
    'json' => 'ال :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'ال :attribute must be less than :value.',
        'file' => 'ال :attribute must be less than :value kilobytes.',
        'string' => 'ال :attribute must be less than :value characters.',
        'array' => 'ال :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'ال :attribute must be less than or equal :value.',
        'file' => 'ال :attribute must be less than or equal :value kilobytes.',
        'string' => 'ال :attribute must be less than or equal :value characters.',
        'array' => 'ال :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'ال :attribute may not be greater than :max.',
        'file' => 'ال :attribute may not be greater than :max kilobytes.',
        'string' => 'ال :attribute may not be greater than :max characters.',
        'array' => 'ال :attribute may not have more than :max items.',
    ],
    'mimes' => 'ال :attribute must be a file of type: :values.',
    'mimetypes' => 'ال :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'ال :attribute must be at least :min.',
        'file' => 'ال :attribute must be at least :min kilobytes.',
        'string' => 'ال :attribute must be at least :min characters.',
        'array' => 'ال :attribute must have at least :min items.',
    ],
    'not_in' => 'ال selected :attribute is invalid.',
    'not_regex' => 'ال :attribute format is invalid.',
    'numeric' => 'ال :attribute must be a number.',
    'present' => 'ال :attribute field must be present.',
    'regex' => 'ال :attribute format is invalid.',
    'required' => 'ال :attribute field is required.',
    'required_if' => 'ال :attribute field is required when :oالr is :value.',
    'required_unless' => 'ال :attribute field is required unless :other is in :values.',
    'required_with' => 'ال :attribute field is required when :values is present.',
    'required_with_all' => 'ال :attribute field is required when :values are present.',
    'required_without' => 'ال :attribute field is required when :values is not present.',
    'required_without_all' => 'ال :attribute field is required when none of :values are present.',
    'same' => 'ال :attribute و :other must match.',
    'size' => [
        'numeric' => 'ال :attribute must be :size.',
        'file' => 'ال :attribute must be :size kilobytes.',
        'string' => 'ال :attribute must be :size characters.',
        'array' => 'ال :attribute must contain :size items.',
    ],
    'string' => 'ال :attribute must be a string.',
    'timezone' => 'ال :attribute must be a valid zone.',
    'unique' => 'ال :attribute has already been taken.',
    'uploaded' => 'ال :attribute failed to upload.',
    'url' => 'ال :attribute format is invalid.',
    'uuid' => 'ال :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
