#!/bin/bash

dir_to_be_archive=/disk2/qatest/svn_code/qa/WebFramework/htdocs
remote_host=nc111
remote_dir=/disk4/tts_zhangpei_test

rsync -ravuq $dir_to_be_archive test@$remote_host:$remote_dir
