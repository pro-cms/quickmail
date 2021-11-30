<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QaTopicCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'subject'   => 'required',
            'content'   => 'required',
            'from_email'  => 'required|email',
            'attachment'  => 'mimes:jpeg,bmp,png,gif,doc,docx,xls,xlsx,ppt,pptx,pdf,txt,zip,rar|max:2048',

        ];
    }
}
