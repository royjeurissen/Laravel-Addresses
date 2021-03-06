<?php namespace Lecturize\Addresses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Lecturize\Addresses\Traits\HasCountry;

/**
 * Class Contact
 * @package Lecturize\Addresses\Models
 */
class Contact extends Model
{
    use HasCountry;
    use SoftDeletes;

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'gender',
        'title',
        'first_name',
        'middle_name',
        'last_name',
        'company',
        'position',
        'phone',
        'mobile',
        'fax',
        'email',
        'website',
    ];

    /**
     * @inheritdoc
     */
    protected $dates = ['deleted_at'];

    /**
     * @inheritdoc
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('lecturize.contacts.table', 'contacts');
    }

    /**
     * Get the related model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function contactable()
    {
        return $this->morphTo();
    }

    /**
     * Get the validation rules.
     *
     * @return array
     */
    public static function getValidationRules()
    {
        $rules = [];

        return $rules;
    }

    /**
     * Get the contacts full name.
     *
     * @param  bool $show_salutation
     * @return string
     */
    public function getFullNameAttribute($show_salutation = false)
    {
        $names = [];
        $names[] = $show_salutation && $this->gender ? trans('addresses::contacts.salutation.'. $this->gender) : '';
        $names[] = $this->first_name  ?: '';
        $names[] = $this->middle_name ?: '';
        $names[] = $this->last_name   ?: '';

        return trim(implode(' ', array_filter($names)));
    }

    /**
     * Get the contacts full name, reversed.
     *
     * @param  bool $show_salutation
     * @return string
     */
    public function getFullNameRevAttribute($show_salutation = false)
    {
        $first = [];
        $first[] = $this->first_name  ?: '';
        $first[] = $this->middle_name ?: '';

        $last = [];
        $last[] = $show_salutation && $this->gender ? trans('addresses::contacts.salutation.'. $this->gender) : '';
        $last[] = $this->last_name ?: '';

        $names = [];
        $names[] = implode(' ', array_filter($last));
        $names[] = implode(' ', array_filter($first));

        return trim(implode(', ', array_filter($names)));
    }
}