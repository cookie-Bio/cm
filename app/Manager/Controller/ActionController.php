<?php

namespace Manager\Controller;

use Manager\Models\Utils;

class ActionController extends Controller
{
    /**
     * обработка action (message\\action)
     * @param array $action
     * @return void
     */
    public static function handler(array $action): void
    {
        $type = $action['type'];
        $member_id = $action['member_id'];
        if (method_exists(self::class, $type)) self::$type((int)$member_id);
    }

    /**
     * Пользователь присоединился к беседе
     * @param $id
     */
    private static function chat_invite_user(int $id)
    {
        /**
         * Если добавили бота
         * Приветственное сообщение + предложение выдать админку
         */
        if ($id == -self::$vk->getVars('group_id')) {
            self::$vk->reply("Привет! Я опенсорс чат менеджер😊\nБуду рад служить тебе ~мой пользователь|" . self::$vk->getVars('user_id') . "~");
            sleep(2);

            $buttons[] = self::$vk->buttonText('Я выдал админку', 'green', ['chat' => 'registration']);
            $buttons[] = self::$vk->buttonOpenLink('https://vk.com/@labile.paranoid-kak-dobavit-bota-v-besedu-i-dat-emu-prava-administratora', 'А как блин?');
            self::$vk
                ->msg("🛠 Для начала наших прекрасных отношений выдай мне права администратора")
                ->addImg('https://sun9-66.userapi.com/impg/5lLDD_qo40mfj7h--VbNcns8TnX7ov14Mkc0ww/xZtQcjToEvE.jpg?size=600x400&quality=96&proxy=1&sign=1c61751b37e889ffc011454632d19bd5&type=album')
//                ->addImg(Utils::snowCat())
                ->kbd([$buttons], true)
                ->send();

        } elseif (self::$db->statusWelcomeMessage() === true) {
            $welcome_msg = self::$db->snowWelcomeMessage();
            if (!is_bool($welcome_msg)) self::$vk->reply($welcome_msg);
        }

    }

    /**
     * Пользователь присоединился к беседе по инвайт-ссылке
     * @param $id
     * @return void
     */
    private static function chat_invite_user_by_link(int $id)
    {
        self::chat_invite_user($id);
    }

    /**
     * Пользователь покинул беседу, либо был исключён кикнули
     * @param $id
     * @return void
     */
    private static function chat_kick_user(int $id)
    {
        self::$vk->reply("~!fn|$id~ пока-пока!");
    }

    /**
     * Обновлена аватарка
     * @param $id
     */
    private static function chat_photo_update(int $id)
    {
    }

    /**
     * Удалена аватарка
     * @param $id
     */
    private static function chat_photo_remove(int $id)
    {
    }

    /**
     * Закреплено сообщение
     * @param $id
     */
    private static function chat_pin_message(int $id)
    {
    }

    /**
     * Откреплено сообщение
     * @param $id
     */
    private static function chat_unpin_message(int $id)
    {
    }

    /**
     * Сделан скриншот
     * @param $id
     */
    private static function chat_screenshot(int $id)
    {
    }
}