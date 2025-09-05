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

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => ':attribute を :max 桁以上入力してください！',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'string' => 'The :attribute may not be greater than :max characters.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => ':attribute を入力してください。',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => ':attribute と :other は一致していません。',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',

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
        'ERGP0002' => [
            'required' => 'グループ名を入れてください。'
        ],
        'ERGP0001' => [
            'required' => 'グループ名はすでに存在してます。'
        ],
        'group_name' => [
            'max' => 'グループ名を200文字以内に入力してください。'
        ],
        'name' => [
            'required' => '名前を入力してください!'
        ],
        'email' => [
            'required' => 'メールアドレスを入力してください!',
            'unique' => 'このメールはすでに使用されています。',
            'email' => '電子メールは有効な電子メール アドレスである必要があります。'
        ],
        'url' => [
            'required' => 'format!'
        ],
        'title' => [
            'required' => 'タイトルを入れてください。'
        ],
        'body' => [
            'required' => '問題内容を入れてください。'
        ],
        'option1' => [
            'required' => '選択肢1を入れてください。'
        ],
        'option2' => [
            'required' => '選択肢2を入れてください。'
        ],
        'ERQA0005' => [
            'required' => '選択肢4より、選択肢3を入れてください。'
        ],
        'ERQA0006' => [
            'required' => '選択肢3の内容を入力してください。'
        ],
        'ERQA0007' => [
            'required' => '選択肢4の内容を入力してください。'
        ],
        'correct_answer' => [
            'required' => '正当の回答を選択してください。'
        ],
        'question_type' => [
            'required' => '問題種類を入力してください!'
        ],
        'password' => [
            'required' => 'パスワードを入力してください!'
        ],
        'new_password' => [
            'required' => '新規パスワードを入力してください!'
        ],
        'confirm_password' => [
            'required' => '確認パスワードを入力してください!!'
        ],
        'confirm_password' => [
            'same' => '新規パスワードと確認パスワードは一致していません!'
        ],
        'new_password' => [
            'min' => '新規パスワードを8桁以上入力してください。!'
        ],
        'confirm_password' => [
            'min' => '確認パスワードを8桁以上入力してください。!'
        ],
        'login-error' => [
            'required' => 'メールアドレスかパスワードが間違ってます。管理者にお問い合わせください。'
        ],
        'ERGP0011' => [
            'required' => '試験名を入力してください!!'
        ],
        'exam_nm' => [
            'max' => '試験名を100文字以内に入力してください。'
        ],
        'start_dt' => [
            'required' => '有効期限開始を入力してください!!'
        ],
        'end_dt' => [
            'required' => '有効期限終了を入力してください!!'
        ],
        'ERGP0012' => [
            'required' => '問題種類を入力してください!!'
        ],
        'question_type_nm' => [
            'max' => '問題種類を100文字以内に入力してください。'
        ],
        'category_nm'=>[
            'required'=>'カテゴリ名を入力してください!!'
        ],
        'explanation'=>[
            'required'=>'用語の説明を入力してください!!'
        ],
        'word' =>[
            'required' => '用語の言葉を入力してください!!'
        ]
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
