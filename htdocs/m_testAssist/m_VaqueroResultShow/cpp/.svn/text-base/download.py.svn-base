#!/usr/bin/python

import cookielib
import urllib
import urllib2

from user import *

url = 'http://vaquero.corp.youdao.com//image?type=img&product=ead-click&name=nc107&drawname=ead.click.status.proportion&period=hour&cubtype=ead-click_click&width=800&height=600'

passman = urllib2.HTTPPasswordMgrWithDefaultRealm()
passman.add_password(None, url, username, password)
urllib2.install_opener(urllib2.build_opener(urllib2.HTTPBasicAuthHandler(passman)))

req = urllib2.Request(url)
f = urllib2.urlopen(req)
data = f.read()
open("abc.gif", "w").write(data);
