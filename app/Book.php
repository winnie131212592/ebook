<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
	protected $guarded = [];

	public function pages()
	{
		return $this->hasMany(Page::class);
	}

	public function cover()
	{
		return $this->pages()->first();
	}

    public function views()
    {
        return $this->belongsToMany(User::class, 'book_view')->withTimestamps();
    }

    public function bookmarks()
    {
        return $this->belongsToMany(User::class, 'book_user')->withTimestamps();
    }

    public function downloads()
    {
        return $this->belongsToMany(User::class, 'book_download')->withTimestamps();
    }

    public static function mostViewed($top = 10)
    {
        return \DB::table('book_view')
                ->select(
                        'book_view.book_id',
                        'books.title', 
                        \DB::raw('COUNT(book_view.book_id) as total_views')
                )
                ->join('books', 'book_view.book_id', '=', 'books.id')
                ->groupBy('book_view.book_id')
                ->orderBy('total_views', 'desc')
                ->limit($top)
                ->get();
    }
}
