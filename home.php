<?php

Session::getInstance()->requireLogin();
URL::redirect(URL::link('/lms'));
