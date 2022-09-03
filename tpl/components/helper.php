<?php
if(!defined('__XE__'))
{
	exit();
}

if(!function_exists('getDynamicProperty'))
{
	/**
	 * 오브젝트에서 키에 해당하는 값을 반환합니다.
	 * XE 템플릿에서 사용할 수 없는 $object->$key 형식을 돕기 위한 함수입니다.
	 * 
	 * @throws Exception
	 */
	function getDynamicProperty($object, string $key)
	{
		if(is_array($object))
		{
			return $object[$key];
		}
		
		if(is_object($object))
		{
			return $object->$key;
		}

		throw new Exception();
	}
}
