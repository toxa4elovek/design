<?php
namespace app\extensions\helper;

class Og extends \lithium\template\Helper
{

    /**
     * Метод возвращяет open graph метатег og:image
     *
     * @param $imageUrl
     * @return mixed
     */
    public function getOgImage($imageUrl)
    {
        $defaultImageUrl = 'https://www.godesigner.ru/img/fb_icon.jpg';
        if (empty($imageUrl)) {
            $imageUrl = $defaultImageUrl;
        } else {
            if (!preg_match('/^http/', $imageUrl)) {
                $imageUrl = 'https://www.godesigner.ru' . $imageUrl;
            }
        }
        $template = '<meta property="og:image" content="{image}"/>';
        return str_replace('{image}', $imageUrl, $template);
    }

    /**
     * Метод возвращяет open graph метатег og:title
     *
     * @param $title
     * @return mixed
     */
    public function getOgTitle($title)
    {
        $defaultTitle = 'Логотип, сайт и дизайн: выбирай из идей, а не портфолио';
        if (empty($title)) {
            $title = $defaultTitle;
        }
        $template = '<meta property="og:title" content="{title}"/>';
        return str_replace('{title}', htmlspecialchars($title), $template);
    }

    /**
     * Метод возвращяет open graph метатег og:title
     *
     * @param $description
     * @return mixed
     */
    public function getOgDescription($description)
    {
        $defaultTitle = 'Логотип, сайт и дизайн от всего креативного интернет сообщества';
        if (empty($description)) {
            $description = $defaultTitle;
        } else {
            if (mb_strlen($description, 'UTF-8') > 100) {
                $description = mb_substr($description, 0, 100, 'UTF-8') . '...';
            }
            $description = str_replace('"', '\'', str_replace("\n\r", '', str_replace('&nbsp;', ' ', strip_tags($description))));
            $description = trim(preg_replace('/\s+/', ' ', $description));
        }
        $template = '<meta property="og:description" content="{description}"/>';
        return str_replace('{description}', $description, $template);
    }

    /**
     * Метод возвращяет красивый текущий адрес сайта в метатеге og:url
     *
     * @return string
     */
    public function getOgUrl()
    {
        return '<meta property="og:url" content="' . 'https://www.godesigner.ru' . $_SERVER['REQUEST_URI'] . '"/>';
    }
}
