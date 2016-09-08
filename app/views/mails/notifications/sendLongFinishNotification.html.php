<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title></title>
</head>
<body style="margin: 0; padding: 0;">
<img src="https://www.godesigner.ru/img/logo_original-03.png" width="200">

<table>
    <tr><td width="5"></td><td width="30"></td><td>
            <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;">ДОЛГОЕ ЗАВЕРШЕНИЕ ПРОЕКТА!</span><br>
        </td></tr>
    <tr><td colspan="3" height="40"></td></tr>
    <tr><td width="5"></td><td valign="top">

        </td>
        <td>
            <a style="color: #648fa4; line-height: 17px; font-size: 20px; font-weight: bold; font-family: Arial, sans-serif;text-decoration: none;" href="https://www.godesigner.ru/pitches/view/<?=$project->id?>"><?=$project->title?></a><br/>
            <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;"><?=$this->view()->render(array('template' => 'pitch-info'), array('pitch' => $project));?></span><br/>
            <span style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;">Завершение проекта затянулось. <a href="https://cp.godesigner.ru/users/loginasadmin?query=redirect&redirect=https://www.godesigner.ru/users/step<?= $step?>/<?= $project->awarded ?>">Войти как администратор</a><br/></span><br/>
        </td></tr>
    <tr>
        <td colspan="3" height="100"></td>
    </tr>
</table>

</body></html>