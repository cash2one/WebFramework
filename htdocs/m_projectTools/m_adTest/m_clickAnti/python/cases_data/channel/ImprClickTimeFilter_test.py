#!/usr/bin/python
#encoding: utf-8

from ChannelTestBase import *

case_list = [
    ### NOT use configs in FILTERARG
    {
        "name": "channel_ImprClickTimeFilter_01_01",
        "desc": "该策略在FILTERARG表中未配置，展示和点击的时间差小于10s则该点击会被ImprClickTimeFilter过滤掉",
        "url": "",
        "check_type": "filtered",
        "impr_click_time_delta": 5,
    },
    {
        "name": "channel_ImprClickTimeFilter_01_02",
        "desc": "该策略在FILTERARG表中未配置，展示和点击的时间差大于10min则该点击会被ImprClickTimeFilter过滤掉",
        "url": "",
        "check_type": "filtered",
        "impr_click_time_delta": 610000,
    },
    {
        "name": "channel_ImprClickTimeFilter_01_03",
        "desc": "该策略在FILTERARG表中未配置，展示和点击的时间差介于[10s 10min]则该点击不会被ImprClickTimeFilter过滤掉",
        "url": "",
        "check_type": "not_filtered",
        "impr_click_time_delta": 410000,
    },

    ### use configs in FILTERARG, insert MIN_LIMIT
    {
        "name": "channel_ImprClickTimeFilter_02_01",
        "desc": "该策略在FILTERARG表中配置(MIN_LIMIT:20000), 展示和点击的时间差小于20s则该点击会被ImprClickTimeFilter过滤掉",
        "filterarg": ((filter_name, server_id, 20000, cycle, fkey_min_limit), ),
        "url": "",
        "check_type": "filtered",
        "impr_click_time_delta": 15000,
    },
    {
        "name": "channel_ImprClickTimeFilter_02_02",
        "desc": "该策略在FILTERARG表中配置(MIN_LIMIT:20000), 展示和点击的时间差大于10min则该点击会被ImprClickTimeFilter过滤掉",
        "filterarg": ((filter_name, server_id, 20000, cycle, fkey_min_limit), ),
        "url": "",
        "check_type": "filtered",
        "impr_click_time_delta": 610000,
    },
    {
        "name": "channel_ImprClickTimeFilter_02_03",
        "desc": "该策略在FILTERARG表中配置(MIN_LIMIT:20000), 展示和点击的时间差介于[20s, 10min]则该点击不会被ImprClickTimeFilter过滤掉",
        "filterarg": ((filter_name, server_id, 20000, cycle, fkey_min_limit), ),
        "url": "",
        "check_type": "not_filtered",
        "impr_click_time_delta": 40000,
    },


    ### use configs in FILTERARG, insert MAX_LIMIT
    {
        "name": "channel_ImprClickTimeFilter_03_01",
        "desc": "该策略在FILTERARG表中配置(MAX_LIMIT:20000), 展示和点击的时间差小于10s则该点击会被ImprClickTimeFilter过滤掉",
        "filterarg": ((filter_name, server_id, 20000, cycle, fkey_max_limit), ),
        "url": "",
        "check_type": "filtered",
        "impr_click_time_delta": 4000,
    },
    {
        "name": "channel_ImprClickTimeFilter_03_02",
        "desc": "该策略在FILTERARG表中配置(MAX_LIMIT:20000), 展示和点击的时间差大于20s则该点击会被ImprClickTimeFilter过滤掉",
        "filterarg": ((filter_name, server_id, 20000, cycle, fkey_max_limit), ),
        "url": "",
        "check_type": "filtered",
        "impr_click_time_delta": 30000,
    },
    {
        "name": "channel_ImprClickTimeFilter_03_03",
        "desc": "该策略在FILTERARG表中配置(MAX_LIMIT:20000), 展示和点击的时间差介于[10s, 20s]则该点击不会被ImprClickTimeFilter过滤掉",
        "filterarg": ((filter_name, server_id, 20000, cycle, fkey_max_limit), ),
        "url": "",
        "check_type": "not_filtered",
        "impr_click_time_delta": 15000,
    },

    ### use configs in FILTERARG, insert MIN_LIMIT, MAX_LIMIT & SYNDID:MIN_LIMIT:MAX_LIMIT
    {
        "name": "channel_ImprClickTimeFilter_04_01",
        "desc": "该策略在FILTERARG表中配置(MIN_LIMIT: 60000, MAX_LIMIT:120000), 展示和点击的时间差小于60s则该点击会被ImprClickTimeFilter过滤掉",
        "filterarg": ((filter_name, server_id, 60000, cycle, fkey_min_limit), (filter_name, server_id, 120000, cycle, fkey_max_limit), (filter_name, server_id, "XXX:180000:240000", cycle, fkey_syndid)),
        "url": "",
        "check_type": "filtered",
        "impr_click_time_delta": 50000,
    },
    {
        "name": "channel_ImprClickTimeFilter_04_02",
        "desc": "该策略在FILTERARG表中配置(MIN_LIMIT: 60000, MAX_LIMIT:120000), 展示和点击的时间差大于120s则该点击会被ImprClickTimeFilter过滤掉",
        "filterarg": ((filter_name, server_id, 60000, cycle, fkey_min_limit), (filter_name, server_id, 120000, cycle, fkey_max_limit), (filter_name, server_id, "XXX:180000:240000", cycle, fkey_syndid)),
        "url": "",
        "check_type": "filtered",
        "impr_click_time_delta": 150000,
    },
    {
        "name": "channel_ImprClickTimeFilter_04_03",
        "desc": "该策略在FILTERARG表中配置(MIN_LIMIT: 60000, MAX_LIMIT:120000), 展示和点击的时间差介于[60s, 120s]则该点击不会被ImprClickTimeFilter过滤掉",
        "filterarg": ((filter_name, server_id, 60000, cycle, fkey_min_limit), (filter_name, server_id, 120000, cycle, fkey_max_limit), (filter_name, server_id, "XXX:180000:240000", cycle, fkey_syndid)),
        "url": "",
        "check_type": "not_filtered",
        "impr_click_time_delta": 100000,
    },
    {
        "name": "channel_ImprClickTimeFilter_04_04",
        "desc": "该策略在FILTERARG表中配置(SYNDID: XXX:180000:240000), 展示和点击的时间差小于180s则该点击会被ImprClickTimeFilter过滤掉",
        "filterarg": ((filter_name, server_id, 60000, cycle, fkey_min_limit), (filter_name, server_id, 120000, cycle, fkey_max_limit), (filter_name, server_id, "XXX:180000:240000", cycle, fkey_syndid)),
        "url": "",
        "check_type": "filtered",
        "impr_click_time_delta": 170000,
    },
    {
        "name": "channel_ImprClickTimeFilter_04_05",
        "desc": "该策略在FILTERARG表中配置(SYNDID: XXX:180000:240000), 展示和点击的时间差大于240s则该点击会被ImprClickTimeFilter过滤掉",
        "filterarg": ((filter_name, server_id, 60000, cycle, fkey_min_limit), (filter_name, server_id, 120000, cycle, fkey_max_limit), (filter_name, server_id, "XXX:180000:240000", cycle, fkey_syndid)),
        "url": "",
        "check_type": "filtered",
        "impr_click_time_delta": 250000,
    },
    {
        "name": "channel_ImprClickTimeFilter_04_06",
        "desc": "该策略在FILTERARG表中配置(SYNDID: XXX:180000:240000), 展示和点击的时间差介于[180s, 240s]则该点击不会被ImprClickTimeFilter过滤掉",
        "filterarg": ((filter_name, server_id, 60000, cycle, fkey_min_limit), (filter_name, server_id, 120000, cycle, fkey_max_limit), (filter_name, server_id, "XXX:180000:240000", cycle, fkey_syndid)),
        "url": "",
        "check_type": "not_filtered",
        "impr_click_time_delta": 200000,
    },
]
