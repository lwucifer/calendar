<?php

namespace App\Models;

class MailTemplate extends BaseModel
{
    protected $table = "mail_templates";
    protected $fillable = ["id", "template", "title", "content", "status", "is_deleted"];
    protected $field_require = [];
    protected $title = '';

    const STATUS_ACTIVE = 'active';
    const STATUS_InACTIVE = 'inactive';

    public function fieldSetValidate()
    {
        $result = [];
        $result['template'] = 'required|unique:mail_templates,template';
        return $result;
    }

    /**
     * @param $template
     * @return mixed
     */
    public static function getNameTemplateMail($template)
    {
        return self::where('template', $template)->where('is_deleted', false)->first()->value('template');
    }
}
