<?php
    use DynamicalWeb\HTML;
?>
<footer class="footer bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="float-right pull-none">
                    <ul class="list-inline social">
                        <li class="list-inline-item reference-link">
                            <a href="#">
                                <small>Terms of Service</small>
                            </a>
                        </li>
                        <li class="list-inline-item reference-link">
                            <a href="#">
                                <small>Privacy Policy</small>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="pull-none">
                    <p class="copy-rights">2017 - <?PHP HTML::print(date('Y')); ?> &copy; Intellivoid Technologies. All Rights Reserved</p>
                </div>
            </div>
        </div>
    </div>
</footer>