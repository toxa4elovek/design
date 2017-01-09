<!DOCTYPE  html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Презентация</title>
        <style type="text/css">
            * {margin:0; padding:0; text-indent:0; }
        h1 { color: #010202; font-family:FuturaDemi, sans-serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 28pt; }
        .s1 { color: #231F20; font-family:Garamond, serif; font-style: italic; font-weight: normal; text-decoration: none; font-size: 14pt; }
        p { color: #231F20; font-family:Garamond, serif; font-style: italic; font-weight: normal; text-decoration: none; font-size: 9pt; margin:0pt; }
        .s2 { color: black; font-family:Garamond, serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 10pt; }
        </style>
    </head>
    <body>
        <p style="text-indent: 0pt;text-align: left;margin-top:323px">
            <br/></p>
        <h1 style="margin-top:323px;padding-top: 2pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">ПРЕЗЕНТАЦИЯ</h1>
        <p class="s1" style="width: 300px;padding-top: 15pt;padding-left: 5pt;text-indent: 0pt;line-height: 133%;text-align: left;"><?= $pitch->title?></p>
        <pagebreak>
        <?php
        $i = 1;
        foreach ($solutions as $solution):?>
        <table>
            <?php
            if (isset($solution->images['solution_pdfSummary']['filename'])) {
                $images = [$solution->images['solution_pdfSummary']];
            } elseif (isset($solution->images['solution_pdfSummary']['0'])) {
                $images = $solution->images['solution_pdfSummary'];
            } else {
                if (isset($solution->images['solution_leftFeed']['filename'])) {
                    $images = [$solution->images['solution_leftFeed']];
                } elseif (isset($solution->images['solution_leftFeed']['0'])) {
                    $images = $solution->images['solution_leftFeed'];
                } else {
                    $images = [];
                    continue;
                }
            }
            foreach ($images as $index => $image):?>
            <tr>
                <td width="470" <?php if ($index > 0):?>colspan="2"<?php endif ?>>
                    <img width="464" alt="image" src="<?= $image['filename'] ?>"/>
                </td>
                <?php if ($index === 0):?>
                <td style="vertical-align: top; padding-left: 10px">
                    <h1 style="padding-top: 1pt;text-indent: 0pt;text-align: right;">#<?= $i?></h1>
                    <p style="padding-top: 4pt;padding-left: 368pt;text-indent: 0pt;line-height: 109%;text-align: left;"><?= $pitch->title?></p>
                </td>
                <?php endif ?>
            </tr>
            <tr><td height="10" colspan="2"></td></tr>
            <?php endforeach ?>
        </table>
        <pagebreak>
        <?php $i++; endforeach ?>
        <table>
            <?php
            $count = count($solutions);
            $i = 1;
            $k = 1;
            foreach ($solutions as $solution):
                if (isset($solution->images['solution_pdfSummary']['filename'])) {
                    $imageFileName = $solution->images['solution_pdfSummary']['filename'];
                } elseif (isset($solution->images['solution_pdfSummary']['0']['filename'])) {
                    $imageFileName = $solution->images['solution_pdfSummary'][0]['filename'];
                } else {
                    if (isset($solution->images['solution_leftFeed']['filename'])) {
                        $imageFileName = $solution->images['solution_leftFeed']['filename'];
                    } elseif (isset($solution->images['solution_leftFeed']['0']['filename'])) {
                        $imageFileName = $solution->images['solution_leftFeed'][0]['filename'];
                    } else {
                        continue;
                    }
                }
                $images[$k] = $imageFileName;
                $counter[$k] = $i;
                if (($i === $count) && ($k % 2) !== 0):
                    ?>
                    <tr>
                        <td>
                            <img width="336" height="224" alt="image" src="<?php echo $images[$k]?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td><p style="padding-top: 2pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">#<?= $counter[$k]?></p></td>
                    </tr>
                    <tr><td colspan="3" height="20"></td></tr>
                <?php
                endif;
                if (($k % 2) === 0):
                ?>
            <tr>
                <td>
                    <img width="336" height="224" alt="image" src="<?php echo $images[$k-1]?>"/>
                </td>
                <td width="30"></td>
                <td>
                    <img width="336" height="224" alt="image" src="<?php echo $images[$k]?>"/>
                </td>
            </tr>
            <tr>
                <td><p style="padding-top: 2pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">#<?= $counter[$k-1]?></p></td>
                <td width="30"></td>
                <td><p style="padding-top: 2pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">#<?= $counter[$k]?></p></td>
            </tr>
            <tr><td colspan="3" height="20"></td></tr>
            <?php
                $k = 0;
                    endif;
                    $i++; $k++; endforeach;?>
        </table>
        <pagebreak>
        <p style="text-indent: 0pt;text-align: left;"><br/></p>
        <h1 style="margin-top:323px;padding-top: 2pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">СПАСИБО!</h1>
    </body>
</html>
