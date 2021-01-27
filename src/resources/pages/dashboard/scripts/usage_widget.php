<?php

    use DynamicalWeb\HTML;

    /**
     * Calculates the percentage
     *
     * @param int $input
     * @param int $total
     * @return int
     */
    function calculatePercentage(int $input, int $total): int
    {
        if($total > 0)
        {
            return ($input / $total) * 100;
            //return round($input * ($total / 100), 2);
        }

        return 0;
    }


    /**
     * Generates a usage widget
     *
     * @param int $current_usage
     * @param int $max_usage
     * @param string $name
     * @param string $color
     */
    function generateUsageWidget(int $current_usage, int $max_usage, string $name, string $color)
    {
        $class = "progress-bar";

        if($max_usage > 0 && (($current_usage / $max_usage) * 100) >= 80)
        {
            $color = "#ffc107";
            $class = "progress-bar progress-bar-striped";
        }

        if($max_usage > 0 && (($current_usage / $max_usage) * 100) == 100)
        {
            $color = "#dc3545";
            $class = "progress-bar progress-bar-striped progress-bar-animated";
        }

        ?>
        <div class="mx-2 my-1">
            <p class="m-b-5">
                <span><?PHP HTML::print($name); ?></span>
                <span class="pull-right">
                    <?PHP
                        if($max_usage > 0)
                        {
                            HTML::print($current_usage . "/" . $max_usage);
                        }
                        else
                        {
                            HTML::print($current_usage);
                        }
                    ?>
                </span>
            </p>
            <div class="progress m-b-10" style="height: 5px;">
                <div class="<?PHP HTML::print($class); ?>" role="progressbar" style="background-color: <?PHP HTML::print($color, false); ?>; width: <?PHP HTML::print(calculatePercentage($current_usage, $max_usage)); ?>%;" aria-valuenow="<?PHP HTML::print($current_usage); ?>" aria-valuemin="0" aria-valuemax="<?PHP HTML::print($max_usage); ?>"></div>
            </div>
        </div>
        <?php
    }