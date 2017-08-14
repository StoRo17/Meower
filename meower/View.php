<?php

namespace Meower;

use Meower\Exceptions\View\TemplateFileNotFoundException;

class View
{
    /**
     * Render this view inserting arguments into it.
     *
     * @param string $template
     * @param array $args
     * @return string
     * @throws TemplateFileNotFoundException
     * @throws \Exception
     */
    public function render($template, $args = [])
    {
        $templatePath = VIEWS_PATH . '/' . $template . '.php';

        if (!is_file($templatePath)) {
            throw new TemplateFileNotFoundException("Template {$template} not found in {$templatePath}");
        }

        extract($args);
        ob_start();
        ob_implicit_flush(0);

        try {
            require $templatePath;
        } catch (\Exception $err) {
            ob_end_clean();
            throw $err;
        }

        return ob_get_clean();
    }
}
