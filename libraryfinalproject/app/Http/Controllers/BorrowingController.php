<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'issued_by',
        'status',
        'borrowed_at',
        'returned_at',
        'due_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Mark overdue borrowing records
     */
    public static function markOverdueRecords()
    {
        Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', Carbon::now())
            ->update(['status' => 'overdue']);
    }
}
