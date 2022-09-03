<?php

use Rhymix\Framework\Exceptions\DBError;

class Member_titleModel extends Member_title
{
	/**
	 * 칭호 정보를 가져옵니다.
	 *
	 * @param int $title_srl
	 * @return ?object
	 * @throws DBError
	 */
	public function getTitle(int $title_srl)
	{
		if($title_srl == 0)
		{
			return null;
		}
		
		$output = executeQuery('member_title.getTitle', (object) [
			'title_srl' => $title_srl
		]);
		
		if(!$output->toBool())
		{
			throw new DBError($output->message);
		}
		
		return $output->data;
	}

	/**
	 * 회원의 칭호 정보를 가져옵니다.
	 *
	 * @param int $member_srl
	 * @return ?object
	 * @throws DBError
	 */
	public function getMemberTitle(int $member_srl)
	{
		$output = executeQuery('member_title.getMemberTitle', (object) [
			'member_srl' => $member_srl
		]);

		if(!$output->toBool())
		{
			throw new DBError($output->message);
		}
		
		return $output->data;
	}

	/**
	 * 칭호 HTML을 가져옵니다. member_srl을 전달할 경우 템플릿에서 회원 정보를 활용할 수 있으나, 이용을 권장하지 않습니다.
	 * 
	 * @param int $title_srl
	 * @param ?int $member_srl
	 * @return string
	 */
	public function getTitleHtml(int $title_srl, int $member_srl = null): string
	{
		$config = $this->getConfig();
		$skin = $config->skin ?: 'default';
		$skin_path = __DIR__ . '/skins/' . $skin;
	
		try
		{
			$title = $this->getTitle($title_srl);
			if(!$title)
			{
				throw new Exception();
			}	
		}
		catch(Exception $e)
		{
			return '';
		}
		
		Context::set('title', $title);
		Context::set('member_srl', $member_srl);
		
		$oTemplateHandler = TemplateHandler::getInstance();
		return $oTemplateHandler->compile($skin_path, 'title');
	}

	/**
	 * 칭호들을 가져옵니다. title_srls가 비어있을 경우 모든 칭호를 가져옵니다.
	 * 
	 * @param array $title_srls
	 * @return array
	 * @throws DBError
	 */
	public function getManyTitles(array $title_srls = []): array
	{
		$output = executeQueryArray('member_title.getManyTitles', (object) [
			'title_srls' => $title_srls
		]);
		
		if(!$output->toBool())
		{
			throw new DBError($output->message);
		}
		
		return $output->data;
	}
}
