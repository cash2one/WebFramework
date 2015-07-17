#!/bin/bash

rm -rf project
svn co https://dev.corp.youdao.com/svn/outfox/products/ad/ead/tags/click-anti-arch-opt-pre5@461887 project
cp ShowClickInfo.java /disk2/qatest/svn_code/qa/WebFramework/htdocs/m_miniTools/m_clickKParser/click_code/project/src/java/outfox/ead/click/
cp ClassifyClick.java /disk2/qatest/svn_code/qa/WebFramework/htdocs/m_miniTools/m_clickKParser/click_code/project/src/java/outfox/ead/click/

cd project
ant resolve
ant compile
