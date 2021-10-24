<?php

/**
 * Обрезает переданный текст если количество символов превышает максимально допустимое.
 *
 * Примеры:
 * 1) truncateContent("Американский драматический сериал в жанре фэнтези, является адаптацией
 *                  цикла романов «Песнь Льда и Пламени». Создатели сериала — сценаристы Дэвид
 *                  Беньофф и Дэн Вайс, съёмки ведутся в Северной Ирландии, Хорватии, Исландии,
 *                  Испании, Марокко и на Мальте. Всего сериал включает 73 серии, объединённые
 *                  в 8 сезонов. Премьера первого сезона в США состоялась на канале HBO 17 апреля
 *                  2011 года. Сериал отмечен множеством наград. Способствовал популяризации Джорджа
 *                  Мартина как писателя и придуманного им мира.", 400);
 * result = ["content" => "Американский драматический сериал в жанре фэнтези, является адаптацией цикла
 *                         романов «Песнь Льда и Пламени». Создатели сериала — сценаристы Дэвид Беньофф
 *                         и Дэн Вайс, съёмки ведутся в Северной Ирландии, Хорватии, Исландии, Испании,
 *                         Марокко и на Мальте. Всего сериал включает 73 серии, объединённые в 8 сезонов.
 *                         Премьера первого сезона в США состоялась на канале HBO 17 апреля 2011 года.
 *                         Сериал отмечен...",
 *         "truncated" => true]
 *
 * 2) truncateContent("Не могу дождаться начала финального сезона своего любимого сериала!");
 * result = ["content" => "Не могу дождаться начала финального сезона своего любимого сериала!",
 *         "truncated" => false]
 *
 * @param string $content - текст который необходимо обработать.
 * @param int $maxLength - допустимое количество символов в тексте.
 * @return array [
 *  content => string - обработанный текст
 *  truncated => bool - true если текст был обрезан]
 */
function truncateContent(string $content, int $maxLength = 300): array
{
    $resultLength = 0;
    $resultArray = [];
    $contentArray = explode(" ", $content);
    foreach ($contentArray as $contentPart) {
        $resultLength += mb_strlen($contentPart);
        $resultArray[] = $contentPart;
        if ($resultLength >= $maxLength) {
            break;
        }
        $resultLength++;
    }
    $truncated = count($contentArray) !== count($resultArray);
    $result = $truncated ? implode(" ", $resultArray) . "..." : implode(" ", $resultArray);

    return [
        "content" => $result,
        "truncated" => $truncated,
    ];
}

/**
 * Возвращает описание длительности существования поста.
 *
 * Примеры:
 * 1) getTimeAgo(new DateTime("2021-09-23 15:34:40"));
 * result = "19 минут назад"
 *
 * @param DateTime $created_date - дата создания поста.
 * @return string "19 минут назад"
 */
function getTimeAgo(DateTime $created_date): string
{
    $current_date = date_create();
    $diff = date_diff($current_date, $created_date);

    if ($diff->y > 0) { // больше года
        $date_count = $diff->y;
        $format = ['год', 'года', 'лет'];
    } elseif ($diff->days > 35) { // больше 5 недель
        $date_count = $diff->m;
        $format = ['месяц', 'месяца', 'месяцев'];
    } elseif ($diff->days > 7) { // больше 7 дней
        $date_count = ceil($diff->days / 7);
        $format = ['неделя', 'недели', 'недель'];
    } elseif ($diff->d > 0) { // больше 24 часов
        $date_count = $diff->d;
        $format = ['день', 'дня', 'дней'];
    } elseif ($diff->h > 0) { // больше часа
        $date_count = $diff->h;
        $format = ['час', 'часа', 'часов'];
    } else {
        $date_count = $diff->i;
        $format = ['минута', 'минуты', 'минут'];
    }

    $dateType = get_noun_plural_form($date_count, ...$format);

    return $date_count . " " . $dateType . " назад";
}

/**
 * Функция добавляет дату создания, время существования и заголовок каждому посту в массиве,
 * так же переопределяет имена полей в ассоциативном массиве и задает тип поста.
 *
 * Пример:
 * normalizePosts([[
 *       "id" => "3",
 *       "title" => "Моя мечта",
 *       "content" => "coast-medium.jpg",
 *       "author" => "Лариса",
 *       "login" => "Лариса",
 *       "type_id" => "3",
 *       "views_count" => "50",
 *       "avatar_path" => "userpic-larisa-small.jpg",
 *       ],
 *       [
 *       "id" => "4",
 *       "title" => "Лучшие курсы",
 *       "content" => "www.htmlacademy.ru",
 *       "author" => "Владик",
 *       "login" => "Владик",
 *       "type_id" => "5",
 *       "views_count" => "180",
 *       "avatar_path" => "userpic.jpg",
 *   ]]);
 * result = [[
 *          "id" => "3",
 *          "title" => "Моя мечта",
 *          "type" => "post-photo",
 *          "contain" => "coast-medium.jpg",
 *          "user_name" => "Лариса",
 *          "avatar" => "userpic-larisa-small.jpg",
 *          "views_count" => "50",
 *          "created_date" => "2021-09-23 15:31:40",
 *          "time_ago" => "3 минуты назад",
 *          "date_title" => "23.09.2021 15:31",
 *       ],
 *       [
 *          "id" => "4",
 *          "title" => "Лучшие курсы",
 *          "type" => "post-link",
 *          "contain" => "www.htmlacademy.ru",
 *          "user_name" => "Владик",
 *          "avatar" => "userpic.jpg",
 *          "views_count" => "180",
 *          "created_date" => "2021-09-23 13:34:40",
 *          "time_ago" => "2 часа назад",
 *          "date_title" => "23.09.2021 13:34",
 *   ]]
 *
 * @param array $posts<array{id: string, title: string, content: string, author: string, login: string, type_id:string, views_count: string, avatar_path: string}>
 * @param array $post_types<array{id: string, name: string, icon_class: string}>
 * @return array<array{id: string, title: string, type: string, contain: string, user_name: string, avatar: string, views_count:string, created_date:string, time_ago: string, date_title: string}>
 */
function normalizePosts(array $posts, array $post_types): array
{
    $result = [];

    foreach ($posts as $post) {
        $result[] = normalizePost($post, $post_types);
    }

    return $result;
}

function normalizePost(array $post, array $post_types): array {
    if ($post === []) {
        return $post;
    }

    $created_date = date_create($post["created_date"]);
    $type_key = array_search((string) $post["type_id"], array_column($post_types, "id"), true);

    return [
        "id" => (string) $post["id"],
        "title" => $post["title"],
        "contain" => $post["content"],
        "author" => $post["author"],
        "user_name" => $post["login"],
        "avatar" => $post["avatar_path"],
        "views_count" => $post["views_count"],
        "created_date" => $post["created_date"],
        "type" => "post-" . $post_types[$type_key]["icon_class"],
        "time_ago" => getTimeAgo($created_date),
        "date_title" => date_format($created_date, "d.m.Y H:i"),
    ];
}

/**
 * Функция переопределяет имена полей в ассоциативном массиве.
 *
 * Пример:
 * normalizePostTypes([[
 *       "id" => "2",
 *       "name" => "Ссылка",
 *       "icon_class" => "link",
 *       ],
 *       [
 *       "id" => "3",
 *       "name" => "Картинка",
 *       "icon_class" => "photo",
 *   ]]);
 * result = [[
 *       "id" => "2",
 *       "name" => "Ссылка",
 *       "icon_class" => "link",
 *       ],
 *       [
 *       "id" => "3",
 *       "name" => "Картинка",
 *       "icon_class" => "photo",
 *   ]]
 *
 * @param array $post_types <array{id: string, name: string, icon_class: string}>
 * @return array<array{id: string, name: string, icon_class: string}>
 */
function normalizePostTypes(array $post_types): array
{
    $result = [];

    foreach ($post_types as $post_type) {
        $result[] = [
            "id" => (string) $post_type["id"],
            "name" => $post_type["name"],
            "icon_class" => $post_type["icon_class"],
        ];
    }

    return $result;
}

function getFirstTypeId(array $post_types): string {
    return $post_types[0] ? $post_types[0]["id"] : "";
}

function checkFilling(string $field_name, string $field_title): string {
    $error_message = "";
    if (isset($_POST[$field_name]) && $_POST[$field_name] === "") {
        $error_message = $field_title . ". Это поле должно быть заполнено.";
    }

    return $error_message;
}

function addError(array $errors, string $error_message, string $field_name): array {
    if ($error_message !== "") {
        $errors[$field_name][] = $error_message;
    }

    return $errors;
}

function checkTags($pattern, $tags, $errors): array
{
    foreach ($tags as $tag) {
        if (!preg_match($pattern, $tag)) {
            $errors = addError($errors, "Неверный формат тега " . $tag, "tags");
        }
    }

    return $errors;
}

function checkPictureType($pattern, $type): string {
    $error = "";
    if (!preg_match($pattern, $type)) {
        $error = "Неверный формат файла";
    }

    return $error;
}

function getFilePath($full_path, $file_name): string {
    return $full_path . basename($file_name);
}

function downloadPictureFile($file_path, $uploads_dir, $file_name, $temp_path, $errors): string {
    $result = "";

    if (count($errors) === 0 &&
        move_uploaded_file($temp_path, $file_path)) {
        $result = $uploads_dir . $file_name;
    }

    return $result;
}

function downloadContent($file_path, $uploads_dir, $file_name, $data, $errors): string {
    $result = "";

    if (count($errors) === 0 &&
        file_put_contents($file_path, $data)) {
        $result = $uploads_dir . $file_name;
    }

    return $result;
}

function getValidateURL($url, $errors): string {
    $result = "";

    if (count($errors) === 0) {
        $result = filter_var($url, FILTER_VALIDATE_URL);
    }

    return $result;
}

function checkURL($url): string {
    if ($url === "") {
        return "Не заполнено поле ссылка";
    }

    return filter_var($url, FILTER_VALIDATE_URL) ? "" : "Неверная ссылка";
}

function checkYoutubeURL($url): string {
    if ($url === "") {
        $result = "Не заполнено поле ссылка youtube";
    } else if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $result = "Неверная ссылка на видео";
    } else {
        $result = check_youtube_url($url);
    }

    return $result;
}

function addPictureFile($web_name, $result, $full_path, $uploads_dir): array {
    if ($_FILES[$web_name]["error"] === 0) {
        $picture = $_FILES[$web_name];
        $result["errors"] = addError(
            $result["errors"],
            checkPictureType("/image\/(jpg|jpeg|png|gif)/i", $picture["type"]),
            $web_name
        );
        $file_path = getFilePath($full_path, $picture["name"]);
        $result["content"] = downloadPictureFile(
            $file_path,
            $uploads_dir,
            $picture["name"],
            $picture["tmp_name"],
            $result["errors"]
        );
    }

    return $result;
}

function addPictureURL($web_name, $result, $field, $full_path, $uploads_dir): array {
    if ($result["content"] === "") {
        if ($_POST[$web_name] === "") {
            $result["errors"] = addError($result["errors"], "Необходимо выбрать изображение с компьютера или указать ссылку из интернета.", $web_name);
            return $result;
        }

        $picture_url = filter_var($_POST[$web_name], FILTER_VALIDATE_URL);
        $photo_info = pathinfo($picture_url);
        $result["errors"] = addError($result["errors"], checkPictureType("/(jpg|jpeg|png|gif)/i", $photo_info["extension"] ?? ""), $web_name);

        if (count($result["errors"]) === 0) {
            $download_photo = file_get_contents($picture_url);
            $file_path = getFilePath($full_path, $photo_info["basename"]);
            $result["content"] = downloadContent($file_path, $uploads_dir, $photo_info["basename"], $download_photo, $result["errors"]);
            $result[$field] = $picture_url;
        }
    }

    return $result;
}

function addWebsite($web_name, $result, $field): array {
    $website = $_POST[$web_name];
    $result["errors"] = addError($result["errors"], checkURL($website), $web_name);
    $result["content"] = getValidateURL($website, $result["errors"]);
    $result[$field] = $result["content"];

    return $result;
}

function addVideoURL($web_name, $result, $field): array {
    $video_url = $_POST[$web_name];
    $result["errors"] = addError($result["errors"], checkYoutubeURL($video_url), $web_name);
    $result["content"] = getValidateURL($video_url, $result["errors"]);
    $result[$field] = $result["content"];

    return $result;
}

function addTextContent($web_name, $result, $field, $required_empty_filed): array {
    $result["errors"] = addError($result["errors"], checkFilling($web_name, $required_empty_filed[$web_name]), $web_name);
    $result[$field] = $_POST[$web_name] ?? "";

    return $result;
}

function addTags($field, $result): array {
    if (isset($_POST[$field]) && $_POST[$field] !== "") {
        $result[$field] = explode(" ", $_POST[$field]);
        $result["errors"] = checkTags("/^#[A-Za-zА-Яа-яËё0-9]{1,19}$/", $result[$field], $result["errors"]);
    }

    return $result;
}
