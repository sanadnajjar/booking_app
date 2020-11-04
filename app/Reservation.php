<?php
/*
|--------------------------------------------------------------------------
| app/Reservation.php *** Copyright netprogs.pl | available only at Udemy.com | further distribution is prohibited  ***
|--------------------------------------------------------------------------
*/

namespace App; /* Lecture 19 */

use Illuminate\Database\Eloquent\Model; /* Lecture 19 */

/* Lecture 19 */
class Reservation extends Model
{
    public $timestamps = false; /* Lecture 26 */
    protected $guarded = ['id']; /* Lecture 26 */
    //protected $fillable = ['name']; /* Lecture 26 */

    /* Lecture 28 */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /* Lecture 30 */
    public function room()
    {
        return $this->belongsTo('App\Room');
    }


}

