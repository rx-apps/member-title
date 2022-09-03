<?php

use Rhymix\Framework\Exceptions\DBError;
use Rhymix\Framework\Exceptions\TargetNotFound;

class Member_titleAdminView extends Member_title
{
	/**
	 * @var array 관리자 상단 메뉴
	 */
	protected static $_menus = [
		// [ string $menu_lang_key, [ string $act, string ...$sub_act ], (bool) $visibility = true ]
		[ 'member_title_admin_menu_index', [ 'dispMember_titleAdminIndex' ] ],
		[ 'member_title_admin_menu_member_list', [ 'dispMember_titleAdminMemberList' ] ],
		[ 'member_title_admin_menu_member_title', [ 'dispMember_titleAdminMemberTitle'], false ],
		[ 'member_title_admin_menu_title_list' , [ 'dispMember_titleAdminTitleList' ] ],
		[ 'member_title_admin_menu_title_info', [ 'dispMember_titleAdminTitleInsert', 'dispMember_titleAdminTitleUpdate', 'dispMember_titleAdminTitleDelete' ], false ]
	];

	/**
	 * 초기화 시 호출됩니다.
	 * 
	 * @return void
	 */
	public function init()
	{
		Context::set('menus', self::$_menus);
		$this->setTemplatePath(__DIR__ . '/tpl');
	}

	/**
	 * 메뉴의 출력 여부를 설정합니다.
	 *
	 * @param int $menu_index
	 * @param bool $visibility
	 * @return bool
	 */
	private function setMenuVisibility(int $menu_index, bool $visibility): bool
	{
		if(!isset(self::$_menus[$menu_index]))
		{
			return false;
		}
		
		self::$_menus[$menu_index][2] = $visibility;
		Context::set('menus', self::$_menus);
		
		return true;
	}
	
	/**
	 * 메뉴를 보이게 설정합니다.
	 *
	 * @param int $menu_index
	 * @return bool
	 */
	private function setMenuVisible(int $menu_index): bool
	{
		return $this->setMenuVisibility($menu_index, true);
	}
	
	/**
	 * 메뉴를 보이지 않게 설정합니다.
	 *
	 * @param int $menu_index
	 * @return bool
	 */
	private function setMenuInvisible(int $menu_index): bool
	{
		return $this->setMenuVisibility($menu_index, false);
	}

	/**
	 * 대시보드 역할을 하는 메인 메뉴입니다.
	 * 
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function dispMember_titleAdminIndex()
	{
		$oAdminModel = $this->getAdminModel();
		
		$current_version = $oAdminModel->getCurrentVersion();
		Context::set('current_version', $current_version);
		
		$github_version = $oAdminModel->getGithubVersion();
		Context::set('github_version', $github_version);
		
		$is_github_updated = version_compare($current_version, $github_version, '>=');
		Context::set('is_github_updated', $is_github_updated);
		
		$github_url = $oAdminModel->getGithubUrl();
		Context::set('github_url', $github_url);
		
		$this->setTemplateFile('index');
	}

	/**
	 * 회원 목록을 출력하는 메뉴입니다.
	 *
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function dispMember_titleAdminMemberList()
	{
		$oModel = $this->getModel();
		
		/** @var memberAdminModel $oMemberAdminModel */
		$oMemberAdminModel = getAdminModel('member');
		
		/** @var memberModel $oMemberModel */
		$oMemberModel = getModel('member');
		$member_config = $oMemberModel->getMemberConfig();

		$output = $oMemberAdminModel->getMemberList();
		if($output->data)
		{
			foreach($output->data as $key => $member)
			{
				if($member->denied !== 'N')
				{
					$output->data[$key] = null;
					continue;
				}
				
				$output->data[$key]->group_list = $oMemberModel->getMemberGroups($member->member_srl);
				
				try
				{
					$title_srl = $oModel->getMemberTitle($member->member_srl)->title_srl;
					if($title_srl)
					{
						$output->data[$key]->title = $oModel->getTitle($title_srl);
					}
				}
				catch(Exception $e)
				{
					$output->data[$key]->title = null;
				}
				
				if($member_config->profile_image === 'Y')
				{
					$output->data[$key]->profile_image = $oMemberModel->getProfileImage($member->member_srl);
				}
			}
		}
		
		$output->data = array_filter($output->data);
		
		$member_identifiers = [
			'user_id' => 'user_id',
			'email_address' => 'email_address',
			'phone_number' => 'phone_number',
			'user_name' => 'user_name',
			'nick_name' => 'nick_name'
		];
		
		$used_identifiers = [];
		if(is_array($member_config->signupForm))
		{
			foreach($member_config->signupForm as $signup_item)
			{
				if(!count($member_identifiers))
				{
					break;
				}
				
				if(in_array($signup_item->name, $member_identifiers) && ($signup_item->required || $signup_item->isUse))
				{
					unset($member_identifiers[$signup_item->name]);
					$used_identifiers[$signup_item->name] = Context::getLang($signup_item->name);
				}
			}
		}
		
		Context::set('total_count', $output->total_count);
		Context::set('total_page', $output->total_page);
		Context::set('page', $output->page);
		Context::set('member_list', $output->data);
		Context::set('page_navigation', $output->page_navigation);
		Context::set('used_identifiers', $used_identifiers);
		Context::set('member_config', $member_config);
		
		$this->setTemplateFile('member_list');
	}

	/**
	 * 회원 칭호를 설정하는 메뉴입니다.
	 *
	 * @return void
	 * @throws TargetNotFound
	 * @throws DBError
	 * @noinspection PhpUnused
	 */
	public function dispMember_titleAdminMemberTitle()
	{
		$member_srl = Context::get('member_srl');
		if(!$member_srl)
		{
			throw new TargetNotFound();
		}
		
		/** @var memberModel $oMemberModel */
		$oMemberModel = getModel('member');
		$member_info = $oMemberModel->getMemberInfo($member_srl);
		if(!$member_info->member_srl)
		{
			throw new TargetNotFound();
		}
		
		$oModel = $this->getModel();
		$title_list = $oModel->getManyTitles();
		$member_title = $oModel->getMemberTitle($member_srl);

		Context::set('member_info', $member_info);
		Context::set('title_list', $title_list);
		Context::set('member_title', $member_title);

		$this->setMenuVisible(2);
		$this->setTemplateFile('member_title');
	}

	/**
	 * 칭호 목록을 출력하는 메뉴입니다.
	 *
	 * @return void
	 * @throws DBError
	 * @noinspection PhpUnused
	 */
	public function dispMember_titleAdminTitleList()
	{
		$page = Context::get('page') ?? 1;
		
		$oModel = $this->getModel();
		$oAdminModel = $this->getAdminModel();
		$output = $oAdminModel->getManyTitlesWithPagination([
			'page' => $page,
			'type' => Context::get('type'),
			'sort_order' => 'desc'
		]);
		
		foreach($output->data as $key => $title)
		{
			$output->data[$key]->preview = $oModel->getTitleHtml($title->title_srl);
		}
		
		Context::set('total_count', $output->total_count);
		Context::set('total_page', $output->total_page);
		Context::set('page', $page);
		Context::set('title_list', $output->data);
		Context::set('page_navigation', $output->page_navigation);
		
		$this->setTemplateFile('title_list');
	}

	/**
	 * 칭호를 추가하는 메뉴입니다.
	 * 
	 * @return void
	 */
	public function dispMember_titleAdminTitleInsert()
	{
		$this->setMenuVisible(4);
		$this->setTemplateFile('title_info');
	}

	/**
	 * 기존 칭호를 수정하는 메뉴입니다.
	 *
	 * @return void
	 * @throws TargetNotFound|DBError
	 * @noinspection PhpUnused
	 */
	public function dispMember_titleAdminTitleUpdate()
	{
		$title_srl = Context::get('title_srl');
		
		$oModel = $this->getModel();
		$title = $oModel->getTitle($title_srl);
		if(!$title)
		{
			throw new TargetNotFound();
		}
		
		Context::set('title', $title);
		
		$this->dispMember_titleAdminTitleInsert();
	}

	/**
	 * 칭호를 삭제하는 메뉴입니다.
	 * 
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function dispMember_titleAdminTitleDelete()
	{
		$title_srl = Context::get('title_srl');
		
		$oModel = $this->getModel();
		$title = $oModel->getTitle($title_srl);
		if(!$title)
		{
			throw new TargetNotFound();
		}

		Context::set('title', $title);

		$this->setMenuVisible(4);
		$this->setTemplateFile('title_delete');
	}
}
