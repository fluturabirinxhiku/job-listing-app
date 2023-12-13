<?php

/**
 * Get the base path
 * 
 * @param string $path
 * @return string 
 */
function basePath($path = '')
{
    return __DIR__ . '/' . $path;
}

/**
 * Load a view
 * 
 * @param string $name
 * @param array $data
 * @return void 
 */
function loadView($name, $data = [])
{
    $viewPath = basePath("views/{$name}.view.php");
    if (file_exists($viewPath)) {
        extract($data);
        require $viewPath;
    } else {
        echo "View {$name} not found";
    }
}

/**
 * Load a partial
 * 
 * @param string $name
 * @return void 
 */
function loadPartial($name)
{
    $partialPath = basePath("views/partials/{$name}.php");
    if (file_exists($partialPath)) {
        require $partialPath;
    } else {
        echo "Partial {$name} not found";
    }
}