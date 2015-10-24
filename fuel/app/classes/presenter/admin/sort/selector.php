<?php

class	Presenter_Admin_Sort_Selector	extends	\Presenter
{
	public function view()
	{
		$this->users = Auth::find('all');
	}
}
