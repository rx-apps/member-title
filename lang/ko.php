<?php
if(!defined('__XE__'))
{
	exit();
}

/** @var stdClass $lang */

/**
 * 기본 정보 관련
 */
$lang->member_title_title = '회원 칭호';
$lang->member_title_description = '회원에게 칭호를 부여합니다.';

/**
 * 관리자 페이지 메뉴
 */
$lang->member_title_admin_menu_index = '대시보드';
$lang->member_title_admin_menu_member_list = '회원 목록';
$lang->member_title_admin_menu_member_title = '회원 칭호 설정';
$lang->member_title_admin_menu_title_list = '칭호 목록';
$lang->member_title_admin_menu_title_info = '칭호 설정';

/**
 * 관리자 페이지 대시보드
 */
$lang->member_title_admin_tit_check_update = '업데이트 확인';
$lang->member_title_admin_lbl_is_updated = '최신버전 여부';
$lang->member_title_admin_txt_already_updated = '최신버전을 사용하고 있습니다! (버전 %s)';
$lang->member_title_admin_txt_need_update = '최신버전으로 업데이트가 필요합니다! 깃헙에 방문하여 최신버전을 다운로드 받으세요. (버전 %s)';
$lang->member_title_admin_lbl_github_url = '깃헙 URL';
$lang->member_title_admin_txt_github_url = '네트워크 상태 불안정 등의 이유로 최신버전 정보가 부정확할 수 있습니다.<br />링크 접속 후 Watch -> Custom -> Releases 체크 후 Apply 버튼을 클릭하여 깃헙 알림센터를 통한 최신버전 알림을 받아볼 수 있습니다.';
$lang->member_title_admin_lbl_advertisement = '커피한잔 대신 (광고)';
$lang->member_title_admin_txt_advertisement_name = '제 컴퓨터에서는 작동하는데요?! 아니 진짜로 작동하는데 왜 거기서는 작동은 안하는지... 저는 잘 모르겠네요ㅎㅎ;';
$lang->member_title_admin_txt_advertisement_url = 'https://smartstore.naver.com/dsticker/products/6518098733?nt_source=github&nt_medium=rx-apps&nt_detail=member-title';

$lang->member_title_admin_tit_config = '기본 설정';

/**
 * 관리자 페이지 회원 목록
 */
$lang->member_title_admin_lbl_title = '칭호명';
$lang->member_title_admin_btn_member_title = '칭호 설정';

/**
 * 관리자 페이지 회원 칭호 설정
 */
$lang->member_title_admin_tit_member_title = '칭호 설정';
$lang->member_title_admin_lbl_current_title = '사용 칭호';
$lang->member_title_admin_txt_no_selection = '선택 안함';

/**
 * 관리자 페이지 칭호 목록
 */
$lang->member_title_admin_btn_all_titles = '모든 칭호';
$lang->member_title_admin_btn_insert_title = '칭호 추가';
$lang->member_title_admin_lbl_title = '칭호명';
$lang->member_title_admin_lbl_description = '칭호 설명';
$lang->member_title_admin_lbl_preview = '미리보기';
$lang->member_title_admin_lbl_type = '타입';
$lang->member_title_admin_lbl_content = '내용';

$lang->member_title_admin_menu_type_text = '텍스트형';
$lang->member_title_admin_menu_type_image = '이미지형';
$lang->member_title_admin_menu_type_mixed = '혼합형';

/**
 * 관리자 페이지 칭호 설정
 */
$lang->member_title_admin_lbl_member_title_title = '칭호명';
$lang->member_title_admin_txt_member_title_title = '칭호명을 입력해 주세요. 칭호를 잘 표현할 수 있는 이름이 좋습니다.';
$lang->member_title_admin_lbl_member_title_description = '칭호 설명';
$lang->member_title_admin_txt_member_title_description = '칭호의 설명을 입력해 주세요. 스킨에 따라 사용자가 해당 설명을 볼 수 있으므로 명확하게 작성하는 것이 좋습니다.';
$lang->member_title_admin_lbl_member_title_type = '타입';
$lang->member_title_admin_txt_member_title_type = '칭호의 타입입니다. 스킨에 따라 지원하지 않을 수 있습니다.';
$lang->member_title_admin_lbl_member_title_content = '내용';
$lang->member_title_admin_txt_member_title_content = '실제로 출력되는 칭호의 내용입니다. 텍스트형의 경우 칭호 뱃지의 텍스트를, 이미지형의 경우 뱃지 대신 출력될 이미지 주소를 입력하는 것이 일반적입니다.';
$lang->member_title_admin_lbl_member_title_option = '옵션';
$lang->member_title_admin_txt_member_title_option = '스킨에 전달되는 옵션입니다. 텍스트형의 경우 칭호 뱃지의 색상을 입력하는 것이 일반적이나, 스킨의 처리 방식에 따라 다르게 활용될 수 있습니다.';
