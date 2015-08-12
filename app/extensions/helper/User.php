<?php
namespace app\extensions\helper;

/**
 * Class User - Хелпер для различных проверок текущего пользователя
 * Взаимодействует с моделью User и Expert
 * @package app\extensions\helper
 */
class User extends \lithium\storage\Session {

    /**
     * @var array Массив для хранения опций и данных из моделей
     */
    protected $_options = array();

    /**
     * Конструктор устанавливает свойства
     */
    public function __construct($config = array()) {
        $defaults = array(
            'userModel' => 'app\models\User',
            'expertModel' => 'app\models\Expert',
            'inflector' => 'app\extensions\helper\NameInflector',
            'defaultAvatarUrl' => '/img/default_small_avatar.png',
        );
        $this->_options = $options = $config + $defaults;
        $this->_options['adminsIds'] = $options['userModel']::getAdminsIds();
        $this->_options['expertsIds'] = $options['expertModel']::getExpertUserIds();
        $this->_options['editorsIds'] = $options['userModel']::getEditorsIds();
        $this->_options['authorIds'] = $options['userModel']::getAuthorsIds();
        $this->_options['feedAuthorsIds'] = $options['userModel']::getFeedAuthorsIds();
    }

    /**
     * Метод определяет, является ли текущий пользователь экспертом
     *
     * @param $expertsIds - Список айдишников эксперта
     * @return bool - Является ли пользователь экспертом
     */
    public function isExpert() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return in_array($this->getId(), $this->_options['expertsIds']);
    }

    /**
     * Метод определяет, является ли текущий пользователь админом
     *
     * @return bool - Является ли пользователь админом
     */
    public function isAdmin() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        if(($this->read('user.isAdmin')) || (in_array($this->getId(), $this->_options['adminsIds']))) {
            return true;
        }
        return false;
    }

    /**
     * Метод определяет, является ли текущий пользователь редактором блога
     *
     * @return bool
     */
    public function isEditor() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return in_array($this->getId(), $this->_options['editorsIds']);
    }

    /**
     * Метод определяет, является ли текущий пользователь редактором блога
     *
     * @return bool
     */
    public function isAuthor() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return in_array($this->getId(), $this->_options['authorIds']);
    }

    /**
     * Метод определяет, является ли текущий пользователь автором ленты новостей
     *
     * @return bool
     */
    public function isFeedWriter() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return in_array($this->getId(), $this->_options['feedAuthorsIds']);
    }

    /**
     * Метод определяет, залогинен ли текущий пользователь
     *
     * @return bool
     */
    public function isLoggedIn() {
        return (bool) $this->read('user');
    }

    /**
     * Метод определяет, является ли текущий пользователь владельцем питча с айди $pitchUserId
     *
     * @param $pitchUserId - айди владельца питча
     * @return bool
     */
    public function isPitchOwner($pitchUserId) {
        return $this->__detectOwnership($pitchUserId);
    }

    /**
     * Метод определяет, является ли текущий пользователь автором решения с айди $solutionUserId
     *
     * @param $solutionUserId
     * @return bool
     */
    public function isSolutionAuthor($solutionUserId) {
        return $this->__detectOwnership($solutionUserId);
    }

    /**
     * Метод определяет, является ли текущий пользователь автором комментария с айди $commentAuthorId
     *
     * @param $commentAuthorId
     * @return bool
     */
    public function isCommentAuthor($commentAuthorId) {
        return $this->__detectOwnership($commentAuthorId);
    }

    /**
     * Метод определяет, является ли текущий пользователь автором поста с айди $postAuthorId
     *
     * @param $commentAuthorId
     * @return bool
     */
    public function isPostAuthor($postAuthorId) {
        return $this->__detectOwnership($postAuthorId);
    }

    /**
     * Метод определяет, нет ли актуального запрета на комментирование у пользователя
     *
     * @return bool
     */
    public function isAllowedToComment() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return strtotime($this->read('user.silenceUntil')) < time();
    }

    /**
     * Метод проверяет, входит ли пользователь через социальные сети
     *
     * @return bool
     */
    public function isSocialNetworkUser() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return (bool) $this->read('user.social');
    }

    /**
     * Метод определяет, есть ли питч с айди $pitchId в избранном у пользователя
     *
     * @param $pitchId
     * @return bool
     */
    public function isPitchFavourite($pitchId) {
        if((!$this->isLoggedIn()) and (!$this->hasFavouritePitches())) {
            return false;
        }
        return in_array($pitchId, $this->read('user.faves'));
    }

    /**
     * Метод проверяет, есть ли у пользовать избранные питчи
     *
     * @return bool
     */
    public function hasFavouritePitches() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return is_array($this->read('user.faves'));
    }

    /**
     * Метод возвращает айди пользователя или false, если он не залогинен
     *
     * @return bool|mixed
     */
    public function getId() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return (int) $this->read('user.id');
    }

    /**
     * Метод возвращает имя пользователя или false, если он не залогинен
     *
     * @return bool|mixed
     */
    public function getFirstname() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return $this->read('user.first_name');
    }

    /**
     * Метод возвращает фамилию пользователя или false, если он не залогинен
     *
     * @return bool|mixed
     */
    public function getLastname() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return $this->read('user.last_name');
    }

    /**
     * Метод возвращает имя и фамилию пользователя, разделленные пробелом
     *
     * @return bool|string
     */
    public function getFullname() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return $this->getFirstname() . ' ' . $this->getLastname();
    }

    /**
     * Метод возвращает email или false, если он не залогинен
     *
     * @return bool|mixed
     */
    public function getEmail() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return $this->read('user.email');
    }
    
    /**
     * Метод возвращает дату создания аккаунта или false, если он не залогинен
     *
     * @return bool|mixed
     */
    public function getCreatedDate() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return $this->read('user.created');
    }

    /**
     * Метод возвращает отформатированное имя в формате "Дмитрий Н."
     * Если даны оба параметра, то вместо текущих данных сессии используются параметры
     *
     * @param null $firstName
     * @param null $lastName
     * @return bool
     */
    public function getFormattedName($firstName = null, $lastName = null) {
        if($this->isAdmin()) {
            if(!is_null($firstName) && !is_null($lastName)) {
                return $firstName . ' ' . $lastName;
            }else {
                return $this->getFirstname() . ' ' . $this->getLastname();
            }
        }
        $inflectorClassName = $this->_options['inflector'];
        if(!is_null($firstName) && !is_null($lastName)) {
            return $inflectorClassName::renderName($firstName, $lastName);
        }
        if(!$this->isLoggedIn()) {
            return false;
        }
        return $inflectorClassName::renderName($this->getFirstname(), $this->getLastname());
    }

    /**
     * Метод возвращает текущие питчи (user.currentpitches) пользователя
     *
     * @return bool|mixed
     */
    public function getCurrentPitches() {
        return $this->__getPitchesFromList('currentpitches');
    }

    /**
     * Метод возвращает текущие питчи для дизайнера (user.currentdesignpitches) пользователя
     *
     * @return bool|mixed
     */
    public function getCurrentDesignersPitches() {
        return $this->__getPitchesFromList('currentdesignpitches');
    }

    /**
     * Метод возвращает количество текущий питчей (user.currentpitches) пользователя
     *
     * @return int
     */
    public function getCountOfCurrentPitches() {
        return $this->__countPitches($this->getCurrentPitches());
    }

    /**
     * Метод возвращает количество текущий питчей для дизайнера (user.currentdesignpitches) пользователя
     *
     * @return int
     */
    public function getCountOfCurrentDesignersPitches() {
        return $this->__countPitches($this->getCurrentDesignersPitches());
    }

    /**
     * Метод возвращает количество непрочитанных постов в блоге
     *
     * @return int
     */
    public function getNewBlogpostCount() {
        return (int) $this->read('user.blogpost.count');
    }

    /**
     * Метод возвращает количество непрочитанных обновлений
     *
     * @return int
     */
    public function getNewEventsCount() {
        return (int) $this->read('user.events.count');
    }

    /**
     * Метод возвращает текущий аватар пользователя - или дефолтный, если он не установлен
     *
     * @return mixed
     */
    public function getAvatarUrl() {
        if(!$avatarUrl = $this->read('user.images.avatar_small.weburl')) {
            return $this->_options['defaultAvatarUrl'];
        }else {
            return $avatarUrl;
        }
    }

    /**
     * Метод возвращает временной интервал, оставшийся до активации дизайнера
     *
     * @return boolean|DateInterval
     */
    public function designerTimeRemain($pitch = false) {
        if(($pitch) && ($pitch->free)) {
            return false;
        }

        if (!$this->isLoggedIn()) {
            return false;
        }

        $userModel = $this->_options['userModel'];
        $timeWait = $userModel::designerTimeWait((int) $this->read('user.id'));

        $datetime1 = new \DateTime();
        $datetime2 = new \DateTime(date('Y-m-d H:i:s', (strtotime($this->read('user.created')) + $timeWait * DAY)));
        $interval = $datetime2->diff($datetime1);

        if ($interval->invert == 0) {
            return false;
        }

        return $interval;
    }

    /**
     * Приватный метод помощник, сравнивает аргумент @model_id с текущим user.id, если установлен
     *
     * @param $model_id
     * @return bool
     */
    private function __detectOwnership($model_id) {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return $model_id == $this->getId();
    }

    /**
     * Приватный метод помощник, получает массив/объекты и возвращает их количество
     *
     * @param $pitches
     * @return int
     */
    private function __countPitches($pitches) {
        if($pitches) {
            return count($pitches);
        }
        return 0;
    }

    /**
     * Приватный метод помощник, пытается получить данные текущих питчий или питчей для дизайнера
     *
     * @param $listType
     * @return bool|mixed
     */
    private function __getPitchesFromList($listType) {
        if(!$this->isLoggedIn()) {
            return false;
        }
        $pitches = $this->read('user.' . $listType);
        if(!is_null($pitches)) {
            return $pitches;
        }
    }

    /**
     * Метод помощник, добавляет "а" к слову, если пол - женский
     *
     * @param $txt
     * @param int $gender
     * @return string
     */
    public function getGenderTxt($txt,$gender=0) {
        // 0 - не установлен
        // 1 - м
        // 2 - ж
        if ($gender > 1) {
            $txt .='а';
        }
        return $txt;
    }

    /**
     * Метод получает индекс пола
     *
     * @return int
     */
    public function getGender() {
        if(!$this->isLoggedIn()) {
            return 0;
        }
        return (int) $this->read('user.gender');
    }

    /**
     * Метод определяет, нужно ли пользователю сменить почту
     */
    public function needToChangeEmail() {
        if(($this->isLoggedIn()) && (preg_match('/@(mail|inbox|list|bk).ru$/', $this->getEmail()))) {
            return true;
        }
        return false;
    }

    /**
     * Метод возвращяет строку - адрес электроной части, где часть адреса скрыта звездочками
     *
     * @return string
     */
    public function getMaskedEmail() {
        if($this->isLoggedIn()) {
            $email = $this->getEmail();
            preg_match('/^(.)(.*)(@.*)/', $email, $matches);
            $partToReplace = $matches[2];
            $partToReplaceLength = mb_strlen($partToReplace, 'UTF-8');
            $maskedPart = str_repeat('*' , $partToReplaceLength);
            $maskedString = str_replace($partToReplace, $maskedPart, $email);
            return $maskedString;
        }
    }

    /**
     * Метод возвращяет баланс текущего пользователя, если он залогинен.
     *
     * @return bool|mixed
     */
    public function getBalance() {
        if($this->isLoggedIn()) {
            return $this->read('user.balance');
        }
        return false;
    }

    /**
     * Метод возвращяет краткое название компании текущего пользователя, если он залогинен.
     *
     * @return bool|mixed
     */
    public function getShortCompanyName() {
        if($this->isLoggedIn()) {
            return $this->read('user.short_company_name');
        }
        return false;
    }

    /**
     * Метод возвращяет подписан ли человек на абонентку или нет
     *
     * @return bool
     */
    public function isSubscriptionActive() {
        if($this->isLoggedIn()) {
            $userModel = $this->_options['userModel'];
            return $userModel::isSubscriptionActive($this->getId());
        }
        return false;
    }

    /**
     * Метод возвращяет дату окончания подписки
     *
     * @param $format
     * @return bool|date
     */
    public function getSubscriptionExpireDate($format = 'd.m.Y H:i:s') {
        if($this->isLoggedIn()) {
            $userModel = $this->_options['userModel'];
            return $userModel::getSubscriptionExpireDate($this->getId());
        }
        return false;
    }

}