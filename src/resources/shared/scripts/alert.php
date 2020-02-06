<?php

    use DynamicalWeb\HTML;

    /**
     * Renders an Alert
     *
     * @param string $text
     * @param string $type
     * @param string $icon
     */
    function render_alert(string $text, string $type, string $icon)
    {
        print("<div class=\"alert animated flipInX alert-$type alert-colored\" role=\"alert\">");
        print("<i class=\"mdi mdi-$icon\"></i> ");
        HTML::print($text);
        print("</div>");
    }