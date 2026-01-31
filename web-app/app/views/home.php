<?php

return function (string $title, string $content): string {
    return layout(<<<HTML
        <main>
        <h1>{$title}</h1>
        <p>{$content}</p>
        </main>
HTML);
};
