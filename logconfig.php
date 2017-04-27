<?php
error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
error_reporting(E_ALL &  ~( E_DEPRECATED | E_USER_DEPRECATED | E_USER_NOTICE | E_STRICT ));