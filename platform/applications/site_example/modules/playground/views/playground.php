<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <section class="container">

        <div class="row">

            <div class="col-sm-12">

                <div class="page-header">
                    <h1>The Playground</h1>
                </div>

                <h4>Standalone Pages</h4>

                <ul>
                    <li><a href="<?php echo base_url('non-mvc/demo.php'); ?>">Non-MVC Page Demonstration</a></li>
                    <li><a href="<?php echo base_url('non-mvc/http_build_url_test.php'); ?>">Testing http_build_url()</a></li>
                    <li>
                        <a href="<?php echo base_url('non-mvc/idna.php'); ?>">IDNA Converter Test (PEAR IDNA2)</a>
                        <br />
                        <span class="text-muted">This component might be used by HTMLPurifier, so let us have a look at its test.</span>
                    </li>
                    <li><a href="<?php echo base_url('non-mvc/normalize.css.php'); ?>">Testing normalize.css</a></li>
                </ul>

                <h4>Feature Tests</h4>

                <ul>
                    <li><a href="<?php echo site_url('playground/captcha'); ?>">Captcha Test</a></li>
                </ul>

            </div>

        </div>

    </section>

