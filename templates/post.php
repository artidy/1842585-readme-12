<?php

/**
 * Шаблон отдельного поста страницы
 * @var $post array{id: string, title: string, type: string, contain: string, user_id:string, user_name: string, avatar: string, views_count:string, created_date:string, time_ago: string, date_title: string} - пост пользователя
 * @var $user array - информация об авторизованном пользователе
 * @var $user_info string - шаблон информации о пользователе
 * @var $comments array - комментарии к данному посту
 * @var $show_all_comments - показать все комментарии
 * @var $errors array - ошибки заполнения комментария
 */

?>

<div class="container">
    <h1 class="page__title page__title--publication"><?= htmlspecialchars($post['title']) ?></h1>
    <section class="post-details">
        <h2 class="visually-hidden">Публикация</h2>
        <div class="post-details__wrapper <?= $post["type"] ?>">
            <div class="post-details__main-block post post--details">
                <?php
                    $template_post = include_template("/parts/post/" . $post["type"] . ".php", [
                        "id" => $post["id"],
                        "title" => $post["title"],
                        "content" => $post["contain"],
                        "author" => $post["user_name"],
                        "is_details" => true,
                        "show_title" => false,
                        "is_video_control" => false,
                    ]);
                    print($template_post);
                ?>
                <div class="post__indicators">
                    <?php
                        $template_indicators = include_template("/parts/post/indicators.php", [
                            "post" => $post,
                        ]);
                        print($template_indicators);
                    ?>
                    <span class="post__view">500 просмотров</span>
                </div>
                <ul class="post__tags">
                    <li><a href="#">#nature</a></li>
                    <li><a href="#">#globe</a></li>
                    <li><a href="#">#photooftheday</a></li>
                    <li><a href="#">#canon</a></li>
                    <li><a href="#">#landscape</a></li>
                    <li><a href="#">#щикарныйвид</a></li>
                </ul>
                <div class="comments">
                    <form class="comments__form form" action="/post.php" method="post">
                        <input type="hidden" value="<?= htmlspecialchars($post["id"]) ?>" name="post_id">
                        <div class="comments__my-avatar">
                            <img class="comments__picture" src="<?= htmlspecialchars($user["avatar"]) ?>" alt="Аватар пользователя">
                        </div>
                        <div class="form__input-section <?= count($errors) > 0 ? "form__input-section--error" : "" ?>">
                            <textarea id="comment" name="comment" class="comments__textarea form__textarea form__input" placeholder="Ваш комментарий"></textarea>
                            <label for="comment" class="visually-hidden">Ваш комментарий</label>
                            <button class="form__error-button button" type="button">!</button>
                            <div class="form__error-text">
                                <h3 class="form__error-title">Ошибка валидации</h3>
                                <?php foreach ($errors as $error): ?>
                                    <p class="form__error-desc"><?= htmlspecialchars($error) ?></p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button class="comments__submit button button--green" type="submit">Отправить</button>
                    </form>
                    <div class="comments__list-wrapper">
                        <ul class="comments__list">
                            <?php foreach ($comments as $comment): ?>
                                <li class="comments__item user">
                                    <div class="comments__avatar">
                                        <a class="user__avatar-link" href="/profile.php?user_id=<?= htmlspecialchars($comment["user_id"]) ?>">
                                            <img class="comments__picture" src="<?= htmlspecialchars($comment["avatar"]) ?>"
                                                 alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="comments__info">
                                        <div class="comments__name-wrapper">
                                            <a class="comments__user-name" href="/profile.php?user_id=<?= htmlspecialchars($comment["user_id"]) ?>">
                                                <span><?= htmlspecialchars($comment["user_name"]) ?></span>
                                            </a>
                                            <time class="comments__time" title="<?= htmlspecialchars($post["date_title"]) ?>" datetime="<?= htmlspecialchars($comment["comment_at"]) ?>"><?= htmlspecialchars($comment["time_ago"]) ?></time>
                                        </div>
                                        <p class="comments__text">
                                            <?= htmlspecialchars($comment["comment"]) ?>
                                        </p>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if ($post["comments_count"] > 2 && !$show_all_comments): ?>
                            <a class="comments__more-link" href="/post.php?post_id=<?= htmlspecialchars($post["id"]) ?>&show_all_comments=true">
                                <span>Показать все комментарии</span>
                                <sup class="comments__amount"><?= htmlspecialchars($post["comments_count"]) ?></sup>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="post-details__user user">
                <div class="post-details__user-info user__info">
                    <div class="post-details__avatar user__avatar">
                        <a class="post-details__avatar-link user__avatar-link" href="/profile.php?user_id=<?= htmlspecialchars($post["user_id"]) ?>">
                            <img class="post-details__picture user__picture"
                                 src="<?= htmlspecialchars($post["avatar"]) ?>"
                                 alt="Аватар пользователя">
                        </a>
                    </div>
                    <div class="post-details__name-wrapper user__name-wrapper">
                        <a class="post-details__name user__name" href="/profile.php?user_id=<?= htmlspecialchars($post["user_id"]) ?>">
                            <span><?= htmlspecialchars($post["user_name"]) ?></span>
                        </a>
                        <time class="post-details__time user__time"
                              title="<?= htmlspecialchars($post["date_title"]) ?>" datetime="<?= htmlspecialchars(
                            $post["created_date"]
                        ) ?>"><?= htmlspecialchars(
                            $post["time_ago"]
                        ) ?></time>
                    </div>
                </div>
                <?php print($user_info); ?>
            </div>
        </div>
    </section>
</div>
