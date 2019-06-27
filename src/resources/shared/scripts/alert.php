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
        print("<div class=\"alert alert-$type alert-dismissible fade show\" role=\"alert\">");
        print("<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">");
        print("<span aria-hidden=\"true\">×</span>");
        print("</button>");
        print("<i class=\"mdi mdi-$icon\"></i>");
        HTML::print($text);
        print("</div>");
    }