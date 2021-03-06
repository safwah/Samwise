<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var array
	 */
	protected $appends = ['path', 'children'];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [];

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id', 'created_at', 'updated_at', 'prev', 'next'];

	/**
	 * A page can have many children
	 */
	public function getChildrenAttribute()
	{
		return $this->hasMany('App\Page', 'parent_id')->orderBy('sorting')->get();
	}

	public function getPathAttribute()
	{
		$path = "/" . makeSlug($this->name);
		if ($this->parent_id != null) {
			$path = Page::find($this->parent_id)->path . $path;
		}

		return $path;
	}

	/**
	 * Retrieve the prev item
	 */
	public function getPrevAttribute()
	{
		$prev_id = Page::where('id', '<', $this->id)->max('id');
		if ($prev_id == null) {
			$prev_id = Page::all()->max('id');
		}
		return "/page/$prev_id";
	}

	/**
	 * Retrieve the next item
	 */
	public function getNextAttribute()
	{
		$next_id = Page::where('id', '>', $this->id)->min('id');
		if ($next_id == null) {
			$next_id = Page::all()->min('id');
		}
		return "/page/$next_id";
	}

	static public function getMenus() {
		$menu = [
			'header' => Page::where('parent_id', null)->where('location','header')->orderBy('sorting')->get()->toArray(),
			'footer' => Page::where('parent_id', null)->where('location','footer')->orderBy('sorting')->get()->toArray()
		];

		foreach ($menu as $location => $page) {
			for ($i=0; $i < count($menu[$location]); $i++) { 
				$id = $menu[$location][$i]['id'];
				$name = $menu[$location][$i]['name'];
				$path = $menu[$location][$i]['path'];
				$menu[$location][$i] = ['id' => $id,'name' => $name, 'path' => $path];
			}
		}

		return $menu;
	}
}
