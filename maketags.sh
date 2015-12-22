#!/bin/bash
ctags --langmap=php:+.latte --languages=php,html -R . tags
