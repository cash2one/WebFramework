#!/usr/bin/python
#encoding:utf-8

from lib import *

tc = TestCase("dict IpConsistFilter test", "Click should be filtered when impr ip NOT consist with click ip")
tc.setup.clickAction.setClickerIp("192.168.1.1")
tc.setup.unchargedClicksTable.empty()
tc.setup.fraudClickTable.empty()
tc.setup.redis.empty()
tc.setup.setValidator(ClickShouldFilter("IpConsistFilter"))
tc.setup.request.addClickUrl(ClickFactory.getDictUrl())
