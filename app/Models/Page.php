<?php

namespace App\Models;

use App\Support\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use LogsActivity;

    /** Long-form field excluded from the audit diff. */
    protected array $activityLogIgnore = ['content'];

    protected $guarded = ['id'];
}
