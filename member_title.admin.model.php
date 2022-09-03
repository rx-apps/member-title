<?php

use Rhymix\Framework\Exceptions\DBError;

class Member_titleAdminModel extends Member_title
{
	/**
	 * 깃헙 프로젝트 링크를 가져옵니다.
	 * 
	 * @return string
	 */
	public function getGithubUrl(): string
	{
		$module_name = str_replace('_', '-', $this->module);
		return 'https://github.com/rx-apps/' . $module_name;
	}
	
	/**
	 * 깃헙에 등록된 버전을 가져옵니다.
	 * 
	 * @return string
	 */
	public function getGithubVersion(): string
	{
		$module_name = str_replace('_', '-', $this->module);
		$github_url = 'https://api.github.com/repos/rx-apps/' . $module_name . '/releases/latest';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $github_url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_USERAGENT, 'rx-apps/1.0');
		curl_setopt($ch, CURLOPT_REFERER, 'https://github.com/rx-apps/');

		($output = json_decode(curl_exec($ch))) && curl_close($ch);
		if(!isset($output->tag_name))
		{
			return '0.0.0';
		}
		
		return $output->tag_name;
	}

	/**
	 * 현재 설치된 모듈의 버전을 가져옵니다.
	 * 
	 * @return string
	 */
	public function getCurrentVersion(): string
	{
		$oModuleModel = getModel('module');
		$module_info = $oModuleModel->getModuleInfoXml($this->module);
		return $module_info->version;
	}

	/**
	 * 칭호 목록을 페이지네이션 정보와 함께 가져옵니다.
	 * 
	 * @param $obj
	 * @return object
	 * @throws DBError
	 */
	public function getManyTitlesWithPagination($obj)
	{
		$output = executeQueryArray('member_title.getManyTitlesWithPagination', (object) $obj);
		if(!$output->toBool())
		{
			throw new DBError($output->message);
		}
		
		return $output;
	}
}
