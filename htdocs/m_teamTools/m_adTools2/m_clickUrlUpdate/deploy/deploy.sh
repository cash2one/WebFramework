#!/bin/bash

click_src=https://dev.corp.youdao.com/svn/outfox/products/ad/ead/tags/click_impr_req_cut_pre0@476580

rm -rf project
svn co $click_src project
cp ./ClickInfo.java project/src/java/outfox/ead/click

cd project
ant resolve
ant compile
