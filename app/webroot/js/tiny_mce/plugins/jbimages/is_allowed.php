<?php

/*-------------------------------------------------------------------
|
| ���������� ��������������� ��� ������� ����� �������, ����� ��� ����������:
| `TRUE`, ���� ������������ ����������� ��� �������� �����������
| `FALSE` - �� ���� ��������� �������
| ����������� �������� � ����� ������� �����������.
| ��� ������� �������� ������ �������� ������ �� �������������������� �������.
| 
| ���� ����� � ���������� TinyMCE �������� HTTP-������������, �� ����� �� ������������.
| 
-------------------------------------------------------------------*/

function is_allowed()
{
    session_name('godesigner');
    session_start();
    if((isset($_SESSION['user'])) && (isset($_SESSION['user']['id'])) && (in_array($_SESSION['user']['id'], array(32, 4, 5, 108, 81)))) {
        return true;
    }
	return false;
}

?>