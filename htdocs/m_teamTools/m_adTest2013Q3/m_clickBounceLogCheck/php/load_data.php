<?php

$ret_array = Array('2013-11-04 20:32:56.694 DEBUG ClickBounceController:72 - yd_ext=EkAKATEiOwiZ454EEM3jdxjgsg4gyvQPKPNOMKCNBjigjQZlAButR3AAeKwCgAH6AZgBAaIBC1RyYWRpdGlvbmFsIhAyODgwNTJmMTFkYjY3YTU4KGkwADogOTlmYTlkOGU5MjVhNDZhZmI4YjdjN2U0OTVhNDUzM2VCAFIMMTAuMzYuMTQ2LjUxag0xMzgzMTI5MTkzMDg0eACCARVodHRwOi8vd3d3LnlvdWRpLmNvbS-IAba0BZAB_LSyx6Ao
2013-11-04 20:32:56.696 DEBUG ClickBounceController:98 - bidresult=adSlotResponse {
  id_in_page: "1"
  ad {
    variation_id: 8892825
    group_id: 1962445
    campaign_id: 235872
    sponsor_id: 260682
    algorithm_id: 10099
    cost: 100000
    actualCpc: 100000
    score: 88630.0
    cpa: 0
    width: 300
    height: 250
    rank: 1
    type: "Traditional"
  }
}
bid: "288052f11db67a58"
syndId: 105
memberId: 0
outside_cookie: "99fa9d8e925a46afb8b7c7e495a4533e"
bid_cookie: ""
ip: "10.36.146.51"
render_time: "1383129193084"
trace_enabled: false
page_url_by_exchange: "http://www.youdi.com/"
bid_price: 88630
response_time: 1383129193084

2013-11-04 20:32:56.697 DEBUG ClickBounceController:118 - newBidresult=adSlotResponse {
  id_in_page: "1"
  ad {
    variation_id: 8892825
    group_id: 1962445
    campaign_id: 235872
    sponsor_id: 260682
    algorithm_id: 10099
    cost: 100000
    actualCpc: 100000
    score: 88630.0
    cpa: 0
    width: 300
    height: 250
    landing_page: "http://www.zdytest.com"
    rank: 1
    type: "Traditional"
  }
}
bid: "288052f11db67a58"
syndId: 105
memberId: 0
outside_cookie: "99fa9d8e925a46afb8b7c7e495a4533e"
bid_cookie: ""
ip: "10.36.146.51"
render_time: "1383129193084"
trace_enabled: false
page_url_by_exchange: "http://www.youdi.com/"
bid_price: 88630
response_time: 1383129193084',

'2013-11-04 20:33:25.114  INFO ClickSenderQueue[dbUpdateThread]:56 - [SEND SUCCESS] ClickAction[sponsorId=260682, campaignId=235872, adGroupId=1962445, advId=8892825, keywordId=0, superKeyword={"BID":"288052f11db67a58"#&@!"d":"http://www.zdytest.com"#&@!"Type":"Traditional"#&@!"keyword":""#&@!"HEIGHT":"250"#&@!"User-Agent":"Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML#&@! like Gecko) Chrome/30.0.1599.101 Safari/537.36"#&@!"WIDTH":"300"#&@!"area":"0"#&@!"cac_p_all":null#&@!"s":"32"#&@!"r":"1"#&@!"CPA":"0"#&@!"POSITION_ID":"1"#&@!"BIDDER":"DSP"#&@!"cac_all":null}, syndId=105, siteId=10099, codeId=-9223372036854775808, origCost=100000, actuCost=100000, imprPos=1, qs=88630.0, rank=1.0, imprIp=10.36.146.51, imprReq=http://www.youdi.com/, imprTime=Oct 30, 2013 6:33:13 PM,nominatorId=0,comments=null, textMap={BID=288052f11db67a58, d=http://www.zdytest.com, s=32, r=1, Type=Traditional, HEIGHT=250, CPA=0, POSITION_ID=1, BIDDER=DSP, WIDTH=300}][dbId=0, clickerIp=61.135.255.83, clickerId=7760487330553003859, clickTime=Nov 4, 2013 8:33:25 PM,commitTime=Jan 1, 1970 8:00:00 AM,signature=2908107640659192099, referer=null, memberId=0]'
);

echo json_encode($ret_array);
