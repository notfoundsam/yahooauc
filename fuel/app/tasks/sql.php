<?php

namespace Fuel\Tasks;

/**
* 
*/
class Sql
{
	public static function run()
	{
		try
		{
			$users = \DB::select_array(['id', 'username'])->from('users')->execute();

			\DB::start_transaction();

			foreach ($users as $user)
			{
				\DB::update('auctions')->value('won_user', $user['id'])->where('won_user', '=', $user['username'])->execute();
			}

			\DB::commit_transaction();

			\DBUtil::modify_fields('auctions', [
				'won_user' => ['constraint' => 11, 'type' => 'int', 'name' => 'user_id'],
			]);
			
		}
		catch (Exception $e)
		{
			\DB::rollback_transaction();
		}
	}
}