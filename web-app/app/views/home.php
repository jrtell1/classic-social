<?php

return function (string $title, string $content): string {
    return layout(<<<HTML
        <main>
        <h1>{e($title)}</h1>
        <p>{e($content)}</p>
        </main>
HTML);
};
