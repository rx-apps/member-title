<?php

use Rhymix\Framework\Exceptions\DBError;
use Rhymix\Framework\Exceptions\TargetNotFound;

class Member_titleAdminController extends Member_title
{
	/**
	 * 칭호를 생성합니다.
	 *
	 * @param string $title
	 * @param string $description
	 * @param string $type
	 * @param string $content
	 * @param string $option
	 * @return int
	 * @throws DBError
	 */
	public function insertTitle(string $title, string $description, string $type, string $content, string $option = ''): int
	{
		$title_srl = getNextSequence();
		$output = executeQuery('member_title.insertTitle', [
			'title_srl' => $title_srl,
			'title' => $title,
			'description' => $description,
			'type' => $type,
			'content' => $content,
			'option' => $option,
			'regdate' => date('YmdHis')
		]);
		
		if(!$output->toBool())
		{
			throw new DBError($output->message);
		}
		
		return $title_srl;
	}

	/**
	 * 칭호를 수정합니다.
	 * 
	 * @param int $title_srl
	 * @param string $title
	 * @param string $description
	 * @param string $type
	 * @param string $content
	 * @param string $option
	 * @return bool
	 * @throws DBError
	 */
	public function updateTitle(int $title_srl, string $title, string $description, string $type, string $content, string $option = ''): bool
	{
		$output = executeQuery('member_title.updateTitle', [
			'title_srl' => $title_srl,
			'title' => $title,
			'description' => $description,
			'type' => $type,
			'content' => $content,
			'option' => $option,
			'regdate' => date('YmdHis')
		]);

		if(!$output->toBool())
		{
			throw new DBError($output->message);
		}
		
		return true;
	}

	/**
	 * 칭호를 삭제합니다.
	 * 
	 * @param int $title_srl
	 * @return bool
	 * @throws DBError
	 */
	public function deleteTitle(int $title_srl): bool
	{
		$output = executeQuery('member_title.deleteTitle', [
			'title_srl' => $title_srl
		]);
		
		if(!$output->toBool())
		{
			throw new DBError($output->message);
		}
		
		return true;
	}
	
	/**
	 * 회원 칭호를 설정하는 메뉴입니다.
	 * 
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function procMember_titleAdminMemberTitle()
	{
		$vars = Context::getRequestVars();
		$member_srl = $vars->member_srl;
		$title_srl = $vars->title_srl;
		
		try
		{
			if($title_srl != 0)
			{
				$oModel = $this->getModel();
				if(!$oModel->getTitle($title_srl))
				{
					throw new TargetNotFound();
				}	
			}
			
			$oController = $this->getController();
			$oController->setMemberTitle((int) $member_srl, (int) $title_srl);
		}
		catch(Exception $e)
		{
			$this->setError(-1);
			$this->setMessage($e->getMessage());
			$this->setRedirectUrl($vars->error_return_url);
			return;
		}

		$this->setMessage('success_updated');
		$this->setRedirectUrl(getNotEncodedUrl('', 'module', 'admin', 'act', 'dispMember_titleAdminMemberList'));
	}

	/**
	 * 칭호를 생성하거나 수정하는 메뉴입니다.
	 * 
	 * @return void
	 * @throws DBError
	 */
	public function procMember_titleAdminTitleInsert()
	{
		$vars = Context::getRequestVars();
		$title_srl = $vars->title_srl;
		
		if(!$title_srl)
		{
			$this->insertTitle($vars->title, $vars->description ?? '', $vars->type, $vars->content ?? '', $vars->option ?? '');
			$this->setMessage('success_registed');
		}
		else
		{
			$this->updateTitle($title_srl, $vars->title, $vars->description ?? '', $vars->type, $vars->content ?? '', $vars->option ?? '');
			$this->setMessage('success_updated');
		}
		
		$this->setRedirectUrl(getNotEncodedUrl('', 'module', 'admin', 'act', 'dispMember_titleAdminTitleList'));
	}

	/**
	 * 칭호를 삭제하는 메뉴입니다.
	 * 
	 * @return void
	 * @throws DBError
	 */
	public function procMember_titleAdminTitleDelete()
	{
		$title_srl = Context::get('title_srl');
		$this->deleteTitle($title_srl);

		$this->setMessage('success_deleted');
		$this->setRedirectUrl(getNotEncodedUrl('', 'module', 'admin', 'act', 'dispMember_titleAdminTitleList'));
	}
}
