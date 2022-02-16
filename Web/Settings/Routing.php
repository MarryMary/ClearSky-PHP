<?php
use Clsk\Elena\Router\StarNavigator;

StarNavigator::Get("/", function(){return Viewer("Hello");});