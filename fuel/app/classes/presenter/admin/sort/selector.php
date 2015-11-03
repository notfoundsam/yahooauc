<?php

class	Presenter_Admin_Sort_Selector	extends	\Presenter
{
	public function view()
	{
		$this->users = \Model\Auth_User::find('all', [
			'where' => [['id', '>', 0]],
		]);
	}
}
