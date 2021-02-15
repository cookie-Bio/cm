<?php


namespace Manager\Commands;


use Exception;
use Manager\Models\ChatsQuery;
use Manager\Models\Utils;

trait Chat
{

    /**
     * Зарегистрировать чат
     */
    public function chatRegistration()
    {
        try {
            $this->vk->isAdmin(-$this->vk->getVars('group_id'), $this->vk->getVars('peer_id'));
        } catch (Exception $e) {
            if ($e->getCode() === 0) $this->vk->reply('Ты меня обманул!!!');
            return;
        }

        $this->db->createChatRecord($this->vk->getVars('chat_id'))
            ? $this->vk->reply('верю-верю') : $this->vk->reply('А мы раньше где-то встречались?');
    }

    /**
     * Показать все настройки
     */
    public function snowAllSettings()
    {
        $settings = $this->db->showAllSettings();
        $text['action'] = "default:\n";
        $text['penalty'] = "penalty:\n";
        $text['specific'] = "specific:\n";

        foreach ($settings as $setting => $key) {
            foreach ($key as $value) {
                if (!isset($value['default'])) $default = '';
                elseif (is_array($value['default'])) $default = implode(", ", $value['default']);
                else $default = $value['default'];

                if ($setting === ChatsQuery::ACTION) $text['action'] .= $value['description'] . "\nДействие - " . Utils::intToStringAction($value['action']) . PHP_EOL . PHP_EOL;
                if ($setting === ChatsQuery::PENALTY) $text['penalty'] .= $value['description'] . ' - ' . $default . "\nВ случае нарушения - " . Utils::intToStringAction($value['action']) . PHP_EOL . PHP_EOL;
                if ($setting === ChatsQuery::SPECIFIC) $text['specific'] .= $value['description'] . ' - ' . $default . "\nВ случае нарушения - " . Utils::intToStringAction($value['action']) . PHP_EOL . PHP_EOL;
            }
        }
        $this->print(implode("\n", $text));
//        $text .= $settings['mute']['description'] . ': ' . $settings['mute']['default'] . PHP_EOL;

    }

    /**
     * Отправить каллбек кнопки с настройками с возможностью их переключать
     */
    public function sendCallbackSettings()
    {
        $button = null;
        $i = 0;
        foreach ($this->db->showAllSettings() as $setting => $key) {
            foreach ($key as $value) {
                $button[$i][] = $this->vk->buttonCallback($value['description'], $value['action'] ? 'green' : 'red', ['gui' => 'settings', 'action' => key($key)]);
                $i++;
                if (count($button) === 5) {
                    $button[$i][] = $this->vk->buttonCallback('⏩', 'white', ['gui' => 'settings', 'action' => 'next']);
                    break(2);
                }
            }
        }
//        $button[$i][] = $this->vk->buttonCallback('⏪', 'white', ['gui_settings' => 'info']);

        Utils::var_dumpToStdout($button);

        $this->vk
            ->msg('🔧 Gui Settings')
            ->kbd($button, true)
            ->send();

        $b[] = $this->vk->buttonText('⏩', 'white', ['command' => 'not_supported_button']);
        $this->vk
            ->msg('🔧')
            ->kbd([$b], true)
            ->send();
    }
    //TODO Написать изменение настроек гуи
    //TODO написать регулярку для варна за ссылки
    //TODO написать хранилище для спам слов
}