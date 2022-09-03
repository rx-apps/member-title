<?php

use Rhymix\Framework\Exceptions\DBError;

class Member_titleController extends Member_title
{
	/**
	 * @var array 칭호 HTML 캐시
	 */
	private static $_cache_title_htmls = [];

	/**
	 * 화면 출력 전 HTML을 조작합니다.
	 * 
	 * @param string $output
	 * @return bool
	 * @noinspection PhpUnused
	 */
	public function triggerDisplayBefore(string &$output): bool
	{
		$output = preg_replace_callback('!<(div|span|a)([^>]*)member_([0-9\-]+)([^>]*)>(.*?)</(div|span|a)>!is', function (array $matches) {
			return $this->transformOutput($matches);
		}, $output);
		
		return true;
	}

	/**
	 * member_nn 값이 속성 등으로 포함된 요소 내에 칭호를 삽입합니다.
	 * 
	 * @param array $matches
	 * @return string
	 */
	private function transformOutput(array $matches): string
	{
		$output = $matches[0];
		$tag_name = $matches[1];
		$member_srl = $matches[3];
		$nick_name = $matches[5];
		
		if($member_srl < 1 || !$member_srl)
		{
			return $output;
		}
		
		$oModel = $this->getModel();
		try
		{
			$title = $oModel->getMemberTitle($member_srl);
			if(!$title)
			{
				throw new Exception();
			}
		}
		catch(Exception $e)
		{
			return $output;
		}
		
		if(!isset(self::$_cache_title_htmls[$member_srl]))
		{
			self::$_cache_title_htmls[$member_srl] = $oModel->getTitleHtml($title->title_srl, $member_srl);	
		}
		$title_html = self::$_cache_title_htmls[$member_srl];
		
		$tag_start = preg_replace('!' . preg_quote($nick_name, '!') . '</' . $tag_name . '>$!', '', $output);
		return $tag_start . $title_html . $nick_name . '</' . $tag_name . '>';
	}

	/**
	 * 회원의 칭호를 설정합니다.
	 *
	 * @param int $member_srl
	 * @param int $title_srl
	 * @return bool
	 * @throws DBError
	 */
	public function setMemberTitle(int $member_srl, int $title_srl): bool
	{
		$output = executeQuery('member_title.upsertMemberTitle', (object) [
			'member_srl' => $member_srl,
			'title_srl' => $title_srl,
			'last_update' => date('YmdHis')
		]);

		if(!$output->toBool())
		{
			throw new DBError($output->message);
		}

		return true;
	}
}
